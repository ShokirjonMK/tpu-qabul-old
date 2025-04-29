<?php

namespace backend\controllers;

use backend\models\AddBall;
use backend\models\Contract;
use backend\models\Oferta;
use backend\models\Passport;
use backend\models\SendSms;
use backend\models\StepThree2;
use backend\models\StepThree3;
use backend\models\UserUpdate;
use common\models\AuthAssignment;
use common\models\Direction;
use common\models\DirectionCourse;
use common\models\EduType;
use common\models\EduYearType;
use backend\models\StepThree;
use common\models\Exam;
use common\models\ExamStudentQuestions;
use common\models\ExamSubject;
use common\models\Student;
use common\models\StudentDtm;
use common\models\StudentDtmSearch;
use common\models\StudentOferta;
use common\models\StudentOperator;
use common\models\StudentOperatorType;
use common\models\StudentPerevot;
use common\models\StudentSearch;
use common\models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use common\models\EduYear;

/**
 * StudentDtmController implements the CRUD actions for StudentDtm model.
 */
class StudentController extends Controller
{
    use ActionTrait;
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all StudentDtm models.
     *
     * @return string
     */
    public function actionIndex($id)
    {
        $edu_type = $this->eduTypeFindModel($id);
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $edu_type);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'edu_type' => $edu_type
        ]);
    }

    public function actionUserStep()
    {
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->step($this->request->queryParams);

        return $this->render('user-step', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAll()
    {
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->all($this->request->queryParams);

        return $this->render('all', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSendSms($id)
    {
        $student = $this->studentSindModel($id);
        $model = new SendSms();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $result = $model->sendSms($student , $model);
                if ($result['is_ok']) {
                    Yii::$app->session->setFlash('success');
                } else {
                    Yii::$app->session->setFlash('error' , $result['errors']);
                }
                return $this->redirect(['view' , 'id' => $student->id]);
            }
        }

        return $this->renderAjax('_send-sms' , [
            'id' => $id,
            'model' => $model
        ]);
    }

    public function actionStepQabul($id)
    {
        $student = $this->studentSindModel($id);
        $user = $student->user;
        $model = new StepThree();

        $eduYear = EduYear::find()->where(['is_deleted' => 0 , 'status' => 1])->one();
        $eduYearType = EduYearType::findOne([
            'edu_type_id' => 1,
            'edu_year_id' => $eduYear->id,
        ]);
        $student->edu_year_type_id = $eduYearType->id;
        $student->edu_type_id = $eduYearType->edu_type_id;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $result = $model->ikStep($user , $student);
                if ($result['is_ok']) {
                    Yii::$app->session->setFlash('success');
                } else {
                    Yii::$app->session->setFlash('error' , $result['errors']);
                }
                return $this->redirect(['view' , 'id' => $student->id]);
            }
        }

        return $this->renderAjax('_form-step3' , [
            'id' => $id,
            'model' => $model,
            'student' => $student,
            'user' => $user,
            'eduYear' => $eduYear
        ]);
    }

    public function actionStepPerevot($id)
    {
        $student = $this->studentSindModel($id);
        $user = $student->user;
        $model = new StepThree2();

        $eduYear = EduYear::find()->where(['is_deleted' => 0 , 'status' => 1])->one();
        $eduYearType = EduYearType::findOne([
            'edu_type_id' => 2,
            'edu_year_id' => $eduYear->id,
        ]);
        $student->edu_year_type_id = $eduYearType->id;
        $student->edu_type_id = $eduYearType->edu_type_id;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $result = $model->ikStep($user , $student);
                if ($result['is_ok']) {
                    Yii::$app->session->setFlash('success');
                } else {
                    Yii::$app->session->setFlash('error' , $result['errors']);
                }
                return $this->redirect(['view' , 'id' => $student->id]);
            }
        }

        return $this->renderAjax('_form-step32' , [
            'id' => $id,
            'model' => $model,
            'student' => $student,
            'user' => $user,
            'eduYear' => $eduYear
        ]);
    }

    public function actionStepDtm($id)
    {
        $student = $this->studentSindModel($id);
        $user = $student->user;
        $model = new StepThree3();
        $eduYear = EduYear::find()->where(['is_deleted' => 0 , 'status' => 1])->one();
        $eduYearType = EduYearType::findOne([
            'edu_type_id' => 3,
            'edu_year_id' => $eduYear->id,
        ]);
        $student->edu_year_type_id = $eduYearType->id;
        $student->edu_type_id = $eduYearType->edu_type_id;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $result = $model->ikStep($user , $student);
                if ($result['is_ok']) {
                    Yii::$app->session->setFlash('success');
                } else {
                    Yii::$app->session->setFlash('error' , $result['errors']);
                }
                return $this->redirect(['view' , 'id' => $student->id]);
            }
        }

        return $this->renderAjax('_form-step33' , [
            'id' => $id,
            'model' => $model,
            'student' => $student,
            'user' => $user,
            'eduYear' => $eduYear
        ]);
    }

    public function actionDirection()
    {
        $form_id = yii::$app->request->post('form_id');
        $lang_id = yii::$app->request->post('lang_id');
        $type_id = yii::$app->request->post('type_id');

        $directions = Direction::find()
            ->where([
                'edu_year_type_id' => $type_id,
                'edu_year_form_id' => $form_id,
                'language_id' => $lang_id,
                'status' => 1,
                'is_deleted' => 0
            ])->all();

        $options = "";
        $options .= "<option value=''>Yo'nalish tanlang ...<option>";
        if (count($directions) > 0) {
            foreach ($directions as $direction) {
                $options .= "<option value='$direction->id'>". $direction->code ." - ". $direction->name_uz. " - ". $direction->eduType->name_uz ."</option>";
            }
        }
        return $options;
    }

    public function actionDirectionCourse()
    {
        $dir_id = yii::$app->request->post('dir_id');
        $type_id = yii::$app->request->post('type_id');

        $direction = Direction::findOne([
            'edu_year_type_id' => $type_id,
            'id' => $dir_id,
            'status' => 1,
            'is_deleted' => 0
        ]);

        $options = "";
        $options .= "<option value=''>Yakunlagan bosqichingiz ...<option>";

        if ($direction) {
            $directionCourses = DirectionCourse::find()
                ->where([
                    'direction_id' => $direction->id,
                    'status' => 1,
                    'is_deleted' => 0
                ])->orderBy('course_id asc')->all();
            if (count($directionCourses) > 0) {
                foreach ($directionCourses as $course) {
                    $options .= "<option value='$course->id'>{$course->course->name_uz}</option>";
                }
            }
        }

        return $options;
    }

    public function actionExamSubjectBall($id)
    {
        $examSubject = $this->examSubjectfindModel($id);

        $model = new AddBall();
        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = AddBall::ball($model , $examSubject);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                } else {
                    \Yii::$app->session->setFlash('error' , $result['errors']);
                }
                return $this->redirect(['student/view', 'id' => $examSubject->student_id]);
            }
        }

        return $this->renderAjax('confirm', [
            'model' => $model,
            'examSubject' => $examSubject
        ]);
    }

    public function actionTestDelete($id)
    {
        $exam = $this->examfindModel($id);

        $result = Exam::deleteBall($exam);
        if ($result['is_ok']) {
            \Yii::$app->session->setFlash('success');
        } else {
            \Yii::$app->session->setFlash('error' , $result['errors']);
        }

        return $this->redirect(['student/view', 'id' => $exam->student_id]);
    }

    protected function eduTypeFindModel($id)
    {
        if (($model = EduYearType::findOne(['id' => $id, 'is_deleted' => 0])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionInfo($id)
    {
        $student = $this->studentSindModel($id);

        $model = new Passport();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $result = $model->ikStep($student);
                if ($result['is_ok']) {
                    Yii::$app->session->setFlash('success');
                } else {
                    Yii::$app->session->setFlash('error' , $result['errors']);
                }
                return $this->redirect(['view' , 'id' => $student->id]);
            }
        }

        return $this->renderAjax('info' , [
            'model' => $model,
            'student' => $student,
        ]);
    }


    public function actionContract($id, $type , $std_id)
    {
        $query = $this->findModelByType($id, $type);
        $statusValid = $this->isStatusValid($query, $type);

        if ($statusValid['is_ok']) {
            $data = $statusValid['data'];

            $model = new Contract();

            $model->price = $data->contract_price;

            if ($this->request->isPost && $model->load($this->request->post())) {
                $result = $model->ikStep($data);
                if ($result['is_ok']) {
                    Yii::$app->session->setFlash('success');
                } else {
                    Yii::$app->session->setFlash('error', $result['errors']);
                }
                return $this->redirect(['view', 'id' => $std_id]);
            }

            return $this->renderAjax('contract', [
                'model' => $model,
            ]);
        }
    }

    private function findModelByType($id, $type)
    {
        switch ($type) {
            case 1:
                return $this->examfindModel($id);
            case 2:
                return $this->perevotfindModel($id);
            case 3:
                return $this->dtmfindModel($id);
            default:
                return null;
        }
    }

    private function isStatusValid($query, $type)
    {
        $user = Yii::$app->user->identity;
        if ($user->user_role != 'supper_admin') {
            return ['is_ok' => false];
        }

        if (!$query) {
            return ['is_ok' => false];
        }

        if ($type == 1 && $query->status == 3) {
            return ['is_ok' => true , 'data' => $query];
        }

        if (($type == 2 || $type == 3) && $query->file_status == 2) {
            return ['is_ok' => true , 'data' => $query];
        }

        return ['is_ok' => false];
    }


    public function actionUserUpdate($id)
    {
        $student = $this->studentSindModel($id);

        $model = new UserUpdate();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $result = $model->ikStep($student);
                if ($result['is_ok']) {
                    Yii::$app->session->setFlash('success');
                } else {
                    Yii::$app->session->setFlash('error' , $result['errors']);
                }
                return $this->redirect(['view' , 'id' => $student->id]);
            }
        }

        return $this->renderAjax('user-update' , [
            'model' => $model,
            'student' => $student,
        ]);
    }

    protected function findModel($id)
    {
        $model = Student::findOne(['id' => $id]);
        if ($model) {
            return $model;
        }
        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

    protected function studentSindModel($id)
    {
        $model = Student::findOne(['id' => $id]);
        if ($model) {
            return $model;
        }
        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

    protected function examSubjectfindModel($id)
    {
        $model = ExamSubject::findOne(['id' => $id]);
        if ($model) {
            return $model;
        }
        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

    protected function examfindModel($id)
    {
        $model = Exam::findOne(['id' => $id]);
        if ($model) {
            return $model;
        }
        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

    protected function perevotfindModel($id)
    {
        $model = StudentPerevot::findOne(['id' => $id]);
        if ($model) {
            return $model;
        }
        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

    protected function dtmfindModel($id)
    {
        $model = StudentDtm::findOne(['id' => $id]);
        if ($model) {
            return $model;
        }
        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

    protected function ofertafindModel($id)
    {
        $model = StudentOferta::findOne(['id' => $id]);
        if ($model) {
            return $model;
        }
        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionOfertaUpload($id)
    {
        $oferta = $this->ofertafindModel($id);
        $model = new Oferta();

        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = Oferta::upload($model , $oferta);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                } else {
                    \Yii::$app->session->setFlash('error' , $result['errors']);
                }
                return $this->redirect(['view', 'id' => $oferta->student_id]);
            }
        }

        return $this->renderAjax('oferta-upload', [
            'model' => $model,
        ]);
    }

    public function actionOfertaConfirm($id)
    {
        $model = $this->ofertafindModel($id);
        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = Oferta::check($model);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                } else {
                    \Yii::$app->session->setFlash('error' , $result['errors']);
                }
                return $this->redirect(['view', 'id' => $model->student_id]);
            }
        }

        return $this->renderAjax('oferta-confirm', [
            'model' => $model,
        ]);
    }


    public function actionExcelExporta()
    {
        $time = time();
        $students = Student::find()->all();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Student Id');
        $sheet->setCellValue('B1', 'Student full name');

        $row = 2;

        foreach ($students as $item) {
            $full_name = $item->last_name.' '.$item->first_name.' '.$item->middle_name;
            $sheet->setCellValue('A' . $row, $item->id);
            $sheet->setCellValue('B' . $row, $full_name);
            $row++;
        }

        $filePath = Yii::getAlias('@backend/web/ik/'.$time.'.xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return "https://qabul.tpu.uz/backend/web/ik/".$time.".xlsx";
    }

    public function actionCons11($id)
    {
        $user = Yii::$app->user->identity;
        if ($user->id == 24416) {
            $students = Student::findOne($id);
            $user = $students->user;
            $user->cons_id = 3;
            $user->save(false);
        }
        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionExcelExport()
    {
        $time = time();
        $students = Student::find()->all();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header row
        $sheet->setCellValue('A1', 'Student Id');
        $sheet->setCellValue('B1', 'Familya');
        $sheet->setCellValue('C1', 'Ismi');
        $sheet->setCellValue('D1', 'Otasini ismi');
        $sheet->setCellValue('E1', 'Yo\'nalish');
        $sheet->setCellValue('F1', 'Ta\'lim shakli');
//        $sheet->setCellValue('G1', 'Ta\'lim turi');
        $sheet->setCellValue('H1', 'Ta\'lim tili');
        $sheet->setCellValue('I1', 'Tel');
        $sheet->setCellValue('J1', 'Ro\'yhatga olingan sana');
//        $sheet->setCellValue('K1', 'Status');

        $row = 2;


        // Fill data rows
        foreach ($students as $item) {
            $sheet->setCellValue('A' . $row, $item->id);
            $sheet->setCellValue('B' . $row, $item->last_name);
            $sheet->setCellValue('C' . $row, $item->first_name);
            $sheet->setCellValue('D' . $row, $item->middle_name);
            $sheet->setCellValue('E' . $row, $item->direction_id ? $item->direction->name_uz : '' );
            $sheet->setCellValue('F' . $row, $item->edu_form_id ? $item->eduForm->name_uz : '' );
//            $sheet->setCellValue('G' . $row, $item->edu_type_id ? $item->eduType->name_uz : '' );
            $sheet->setCellValue('H' . $row, $item->language_id ? $item->language->name_uz : '' );
            $sheet->setCellValue('I' . $row, $item->username);
            $sheet->setCellValue('J' . $row, date("Y-m-d H:i:s" , $item->user->created_at));
//            $sheet->setCellValue('K' . $row, $item->eduStatusName);
            $row++;
        }

        if (!file_exists(\Yii::getAlias('@backend/web/ik'))) {
            mkdir(\Yii::getAlias('@backend/web/ik'), 0777, true);
        }

        $filePath = Yii::getAlias('@backend/web/ik/'.$time.'.xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);
        return "https://qabul.tpu.uz/backend/web/ik/".$time.".xlsx";
    }



    public function actionDele($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];
        $student = $this->studentSindModel($id);
        $user = Yii::$app->user->identity;
        $t = false;

        if ($user->user_role != "supper_admin") {
            if ($user->user_role == "admin") {
                if ($user->cons_id == $student->user->cons_id) {
                    $t = true;
                }
            }
        } else {
            $t = true;
        }
        if ($t) {
            if ($student->lead_id != null) {
                $amoCrmClient = Yii::$app->ikAmoCrm;
                $leadId = $student->lead_id;
                $tags = [];
                $message = '';
                $customFields = [];

                $updatedFields = [
                    'pipelineId' => $student->pipeline_id,
                    'statusId' => User::STEP_STATUS_8
                ];

                $amoCrmClient->updateLead($leadId, $updatedFields, $tags, $message, $customFields);
            }
            $student->student_operator_id = null;
            $student->save(false);
            StudentOperator::deleteAll(['student_id' => $student->id]);
            StudentDtm::deleteAll(['student_id' => $student->id]);
            StudentPerevot::deleteAll(['student_id' => $student->id]);
            StudentOferta::deleteAll(['student_id' => $student->id]);
            ExamStudentQuestions::deleteAll(['user_id' => $student->user_id]);
            AuthAssignment::deleteAll(['user_id' => $student->user_id]);
            ExamSubject::deleteAll(['user_id' => $student->user_id]);
            Exam::deleteAll(['user_id' => $student->user_id]);
            Student::deleteAll(['id' => $student->id]);
            User::deleteAll(['id' => $student->user_id]);
            \Yii::$app->session->setFlash('success');
        } else {
            $errors[] = ["Ma'lumotni o'chirish imkonsiz!!!"];
            \Yii::$app->session->setFlash('error' , $errors);
        }

        if (count($errors) == 0) {
            $transaction->commit();
        }else {
            $transaction->rollBack();
        }
        return $this->redirect(['student/user-step']);
    }
}
