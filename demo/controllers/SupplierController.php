<?php

namespace app\controllers;

use app\models\Supplier;
use app\models\SupplierSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class SupplierController extends Controller
{

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


    public function actionIndex()
    {
        $dataProvider = SupplierSearch::search($this->request->queryParams);

        return $this->render('index', [
                'searchModel' => SupplierSearch,
                'dataProvider' => $dataProvider,
        ]);
    }



    public function actionCsv()
    {

        if ($this->request->isPost) {
            $fields = array_keys($this->request->post("fields"));
            $where = [];
            $values = [];

            $ids = explode(",",$this->request->get("ids"));
            foreach((array)$ids as $k=>$v) {
                $ids[$k] = intval($v);

            }
            $where[] = "id in (".implode(",", $ids).")";

            $data = Supplier::find()->select($fields)->where($where,$values)->asArray()->all();
            $content = [];
            foreach((array)$data as $row){
                if(empty($content)){
                    $column_header = [];
                    foreach((array)$row as $field=>$value){
                        $column_header[] = $field;
                    }
                    $content[] = implode(",", $column_header);
                }
                $content[] = implode(",", $row);
            }
            $content_csv = implode("\n", $content);
            $this->downLoadCsv($content_csv);


        }
        return $this->render('csv', []);
    }

    public function downLoadCsv($csv){
            header("Content-Type: application/vnd.ms-excel; charset=utf-8");
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=supplier.csv ");
            header("Content-Transfer-Encoding: binary ");
            echo $csv;
            exit;
    }

}
















