<?php ob_start()?>
<?php $trip= array('01:00:00'=>1,'06:00:00'=>2,'15:00:00'=>3,'20:00:00'=>4,'03:30:00'=>1,'09:30:00'=>2,'17:30:00'=>3,'22:30:00'=>4)?>
<?php if(count($data)):?>
  <?php 
    $date=array();
    $col=array();
    $col['count'] =0;
  ?>
  <?php foreach($data as $key=>$d):?>
    <?php $date[] = $key;?>
    <?php 
       foreach($d as $c){
         $col['count'] +=1;
         $col['voyage'][] = $c['voyage'];
         $col['trips'][] = 1;
         $col['rev'][] = number_format($c['total_rev']);
         $col['bc_full'][] = $c['bc_full'];
         $col['bc_senior'][] = $c['bc_senior'];
         $col['bc_student'][] = $c['bc_student'];
         $col['bc_half'][] = $c['bc_half'];
         $col['bc_pwd'][] = $c['bc_pwd'];
         $col['bc_full_promo'][] = $c['bc_full_promo'];
         $col['bc_ssp_promo'][] = $c['bc_ssp_promo'];
         $col['bc_pass'][] = $c['bc_pass'];
         $col['bc_total_pax'][] = $c['bc_total_pax'];
         $col['bc_lf'][] = number_format($c['bc_total_pax']/159*100);

         $col['pe_full'][] = $c['pe_full'];
         $col['pe_senior'][] = $c['pe_senior'];
         $col['pe_student'][] = $c['pe_student'];
         $col['pe_half'][] = $c['pe_half'];
         $col['pe_pwd'][] = $c['pe_pwd'];
         $col['pe_pass'][] = $c['pe_pass'];
         $col['pe_total_pax'][] = $c['pe_total_pax'];
         $col['pe_lf'][] = number_format($c['pe_total_pax']/105*100);

         $col['total_lf'][] = number_format($c['total_pax']/264*100);

         $col['total_pax_al'][] = $c['total_pax'];
         $col['total_pax'][] = $c['total_pax'];
         $col['ave_rev'][] = number_format($c['total_rev']/$c['total_pax']);
         $col['b_cap'][] = 159;
         $col['pe_cap'][] = 105;
         $col['total_cap'][] = 264;

       }
       $col['count'] +=1;
       $col['voyage'][]='Total';
       $col['trips'][] = '<b>'.count($d).'</b>';
       $col['rev'][] = '<b>'.number_format(array_sum(array_map(function($trev){return $trev['total_rev'];},$d))).'</b>';
       $col['bc_full'][] = '<b>'.array_sum(array_map(function($bcfull){return $bcfull['bc_full'];},$d)).'</b>';
       $col['bc_senior'][] = '<b>'.array_sum(array_map(function($bcsenior){return $bcsenior['bc_senior'];},$d)).'</b>';
       $col['bc_student'][] = '<b>'.array_sum(array_map(function($bcstudent){return $bcstudent['bc_student'];},$d)).'</b>';
       $col['bc_half'][] = '<b>'.array_sum(array_map(function($bchalf){return $bchalf['bc_half'];},$d)).'</b>';
       $col['bc_pwd'][] = '<b>'.array_sum(array_map(function($bcpwd){return $bcpwd['bc_pwd'];},$d)).'</b>';
       $col['bc_full_promo'][] = '<b>'.array_sum(array_map(function($bcfullpromo){return $bcfullpromo['bc_full_promo'];},$d)).'</b>';
       $col['bc_ssp_promo'][] = '<b>'.array_sum(array_map(function($bcssppromo){return $bcssppromo['bc_ssp_promo'];},$d)).'</b>';
       $col['bc_pass'][] = '<b>'.array_sum(array_map(function($bcpass){return $bcpass['bc_pass'];},$d)).'</b>';

       $col['pe_full'][] = '<b>'.array_sum(array_map(function($pefull){return $pefull['pe_full'];},$d)).'</b>';
       $col['pe_senior'][] = '<b>'.array_sum(array_map(function($pesenior){return $pesenior['pe_senior'];},$d)).'</b>';
       $col['pe_student'][] = '<b>'.array_sum(array_map(function($pestudent){return $pestudent['pe_student'];},$d)).'</b>';
       $col['pe_half'][] = '<b>'.array_sum(array_map(function($pehalf){return $pehalf['pe_half'];},$d)).'</b>';
       $col['pe_pwd'][] = '<b>'.array_sum(array_map(function($pepwd){return $pepwd['pe_pwd'];},$d)).'</b>';
       $col['pe_pass'][] = '<b>'.array_sum(array_map(function($pepass){return $pepass['pe_pass'];},$d)).'</b>';

       $col['total_pax'][] = '<b>'.array_sum(array_map(function($totalpax){return $totalpax['total_pax'];},$d)).'</b>';
       $col['total_pax_al'][] = '<b>'.number_format(array_sum(array_map(function($bctotalpax){return $bctotalpax['bc_total_pax'];},$d))/count($d) +
        array_sum(array_map(function($petotalpax){return $petotalpax['pe_total_pax'];},$d))/count($d)).'</b>';

       $col['bc_total_pax'][] = '<b>'.number_format(array_sum(array_map(function($bctotalpax){return $bctotalpax['bc_total_pax'];},$d))/count($d)).'</b>';
       $col['pe_total_pax'][] = '<b>'.number_format(array_sum(array_map(function($petotalpax){return $petotalpax['pe_total_pax'];},$d))/count($d)).'</b>';

       $col['bc_lf'][] = '<b>'.number_format((array_sum(array_map(function($bctotalpax){return $bctotalpax['bc_total_pax'];},$d))/count($d)) / 159*100).'</b>';
       $col['pe_lf'][] = '<b>'.number_format((array_sum(array_map(function($petotalpax){return $petotalpax['pe_total_pax'];},$d))/count($d))/105*100).'</b>';
       $col['total_lf'][] = '<b>'.number_format((array_sum(array_map(function($bctotalpax){return $bctotalpax['bc_total_pax'];},$d))/count($d) +
        array_sum(array_map(function($petotalpax){return $petotalpax['pe_total_pax'];},$d))/count($d))/264*100).'</b>';

       $col['ave_rev'][] = '<b>'.number_format(array_sum(array_map(function($avrev){return $avrev['total_rev'];},$d))/ array_sum(array_map(function($totalpax){return $totalpax['total_pax'];},$d))).'</b>';
       $col['b_cap'][] = '<b>159</b>';
       $col['pe_cap'][] = '<b>105</b>';
       $col['total_cap'][] = '<b>264</b>';
    ?>
  <?php endforeach;?>

      <table width=200px;>
        <tr>
          <th bgcolor=#92d050>Date</th>
          <?php foreach($date as $d8):?>
          <td colspan=<?=count($data[$d8])+1?> bgcolor=#92d050><center><?=date('l, F d, Y',strtotime($d8))?></center></td>
          <?php endforeach;?>
        </tr>
        <tr>
          <th class="bold right">Trip No. Voyage</th>
          <td><?= implode('</td><td>',$col['voyage'])?></td>
        </tr>
        <tr>
      <th class="bold">No of Trips</th>
          <td><?= implode('</td><td>',$col['trips'])?></td>
        </tr>
        <tr>
      <th class="bold">Total Revenue</th>
          <td bgcolor=yellow><?=implode('</td><td bgcolor=yellow>',$col['rev'])?></td>
        </tr>
        <tr>
      <th class="bold">Ave. Revenue Per Trip</th>
          <td><?=implode('</td><td>',$col['rev'])?></td>
        </tr>
        <tr>
      <th>No. of Passengers Loaded</th>
          <td class="space" colspan=<?=$col['count']?>>&nbsp;</td>
        </tr>
        <tr>
      <th>BC-Full</th>
          <td><?= implode('</td><td>',$col['bc_full'])?></td>
        </tr>
        <tr>
      <th>BC-Senior</th>
          <td><?= implode('</td><td>',$col['bc_senior'])?></td>
        </tr>
        <tr>
      <th>BC-Student</th>
          <td><?= implode('</td><td>',$col['bc_student'])?></td>
        </tr>
        <tr>
      <th>BC-Half Fare</th>
          <td><?= implode('</td><td>',$col['bc_half'])?></td>
        </tr>
        <tr>
      <th>BC-PWD</th>
          <td><?= implode('</td><td>',$col['bc_pwd'])?></td>
        </tr>
        <tr>
      <th>BC-Full Fare Promo</th>
          <td><?= implode('</td><td>',$col['bc_full_promo'])?></td>
        </tr>
        <tr>
      <th>BC-SENIORS/STUDENT/PWD Promo</th>
          <td><?= implode('</td><td>',$col['bc_ssp_promo'])?></td>
        </tr>
        <tr>
      <th>BC-W/PASS</th>
          <td><?= implode('</td><td>',$col['bc_pass'])?></td>
        </tr>
        <tr>
      <th>P/E-Full</th>
          <td><?= implode('</td><td>',$col['pe_full'])?></td>
        </tr>
        <tr>
      <th>P/E-Senior</th>
          <td><?= implode('</td><td>',$col['pe_senior'])?></td>
        </tr>
        <tr>
      <th>P/E-Student</th>
          <td><?= implode('</td><td>',$col['pe_student'])?></td>
        </tr>
        <tr>
      <th>P/E-Half Fare</th>
          <td><?= implode('</td><td>',$col['pe_half'])?></td>
        </tr>
        <tr>
      <th>P/E-PWD</th>
          <td><?= implode('</td><td>',$col['pe_pwd'])?></td>
        </tr>
        <tr>
      <th>PE-W/PASS</th>
          <td><?= implode('</td><td>',$col['pe_pass'])?></td>
        </tr>
        <tr>
      <th class="bold">Total Passengers</th>
          <td><?= implode('</td><td>',$col['total_pax'])?></td>
        </tr>
        <tr>
      <th class="bold">Revenue on Passengers</th>
          <td><?=implode('</td><td>',$col['rev'])?></td>
        </tr>
        <tr>
      <th class="bold">Ave. Passenger's Fare</th>
          <td><?= implode('</td><td>',$col['ave_rev'])?></td>
        </tr>
        <tr>
      <th class="bold space">Legend: Vessel Status</th>
          <td class="space" colspan=<?=$col['count']?>>&nbsp;</td>
        </tr>
        <tr>
      <th class="space">BC (Business Class)</th>
          <td class="space" colspan=<?=$col['count']?>>&nbsp;</td>
        </tr>
        <tr>
      <th class="space">P/E (Premium Economy)</th>
          <td class="space" colspan=<?=$col['count']?>>&nbsp;</td>
        </tr>
        <tr>
          <th bgcolor=#92d050>Date</th>
          <?php foreach($date as $d8):?>
          <td colspan=<?=count($data[$d8])+1?> bgcolor=#92d050><center><?=date('l, F d, Y',strtotime($d8))?></center></td>
          <?php endforeach;?>
        </tr>
        <tr>
      <th class="bold">Capacity</th>
          <td class="space" colspan=<?=$col['count']?>>&nbsp;</td>
        </tr>
        <tr>
      <th>BC</th>
          <td><?= implode('</td><td>',$col['b_cap'])?></td>
        </tr>
        <tr>
      <th>P/E</th>
          <td><?= implode('</td><td>',$col['pe_cap'])?></td>
        </tr>
        <tr>
      <th>TOTAL</th>
          <td><?= implode('</td><td>',$col['total_cap'])?></td>
        </tr>
        <tr>
      <th class="bold">Actual Pax Loaded</th>
          <td class="space" colspan=<?=$col['count']?>>&nbsp;</td>
        </tr>
        <tr>
      <th>BC</th>
          <td><?= implode('</td><td>',$col['bc_total_pax'])?></td>
        </tr>
        <tr>
      <th>P/E</th>
          <td><?= implode('</td><td>',$col['pe_total_pax'])?></td>
        </tr>
        <tr>
      <th>TOTAL</th>
          <td><?= implode('</td><td>',$col['total_pax_al'])?></td>
        </tr>
        <tr>
      <th class="bold">Load Factor:</th>
          <td class="space" colspan=<?=$col['count']?>>&nbsp;</td>
        </tr>
        <tr>
      <th>BC</th>
          <td><?= implode('%</td><td>',$col['bc_lf'])?>%</td>
        </tr>
        <tr>
      <th>P/E</th>
          <td><?= implode('%</td><td>',$col['pe_lf'])?>%</td>
        </tr>
        <tr>
      <th>Total Load Factor</th>
          <td><?= implode('%</td><td>',$col['total_lf'])?>%</td>
        </tr>
      </table>
<?php $output = ob_get_clean()?>
<?php
     $file ='ACCOUNTING_REPORT.xls';
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
     echo $output;
?>
<div class="clearfix"></div>
<?php endif;?>
