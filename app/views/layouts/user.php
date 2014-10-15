<!DOCTYPE html>
<html lang="ru">
    <head>
        <?php include 'parts/_head.php' ?>
    </head>
    <body>
        <?php include 'parts/_header.php' ?>
        <div class="content content_profile-hotel">
            <?php include 'parts/user/_profile.php'; ?>
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
        <?php include 'parts/_footer.php' ?>
        <div class="overlay-all" onclick="js_close_all()"></div>
        <div class="overlay"></div>
        <?php include 'parts/_photoPopup.php'; ?>
    </body>
</html>