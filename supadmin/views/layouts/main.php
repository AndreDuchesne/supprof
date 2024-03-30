<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <?= Html::csrfMetaTags() ?>
    <?= $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]); ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">

    <?php
    NavBar::begin([
        'brandLabel' => '<span class="logohilight">suPProf (Gestion)</span>',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand navbar-dark bg-dark',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar nav justify-content-end nav nav-pills'],
        'items' => [
            ['label' => 'Accueil', 'url' => ['/site/index']],
            ['label' => 'À Propos', 'url' => ['/site/about']],
            ['label' => 'Support', 'url' => ['/site/contact']],
            [
                    'label' => 'Gestion',
                    'url' => ['/site/gestion'],
                    'linkOptions' => ['data-method' => 'post'],
                    'visible' => !Yii::$app->user->isGuest
            ],            
            Yii::$app->user->isGuest ?
                ['label' => 'Connexion', 'url' => ['/site/login']] :
                [
                    'label' => 'Déconnexion (' . Yii::$app->user->identity->prenom . ' ' . Yii::$app->user->identity->nom . ')',
                    'url' => ['/site/logout'],
                    ['class' => 'nav-link btn btn-link logout'],
                    'linkOptions' => ['data-method' => 'post']
                ],
        ],
    ]);
    NavBar::end();
    ?>
    

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'class' => 'breadcrumb',
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; suPProf <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
