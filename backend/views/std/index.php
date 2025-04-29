<?php

use common\models\Std;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/** @var yii\web\View $this */
/** @var common\models\StdSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Talabalar');
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
?>
<div class="std-index">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <?php
            foreach ($breadcrumbs['item'] as $item) {
                echo "<li class='breadcrumb-item'><a href='". Url::to($item['url']) ."'>". $item['label'] ."</a></li>";
            }
            ?>
            <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
        </ol>
    </nav>

    <div class="mb-3 mt-4">
        <?= Html::a(Yii::t('app', 'Qo\'shish'), ['create'], ['class' => 'b-btn b-primary']) ?>
    </div>

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?php
     $data = [
         ['class' => 'yii\grid\SerialColumn'],
         [
             'attribute' => 'F.I.O',
             'contentOptions' => ['date-label' => 'F.I.O' ,'class' => 'wid250'],
             'format' => 'raw',
             'value' => function($model) {
                 return $model->last_name.' '.$model->first_name.' '.$model->middle_name;
             },
         ],
         'passport_pin',
         [
             'attribute' => 'seriya va raqam',
             'contentOptions' => ['date-label' => 'seriya va raqam'],
             'format' => 'raw',
             'value' => function($model) {
                 return $model->passport_serial.' '.$model->passport_number;
             },
         ],
         [
             'attribute' => 'Jinsi',
             'contentOptions' => ['date-label' => 'Jinsi'],
             'format' => 'raw',
             'value' => function($model) {
                 if ($model->gender == 1) {
                     return "Erkak";
                 } else {
                     return "Ayol";
                 }
             },
         ],
         'birthday',
         'student_phone',
         [
             'class' => ActionColumn::className(),
             'contentOptions' => ['date-label' => 'Harakatlar' , 'class' => 'd-flex justify-content-around'],
             'header'=> 'Harakatlar',
             'buttons'  => [
                 'view'   => function ($url, $model) {
                     $url = Url::to(['view', 'id' => $model->id]);
                     return Html::a('<i class="fa fa-eye"></i>', $url, [
                         'title' => 'view',
                         'class' => 'tableIcon',
                     ]);
                 },
                 'update' => function ($url, $model) {
                     $url = Url::to(['update', 'id' => $model->id]);
                     return Html::a('<i class="fa-solid fa-pen-to-square"></i>', $url, [
                         'title' => 'update',
                         'class' => 'tableIcon',
                     ]);
                 },
                 'delete' => function () {
                     return false;
                 },
             ]
         ],
     ];


    ?>

    <div class="form-section">
        <div class="form-section_item">
            <div class="d-flex justify-content-between align-items-center">
                <p><b>Jami soni: &nbsp; <?= $dataProvider->totalCount ?></b></p>

                <div class="page_export d-flex align-items-center gap-4">
                    <div>
                        <?php echo ExportMenu::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => $data,
                            'asDropdown' => false,
                        ]); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $data,
    ]); ?>


</div>
