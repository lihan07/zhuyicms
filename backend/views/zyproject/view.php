<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ZyProject */

$this->title = $model->project_id;
$this->params['breadcrumbs'][] = ['label' => 'Zy Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zy-project-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->project_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->project_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'project_id',
            'user_id',
            'city',
            'address',
            'compound',
            'decoration_type',
            'covered_area',
            'use_area',
            'budget_design_work',
            'budget_design',
            'budget_ruan',
            'budget_ying',
            'budget_yuanlin',
            'work_time',
            'home_type',
            'project_status',
            'service_type',
            'home_img',
            'favorite_img',
            'designer_level',
            'match_json',
            'description:ntext',
            'project_tags',
        ],
    ]) ?>

</div>