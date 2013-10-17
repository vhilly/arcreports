<?php
  class ReportForm extends CFormModel{
    public $date_range;
    public $date;
    public $type;
    public $route;
    public $voyage;
    public function rules(){
       return array( 
         array('date_range,date,voyage','length','max'=>255),
	 array('type,route', 'numerical', 'integerOnly'=>true),
       );
    }
    public function attributeLabels(){
      return array(
        'date_range' => 'Date Range',
        'date' => 'Date',
        'route' => 'Route',
        'voyage' => 'Voyage',
      );
    }
  }
?>
