<?php
/* @var $this EquipmentcalibrationController */
/* @var $model Equipmentcalibration */

$this->breadcrumbs=array(
	'Equipmentcalibrations'=>array('index'),
	$model->ID,
);

$this->menu=array(
	array('label'=>'List Equipmentcalibration', 'url'=>array('index')),
	array('label'=>'Create Equipmentcalibration', 'url'=>array('create')),
	array('label'=>'Update Equipmentcalibration', 'url'=>array('update', 'id'=>$model->ID)),
	array('label'=>'Delete Equipmentcalibration', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Equipmentcalibration', 'url'=>array('admin')),
);
?>

<h1>View Equipmentcalibration #<?php echo $model->ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ID',
		'user_id',
		'equipmentID',
		'date',
		array(
			'name'=>'isdone',
			'value'=>$model->getstatus(),
			),
		'certificate',
	),
)); 

echo CHtml::link(
	"Import New",
	'javascript:void(0)',
	array(
		'url'=>array("/equipment/loadFilter",array("data"=>"hahaha")),
		'class'=>'btn btn-success btn-small',
		'onclick'=>"js:{  $('#dialogImportDetail2').dialog('open');}",						
		)
);

?>


<div id="pdftarget" style="height:900px">

<?php

	
		$basePath=Yii::app()->baseUrl.'/upload/';
		echo $basePath.$model->certificate;
		$this->widget('ext.pdfJs.QPdfJs',array(
			'id'=>'pdfviewer',
			//'url'=>$basePath."hhh.pdf",
			'url'=>$basePath.$model->certificate,
			//'url'=>Yii::getPathOfAlias('webroot').'/upload/mytest.pdf',
		));


	
	
?>

</div> 



<!--import calibration dialogue-->
<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		    'id'=>'dialogImportDetail2',
		    // additional javascript options for the dialog plugin
		    'options'=>array(
		        'title'=>'Import Calibration Data',
				'show'=>'scale',
				'hide'=>'scale',				
				'width'=>500,
				'modal'=>true,
				'resizable'=>false,
				'autoOpen'=>false,
			    ),
		));
?>
	<div class="form">
		<?php $image=Yii::app()->baseUrl.('/images/ajax-loader.gif');?>
		<?php $form=$this->beginWidget('CActiveForm', array(
			'htmlOptions' => array('enctype' => 'multipart/form-data'),
		)); ?>
	 	<div class="row-fluid">
			<?php echo CHtml::hiddenField("theid",$model->ID,array("id"=>"theid2")); ?>
		</div> 
		<div class="row">
			<?php echo CHtml::fileField('import_path2',''); ?>
	        <?php echo CHtml::submitButton('Import File', array('class'=>'btn btn-info')); ?>
	    </div>
	    <?php $this->endWidget(); ?>
	</div>

<?php
	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>

