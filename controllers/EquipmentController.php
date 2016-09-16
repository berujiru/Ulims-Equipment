<?php

class EquipmentController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array('rights');
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin','*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		if($_FILES['import_path']['tmp_name'])
		{

			$target_dir =Yii::getPathOfAlias('webroot').'/upload/';
			$newname = basename(date("Y-m-d").'-'.$_FILES["import_path"]["name"]);
			$target_file = $target_dir . $newname;
			// print_r($_FILES["import_path"]);
			// exit();
			//$target_file = Yii::app()->baseUrl.'/upload/'.basename($_FILES["import_path"]["tmp_name"]);
			if($_FILES["import_path"]["type"]==="application/pdf"){
				if(move_uploaded_file($_FILES["import_path"]["tmp_name"], $target_file)){
					$id = $_POST['theid'];
					$maintenancedata =$newname;
					$tempmodel = Equipmentmaintenance::model()->findByPk($id);
					$tempmodel->maintenancedata = $maintenancedata;
					$tempmodel->isdone=1;
					if($tempmodel->save()){
						$this->redirect(Yii::app()->createUrl('equipment/equipmentmaintenance/view',array(
							'id'=>$id,
						)));
					}
				}
				else{
					echo $_FILES['import_path'];
					//exit();
					throw new CHttpException(500,$_FILES["import_path"]);
				}
			}
			else{
				Yii::app()->user->setFlash('error','The file uploaded is not of type PDF!');
			}
		}

		if($_FILES['import_path2']['tmp_name'])
		{
			//echo file_get_contents($_FILES['import_path']['tmp_name']);
			$id = $_POST['theid'];
			$calibrationdata = file_get_contents($_FILES['import_path2']['tmp_name']);
			$tempmodel = Equipmentcalibration::model()->findByPk($id);
			$tempmodel->certificate = $calibrationdata;
			$tempmodel->isdone=1;
			if($tempmodel->save()){
				$this->redirect(Yii::app()->createUrl('equipment/equipmentcalibration/view',array(
					//'model'=>$tempmodel,
					'id'=>$id,
				)));
			}

		}


		
		$model = $this->loadModel($id);
		$maintenance=new CActiveDataProvider('Equipmentmaintenance', array(
			'criteria'=>array(
		        'condition'=>'equipmentID='.$model->equipmentID/*.' and isdone = "0"'*/,
		        'order'=>'date DESC'
		    ),
		    'pagination'=>array(
		        'pageSize'=>5,
		    ),
		));
		$calibration=new CActiveDataProvider('Equipmentcalibration', array(
			'criteria'=>array(
		        'condition'=>'equipmentID='.$model->equipmentID/*.' and isdone = "0"'*/,
		        'order'=>'date DESC'
		    ),
		    'pagination'=>array(
		        'pageSize'=>5,
		    ),
		));

		$this->render('view',array(
			'model'=>$model,
			'maintenance'=>$maintenance,
			'calibration'=>$calibration,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Equipment;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Equipment']))
		{
			$model->attributes=$_POST['Equipment'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->ID));
		}

		$dirname = Yii::getPathOfAlias('webroot').'/upload/';
		$file = Yii::getPathOfAlias('webroot').'/upload/import.txt';
		
		// Create $dirname if not exist
		if (!is_dir($dirname)){
			mkdir($dirname, 0755, true);
		}
		
		// Create $file if not exist
		if(!file_exists($file)){  
			fopen($file, 'w+');
			file_put_contents($file, serialize(array()));
		}

		//loading import data
		if($_FILES['import_path']['tmp_name'])
		{
			Yii::import('application.vendors.PHPExcel',true);
            $objReader = new PHPExcel_Reader_Excel2007;
            $objPHPExcel = $objReader->load($_FILES['import_path']['tmp_name']);
            //$objPHPExcel = $objReader->load('F:\import.xls');
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $objWorksheet->getHighestRow(); // e.g. 10
            $highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5
			//$equipment = array();
            $importData = array();
			for ($row = 6; $row <= $highestRow; ++$row) {
				$equipment = array(
					'equipmentID'=> $objWorksheet->getCellByColumnAndRow(1, $row)->getValue(),
					'name'=>$objWorksheet->getCellByColumnAndRow(0, $row)->getValue(),
					'description'=>$objWorksheet->getCellByColumnAndRow(2, $row)->getValue(),
					'lab'=>$objWorksheet->getCellByColumnAndRow(4, $row)->getCalculatedValue(),
					'classificationID'=>$objWorksheet->getCellByColumnAndRow(6, $row)->getCalculatedValue(),
					'specification'=>$objWorksheet->getCellByColumnAndRow(7, $row)->getValue(),
					'date_received'=>date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(8, $row)->getValue())),
					'received_by'=>$objWorksheet->getCellByColumnAndRow(10, $row)->getValue(),
					'amount'=>$objWorksheet->getCellByColumnAndRow(11, $row)->getValue(),
					'supplier'=>$objWorksheet->getCellByColumnAndRow(13, $row)->getValue(),
					'status'=>$objWorksheet->getCellByColumnAndRow(15, $row)->getCalculatedValue(),
					'remarks'=>$objWorksheet->getCellByColumnAndRow(16, $row)->getValue(),
				);
				array_push($importData, $equipment);
			}

			file_put_contents($file, serialize($importData));

			

        }

        $data = file_get_contents($file);
		$arr = unserialize($data);
		$importDataProvider = new CArrayDataProvider($arr);

		$this->render('create',array(
			'model'=>$model,
			'importDataProvider'=>$importDataProvider,
			'equipment'=>$equipment,
			'has_duplicate'=>$this->checkExistingEquipments($arr)
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Equipment']))
		{
			$model->attributes=$_POST['Equipment'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->ID));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Equipment');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Equipment('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Equipment']))
			$model->attributes=$_GET['Equipment'];
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionCreateDataEntryFile()
	{
		// $equipmentDataProvider=new CActiveDataProvider(Equipment, array(
		// 		'pagination'=>false,
		// ));
		
		//get all the lab
		$labs = Lab::model()->findAll();
		if($labs){
			$labCount=count($labs);
			$labArray=array();
			foreach($labs as $lab){
				$labArray[]=array('labId'=>$lab->id, 'labName'=>$lab->labCode. " - ".$lab->labName);
			}
		}

		$classifications = Equipmentclassification::model()->findAll();
		if($classifications){
			$classifyCount=count($classifications);
			$classifyArray=array();
			foreach($classifications as $classify){
				$classifyArray[]=array('classifyId'=>$classify->ID, 'classifyName'=>$classify->name);
			}
		}

		$statuses = Equipmentstatus::model()->findAll();
		if($statuses){
			$statusCount=count($statuses);
			$statusArray=array();
			foreach($statuses as $status){
				$statusArray[]=array('statusId'=>$status->ID, 'statusName'=>$status->name);
			}
		}
		//put a lab and classification active data record to arrayy //merging
		$data = array_map(function ($arr1, $arr2) {
		$new2 = array();
		foreach ($arr2 as $key => $value) {
		if (($value !== NULL) || !isset($arr1[$key])) {
			$new2[$key] = $value;
			}
		}
			if($arr1==NULL){
				return $new2;
			}else{
				return array_merge($arr1, $new2);
			}
		}, $labArray, $classifyArray);


		$data2 = array_map(function ($arr1, $arr2) {
		$new2 = array();
		foreach ($arr2 as $key => $value) {
		if (($value !== NULL) || !isset($arr1[$key])) {
			$new2[$key] = $value;
			}
		}
			if($arr1==NULL){
				return $new2;
			}else{
				return array_merge($arr1, $new2);
			}
		}, $data, $statusArray);	

		$dataProvider=new CArrayDataProvider($data2);

		$this->widget('ext.eexcelview.EExcelViewCreateEquipmentDataEntryFile', array(
			'dataProvider'=>$dataProvider,
			'columns'=>array(				
				'labName',
				'labId',
				'classifyName',				
				'classifyId',
				'statusName',				
				'statusId',
			),
			'title'=>'Equipment Data Entry Form for ULIMS',
			'filename'=>'Equipment DataEntryForm',
			'grid_mode'=>'export',
			'exportType' => 'Excel2007',
			'creator' =>'ULIMS equipment',
			'subject'=>'Data entry form for ULIMS',
			'labCount'=>$labCount,
			'classifyCount'=>$classifyCount,
			'statusCount'=>$statusCount,
			)
		);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Equipment the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Equipment::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Equipment $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='equipment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function checkExistingEquipments($equipments)
	{
		$has_duplicate = false;
		foreach($equipments as $equipment){
			$data = equipment::model()->findByAttributes(array('equipmentID'=>$equipment['equipmentID']));
			$has_duplicate = $data ? true : false;
			if($has_duplicate){
				return true;
			}		
		}
		return false;
	}

	public function actionImport()
	{
		$file = Yii::getPathOfAlias('webroot').'/upload/import.txt';
		$data = file_get_contents($file);
		$arr = unserialize($data);
		
		file_put_contents($file, serialize(array()));
		$count = 0;
		foreach($arr as $equipment){
			
			$new_equipment = new Equipment();
			$new_equipment->equipmentID =$equipment['equipmentID'];
			$new_equipment->name=$equipment['name'];
			$new_equipment->description=$equipment['description'];
			$new_equipment->lab=$equipment['lab'];
			$new_equipment->classificationID=$equipment['classificationID'];
			$new_equipment->specification=$equipment['specification'];
			$new_equipment->date_received=$equipment['date_received'];
			$new_equipment->received_by=$equipment['received_by'];
			$new_equipment->amount=$equipment['amount'];
			$new_equipment->supplier=$equipment['supplier'];
			$new_equipment->status=$equipment['status'];
			$new_equipment->remarks=$equipment['remarks'];
			if($new_equipment->save()){
				$count ++;
			}

		}
		if($count != 0){
			$html = $count;
			echo CJSON::encode(array(
	                  	'status'=>'success', 
	                    'div'=>$html.' Equipments Successfully Imported.'
	                    ));
			exit;
		}else{
			echo CJSON::encode(array(
                  	'status'=>'failure', 
                    'div'=>'<div style="text-align:center;" class="alert alert-error"><i class="icon icon-warning-sign"></i><font style="font-size:14px;"> System Warning. </font><br \><br \><div>'.$count.' Requests imported.</div></div>'
                    ));
			exit;
		}

	}
	public function actionClearFile(){
		$file = Yii::getPathOfAlias('webroot').'/upload/import.txt';
		file_put_contents($file, serialize(array()));
		$this->redirect('create');
	}


	public function actionViewMaintenanceRecord(){
		if(isset($_POST['id']))
			$requestId=$_POST['id'];

		$maintenance=Equipmentmaintenance::model()->findByPk($requestId);
		// echo CJSON::encode("Date :' ".$maintenance->date."' and Type :".$maintenance->type);
		$datum = null;
		if($maintenance->type === "0")
			$datum ="Date : ".$maintenance->date." and Type : Standard Maintenance";
		else
			$datum = "Date : ".$maintenance->date." and Type : Preventive / Corrective Maintenance";

		echo json_encode(array("title"=>$datum,"data"=>nl2br($maintenance->maintenancedata)));
		//exit();
	}

	public function actionViewCalibrationRecord($id){
		
		// if(isset($_POST['id']))
		// 	$requestId=$_POST['id'];

		//echo $id; exit();

		// $calibration=Equipmentcalibration::model()->findByPk($requestId);
		$calibration=Equipmentcalibration::model()->findByPk($id);
		
			if($calibration)
			 	echo $this->renderPartial('_popupload', array('pdf'=>$calibration->certificate));
			 else
			 	echo $this->renderPartial('_popupload', array('pdf'=>NULL));

		//$basePath=Yii::app()->baseUrl.'/upload/';

		//echo $basePath.$calibration->certificate;
		
	}

	public function actionLoadFilterdtr(){

			$this->renderPartial('_popupload', array('model'=>$model)); 
	}
}
