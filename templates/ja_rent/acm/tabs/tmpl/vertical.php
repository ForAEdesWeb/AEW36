<?php
	jimport( 'joomla.application.module.helper' );
	$items_position = $helper->get('position');
	$mods = JModuleHelper::getModules($items_position);
?>
<div id="mod-<?php echo $module->id ?>" class="t3-tabs t3-tabs-vertical">	
	<div class="row">
		<!-- BEGIN: TAB NAV -->
		<ul class="nav nav-tabs" role="tablist">
			<?php
			$i = 0;
			foreach ($mods as $mod):
				?>
				<li class="<?php if ($i < 1) echo "active"; ?>">
					<a href="#mod-<?php echo $module->id ?> .mod-<?php echo $mod->id ?>" role="tab"
						 data-toggle="tab"><?php echo $mod->title ?></a>
				</li>
				<?php
				$i++;
			endforeach
			?>

		</ul>
		<!-- BEGIN: TAB PANES -->
		<div class="tab-content">
			<?php
			echo $helper->renderModules($items_position,
				array(
					'style'=>'ACMContainerItems',
					'active'=>0,
					'tag'=>'div',
					'class'=>'tab-pane fade'
				))
			?>
		</div>
		<!-- END: TAB PANES -->
	</div>
</div>