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

JHtml::_('behavior.tooltip');
JHTML::_('script','system/multiselect.js',false,true);
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_jcareers/assets/css/jcareers.css');

$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_jcareers');
$saveOrder	= $listOrder == 'a.ordering';

$document = JFactory::getDocument();
$style = "table.zebra { width: 100%; }

table.zebra th {
	font-size: 16px;
	font-weight: normal;
	text-align: left;
}

table.zebra th,
table.zebra td { padding: 5px; }

table.zebra tbody,
table.zebra tfoot { font-size: 12px; }

table.zebra .bold { font-weight: bold; }
table.zebra .center { text-align: center; }

table.zebra td * { vertical-align: middle; }

table.zebra tfoot { font-style: italic; }

table.zebra caption {
	font-size: {$this->params->get('tableHeaderFontSize')}px;
	text-align: left;
	font-style: italic;
}
table.zebra tbody td{border-bottom:1px solid {$this->params->get('tableRowSeperatorColor')}}
table.zebra tbody tr:first-child td{ border-top:1px solid {$this->params->get('tableRowSeperatorColor')} }
table.zebra tbody tr.odd{ background:{$this->params->get('tableOddRowBgColor')}; }
table.zebra tbody tr:nth-of-type(odd){ background-color:{$this->params->get('tableOddRowBgColor')} }
";

$document->addStyleDeclaration( $style );


$subTitleTag = 'h1';

if ($this->params->get('show_page_heading')) : 
	$subTitleTag = 'h2';
?>
	<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
<?php endif; ?>

<?php 
	$subTitle = $this->params->get('ListingPageHeader');
	if( strlen( trim( $subTitle) ) > 0 ){
		echo "<$subTitleTag>".$this->params->get('ListingPageHeader')."</$subTitleTag>";
	}
?>

<?php $content = $this->params->get('ListingPageContent');
	if( strlen( trim( $content) ) > 0 ){
		echo '<p>'.$content.'</p>';
	}
?>
<form action="<?php echo JRoute::_('index.php?option=com_jcareers&view=jobs'); ?>" method="post" name="adminForm" id="adminForm">
	<table class="zebra" width="100%">
		<thead>
			<tr>
				
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'Job Code', 'a.code', $listDirn, $listOrder); ?>
				</th>
				
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'Job Title', 'a.title', $listDirn, $listOrder); ?>
				</th>
				
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'Number Of Positions', 'a.total_positions', $listDirn, $listOrder); ?>
				</th>

				<th class='left'>
				
				</th>

                
			</tr>
		</thead>
		<tfoot>
			<?php 
                if(isset($this->items[0])){
                    $colspan = count(get_object_vars($this->items[0]));
                }
                else{
                    $colspan = 10;
                }
            ?>
			<tr>
				<td colspan="<?php echo $colspan ?>">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$ordering	= ($listOrder == 'a.ordering');
			$canCreate	= $user->authorise('core.create',		'com_jcareers');
			$canEdit	= $user->authorise('core.edit',			'com_jcareers');
			$canCheckin	= $user->authorise('core.manage',		'com_jcareers');
			$canChange	= $user->authorise('core.edit.state',	'com_jcareers');
			?>
			<tr class="row<?php echo $i % 2; ?>">
				
				<td>
					<?php echo $item->code; ?>
				</td>
				<td>
					<?php echo $this->escape($item->title); ?>
				
				</td>
				<td>
					<?php echo $item->total_positions; ?>
				</td>
				
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_jcareers&view=job&id='.$item->id ); ?>">View Details</a> 
				</td>
                
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>