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

defined('_JEXEC') OR die('Restricted Area');

$res=$this->res;
$days=$this->days;
$pickup=$this->pickup;
$release=$this->release;
$place=$this->place;
$all_characteristics=$this->all_characteristics;
$navig=$this->navig;

$characteristics_map = vikrentcar::loadCharacteristics((count($all_characteristics) > 0 ? array_keys($all_characteristics) : array()));
$currencysymb = vikrentcar::getCurrencySymb();

//Filter by Characteristics
$usecharatsfilter = vikrentcar::useCharatsFilter();
if($usecharatsfilter === true && empty($navig) && count($all_characteristics) > 0) {
	$all_characteristics = vikrentcar::sortCharacteristics($all_characteristics, $characteristics_map);
	?>
<div class="vrc-searchfilter-characteristics-container">
	<div class="vrc-searchfilter-characteristics-list">
	<?php
	foreach ($all_characteristics as $chk => $chv) {
		?>
		<div class="vrc-searchfilter-characteristic">
			<span class="vrc-searchfilter-cinput"><input type="checkbox" value="<?php echo $chk; ?>" /></span>
		<?php
		if(!empty($characteristics_map[$chk]['icon'])) {
			?>
			<span class="vrc-searchfilter-cicon"><img src="<?php echo JURI::root(); ?>administrator/components/com_vikrentcar/resources/<?php echo $characteristics_map[$chk]['icon']; ?>" /></span>
			<?php
		}
		?>
			<span class="vrc-searchfilter-cname"><?php echo !empty($characteristics_map[$chk]['textimg']) ? $characteristics_map[$chk]['textimg'] : $characteristics_map[$chk]['name']; ?></span>
			<span class="vrc-searchfilter-cquantity">(<?php echo $chv; ?>)</span>
		</div>
		<?php
	}
	?>
	</div>
</div>
	<?php
}else {
	$usecharatsfilter = false;
}
//
?>
<p class="vrcarsfound"><?php echo JText::_('VRCARSFND'); ?>: <span><?php echo count($res); ?></span></p>

