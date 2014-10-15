<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

        <?php
        Yii::app()->bootstrap->register(); 
        Yii::app()->clientScript->registerPackage('admin');
        
        ?>
    </head>

    <body>

        <?php
        $this->renderFile(__DIR__.'/_nav.php');
        ?>

        <div class="container" id="page">

            <?php if (isset($this->breadcrumbs)): ?>
                <?php
                $this->widget('bootstrap.widgets.TbBreadcrumb', array(
                    'links' => $this->breadcrumbs,
                    'homeUrl' => Yii::app()->baseUrl . '/admin'
                ));
                ?><!-- breadcrumbs -->
            <?php
            endif;

            $this->widget('bootstrap.widgets.TbAlert');

            echo $content;
            ?>

            <div class="clear"></div>

            <div id="footer"></div><!-- footer -->

        </div><!-- page -->
    </body>
</html>