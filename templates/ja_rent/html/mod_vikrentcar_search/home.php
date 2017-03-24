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

defined('_JEXEC') or die('Restricted Area');
$session = JFactory::getSession();
$svrcplace = $session->get('vrcplace', '');
$indvrcplace = 0;
$svrcreturnplace = $session->get('vrcreturnplace', '');
$indvrcreturnplace = 0;
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root().'modules/mod_vikrentcar_search/mod_vikrentcar_search.css');
if ($params->get('calendar') != "jqueryui") {
	JHTML::_('behavior.calendar');
}
if(intval($params->get('loadjqueryvrc')) == 1) {
	JHtml::_('jquery.framework', true, true);
	JHtml::_('script', JURI::root().'components/com_vikrentcar/resources/jquery-1.11.1.min.js', false, true, false, false);
}

$moduleParams 				= new JRegistry();
$moduleParams->loadString($module->params);
$moduleIntro 					= $moduleParams->get('module-intro', ''); 
$specialTitle 				= $moduleParams->get('module-specical-title','0');
?>

<div class="row <?php echo $params->get('moduleclass_sfx'); ?>">
<?php if($specialTitle): ?>
<div class="col-sm-10 col-md-7 section-header">
  <?php if($module->showtitle): ?>
  <h3><span><?php echo $module->title ?></span></h3>
  <?php endif; ?>

  <?php if($moduleIntro): ?>
    <p class="container-sm module-intro hidden-xs"><?php echo $moduleIntro; ?></p>
  <?php endif; ?> 

  <?php if(strlen($vrtext) > 0): ?>
    <div class="container-sm block-intro hidden-xs">
    <?php echo $vrtext; ?>
  	</div>
	<?php endif; ?>
