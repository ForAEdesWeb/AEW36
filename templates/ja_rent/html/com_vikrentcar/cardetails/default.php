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

$car = $this->car;
$busy = $this->busy;

//load jQuery lib and navigation
$document = JFactory::getDocument();
if(vikrentcar::loadJquery()) {
	JHtml::_('jquery.framework', true, true);
	JHtml::_('script', JURI::root().'components/com_vikrentcar/resources/jquery-1.11.1.min.js', false, true, false, false);
}
$document->addStyleSheet(JURI::root().'components/com_vikrentcar/resources/jquery.fancybox.css');
JHtml::_('script', JURI::root().'components/com_vikrentcar/resources/jquery.fancybox.js', false, true, false, false);
$navdecl = '
jQuery.noConflict();
jQuery(document).ready(function() {
	jQuery(".vrcmodal").fancybox({
		"helpers": {
			"overlay": {
				"locked": false
			}
		},"padding": 0
	});
	jQuery(".vrcmodalframe").fancybox({
		"helpers": {
			"overlay": {
				"locked": false
			}
		},
		"width": "75%",
		"height": "75%",
	    "autoScale": false,
	    "transitionIn": "none",
		"transitionOut": "none",
		"padding": 0,
		"type": "iframe"
	});
});';
$document->addScriptDeclaration($navdecl);
//

$currencysymb = vikrentcar::getCurrencySymb();
$showpartlyres = vikrentcar::showPartlyReserved();
$numcalendars = vikrentcar::numCalendars();
$carats = vikrentcar::getCarCaratOriz($car['idcarat']);
?>

<div class="car-detail">

<div class="row">
	<div class="col-md-8">

		<?php
		if (!empty ($car['img'])) {
			$imgpath = file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikrentcar'.DS.'resources'.DS.'vthumb_'.$car['img']) ? 'administrator/components/com_vikrentcar/resources/'.$car['img'] : 'administrator/components/com_vikrentcar/resources/'.$car['img'];
			?>
			<div class="vrc-cdetails-cmainimg">
				<img src="<?php echo JURI::root().$imgpath; ?>" alt="<?php echo JText::_('VRCLISTSFROM'); ?>" />
			</div>
		<?php } ?>

		<?php
		if(strlen($car['moreimgs']) > 0) {
			$moreimages = explode(';;', $car['moreimgs']);
			?>
			<div class="cardetails_moreimages">
			<?php
			foreach($moreimages as $mimg) {
				if(!empty($mimg)) {
				?>
				<a href="<?php echo JURI::root(); ?>administrator/components/com_vikrentcar/resources/big_<?php echo $mimg; ?>" rel="vrcgroup<?php echo $car['id']; ?>" target="_blank" class="vrcmodal"><img src="<?php echo JURI::root(); ?>administrator/components/com_vikrentcar/resources/thumb_<?php echo $mimg; ?>"/></a>
				<?php
				}
			}
			?>
			</div>
		<?php } ?>

	</div>

	<div class="col-md-4">
		<div class="vrclistcarcat"><span><?php echo vikrentcar::sayCategory($car['idcat']); ?></span></div>

		<div class="vrc-cdetails-cgroup"><h1><?php echo $car['name']; ?></h1></div>

		<?php if ($car['cost'] > 0) { ?>
		<div class="vrc-cdetails-cost">
			<span class="vrcliststartfrom"><?php echo JText::_('VRCLISTSFROM'); ?></span>
			<span class="car_cost"><span class="vrc_currency"><?php echo $currencysymb; ?></span> <span class="vrc_price"><?php echo strlen($car['startfrom']) > 0 ? vikrentcar::numberFormat($car['startfrom']) : vikrentcar::numberFormat($car['cost']); ?></span></span>
		</div>
		<?php } ?>

		<?php if (!empty($carats)) { ?>
		<div class="vrc-car-carats">
			<?php echo $carats; ?>
		</div>
		<?php } ?>

	</div>
