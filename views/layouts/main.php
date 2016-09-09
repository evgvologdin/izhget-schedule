<?php
/**
 * @var $this \yii\web\View
 */
use yii\helpers\Url;
use yii\helpers\Html;
use app\assets\AppAssets;
use app\assets\DevAssets;

if(YII_DEBUG) {
    DevAssets::register($this);
} else {
    AppAssets::register($this);
}
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="ru-RU">
<head>
    <meta charset="<?=Yii::$app->charset;?>">
    <meta name='yandex-verification' content='48756fbf598873b4' />
    <meta name="google-site-verification" content="bDfplKM8aQVrmNkWUhZwNVvbQ_5wdVTcwJHavb_z2MY" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta property="fb:admins" content="100001326342833"/>
    <meta property="og:title" content="<?=Yii::$app->name?>" />
    <meta property="og:site_name" content="<?=Yii::$app->name?>" />
    <meta property="og:description" content="" />
    <meta property="og:image" content="/client/img/screen.png" />
    <link rel="shortcut icon" href="/client/icons/favicon.png" />
    <link rel="apple-touch-icon" href="/client/icons/touch-iphone.png" />
    <link rel="apple-touch-icon" sizes="76x76" href="/client/icons/touch-ipad.png" />
    <link rel="apple-touch-icon" sizes="120x120" href="/client/icons/touch-iphone-retina.png" />
    <link rel="apple-touch-icon" sizes="152x152" href="/client/icons/touch-ipad-retina.png" />
    <link rel="manifest" href="/manifest.json" />
    <title><?=Html::encode(Yii::$app->name);?></title>
    <?php $this->head(); ?>
</head>
<body class="wait">
    <?php $this->beginBody(); ?>
    <header>
        <h1 class="text-center"><?=Html::a(Yii::$app->name, ['/']);?></h1><hr />
    </header>
    <?=$content;?>
    <footer>
        <div class="wrap">
            <p class="text-right">
                <small>Разработка сайта&nbsp;&mdash; <?=Html::a('Евгений Вологдин', 'http://evgvologdin.ru')?>, 2015</small>
            </p>
            <p class="text-right">
                <small>Источник расписания&nbsp;&mdash; <?=Html::a('ИжГЭТ', 'http://izhget.ru')?></small>
            </p>
        </div>
    </footer>
    <?php $this->endBody(); ?>
    <?php if(YII_DEBUG === false):?>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript"> 
        (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter32439430 = new Ya.Metrika({ id:32439430, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true, trackHash:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");
    </script>
    <noscript>
        <div><img src="https://mc.yandex.ru/watch/32439430" style="position:absolute; left:-9999px;" alt="" /></div>
    </noscript>
    <!-- /Yandex.Metrika counter -->
    <?php endif;?>
</body>
</html>
<?php $this->endPage();?>
