<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Employee $model */
/** @var common\models\User $user */

$role = $user->authItem;
$this->title = 'Xodim ma\'lumoti';
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
if ($role->name != 'moderator') {
    $breadcrumbs['item'][] = [
        'label' => Yii::t('app', 'Xodimlar'),
        'url' => ['index'],
    ];
}
\yii\web\YiiAsset::register($this);
?>
<div class="employee-view">

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

    <p class="mb-3">
        <?= Html::a(Yii::t('app', 'Tahrirlash'), ['update', 'id' => $model->id], ['class' => 'b-btn b-primary']) ?>

        <?php if ($user->authItem->name == 'supper_admin') : ?>
            <?= Html::a(Yii::t('app', 'Bloklash'), ['delete', 'id' => $model->id], [
                'class' => 'b-btn b-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Rostan ham userni blocklamoqchimisiz?'),
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <div class="grid-view">

        <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            'first_name',
            'last_name',
            'middle_name',
            'phone',
            [
                'attribute' => 'gender',
                'contentOptions' => ['date-label' => 'gender'],
                'format' => 'raw',
                'value' => function($model) {
                    if ($model->gender == 1) {
                        return "<div class='badge-table-div active'><span>Erkak</span></div>";
                    } elseif ($model->gender == 0) {
                        return "<div class='badge-table-div active'><span>Ayol</span></div>";
                    }
                },
            ],
            'brithday',
            [
                'attribute' => 'username',
                'contentOptions' => ['date-label' => 'username'],
                'format' => 'raw',
                'value' => function($model) {
                    return $model->user->username;
                },
            ],
            'password',
            [
                'attribute' => 'cons_id',
                'contentOptions' => ['date-label' => 'cons_id'],
                'format' => 'raw',
                'value' => function($model) {
                    $cons = $model->user->cons;
                    if ($cons) {
                        return $cons->name;
                    }
                    return '-----';
                },
            ],
            [
                'attribute' => 'status',
                'contentOptions' => ['date-label' => 'status'],
                'format' => 'raw',
                'value' => function($model) {
                    if ($model->status == 10) {
                        return "<div class='badge-table-div active'><span>Faol</span></div>";
                    } elseif ($model->status == 0) {
                        return "<div class='badge-table-div active'><span>Blocklangan</span></div>";
                    }
                },
            ],
            [
                'attribute' => 'created_at',
                'contentOptions' => ['date-label' => 'created_at'],
                'format' => 'raw',
                'value' => function($model) {
                    return date('Y-m-d H:i:s' , $model->created_at);
                },
            ],
            [
                'attribute' => 'updated_at',
                'contentOptions' => ['date-label' => 'updated_at'],
                'format' => 'raw',
                'value' => function($model) {
                    return date('Y-m-d H:i:s' , $model->updated_at);
                },
            ],
            [
                'attribute' => 'created_by',
                'contentOptions' => ['date-label' => 'created_by'],
                'format' => 'raw',
                'value' => function($model) {
                    $emp = $model->createdBy->employee;
                    return $emp->first_name.' '.$emp->last_name;
                },
            ],
            [
                'attribute' => 'updated_by',
                'contentOptions' => ['date-label' => 'updated_by'],
                'format' => 'raw',
                'value' => function($model) {
                    $emp = $model->updatedBy->employee;
                    return $emp->first_name.' '.$emp->last_name;
                },
            ],
        ],
    ]) ?>

    </div>
</div>
