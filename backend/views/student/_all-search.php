<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\Languages;
use common\models\EduYear;
use common\models\EduYearForm;
use common\models\Direction;
use common\models\Status;
use kartik\date\DatePicker;
use common\models\Target;

/** @var yii\web\View $this */
/** @var common\models\StudentPerevotSearch $model */
/** @var yii\widgets\ActiveForm $form */
/** @var \common\models\EduYearType $edu_type */

?>

<div class="student-perevot-search">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
    ]); ?>

    <div class="form-section mb-4">
        <div class="form-section_item">

            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'username')
                            ->widget(\yii\widgets\MaskedInput::class, [
                                'mask' => '+\9\9\8 (99) 999-99-99',
                                'options' => [
                                    'placeholder' => '+998 (__) ___-__-__',
                                ],
                            ]) ?>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'passport_serial')->textInput([
                            'maxlength' => true,
                            'placeholder' => '__',
                            'oninput' => "this.value = this.value.replace(/\\d/, '').toUpperCase()"
                        ]) ?>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'passport_number')->widget(\yii\widgets\MaskedInput::class, [
                            'mask' => '9999999',
                            'options' => [
                                'placeholder' => '_______',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>

            <div class="form-group d-flex justify-content-end gap-2">
                <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'b-btn b-primary']) ?>
                <?= Html::a(Yii::t('app', 'Reset'), ['all'], ['class' => 'b-btn b-secondary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

