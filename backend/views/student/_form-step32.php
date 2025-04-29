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
/** @var EduYear $eduYear */
/** @var $id */

$languages = Languages::find()->where(['is_deleted' => 0 , 'status' => 1])->all();
$eduYearForms = EduYearForm::getEduFormName($eduYear);
$model->language_id = $student->language_id;
$model->edu_year_form_id = $student->edu_year_form_id;
$model->direction_id = $student->direction_id;
$model->edu_name = $student->edu_name;
$model->edu_direction = $student->edu_direction;
$model->direction_course_id = $student->direction_course_id;
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
            $courses[$directionCours->id] = $directionCours->course->name_uz;
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
            $data[$direction->id] = $direction->code. ' - '. $direction->name_uz. ' - '.$direction->eduType->name_uz;
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
            'options' => ['placeholder' => 'Ta\'lim  tilini tanlang ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Ta\'lim tili <span>*</span>'); ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'edu_year_form_id')->widget(Select2::classname(), [
            'data' => $eduYearForms,
            'options' => ['placeholder' => 'Ta\'lim shaklini tanlang ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Ta\'lim shakli <span>*</span>');; ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'direction_id')->widget(Select2::classname(), [
            'data' => $data,
            'options' => ['placeholder' => 'Yo\'nalish tanlang ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Ta\'lim Yo\'nalishi <span>*</span>');; ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'direction_course_id')->widget(Select2::classname(), [
            'data' => $courses,
            'options' => ['placeholder' => 'Yakunlagan bosqichingiz ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Yakunlagan bosqichingiz <span>*</span>');; ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'edu_name')->textInput(['placeholder' => 'Avvalgi OTM nomi ...'])->label('Avvalgi OTM nomi <span>*</span>');; ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'edu_direction')->textInput(['placeholder' => 'Avvalgi yo\'nalish nomi ...'])->label('Avvalgi yo\'nalish nomi <span>*</span>');; ?>
    </div>

    <div class="d-flex justify-content-center mb-3 mt-3">
        <?= Html::submitButton('Ma\'lumotni saqlash', ['class' => 'b-btn b-primary', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
    $(document).ready(function() {
        $("#stepthree2-language_id").on('change', function () {
            var form_id = $("#stepthree2-edu_year_form_id").val();
            var lang_id = $(this).val();
            var type_id = $student->edu_year_type_id;
            if (form_id > 0) {
                $.ajax({
                    url: '../student/direction/',
                    data: {lang_id: lang_id, form_id: form_id , type_id: type_id},
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
            var type_id = $student->edu_year_type_id;
            if (lang_id > 0) {
                $.ajax({
                    url: '../student/direction/',
                    data: {lang_id: lang_id, form_id: form_id , type_id: type_id},
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
            var type_id = $student->edu_year_type_id;
            if (dir_id > 0) {
                $.ajax({
                    url: '../student/direction-course/',
                    data: {dir_id: dir_id , type_id: type_id},
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




