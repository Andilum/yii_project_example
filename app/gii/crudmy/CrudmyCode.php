<?php

class CrudmyCode extends CCodeModel {

    public $model;
    public $controller;
    public $name;
    public $sortfiled;
    public $parent_fild;
    public $baseControllerClass = 'Controller';
    private $_modelClass;
    private $_table;
    
    public $isdate=false;

    public function rules() {
        return array_merge(parent::rules(), array(
            array('model, controller', 'filter', 'filter' => 'trim'),
            array('model, controller, name, baseControllerClass', 'required'),
            array('model', 'match', 'pattern' => '/^\w+[\w+\\.]*$/', 'message' => '{attribute} should only contain word characters and dots.'),
            array('controller', 'match', 'pattern' => '/^\w+[\w+\\/]*$/', 'message' => '{attribute} should only contain word characters and slashes.'),
            array('baseControllerClass', 'match', 'pattern' => '/^[a-zA-Z_]\w*$/', 'message' => '{attribute} should only contain word characters.'),
            array('baseControllerClass', 'validateReservedWord', 'skipOnError' => true),
            array('model', 'validateModel'),
            array('sortfiled,parent_fild','safe'),
            array('baseControllerClass', 'sticky'),
                ));
    }
    
    protected function afterValidate() {
          
            
        if (!$this->hasErrors())
        {
            if (strpos($this->name,'|')!==false)
            $this->name=  explode('|', $this->name);
            else  $this->name=array($this->name, $this->name, $this->name, $this->name);
        }
        if ($this->parent_fild)
        $this->template='default_tree';
     else    $this->template='default';
      
     
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), array(
            'model' => 'Model Class',
            'controller' => 'Controller ID',
            'baseControllerClass' => 'Base Controller Class',
            'name'=>'имя в разных склонениях (статьи|статью|статья|статей)',
            'sortfiled'=>'поле для сортировки',
            'parent_fild'=>'Поле parent_id'
                ));
    }

    public function requiredTemplates() {
        return array(
            'controller.php',
        );
    }

    public function init() {
        if (Yii::app()->db === null)
            throw new CHttpException(500, 'An active "db" connection is required to run this generator.');
        parent::init();
    }

    public function successMessage() {
        $link = CHtml::link('try it now', Yii::app()->createUrl($this->controller), array('target' => '_blank'));
        return "The controller has been generated successfully. You may $link.";
    }

    public function validateModel($attribute, $params) {
        if ($this->hasErrors('model'))
            return;
        $class = @Yii::import($this->model, true);
        if (!is_string($class) || !$this->classExists($class))
            $this->addError('model', "Class '{$this->model}' does not exist or has syntax error.");
        else if (!is_subclass_of($class, 'CActiveRecord'))
            $this->addError('model', "'{$this->model}' must extend from CActiveRecord.");
        else {
            $table = CActiveRecord::model($class)->tableSchema;
            if ($table->primaryKey === null)
                $this->addError('model', "Table '{$table->name}' does not have a primary key.");
            else if (is_array($table->primaryKey))
                $this->addError('model', "Table '{$table->name}' has a composite primary key which is not supported by crud generator.");
            else {
                $this->_modelClass = $class;
                $this->_table = $table;
            }
        }
    }

    public function prepare() {
        $this->files = array();
        $templatePath = $this->templatePath;
       
        $controllerTemplateFile = $templatePath . DIRECTORY_SEPARATOR . 'controller.php';

        $this->files[] = new CCodeFile(
                $this->controllerFile, $this->render($controllerTemplateFile)
        );

        $files = scandir($templatePath);
        foreach ($files as $file) {
            if (is_file($templatePath . '/' . $file) && CFileHelper::getExtension($file) === 'php' && $file !== 'controller.php') {
                $this->files[] = new CCodeFile(
                        $this->viewPath . DIRECTORY_SEPARATOR . $file, $this->render($templatePath . '/' . $file)
                );
            }
        }
    }

    public function getModelClass() {
        return $this->_modelClass;
    }

    public function getControllerClass() {
        if (($pos = strrpos($this->controller, '/')) !== false)
            return ucfirst(substr($this->controller, $pos + 1)) . 'Controller';
        else
            return ucfirst($this->controller) . 'Controller';
    }

    public function getModule() {
        if (($pos = strpos($this->controller, '/')) !== false) {
            $id = substr($this->controller, 0, $pos);
            if (($module = Yii::app()->getModule($id)) !== null)
                return $module;
        }
        return Yii::app();
    }

    public function getControllerID() {
        if ($this->getModule() !== Yii::app())
            $id = substr($this->controller, strpos($this->controller, '/') + 1);
        else
            $id = $this->controller;
        if (($pos = strrpos($id, '/')) !== false)
            $id[$pos + 1] = strtolower($id[$pos + 1]);
        else
            $id[0] = strtolower($id[0]);
        return $id;
    }

    public function getUniqueControllerID() {
        $id = $this->controller;
        if (($pos = strrpos($id, '/')) !== false)
            $id[$pos + 1] = strtolower($id[$pos + 1]);
        else
            $id[0] = strtolower($id[0]);
        return $id;
    }

    public function getControllerFile() {
        $module = $this->getModule();
        $id = $this->getControllerID();
        if (($pos = strrpos($id, '/')) !== false)
            $id[$pos + 1] = strtoupper($id[$pos + 1]);
        else
            $id[0] = strtoupper($id[0]);
        return $module->getControllerPath() . '/' . $id . 'Controller.php';
    }

    public function getViewPath() {
        return $this->getModule()->getViewPath() . '/' . $this->getControllerID();
    }

    public function getTableSchema() {
        return $this->_table;
    }

    public function generateInputLabel($modelClass, $column) {
        return "CHtml::activeLabelEx(\$model,'{$column->name}')";
    }

    public function generateInputField($modelClass, $column) {
        if ($this->isColumBoll($column))
            return "CHtml::activeCheckBox(\$model,'{$column->name}')";
        else if (stripos($column->dbType, 'text') !== false)
            return "CHtml::activeTextArea(\$model,'{$column->name}',array('rows'=>6, 'cols'=>50))";
        else {

            if (stripos($column->dbType, 'enum') !== false) {

                return "CHtml::activeDropDownList(\$model,'{$column->name}'," . $this->getArrauenum($column->dbType) . ")";
            } else {

                if ($this->isColumbDate($column)) {
                    $this->isdate=true;
                    return "CHtml::activeTextField(\$model,'{$column->name}',array('class'=>'datevibor'))"; // $this->getWidgetDate($column);
                } else {
                    if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name))
                        $inputField = 'activePasswordField';
                    else
                        $inputField = 'activeTextField';

                    if ($column->type !== 'string' || $column->size === null)
                        return "CHtml::{$inputField}(\$model,'{$column->name}')";
                    else {
                        if (($size = $maxLength = $column->size) > 60)
                            $size = 60;
                        return "CHtml::{$inputField}(\$model,'{$column->name}',array('size'=>$size,'maxlength'=>$maxLength))";
                    }
                }
            }
        }
    }

    public function generateActiveLabel($modelClass, $column) {
        return "\$form->labelEx(\$model,'{$column->name}')";
    }

    public function isColumBoll($column) {
        
        
        return ($column->dbType=='tinyint(1)' || $column->type === 'boolean' || stripos($column->dbType, 'bit') !== false);
    }

    public function isColumbDate($column) {
        return ($column->dbType == 'timestamp' || $column->dbType == 'datetime' || $column->dbType == 'date');
    }

    public function getArrauenum($dbType) {
      
     
        
        preg_match_all("/\'[^']*\'/", $dbType, $matches);

        $arr = "array(";
        foreach ($matches[0] as $value) {
            $arr.="$value=>$value,";
        }
        $arr.=")";
        return $arr;
    }

    public function getWidgetDate($column) {
        return
                "\$this->widget('zii.widgets.jui.CJuiDatePicker',array(
      'model'=>\$model,
     'attribute'=>'{$column->name}',
      'options'=>array(
          'showAnim'=>'fold',
      ),
      'htmlOptions'=>array(
          'style'=>'height:20px;',
          'class'=>'dpfild',
      ),
 ))";
    }

    public function generateActiveField($modelClass, $column) {
        if ($this->isColumBoll($column))
            return "\$form->checkBox(\$model,'{$column->name}')";
        else if (stripos($column->dbType, 'text') !== false)
            return "\$form->textArea(\$model,'{$column->name}',array('rows'=>6, 'cols'=>50))";
        else {
            if (stripos($column->dbType, 'enum') !== false) {

                return "\$form->dropDownList(\$model,'{$column->name}'," . $this->getArrauenum($column->dbType) . ")";
            } else {
                if ($this->isColumbDate($column)) {
                    $this->isdate=true;
                       return "\$form->textField(\$model,'{$column->name}',array('class'=>'datevibor'))";  //$this->getWidgetDate($column);
                } else {

                    if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name))
                        $inputField = 'passwordField';
                    else
                        $inputField = 'textField';

                    if ($column->type !== 'string' || $column->size === null)
                        return "\$form->{$inputField}(\$model,'{$column->name}')";
                    else {
                        if (($size = $maxLength = $column->size) > 60)
                            $size = 60;
                        return "\$form->{$inputField}(\$model,'{$column->name}',array('size'=>$size,'maxlength'=>$maxLength))";
                    }
                }
            }
        }
    }

    public function guessNameColumn($columns) {
        foreach ($columns as $column) {
            if (!strcasecmp($column->name, 'name'))
                return $column->name;
        }
        foreach ($columns as $column) {
            if (!strcasecmp($column->name, 'title'))
                return $column->name;
        }
        foreach ($columns as $column) {
            if ($column->isPrimaryKey)
                return $column->name;
        }
        return 'id';
    }
    
   

}