<?php
/**
 * ------------------------------------------------------------------------
 * JA CountDown Module for J25 & J33
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die;
?>

<div class="clock">
	
	<div class="clock_days">
		<canvas id="canvas_days" height="190px" width="190px" id="canvas_days"></canvas>
		<div class="text">
			<p class="val">0</p>
			<p class="type_days">Days</p>
		</div>
	</div>
	<div class="clock_hours">
		<canvas height="190px" width="190px" id="canvas_hours"></canvas>
		<div class="text">
			<p class="val">0</p>
			<p class="type_hours">Hours</p>
		</div>
	</div>
	<div class="clock_minutes">
		<canvas height="190px" width="190px" id="canvas_minutes"></canvas>
		<div class="text">
			<p class="val">0</p>
			<p class="type_minutes">Minutes</p>
		</div>
	</div>
	<div class="clock_seconds">
		<canvas height="190px" width="190px" id="canvas_seconds"></canvas>
		<div class="text">
			<p class="val">0</p>
			<p class="type_seconds">Seconds</p>
		</div>
	</div>
	
</div><!--/clock -->