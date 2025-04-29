<?php

use common\models\Telegram;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use kartik\export\ExportMenu;

/** @var yii\web\View $this */
/** @var common\models\TelegramSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Telegrams');
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
?>
<div class="telegram-index">

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


    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    $data = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'O\'chirish',
            'contentOptions' => ['data-label' => 'O\'chirish'],
            'format' => 'raw',
            'value' => function($model) {
                return Html::a(
                    "O'chirish",
                    Url::to(['delete', 'id' => $model->id]),
                    [
                        'class' => 'badge-table-div active',
                        'data' => [
                            'method' => 'post',
                            'confirm' => 'Haqiqatan ham ushbu elementni o\'chirishni xohlaysizmi?',
                        ],
                    ]
                );
            },
        ],
        [
            'attribute' => 'F.I.O',
            'contentOptions' => ['date-label' => 'F.I.O' ,'class' => 'wid250'],
            'format' => 'raw',
            'value' => function($model) {
                if ($model->step < 6) {
                    return "----- ----- -----";
                }
                return $model->last_name.' '.$model->first_name.' '.$model->middle_name . ' | '.$model->passport_serial.' '.$model->passport_number;
            },
        ],
        'chat_id',
        'phone',
        [
            'attribute' => 'step',
            'contentOptions' => ['date-label' => 'step'],
            'format' => 'raw',
            'value' => function($model) {
                return "<div class='badge-table-div active'><span>". $model->step ." - qadam</span></div>";
            },
        ],
        [
            'attribute' => 'Status',
            'contentOptions' => ['date-label' => 'Status'],
            'format' => 'raw',
            'value' => function($model) {
                if ($model->bot_status == 0) {
                    return "<div class='badge-table-div active'><span>Jarayonda</span></div>";
                } elseif ($model->bot_status == 1) {
                    $date = "<br><div class='badge-table-div active mt-2'><span>". $model->confirm_date ."</span></div>";
                    return "<div class='badge-table-div active'><span>Kelib tushdi</span></div>".$date;
                } elseif ($model->bot_status == 2) {
                    return "<div class='badge-table-div active'><span>Tasdiqlandi</span></div>";
                } elseif ($model->bot_status == 3) {
                    return "<div class='badge-table-div active'><span>Bekor qilindi</span></div>";
                }  elseif ($model->bot_status == 5) {
                    return "<div class='badge-table-div active'><span>Blocklandi</span></div>";
                }
            },
        ],
        [
            'attribute' => 'Batafsil',
            'contentOptions' => ['date-label' => 'Batafsil'],
            'format' => 'raw',
            'value' => function($model) {
                return "<a href='". Url::to(['view' , 'id' => $model->id]) ."' class='badge-table-div active'><span>Batafsil</span></a>";
            },
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
        'pager' => [
            'class' => LinkPager::class,
            'pagination' => $dataProvider->getPagination(),
            'firstPageLabel' => 'First',
            'lastPageLabel' => 'Last',
            'nextPageLabel' => false,
            'prevPageLabel' => false,
            'maxButtonCount' => 10,
        ],
    ]); ?>


</div>
