<?php
use yii\helpers\Url;
use yii\bootstrap5\Html;
use common\models\Student;
use common\models\Direction;
use common\models\Course;
use common\models\Exam;
use common\models\StudentPerevot;
use common\models\ExamSubject;
use common\models\StudentOferta;
use common\models\StudentDtm;
use common\models\StudentMagistr;
use common\models\StudentOperator;
use common\models\Constalting;


/** @var Student $model */
/** @var Direction $direction */
/** @var StudentPerevot $perevot */
/** @var Exam $exam */
/** @var ExamSubject $examSubjects */
/** @var StudentOperator $type */
/** @var Constalting $cons */

$this->title = 'Ma\'lumotlar tahlili';
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
if ($model->edu_year_type_id != null) {
    $breadcrumbs['item'][] = [
        'label' => $model->eduType->name_uz,
        'url' => ['index' , 'id' => $model->edu_year_type_id],
    ];
} else {
    $breadcrumbs['item'][] = [
        'label' => 'Chala arizalar',
        'url' => ['user-step'],
    ];
}
$full_name = $model->last_name.' '.$model->first_name.' '.$model->middle_name;
$direction = $model->direction;
$contract = false;
$contract_type = 0;
$contract_price = 0;
$eduType = false;
$cont = null;
if ($model->edu_year_type_id != null) {
    if ($model->edu_type_id == 1) {
        $exam = Exam::findOne([
            'student_id' => $model->id,
            'direction_id' => $model->direction_id,
            'is_deleted' => 0
        ]);
        if ($exam) {
            $examSubjects = $exam->examSubjects;
            if ($exam->status == 3) {
                $cont = $exam->id;
                $contract = true;
                $contract_type = $exam->contract_type;
                $contract_price = $exam->contract_price;
            }
        }
    } elseif ($model->edu_type_id == 2) {
        $perevot = StudentPerevot::findOne([
            'student_id' => $model->id,
            'direction_id' => $model->direction_id,
            'is_deleted' => 0
        ]);
        if ($perevot) {
            if ($perevot->file_status == 2) {
                $cont = $perevot->id;
                $contract = true;
                $contract_type = $perevot->contract_type;
                $contract_price = $perevot->contract_price;
            }
        }
    } elseif ($model->edu_type_id == 3) {
        $perevot = StudentDtm::findOne([
            'student_id' => $model->id,
            'direction_id' => $model->direction_id,
            'is_deleted' => 0
        ]);
        if ($perevot) {
            if ($perevot->file_status == 2) {
                $cont = $perevot->id;
                $contract = true;
                $contract_type = $perevot->contract_type;
                $contract_price = $perevot->contract_price;
            }
        }
    } elseif ($model->edu_type_id == 4) {
//        $perevot = StudentMagistr::findOne([
//            'student_id' => $model->id,
//            'direction_id' => $model->direction_id,
//            'is_deleted' => 0
//        ]);
//        if ($perevot) {
//            if ($perevot->file_status == 2) {
//                $contract = true;
//                $contract_type = $perevot->contract_type;
//                $contract_price = $perevot->contract_price;
//            }
//        }
    }
}

if ($model->eduType != null) {
    $eduType = $model->eduType;
}
if ($direction) {
    if ($direction->oferta == 1) {
        $oferta = StudentOferta::findOne([
            'direction_id' => $direction->id,
            'student_id' => $model->id,
            'status' => 1,
            'is_deleted' => 0
        ]);
    }
}
$type = $model->stdOperator;
$cons = $model->user->cons;
$ulugbek  = Yii::$app->user->identity;
?>

