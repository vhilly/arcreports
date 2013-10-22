<?php $tn= array('DATE','WEEK#','MONTH','DAY OF WEEK')?>
<div class='span-12'>
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'revenue-report',
        'method'=>'post',
        'type'=>'inline',
        'htmlOptions'=>array('class'=>''),
     )); ?>
    <?php echo $form->dateRangeRow($rf, 'date_range',
      array(
        'placement'=>'left',
        'options' => array('callback'=>'js:function(start, end){$("#ReportForm_date_range").val("\'"+ start.toString("yyyy-M-d")+"\' AND \'"+ end.toString("yyyy-M-d")+"\'") ;}')
      ));
    echo $form->dropDownListRow($rf,'type',array(1=>'Daily',2=>'Weekly',3=>'Monthly',4=>'Day Of Week'),array('class'=>'span2'));
    $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Generate Report'));
    $this->endWidget();
    ?>
</div>
<div class=clearfix></div>
<?php if(count($result)):?>
  <?php $box = $this->beginWidget('bootstrap.widgets.TbBox', array(
    'title' => ' REVENUE REPORT',
    'headerIcon' => 'icon-signal',
    'htmlOptions' => array('class'=>'bootstrap-widget-table well')
  ));?>
  <table class=span>
    <tr>
      <th><?=$tn[$type-1]?></th>
      <th>REVENUE</th>
    </tr>
  <?php foreach($result as $r):?>
    <tr>
      <td><?=$r['departure_date']?></td>
      <td><?=number_format($r['total_rev'])?></td>
    </tr>
  <?php endforeach;?>
  </table>
  <div class="pull-right span10">
    <div id="chart_div" style="width: 600px; height: 400px;"></div>
  </div>
  <?php $this->endWidget();?>
<?php endif;?>
<div class=clearfix></div>

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawColumn);
      function drawColumn(div,business,premium,cargo) {
        // Create and populate the data table.
        var data = google.visualization.arrayToDataTable([
          ['Revenue', 'Premium Economy','Business Class','Cargo'],
	  <?php foreach($result as $r):?>
            ['<?=$r['departure_date']?>',<?=$r['premium_rev']?>,<?=$r['business_rev']?>,<?=$r['cargo_rev']?>],
	  <?php endforeach;?>
        ]);
        // Create and draw the visualization.
        new google.visualization.ColumnChart(document.getElementById('chart_div')).
            draw(data,
                 {title:"",
                  width:800, height:300,
                  hAxis: {title: ""},
                  isStacked: true
                 }
            );
      }
    </script>
