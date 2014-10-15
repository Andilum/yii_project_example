<!DOCTYPE html>
<html lang="ru">
<head>
    <?php include 'parts/allocation/_head.php' ?>
</head>
<body>
    <?php include 'parts/_header.php'; ?>
    <div class="content content_profile-hotel">
        <?php include 'parts/allocation/_profile.php'; ?>
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
    <?php include 'parts/_footer.php'; ?>
    <?php $this->widget('RatingPopup'); ?>
    <div class="overlay-all" onclick="js_close_all()"></div>
    <div class="overlay"></div>
    <?php include 'parts/_photoPopup.php'; ?>
</body>
</html>