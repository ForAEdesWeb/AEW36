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
	<!-- Days -->
	<div class="clock_days">
		<div class="bgLayer">
			<div class="topLayer"></div>
			<canvas id="canvas_days" width="188" height="188">
			</canvas>
			<div class="text">
				<p class="val">0</p>
				<p class="type_days">Days</p>
			</div>
		</div>
	</div>
	<!-- Days -->
	<!-- Hours -->
	<div class="clock_hours">
		<div class="bgLayer">
			<div class="topLayer"></div>
			<canvas id="canvas_hours" width="188" height="188">
			</canvas>
			<div class="text">
				<p class="val">0</p>
				<p class="type_hours">Hours</p>
			</div>
		</div>
	</div>
	<!-- Hours -->
	<!-- Minutes -->
	<div class="clock_minutes">
		<div class="bgLayer">
			<div class="topLayer"></div>
			<canvas id="canvas_minutes" width="188" height="188">
			</canvas>
			<div class="text">
				<p class="val">0</p>
				<p class="type_minutes">Minutes</p>
			</div>
		</div>
	</div>
	<!-- Minutes -->
	<!-- Seconds -->
	<div class="clock_seconds">
		<div class="bgLayer">
			<div class="topLayer"></div>
			<canvas id="canvas_seconds" width="188" height="188">
			</canvas>
			<div class="text">
				<p class="val">0</p>
				<p class="type_seconds">Seconds</p>
			</div>
		</div>
	</div>
	<!-- Seconds -->
</div>