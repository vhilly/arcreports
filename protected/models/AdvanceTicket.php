<?php

/**
 * This is the model class for table "advance_ticket".
 *
 * The followings are the available columns in table 'advance_ticket':
 * @property integer $id
 * @property string $tkt_no
 * @property integer $class
 * @property integer $type
 * @property integer $seller
 * @property string $included_routes
 * @property string $amt
 * @property string $date_created
 * @property integer $status
 * @property string $is_sync
 *
 * The followings are the available model relations:
 * @property SeatingClass $class0
 * @property PassageFareTypes $type0
 * @property Sellers $seller0
 */
class AdvanceTicket extends CActiveRecord
{
       public function getDbConnection() { 
         return Yii::app()->syncdb; 
       } 
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'advance_ticket';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tkt_no, class, type, seller, included_routes, amt, date_created', 'required'),
			array('class, type, seller, status', 'numerical', 'integerOnly'=>true),
			array('tkt_no', 'length', 'max'=>32),
			array('included_routes', 'length', 'max'=>255),
			array('amt', 'length', 'max'=>20),
			array('is_sync', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tkt_no, class, type, seller, included_routes, amt, date_created, status, is_sync', 'safe', 'on'=>'search'),
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
			'class0' => array(self::BELONGS_TO, 'SeatingClass', 'class'),
			'type0' => array(self::BELONGS_TO, 'PassageFareTypes', 'type'),
			'seller0' => array(self::BELONGS_TO, 'Sellers', 'seller'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tkt_no' => 'Tkt No',
			'class' => 'Class',
			'type' => 'Type',
			'seller' => 'Seller',
			'included_routes' => 'Included Routes',
			'amt' => 'Amt',
			'date_created' => 'Date Created',
			'status' => 'Status',
			'is_sync' => 'Is Sync',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('tkt_no',$this->tkt_no,true);
		$criteria->compare('class',$this->class);
		$criteria->compare('type',$this->type);
		$criteria->compare('seller',$this->seller);
		$criteria->compare('included_routes',$this->included_routes,true);
		$criteria->compare('amt',$this->amt,true);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AdvanceTicket the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
