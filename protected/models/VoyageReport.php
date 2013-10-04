<?php

/**
 * This is the model class for table "voyage_report".
 *
 * The followings are the available columns in table 'voyage_report':
 * @property string $voyage_id
 * @property string $vessel
 * @property string $voyage
 * @property string $origin
 * @property string $destination
 * @property string $departure_date
 * @property string $departure_time
 * @property integer $reserved
 * @property integer $checked_in
 * @property integer $boarded
 * @property integer $no_show
 * @property integer $refunded
 * @property integer $canceled
 * @property integer $business_cnt
 * @property integer $premium_cnt
 * @property integer $total_cnt
 * @property string $business_rev
 * @property string $premium_rev
 * @property string $total_rev
 */
class VoyageReport extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'voyage_report';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('voyage_id, vessel, voyage, origin, destination, departure_date, departure_time, reserved, checked_in, boarded, no_show, refunded, canceled, business_cnt, premium_cnt, total_cnt, business_rev, premium_rev, total_rev', 'required'),
			array('reserved, checked_in, boarded, no_show, refunded, canceled, business_cnt, premium_cnt, total_cnt', 'numerical', 'integerOnly'=>true),
			array('voyage_id, vessel, voyage, origin, destination', 'length', 'max'=>255),
			array('business_rev, premium_rev, total_rev', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('voyage_id, vessel, voyage, origin, destination, departure_date, departure_time, reserved, checked_in, boarded, no_show, refunded, canceled, business_cnt, premium_cnt, total_cnt, business_rev, premium_rev, total_rev', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'voyage_id' => 'Voyage',
			'vessel' => 'Vessel',
			'voyage' => 'Voyage',
			'origin' => 'Origin',
			'destination' => 'Destination',
			'departure_date' => 'Departure Date',
			'departure_time' => 'Departure Time',
			'reserved' => 'Reserved',
			'checked_in' => 'Checked In',
			'boarded' => 'Boarded',
			'no_show' => 'No Show',
			'refunded' => 'Refunded',
			'canceled' => 'Canceled',
			'business_cnt' => 'Business Cnt',
			'premium_cnt' => 'Premium Cnt',
			'total_cnt' => 'Total Cnt',
			'business_rev' => 'Business Rev',
			'premium_rev' => 'Premium Rev',
			'total_rev' => 'Total Rev',
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

		$criteria->compare('voyage_id',$this->voyage_id,true);
		$criteria->compare('vessel',$this->vessel,true);
		$criteria->compare('voyage',$this->voyage,true);
		$criteria->compare('origin',$this->origin,true);
		$criteria->compare('destination',$this->destination,true);
		$criteria->compare('departure_date',$this->departure_date,true);
		$criteria->compare('departure_time',$this->departure_time,true);
		$criteria->compare('reserved',$this->reserved);
		$criteria->compare('checked_in',$this->checked_in);
		$criteria->compare('boarded',$this->boarded);
		$criteria->compare('no_show',$this->no_show);
		$criteria->compare('refunded',$this->refunded);
		$criteria->compare('canceled',$this->canceled);
		$criteria->compare('business_cnt',$this->business_cnt);
		$criteria->compare('premium_cnt',$this->premium_cnt);
		$criteria->compare('total_cnt',$this->total_cnt);
		$criteria->compare('business_rev',$this->business_rev,true);
		$criteria->compare('premium_rev',$this->premium_rev,true);
		$criteria->compare('total_rev',$this->total_rev,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VoyageReport the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
