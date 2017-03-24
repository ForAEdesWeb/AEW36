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

// no direct access
defined('_JEXEC') or die;
$currencysymb = $params->get('currency');
$numberColumn = 1;
$percent = round((100 / $numberColumn));
if ($params->get('stylecar') == 'horizontal') {
	$percentstyle = ' style="width: '. $percent .'%;"';
}else {
	$percentstyle = '';
}

?>
<?php if($params->get('numb') > $numberColumn): echo ''; endif; ?>
<div id="module-<?php echo $module->id; ?>" class="vrcmodcarscontainer vrcmodcarssingle">
<div class="vrcmodcars<?php echo $params->get('stylecar'); ?> owl-carousel">
<?php
foreach($cars as $c) {
	?>
	<div class="vrcmodcarsboxdiv clearfix">	
		<div class="vrc-car-thumb">
  		<?php
  		if(!empty($c['img'])) {
  		?>
  		<a href="<?php echo JRoute::_('index.php?option=com_vikrentcar&view=cardetails&carid='.$c['id'].'&Itemid='.$params->get('itemid')); ?>"><img src="<?php echo JURI::root(); ?>administrator/components/com_vikrentcar/resources/<?php echo $c['img']; ?>" class="vrcmodcarsimg"/></a>
  		<?php
  		}
  		?>
  		<?php
  		if($c['cost'] > 0) {
  		?>
  		<div class="vrc-car-price">
  		<span class="vrcmodcarsstartfrom"><?php echo JText::_('VRCMODCARSTARTFROM'); ?></span>
  		<span class="vrcmodcarscarcost"><?php echo $currencysymb; ?> <?php echo $c['cost']; ?></span>
  		</div>
  		<?php
  		}
  		?>

      <div class="vrcinf">
        <?php
        if($showcatname) {
        ?>
        <span class="vrcmodcarscat"><?php echo $c['catname']; ?></span>
        <?php
        }
        ?>
        <a href="<?php echo JRoute::_('index.php?option=com_vikrentcar&view=cardetails&carid='.$c['id'].'&Itemid='.$params->get('itemid')); ?>"><span class="vrcmodcarsname"><?php echo $c['name']; ?></span></a>
      </div> 
		</div>
    <?php
		if(!empty($carats)) {
			?>
			<div class="vrc-car-characteristics">
				<?php echo $carats; ?>
			</div>
			<?php
		}
		?>  
	</div>	
	<?php
}
?>
</div>
</div>

<script>
(function($){
  jQuery(document).ready(function($) {
    $("#module-<?php echo $module->id; ?>.vrcmodcarscontainer .owl-carousel").owlCarousel({
      navigation : true,
      pagination: false,
      items: <?php echo $numberColumn; ?>, 
      loop: false,
      scrollPerPage : true,
      navigationText : ["<i class='fa fa-caret-left'></i>", "<i class='fa fa-caret-right'></i>"],
      itemsDesktop : [1199, 1],
      itemsDesktopSmall : [979, 1],
      itemsTablet : [768, 1],
      itemsTabletSmall : [600, 1],
      itemsMobile : [479, 1]
    });
  });
})(jQuery);
</script>