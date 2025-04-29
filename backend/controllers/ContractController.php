<?php

namespace backend\controllers;

use common\models\Exam;
use common\models\Message;
use common\models\StudentPerevot;
use common\models\StudentPerevotSearch;
use common\models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Student;
use kartik\mpdf\Pdf;
use Yii;

/**
 * StudentPerevotController implements the CRUD actions for StudentPerevot model.
 */
class ContractController extends Controller
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

    public function actionIndex($id , $type)
    {
        $student = Student::findOne(['id' => $id]);
        $user = $student->user;

        $action = '';
        if ($type == 2) {
            if ($student->language_id == 1) {
                $action = 'con2-uz';
            } elseif ($student->language_id == 3) {
                $action = 'con2-ru';
            }
        } elseif ($type == 3) {
            if ($student->language_id == 1) {
                $action = 'con3-uz';
            } elseif ($student->language_id == 3) {
                $action = 'con3-ru';
            }
        }

        $content = $this->renderPartial($action, [
            'student' => $student,
            'type' => $type,
            'user' => $user
        ]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'marginLeft' => 25,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_DOWNLOAD,
            'content' => $content,
            'cssInline' => 'body { font-family: Times, "Times New Roman", serif; }',
            'filename' => date('YmdHis') . ".pdf",
            'options' => [
                'title' => 'Contract',
                'subject' => 'Student Contract',
                'keywords' => 'pdf, contract, student',
            ],
        ]);

        if ($student->lead_id != null) {
            try {
                $amoCrmClient = Yii::$app->ikAmoCrm;
                $leadId = $student->lead_id;
                $tags = [];
                $message = '';
                $customFields = [];

                $updatedFields = [
                    'pipelineId' => $student->pipeline_id,
                    'statusId' => User::STEP_STATUS_7
                ];

                $updatedLead = $amoCrmClient->updateLead($leadId, $updatedFields, $tags, $message, $customFields);
            } catch (\Exception $e) {
                $errors[] = ['Ma\'lumot uzatishda xatolik STEP 2: ' . $e->getMessage()];
                Yii::$app->session->setFlash('error' , $errors);
                return $this->redirect(['site/index']);
            }
        }

        return $pdf->render();
    }

    public function actionBug7()
    {
        $students = Student::find()
            ->where(['exam_type' => 1])
            ->andWhere(['in' , 'id' , Exam::find()
                ->select('student_id')
                ->andWhere(['is_deleted' => 0])
                ->andWhere(['<' , 'status' , 3])
            ])->all();

        $text = "Hurmatli abituriyent! Sizga “TASHKENT PERFECT UNIVERSITY”da 24.07.2024y. soat 10:00da offline imtihon o'tkazilishini ma'lum qilamiz. Shaxsni tasdiqlovchi hujjat(pasport) bilan universitet binosiga kelishingizni so'raymiz. Manzil: Toshkent shahar, Yunusobod tumani, Bog’ishamol ko’chasi 220-uy. Aloqa markazi: 771292929";

        if (count($students)) {
            foreach ($students as $student) {
                $phone = $student->username;
                Message::sendedSms($phone , $text);
            }
        }
        return $this->redirect(['site/index']);
    }

    public function actionBugik()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];
        $inputFileName = __DIR__ . '/excel/daDAczA (3).xlsx';
        $spreadsheet = IOFactory::load($inputFileName);
        $data = $spreadsheet->getActiveSheet()->toArray();

//        $t = [];
//        foreach ($data as $key => $row) {
//            $number = $row[0];
//            if ($number == null) {
//                break;
//            }
//            $number = '+998 (' . substr($number, 0, 2) . ') ' . substr($number, 2, 3) . '-' . substr($number, 5, 2) . '-' . substr($number, 7, 2);
//            $user = User::findOne(['username' => $number]);
//            $user->status = 3;
//            $user->save(false);
//        }

        if (count($errors) == 0) {
            $transaction->commit();
            echo "tugadi.";
        } else {
            $transaction->rollBack();
            dd($errors);
        }
    }


    public function actionSend()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        $students = Student::find()
            ->where(['in' , 'id' , StudentPerevot::find()
                ->select('student_id')
                ->where(['file_status' => 2])
            ])->all();

        $text = "Hurmatli abituriyent! Siz “TASHKENT PERFECT UNIVERSITY”ga talabalikka tavsiya etildingiz. To'lov shartnomasini https://qabul.tpu.uz qabul tizimi orqali yuklab olishingizni va 30-avgustgacha https://forms.gle/FcZ2n5bo6MDpse6BA havolada ko'rsatilgan hujjatlaringizni universitet qabul bo'limiga topshirishingizni so'raymiz. Manzil: Toshkent sh., Yunusobod t., Bog’ishamol ko’chasi 220-uy. Aloqa markazi: 77 129 29 29.";

        if (count($students) > 0) {
            foreach ($students as $student) {
                $phone = $student->username;
                $result = Message::sendedSms($phone , $text);
                echo $result."\n";
            }
        }

        if (count($errors) == 0) {
            $transaction->commit();
            echo "tugadi.";
        } else {
            $transaction->rollBack();
            echo "tugadi.";
        }
    }
}
