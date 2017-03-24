<?php
  jimport( 'joomla.application.module.helper' );
  $count = $helper->getRows('member_info.member-name');
  $col = $helper->get('number_col');
?>

<div class="acm-teams">
	<div class="style-1 team-items">
		<?php
      for ($i=0; $i < $count; $i++) :
        if ($i%$col==0) echo '<div class="row">'; 
    ?>
		<div class="item col-sm-6 col-md-<?php echo (12/$col); ?>">
      <div class="item-inner">
    
        <div class="member-image">
          <img src="<?php echo $helper->get('member_info.member-image', $i); ?>" alt="<?php echo $helper->get('member_info.member-name', $i); ?>" />
        </div>
        <h4><?php echo $helper->get('member_info.member-name', $i); ?></h4>
        <p class="member-title"><?php echo $helper->get('member_info.member-position', $i); ?></p>
        <p class="member-desc"><?php echo $helper->get('member_info.member-desc', $i); ?></p>
      </div>
		</div>
    
    <?php if ( ($i%$col==($col-1)) || $i==($count-1) )  echo '</div>'; ?>
		<?php endfor; ?>
	</div>
  
</div>
