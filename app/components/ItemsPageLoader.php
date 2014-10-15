<?php

/**
 * Класс для разбивки и загрузки элеменов на странице
 */
class ItemsPageLoader {

    /**
     *
     * @var CActiveDataProvider 
     */
    private $dp;
    
    /**
     * вьюшка для одного элемента
     * @var type 
     */
    private $viewItem;
    
    /**
     * надпись на пагинаторе
     * если содержит ссылку то без автозагрузки
     * @var type 
     */
    public $paginLabel = 'загрузка';
    
    /**
     * колечество выводимых элементов
     * @var type 
     */
    public $pageSize = 20;
    
    /**
     * ид блока
     * @var type 
     */
    public $id;
    public $emptText = 'не найдено.';
    private $isMore;

    public function __construct(CDataProvider $dp, $viewItem, $id = null) {
        $this->dp = $dp;
        $dp->setPagination(false);
        $this->viewItem = $viewItem;
        if ($id) {
            $this->id = $id;
        } else {
            if ($dp instanceof CActiveDataProvider) {
                $this->id = get_class($dp->model);
            } else {
                throw new Exception('id empty');
            }
        }



        if (isset($_GET['ajax']) && $_GET['ajax'] == $this->id) {
            $this->renderItems(filter_input(INPUT_GET, 'offset', FILTER_VALIDATE_INT));
            echo '<!>';
            echo $this->isMore ? '1' : '0';
            Yii::app()->end();
        }
    }

    protected function getData($offset = 0) {
        $this->applyLimit($this->pageSize + 1, $offset);
        $data = $this->dp->getData(true);
        $this->isMore = count($data) > $this->pageSize;
        if ($this->isMore) {
            array_pop($data);
        }
        return $data;
    }

    protected function renderItems($offset = null) {
        $data = $this->getData($offset);
        if ($data) {
            foreach ($data as $item) {
                Yii::app()->controller->renderPartial($this->viewItem, array('data' => $item));
            }
        } else {
            echo $this->emptText;
        }
    }

    public function render() {
        Yii::app()->clientScript->registerScriptFile(CHtml::asset(__DIR__ . '/assets/ItemsPageLoader/itemspageloader.js'));
        echo '<div id="' . $this->id . '" class="itemspageloader">';
        echo '<div class="items">';
        $this->renderItems();
        echo '</div>';

        if ($this->isMore) {
            echo '<div class="pagin">';
            echo $this->paginLabel;
            echo '</div>';
        }


        echo '</div>';
    }

    protected function applyLimit($limit, $offset) {

        if ($this->dp instanceof CActiveDataProvider) {
            $this->dp->getCriteria()->limit = $limit;
            if ($offset) {
                $this->dp->getCriteria()->offset = $offset;
            }
        } elseif ($this->dp instanceof CSqlDataProvider) {
            $this->dp->sql = Yii::app()->db->getCommandBuilder()->applyLimit($this->dp->sql, $limit, $offset);
        } else
        {
              throw new Exception(get_class($this->dp).' not supported');
        }
    }

}