<div class="vrc-search-results-block">
<?php
$returnplace = JRequest::getInt('returnplace', '', 'request');
$pitemid = JRequest::getInt('Itemid', '', 'request');
$ptmpl = JRequest::getString('tmpl', '', 'request');
foreach ($res as $k => $r) {
	$getcar = vikrentcar::getCarInfo($k);
	$car_params = !empty($getcar['params']) ? json_decode($getcar['params'], true) : array();
	$carats = vikrentcar::getCarCaratOriz($getcar['idcarat'], $characteristics_map);
	$imgpath = file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikrentcar'.DS.'resources'.DS.'vthumb_'.$getcar['img']) ? 'administrator/components/com_vikrentcar/resources/vthumb_'.$getcar['img'] : 'administrator/components/com_vikrentcar/resources/'.$getcar['img'];
	$vcategory = vikrentcar::sayCategory($getcar['idcat']);
	$has_promotion = array_key_exists('promotion', $r[0]) ? true : false;
	$car_cost = vikrentcar::sayCostPlusIva($r[0]['cost'], $r[0]['idprice']);
	?>
	<div class="car_result row<?php echo $has_promotion === true ? ' vrc-promotion-price' : ''; ?>" data-car-characteristics="<?php echo $getcar['idcarat']; ?>">

		<!-- Begin: Car thumbnail -->
		<div class="vrc-car-thumb col-md-4">
			<img class="imgresult" alt="<?php echo $getcar['name']; ?>" src="<?php echo JURI::root().$imgpath; ?>" />

			<div class="vrc-car-price">
				<div class="vrcsrowpricediv">
					<span class="vrcstartfrom"><?php echo JText::_('VRSTARTFROM'); ?></span>
					<span class="car_cost"><span class="vrc_currency"><?php echo $currencysymb; ?></span> <span class="vrc_price"><?php echo vikrentcar::numberFormat($car_cost); ?></span></span>
				</div>

				<?php if($car_params['sdailycost'] == 1 && $days > 1) { 
					$costperday = $car_cost / $days;
				?>
				<div class="vrc-car-result-dailycost">
					<span class="vrc_currency"><?php echo $currencysymb; ?></span>
					<span class="vrc_price"><?php echo vikrentcar::numberFormat($costperday); ?></span>
					<span class="vrc-perday-txt"><?php echo JText::_('VRCPERDAYCOST'); ?></span>
				</div>
				<?php } ?>

			</div>
		</div>
		<!-- End: Car thumbnail -->

		<div class="vrc-car-result-info col-md-8">

			<div class="inner">
				<h3 class="vrc-car-name"><?php echo $vcategory; ?><?php echo strlen($vcategory) > 0 ? ':' : ''; ?> <?php echo $getcar['name']; ?></h3>
				<div class="vrc-car-result-description">
				<?php
					if(!empty($getcar['short_info'])) {
						echo $getcar['short_info'];
					} else {
						echo (strlen(strip_tags($getcar['info'])) > 250 ? substr(strip_tags($getcar['info']), 0, 250).' ...' : $getcar['info']);
					}
				?>
				</div>

				<?php if($has_promotion === true && !empty($r[0]['promotion']['promotxt'])) { ?>
				<div class="vrc-promotion-block">
					<?php echo $r[0]['promotion']['promotxt']; ?>
				</div>
				<?php } ?>

				<div class="vrc-car-bookingbtn">
					<form action="<?php echo JRoute::_('index.php?option=com_vikrentcar'); ?>" method="get">
						<input type="hidden" name="option" value="com_vikrentcar"/>
			  			<input type="hidden" name="caropt" value="<?php echo $k; ?>"/>
			  			<input type="hidden" name="days" value="<?php echo $days; ?>"/>
			  			<input type="hidden" name="pickup" value="<?php echo $pickup; ?>"/>
			  			<input type="hidden" name="release" value="<?php echo $release; ?>"/>
			  			<input type="hidden" name="place" value="<?php echo $place; ?>"/>
			  			<input type="hidden" name="returnplace" value="<?php echo $returnplace; ?>"/>
			  			<input type="hidden" name="task" value="showprc"/>
			  			<input type="hidden" name="Itemid" value="<?php echo $pitemid; ?>"/>
			  		<?php
			  		if($ptmpl == 'component') {
						echo "<input type=\"hidden\" name=\"tmpl\" value=\"component\"/>\n";
					}
			  		?>
						<input type="submit" name="goon" value="<?php echo JText::_('VRPROSEGUI'); ?>" class="booknow"/>
					</form>
				</div>				

			</div>

		</div>
		<!-- End: Car info -->

		<?php if(!empty($carats)) { ?>
		<div class="vrc-car-characteristics">
			<?php echo $carats; ?>
		</div>
		<?php } ?>
	</div>

	<?php } ?>
	<div class="goback">
		<a href="<?php echo JRoute::_('index.php?option=com_vikrentcar&view=vikrentcar&pickup='.$pickup.'&return='.$release); ?>"><i class="fa fa-calendar"></i><?php echo JText::_('VRCHANGEDATES'); ?></a>
	</div>
</div>
<?php

//pagination
if(strlen($navig) > 0) {
	?>
<div class="vrc-pagination"><?php echo $navig; ?></div>
	<?php
}

?>
<script type="text/javascript">
jQuery(document).ready(function() {
	if (jQuery('.car_result').length) {
		jQuery('.car_result').each(function() {
			var car_img = jQuery(this).find('.vrc-car-result-left').find('img');
			if(car_img.length) {
				jQuery(this).find('.vrc-car-result-right').find('.vrc-car-result-rightinner').find('.vrc-car-result-rightinner-deep').find('.vrc-car-result-inner').css('min-height', car_img.height()+'px');
			}
		});
	};
<?php
if($usecharatsfilter === true) {
	?>
	jQuery('.vrc-searchfilter-cinput input').click(function() {
		var charact_id = jQuery(this).val();
		var charact_el = jQuery(this).parent('span').parent('div');
		var dofilter = jQuery(this)[0].checked;
		var cur_result = parseInt(jQuery('.vrcarsfound span').text());
		jQuery('.car_result').each(function() {
			var car_carats = jQuery(this).attr('data-car-characteristics').replace(/;+$/,'').split(';');
			if(dofilter === true && jQuery.inArray(charact_id, car_carats) === -1) {
				jQuery(this).fadeOut();
				cur_result--;
			}else if(dofilter === false && jQuery.inArray(charact_id, car_carats) === -1) {
				jQuery(this).fadeIn();
				cur_result++;
			}
		});
		jQuery('.vrcarsfound span').text(cur_result);
		(dofilter === true ? charact_el.addClass('vrc-searchfilter-characteristic-active') : charact_el.removeClass('vrc-searchfilter-characteristic-active'));
	});
	jQuery('.vrc-searchfilter-cicon, .vrc-searchfilter-cname, .vrc-searchfilter-cquantity').click(function(){
		jQuery(this).closest('.vrc-searchfilter-characteristic').find('.vrc-searchfilter-cinput').find('input').trigger('click');
	});
	<?php
}
?>
});
</script>