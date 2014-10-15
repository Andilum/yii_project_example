<?php
/* @var $this PostController */
/* @var $model Post */
/* @var $search CActiveDataProvider */

$this->pageTitle = "посты";
$this->breadcrumbs = array(
    $this->pageTitle,
);

$this->menu = array(
    array('label' => 'Создать пост', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('post-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Список постов</h1>

<style>
    #post-grid
    {
        
    }
    
    #post-grid_c6
    {
        width: 70px;
    }
    #post-grid .filters>td:nth-child(7)>*
    {
        display: inline-block;
        width: 70px  !important;
    }
    #post-grid .filters>td:nth-child(5)>*
    {
        display: inline-block;
        width: 70px  !important;
    }
</style>

<p class="muted">
    В полях поиска ввести оператор сравнения(<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    или <b>=</b>) в начале, чтобы указать, как должно быть сравнение.
</p>

<?php echo CHtml::link('Расширенный поиск', '#', array('class' => 'search-button btn btn-link')); ?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->

<form id="pnstr" style="float: right" action="<?= $this->createUrl('') ?>" method="get">
    <label>Показывать на странице
        <?php echo CHtml::dropDownList('pcount', isset($_GET['pcount']) ? $_GET['pcount'] : '', array('10' => '10', '30' => '30', '60' => '60', '5000' => 'всё'), array('onchange' => 'document.getElementById(\'pnstr\').submit();'))
        ?></label>
</form>
<div class="clear"></div>

<?php

if (isset($_GET['pcount'])) {
    $search->setPagination(array('pageSize' => $_GET['pcount']));
}


$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'post-grid',
    'dataProvider' => $search,
    'filter' => $model,
    'columns' => array(
        'id',
        'name',
        array(
            'name' => 'tp_user_id',
            'value' => function($data) {
        return $data->tp_user_id ? ( ($data->user ? $data->user->name : '') . ' #' . $data->tp_user_id) : null;
    }
        ),
        array(
            'name' => 'allocation_id',
            'value' => function($data) {
        return $data->allocation_id ? ( ($data->allocation ? $data->allocation->name : '') . ' #' . $data->allocation_id) : null;
    }
        ),
                   'text',
        array(
            'class' => 'CDataColumn',
            'name' => 'date',
            'type'=>'datetime',
            'filterHtmlOptions' => array('class' => 'c_datevibor')),
     
        array(
            'class' => 'CDataColumn',
            'name' => 'trash',
            'type' => 'boolean',
            'filter' => array('0' => 'нет', '1' => 'да')),

        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
           // 'template'=>'{view} {update} {delete}'
        ),
    ),
    'afterAjaxUpdate' => 'js:function(){ jQuery(\'.datevibor,.c_datevibor input\').datepicker({dateFormat:\'yy-mm-dd\'}); }',
));

        
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerScriptFile($cs->getCoreScriptUrl() . '/jui/js/jquery-ui-i18n.min.js', CClientScript::POS_HEAD);
$cs->registerCoreScript('jquery.ui');
$cs->registerCssFile(
        $cs->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
);
$js = "
$.datepicker.setDefaults($.datepicker.regional['ru']); 
jQuery('.datevibor,.c_datevibor input').datepicker({dateFormat:'yy-mm-dd'});";
$cs->registerScript('dateviborfiltr', $js);
?>