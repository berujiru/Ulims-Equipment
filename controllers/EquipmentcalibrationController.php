<?php

class EquipmentcalibrationController extends Controller
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
				'users'=>array('admin'),
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
		if($_FILES['import_path2']['tmp_name'])
		{
			//get the upload folder
			$target_dir =Yii::getPathOfAlias('webroot').'/upload/';
			$newname = basename(date("Y-m-d").'-'.rand(1,100).'-'.$_FILES["import_path2"]["name"]);
			$target_file = $target_dir . $newname;
			// print_r($_FILES["import_path"]);
			// exit();
			//$target_file = Yii::app()->baseUrl.'/upload/'.basename($_FILES["import_path"]["tmp_name"]);
			if($_FILES["import_path2"]["type"]==="application/pdf"){
				if(move_uploaded_file($_FILES["import_path2"]["tmp_name"], $target_file)){
					$id = $_POST['theid'];
					//$maintenancedata =$newname;
					$tempmodel = Equipmentcalibration::model()->findByPk($id);
					//get the old certificate
					$tobedelete=$tempmodel->certificate;
					$tempmodel->certificate = $newname;
					$tempmodel->isdone=1;
					if($tempmodel->save()){
						//delete the old certificate
						$path= $target_dir . $tobedelete;
						unlink($path);
						$this->redirect(Yii::app()->createUrl('equipment/equipmentcalibration/view',array(
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


		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($item = 0)
	{
		
		$model=new Equipmentcalibration;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Equipmentcalibration']))
		{
			$model->attributes=$_POST['Equipmentcalibration'];
			$model->user_id = Yii::app()->user->id;
			if($model->save()){
				$equipment = Equipment::model()->findByAttributes(array("equipmentID"=>$model->equipmentID));
				$this->redirect(array('equipment/view','id'=>$equipment->ID));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'item'=>$item,
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

		if(isset($_POST['Equipmentcalibration']))
		{
			$model->attributes=$_POST['Equipmentcalibration'];
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
		$dataProvider=new CActiveDataProvider('Equipmentcalibration');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Equipmentcalibration('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Equipmentcalibration']))
			$model->attributes=$_GET['Equipmentcalibration'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Equipmentcalibration the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Equipmentcalibration::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Equipmentcalibration $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='equipmentcalibration-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
