<!DOCTYPE html>
<html lang="ru">
    <head>
        <?php
         $cs = Yii::app()->getClientScript();
        $cs->registerCssFile('/css/b-chat-page.css');
        
        include 'parts/_head.php' ?>
    </head>
    <body>
        <?php include 'parts/_header.php' ?>
        <div class="content">
            <div class="page-wrap">
                <div class="wrapper">

                    <table class="content-tbl">
                        <tr>
                            <?php
                            if (Yii::app()->controller->my) {
                                include 'parts/user/_leftbar_my.php';
                            } else {
                                include 'parts/user/_leftbar_other.php';
                            }
                            ?>

                            <?php include 'parts/_body.php'; ?>

                            <?php
                            if (Yii::app()->controller->my) {
                                include 'parts/user/_rightbar_my.php';
                            } else {
                                include 'parts/user/_rightbar_other.php';
                            }
                            ?>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <?php include 'parts/_footer.php' ?>
        <script type="text/javascript">
            window.modeSideMenu='fixed';
        </script>
    </body>
</html>