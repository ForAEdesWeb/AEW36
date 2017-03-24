<?php 
	jimport( 'joomla.application.module.helper' );
	$featuresImg 					= $helper->get('block-bg');
	$fullWidth 						= $helper->get('full-width');
	$featuresBackground  	= 'background-image: url("'.$featuresImg.'"); background-repeat: no-repeat; background-size: cover; background-position: center center;';
	$moduleParams 				= new JRegistry();
  $moduleParams->loadString($module->params);
  $moduleIntro 					= $moduleParams->get('module-intro', ''); 
  $specialTitle         = $moduleParams->get('module-specical-title','0');
?>
<div class="row">
  <?php if($specialTitle): ?>
  	<?php if($module->showtitle || $helper->get('block-intro')): ?>
  	<div class="col-xs-12 col-sm-3 section-header">
  		<?php if($module->showtitle): ?>
  		<h3><span><?php echo $module->title ?></span></h3>
  		<?php endif; ?>

  		<?php if($moduleIntro): ?>
  			<p class="container-sm module-intro"><?php echo $moduleIntro; ?></p>
  		<?php endif; ?>	

  		<?php if($helper->get('block-intro')): ?>
  			<p class="container-sm block-intro"><?php echo $helper->get('block-intro'); ?></p>
  		<?php endif; ?>	

  		<?php if($helper->get('btn-value')): ?>
  			<a class="btn btn-lg btn-feature btn-decor <?php echo $helper->get('btn-class'); ?>" href="<?php echo $helper->get('btn-link'); ?>">
  				<?php if($helper->get('btn-icon')): ?>
  					<i class="fa <?php echo $helper->get('btn-icon'); ?>"></i>
  				<?php endif; ?>
  				<?php echo $helper->get('btn-value'); ?>
  			</a>
  		<?php endif; ?>
  	</div>
    <?php endif; ?>
	<?php endif; ?>
	<div id="acm-features<?php echo $module->id;?>" class="col-xs-12 <?php if($specialTitle): ?> col-sm-9 <?php endif; ?> acm-features <?php echo $helper->get('features-style'); ?> style-2">
			<div class="row equal-height equal-height-child owl-carousel">
			<?php $count = $helper->getRows('data.title'); ?>
			<?php for ($i=0; $i<$count; $i++) : ?>
			
				<div class="features-item">
					<div class="inner">
						<?php if($helper->get('data.img', $i)) : ?>
							<div class="features-img">
								<img src="<?php echo $helper->get('data.img', $i) ?>" alt="<?php echo $helper->get('data.title', $i) ?>" />
							</div>
						<?php endif ; ?>
						
						<?php if($helper->get('data.title', $i)) : ?>
							<h3><?php echo $helper->get('data.title', $i) ?></h3>
						<?php endif ; ?>
						
						<?php if($helper->get('data.description', $i)) : ?>
							<p><?php echo $helper->get('data.description', $i) ?></p>
						<?php endif ; ?>
					</div>
				</div>
			<?php endfor ?>
			</div>
	</div>
</div>

<script>
(function($){
  jQuery(document).ready(function($) {
    $("#acm-features<?php echo $module->id;?> .owl-carousel").owlCarousel({
      navigation : false,
      pagination: false,
      items: <?php echo $helper->get('columns'); ?>,
      loop: false,
      scrollPerPage : true,
      autoHeight: true,
      itemsDesktop : [1199, 2],
      itemsDesktopSmall : [979, 2],
      itemsTablet : [768, 2],
      itemsTabletSmall : [600, 1],
      itemsMobile : [479, 1]
    });
  });
})(jQuery);
</script>