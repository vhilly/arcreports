 <style>
   .fixWidth {
     width:70px;
     font-size:10px;
   }
   .borderLess{
      border:0px;
    }
 </style>
 <script type="text/javascript" src="https://www.google.com/jsapi"></script>
 <script type="text/javascript">
      google.load('visualization', '1.0', {'packages':['corechart']});
     // google.setOnLoadCallback(drawChart);

      function drawPie(div,reserved,checked,boarded,pax) {
        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Booking Status');
        data.addColumn('number', 'count');
        data.addRows([
          ['Reserved', reserved],
          ['Checked-In', checked],
          ['Boarded', boarded],
          ['Seats Available', 264-pax]
        ]);

        // Set chart options
        var title = 'Capacity = 264 Total Pax = '+pax;
        var options = {'title':title,
                       'width':500,
                       'height':300,
                        'is3D':true
         };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById(div));
        chart.draw(data, options);1
      }
      function drawColumn(div,business,premium) {
        // Create and populate the data table.
        var data = google.visualization.arrayToDataTable([
          ['Revenue', 'Premium Economy','Business Class'],
          ['',premium,business]
        ]);
      
        // Create and draw the visualization.
        new google.visualization.ColumnChart(document.getElementById(div)).
            draw(data,
                 {title:"Total Revenue",
                  width:500, height:300,
                  hAxis: {title: ""},
                  isStacked: true,
                 }
            );
      }
      

 </script>
 <table border=1px cellpadding=5px cellspacing=0px>
  <?php foreach($voyages as $key=>$v):?>
    <tr>
      <th class=fixWidth>VESSEL</th>
      <th class=fixWidth>VOYAGE#</th>
      <th class=fixWidth>ROUTE</th>
      <th class=fixWidth>DEPARTURE</th>
      <th class=fixWidth>RESERVED</th>
      <th class=fixWidth>CHECKED-IN</th>
      <th class=fixWidth>BOARDED</th>
      <th class=fixWidth>NO SHOW</th>
      <th class=fixWidth>REFUNDED</th>
      <th class=fixWidth>CANCELED</th>
      <th class=fixWidth>BUSINESS CLASS</th>
      <th class=fixWidth>PREMIUM ECONOMY</th>
      <th class=fixWidth>TOTAL PAX</th>
      <th class=fixWidth>REVENUE (Business Class)</th>
      <th class=fixWidth>REVENUE (Premium Economy)</th>
      <th class=fixWidth>TOTAL REVENUE</th>
    </tr>
    <?php $row = $v->attributes; array_shift($row);?>
    <tr>
      <td class=fixWidth><?=$row['vessel']?></td>
      <td class=fixWidth><?=$row['voyage']?></td>
      <td class=fixWidth><?=$row['origin']?> - <?=$row['destination']?></td>
      <td class=fixWidth><?=$row['departure_date']?> <?=date('g:i A',strtotime($row['departure_time']))?></td>
      <td class=fixWidth><?=$row['reserved']?></td>
      <td class=fixWidth><?=$row['checked_in']?></td>
      <td class=fixWidth><?=$row['boarded']?></td>
      <td class=fixWidth><?=$row['no_show']?></td>
      <td class=fixWidth><?=$row['refunded']?></td>
      <td class=fixWidth><?=$row['canceled']?></td>
      <td class=fixWidth><?=$row['business_cnt']?></td>
      <td class=fixWidth><?=$row['premium_cnt']?></td>
      <td class=fixWidth><?=$row['total_cnt']?></td>
      <td class=fixWidth><?=number_format($row['business_rev'])?></td>
      <td class=fixWidth><?=number_format($row['premium_rev'])?></td>
      <td class=fixWidth><?=number_format($row['total_rev'])?></td>
    </tr>
      <td colspan=18></td>
    <tr>
    </tr>
    <tr class=borderLess>
      <td colspan=9><div id="<?=$key?>"></div>
      <td colspan=9><div id="c<?=$key?>"></div></td>
    </tr>
    <script>
      drawPie(<?=$key?>,<?=$row['reserved']?>,<?=$row['checked_in']?>,<?=$row['boarded']?>,<?=$row['total_cnt']?>);
      drawColumn('c'+<?=$key?>,<?=$row['business_rev']?>,<?=$row['premium_rev']?>);
    </script>
  <?php endforeach;?>
  </table>
