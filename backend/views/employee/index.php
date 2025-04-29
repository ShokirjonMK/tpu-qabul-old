<?php

use common\models\Employee;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\MaskedInput;
use common\models\Status;
use yii\helpers\ArrayHelper;
use common\models\Constalting;
use common\models\AuthItem;

/** @var yii\web\View $this */
/** @var common\models\EmployeeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var \common\models\User $user */
/** @var \common\models\Constalting $cons */
/** @var \common\models\AuthItem $roles */
/** @var \common\models\AuthItem $role */

$this->title = Yii::t('app', 'Xodimlar');
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
?>
<div class="employee-index">

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

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'first_name',
            'last_name',
            [
                'attribute' => 'cons_id',
                'contentOptions' => ['date-label' => 'cons_id'],
                'format' => 'raw',
                'value' => function($model) {
                    return $model->user->cons->name;
                },
                'filter' => Html::activeDropDownList($searchModel, 'cons_id',
                    ArrayHelper::map($cons , 'id' , 'name'),
                    ['class'=>'form-control','prompt' => 'Hamkor ...']),
            ],
            [
                'attribute' => 'role',
                'contentOptions' => ['date-label' => 'role'],
                'format' => 'raw',
                'value' => function($model) {
                    return "<div class='badge-table-div active'><span>{$model->user->user_role}</span></div>";
                },
                'filter' => Html::activeDropDownList($searchModel, 'role',
                    ArrayHelper::map($roles , 'name' , 'description'),
                    ['class'=>'form-control','prompt' => 'Rol ...']),
            ],
            [
                'class' => ActionColumn::className(),
                'contentOptions' => ['date-label' => 'Harakatlar' , 'class' => 'gridActionColumn'],
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
                    'delete' => function ($url, $model) {
                        $url = Url::to(['delete', 'id' => $model->id]);
                        return Html::a('<i class="fa fa-trash"></i>', $url, [
                            'title'        => 'delete',
                            'class' => 'tableIcon',
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method'  => 'post',
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>


</div>
