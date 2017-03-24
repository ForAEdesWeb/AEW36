<?php
	if($helper->count('title') >= $helper->count('description')) {
		$count = $helper->count('title');
	} else {
		$count = $helper->count('description');
	}
?>

<div class="section-inner ">
  <div class="acm-slideshow bg-slideshow">
  	<div id="acm-slideshow-<?php echo $module->id; ?>">
  		<div class="owl-carousel owl-theme">
  				<?php for ($i=0; $i<$count; $i++) : ?>
  				<div class="item">
            <?php if($helper->get('image', $i)): ?>
              <img src="<?php echo $helper->get('image', $i); ?>" class="slider-img" alt="<?php echo $helper->get('title', $i) ?>">
            <?php endif; ?>
            <div class="container slider-content">
              <?php if($helper->get('data.title', $i)): ?>
                <h1 class="item-title"><?php echo $helper->get('title', $i) ?></h1>
              <?php endif; ?>
              
    					<?php if($helper->get('description', $i)): ?>
    						<p class="item-desc"><?php echo $helper->get('description', $i) ?></p>
    					<?php endif; ?>
              <?php if($helper->get('button', $i)): ?>
                <a href="<?php echo $helper->get('button-link', $i) ?>" class="slider-btn btn btn-decor btn-lg btn-primary"><?php echo $helper->get('button', $i) ?></a>
              <?php endif; ?>
            </div>
  				</div>
  			 	<?php endfor ;?>
  		</div>
  	</div>
  </div>
</div>

<script>
(function($){
  jQuery(document).ready(function($) {
    $("#acm-slideshow-<?php echo $module->id; ?> .owl-carousel").owlCarousel({
      slideSpeed : 300,
      paginationSpeed : 400,
      singleItem:true,
      pagination:true,
      navigation : true,
      autoPlay: false,
    });
  });
})(jQuery);

</script>