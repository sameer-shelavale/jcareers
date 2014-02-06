<?php

/**
 * @version     1.0.0
 * @package     com_jcareers
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      sameer shelavale <samiirds@gmail.com> - https://github.com/sameer-shelavale
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit
 */
class JcareersViewJob extends JView {

    protected $state;
    protected $item;
    protected $form;
    protected $params;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        
		$app	= JFactory::getApplication();
        $user	= JFactory::getUser();
        
        $this->state = $this->get('State');
        $this->item = $this->get('Data');

        $this->params = $app->getParams('com_jcareers');
   		

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        
        
        
        if($this->_layout == 'edit') {
            
            $authorised = $user->authorise('core.create', 'com_jcareers');

            if ($authorised !== true) {
                throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
            }
        }
        
        $this->_prepareDocument();
        
        $pathway = $app->getPathway();
		$pathway->addItem( $this->params->get('ListingPageHeader'), 'index.php?option=com_jcareers&view=jobs');
        $pathway->addItem( $this->item->title, 'index.php?option=com_jcareers&view=job&id='.$this->item->id );
		
        $formObj = new X2Form( 'AppForm', 'xmlfile', JPATH_COMPONENT.'/models/xforms/jobapplication.xml',  false,'english', 'joomla', JFactory::getDbo()  );
        // load old data in case earlier form submission resulted in error
		
		if( $formData = $app->getUserState('com_jcareers.add.jobapplication.data') ){
			$formObj->setValues( $formData );
		}
    	if( $errorData = $app->getUserState('com_jcareers.add.jobapplication.errorFields') ){
			$formObj->setErrorFields( $errorData );
		}
		
    	if( !$formObj ){
			JError::raiseError(500, "Form not found." );
			return false;
		}
		
		
		if( $oldData ){
			$formObj->setValues( $oldData );
		}
		$formObj->attributes['action'] = JRoute::_('index.php?option=com_jcareers&task=jobapplication.save');
		$formObj->extraCode =  JHtml::_('form.token'); 
		$formObj->elements['job_id']->value = $this->item->id ; 
		$this->xForm = $formObj;
		
        parent::display($tpl);
    }


	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app	= JFactory::getApplication();
		$menus	= $app->getMenu();
		$title	= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('com_jcareers_DEFAULT_PAGE_TITLE'));
		}
		$title = $this->params->get('page_title', '');
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}        
    
}
