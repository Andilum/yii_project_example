<?php
Yii::app()->getClientScript()->registerCoreScript('jquery');
$item = Yii::app()->helperAdmin->getMenuModule();
$this->widget('bootstrap.widgets.TbNavbar', array(
    'brandUrl' => Yii::app()->createUrl('admin/default/index'),
    'display' => isset($display) ? $display : TbHtml::NAVBAR_DISPLAY_FIXEDTOP,
    'items' => array(
        array(
            'class' => 'bootstrap.widgets.TbNav',
            'items' => array(
                array('label' => 'Модули', 'url' => Yii::app()->createUrl('admin/default/index'),
                    'items' => $item
                ),
           //     array('label' => , 'url' => Yii::app()->createUrl('admin/default/logout'), 'visible' => !Yii::app()->user->isGuest)
            )
        ), 
        '<a class="btn pull-right"  href="/">На сайт</a>',
        '<a class="btn-link pull-right" style="margin-right: 17px; margin-top: 8px;"  href="/admin/default/logout">Выход (' . CHtml::encode(Yii::app()->user->name) . ')</a>',
        
    ),
));
?>