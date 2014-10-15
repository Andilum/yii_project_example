<?php /* @var $this Controller */ ?>
<?php $this->beginContent('admin.views.layouts.main'); ?>
<div class="row">
    <div class="span9">
        <div id="content">
            <?php echo $content; ?>
        </div><!-- content -->
    </div>
    <div class="span3">
        <div id="sidebar">
            
                <?php echo TbHtml::buttonGroup($this->menu, array('vertical' => true, 'color' => TbHtml::BUTTON_COLOR_INFO)); ?>
            
        <?php
        
       
       /* 
            $this->widget('bootstrap.widgets.TbNav', array(
              
                'type'=>  TbHtml::NAV_TYPE_LIST,
                  'items'=>$this->menu,
            ));*/
          
        ?>
        </div><!-- sidebar -->
    </div>
</div>
<?php $this->endContent(); ?>