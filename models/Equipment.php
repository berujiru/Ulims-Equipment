<?php

/**
 * This is the model class for table "equipment".
 *
 * The followings are the available columns in table 'equipment':
 * @property integer $ID
 * @property string $equipmentID
 * @property string $name
 * @property string $description
 * @property integer $lab
 * @property integer $classificationID
 * @property string $specification
 * @property string $date_received
 * @property integer $received_by
 * @property double $amount
 * @property integer $supplier
 * @property integer $status
 * @property integer $usagestatus
 * @property string $lengthofuse
 * @property string $remarks
 */
class Equipment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'equipment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('equipmentID, name, description, lab, classificationID, specification, date_received, received_by, amount, supplier, status, remarks', 'required'),
			array('lab, classificationID, received_by, supplier, status, usagestatus', 'numerical', 'integerOnly'=>true),
			array('amount', 'numerical'),
			array('equipmentID', 'length', 'max'=>50),
			array('name, description, remarks', 'length', 'max'=>255),
			array('specification', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ID, equipmentID, name, description, lab, classificationID, specification, date_received, received_by, amount, supplier, status, usagestatus, lengthofuse, remarks', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'labaccess'=>array(self::BELONGS_TO,'Lab','lab'),
			'classification'=>array(self::BELONGS_TO,'Equipmentclassification','classificationID'),
			'equipstatus'=>array(self::BELONGS_TO,'Equipmentstatus','status'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'equipmentID' => 'Equipment',
			'name' => 'Name',
			'description' => 'Description',
			'lab' => 'Lab',
			'classificationID' => 'Classification',
			'specification' => 'Specification',
			'date_received' => 'Date Received',
			'received_by' => 'Received By',
			'amount' => 'Amount',
			'supplier' => 'Supplier',
			'status' => 'Status',
			'usagestatus' => 'Usagestatus',
			'lengthofuse' => 'Lengthofuse',
			'remarks' => 'Remarks',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('ID',$this->ID);
		$criteria->compare('equipmentID',$this->equipmentID,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('lab',$this->lab);
		$criteria->compare('classificationID',$this->classificationID);
		$criteria->compare('specification',$this->specification,true);
		$criteria->compare('date_received',$this->date_received,true);
		$criteria->compare('received_by',$this->received_by);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('supplier',$this->supplier);
		$criteria->compare('status',$this->status);
		$criteria->compare('usagestatus',$this->usagestatus);
		$criteria->compare('lengthofuse',$this->lengthofuse,true);
		$criteria->compare('remarks',$this->remarks,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->equipmentDb;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Equipment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
