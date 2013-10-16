
<div class=well>
  <?php if(!$data['excel']):?>
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'revenue-report',
        'method'=>'get',
        'type'=>'inline',
        'htmlOptions'=>array('class'=>''),
     )); ?>
     <?php echo $form->dropDownListRow($data['rf'],'route',CHtml::listData(Route::model()->findAll(),'id','name'),array('class'=>'span2'))?>
        <?php echo $form->datePickerRow($data['rf'], 'date', array('append'=>'<i class="icon-calendar" style="cursor:pointer"></i>','class'=>'span2','options'=>array( 'format' => 'yyyy-mm-dd')));
    $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Generate Report'));
    $this->endWidget();
    ?>
  <?php $this->widget('bootstrap.widgets.TbButton', array('type'=>'success','buttonType'=>'link','icon'=>'share',
  'url'=>Yii::app()->request->url.'&excel=1','label'=>'Export to Excel'));?>
  <br>
  <br>
  <?php endif;?>
<table class=table>
   <tr>
     <th>Kind of Receipt</th>
     <th colspan=3>INCLUSIVE TICKETS AND WAYBILL NUMBER</th>
     <th>Number Used</th>
     <th>Fare</th>
     <th>Total Amount</th>
   </tr>
   <?php foreach($data['output'] as $k=>$o):?>
   <tr>
     <th colspan=7 bgcolor=cyan><center> <?=$k?></center></th>
   </tr>
   <tr><th colspan=7><u>PASSENGERS FARE:</u></th></tr>
   <?php if(isset($o[1])):?>
   <tr><td colspan=7>BUSINESS CLASS:</td></tr>
   <?php foreach($o[1] as $v):?>
   <tr>
     <td><?=implode('</td><td>',$v)?></td>
   </tr>
   <?php endforeach;?>
   <tr>
     <td>TOTAL</td>
     <td colspan=5></t>
     <th><?=number_format($data['totalPerVoyage'][$k][1])?></th>
   </tr>
   <?php endif;?>
   <?php if(isset($o[2])):?>
   <tr><td colspan=7>PREMIUM ECONOMY:</td></tr>
   <?php foreach($o[2] as $v):?>
   <tr>
     <td><?=implode('</td><td>',$v)?></td>
   </tr>
   <?php endforeach;?>
   <tr>
     <td>TOTAL</td>
     <th colspan=5></th>
     <th><?=number_format($data['totalPerVoyage'][$k][2])?></th>
   </tr>
   <tr>
   <?php endif;?>
     <td>TOTAL PASSENGER REVENUE</td>
     <td colspan=5></td>
     <th><?=number_format($data['totalPerVoyage'][$k]['total'])?></th>
   </tr>
   <?php if(isset($data['output'][$k]['cargo'])):?>
   <tr>
     <th><u>VEHICLES FREIGHT</u></th>
     <td colspan=6></td>
   </tr>
   <tr>
     <td>ROLLING CARGO</td>
     <td colspan=3></td>
     <td>x<?=$data['output'][$k]['cargo']['cnt']?></td>
     <td></td>
     <th><?=number_format($data['output'][$k]['cargo']['amt'])?></th>
   </tr>
   <tr>
     <td>TOTAL VECHICLE REVENUE</td>
     <td colspan=5></td>
     <th><?=number_format($data['output'][$k]['cargo']['amt'])?></th>
   </tr>
  <?php endif;?>
   <tr>
     <th>GRAND TOTAL</th>
     <td colspan=5></td>
     <th><?=@number_format($data['output'][$k]['cargo']['amt']+$data['totalPerVoyage'][$k]['total'])?></th>
   </tr>
   <?php endforeach;?>
</table>
</div>
  <?php if($data['excel']):?>
<?php
     $file ='TELLERS_AND_PURSERS_REPORT.xls';
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