<div class="page">
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

    <div class="page-item mb-4">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="form-section">
                    <div class="form-section_item">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="view-info-right">
                                    <div class="subject_box">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="subject_box_left">
                                                <p>Telefon raqam:</p>
                                            </div>
                                            <div class="subject_box_right">
                                                <h6><?= $model->user->username ?></h6>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="subject_box_left">
                                                <p>Parol:</p>
                                            </div>
                                            <div class="subject_box_right">
                                                <h6><?= $model->password ?></h6>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="subject_box_left">
                                                <p>Status:</p>
                                            </div>
                                            <div class="subject_box_right">
                                                <h6><?php if ($model->user->status == 10) { echo "Faol";} elseif ($model->user->status == 9) { echo "To'liq ro'yhatdan o'tmagan";} else { echo "Bloklangan";} ?></h6>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="subject_box_left">
                                                <p>Raqam ro'yhatga olingan sana:</p>
                                            </div>
                                            <div class="subject_box_right">
                                                <h6><?= date("Y-m-d  H:i:s" , $model->user->created_at) ?></h6>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="subject_box_left">
                                                <p>Jarayoni:</p>
                                            </div>
                                            <div class="subject_box_right">
                                                <h6><?= $model->eduStatusName ?></h6>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="subject_box_left">
                                                <p>Qaysi oqimdan kelganligi:</p>
                                            </div>
                                            <div class="subject_box_right">
                                                <h6>
                                                    <?= $cons->name ?> &nbsp;&nbsp;
                                                    <?php
                                                        if ($cons->id == 1) {
                                                            echo "<a href='https://qabul.tpu.uz'>qabul.tpu.uz</a>";
                                                        } elseif ($cons->id == 2) {
                                                            echo "<a href='https://edu.tpu.uz'>edu.tpu.uz</a>";
                                                        } elseif ($cons->id == 3) {
                                                            echo "<a href='https://cons.tpu.uz'>cons.tpu.uz</a>";
                                                        }
                                                    ?>
                                                </h6>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="subject_box_left">
                                                <p>Bot status:</p>
                                            </div>
                                            <div class="subject_box_right">
                                                <h6><?= $model->user->chat_id ? 'Mavjud' : 'Mavjud emas' ?></h6>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="subject_box_left">
                                                <p>Qadam:</p>
                                            </div>
                                            <div class="subject_box_right">
                                                <h6><?= $model->user->step ?> - qadam</h6>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-3 align-items-center mt-3">
                                            <?= Html::a(Yii::t('app', 'Tahrirlash'), ['user-update', 'id' => $model->id],
                                                [
                                                    'class' => 'sub_links',
                                                    "data-bs-toggle" => "modal",
                                                    "data-bs-target" => "#studentUserEdite",
                                                ])
                                            ?>
                                            <?= Html::a(Yii::t('app', 'SMS habar yuborish'), ['send-sms', 'id' => $model->id],
                                                [
                                                    'class' => 'sub_links',
                                                    "data-bs-toggle" => "modal",
                                                    "data-bs-target" => "#studentSendSms",
                                                ])
                                            ?>

                                            <?php if ($ulugbek->id == 24416): ?>
                                                <?= Html::a(Yii::t('app', '1111'), ['cons11', 'id' => $model->id],
                                                    [
                                                        'class' => 'sub_links',
                                                    ])
                                                ?>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="view-info-right">
                                    <div class="subject_box">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="subject_box_left">
                                                <p>Oxirgi bog'langan operator:</p>
                                            </div>
                                            <div class="subject_box_right">
                                                <h6><?= $type ? $type->user->employeeFullName : '--------' ?></h6>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="subject_box_left">
                                                <p>Oxirgi bog'langan vaqt:</p>
                                            </div>
                                            <div class="subject_box_right">
                                                <h6><?= $type ? date("Y-m-d H:i:s" , $type->date) : '--------' ?></h6>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="subject_box_left">
                                                <p>Oxirgi xolati:</p>
                                            </div>
                                            <div class="subject_box_right">
                                                <h6><?= $type ? $type->studentOperatorType->name : '--------' ?></h6>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between mt-3">
                                            <div class="subject_box_left">
                                                <p>Oxirgi qisqacha ma'lumot:</p>
                                            </div>
                                            <div class="subject_box_right">
                                                <h6>
                                                    <?= $type ? $type->text : '--------' ?>
                                                </h6>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-3 align-items-center mt-5">
                                            <?= Html::a(Yii::t('app', 'Yangi suxbat natijasi'), ['student-operator-type/operator', 'id' => $model->id],
                                                [
                                                    'class' => 'sub_links',
                                                    "data-bs-toggle" => "modal",
                                                    "data-bs-target" => "#studentOperator",
                                                ])
                                            ?>
                                            <?= Html::a(Yii::t('app', 'Suxbat natijalari ro\'yxati'), ['student-operator-type/list', 'id' => $model->id],
                                                [
                                                    'class' => 'sub_links',
                                                    "data-bs-toggle" => "modal",
                                                    "data-bs-target" => "#studentOperatorList",
                                                ])
                                            ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="page-item mb-4">

        <div class="page_title mt-5 mb-3">
            <h6 class="title-h5">Pasport ma'lumoti</h6>
            <h6 class="title-link">
                <?= Html::a(Yii::t('app', 'Tahrirlash'), ['info', 'id' => $model->id],
                    [
                        "data-bs-toggle" => "modal",
                        "data-bs-target" => "#studentInfo",
                    ])
                ?>
            </h6>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="form-section">
                    <div class="form-section_item">
                        <div class="row">

                            <div class="col-md-3 col-12">
                                <div class="view-info-right">
                                    <p>F.I.O</p>
                                    <h6><?= $full_name ?></h6>
                                </div>
                            </div>

                            <div class="col-md-3 col-12">
                                <div class="view-info-right">
                                    <p>Tug'ilgan sana</p>
                                    <h6><?= $model->birthday ?></h6>
                                </div>
                            </div>

                            <div class="col-md-3 col-12">
                                <div class="view-info-right">
                                    <p>Pasport ma'lumoti</p>
                                    <h6><?= $model->passport_serial.' '.$model->passport_number ?></h6>
                                </div>
                            </div>

                            <div class="col-md-3 col-12">
                                <div class="view-info-right">
                                    <p>JShShIR</p>
                                    <h6><?= $model->passport_pin ?></h6>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>





    <?php if ($direction): ?>
        <?php if ($direction->oferta == 1): ?>
            <br>
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-section">
                        <div class="form-section_item">
                            <div class="view-info-right">
                                <p>Oferta holati</p>
                                <h6>
                                    <?php if ($oferta->file_status == 0) : ?>
                                        Yuklanmagan
                                    <?php elseif ($oferta->file_status == 1): ?>
                                        <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $oferta->file ?>">Kelib tushdi</a>
                                    <?php elseif ($oferta->file_status == 2): ?>
                                        <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $oferta->file ?>">Tasdiqlandi</a>
                                    <?php elseif ($oferta->file_status == 3): ?>
                                        <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $oferta->file ?>">Bekor qilindi</a>
                                    <?php endif; ?>
                                </h6>
                            </div>
                            <div class="subject_box mt-3">

                                <div class="d-flex gap-3 align-items-center">
                                    <?= Html::a(Yii::t('app', 'Oferta yuklash'), ['student/oferta-upload', 'id' => $oferta->id],
                                        [
                                            'class' => 'sub_links',
                                            "data-bs-toggle" => "modal",
                                            "data-bs-target" => "#studentOferUpload",
                                        ])
                                    ?>
                                    <?= Html::a(Yii::t('app', 'Oferta tasdiqlash'), ['student/oferta-confirm', 'id' => $oferta->id],
                                        [
                                            'class' => 'sub_links',
                                            "data-bs-toggle" => "modal",
                                            "data-bs-target" => "#studentOferConfirm",
                                        ]) ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="page-item mb-4">

        <div class="page_title mt-5">
            <h6 class="title-h5"><?php if ($eduType) { echo $eduType->name_uz; } else { echo "Chala user";} ?> ma'lumotlari</h6>
            <div class="user-profil mb-3">
                <div class="dropdown">
                    <button class="dropdown-toggle d-flex align-items-center edite_drop" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user_title">
                            <h6>Tahrirlash</h6>
                        </div>
                    </button>
                    <ul class="drop_m dropdown-menu dropdown-menu-end" style="">
                        <ul class="drop_m_ul">
                            <li>
                                <?= Html::a("<span>Qabul</span>", ['step-qabul', 'id' => $model->id],
                                    [
                                        "data-bs-toggle" => "modal",
                                        "data-bs-target" => "#studentQabulEdite",
                                    ])
                                ?>
                            </li>
                            <li>
                                <?= Html::a("<span>Perevot</span>", ['step-perevot', 'id' => $model->id],
                                    [
                                        "data-bs-toggle" => "modal",
                                        "data-bs-target" => "#studentPerevotEdite",
                                    ])
                                ?>
                            </li>
                            <li>
                                <?= Html::a("<span>DTM</span>", ['step-dtm', 'id' => $model->id],
                                    [
                                        "data-bs-toggle" => "modal",
                                        "data-bs-target" => "#studentPerevotEdite",
                                    ])
                                ?>
                            </li>
                            <?php if ($model->edu_type_id == 1) : ?>
                                <?php if ($contract) : ?>

                                    <li>
                                        <?= Html::a("<span>Test o'chirish</span><i class='fa-solid fa-trash'></i>", ['test-delete', 'id' => $exam->id],
                                            [
                                                'data' => [
                                                    'confirm' => Yii::t('app', 'Rostdan imtixonni o\'chirmoqchimisiz?'),
                                                    'method' => 'post',
                                                ],
                                            ])
                                        ?>
                                    </li>

                                <?php endif; ?>
                            <?php endif; ?>


                        </ul>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="form-section">
                    <div class="form-section_item">
                        <?php if ($direction): ?>
                            <div class="row">

                                <div class="col-md-4 col-12 mb-4">
                                    <div class="view-info-right">
                                        <p>Yo'nalish nomi</p>
                                        <h6><?= $direction->code.' - '.$direction->name_uz ?></h6>
                                    </div>
                                </div>

                                <div class="col-md-4 col-12  mb-4">
                                    <div class="view-info-right">
                                        <p>Ta'lim tili</p>
                                        <h6><?= $direction->language->name_uz ?></h6>
                                    </div>
                                </div>

                                <div class="col-md-4 col-12 mb-4">
                                    <div class="view-info-right">
                                        <p>Ta'lim shakli</p>
                                        <h6><?= $direction->eduForm->name_uz ?></h6>
                                    </div>
                                </div>

                                <?php if ($model->edu_type_id == 2) : ?>

                                    <div class="col-md-4 col-12">
                                        <div class="view-info-right">
                                            <p>Avvalgi OTM nomi</p>
                                            <h6><?= $model->edu_name ?></h6>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <div class="view-info-right">
                                            <p>Tamomlagan bosqich</p>
                                            <h6><?= Course::findOne(['id' => $model->course_id])->name_uz ?></h6>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-4">
                                        <div class="view-info-right">
                                            <p>Yuborilgan fayllar</p>
                                            <div class="row mt-2">
                                                <?php if ($perevot): ?>
                                                    <div class="col-lg-6 col-md-12">
                                                        <div class="subject_box">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="subject_box_left">
                                                                    <p>Holati:</p>
                                                                </div>
                                                                <div class="subject_box_right">
                                                                    <h6>
                                                                        <?php if ($perevot->file_status == 0) : ?>
                                                                            Yuklanmagan
                                                                        <?php elseif ($perevot->file_status == 1): ?>
                                                                            <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $perevot->file ?>">Kelib tushdi</a>
                                                                        <?php elseif ($perevot->file_status == 2): ?>
                                                                            <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $perevot->file ?>">Tasdiqlandi</a>
                                                                        <?php elseif ($perevot->file_status == 3): ?>
                                                                            <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $perevot->file ?>">Bekor qilindi</a>
                                                                        <?php endif; ?>
                                                                    </h6>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex gap-3 align-items-center mt-3">
                                                                <?= Html::a(Yii::t('app', 'Transkript yuklash'), ['student-perevot/upload', 'id' => $perevot->id],
                                                                    [
                                                                        'class' => 'sub_links',
                                                                        "data-bs-toggle" => "modal",
                                                                        "data-bs-target" => "#studentTrUpload",
                                                                    ])
                                                                ?>
                                                                <?= Html::a(Yii::t('app', 'Transkript tasdiqlash'), ['student-perevot/check', 'id' => $perevot->id],
                                                                    [
                                                                        'class' => 'sub_links',
                                                                        "data-bs-toggle" => "modal",
                                                                        "data-bs-target" => "#studentTrConfirm",
                                                                    ]) ?>
                                                            </div>

                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                <?php elseif ($model->edu_type_id == 1) : ?>
                                    <div class="col-md-4 col-12">
                                        <div class="view-info-right">
                                            <p>Imtixon turi</p>
                                            <h6>
                                                <?php if ($model->exam_type == 0) : ?>
                                                    Online
                                                <?php elseif ($model->exam_type == 1): ?>
                                                    Offline
                                                <?php endif; ?>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="view-info-right">
                                            <p>Holati</p>
                                            <h6>
                                                <?php if ($exam->status == 0) : ?>
                                                    Bekor qilingan
                                                <?php elseif ($exam->status == 1): ?>
                                                    Testga kirmagan
                                                <?php elseif ($exam->status == 2): ?>
                                                    Test ishlamoqda
                                                <?php elseif ($exam->status == 3): ?>
                                                    Testni yakunladi
                                                <?php endif; ?>
                                            </h6>
                                        </div>
                                    </div>
                                    <?php if ($exam->status == 3) : ?>
                                        <div class="col-md-4 col-12">
                                            <div class="view-info-right">
                                                <p>To'plangan ball</p>
                                                <h6>
                                                    <?= $exam->ball ?> ball
                                                </h6>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="col-md-12 mt-4">
                                        <div class="view-info-right">
                                            <p>Fanlari</p>
                                            <?php if (count($examSubjects) > 0) : ?>
                                                <div class="row mt-2">
                                                    <?php foreach ($examSubjects as $examSubject) : ?>
                                                        <div class="col-lg-6 col-md-12">
                                                            <div class="subject_box">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <div class="subject_box_left">
                                                                        <p>Fan nomi:</p>
                                                                    </div>
                                                                    <div class="subject_box_right">
                                                                        <h6><?= $examSubject->subject->name_uz ?></h6>
                                                                    </div>
                                                                </div>

                                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                                    <div class="subject_box_left">
                                                                        <p>Jami savollar soni:</p>
                                                                    </div>
                                                                    <div class="subject_box_right">
                                                                        <h6><?= $examSubject->directionSubject->question_count ?> ta</h6>
                                                                    </div>
                                                                </div>

                                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                                    <div class="subject_box_left">
                                                                        <p>Har bir savolga beriladigan ball:</p>
                                                                    </div>
                                                                    <div class="subject_box_right">
                                                                        <h6><?= $examSubject->directionSubject->ball ?> ball</h6>
                                                                    </div>
                                                                </div>

                                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                                    <div class="subject_box_left">
                                                                        <p>Fandan to'plangan ball:</p>
                                                                    </div>
                                                                    <div class="subject_box_right">
                                                                        <h6><?= $examSubject->ball ?> ball</h6>
                                                                    </div>
                                                                </div>

                                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                                    <div class="subject_box_left">
                                                                        <p>Sertifikat:</p>
                                                                    </div>
                                                                    <div class="subject_box_right">
                                                                        <h6>
                                                                            <?php if ($examSubject->file_status == 0) : ?>
                                                                                Yuklanmagan
                                                                            <?php elseif ($examSubject->file_status == 1): ?>
                                                                                <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $examSubject->file ?>">Kelib tushdi</a>
                                                                            <?php elseif ($examSubject->file_status == 2): ?>
                                                                                <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $examSubject->file ?>">Tasdiqlandi</a>
                                                                            <?php elseif ($examSubject->file_status == 3): ?>
                                                                                <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $examSubject->file ?>">Bekor qilindi</a>
                                                                            <?php endif; ?>
                                                                        </h6>
                                                                    </div>
                                                                </div>

                                                                <div class="d-flex gap-3 align-items-center mt-3">
                                                                    <?= Html::a(Yii::t('app', 'Sertifikat yuklash'), ['exam/upload', 'id' => $examSubject->id],
                                                                        [
                                                                            'class' => 'sub_links',
                                                                            "data-bs-toggle" => "modal",
                                                                            "data-bs-target" => "#studentSerUpload",
                                                                        ])
                                                                    ?>
                                                                    <?= Html::a(Yii::t('app', 'Sertifikat tasdiqlash'), ['exam/confirm', 'id' => $examSubject->id],
                                                                        [
                                                                            'class' => 'sub_links',
                                                                            "data-bs-toggle" => "modal",
                                                                            "data-bs-target" => "#studentSerConfirm",
                                                                        ]) ?>
                                                                    <?= Html::a(Yii::t('app', 'Ball berish'), ['student/exam-subject-ball', 'id' => $examSubject->id],
                                                                        [
                                                                            'class' => 'sub_links',
                                                                            "data-bs-toggle" => "modal",
                                                                            "data-bs-target" => "#studentAddBall",
                                                                        ]) ?>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php elseif ($model->edu_type_id == 3) : ?>
                                    <div class="col-md-12 mt-4">
                                        <div class="view-info-right">
                                            <p>Yuborilgan fayllar</p>
                                            <div class="row mt-2">
                                                <?php if ($perevot): ?>
                                                    <div class="col-lg-6 col-md-12">
                                                        <div class="subject_box">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="subject_box_left">
                                                                    <p>Holati:</p>
                                                                </div>
                                                                <div class="subject_box_right">
                                                                    <h6>
                                                                        <?php if ($perevot->file_status == 0) : ?>
                                                                            Yuklanmagan
                                                                        <?php elseif ($perevot->file_status == 1): ?>
                                                                            <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $perevot->file ?>">Kelib tushdi</a>
                                                                        <?php elseif ($perevot->file_status == 2): ?>
                                                                            <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $perevot->file ?>">Tasdiqlandi</a>
                                                                        <?php elseif ($perevot->file_status == 3): ?>
                                                                            <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $perevot->file ?>">Bekor qilindi</a>
                                                                        <?php endif; ?>
                                                                    </h6>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex gap-3 align-items-center mt-3">
                                                                <?= Html::a(Yii::t('app', 'Fayl yuklash'), ['student-perevot/upload3', 'id' => $perevot->id],
                                                                    [
                                                                        'class' => 'sub_links',
                                                                        "data-bs-toggle" => "modal",
                                                                        "data-bs-target" => "#studentTrUpload",
                                                                    ])
                                                                ?>
                                                                <?= Html::a(Yii::t('app', 'Faylni tasdiqlash'), ['student-perevot/check3', 'id' => $perevot->id],
                                                                    [
                                                                        'class' => 'sub_links',
                                                                        "data-bs-toggle" => "modal",
                                                                        "data-bs-target" => "#studentTrConfirm",
                                                                    ]) ?>
                                                            </div>

                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif ($model->edu_type_id == 4) : ?>
                                    <div class="col-md-12 mt-4">
                                        <div class="view-info-right">
                                            <p>Yuborilgan fayllar</p>
                                            <div class="row mt-2">
                                                <?php if ($perevot): ?>
                                                    <div class="col-lg-6 col-md-12">
                                                        <div class="subject_box">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="subject_box_left">
                                                                    <p>Holati:</p>
                                                                </div>
                                                                <div class="subject_box_right">
                                                                    <h6>
                                                                        <?php if ($perevot->file_status == 0) : ?>
                                                                            Yuklanmagan
                                                                        <?php elseif ($perevot->file_status == 1): ?>
                                                                            <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $perevot->file ?>">Kelib tushdi</a>
                                                                        <?php elseif ($perevot->file_status == 2): ?>
                                                                            <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $perevot->file ?>">Tasdiqlandi</a>
                                                                        <?php elseif ($perevot->file_status == 3): ?>
                                                                            <a target="_blank" href="/frontend/web/uploads/<?= $model->id ?>/<?= $perevot->file ?>">Bekor qilindi</a>
                                                                        <?php endif; ?>
                                                                    </h6>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex gap-3 align-items-center mt-3">
                                                                <?= Html::a(Yii::t('app', 'Fayl yuklash'), ['student-perevot/upload4', 'id' => $perevot->id],
                                                                    [
                                                                        'class' => 'sub_links',
                                                                        "data-bs-toggle" => "modal",
                                                                        "data-bs-target" => "#studentTrUpload",
                                                                    ])
                                                                ?>
                                                                <?= Html::a(Yii::t('app', 'Faylni tasdiqlash'), ['student-perevot/check4', 'id' => $perevot->id],
                                                                    [
                                                                        'class' => 'sub_links',
                                                                        "data-bs-toggle" => "modal",
                                                                        "data-bs-target" => "#studentTrConfirm",
                                                                    ]) ?>
                                                            </div>

                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <p align="center" class="svg_icon">
                                <svg  viewBox="0 0 184 152" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g transform="translate(24 31.67)"><ellipse fill-opacity=".8" fill="#F5F5F7" cx="67.797" cy="106.89" rx="67.797" ry="12.668"></ellipse><path d="M122.034 69.674L98.109 40.229c-1.148-1.386-2.826-2.225-4.593-2.225h-51.44c-1.766 0-3.444.839-4.592 2.225L13.56 69.674v15.383h108.475V69.674z" fill="#AEB8C2"></path><path d="M101.537 86.214L80.63 61.102c-1.001-1.207-2.507-1.867-4.048-1.867H31.724c-1.54 0-3.047.66-4.048 1.867L6.769 86.214v13.792h94.768V86.214z" fill="url(#linearGradient-1)" transform="translate(13.56)"></path><path d="M33.83 0h67.933a4 4 0 0 1 4 4v93.344a4 4 0 0 1-4 4H33.83a4 4 0 0 1-4-4V4a4 4 0 0 1 4-4z" fill="#F5F5F7"></path><path d="M42.678 9.953h50.237a2 2 0 0 1 2 2V36.91a2 2 0 0 1-2 2H42.678a2 2 0 0 1-2-2V11.953a2 2 0 0 1 2-2zM42.94 49.767h49.713a2.262 2.262 0 1 1 0 4.524H42.94a2.262 2.262 0 0 1 0-4.524zM42.94 61.53h49.713a2.262 2.262 0 1 1 0 4.525H42.94a2.262 2.262 0 0 1 0-4.525zM121.813 105.032c-.775 3.071-3.497 5.36-6.735 5.36H20.515c-3.238 0-5.96-2.29-6.734-5.36a7.309 7.309 0 0 1-.222-1.79V69.675h26.318c2.907 0 5.25 2.448 5.25 5.42v.04c0 2.971 2.37 5.37 5.277 5.37h34.785c2.907 0 5.277-2.421 5.277-5.393V75.1c0-2.972 2.343-5.426 5.25-5.426h26.318v33.569c0 .617-.077 1.216-.221 1.789z" fill="#DCE0E6"></path></g><path d="M149.121 33.292l-6.83 2.65a1 1 0 0 1-1.317-1.23l1.937-6.207c-2.589-2.944-4.109-6.534-4.109-10.408C138.802 8.102 148.92 0 161.402 0 173.881 0 184 8.102 184 18.097c0 9.995-10.118 18.097-22.599 18.097-4.528 0-8.744-1.066-12.28-2.902z" fill="#DCE0E6"></path><g transform="translate(149.65 15.383)" fill="#FFF"><ellipse cx="20.654" cy="3.167" rx="2.849" ry="2.815"></ellipse><path d="M5.698 5.63H0L2.898.704zM9.259.704h4.985V5.63H9.259z"></path></g></g></svg>
                            </p>
                            <br>
                            <p align="center"><?php if ($eduType) { echo $eduType->name_uz; } else { echo "Chala user";} ?> ma'lumotlari mavjud emas.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <?php if ($contract) : ?>
        <div class="page-item mb-4">
            <div class="page_title mt-5 mb-3">
                <h6 class="title-h5">Shartnoma</h6>
                <h6 class="title-link">
                    <?= Html::a(Yii::t('app', 'Tahrirlash'), ['contract', 'id' => $cont , 'type' => $model->edu_type_id , 'std_id' => $model->id],
                        [
                            "data-bs-toggle" => "modal",
                            "data-bs-target" => "#studentContract",
                        ])
                    ?>
                </h6>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="form-section">
                        <div class="form-section_item">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="view-info-right">
                                        <p>Ikki tomonlama shartnoma &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <?= $contract_type ?> &nbsp;&nbsp; barobar &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <?= number_format((int)$contract_price, 0, '', ' ') .' so‘m' ?> </p>
                                        <h6><a href="<?= Url::to(['contract/index' , 'id' => $model->id , 'type' => 2]) ?>">Yuklash uchun bosing</a></h6>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="view-info-right">
                                        <p>Uch tomonlama shartnoma &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <?= $contract_type ?> &nbsp;&nbsp; barobar &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <?= number_format((int)$contract_price, 0, '', ' ') .' so‘m' ?></p>
                                        <h6>
                                            <h6><a href="<?= Url::to(['contract/index' , 'id' => $model->id , 'type' => 3]) ?>">Yuklash uchun bosing</a></h6>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="studentSendSms" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLabel">Sms xabarnoma yuborish</h1>
                        <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
                    </div>
                    <div class="modal-body" id="studentSendSmsBody">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="studentOferUpload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLabel">Oferta yuklang</h1>
                        <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
                    </div>
                    <div class="modal-body" id="studentOferUploadBody">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="studentOferConfirm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLabel">Ofertani tasdiqlash</h1>
                        <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
                    </div>
                    <div class="modal-body" id="studentOferConfirmBody">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="studentTrUpload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLabel">Transkrpt yuklang</h1>
                        <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
                    </div>
                    <div class="modal-body" id="studentTrUploadBody">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="studentTrConfirm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLabel">Faylni tasdiqlash</h1>
                        <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
                    </div>
                    <div class="modal-body" id="studentTrConfirmBody">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="studentSerUpload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLabel">Sertifikat yuklang</h1>
                        <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
                    </div>
                    <div class="modal-body" id="studentSerUploadBody">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="studentSerConfirm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLabel">Sertifikatni tasdiqlash</h1>
                        <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
                    </div>
                    <div class="modal-body" id="studentSerConfirmBody">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="studentInfo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLabel">Pasport ma'lumotini tahrirlash</h1>
                        <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
                    </div>
                    <div class="modal-body" id="studentInfoBody">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="studentUserEdite" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLabel">Ro'yhatdan o'tish ma'lumotini tahrirlash</h1>
                        <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
                    </div>
                    <div class="modal-body" id="studentUserEditeBody">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="studentQabulEdite" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLabel">Qabul ma'lumotlarini tahrirlash</h1>
                        <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
                    </div>
                    <div class="modal-body" id="studentQabulEditeBody">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="studentPerevotEdite" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLabel">Ma'lumotlarini tahrirlash</h1>
                        <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
                    </div>
                    <div class="modal-body" id="studentPerevotEditeBody">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="studentAddBall" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLabel">Fanga ball berish</h1>
                        <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
                    </div>
                    <div class="modal-body" id="studentAddBallBody">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="studentOperator" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLabel">Yangi suxbat natijasini kiriting</h1>
                        <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
                    </div>
                    <div class="modal-body" id="studentOperatorBody">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="studentOperatorList" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLabel">Jami suxbat natijasi</h1>
                        <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
                    </div>
                    <div class="modal-body" id="studentOperatorListBody">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="studentContract" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLabel">Shartnoma summasini tahrirlash</h1>
                        <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
                    </div>
                    <div class="modal-body" id="studentContractBody">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