</div>

	<div class="vrc-cdetails-cardesc">
	<?php
	//BEGIN: Joomla Content Plugins Rendering
	JPluginHelper::importPlugin('content');
	$myItem =JTable::getInstance('content');
	$dispatcher =JDispatcher::getInstance();
	$myItem->text = $car['info'];
	$dispatcher->trigger('onContentPrepare', array('com_vikrentcar.cardetails', &$myItem, &$params, 0));
	$car['info'] = $myItem->text;
	//END: Joomla Content Plugins Rendering
	echo $car['info'];
	?>
	</div>


<?php

$pmonth = JRequest :: getInt('month', '', 'request');
$arr=getdate();
$mon=$arr['mon'];
$realmon=($mon < 10 ? "0".$mon : $mon);
$year=$arr['year'];
$day=$realmon."/01/".$year;
$dayts=strtotime($day);
$validmonth=false;
if($pmonth > 0 && $pmonth >= $dayts) {
	$validmonth=true;
}
$moptions="";
for($i=0; $i < 12; $i++) {
	$moptions.="<option value=\"".$dayts."\"".($validmonth && $pmonth == $dayts ? " selected=\"selected\"" : "").">".vikrentcar::sayMonth($arr['mon'])." ".$arr['year']."</option>\n";
	$next=$arr['mon'] + 1;
	$dayts=mktime(0, 0, 0, ($next < 10 ? "0".$next : $next), 01, $arr['year']);
	$arr=getdate($dayts);
}

if($numcalendars > 0) {
?>
<div class="vrcdetsep"></div>

<form action="<?php echo JRoute::_('index.php?option=com_vikrentcar&view=cardetails&carid='.$car['id']); ?>" method="post" name="vrcmonths">
<select name="month" onchange="javascript: document.vrcmonths.submit();" class="vrcselectm"><?php echo $moptions; ?></select>
</form>

<div class="vrcdetsep"></div>  

<div class="vrclegendediv">

	<span class="vrclegenda"><div class="vrclegfree">&nbsp;</div> <?php echo JText::_('VRLEGFREE'); ?></span>
	<?php
	if($showpartlyres) {
	?>
	<span class="vrclegenda"><div class="vrclegwarning">&nbsp;</div> <?php echo JText::_('VRLEGWARNING'); ?></span>
	<?php
	}
	?>
	<span class="vrclegenda"><div class="vrclegbusy">&nbsp;</div> <?php echo JText::_('VRLEGBUSY'); ?></span>
	
</div>
<?php
}
?>

<div class="vrcdetsep"></div>

<?php
$check=false;
if(@is_array($busy)) {
	$check=true;
}
if($validmonth) {
	$arr=getdate($pmonth);
	$mon=$arr['mon'];
	$realmon=($mon < 10 ? "0".$mon : $mon);
	$year=$arr['year'];
	$day=$realmon."/01/".$year;
	$dayts=strtotime($day);
	$newarr=getdate($dayts);
}else {
	$arr=getdate();
	$mon=$arr['mon'];
	$realmon=($mon < 10 ? "0".$mon : $mon);
	$year=$arr['year'];
	$day=$realmon."/01/".$year;
	$dayts=strtotime($day);
	$newarr=getdate($dayts);
}

$firstwday = (int)vikrentcar::getFirstWeekDay();
$days_labels = array(
	JText::_('VRSUN'),
	JText::_('VRMON'),
	JText::_('VRTUE'),
	JText::_('VRWED'),
	JText::_('VRTHU'),
	JText::_('VRFRI'),
	JText::_('VRSAT')
);
$days_indexes = array();
for( $i = 0; $i < 7; $i++ ) {
	$days_indexes[$i] = (6-($firstwday-$i)+1)%7;
}

$push_disabled_in = array();
$push_disabled_out = array();
$previousdayclass="";

