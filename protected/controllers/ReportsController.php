<?php

  class ReportsController extends Controller{
    public function filters(){
      return array(
        'accessControl', // perform access control for CRUD operations
      );
    }
    public function accessRules(){
      return array(
        array('allow',  // allow all users to perform 'index' and 'view' actions
          'actions'=>array('index','view'),
          'users'=>array('*'),
        ),
        array('allow', // allow authenticated user to perform 'create' and 'update' actions
          'actions'=>array('currentVoyages','voyages','revenue','accounting','advanceTicketSales','tellers'),
          'users'=>array('@'),
        ),
        array('allow', // allow admin user to perform 'admin' and 'delete' actions
          'actions'=>array(''),
          'users'=>array('admin'),
        ),
        array('deny',  // deny all users
          'users'=>array('*'),
        ),
      );
    }
    public function actionCurrentVoyages(){
      $voyages = VoyageReport::model()->findAll(array('condition'=>'departure_date=CURDATE()','order'=>'departure_date DESC,departure_time DESC'));
      $this->render('voyages',array('voyages'=>$voyages));
    }
    public function actionVoyages(){
      $voyages = VoyageReport::model()->findAll(array('order'=>'departure_date DESC,departure_time DESC LIMIT 20'));
      $this->render('voyages',array('voyages'=>$voyages));
    }
    public function actionRevenue(){
      $rf = new ReportForm;
      $result=array();
      $where ='';
      if(isset($_POST['ReportForm'])){
        $rf->attributes=$_POST['ReportForm'];
        switch($rf->type){
          case 1:$select='departure_date';$group='departure_date';
          break;
          case 2:$select='WEEK(departure_date) departure_date';$group='WEEK(departure_date)';
          break;
          case 3:$select='MONTHNAME(departure_date) departure_date';$group='MONTHNAME(departure_date)';
          break;
          case 4:$select='DAYNAME(departure_date) departure_date';$group='DAYNAME(departure_date)';
          break;
        }
        if($rf->date_range)
          $where = " WHERE departure_date BETWEEN {$rf->date_range}";
        else
          $where = "  WHERE departure_date BETWEEN CURDATE() AND CURDATE()";
        $sql = "SELECT $select,vessel,voyage,sum(total_rev) total_rev,sum(business_rev) business_rev,sum(premium_rev) premium_rev FROM voyage_report $where GROUP BY $group";
        $result = Yii::app()->db->createCommand($sql)->queryAll();
      }
      $this->render('revenue',array('result'=>$result,'rf'=>$rf,'type'=>$rf->type));
    }
    public function actionAccounting($excel=null){
      $data =array();
      $rf = new ReportForm;
      $result=array();
      $where ='WHERE 1';
      if(isset($_GET['ReportForm'])){
        $rf->attributes=$_GET['ReportForm'];
        if($rf->date_range)
          $where .= "  AND departure_date BETWEEN {$rf->date_range}";
        else
          $where .= "  AND departure_date BETWEEN CURDATE() AND CURDATE()";
        if($rf->route)
          $where  .= " AND route = {$rf->route} ";
        $sql = "
        SELECT departure_date,departure_time, vessel,voyage, voyage_id, 
        SUM( IF( booking_status <6,1,0))  total_pax,

        SUM( IF( booking_status <6 AND seating_class=1,1,0))  bc_total_pax,
        SUM( IF( booking_status <6 AND seating_class =1 AND passenger_type IN(1,8) , 1, 0 ) ) bc_full, 
        SUM( IF( booking_status <6 AND seating_class =1 AND passenger_type IN(2,8), 1, 0 ) ) bc_student,
        SUM( IF( booking_status <6 AND seating_class =1 AND passenger_type =3, 1, 0 ) ) bc_senior,
        SUM( IF( booking_status <6 AND seating_class =1 AND passenger_type =4, 1, 0 ) ) bc_half,
        SUM( IF( booking_status <6 AND seating_class =1 AND passenger_type =6, 1, 0 ) ) bc_pwd,
        SUM( IF( booking_status <6 AND seating_class =1 AND passenger_type =7, 1, 0 ) ) bc_pass,


        SUM( IF( booking_status <6 AND seating_class=2,1,0))  pe_total_pax,
        SUM( IF( booking_status <6 AND seating_class =2 AND passenger_type =1, 1, 0 ) ) pe_full, 
        SUM( IF( booking_status <6 AND seating_class =2 AND passenger_type =2, 1, 0 ) ) pe_student,
        SUM( IF( booking_status <6 AND seating_class =2 AND passenger_type =3, 1, 0 ) ) pe_senior,
        SUM( IF( booking_status <6 AND seating_class =2 AND passenger_type =4, 1, 0 ) ) pe_half,
        SUM( IF( booking_status <6 AND seating_class =2 AND passenger_type =6, 1, 0 ) ) pe_pwd,
        SUM( IF( booking_status <6 AND seating_class =2 AND passenger_type =7, 1, 0 ) ) pe_pass,

        SUM( IF( booking_status <6 AND seating_class =1 AND passenger_type =8, 1, 0 ) ) bc_full_promo,
        SUM( IF( booking_status <6 AND seating_class =1 AND passenger_type =9, 1, 0 ) ) bc_ssp_promo,

        SUM( IF( booking_status <6 AND seating_class =1, amt, 0 ) ) business_rev, 
        SUM( IF( booking_status <6 AND seating_class =2, amt, 0 ) ) premium_rev, 
        SUM( IF( booking_status <6, amt, 0 ) ) total_rev
        FROM `booking_history`
        $where
        GROUP BY departure_date, voyage_id
        ";

        $result = Yii::app()->db->createCommand($sql)->queryAll();
        if(count($result)){
          foreach($result as $r){
            $data[$r['departure_date']][] = $r;
          }
        }
      }
      if($excel)
        $this->renderPartial('accountingEx',array('data'=>$data,'rf'=>$rf,'type'=>$rf->type));
      else
        $this->render('accounting',array('data'=>$data,'rf'=>$rf,'type'=>$rf->type));
    }
    public function actionAdvanceTicketSales($excel=null){
      $model=new AdvanceTicket('search');
      $model->unsetAttributes();  // clear any default values
      if(isset($_GET['AdvanceTicket'])){
        $model->attributes=$_GET['AdvanceTicket'];
      }
      $at=AdvanceTicket::model()->findAll(array('condition'=>'status=2'));
      $classes=SeatingClass::model()->findAll();
      if($excel)
        $this->renderPartial('advTktSales',array('data'=>compact('at','classes','model','excel')));
      else
        $this->render('advTktSales',array('data'=>compact('at','classes','model','excel')));
    }
    public function actionTellers($excel=null){
      $rf = new ReportForm;
      $sc = array(1=>'BC',2=>'PE');
      $pt = array(1=>'FF',2=>'SF',3=>'SC',4=>'CHILD',5=>'INFANT',6=>'PWD',7=>'W/PASS','8'=>'Weekday',9=>'Weekday');
      $output = array();
      $total=0;
      if(isset($_GET['ReportForm'])){
        $rf->attributes = $_GET['ReportForm'];
        $rf->date = $rf->date ? $rf->date : date('Y-m-d');
        $bh = BookingHistory::model()->findAll(array('condition'=>"departure_date = '{$rf->date}' AND route={$rf->route} AND booking_status < 6",'order'=>'tkt_serial'));
        if(count($bh)){
          $i = 0;
          $tmp = null;
          $tmpSerial = null;
          $last = '';
          $cnt=1;
          foreach($bh as $b){
	    $kor=$pt[$b->passenger_type].'-'.$sc[$b->seating_class];
            if(is_numeric($b->tkt_serial)){
              if($tmp == $kor){
                $cnt++;
                $output[$i-1][1]=$tmpSerial.'-'.$b->tkt_serial;
	        $output[$i-1][3]=$cnt;
	        $output[$i-1][5]=number_format($b->amt*$cnt);
              }else{
	        $output[$i] = array($kor,$b->tkt_serial);
                $tmpSerial=$b->tkt_serial;
                $cnt=1;
	        $output[$i][3]=$cnt;
	        $output[$i][4]=$b->amt;
	        $output[$i][5]=$b->amt;
                $i++;
              }
              $tmp=$kor;
            }else{
	        @$output[$kor][0]=$kor;
	        @$output[$kor][1]='No Series';
	        @$output[$kor][2]+=1;
	        @$output[$kor][3]=$b->amt;
	        @$output[$kor][4]=number_format($output[$kor][2]*$b->amt);
            }
            $total+=$b->amt;
          }
        }
      }
      if($excel)
        $this->renderPartial('tellers',array('data'=>compact('total','output','rf','excel')));
      else
        $this->render('tellers',array('data'=>compact('total','output','rf','excel')));

    }
  }
