<?php $classes=CHtml::listData($data['classes'],'id','name');?>
<?php if(!$data['excel']):?>
<div class='well'>
  <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'advrevenue-report',
    'method'=>'get',
    'type'=>'inline',
    'htmlOptions'=>array('class'=>''),
  )); ?>
  <?php echo $form->dateRangeRow($data['rf'], 'date_range',
    array(
      'placement'=>'left',
      'options'=>array('format'=>'yyyy-MM-dd'),
      //'options' => array('callback'=>'js:function(start, end){$("#ReportForm_date_range").val("\'"+ start.toString("yyyy-M-d")+"\' AND \'"+ end.toString("yyyy-M-d")+"\'") ;}')
    ));
    $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Generate Report'));
    $this->endWidget();
  ?>
<div class=clearfix></div>
<?php $this->widget('bootstrap.widgets.TbButton', array('type'=>'success','buttonType'=>'link','icon'=>'icon-share',
 'url'=>Yii::app()->request->url.'&excel=1','label'=>'Export to Excel'));?><br><br>
<?endif;?>
  <?php 
    $class=array();
    $ptypes=array();
    foreach($data['at'] as $v){
        @$class[$v->class]['rev'][$v->type] += $v->amt;
        @$class[$v->class]['cnt'][$v->type] += 1;
        $ptypes[$v->type] = $v->type0->name;
    }
   
  ?>

  <?php foreach($classes as $i=>$name):?>
    <?php if(isset($class[$i])):?>
      <?php $box = $this->beginWidget(
        'bootstrap.widgets.TbBox',
        array(
          'title' => $name,
          'headerIcon' => 'icon-th-list',
          'htmlOptions' => array('class' => 'bootstrap-widget-table')
        )
      );?>
      <table class='table table-hover'>
        <tr>
           <th>Type</th>
           <th>No. Of Passengers</th>
           <th>Revenue</th>
        </tr>
      <?php foreach($class[$i]['rev'] as $k=>$v):?>
        <tr>
          <td><?=$k==2 ? 'STUDENT/SENIORS/PWD':$ptypes[$k]?></td>
          <td><?=$class[$i]['cnt'][$k]?></td>
          <td><?=number_format($v)?></td>
        </tr>
      <?php endforeach;?>
        <tr>
          <th>Total</th>
          <th><?=array_sum($class[$i]['cnt'])?></th>
          <th><?=number_format(array_sum($class[$i]['rev']))?></th>
        </tr>
      </table>
      <?php $this->endWidget(); ?>
    <?php endif;?>
  <?php endforeach;?>
  <br>
  <?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
      'title' => 'ADVANCE TICKETS SOLD',
      'headerIcon' => 'icon-th-list',
      'htmlOptions' => array('class' => 'bootstrap-widget-table')
    )
  );?>
  <?php 
    $gridDataProvider = $data['model']->search();
    $gridDataProvider->criteria->addCondition('status = 2');
    if($data['excel'])
      $gridDataProvider->setPagination(false);
      
    $gridColumns = array(
      array('name'=>'tkt_no', 'header'=>'Ticket No.'),
      array('name'=>'tkt_series', 'header'=>'Series No.'),
      array('name'=>'seller', 'header'=>'Ticket Seller','value'=>'$data->seller0->name','filter'=>CHtml::listData(Sellers::model()->findAll(),'id','name')),
      array('name'=>'class', 'header'=>'Class' ,'value'=>'$data->class0->name','filter'=>$classes),
      array('name'=>'type', 'header'=>'Type' ,'value'=>'$data->type==2 ? "Student/Senior/PWD":$data->type0->name',
       'filter'=>array('1'=>'Full Fare','2'=>'Student/Senior/PWD')),
      array('name'=>'amt', 'header'=>'Amount'),
      array('name'=>'is_billed', 'header'=>'Billed','value'=>'$data->is_billed ? "Yes":"No"','filter'=>array(1=>'Yes',0=>'No')),
      array(
        'name'=>'date_created', 'header'=>'Date Created',
        'filter'=>$this->widget('bootstrap.widgets.TbDatePicker', array(
          'model'=>$data['model'],
          'options'=>array('format'=>'yyyy-mm-dd'),
          'htmlOptions' => array(
            'id' => 'Booking_date_created',
          ),
          'attribute'=>'date_created'),
          true),
         'sortable'=>true,
      ),
      array(
        'name'=>'date_used', 'header'=>'Date Boarded',
        'filter'=>$this->widget('bootstrap.widgets.TbDatePicker', array(
          'model'=>$data['model'],
          'options'=>array('format'=>'yyyy-mm-dd'),
          'htmlOptions' => array(
            'id' => 'Booking_date_used',
          ),
          'attribute'=>'date_used'),
          true),
         'sortable'=>true,
      ),
    );
    $this->widget(
      'bootstrap.widgets.TbExtendedGridView',
      array(
        'id'=>'advTktGrid',
        'dataProvider' => $gridDataProvider,
        'filter'=>$data['model'],
        'afterAjaxUpdate'=>"function() {
          jQuery('#Booking_date_created').datepicker({'format':'yyyy-mm-dd','language':'en','weekStart':0});
          jQuery('#Booking_date_used').datepicker({'format':'yyyy-mm-dd','language':'en','weekStart':0});
          jQuery('.datepicker').hide();
        }",
        'template' => "{items}{pager}",
        'columns' => $gridColumns,
        'type'=>'hover striped bordered',
        'ajaxUrl'=>'',
        'bulkActions' => array(
          'actionButtons' => array(
            array(
              'buttonType' => 'button',
              'type' => 'primary',
              'size' => 'small',
              'label' => 'Mark as Billed',
              'id' =>'btnBilled',
               'click' => 'js:billingStatus(checked,this.id)'
            ),
            array(
              'buttonType' => 'button',
              'type' => 'inverse',
              'size' => 'small',
              'label' => 'Mark as Unbilled',
              'id' =>'btnUnBilled',
               'click' => 'js:billingStatus(checked,this.id)'
            ),
          ),
          'checkBoxColumnConfig' => array(
            'name' => 'id'
          ),
        ),
      )
    );
  ?>
  <?php $this->endWidget();?>
  <?php if($data['collections']):?>
  <?php $seller=CHtml::listData(Sellers::model()->findAll(),'id','name')?>
  <?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
      'title' => 'COLLECTIONS',
      'headerIcon' => 'icon-money',
      'htmlOptions' => array('class' => 'bootstrap-widget-table')
    )
  );?>
      <table class='table table-hover'>
        <tr>
          <th>Date</th>
          <th>Client</th>
          <th>Collections</th>
        <tr>
        <?php foreach($data['collections'] as $c):?>
        <tr>
          <td><?=$c->date?></td>
          <td><?=$seller[$c->seller]?></td>
          <td><?=$c->value?></td>
          <?php if(!$data['excel']):?>
          <td>
            <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'link', 'type'=>'primary', 'label'=>'Add Collection',
             'url'=>Yii::app()->createUrl('collections/update',array('id'=>$c->id)),'label'=>'Update'));?>
          </td>
          <?php endif;?>
        </tr>
        <?php endforeach;?>
      </table>
  <?php $this->endWidget();?>
  <?php endif;?>
  <?php if(!$data['excel']):?>
  <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'link', 'type'=>'success', 'label'=>'Add Collection',
   'url'=>Yii::app()->createUrl('collections/create'),'label'=>'Add Collection'));?>
  <?php endif;?>
  <?php if($data['excel']):?>
<?php
     $file ='ADVANCE_TKT_SALES.xls';
      header('Pragma: public');
      header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");   
      header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
      header('Cache-Control: no-store, no-cache, must-revalidate');
      header('Cache-Control: pre-check=0, post-check=0, max-age=0');
      header("Pragma: no-cache");
      header("Expires: 0");
      header('Content-Transfer-Encoding: none');
      header("Content-type: application/vnd.ms-excel");
      header("Content-Disposition: attachment; filename=$file");
?>
  <?php endif;?>
</div>

<script>
  function billingStatus(checked,btnId){
    if(confirm("Are you sure?")){
      var values = [];
      var billed = btnId=='btnBilled'? 1:0;
      checked.each(function(){
        values.push($(this).val());
      }); 
      $.ajax({
        url:"<?=Yii::app()->createUrl('reports/markAsBilled')?>", 
        data: {is_billed:billed,ids:values.join(",")},
        success:function(data){ 
          $("#advTktGrid").yiiGridView("update"); 
        }
      });
    }
  }
</script>
