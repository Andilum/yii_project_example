<?php
/* @var $model CActiveRecord 
  @var $modelSelect CActiveRecord
 */
?>
<div class="control-group <?php if ($model->hasErrors($attribute)) echo 'error' ?>">
    <label class="control-label"><?= $model->getAttributeLabel($attribute) ?></label>
    <div class="controls">
        <?php
        echo CHtml::hiddenField($name, $model->$attribute, $this->htmlOptions);
        $v = '';

        if (!empty($model->$attribute)) {
            $us = $modelSelect::model()->findByPk($model->$attribute);
            if ($us != null)
                $v = $this->getName($us);
        }
        $this->widget('bootstrap.widgets.TbTypeAhead', array(
            'name' => 'select_' . $name,
            'value' => $v,
            'source' => "js:function  (query, process)
    {
    return $.get('" . $this->url . "',{term:query},function(d){
        var data = new Array();
                      //преобразовываем данные из json в массив
                      $.each(d, function(i, name)
                      {
                        data.push(name.id+'_'+name.label);
                      });
                      
                      return process(data);
    },'json');
    
    }",
            'highlighter' => "js:function (item) {
              var parts = item.split('_');
              parts.shift();
              return parts.join('_');
          }",
            'updater' => "js:function (item) {
                     var parts = item.split('_');
                     var Id = parts.shift();
                       $(\"#$id\").val(Id);
                      return parts.join('_');
                   
          }",
            'matcher' => "js:function() {
                        return true;
                    }",
            'htmlOptions' => array(
                'prepend' => $this->prepend,
                'placeholder' => $model->getAttributeLabel($attribute),
                'autocomplete' => 'off'
            ),
        ));

        if ($model->hasErrors($attribute)) {
            ?>
            <p class="help-block"><?php echo $model->getError($attribute); ?></p>
        <?php } ?>
    </div>
</div>