<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Supplier */

$this->title = '选择要导出的字段';
$this->params['breadcrumbs'][] = ['label' => 'Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-create">

    <h1><?= Html::encode($this->title) ?></h1>

<div class="select-field">

    <?php $form = ActiveForm::begin(); ?>

    <?=Html::checkbox('f[id]',true,['class'=>'', "checked"=>"checked", "onclick"=>"return false;" ,"label"=>"ID"]);?>
    <br/>
    <?=Html::checkbox('f[name]',true,['class'=>'', "checked"=>"checked","label"=>"name"]);?>
    <br/>
    <?=Html::checkbox('f[code]',true,['class'=>'', "checked"=>"checked","label"=>"code"]);?>
    <br/>
    <?=Html::checkbox('f[t_status]',true,['class'=>'', "checked"=>"checked","label"=>"t_status"]);?>
    <br/>


    <div class="form-group">
        <?= Html::submitButton('导出', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


</div>
