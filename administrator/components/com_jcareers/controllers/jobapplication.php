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

jimport('joomla.application.component.controllerform');

/**
 * Job controller class.
 */
class JcareersControllerJobApplication extends JControllerForm
{

    function __construct() {
        $this->view_list = 'jobapplications';
        parent::__construct();
    }

}