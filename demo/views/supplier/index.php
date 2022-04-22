<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\Supplier;


$this->title = 'Suppliers';
$this->params['breadcrumbs'][] = $this->title;

//csv
$route = ['csv'];
if(isset($_GET['SupplierSearch']))
foreach((array)$_GET['SupplierSearch'] as $k=>$v){
	$route[$k] = $v;
}
$route['checkall'] = 'CHECKALL';
$route['ids'] = 'IDS';

$targetUrl =  Url::toRoute($route); 


?>
<div class="supplier-index">


    <p>

        <?= Html::a('导出csv', ['csv'], ['class' => 'btn btn-success', 
			'onclick'=>"location.href='$targetUrl'.replace('CHECKALL',document.getElementsByClassName('select-on-check-all')[0].checked).replace('IDS',$('#grid').yiiGridView('getSelectedRows'));return false;",
	]) ?>
    </p>


    <?= GridView::widget([
        'dataColumnClass' => "yii\grid\DataColumn",
	'id' => 'grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
	    [
	        'class' => 'yii\grid\CheckboxColumn',
	    ],
                'id',
                'name',
                'code',
	    [
	        'attribute' => 't_status',
	        'filter' => Supplier::statusSelectOptions(),
	    ],

        ],
    ]); ?>


</div>
