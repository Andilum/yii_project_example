<?php
/* @var $this DefaultController */
/* @var $dataTest array */

$this->pageTitle = 'тестирование api';
$this->breadcrumbs = array(
    $this->pageTitle,
);
?>
<style>
    .test-list li
    {
        border: 1px solid #cccccc;
        margin-bottom: 5px;
        padding: 5px;
    }
</style>
<h1><?php echo $this->pageTitle ?></h1>
<form method="post" action="<?= Yii::app()->request->getUrl() ?>" class="form-horizontal">
    <div class="data">

        <div class="control-group">
            <label  class="control-label">Адрес</label>
            <div class="controls"><input type="text" value="http://hotelsinspector.com.db0.ru<?php //echo Yii::app()->request->hostInfo ?>" name="host"></div>
        </div>

        <fieldset>
            <legend>для получения токена</legend>
            <div class="control-group">
                <label  class="control-label">Логин</label>
                <div class="controls"><input type="text" value="" name="login"></div>
            </div>
            <div class="control-group">
                <label  class="control-label">Пароль</label>
                <div class="controls"><input type="text" value="" name="password"></div>
            </div>

            <hr>

            <div class="control-group">
                <label  class="control-label">или токен</label>
                <div class="controls"><input type="text" value="" name="token"> </div>
            </div>
        </fieldset>
    </div>

    <div style="text-align: right"><a href="#" onclick="$('.test-enable').attr('checked',false).change();return false;">снать все</a> / <a href="#" onclick="$('.test-enable').attr('checked',true).change();return false;" >выбрать все</a>  </div>
        
    <ul class="test-list">
        <?php
        foreach ($dataTest as $k => $value) {
            ?>
            <li>
                <label><input class="test-enable" checked="1" name="tests[]"  type="checkbox" value="<?= $k ?>"><span style="vertical-align: middle" > <?= $value['label'] ?></span></label>
                <?php if (!empty($value['field'])) { ?>
                    <div class="data">
                        <?php
                        foreach ($value['field'] as $i => $value) {
                            $default=array('name'=>'','required'=>true,'help'=>'');
                             if (!is_array($value))
                             {
                                 $value=array('name'=>$value);
                             }
                             $value=  array_merge($default,$value);
                            
                            ?>
                            <div class="control-group">
                                <label  class="control-label"><?= $value['name'] ?></label>
                                <div class="controls"><input  <?php if ($value['required']) echo 'class="required"'; ?> type="text" value="" name="<?= $k ?>[<?= $value['name'] ?>]">
                                 <?php if ($value['help']) {?>
                                <div class="help"><?=$value['help']?></div>
                                <?php } ?>
                                </div>
                               
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                <?php } ?>

            </li>
            <?php
        }
        ?>
    </ul>

    <div class="form-actions">
        <input type="submit" value="начать тест" class="btn btn-primary btn-large">
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {
        $('.test-list .test-enable').change(function() {
            $(this).parents('li').find('.data input.required').attr('required', !!$(this).attr('checked'));
        }).change();
    });
</script>