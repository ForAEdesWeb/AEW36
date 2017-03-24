<div class="acm-accordion panel-group" id="acm-accordion<?php echo $module->id; ?>" role="tablist" aria-multiselectable="true">
  <?php $count = $helper->getRows('data.accordion-name'); ?>
  <?php for ($i=0; $i<$count; $i++) : ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading-<?php echo $module->id; ?><?php echo $i ?>">
      <h4 class="panel-title">
        <a class="<?php if($i!=0) echo 'collapsed' ?>" role="button" data-toggle="collapse" data-parent="#acm-accordion<?php echo $module->id; ?>" href="#collapse-<?php echo $module->id; ?><?php echo $i ?>" aria-expanded="<?php if($i==0) echo 'true' ?>" aria-controls="collapse-<?php echo $module->id; ?><?php echo $i ?>">
          <span class="marker">
            <span class="marker-close"><i class="fa fa-plus"></i></span>
            <span class="marker-open"><i class="fa fa-minus"></i></span>
          </span>
          <?php echo $helper->get('data.accordion-name', $i) ; ?>
        </a>
      </h4>
    </div>
    <div id="collapse-<?php echo $module->id; ?><?php echo $i ?>" class="panel-collapse collapse <?php if($i==0) echo 'in' ?>" role="tabpanel" aria-labelledby="#acm-accordion<?php echo $module->id; ?>" aria-expanded="<?php if($i==0) echo 'true' ?>">
      <div class="panel-body"><?php echo $helper->get('data.accordion-desc', $i) ; ?></div>
    </div>
  </div>
  <?php endfor ?>
</div>