for($jj = 1; $jj <= $numcalendars; $jj++) {
	$d_count = 0;
	$cal="";
	?>
	<div class="vrccaldivcont">
	<table class="vrccal">
	<tr><td colspan="7" align="center"><strong><?php echo vikrentcar::sayMonth($newarr['mon'])." ".$newarr['year']; ?></strong></td></tr>
	<tr class="vrccaldays">
	<?php
	for($i = 0; $i < 7; $i++) {
		$d_ind = ($i + $firstwday) < 7 ? ($i + $firstwday) : ($i + $firstwday - 7);
		echo '<td>'.$days_labels[$d_ind].'</td>';
	}
	?>
	</tr>
	<tr>
	<?php
	for($i=0, $n = $days_indexes[$newarr['wday']]; $i < $n; $i++, $d_count++) {
		$cal.="<td align=\"center\">&nbsp;</td>";
	}
	while ($newarr['mon']==$mon) {
		if($d_count > 6) {
			$d_count = 0;
			$cal.="</tr>\n<tr>";
		}
		$dclass="vrctdfree";
		$dalt="";
		$bid="";
		if ($check) {
			$totfound=0;
			$ischeckinday = false;
			$ischeckoutday = false;
			foreach($busy as $b){
				$tmpone=getdate($b['ritiro']);
				$rit=($tmpone['mon'] < 10 ? "0".$tmpone['mon'] : $tmpone['mon'])."/".($tmpone['mday'] < 10 ? "0".$tmpone['mday'] : $tmpone['mday'])."/".$tmpone['year'];
				$ritts=strtotime($rit);
				$tmptwo=getdate($b['consegna']);
				$con=($tmptwo['mon'] < 10 ? "0".$tmptwo['mon'] : $tmptwo['mon'])."/".($tmptwo['mday'] < 10 ? "0".$tmptwo['mday'] : $tmptwo['mday'])."/".$tmptwo['year'];
				$conts=strtotime($con);
				if ($newarr[0]>=$ritts && $newarr[0]<=$conts) {
					$totfound++;
					if($newarr[0] == $ritts) {
						$ischeckinday = true;
					}elseif($newarr[0] == $conts) {
						$ischeckoutday = true;
					}
					if($b['stop_sales'] == 1) {
						$totfound = $car['units'];
						break;
					}
				}
			}
			if($totfound >= $car['units']) {
				$dclass="vrctdbusy";
				if($ischeckinday || !$ischeckoutday) {
					$push_disabled_in[] = '"'.date('Y-m-d', $newarr[0]).'"';
				}
				if ($ischeckinday && $previousdayclass != "vrctdbusy") {
					$dclass="vrctdbusy vrctdbusyforcheckin";
				}
				if(!$ischeckinday && !$ischeckoutday) {
					$push_disabled_out[] = '"'.date('Y-m-d', $newarr[0]).'"';
				}
			}elseif($totfound > 0) {
				if($showpartlyres) {
					$dclass="vrctdwarning";
				}
			}
		}
		$previousdayclass=$dclass;
		$useday=($newarr['mday'] < 10 ? "0".$newarr['mday'] : $newarr['mday']);
		if($totfound == 1) {
			$cal.="<td align=\"center\" class=\"".$dclass."\">".$useday."</td>\n";
		}elseif($totfound > 1) {
			$cal.="<td align=\"center\" class=\"".$dclass."\">".$useday."</td>\n";
		}else {
			$cal.="<td align=\"center\" class=\"".$dclass."\">".$useday."</td>\n";
		}
		$next=$newarr['mday'] + 1;
		$dayts=mktime(0, 0, 0, ($newarr['mon'] < 10 ? "0".$newarr['mon'] : $newarr['mon']), ($next < 10 ? "0".$next : $next), $newarr['year']);
		$newarr=getdate($dayts);
		$d_count++;
	}
	
	for($i=$d_count; $i <= 6; $i++){
		$cal.="<td align=\"center\">&nbsp;</td>";
	}
	
	echo $cal;
	?>
	</tr>
	</table>
	</div>
	<?php
	if ($mon==12) {
		$mon=1;
		$year+=1;
		$dayts=mktime(0, 0, 0, ($mon < 10 ? "0".$mon : $mon), 01, $year);
	}else {
		$mon+=1;
		$dayts=mktime(0, 0, 0, ($mon < 10 ? "0".$mon : $mon), 01, $year);
	}
	$newarr=getdate($dayts);
	
	if (($jj % 3)==0) {
		echo "";
	}
}

