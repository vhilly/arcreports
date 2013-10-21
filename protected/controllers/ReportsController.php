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
          'actions'=>array('currentVoyages','voyages','revenue','accounting','advanceTicketSales','tellers','dynamicVoyages','markAsBilled'),
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
            $data['passenger'][$r['departure_date']][] = $r;
          }
        }
        $sql="SELECT voyage,departure_date,SUM(amt) amt FROM cargo_history $where GROUP BY departure_date,voyage_id";
        $result2=Yii::app()->db->createCommand($sql)->queryAll();
        if(count($result2)){
          foreach($result2 as $r2){
            $data['cargo'][$r2['departure_date']][$r2['voyage']] = $r2;
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
      $rf = new ReportForm;
      $dr='';
      if(isset($_GET['ReportForm'])){
        $rf->attributes=$_GET['ReportForm'];
        if($rf->date_range){
          $dr = " AND date_used BETWEEN '".str_replace(' - ','\' AND \'',$rf->date_range)."'";
          $model->date_range=$rf->date_range;
        }
     
      }
      if(isset($_GET['AdvanceTicket'])){
        $model->attributes=$_GET['AdvanceTicket'];
      }
      $collections=Collections::model()->findAll(array('condition'=>"1 $dr"));
      $at=AdvanceTicket::model()->findAll(array('condition'=>"status=2 $dr"));
      $classes=SeatingClass::model()->findAll();
      if($excel)
        $this->renderPartial('advTktSales',array('data'=>compact('at','classes','model','excel','rf','collections')));
      else
        $this->render('advTktSales',array('data'=>compact('at','classes','model','excel','rf','collections')));
    }
    public function actionTellers($excel=null){
      $rf = new ReportForm;
      $sc = array(1=>'BC',2=>'PE');
      $pt = array(1=>'FULL',2=>'STUDENT',3=>'SENIOR',4=>'CHILDREN',5=>'INFANT',6=>'PWD',7=>'W/PASS','8'=>'Weekday',9=>'Weekday');
      $output = array();
      $total=0;
      if(isset($_GET['ReportForm'])){
        $rf->attributes = $_GET['ReportForm'];
        $rf->date = $rf->date ? $rf->date : date('Y-m-d');
        $voyage='';
        if($rf->voyage)
          $voyage="AND voyage_id = '{$rf->voyage}'";
        $bh = BookingHistory::model()->findAll(array('condition'=>"departure_date = '{$rf->date}' {$voyage}  AND route={$rf->route} AND booking_status < 6",'order'=>'tkt_serial'));
        $sql = "SELECT passenger_type,seating_class,voyage,amt,tkt_no FROM cargo_history WHERE 
               departure_date='{$rf->date}' {$voyage} AND route='{$rf->route}' AND booking_status < 6 ";
        $ch=Yii::app()->db->createCommand($sql)->queryAll();
        if(count($bh)){
          $i = 0;
          $tmp = null;
          $tmp2 = null;
          $cnt=1;
          $cnt2=array();
          foreach($bh as $b){
            if(is_numeric($b->tkt_serial)){
             if($tmp2 != $b->voyage){
               $tmp = null;
               $i=0;
             }
             $tmp2=$b->voyage;
             $kor=$sc[$b->seating_class].'-'.$pt[$b->passenger_type];
              if($tmp != $kor){
                $cnt=1;
                $i++;
                $output[$b->voyage][$b->seating_class][$i][0] = $pt[$b->passenger_type];
                $output[$b->voyage][$b->seating_class][$i][1] = $b->tkt_serial;
                $output[$b->voyage][$b->seating_class][$i][2] = '-';
                $output[$b->voyage][$b->seating_class][$i][3] = '';
                $output[$b->voyage][$b->seating_class][$i][4] = $cnt.'x';
                $output[$b->voyage][$b->seating_class][$i][5] = number_format($b['amt'],2);
                $output[$b->voyage][$b->seating_class][$i][6] = number_format($b->amt*$cnt,2);
              }else{
                $output[$b->voyage][$b->seating_class][$i][3] = $b->tkt_serial;
                $cnt++;
                $output[$b->voyage][$b->seating_class][$i][4] = $cnt.'x';
                $output[$b->voyage][$b->seating_class][$i][6] = number_format($b->amt*$cnt,2);
              }
              $tmp=$kor;
            }else{
               $kor2=$sc[$b->seating_class].'-'.$pt[$b->passenger_type];
               @$cnt2[$kor2]++;
               $output[$b->voyage][$b->seating_class][$kor2][0] = $pt[$b->passenger_type];
               $output[$b->voyage][$b->seating_class][$kor2][1] = '';
               $output[$b->voyage][$b->seating_class][$kor2][2] = '-';
               $output[$b->voyage][$b->seating_class][$kor2][3] = '';
               $output[$b->voyage][$b->seating_class][$kor2][4] = $cnt2[$kor2].'x';
               $output[$b->voyage][$b->seating_class][$kor2][5] = number_format($b->amt,2);
               $output[$b->voyage][$b->seating_class][$kor2][6] = number_format($b->amt*$cnt2[$kor2],2);
            }
            @$totalPerVoyage[$b->voyage]['total']+=$b['amt'];;
            @$totalPerVoyage[$b->voyage][$b->seating_class]+=$b['amt'];;
            $total+=$b->amt;
          }
        }
        if(count($ch)){
          foreach($ch as $c){
            @$output[$c['voyage']]['cargo']['per_cargo'][]=array($c['passenger_type'].','.$c['seating_class'],$c['tkt_no'],'-','','1x',
            number_format($c['amt'],2),number_format($c['amt'],2));
            @$output[$c['voyage']]['cargo']['total_revenue']+=$c['amt'];
          }
        }
      }
      if($excel)
        $this->renderPartial('tellers',array('data'=>compact('total','output','rf','excel','totalPerVoyage')));
      else
        $this->render('tellers',array('data'=>compact('total','output','rf','excel','totalPerVoyage')));

    }
    public function actionDynamicVoyages(){
      $data=BookingHistory::model()->findAll(array('condition'=>"departure_date='{$_POST['ReportForm']['date']}'",'group'=>'voyage_id'));
       $data=CHtml::listData($data,'voyage_id','voyage');
        foreach($data as $value=>$name){
        echo CHtml::tag('option',
                   array('value'=>$value),CHtml::encode($name),true);
      }
    }
    public function actionMarkAsBilled($is_billed,$ids){
      AdvanceTicket::model()->updateAll(array('is_billed'=>$is_billed),"id IN ($ids)");
    }
  }
