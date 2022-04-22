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
        $searchModel = new SupplierSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }




    public function actionCreate()
    {
        $model = new Supplier();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }



    public function actionCsv()
    {

        if ($this->request->isPost) {
		$fields = array_keys($this->request->post("f"));
		$where = [];
		$where_vals = [];
		if($this->request->get("checkall") == 'true'){

			if($this->request->get("id")){
				$where[] = "id=:id"; 
				$where_vals[":id"] = $this->request->get("id");
			}
			if($this->request->get("name")){
				$where[] = "name like :name"; 
				$where_vals[":name"] = '%'.$this->request->get("name").'%';
			}
			if($this->request->get("code")){
				$where[] = "code like :code"; 
				$where_vals[":code"] = '%'.$this->request->get("code").'%';
			}
			if($this->request->get("t_status")){
				$where[] = "t_status=:t_status"; 
				$where_vals[":t_status"] = $this->request->get("t_status");
			}

		}else{

			$ids = explode(",",$this->request->get("ids"));
			foreach((array)$ids as $k=>$v) $ids[$k] = intval($v);

			$where[] = "id in (".implode(",", $ids).")";
		}

		$data = Supplier::find()->select($fields)->where(implode(" and ", $where), $where_vals)->asArray()->all();
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

		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=supplier.csv ");
		header("Content-Transfer-Encoding: binary ");
		echo $content_csv;
		exit;

	}
	return $this->render('csv', []);
    }
}