?>

<div class="vrcdetsep"></div>

<h3><?php echo JText::_('VRCSELECTPDDATES'); ?></h3>

<?php

if (vikrentcar :: allowRent()) {
	$dbo = JFactory :: getDBO();
	//vikrentcar 1.5
	$calendartype = vikrentcar::calendarType();
	$document = JFactory :: getDocument();
	//load jQuery lib e jQuery UI
	if(vikrentcar::loadJquery()) {
		JHtml::_('jquery.framework', true, true);
		JHtml::_('script', JURI::root().'components/com_vikrentcar/resources/jquery-1.11.1.min.js', false, true, false, false);
	}
	if($calendartype == "jqueryui") {
		$document->addStyleSheet(JURI::root().'components/com_vikrentcar/resources/jquery-ui.min.css');
		//load jQuery UI
		JHtml::_('script', JURI::root().'components/com_vikrentcar/resources/jquery-ui.min.js', false, true, false, false);
	}
	//
	$pitemid = JRequest :: getInt('Itemid', '', 'request');
	$ptmpl = JRequest :: getString('tmpl', '', 'request');
	$ppickup = JRequest :: getInt('pickup', 0, 'request');
	$ppromo = JRequest :: getInt('promo', 0, 'request');
	$vrcdateformat = vikrentcar::getDateFormat();
	if ($vrcdateformat == "%d/%m/%Y") {
		$df = 'd/m/Y';
	}elseif ($vrcdateformat == "%m/%d/%Y") {
		$df = 'm/d/Y';
	}else {
		$df = 'Y/m/d';
	}
	$coordsplaces = array();
	$selform = "<div class=\"vrcdivsearch\"><form action=\"".JRoute::_('index.php?option=com_vikrentcar')."\" method=\"get\">\n";
	$selform .= "<input type=\"hidden\" name=\"option\" value=\"com_vikrentcar\"/>\n";
	$selform .= "<input type=\"hidden\" name=\"task\" value=\"search\"/>\n";
	$selform .= "<input type=\"hidden\" name=\"cardetail\" value=\"".$car['id']."\"/>\n";
	if($ptmpl == 'component') {
		$selform .= "<input type=\"hidden\" name=\"tmpl\" value=\"component\"/>\n";
	}
	$diffopentime = false;
	if (vikrentcar :: showPlacesFront()) {
		$q = "SELECT * FROM `#__vikrentcar_places` ORDER BY `#__vikrentcar_places`.`ordering` ASC, `#__vikrentcar_places`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$places = $dbo->loadAssocList();
			$plapick = explode(';', $car['idplace']);
			$pladrop = explode(';', $car['idretplace']);
			foreach ($places as $kpla=>$pla) {
				if (!in_array($pla['id'], $plapick) && !in_array($pla['id'], $pladrop)) {
					unset($places[$kpla]);
				}
			}
			if (count($places) == 0) {
				$places = '';
			}
		}else {
			$places = '';
		}
		if (is_array($places)) {
			//check if some place has a different opening time (1.6)
			foreach ($places as $pla) {
				if(!empty($pla['opentime'])) {
					$diffopentime = true;
					break;
				}
			}
			$onchangeplaces = $diffopentime == true ? " onchange=\"javascript: vrcSetLocOpenTime(this.value, 'pickup');\"" : "";
			$onchangeplacesdrop = $diffopentime == true ? " onchange=\"javascript: vrcSetLocOpenTime(this.value, 'dropoff');\"" : "";
			if($diffopentime == true) {
				$onchangedecl = '
jQuery.noConflict();
var vrc_location_change = false;
function vrcSetLocOpenTime(loc, where) {
	if(where == "dropoff") {
		vrc_location_change = true;
	}
	jQuery.ajax({
		type: "POST",
		url: "'.JRoute::_('index.php?option=com_vikrentcar&task=ajaxlocopentime&tmpl=component').'",
		data: { idloc: loc, pickdrop: where }
	}).done(function(res) {
		var vrcobj = jQuery.parseJSON(res);
		if(where == "pickup") {
			jQuery("#vrccomselph").html(vrcobj.hours);
			jQuery("#vrccomselpm").html(vrcobj.minutes);
		}else {
			jQuery("#vrccomseldh").html(vrcobj.hours);
			jQuery("#vrccomseldm").html(vrcobj.minutes);
		}
		if(where == "pickup" && vrc_location_change === false) {
			jQuery("#returnplace").val(loc).trigger("change");
			vrc_location_change = false;
		}
	});
}';
				$document->addScriptDeclaration($onchangedecl);
			}
			//end check if some place has a different opningtime (1.6)
			$selform .= "<div class=\"vrcsfentrycont\"><label>" . JText :: _('VRPPLACE') . "</label><div class=\"vrcsfentryselect\"><select name=\"place\" id=\"place\"".$onchangeplaces.">";
			foreach ($places as $pla) {
				$selform .= "<option value=\"" . $pla['id'] . "\" id=\"place".$pla['id']."\">" . $pla['name'] . "</option>\n";
				if(!empty($pla['lat']) && !empty($pla['lng'])) {
					$coordsplaces[] = $pla;
				}
			}
			$selform .= "</select></div></div>\n";
		}
	}
	
	if($diffopentime == true && is_array($places) && strlen($places[0]['opentime']) > 0) {
		$parts = explode("-", $places[0]['opentime']);
		if (is_array($parts) && $parts[0] != $parts[1]) {
			$opent = vikrentcar :: getHoursMinutes($parts[0]);
			$closet = vikrentcar :: getHoursMinutes($parts[1]);
			$i = $opent[0];
			$imin = $opent[1];
			$j = $closet[0];
		} else {
			$i = 0;
			$imin = 0;
			$j = 23;
		}
	}else {
		$timeopst = vikrentcar :: getTimeOpenStore();
		if (is_array($timeopst) && $timeopst[0] != $timeopst[1]) {
			$opent = vikrentcar :: getHoursMinutes($timeopst[0]);
			$closet = vikrentcar :: getHoursMinutes($timeopst[1]);
			$i = $opent[0];
			$imin = $opent[1];
			$j = $closet[0];
		} else {
			$i = 0;
			$imin = 0;
			$j = 23;
		}
	}
	$hours = "";
	//VRC 1.9
	$pickhdeftime = !empty($places[0]['defaulttime']) ? ((int)$places[0]['defaulttime'] / 3600) : '';
	if(!($i < $j)) {
		while (intval($i) != (int)$j) {
			if ($i < 10) {
				$i = "0" . $i;
			} else {
				$i = $i;
			}
			$hours .= "<option value=\"" . $i . "\"".($pickhdeftime == (int)$i ? ' selected="selected"' : '').">" . $i . "</option>\n";
			$i++;
			$i = $i > 23 ? 0 : $i;
		}
		$i = $i < 10 ? "0" . $i : $i;
		$hours .= "<option value=\"" . $i . "\">" . $i . "</option>\n";
	}else {
		while ($i <= $j) {
			if ($i < 10) {
				$i = "0" . $i;
			} else {
				$i = $i;
			}
			$hours .= "<option value=\"" . $i . "\"".($pickhdeftime == (int)$i ? ' selected="selected"' : '').">" . $i . "</option>\n";
			$i++;
		}
	}
	//
	$minutes = "";
	for ($i = 0; $i < 60; $i += 15) {
		if ($i < 10) {
			$i = "0" . $i;
		} else {
			$i = $i;
		}
		$minutes .= "<option value=\"" . $i . "\"".((int)$i == $imin ? " selected=\"selected\"" : "").">" . $i . "</option>\n";
	}
	
	//vikrentcar 1.5
	if($calendartype == "jqueryui") {
		if ($vrcdateformat == "%d/%m/%Y") {
			$juidf = 'dd/mm/yy';
		}elseif ($vrcdateformat == "%m/%d/%Y") {
			$juidf = 'mm/dd/yy';
		}else {
			$juidf = 'yy/mm/dd';
		}
		//lang for jQuery UI Calendar
		$ldecl = '
jQuery(function($){'."\n".'
	$.datepicker.regional["vikrentcar"] = {'."\n".'
		closeText: "'.JText::_('VRCJQCALDONE').'",'."\n".'
		prevText: "'.JText::_('VRCJQCALPREV').'",'."\n".'
		nextText: "'.JText::_('VRCJQCALNEXT').'",'."\n".'
		currentText: "'.JText::_('VRCJQCALTODAY').'",'."\n".'
		monthNames: ["'.JText::_('VRMONTHONE').'","'.JText::_('VRMONTHTWO').'","'.JText::_('VRMONTHTHREE').'","'.JText::_('VRMONTHFOUR').'","'.JText::_('VRMONTHFIVE').'","'.JText::_('VRMONTHSIX').'","'.JText::_('VRMONTHSEVEN').'","'.JText::_('VRMONTHEIGHT').'","'.JText::_('VRMONTHNINE').'","'.JText::_('VRMONTHTEN').'","'.JText::_('VRMONTHELEVEN').'","'.JText::_('VRMONTHTWELVE').'"],'."\n".'
		monthNamesShort: ["'.mb_substr(JText::_('VRMONTHONE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VRMONTHTWO'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VRMONTHTHREE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VRMONTHFOUR'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VRMONTHFIVE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VRMONTHSIX'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VRMONTHSEVEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VRMONTHEIGHT'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VRMONTHNINE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VRMONTHTEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VRMONTHELEVEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::_('VRMONTHTWELVE'), 0, 3, 'UTF-8').'"],'."\n".'
		dayNames: ["'.JText::_('VRCJQCALSUN').'", "'.JText::_('VRCJQCALMON').'", "'.JText::_('VRCJQCALTUE').'", "'.JText::_('VRCJQCALWED').'", "'.JText::_('VRCJQCALTHU').'", "'.JText::_('VRCJQCALFRI').'", "'.JText::_('VRCJQCALSAT').'"],'."\n".'
		dayNamesShort: ["'.mb_substr(JText::_('VRCJQCALSUN'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::_('VRCJQCALMON'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::_('VRCJQCALTUE'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::_('VRCJQCALWED'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::_('VRCJQCALTHU'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::_('VRCJQCALFRI'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::_('VRCJQCALSAT'), 0, 3, 'UTF-8').'"],'."\n".'
		dayNamesMin: ["'.mb_substr(JText::_('VRCJQCALSUN'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::_('VRCJQCALMON'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::_('VRCJQCALTUE'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::_('VRCJQCALWED'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::_('VRCJQCALTHU'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::_('VRCJQCALFRI'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::_('VRCJQCALSAT'), 0, 2, 'UTF-8').'"],'."\n".'
		weekHeader: "'.JText::_('VRCJQCALWKHEADER').'",'."\n".'
		dateFormat: "'.$juidf.'",'."\n".'
		firstDay: '.vikrentcar::getFirstWeekDay().','."\n".'
		isRTL: false,'."\n".'
		showMonthAfterYear: false,'."\n".'
		yearSuffix: ""'."\n".'
	};'."\n".'
	$.datepicker.setDefaults($.datepicker.regional["vikrentcar"]);'."\n".'
});';
		$document->addScriptDeclaration($ldecl);
		//
		//Minimum Num of Days of Rental (VRC 1.8)
		$dropdayplus = vikrentcar::setDropDatePlus();
		$forcedropday = "jQuery('#releasedate').datepicker( 'option', 'minDate', selectedDate );";
		if (strlen($dropdayplus) > 0 && intval($dropdayplus) > 0) {
			$forcedropday = "
var vrcdate = jQuery(this).datepicker('getDate');
if(vrcdate) {
	vrcdate.setDate(vrcdate.getDate() + ".$dropdayplus.");
	jQuery('#releasedate').datepicker( 'option', 'minDate', vrcdate );
	jQuery('#releasedate').val(jQuery.datepicker.formatDate('".$juidf."', vrcdate));
}";
		}
		//
		$sdecl = "
jQuery.noConflict();
var vrc_fulldays_in = [".implode(', ', $push_disabled_in)."];
var vrc_fulldays_out = [".implode(', ', $push_disabled_out)."];
function vrcIsDayFullIn(date) {
	var actd = jQuery.datepicker.formatDate('yy-mm-dd', date);
	if(jQuery.inArray(actd, vrc_fulldays_in) == -1) {
		return [true];
	}
	return [false];
}
function vrcIsDayFullOut(date) {
	var actd = jQuery.datepicker.formatDate('yy-mm-dd', date);
	if(jQuery.inArray(actd, vrc_fulldays_out) == -1) {
		return [true];
	}
	return [false];
}
jQuery(function(){
	jQuery.datepicker.setDefaults( jQuery.datepicker.regional[ '' ] );
	jQuery('#pickupdate').datepicker({
		showOn: 'both',
		buttonImage: '".JURI::root()."components/com_vikrentcar/resources/images/calendar.png',
		buttonImageOnly: true,
		beforeShowDay: vrcIsDayFullIn,
		onSelect: function( selectedDate ) {
			".$forcedropday."
		}
	});
	jQuery('#pickupdate').datepicker( 'option', 'dateFormat', '".$juidf."');
	jQuery('#pickupdate').datepicker( 'option', 'minDate', '".vikrentcar::getMinDaysAdvance()."d');
	jQuery('#pickupdate').datepicker( 'option', 'maxDate', '".vikrentcar::getMaxDateFuture()."');
	jQuery('#releasedate').datepicker({
		showOn: 'both',
		buttonImage: '".JURI::root()."components/com_vikrentcar/resources/images/calendar.png',
		buttonImageOnly: true,
		beforeShowDay: vrcIsDayFullOut,
		onSelect: function( selectedDate ) {
			jQuery('#pickupdate').datepicker( 'option', 'maxDate', selectedDate );
		}
	});
	jQuery('#releasedate').datepicker( 'option', 'dateFormat', '".$juidf."');
	jQuery('#releasedate').datepicker( 'option', 'minDate', '".vikrentcar::getMinDaysAdvance()."d');
	jQuery('#releasedate').datepicker( 'option', 'maxDate', '".vikrentcar::getMaxDateFuture()."');
	jQuery('#pickupdate').datepicker( 'option', jQuery.datepicker.regional[ 'vikrentcar' ] );
	jQuery('#releasedate').datepicker( 'option', jQuery.datepicker.regional[ 'vikrentcar' ] );
});";
		$document->addScriptDeclaration($sdecl);
		$selform .= "<div class=\"vrcsfentrycont\"><div class=\"vrcsfentrylabsel\"><label>" . JText :: _('VRPICKUPCAR') . "</label><div class=\"vrcsfentrydate\"><input type=\"text\" name=\"pickupdate\" id=\"pickupdate\" size=\"10\"/></div></div><div class=\"vrcsfentrytime\"><label>" . JText :: _('VRALLE') . "</label><span id=\"vrccomselph\"><select name=\"pickuph\">" . $hours . "</select></span></span><span class=\"vrctimesep\">:</span><span id=\"vrccomselpm\"><select name=\"pickupm\">" . $minutes . "</select></span></div></div>\n";
		$selform .= "<div class=\"vrcsfentrycont\"><div class=\"vrcsfentrylabsel\"><label>" . JText :: _('VRRETURNCAR') . "</label><div class=\"vrcsfentrydate\"><input type=\"text\" name=\"releasedate\" id=\"releasedate\" size=\"10\"/></div></div><div class=\"vrcsfentrytime\"><label>" . JText :: _('VRALLEDROP') . "</label><span id=\"vrccomseldh\"><select name=\"releaseh\">" . $hours . "</select></span></span><span class=\"vrctimesep\">:</span><span id=\"vrccomseldm\"><select name=\"releasem\">" . $minutes . "</select></span></div></div>";
	}else {
		//default Joomla Calendar
		JHTML :: _('behavior.calendar');
		$selform .= "<div class=\"vrcsfentrycont\"><div class=\"vrcsfentrylabsel\"><label>" . JText :: _('VRPICKUPCAR') . "</label><div class=\"vrcsfentrydate\">" . JHTML :: _('calendar', '', 'pickupdate', 'pickupdate', $vrcdateformat, array (
			'class' => '',
			'size' => '10',
			'maxlength' => '19'
		)) . "</div></div><div class=\"vrcsfentrytime\"><label>" . JText :: _('VRALLE') . "</label><span id=\"vrccomselph\"><select name=\"pickuph\">" . $hours . "</select></span><span class=\"vrctimesep\">:</span><span id=\"vrccomselpm\"><select name=\"pickupm\">" . $minutes . "</select></span></div></div>\n";
		$selform .= "<div class=\"vrcsfentrycont\"><div class=\"vrcsfentrylabsel\"><label>" . JText :: _('VRRETURNCAR') . "</label><div class=\"vrcsfentrydate\">" . JHTML :: _('calendar', '', 'releasedate', 'releasedate', $vrcdateformat, array (
			'class' => '',
			'size' => '10',
			'maxlength' => '19'
		)) . "</div></div><div class=\"vrcsfentrytime\"><label>" . JText :: _('VRALLEDROP') . "</label><span id=\"vrccomseldh\"><select name=\"releaseh\">" . $hours . "</select></span><span class=\"vrctimesep\">:</span><span id=\"vrccomseldm\"><select name=\"releasem\">" . $minutes . "</select></span></div></div>";
	}
	//
	if (@ is_array($places)) {
		$selform .= "<div class=\"vrcsfentrycont\"><label>" . JText :: _('VRRETURNCARORD') . "</label><div class=\"vrcsfentryselect\"><select name=\"returnplace\" id=\"returnplace\"".(strlen($onchangeplacesdrop) > 0 ? $onchangeplacesdrop : "").">";
		foreach ($places as $pla) {
			$selform .= "<option value=\"" . $pla['id'] . "\" id=\"returnplace".$pla['id']."\">" . $pla['name'] . "</option>\n";
		}
		$selform .= "</select></div></div>\n";
	}
	$selform .= "<div class=\"vrcsfentrycont\"><div class=\"vrcsfentrysubmit\"><input type=\"submit\" name=\"search\" value=\"" . JText::_('VRCBOOKTHISCAR') . "\" class=\"vrcdetbooksubmit\"/></div></div>\n";
	$selform .= "\n";
	//locations on google map
	if(count($coordsplaces) > 0) {
		$selform .= '<div class="vrclocationsbox"><div class="vrclocationsmapdiv"><a href="'.JURI::root().'index.php?option=com_vikrentcar&view=locationsmap&tmpl=component" class="vrcmodalframe" target="_blank">'.JText::_('VRCLOCATIONSMAP').'</a></div></div>';
	}
	//
	$selform .= (!empty ($pitemid) ? "<input type=\"hidden\" name=\"Itemid\" value=\"" . $pitemid . "\"/>" : "") . "</form></div>";
	
	echo $selform;

	if(!empty($ppickup)) {
		?>
		<script type="text/javascript">
		jQuery(document).ready(function() {
		<?php
		if($calendartype == "jqueryui") {
			?>
			jQuery("#pickupdate").datepicker("setDate", new Date(<?php echo date('Y', $ppickup); ?>, <?php echo ((int)date('n', $ppickup) - 1); ?>, <?php echo date('j', $ppickup); ?>));
			jQuery(".ui-datepicker-current-day").click();
			<?php
		}else {
			?>
			jQuery("#pickupdate").val("<?php echo date($df, $ppickup); ?>");
			<?php
		}
		?>
		});
		</script>
		<?php
	}

} else {
	echo vikrentcar :: getDisabledRentMsg();
}

?>

</div>