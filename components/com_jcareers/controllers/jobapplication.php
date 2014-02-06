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

require_once JPATH_COMPONENT.'/controller.php';



/**
 * Job controller class.
 */
class JcareersControllerJobApplication extends JcareersController {

	

	/**
	 * Method to save a user's profile data.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function save(){
		
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app	= JFactory::getApplication();
		$params = $app->getParams();
		$model = $this->getModel('JobApplication', 'JcareersModel');
		$jobModel = $this->getModel('Job', 'JcareersModel');
		$jobId = JFactory::getApplication()->input->get('job_id', 0 );
		
		$job = $jobModel->getData( $jobId );
		
		
		$formObj = new X2Form( 'AppForm', 'xmlfile', JPATH_COMPONENT.'/models/xforms/jobapplication.xml',  false,'english', 'joomla', JFactory::getDbo()  );
		$formObj->elements['resume']->config['uploaddirectory'] = JPATH_ROOT.'/'.$params->get('resumeUploadDir');
		if( !$formObj ){
			JError::raiseError(500, "Form not found." );
			return false;
		}
		
		$log = $formObj->processSubmission( JRequest::get( 'request') );
		
		// Validate the posted data.
		if( $log['result'] != "Success" ){
			// Get the validation messages.
			$app->enqueueMessage( $formObj->errorString, 'warning');
			
			// Save the data in the session.
			$app->setUserState('com_jcareers.add.jobapplication.data', $formObj->getValues() ,array());
			$app->setUserState('com_jcareers.add.jobapplication.errorFields', $formObj->errorFields ,array());
			// Get the user data.
			
			$app->setUserState('com_jcareers.add.jobapplication.job_id', $formObj->elements['job_id']->value, 0 );
			
			$this->setRedirect(JRoute::_('index.php?option=com_jcareers&view=job&id='.$jobId, false));
			return false;
		}
		
		$data = $formObj->getValues();
		$data['id'] = 0;
		// Attempt to save the data.
		
		$return	= $model->save( $data );
		
		// Check for errors.
		if( $return === false ) {
			// Save the data in the session.
			$app->setUserState('com_jcareers.add.jobapplication.data', $data);
			$app->setUserState('com_jcareers.add.jobapplication.errorFields', null);
			$app->setUserState('com_jcareers.add.jobapplication.job_id', null );
			// Redirect back to the edit screen.
			$this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_jcareers&view=job&id='.$jobId, false ));
			return false;
		}

            
        // Clear the data from session.
        $app->setUserState( 'com_jcareers.add.application.id', null);
        $app->setUserState( 'com_jcareers.add.jobapplication.data', null);
		$app->setUserState( 'com_jcareers.add.jobapplication.errorFields', null);

		//send emails
		$emails = explode( ',', $params->get('NotificationEmails'));
		$recipient = array();
		foreach( $emails as $email ){
			$recipient[] = trim( $email );
		}
		
		if( count( $recipient)>0 ){
			//set sender
			$mailer = JFactory::getMailer();
			$config = JFactory::getConfig();
			$sender = array( 
			    $config->getValue( 'config.mailfrom' ),
			    $config->getValue( 'config.fromname' ) );
			$mailer->setSender($sender);
			
			//set receipients
			$mailer->addRecipient($recipient);
			
			$body   = $params->get('NotificationBody');
			$subject = $params->get('NotificationSubject');
			foreach( $formObj->elements as $elemName=>$elem ){
				$body = str_replace( '{'.$elemName.'}', $elem->value, $body );
				$subject = str_replace( '{'.$elemName.'}', $elem->value, $subject );
			}
			
			$body = str_replace( '{job_code}', $job->code, $body );
			$body = str_replace( '{job_title}', $job->title, $body );
			$subject = str_replace( '{job_code}', $job->code, $subject );
			$subject = str_replace( '{job_title}', $job->title, $subject );
			
			$mailer->setBody($body);
			$mailer->setSubject( $subject );
			
			// Optional file attached
			$mailer->addAttachment( $params->get('resumeUploadDir').$data['resume'] );
			
			$send = $mailer->Send();
			if ( $send !== true ) {
				$this->setMessage( 'Error sending email: ' . $send->message );
			} else {
			    $this->setMessage( 'Mail sent' );
			}
		}
        // Redirect to the list screen.
        $this->setMessage( JText::_('COM_JCAREERS_APPLICATION_SAVED_SUCCESSFULLY') );
        $menu = & JSite::getMenu();
        $item = $menu->getActive();
        $this->setRedirect( JRoute::_('index.php?option=com_jcareers', false) );

		// Flush the data from the session.
		$app->setUserState('com_jcareers.edit.job.data', null);
	}
    
    
    function cancel() {
		$menu = & JSite::getMenu();
        $item = $menu->getActive();
        $this->setRedirect(JRoute::_($item->link, false));
    }
    
}