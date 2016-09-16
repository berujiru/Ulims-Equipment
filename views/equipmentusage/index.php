<?php
/* @var $this EquipmentusageController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Equipmentusages',
);

$this->menu=array(
	array('label'=>'Create Equipmentusage', 'url'=>array('create')),
	array('label'=>'Manage Equipmentusage', 'url'=>array('admin')),
);
?>



<h1>Equipmentusages</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
