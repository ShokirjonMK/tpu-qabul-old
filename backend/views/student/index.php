<?php

use common\models\StudentDtm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use common\models\Status;
use kartik\export\ExportMenu;
use yii\widgets\LinkPager;
use common\models\Course;


/** @var yii\web\View $this */
/** @var common\models\StudentDtmSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var \common\models\EduYearType $edu_type */

$this->title = Yii::t('app', 'Students');
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
$user = Yii::$app->user->identity;
?>
<div class="student-dtm-index">

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

    <?php echo $this->render('_search', ['model' => $searchModel , 'edu_type' => $edu_type]); ?>

    <?php $data = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'O\'chirish',
            'contentOptions' => ['date-label' => 'O\'chirish'],
            'format' => 'raw',
            'value' => function($model) {
                return Html::a("<span>O'chirish</span>", ['dele', 'id' => $model->id], [
                    'class' => 'badge-table-div danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Rostdan ma\'lumotni o\'chirmoqchimisiz?'),
                        'method' => 'post',
                    ],
                ]);
            },
        ],
        [
            'attribute' => 'F.I.O',
            'contentOptions' => ['date-label' => 'F.I.O' ,'class' => 'wid250'],
            'format' => 'raw',
            'value' => function($model) {
                if ($model->user->step == 1) {
                    return "---- ---- ----";
                }
                return $model->last_name.' '.$model->first_name.' '.$model->middle_name. " | ".$model->passport_serial.' '.$model->passport_number;
            },
        ],
        [
            'attribute' => 'Yo\'nalish',
            'contentOptions' => ['date-label' => 'F.I.O' ,'class' => 'wid250'],
            'format' => 'raw',
            'value' => function($model) {
                $direction = $model->direction;
                if ($direction) {
                    return $direction->code.' - '.$direction->name_uz;
                }
                return "---- -----";
            },
        ],
        [
            'attribute' => 'Ta\'lim shakli',
            'contentOptions' => ['date-label' => 'F.I.O' ,'class' => 'Ta\'lim shakli'],
            'format' => 'raw',
            'value' => function($model) {
                $eduForm = $model->eduForm;
                if ($eduForm) {
                    return $eduForm->name_uz;
                }
                return "---- -----";
            },
        ],
        [
            'attribute' => 'Bosqich',
            'contentOptions' => ['date-label' => 'F.I.O' ,'class' => 'Ta\'lim shakli'],
            'format' => 'raw',
            'value' => function($model) {
                if ($model->edu_type_id == 2 && $model->course_id != null) {
                    $courseId = $model->course_id + 1;
                    return Course::findOne($courseId)->name_uz;
                }
                return "1 - Kurs";
            },
        ],
        [
            'attribute' => 'Ta\'lim tili',
            'contentOptions' => ['date-label' => 'F.I.O' ,'class' => 'Ta\'lim tili'],
            'format' => 'raw',
            'value' => function($model) {
                $lang = $model->language;
                if ($lang) {
                    return $lang->name_uz;
                }
                return "---- -----";
            },
        ],
        [
            'attribute' => 'Tel raqam',
            'contentOptions' => ['date-label' => 'Tel raqam'],
            'format' => 'raw',
            'value' => function($model) {
                return $model->user->username;
            },
        ],
        [
            'attribute' => 'Ro\'yxatga olingan sana',
            'contentOptions' => ['date-label' => 'Ro\'yxatga olingan sana'],
            'format' => 'raw',
            'value' => function($model) {
                $target = $model->user->target;
                $text = '';
                if ($target) {
                    $text = "<div class='badge-table-div active mt-2'>".$model->user->target->name."</div>";
                }
                return "<div><div>". date("Y-m-d H:i" , $model->user->created_at) ."</div>$text</div>";
            },
        ],
        [
            'attribute' => 'status',
            'contentOptions' => ['date-label' => 'status'],
            'format' => 'raw',
            'value' => function($model) {
                $text = '';
                if ($model->student_operator_type_id != null) {
                    $text = "<br><div class='badge-table-div active mt-2'>".$model->stdOperatorType->name."</div>";
                }
                return $model->eduStatus.$text;
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
    ]; ?>

    <div class="form-section">
        <div class="form-section_item">
            <div class="d-flex justify-content-between align-items-center">
                <p><b>Jami soni: &nbsp; <?= $dataProvider->totalCount ?></b></p>

                <?php if ($user->user_role == 'supper_admin'): ?>
                    <div class="page_export d-flex align-items-center gap-4">
                        <div>
                            <?php echo ExportMenu::widget([
                                'dataProvider' => $dataProvider,
                                'columns' => $data,
                                'asDropdown' => false,
                            ]); ?>
                        </div>

                        <button class="b-btn b-primary" id="allExport">
                            All Export
                        </button>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <?php echo GridView::widget([
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


<?php
$js = <<<JS
    $(document).ready(function() {
        $("#allExport").on('click', function () {
            $('.pageLoading').fadeIn('slow');
            $.ajax({
                url: '../student/excel-export/',
                type: 'POST',
                success: function (data) {
                    var a = document.createElement('a');
                        a.href = data;
                        a.download = data;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        $('.pageLoading').fadeOut('slow');
                },
                error: function () {
                    $('.pageLoading').fadeOut('slow');
                    alert("Xatolik!!!");
                }
            });
        });
    });
JS;
$this->registerJs($js);
?>