</div>
<?php endif; ?>
<div class="col-sm-12">
<div class="vrcdivsearch vrcdivsearchmodule">
    <form action="<?php echo JRoute::_('index.php?option=com_vikrentcar'); ?>" method="get">
    <input type="hidden" name="option" value="com_vikrentcar"/>
    <input type="hidden" name="task" value="search"/>
    <input type="hidden" name="Itemid" value="<?php echo $params->get('itemid'); ?>"/>
    <?php
	$dbo = JFactory::getDBO();
	$diffopentime = false;
	$closingdays = array();
	$declclosingdays = '';
    $vrloc="";
    if(intval($params->get('showloc'))==0) {
    	$q="SELECT `setting` FROM `#__vikrentcar_config` WHERE `param`='placesfront';";
    	$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() == 1) {
			$sl=$dbo->loadAssocList();
			if(intval($sl[0]['setting'])==1){
				$q="SELECT * FROM `#__vikrentcar_places` ORDER BY `#__vikrentcar_places`.`ordering` ASC, `#__vikrentcar_places`.`name` ASC;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if ($dbo->getNumRows() > 0) {
					$places = $dbo->loadAssocList();
					//check if some place has a different opening time (1.6)
					foreach ($places as $kpla=>$pla) {
						if(!empty($pla['opentime'])) {
							$diffopentime = true;
						}
						//check if some place has closing days
						if(!empty($pla['closingdays'])) {
							$closingdays[$pla['id']] = $pla['closingdays'];
						}
						if(!empty($svrcplace) && !empty($svrcreturnplace)) {
							if($pla['id'] == $svrcplace) {
								$indvrcplace = $kpla;
							}
							if($pla['id'] == $svrcreturnplace) {
								$indvrcreturnplace = $kpla;
							}
						}
					}
					//locations closing days (1.7)
					if (count($closingdays) > 0) {
						foreach($closingdays as $idpla => $clostr) {
							$jsclosingdstr = modVikrentcarSearchHelper::formatLocationClosingDays($clostr);
							if (count($jsclosingdstr) > 0) {
								$declclosingdays .= 'var modloc'.$idpla.'closingdays = ['.implode(", ", $jsclosingdstr).'];'."\n";
							}
						}
					}
					$onchangeplaces = $diffopentime == true ? " onchange=\"javascript: vrcSetLocOpenTimeModule(this.value, 'pickup');\"" : "";
					$onchangeplacesdrop = $diffopentime == true ? " onchange=\"javascript: vrcSetLocOpenTimeModule(this.value, 'dropoff');\"" : "";
					if($diffopentime == true) {
						$onchangedecl = '
jQuery.noConflict();
var vrcmod_location_change = false;
function vrcSetLocOpenTimeModule(loc, where) {
	if(where == "dropoff") {
		vrcmod_location_change = true;
	}
	jQuery.ajax({
		type: "POST",
		url: "'.JRoute::_('index.php?option=com_vikrentcar&task=ajaxlocopentime&tmpl=component').'",
		data: { idloc: loc, pickdrop: where }
	}).done(function(res) {
		var vrcobj = jQuery.parseJSON(res);
		if(where == "pickup") {
			jQuery("#vrcmodselph").html(vrcobj.hours);
			jQuery("#vrcmodselpm").html(vrcobj.minutes);
		}else {
			jQuery("#vrcmodseldh").html(vrcobj.hours);
			jQuery("#vrcmodseldm").html(vrcobj.minutes);
		}
		if(where == "pickup" && vrcmod_location_change === false) {
			jQuery("#modreturnplace").val(loc).trigger("change");
			vrcmod_location_change = false;
		}
	});
}';
						$document->addScriptDeclaration($onchangedecl);
					}
					//end check if some place has a different openingtime (1.6)
					
					$vrloc.="<div class=\"vrcsfentrycont\"><label>".JText::_('VRMPPLACE')."</label><div class=\"vrcsfentryselect\"><select name=\"place\" id=\"modplace\"".$onchangeplaces.">";
					foreach($places as $pla){
						$vrloc.="<option value=\"".$pla['id']."\"".(!empty($svrcplace) && $svrcplace == $pla['id'] ? " selected=\"selected\"" : "").">".$pla['name']."</option>\n";
					}
					$vrloc.="</select></div></div>\n";
				}
			}
		}
    }elseif(intval($params->get('showloc'))==1){
    	$q="SELECT * FROM `#__vikrentcar_places` ORDER BY `#__vikrentcar_places`.`ordering` ASC, `#__vikrentcar_places`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$places = $dbo->loadAssocList();
			//check if some place has a different opening time (1.6)
			foreach ($places as $kpla=>$pla) {
				if(!empty($pla['opentime'])) {
					$diffopentime = true;
				}
				//check if some place has closing days
				if(!empty($pla['closingdays'])) {
					$closingdays[$pla['id']] = $pla['closingdays'];
				}
				if(!empty($svrcplace) && !empty($svrcreturnplace)) {
					if($pla['id'] == $svrcplace) {
						$indvrcplace = $kpla;
					}
					if($pla['id'] == $svrcreturnplace) {
						$indvrcreturnplace = $kpla;
					}
				}
			}
			//locations closing days (1.7)
			if (count($closingdays) > 0) {
				foreach($closingdays as $idpla => $clostr) {
					$jsclosingdstr = modVikrentcarSearchHelper::formatLocationClosingDays($clostr);
					if (count($jsclosingdstr) > 0) {
						$declclosingdays .= 'var modloc'.$idpla.'closingdays = ['.implode(", ", $jsclosingdstr).'];'."\n";
					}
				}
			}
			$onchangeplaces = $diffopentime == true ? " onchange=\"javascript: vrcSetLocOpenTimeModule(this.value, 'pickup');\"" : "";
			$onchangeplacesdrop = $diffopentime == true ? " onchange=\"javascript: vrcSetLocOpenTimeModule(this.value, 'dropoff');\"" : "";
			if($diffopentime == true) {
				$onchangedecl = '
jQuery.noConflict();
var vrcmod_location_change = false;
function vrcSetLocOpenTimeModule(loc, where) {
	if(where == "dropoff") {
		vrcmod_location_change = true;
	}
	jQuery.ajax({
		type: "POST",
		url: "'.JRoute::_('index.php?option=com_vikrentcar&task=ajaxlocopentime&tmpl=component').'",
		data: { idloc: loc, pickdrop: where }
	}).done(function(res) {
		var vrcobj = jQuery.parseJSON(res);
		if(where == "pickup") {
			jQuery("#vrcmodselph").html(vrcobj.hours);
			jQuery("#vrcmodselpm").html(vrcobj.minutes);
		}else {
			jQuery("#vrcmodseldh").html(vrcobj.hours);
			jQuery("#vrcmodseldm").html(vrcobj.minutes);
		}
		if(where == "pickup" && vrcmod_location_change === false) {
			jQuery("#modreturnplace").val(loc).trigger("change");
			vrcmod_location_change = false;
		}
	});
}';
				$document->addScriptDeclaration($onchangedecl);
			}
			//end check if some place has a different opningtime (1.6)
			
			$vrloc.="<div class=\"vrcsfentrycont\"><label>".JText::_('VRMPPLACE')."</label><div class=\"vrcsfentryselect\"><select name=\"place\" id=\"modplace\"".$onchangeplaces.">";
			foreach($places as $pla){
				$vrloc.="<option value=\"".$pla['id']."\"".(!empty($svrcplace) && $svrcplace == $pla['id'] ? " selected=\"selected\"" : "").">".$pla['name']."</option>\n";
			}
			$vrloc.="</select></div></div>\n";
		}
    }
    echo $vrloc;
    
	$i=0;
	$imin = 0;
	$j=23;
	
	if($diffopentime == true && is_array($places) && strlen($places[$indvrcplace]['opentime']) > 0) {
		$parts = explode("-", $places[$indvrcplace]['opentime']);
		if (is_array($parts) && $parts[0] != $parts[1]) {
			$opent = modVikrentcarSearchHelper::mgetHoursMinutes($parts[0]);
			$closet = modVikrentcarSearchHelper::mgetHoursMinutes($parts[1]);
			$i = $opent[0];
			$imin = $opent[1];
			$j = $closet[0];
		} else {
			$i = 0;
			$imin = 0;
			$j = 23;
		}
		//change dates drop off location opening time (1.6)
		$iret = $i;
		$iminret = $imin;
		$jret = $j;
		if($indvrcplace != $indvrcreturnplace) {
			if(strlen($places[$indvrcreturnplace]['opentime']) > 0) {
				//different opening time for drop off location
				$parts = explode("-", $places[$indvrcreturnplace]['opentime']);
				if (is_array($parts) && $parts[0] != $parts[1]) {
					$opent = modVikrentcarSearchHelper::mgetHoursMinutes($parts[0]);
					$closet = modVikrentcarSearchHelper::mgetHoursMinutes($parts[1]);
					$iret = $opent[0];
					$iminret = $opent[1];
					$jret = $closet[0];
				} else {
					$iret = 0;
					$iminret = 0;
					$jret = 23;
				}
			}else {
				//global opening time
				$q="SELECT `setting` FROM `#__vikrentcar_config` WHERE `param`='timeopenstore';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$timeopst = $dbo->loadResult();
				$timeopst = explode("-", $timeopst);
				if (is_array($timeopst) && $timeopst[0] != $timeopst[1]) {
					$opent = modVikrentcarSearchHelper::mgetHoursMinutes($timeopst[0]);
					$closet = modVikrentcarSearchHelper::mgetHoursMinutes($timeopst[1]);
					$iret = $opent[0];
					$iminret = $opent[1];
					$jret = $closet[0];
				} else {
					$iret = 0;
					$iminret = 0;
					$jret = 23;
				}
			}
		}
		//
	}else {
		$q="SELECT `setting` FROM `#__vikrentcar_config` WHERE `param`='timeopenstore';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() == 1) {
    		$n=$dbo->loadAssocList();
    		if (!empty($n[0]['setting'])) {
    			$timeopst=explode("-", $n[0]['setting']);
    			if (is_array($timeopst) && $timeopst[0]!=$timeopst[1]) {
    				if ($timeopst[0] >= 3600) {
						$op=$timeopst[0] / 3600;
						$hoursop=floor($op);
					}else {
						$hoursop="0";
					}
    				$i=$hoursop;
    				$opent = modVikrentcarSearchHelper::mgetHoursMinutes($timeopst[0]);
    				$imin = $opent[1];
    				if ($timeopst[1] >= 3600) {
						$op=$timeopst[1] / 3600;
						$hourscl=floor($op);
					}else {
						$hourscl="0";
					}
    				$j=$hourscl;
    			}
    		}
    	}
		$iret = $i;
		$iminret = $imin;
		$jret = $j;
	}
    
	$hours = "";
	//VRC 1.9
	$pickhdeftime = !empty($places[$indvrcplace]['defaulttime']) ? ((int)$places[$indvrcplace]['defaulttime'] / 3600) : '';
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
	$hoursret = "";
	//VRC 1.9
	$drophdeftime = !empty($places[$indvrcreturnplace]['defaulttime']) ? ((int)$places[$indvrcreturnplace]['defaulttime'] / 3600) : '';
	if(!($iret < $jret)) {
		while (intval($iret) != (int)$jret) {
			if ($iret < 10) {
				$iret = "0" . $iret;
			} else {
				$iret = $iret;
			}
			$hoursret .= "<option value=\"" . $iret . "\"".($drophdeftime == (int)$iret ? ' selected="selected"' : '').">" . $iret . "</option>\n";
			$iret++;
			$iret = $iret > 23 ? 0 : $iret;
		}
		$iret = $iret < 10 ? "0" . $iret : $iret;
		$hoursret .= "<option value=\"" . $iret . "\">" . $iret . "</option>\n";
	}else {
		while ((int)$iret <= $jret) {
			if ($iret < 10) {
				$iret = "0" . $iret;
			}
			$hoursret .= "<option value=\"" . $iret . "\"".($drophdeftime == (int)$iret ? ' selected="selected"' : '').">" . $iret . "</option>\n";
			$iret++;
		}
	}
	//
	$minutes = "";
	for ($i = 0; $i < 60; $i += 15) {
		if ($i < 10) {
			$i = "0" . $i;
		}
		$minutes .= "<option value=\"" . $i . "\"".((int)$i == $imin ? " selected=\"selected\"" : "").">" . $i . "</option>\n";
	}
	$minutesret = "";
	for ($iret = 0; $iret < 60; $iret += 15) {
		if ($iret < 10) {
			$iret = "0" . $iret;
		}
		$minutesret .= "<option value=\"" . $iret . "\"".((int)$iret == $iminret ? " selected=\"selected\"" : "").">" . $iret . "</option>\n";
	}
	
	$session = JFactory::getSession();
	$sval = $session->get('getDateFormat', '');
	if (!empty($sval)) {
		$dateformat=$sval;
	}else {
		$q="SELECT `setting` FROM `#__vikrentcar_config` WHERE `param`='dateformat';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() == 1) {
			$df=$dbo->loadAssocList();
			$dateformat=$df[0]['setting'];
		}else{
			$dateformat="%d/%m/%Y";
		}
	}
	
	if ($params->get('calendar') == "jqueryui") {
		if ($dateformat == "%d/%m/%Y") {
			$juidf = 'dd/mm/yy';
		}elseif ($dateformat == "%m/%d/%Y") {
			$juidf = 'mm/dd/yy';
		}else {
			$juidf = 'yy/mm/dd';
		}
		$document->addStyleSheet(JURI::root().'components/com_vikrentcar/resources/jquery-ui.min.css');
		//load jQuery UI
		JHtml::_('script', JURI::root().'components/com_vikrentcar/resources/jquery-ui.min.js', false, true, false, false);
		//
		//lang for jQuery UI Calendar
		$ldecl = '
jQuery(function($){'."\n".'
	$.datepicker.regional["vikrentcarmod"] = {'."\n".'
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
		firstDay: '.modVikrentcarSearchHelper::getFirstWeekDay().','."\n".'
		isRTL: false,'."\n".'
		showMonthAfterYear: false,'."\n".'
		yearSuffix: ""'."\n".'
	};'."\n".'
	$.datepicker.setDefaults($.datepicker.regional["vikrentcarmod"]);'."\n".'
});';
		$document->addScriptDeclaration($ldecl);
		//
		//locations closing days (1.7)
		if (strlen($declclosingdays) > 0) {
			$declclosingdays .= '
jQuery.noConflict();
function modpickupClosingDays(date) {
	dmy = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
	var arrlocclosd = jQuery("#modplace").val();
	var checklocarr = window["modloc"+arrlocclosd+"closingdays"];
	if (jQuery.inArray(dmy, checklocarr) == -1) {
		return [true, ""];
	} else {
		return [false, "", "'.addslashes(JText::_('VRCMLOCDAYCLOSED')).'"];
	}
}
function moddropoffClosingDays(date) {
	dmy = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
	var arrlocclosd = jQuery("#modreturnplace").val();
	var checklocarr = window["modloc"+arrlocclosd+"closingdays"];
	if (jQuery.inArray(dmy, checklocarr) == -1) {
		return [true, ""];
	} else {
		return [false, "", "'.addslashes(JText::_('VRCMLOCDAYCLOSED')).'"];
	}
}';
			$document->addScriptDeclaration($declclosingdays);
		}
		//
		//Minimum Num of Days of Rental (VRC 1.8)
		$dropdayplus = modVikrentcarSearchHelper::setDropDatePlus();
		$forcedropday = "jQuery('#releasedatemod').datepicker( 'option', 'minDate', selectedDate );";
		if (strlen($dropdayplus) > 0 && intval($dropdayplus) > 0) {
			$forcedropday = "
var vrcdatemod = jQuery(this).datepicker('getDate');
if(vrcdatemod) {
	vrcdatemod.setDate(vrcdatemod.getDate() + ".$dropdayplus.");
	jQuery('#releasedatemod').datepicker( 'option', 'minDate', vrcdatemod );
	jQuery('#releasedatemod').val(jQuery.datepicker.formatDate('".$juidf."', vrcdatemod));
}";
		}
		//
		$sdecl = "
jQuery.noConflict();
jQuery(function(){
	jQuery.datepicker.setDefaults( jQuery.datepicker.regional[ '' ] );
	jQuery('#pickupdatemod').datepicker({
		showOn: 'both',
		buttonImage: '".JURI::root()."components/com_vikrentcar/resources/images/calendar.png',
		buttonImageOnly: true,
		onSelect: function( selectedDate ) {
			".$forcedropday."
		}".(strlen($declclosingdays) > 0 ? ", beforeShowDay: modpickupClosingDays" : "")."
	});
	jQuery('#pickupdatemod').datepicker( 'option', 'dateFormat', '".$juidf."');
	jQuery('#pickupdatemod').datepicker( 'option', 'minDate', '".modVikrentcarSearchHelper::getMinDaysAdvance()."d');
	jQuery('#pickupdatemod').datepicker( 'option', 'maxDate', '".modVikrentcarSearchHelper::getMaxDateFuture()."');
	jQuery('#releasedatemod').datepicker({
		showOn: 'both',
		buttonImage: '".JURI::root()."components/com_vikrentcar/resources/images/calendar.png',
		buttonImageOnly: true,
		onSelect: function( selectedDate ) {
			jQuery('#pickupdatemod').datepicker( 'option', 'maxDate', selectedDate );
		}".(strlen($declclosingdays) > 0 ? ", beforeShowDay: moddropoffClosingDays" : "")."
	});
	jQuery('#releasedatemod').datepicker( 'option', 'dateFormat', '".$juidf."');
	jQuery('#releasedatemod').datepicker( 'option', 'minDate', '".modVikrentcarSearchHelper::getMinDaysAdvance()."d');
	jQuery('#releasedatemod').datepicker( 'option', 'maxDate', '".modVikrentcarSearchHelper::getMaxDateFuture()."');
	jQuery('#pickupdatemod').datepicker( 'option', jQuery.datepicker.regional[ 'vikrentcarmod' ] );
	jQuery('#releasedatemod').datepicker( 'option', jQuery.datepicker.regional[ 'vikrentcarmod' ] );
});";
		$document->addScriptDeclaration($sdecl);
		echo "<div class=\"vrcsfentrycont\"><div class=\"vrcsfentrylabsel\"><label>" . JText :: _('VRMPICKUPCAR') . "</label><div class=\"vrcsfentrydate\"><input type=\"text\" name=\"pickupdate\" id=\"pickupdatemod\" size=\"10\"/></div></div><div class=\"vrcsfentrytime\"><label>" . JText :: _('VRMALLE') . "</label><span id=\"vrcmodselph\"><select name=\"pickuph\">" . $hours . "</select></span><span class=\"vrctimesep\">:</span><span id=\"vrcmodselpm\"><select name=\"pickupm\">" . $minutes . "</select></span></div></div>\n";
		//mod carlos
    if(@is_array($places)) {
    	$vrlocreturn="";
    	$vrlocreturn.="<div class=\"vrcsfentrycont\"><label>".JText::_('VRMPLACERET')."</label><div class=\"vrcsfentryselect\"><select name=\"returnplace\" id=\"modreturnplace\"".(strlen($onchangeplacesdrop) > 0 ? $onchangeplacesdrop : "").">";
		foreach($places as $pla){
			$vrlocreturn.="<option value=\"".$pla['id']."\"".(!empty($svrcreturnplace) && $svrcreturnplace == $pla['id'] ? " selected=\"selected\"" : "").">".$pla['name']."</option>\n";
		}
		$vrlocreturn.="</select></div></div>\n";
		echo $vrlocreturn;
    }
    //end mod carlos
		echo "<div class=\"vrcsfentrycont\"><div class=\"vrcsfentrylabsel\"><label>" . JText :: _('VRMRETURNCAR') . "</label><div class=\"vrcsfentrydate\"><input type=\"text\" name=\"releasedate\" id=\"releasedatemod\" size=\"10\"/></div></div><div class=\"vrcsfentrytime\"><label>" . JText :: _('VRMALLEDROP') . "</label><span id=\"vrcmodseldh\"><select name=\"releaseh\">" . $hoursret . "</select></span><span class=\"vrctimesep\">:</span><span id=\"vrcmodseldm\"><select name=\"releasem\">" . $minutesret . "</select></span></div></div>";
	}else {
		echo "<div class=\"vrcsfentrycont\"><div class=\"vrcsfentrylabsel\"><label>".JText::_('VRMPICKUPCAR')."</label><div class=\"vrcsfentrydate\">".JHTML::_('calendar', '', 'pickupdate', 'pickupdatemod', $dateformat, array('class'=>'', 'size'=>'9',  'maxlength'=>'19'))."</div></div><div class=\"vrcsfentrytime\"><label>".JText::_('VRMALLE')."</label><span id=\"vrcmodselph\"><select name=\"pickuph\">".$hours."</select></span><span class=\"vrctimesep\">:</span><span id=\"vrcmodselpm\"><select name=\"pickupm\">".$minutes."</select></span></div></div>\n";
    	echo "<div class=\"vrcsfentrycont\"><div class=\"vrcsfentrylabsel\"><label>".JText::_('VRMRETURNCAR')."</label><div class=\"vrcsfentrydate\">".JHTML::_('calendar', '', 'releasedate', 'releasedatemod', $dateformat, array('class'=>'', 'size'=>'9',  'maxlength'=>'19'))."</div></div><div class=\"vrcsfentrytime\"><label>".JText::_('VRMALLEDROP')."</label><span id=\"vrcmodseldh\"><select name=\"releaseh\">".$hoursret."</select></span><span class=\"vrctimesep\">:</span><span id=\"vrcmodseldm\"><select name=\"releasem\">".$minutesret."</select></span></div></div>";
	}
    
    $vrcats="";
    
    if(intval($params->get('showcat'))==0){
    	$q="SELECT `setting` FROM `#__vikrentcar_config` WHERE `param`='showcategories';";
		$dbo->setQuery($q);
		$dbo->Query($q);
    	if ($dbo->getNumRows() == 1) {
    		$sc=$dbo->loadAssocList();
    		if(intval($sc[0]['setting'])==1){
    			$q="SELECT * FROM `#__vikrentcar_categories` ORDER BY `#__vikrentcar_categories`.`name` ASC;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if ($dbo->getNumRows() > 0) {
					$categories = $dbo->loadAssocList();
					$vrcats.="<div class=\"vrcsfentrycont\"><label>".JText::_('VRMCARCAT')."</label><div class=\"vrcsfentryselect\"><select name=\"categories\">";
					$vrcats.="<option value=\"all\">".JText::_('VRMALLCAT')."</option>\n";
					foreach($categories as $cat){
						$vrcats.="<option value=\"".$cat['id']."\">".$cat['name']."</option>\n";
					}
					$vrcats.="</select></div></div>\n";
				}
    		}
    	}
    }elseif(intval($params->get('showcat'))==1){
    	$q="SELECT * FROM `#__vikrentcar_categories` ORDER BY `#__vikrentcar_categories`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$categories = $dbo->loadAssocList();
			$vrcats.="<div class=\"vrcsfentrycont\"><label>".JText::_('VRMCARCAT')."</label><div class=\"vrcsfentryselect\"><select name=\"categories\">";
			$vrcats.="<option value=\"all\">".JText::_('VRMALLCAT')."</option>\n";
			foreach($categories as $cat){
				$vrcats.="<option value=\"".$cat['id']."\">".$cat['name']."</option>\n";
			}
			$vrcats.="</select></div></div>\n";
		}
    }
    echo $vrcats;
    
    ?>
    <div class="vrcsfentrycont"><div class="vrcsfentrysubmit"><button class="btn btn-decor btn-primary"><?php echo (strlen($params->get('srchbtntext')) > 0 ? $params->get('srchbtntext') : JText::_('SEARCHD')); ?></button></div></div>
    </form>
</div></div>
</div>

<?php
//VikRentCar 1.7
$sespickupts = $session->get('vrcpickupts', '');
$sesdropoffts = $session->get('vrcreturnts', '');
$ptask = JRequest :: getString('task', '', 'request');
if ($ptask == 'search' && !empty($sespickupts) && !empty($sesdropoffts)) {
	if ($dateformat == "%d/%m/%Y") {
		$jsdf = 'd/m/Y';
	}elseif ($dateformat == "%m/%d/%Y") {
		$jsdf = 'm/d/Y';
	}else {
		$jsdf = 'Y/m/d';
	}
	?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		document.getElementById('pickupdatemod').value = '<?php echo date($jsdf, $sespickupts); ?>';
		document.getElementById('releasedatemod').value = '<?php echo date($jsdf, $sesdropoffts); ?>';
	});
	</script>
	<?php
}
?>