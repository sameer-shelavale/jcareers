<?php
/**
 * @version     1.0.0
 * @package     com_jcareers
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      sameer shelavale <samiirds@gmail.com> - https://github.com/sameer-shelavale
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

require_once JPATH_COMPONENT.'/classes/X2Form.php';
require_once JPATH_COMPONENT.'/classes/X2FormCollection.php';
require_once JPATH_COMPONENT.'/classes/X2FormElement.php';
require_once JPATH_COMPONENT.'/classes/class.dbhelper.php';
require_once JPATH_COMPONENT.'/classes/class.logger.php';


// Execute the task.
$controller	= JController::getInstance('Jcareers');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
