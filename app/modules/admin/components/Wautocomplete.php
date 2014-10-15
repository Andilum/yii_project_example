<?php

/**
 * Виджет поля с поиском и выпадаюшим списмком
 */
class Wautocomplete extends CInputWidget {

    public $url = '/admin/api/autocomplete';

    /**
     * Alias Модели выбираемых данных
     * @var string 
     */
    public $modelSelect = 'application.models.City';

    /**
     * Название атрибута модели данных, которо выводить в поле
     * @var type 
     */
    public $attrName;

    /**
     * иконка
     * @var type 
     */
    public $prepend;

    public function run() {
        if (!$this->prepend)
            $this->prepend = TbHtml::icon(TbHtml::ICON_GLOBE);

        list($name, $id) = parent::resolveNameID();

        if (!$this->name) {
            $this->name = $name;
        }
        $this->htmlOptions['id'] = $id;

        $modelSelect = Yii::createComponent($this->modelSelect);
        $this->render('autocomplete', array(
            'model' => $this->model,
            'attribute' => $this->attribute,
            'modelSelect' => $modelSelect,
            'name' => $this->name,
            'id' => $id
        ));
    }

    public function getName($o) {
        if ($this->attrName)
            return $o->{$this->attrName};

        if (isset($o->name))
            return $o->name;
        if (isset($o->title))
            return $o->title;

        if (method_exists($o, 'getname'))
            return $o->getname();
        if (method_exists($o, 'getName'))
            return $o->getName();

        return null;
    }

}