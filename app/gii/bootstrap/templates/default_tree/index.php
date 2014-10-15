<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

<?php

echo '$this->pageTitle="'.$this->name[0].'";';
echo "\$this->breadcrumbs=array(
	\$this->pageTitle,
	
);\n";
?>

$this->menu=array(
	array('label'=>'Создать <?php echo $this->name[1]; ?>', 'url'=>array('create')),
        array('label'=>'Дерево <?php echo $this->name[1]; ?>', 'url'=>array('admintree')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('<?php echo $this->class2id($this->modelClass); ?>-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Список <?php echo $this->name[3]; ?></h1>

<p>
Можно ввести оператор сравнения(<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
или <b>=</b>) в начале, чтобы указать, как сравнение должно быть сделано.
</p>

<?php echo "<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button btn')); ?>"; ?>

<div class="search-form" style="display:none">
<?php echo "<?php \$this->renderPartial('_search',array(
	'model'=>\$model,
)); ?>\n"; ?>
</div><!-- search-form -->

<form id="pnstr" style="float: right" action="<?php echo "<?="; ?>$this->createUrl('')<?php echo "?>"; ?>" method="get">
    <label>Показывать на странице
    <?php echo "<?php"; ?> echo CHtml::dropDownList('pcount', isset($_GET['pcount'])?$_GET['pcount']:'', array('10'=>'10','30'=>'30','60'=>'60','5000'=>'всё'),array('onchange'=>'document.getElementById(\'pnstr\').submit();'))
    <?php echo "?>"; ?>
</label>
</form>
<div class="clear"></div>

<?php echo "<?php "; ?>
$seach=$model->search();
if (isset($_GET['pcount']))
{
    $seach->setPagination(array('pageSize'=>$_GET['pcount']));
}


$this->widget('<?php 
 
         if (!empty($this->sortfiled))
         {
             echo 'ext.RGridViewWidget.RGridViewWidget';
         } else  echo 'bootstrap.widgets.TbGridView';
             
?>', array(
<?php 
if (!empty($this->sortfiled))
{
    ?>

 'rowCssId'=>'$data->primaryKey',
     'orderUrl'=>array('order'),
     'successOrderMessage'=>'Успешно отсортировано',
    'buttonLabel'=>'Сортировать',
    'template' => '{summary} {items} {order} {pager}',
	'options'=>array(
		'cursor' => 'crosshair',
	),
        
<?php
}
?>
	'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
	'dataProvider'=>$seach,
	'filter'=>$model,
	'columns'=>array(
<?php
$count=0;

$isdatefiltr=false;
foreach($this->tableSchema->columns as $column)
{
	if(++$count==7)
		echo "\t\t/*\n";
        
         if (stripos($column->dbType, 'enum') !== false) 
         {
            echo "array('class'=>'CDataColumn','name'=>'".$column->name."','filter'=>".$this->getArrauenum($column->dbType)."),";
         } else
             if ($this->isColumBoll($column))
             {
                 echo "array(
                     'class'=>'CDataColumn',
                     'name'=>'".$column->name."',
                         'value'=>'\$data->".$column->name."?\'да\':\'нет\'',
                         'filter'=>array('0'=>'нет','1'=>'да')),";
             } else  if ($this->isColumbDate($column))
             {
                 
                  echo "array(
                     'class'=>'CDataColumn',
                     'name'=>'".$column->name."',
                          'filterHtmlOptions'=>array('class'=>'c_datevibor')),";
                  
                $isdatefiltr=true;
             } else
        //
	echo "\t\t'".$column->name."',\n";
}
if($count>=7)
	echo "\t\t*/\n";
?>
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
        
        <?php  if ($isdatefiltr) {?>
        'afterAjaxUpdate'=>'js:function(){ jQuery(\'.datevibor,.c_datevibor input\').datepicker({dateFormat:\'yy-mm-dd\'}); }',
        <?php } ?>
)); ?>

<?php if ($isdatefiltr)
{
    ?>
<?="<?php "?>
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery'); 
$cs->registerScriptFile($cs->getCoreScriptUrl().'/jui/js/jquery-ui-i18n.min.js',CClientScript::POS_HEAD);
$cs->registerCoreScript('jquery.ui'); 
$cs->registerCssFile(
$cs->getCoreScriptUrl().
'/jui/css/base/jquery-ui.css'
);
$js = "
$.datepicker.setDefaults($.datepicker.regional['ru']); 
jQuery('.datevibor,.c_datevibor input').datepicker({dateFormat:'yy-mm-dd'});";
$cs->registerScript('dateviborfiltr',$js);


<?="?>"?>
<?php
}
?>