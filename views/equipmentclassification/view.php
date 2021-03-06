<?php
/* @var $this EquipmentclassificationController */
/* @var $model Equipmentclassification */

$this->breadcrumbs=array(
	'Equipmentclassifications'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Equipmentclassification', 'url'=>array('index')),
	array('label'=>'Create Equipmentclassification', 'url'=>array('create')),
	array('label'=>'Update Equipmentclassification', 'url'=>array('update', 'id'=>$model->ID)),
	array('label'=>'Delete Equipmentclassification', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Equipmentclassification', 'url'=>array('admin')),
);
?>

<h1>View Equipmentclassification #<?php echo $model->ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ID',
		'name',
		'description',
	),
)); ?>
