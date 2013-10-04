<?php

/**
 * This is the model class for table "booking_history".
 *
 * The followings are the available columns in table 'booking_history':
 * @property integer $id
 * @property integer $route
 * @property string $voyage_id
 * @property string $vessel
 * @property string $voyage
 * @property string $tkt_no
 * @property string $tkt_serial
 * @property string $booking_no
 * @property integer $transaction
 * @property integer $trans_type
 * @property string $created_by
 * @property integer $booking_status
 * @property string $origin
 * @property string $destination
 * @property string $departure_date
 * @property string $departure_time
 * @property integer $booking_type
 * @property integer $passenger_type
 * @property integer $seating_class
 * @property string $amt
 */
class BookingHistory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('route, voyage_id, vessel, voyage, tkt_no, tkt_serial, booking_no, transaction, trans_type, created_by, booking_status, origin, destination, departure_date, departure_time, booking_type, passenger_type, seating_class, amt', 'required'),
			array('route, transaction, trans_type, booking_status, booking_type, passenger_type, seating_class', 'numerical', 'integerOnly'=>true),
			array('voyage_id, vessel, voyage, origin, destination', 'length', 'max'=>255),
			array('tkt_no, tkt_serial, booking_no', 'length', 'max'=>32),
			array('created_by', 'length', 'max'=>100),
			array('amt', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, route, voyage_id, vessel, voyage, tkt_no, tkt_serial, booking_no, transaction, trans_type, created_by, booking_status, origin, destination, departure_date, departure_time, booking_type, passenger_type, seating_class, amt', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'route' => 'Route',
			'voyage_id' => 'Voyage',
			'vessel' => 'Vessel',
			'voyage' => 'Voyage',
			'tkt_no' => 'Tkt No',
			'tkt_serial' => 'Tkt Serial',
			'booking_no' => 'Booking No',
			'transaction' => 'Transaction',
			'trans_type' => 'Trans Type',
			'created_by' => 'Created By',
			'booking_status' => 'Booking Status',
			'origin' => 'Origin',
			'destination' => 'Destination',
			'departure_date' => 'Departure Date',
			'departure_time' => 'Departure Time',
			'booking_type' => 'Booking Type',
			'passenger_type' => 'Passenger Type',
			'seating_class' => 'Seating Class',
			'amt' => 'Amt',
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
		$criteria->compare('route',$this->route);
		$criteria->compare('voyage_id',$this->voyage_id,true);
		$criteria->compare('vessel',$this->vessel,true);
		$criteria->compare('voyage',$this->voyage,true);
		$criteria->compare('tkt_no',$this->tkt_no,true);
		$criteria->compare('tkt_serial',$this->tkt_serial,true);
		$criteria->compare('booking_no',$this->booking_no,true);
		$criteria->compare('transaction',$this->transaction);
		$criteria->compare('trans_type',$this->trans_type);
		$criteria->compare('created_by',$this->created_by,true);
		$criteria->compare('booking_status',$this->booking_status);
		$criteria->compare('origin',$this->origin,true);
		$criteria->compare('destination',$this->destination,true);
		$criteria->compare('departure_date',$this->departure_date,true);
		$criteria->compare('departure_time',$this->departure_time,true);
		$criteria->compare('booking_type',$this->booking_type);
		$criteria->compare('passenger_type',$this->passenger_type);
		$criteria->compare('seating_class',$this->seating_class);
		$criteria->compare('amt',$this->amt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
