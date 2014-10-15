<?php

class AjaxPager extends CBasePager {

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $ajaxLink;

    /**
     * @var array
     */
    public $ajaxOptions;

    /**
     * Initializes the pager by setting some default property values.
     */
    public function init() {
        $baseAssetsPath = Yii::getPathOfAlias('application.components.assets.AjaxPager');
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile(Yii::app()->getAssetManager()->publish($baseAssetsPath . '/ajaxPager.js'));

        if (is_null($this->ajaxOptions)) {
            $this->ajaxOptions = array();
        }

        $ajaxOptions = array(
            'data' => array('page' => 'js:function(){ return window.page + 1; }', 'sort' => "js:(typeof sort != 'undefined') ? sort : ''"),
            'dataType' => 'json',
            'context' => 'this',
            'success' => 'js:nextItemsSuccess',
        );
        $this->ajaxOptions = CMap::mergeArray($ajaxOptions, $this->ajaxOptions);
        $this->ajaxOptions['url']=$this->ajaxLink;
    }

    /**
     * Executes the widget.
     * This overrides the parent implementation by displaying the generated page buttons.
     */
    public function run() {
        if ($this->getItemCount() > $this->getPageSize() && !Yii::app()->getRequest()->isAjaxRequest) {
            ?>
            <div class="comment-item items-loader" style="display: none" >
                <div class="comment-item-in">
                    <div class="comment-more">
                        <img src="/i/comment-bottom-more.png" alt="" /> загрузка...
                        <script type="text/javascript">
                            window.page = 1;
                            var pagerOptions=<?= CJavaScript::encode(array('ajaxOptions'=>$this->ajaxOptions,'id'=>'post-list','isLastPage'=>false))?>;
                        </script>
            <?php
            
CHtml::ajaxLink($this->label, $this->ajaxLink, $this->ajaxOptions, array('id' => 'next-items')) 
            ?>
                    </div>
                </div>
            </div>
        <?php
        }
    }

}
