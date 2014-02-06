<?php
/**
 * @version     1.0.0
 * @package     com_jcareers
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      sameer shelavale <samiirds@gmail.com> - https://github.com/sameer-shelavale
 */
// no direct access
defined('_JEXEC') or die;

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_jcareers', JPATH_ADMINISTRATOR);

$document = JFactory::getDocument();
$style = "
div#jobDescription{ width:49%; float:left; }
div#jobApplicationForm{ padding-left:1%; width:49%; float:left; }
.clear{clear:both;}
.mandatory{color:#ff0000;}
";

$document->addStyleDeclaration( $style );

 	
	if ($this->item) : ?>
	<h1>
	<?php echo $this->item->title; ?>
	</h1>
    <div class="item_fields" id="jobDescription">
    	<div><?php echo '<b>'.JText::_('COM_JCAREERS_FORM_LBL_JOB_CODE'), '</b> : '. $this->item->code; ?><br/>
    	<?php echo '<b>'.JText::_('COM_JCAREERS_FORM_LBL_JOB_TOTAL_POSITIONS'), '</b> : '. $this->item->total_positions; ?></div>
    	<p><?php echo '<b>'.JText::_('COM_JCAREERS_FORM_LBL_JOB_DESCRIPTION'), '</b> : <br/>'. $this->item->description; ?></p>
    	
    </div>
    <div id="jobApplicationForm">
    	<h3 style="margin-top:0;">Submit your application for this job</h3>
    	<fieldset>
    	 <?php echo $this->xForm->render(); ?>
    	 </fieldset>
    </div>
    <div class="clear"></div>
<?php
else:
    echo JText::_('COM_JCAREERS_ITEM_NOT_LOADED');
endif;
?>
