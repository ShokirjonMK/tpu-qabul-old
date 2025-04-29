<?php

use common\models\Direction;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use common\models\Student;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\Languages;
use common\models\EduYear;
use common\models\EduYearForm;
use common\models\Status;
use common\models\DirectionCourse;


/** @var $model */
/** @var Student $student */
/** @var $id */

$lang = Yii::$app->language;

$languages = Languages::find()->where(['is_deleted' => 0 , 'status' => 1])->all();
$eduYear = EduYear::find()->where(['is_deleted' => 0 , 'status' => 1])->one();
$eduYearForms = EduYearForm::getEduFormName($eduYear);
$model->language_id = $student->language_id;
$model->edu_year_form_id = $student->edu_year_form_id;
$model->direction_id = $student->direction_id;
$model->direction_course_id = $student->direction_course_id;
$model->edu_name = $student->edu_name;
$model->edu_direction = $student->edu_direction;
$data = [];
$courses = [];
if ($model->direction_course_id != null && $model->direction_id != null) {
    $directionCourses = DirectionCourse::find()
        ->where([
            'direction_id' => $model->direction_id,
            'status' => 1,
            'is_deleted' => 0
        ])->all();
    if (count($directionCourses) > 0) {
        foreach ($directionCourses as $directionCours) {
            $courses[$directionCours->id] = $directionCours->course['name_'.$lang];
        }
    }
}
if ($model->direction_id != null) {
    $directions = Direction::find()
        ->where([
            'edu_year_type_id' => $student->edu_year_type_id,
            'edu_year_form_id' => $model->edu_year_form_id,
            'language_id' => $model->language_id,
            'status' => 1,
            'is_deleted' => 0
        ])->all();
    if (count($directions) > 0) {
        foreach ($directions as $direction) {
            $data[$direction->id] = $direction['name_'.$lang];
        }
    }
}
?>

<div class="step_one_box">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'top40'],
        'fieldConfig' => [
            'template' => '{label}{input}{error}',
        ]
    ]); ?>

    <div class="form-group">
        <?= $form->field($model, 'language_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($languages, 'id', 'name_uz'),
            'options' => ['placeholder' => Yii::t("app" , "a57")],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label(Yii::t("app" , "a59").' <span>*</span>'); ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'edu_year_form_id')->widget(Select2::classname(), [
            'data' => $eduYearForms,
            'options' => ['placeholder' => Yii::t("app" , "a58")],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label(Yii::t("app" , "a60").' <span>*</span>');; ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'direction_id')->widget(Select2::classname(), [
            'data' => $data,
            'options' => ['placeholder' => Yii::t("app" , "a61")],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label(Yii::t("app" , "a62").' <span>*</span>');; ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'direction_course_id')->widget(Select2::classname(), [
            'data' => $courses,
            'options' => ['placeholder' => Yii::t("app" , "a74")],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label(Yii::t("app" , "a75").' <span>*</span>');; ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'edu_name')->textInput(['placeholder' => Yii::t("app" , "a76")])->label(Yii::t("app" , "a77").' <span>*</span>');; ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'edu_direction')->textInput(['placeholder' => Yii::t("app" , "a78")])->label(Yii::t("app" , "a79").' <span>*</span>');; ?>
    </div>

    <div class="step_btn_block top40">
        <?= Html::submitButton('Ma\'lumotni saqlash', ['class' => 'step_right_btn step_btn', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
    $(document).ready(function() {
        $("#stepthree2-language_id").on('change', function () {
            var form_id = $("#stepthree2-edu_year_form_id").val();
            var lang_id = $(this).val();
            if (form_id > 0) {
                $.ajax({
                    url: '../file/direction/',
                    data: {lang_id: lang_id, form_id: form_id},
                    type: 'POST',
                    success: function (data) {
                        $("#stepthree2-direction_id").html(data);
                        $("#stepthree2-direction_course_id").html(false);
                    },
                    error: function () {
                        alert("Xatolik!!!");
                    }
                });
            }
        });
        $("#stepthree2-edu_year_form_id").on('change', function () {
            var lang_id = $("#stepthree2-language_id").val();
            var form_id = $(this).val();
            if (lang_id > 0) {
                $.ajax({
                    url: '../file/direction/',
                    data: {lang_id: lang_id, form_id: form_id},
                    type: 'POST',
                    success: function (data) {
                        $("#stepthree2-direction_id").html(data);
                        $("#stepthree2-direction_course_id").html(false);
                    },
                    error: function () {
                        alert("Xatolik!!!");
                    }
                });
            }
        });
        
        $("#stepthree2-direction_id").on('change', function () {
            var dir_id = $(this).val();
            if (dir_id > 0) {
                $.ajax({
                    url: '../file/direction-course/',
                    data: {dir_id: dir_id},
                    type: 'POST',
                    success: function (data) {
                        $("#stepthree2-direction_course_id").html(data);
                    },
                    error: function () {
                        alert("Xatolik!!!");
                    }
                });
            }
        });
    });
JS;
$this->registerJs($js);
?>




