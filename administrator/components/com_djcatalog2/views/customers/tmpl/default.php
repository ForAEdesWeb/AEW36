<?php
/**
 * @version $Id: default.php 272 2014-05-21 10:25:49Z michal $
 * @package DJ-Catalog2
 * @copyright Copyright (C) 2012 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Michal Olczyk - michal.olczyk@design-joomla.eu
 *
 * DJ-Catalog2 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-Catalog2 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-Catalog2. If not, see <http://www.gnu.org/licenses/>.
 *
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
?>
<form action="<?php echo JRoute::_('index.php?option=com_djcatalog2&view=customers');?>" method="post" name="adminForm" id="adminForm">
	<?php if(!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif;?>
	
	<div id="filter-bar" class="btn-toolbar">
		<div class="filter-search btn-group pull-left">
			<label class="element-invisible" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />
		</div>
		<div class="btn-group pull-left">
			<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" class="btn" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="btn-group pull-right hidden-phone">
			<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
	</div>
	<div class="clearfix"> </div>

	<table class="table table-striped">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
				</th>
				<th class="left">
					<?php echo JHtml::_('grid.sort', 'COM_DJCATALOG2_JUSER', 'u.username', $listDirn, $listOrder); ?>
				</th>
				<th width="15%">
					<?php echo JHtml::_('grid.sort', 'COM_DJCATALOG2_EMAIL', 'u.email', $listDirn, $listOrder); ?>
				</th>
				<th colspan="5" width="50%">
					<?php echo JText::_('COM_DJCATALOG2_BILLING_DETAILS'); ?>
				</th>
				<th width="1%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'u.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="9">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php 
		foreach ($this->items as $i => $item) :
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, (int)$item->_user_id.','.(int)$item->id); ?>
				</td>
				<td>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_DJCATALOG2_EDIT_TOOLTIP' );?>::<?php echo $this->escape($item->username); ?>">
						<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id='.$item->_user_id);?>">
							<?php echo $this->escape($item->username); ?></a>
					</span>
					
					<?php /*?>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_DJCATALOG2_EDIT_TOOLTIP' );?>::<?php echo $this->escape($item->username); ?>">
						<a href="<?php echo JRoute::_('index.php?option=com_djcatalog2&task=customer.edit&cid[]='.(int)$item->_user_id.','.(empty($item->id) ? 0 : (int)$item->id));  ?>">
							<?php echo $item->username; ?></a>
					</span>
					<?php */ ?>
					
					<p class="smallsub">
						<?php echo $this->escape($item->name); ?>
					</p>
				</td>
				
				<?php /* ?>
				<td class="center">
					
					[<a href="<?php echo JRoute::_('index.php?option=com_djcatalog2&task=customer.edit&cid[]='.(int)$item->_user_id.','.(empty($item->id) ? 0 : (int)$item->id));  ?>"><?php echo JText::_('COM_DJCATALOG2_EDIT_PROFILE'); ?></a>]
					[<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id='.$item->_user_id);?>"><?php echo JText::_('COM_DJCATALOG2_EDIT_USER'); ?></a>]
				</td>
				<?php */ ?>
				
				<td class="center">
					<a href="mailto:<?php echo $this->escape($item->email); ?>"><?php echo $this->escape($item->email); ?></a>
				</td>
				
				<td class="center">
					<?php if ($item->id) { ?>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_DJCATALOG2_EDIT_TOOLTIP' );?>::<?php echo $this->escape($item->username); ?>">
						<a href="<?php echo JRoute::_('index.php?option=com_djcatalog2&task=customer.edit&cid[]='.(int)$item->_user_id.','.(int)$item->id); ?>">
							<?php echo $item->firstname.' '.$item->lastname; ?></a>
					</span>
					<?php } ?>
				</td>
				<td class="center">
					<?php echo $item->company; ?>
				</td>
				<td class="center">
					<?php echo $item->city ? $item->postcode.' '.$item->city: ''; ?>
				</td>
				<td class="center">
					<?php echo $item->address;?>
				</td>
				<td class="center">
					<?php echo $item->country_name; ?>
				</td>
				
				<td class="center">
					<?php echo (int) $item->_user_id; ?>
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
	</div>
</form>

