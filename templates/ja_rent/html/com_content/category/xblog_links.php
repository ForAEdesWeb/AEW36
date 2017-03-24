<?php
/*
 * ------------------------------------------------------------------------
 * JA Rent template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') or die;
?>


<section class="items-more">
	<h3><?php echo JText::_('COM_CONTENT_MORE_ARTICLES'); ?></h3>
	<ol class="nav">
		<?php
		foreach ($this->link_items as &$item) :
			?>
			<li>
				<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid)); ?>">
					<?php echo $item->title; ?></a>
			</li>
		<?php endforeach; ?>
	</ol>
</section>
