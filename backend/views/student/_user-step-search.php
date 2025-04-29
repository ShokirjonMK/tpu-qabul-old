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

$targets = Target::find()
    ->where(['is_deleted' => 0])
    ->orderBy('id desc')
    ->all();

?>

<div class="student-perevot-search">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
    ]); ?>

    <div class="form-section mb-4">
        <div class="form-section_item">

            <div class="row">

                <div class="col-lg-2 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'first_name') ?>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'last_name') ?>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'middle_name') ?>
                    </div>
                </div>

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

                <div class="col-lg-1 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'passport_serial')->textInput([
                            'maxlength' => true,
                            'placeholder' => '__',
                            'oninput' => "this.value = this.value.replace(/\\d/, '').toUpperCase()"
                        ])?>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'passport_number')->widget(\yii\widgets\MaskedInput::class, [
                            'mask' => '9999999',
                            'options' => [
                                'placeholder' => '_______',
                            ],
                        ]) ?>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'status')->widget(Select2::classname(), [
                            'data' => Status::userStatusUpdate(),
                            'options' => ['placeholder' => 'Status tanlang ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Status <span>*</span>'); ?>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'step')->widget(Select2::classname(), [
                            'data' => Status::step(),
                            'options' => ['placeholder' => 'Qadam tanlang ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Qadam <span>*</span>');; ?>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'start_date')->widget(DatePicker::classname(), [
                            'options' => ['placeholder' => 'Start date ...'],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ]
                        ])->label('Start Date <span>*</span>'); ?>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'end_date')->widget(DatePicker::classname(), [
                            'options' => ['placeholder' => 'End date ...'],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ]
                        ])->label('Start Date <span>*</span>'); ?>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'target_id')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map($targets, 'id', 'name'),
                            'options' => ['placeholder' => 'Target nomini tanlang...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Target <span>*</span>'); ?>
                    </div>
                </div>

            </div>

            <div class="form-group d-flex justify-content-end gap-2">
                <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'b-btn b-primary']) ?>
                <?= Html::a(Yii::t('app', 'Reset'), ['user-step'], ['class' => 'b-btn b-secondary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

