<!DOCTYPE html>
<html lang="ru">
    <head>
        <?php
        $allocation=$this->model;  // в allocation/_leftbar используется
         $cs = Yii::app()->getClientScript();
        $cs->registerCssFile('/css/b-chat-page.css');
        include 'parts/_head.php' ?>
    </head>
    <body>
        <?php include 'parts/_header.php' ?>
        <div class="content content_profile-hotel">
            <div class="page-wrap">
                 <div class="wrapper">
            <table class="content-tbl">
                <tr>
                    <?php include 'parts/allocation/_leftbar.php'; ?>
                    <?php include 'parts/_body.php'; ?>
                    <?php include 'parts/allocation/_rightbar.php'; ?>
                </tr>
            </table>
        </div>
            </div>
        </div>
        <?php include 'parts/_footer.php' ?>
        <?php $this->widget('RatingPopup'); ?>
        <div class="overlay-all" onclick="js_close_all()"></div>
        <div class="overlay"></div>
        <script type="text/javascript">
            window.modeSideMenu='fixed';
        </script>
    </body>
</html>