$js = <<<JS
$(document).ready(function() {
    
    $('#studentContract').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('#studentContractBody').load(url);
    });
    
    $('#studentOperator').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('#studentOperatorBody').load(url);
    });
    
    $('#studentOperatorList').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('#studentOperatorListBody').load(url);
    });
    
    $('#studentSendSms').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('#studentSendSmsBody').load(url);
    });
    
    $('#studentTrUpload').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('#studentTrUploadBody').load(url);
    });
    
    $('#studentTrConfirm').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('#studentTrConfirmBody').load(url);
    });
    
    
    $('#studentOferConfirm').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('#studentOferConfirmBody').load(url);
    });
    
    $('#studentOferUpload').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('#studentOferUploadBody').load(url);
    });
    
    $('#studentSerUpload').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('#studentSerUploadBody').load(url);
    });
    
    $('#studentSerConfirm').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('#studentSerConfirmBody').load(url);
    });
    
    $('#studentInfo').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('#studentInfoBody').load(url);
    });
    
    $('#studentUserEdite').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('#studentUserEditeBody').load(url);
    });
    
    $('#studentQabulEdite').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('#studentQabulEditeBody').load(url);
    });
    
    $('#studentPerevotEdite').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('#studentPerevotEditeBody').load(url);
    });
    $('#studentAddBall').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var url = button.attr('href');
        $(this).find('#studentAddBallBody').load(url);
    });
});
JS;
$this->registerJs($js);
?>
