<div id="admin_control2">
    <?php
    Yii::app()->controller->renderFile(Yii::getPathOfAlias('admin.views.layouts._nav').'.php', array('display' => TbHtml::NAVBAR_DISPLAY_STATICTOP));
    $b1 = array();
    $b2 = array();
    $name = null;
    if (!empty(Yii::app()->controller->module)) {
        if (file_exists(Yii::app()->controller->module->basePath . DS . 'data' . DS . 'data.xml')) {
            $xml = simplexml_load_file(Yii::app()->controller->module->basePath . DS . 'data' . DS . 'data.xml');
            $name = (string) $xml->name[0];
            if (!empty($xml->control[0])) {
                foreach ($xml->control[0] as $value) {
                    $b1[] = array(
                        'url' => url((string) $value['url']),
                        'label' => (string) $value,
                            //   'htmlOptions'=>array('data-toggle'=>'modal','data-target'=>'#modal_admin')
                    );
                }
            }


            if (!empty(Yii::app()->controller->model) && !empty($xml->operation[0])) {
                $modelname = get_class(Yii::app()->controller->model);
                $i = 0;
                while (count($xml->operation[0]->model) > $i && $xml->operation[0]->model[$i]['name'] != $modelname)
                    $i++;

                if ($i < count($xml->operation[0]->model)) {
                    $n = $xml->operation[0]->model[$i];
                    $idget = isset($n['getparam']) ? $n['getparam'] : 'id';
                    if (isset($n['key'])) {
                        $id = Yii::app()->controller->model->{$n['key']};
                    }
                    else
                        $id = Yii::app()->controller->model->primaryKey;
                    $b2 = array();
                    foreach ($n->url as $value) {
                        $b2[] = array(
                            'url' => url((string) $value['url'], array($idget => $id)),
                            'label' => (string) $value
                        );
                        //
                        if (strripos((string) $value['url'], 'delete') !== false) {
                            $b2[count($b2) - 1]['htmlOptions'] = array('onClick' => 'return confirm(\'Вы уверены?\');');
                        }
                    }
                }
            }
        }
    }

    if (Yii::app()->hasModule('seo')) {
        
        $seobl=array();
        if (!empty(Yii::app()->controller->model))
        {
            
            $pk=Yii::app()->controller->model->primaryKey;
            if (!empty($pk) && !is_array($pk))
            {
              $cl=  get_class(Yii::app()->controller->model);  
              $pk=(string)$pk;
            $seobl[]=array(
                'label'=>'страница "'.$cl.'" #'.$pk,
                'url'=>url('seo/admin/setpage',array('type'=>'model','model'=>$cl,'modelId'=>$pk))
                );
            
            }
        }
        
        $seobl[]=array(
                'label'=>'для страниц этого типа ('.Yii::app()->controller->route.')',
                'url'=>url('seo/admin/setpage',array('type'=>'route','url'=>  urlencode(Yii::app()->controller->route)))
                );
        
         $seobl[]=array(
                'label'=>'для URL ('.Yii::app()->request->getUrl().')',
                'url'=>url('seo/admin/setpage',array('type'=>'url','url'=>urlencode(Yii::app()->request->getUrl())))
                );
        
        
        
        
        $b1[] = array(
            'url' => url('seo/admin/index'),
            'label' => 'seo',
             'items'=>$seobl
        );
    }

    if (!empty($b1) || !empty($b2)) {
        ?>
        <div style="background: white;padding: 10px;">
        <?php if (!empty($name)) { ?>
                <div style="text-align: center;font-weight: bold;font-size: 16px;font-family: arial;">
            <?= $name ?>
                </div>
        <?php } ?>


        <?php
        if (!empty($b1)) {
            ?>
                <div style="text-align: center;margin-top: 5px;">
                    <?php echo TbHtml::buttonGroup($b1, array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>
                </div>



                <?php
            }


            if (!empty($b2)) {
                ?>
                <div style="text-align: center;margin-top: 7px;">
                    <?php echo TbHtml::buttonGroup($b2, array('color' => TbHtml::BUTTON_COLOR_INVERSE)); ?>
                </div>



                <?php
            }
            ?>


        </div>
            <?php
        }
        ?>
</div>