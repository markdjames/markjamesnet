<?php
function debug($input, $exit=false) {
		echo "<pre>";
		print_r($input);
		echo "</pre>";
		if ($exit) exit();
}

$data = file('accounts_2.csv');
$i = 0;
$current_box = 0;
foreach ($data as $dt){
	$d = array_chunk(str_getcsv($dt, ",", '"'), 5); 
	foreach($d as $val) {
		$accounts[$i]['date'] = $val[0];
		$accounts[$i]['text'] = $val[2];
		$accounts[$i]['value'] = $val[3];
		$accounts[$i]['balance'] = $val[4];
		$i++;
	}
}

date_default_timezone_set('Europe/London');

$date1 = new DateTime('2011-08-22');
$date2 = new DateTime('2013-10-19'); //DateTime(); 

$diff = $date1->diff($date2);
$months = (($diff->format('%y') * 12) + $diff->format('%m'));

$duration = array(
	'Lizz'=>$months,
	'Mark'=>$months,
	'Adelle'=>$months,
	'Samantha'=>$months,
	'Martin'=>$months,
	'Natalie'=>$months
);
$rent = array(
	'Lizz'=>375,
	'Mark'=>511,
	'Adelle'=>461,
	'Samantha'=>250,
	'Martin'=>375,
	'Natalie'=>461
);
$starting_balance = array(
	'Lizz'=>425-137,
	'Mark'=>561-137,
	'Adelle'=>511-137,
	'Samantha'=>0,
	'Martin'=>425-137,
	'Natalie'=>0		
);
$owed = array(
	'Lizz'=>20,
	'Mark'=>0,
	'Adelle'=>0,
	'Samantha'=>215,
	'Martin'=>0,
	'Natalie'=>166		
);
$totals = array(
	'incoming'=>array(
		'Lizz'=>0,
		'Mark'=>0,
		'Adelle'=>0,
		'Samantha'=>0,
		'Martin'=>0,
		'Natalie'=>0,
		'Council Tax Rebate'=>0,
		'Unaccounted'=>0,
		
	),
	'outgoing'=>array(
		'Lizz'=>0,
		'Mark'=>0,
		'Adelle'=>0-13.06,
		'Samantha'=>0-13.06,
		'Martin'=>0-13.06,
		'Natalie'=>0-13.06,	
		'Rent'=>0,
		'Council Tax'=>0,
		'Gas and Electricity'=>0,
		'TV Licence'=>0,
		'Virgin Media'=>0,
		'Thames Water'=>0,
		'Extras'=>0,
		'Unaccounted'=>0
	)
);
$unacounted = array();

foreach ($accounts as $p) {
	
	if ($p['value']>0) {
		if (stripos($p['text'], 'Skelly')!==false) {
			$totals['incoming']['Lizz'] += $p['value'];
			
		} elseif(stripos($p['text'], 'James')!==false) {
			$totals['incoming']['Mark'] += $p['value'];
		
		} elseif(stripos($p['text'], 'Havard')!==false) {
			$totals['incoming']['Adelle'] += $p['value'];
			
		} elseif(stripos($p['text'], 'Walker')!==false) {
			$totals['incoming']['Samantha'] += $p['value'];
		
		} elseif(stripos($p['text'], 'Tomsky')!==false) {
			$totals['incoming']['Martin'] += $p['value'];
			
		} elseif(stripos($p['text'], 'Tacq')!==false) {
			$totals['incoming']['Natalie'] += $p['value'];
		
		} elseif(stripos($p['text'], 'Islington')!==false) {
			$totals['incoming']['Council Tax Rebate'] += $p['value'];
			
		} else {
			$totals['incoming']['Unaccounted'] += $p['value'];
			$unaccounted[] = $p;
		}
	} else {

		if (stripos($p['text'], 'Skelly')!==false || stripos($p['text'], '000053')!==false) {
			$totals['outgoing']['Lizz'] += $p['value'];
			//debug($p);
			
		} elseif(stripos($p['text'], 'James')!==false) {
			$totals['outgoing']['Mark'] += $p['value'];
			//debug($p);
		
		} elseif(stripos($p['text'], 'Havard')!==false) {
			$totals['outgoing']['Adelle'] += $p['value'];
			
		} elseif(stripos($p['text'], 'Walker')!==false) {
			$totals['outgoing']['Samantha'] += $p['value'];
		
		} elseif(stripos($p['text'], 'Tomsky')!==false) {
			$totals['outgoing']['Martin'] += $p['value'];
			
		} elseif(stripos($p['text'], 'Tacq')!==false) {
			$totals['outgoing']['Natalie'] += $p['value'];
		
		} elseif(stripos($p['text'], 'GATEPARKPROPERTIES')!==false) {
			$totals['outgoing']['Rent'] += $p['value'];
		
		} elseif(stripos($p['text'], 'Council')!==false || stripos($p['text'], 'Islington')!==false) {
			$totals['outgoing']['Council Tax'] += $p['value'];
			
		} elseif(stripos($p['text'], 'Atlantic')!==false || stripos($p['text'], 'Electric')!==false) {
			$totals['outgoing']['Gas and Electricity'] += $p['value'];
			
		} elseif(stripos($p['text'], 'Licence')!==false) {
			$totals['outgoing']['TV Licence'] += $p['value'];
			
		} elseif(stripos($p['text'], 'Virgin')!==false) {
			$totals['outgoing']['Virgin Media'] += $p['value'];
			
		} elseif(stripos($p['text'], 'Thames Water')!==false) {
			$totals['outgoing']['Thames Water'] += $p['value'];
			
		} elseif(stripos($p['text'], 'Asda')!==false || stripos($p['text'], 'ODEON')!==false) {
			$totals['outgoing']['Extras'] += $p['value'];
		
		} else {
			$totals['outgoing']['Unaccounted'] += $p['value'];
			$unaccounted[] = $p;
		}
	}
}
$first = reset($accounts);
$last = end($accounts);
/**
 * Adjustments for extra month rent payback;
 */
$totals['outgoing']['Mark'] += 100;
$totals['outgoing']['Adelle'] -= 100;

$totals['outgoing']['Lizz'] += 250;
$totals['outgoing']['Martin'] -= 250;

$totals['outgoing']['Mark'] += $totals['incoming']['Council Tax Rebate'];
unset($totals['incoming']['Council Tax Rebate']);
unset($totals['incoming']['Unaccounted']);

/**
 * Fixes for mine and Lizz's outgoings as we have withdrawn money for other reasons
 */
$totals['outgoing']['Unaccounted'] += $totals['outgoing']['Lizz'];
$totals['outgoing']['Lizz'] = -(200+13.06);

$totals['outgoing']['Unaccounted'] += ($totals['outgoing']['Mark']+350);
$totals['outgoing']['Mark'] = -(350+13.06);

//$totals['outgoing']['Council Tax'] -= 930; // 6 * 155;

$bills = abs(($totals['outgoing']['Virgin Media']+$totals['outgoing']['TV Licence']+$totals['outgoing']['Gas and Electricity']+$totals['outgoing']['Thames Water']+$totals['outgoing']['Extras']+$totals['outgoing']['Unaccounted']));

$total_initial = 0;
$total_rent_month = 0;
$total_rent = 0;
$total_paid = 0;
$total_surplus = 0;
?>
<style>
body {
	font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
}
td, th {
	padding:10px;
	border:1px solid #ccc;
}
</style>
<h3>Start date: 22 / 08 / 2011</h3>
<table>
	<tr>
    	<th>&nbsp;</th>
        <th>Starting Balance</th>
        <th>Rent / month*</th>
        <th>Total Rent*</th>
        <th>Total Bills**</th>
        <th>Total Paid</th>
        <th>Average / month</th>
        <th>Owed</th>
        <th>Outgoing</th>
        <th>End Balance</th>
   	</tr>
    <?php
	foreach ($totals['incoming'] as $person=>$total) {
		if (isset($rent[$person])) {
			$surplus = $total-(($bills/6)+($rent[$person]*$duration[$person]));
			$surplus += $starting_balance[$person];
			$surplus += $totals['outgoing'][$person];
			$surplus += $owed[$person];
		}
		?>
        <tr>
        	<td><?=$person?></td>
            <td><?=(isset($starting_balance[$person]))?$starting_balance[$person]:"";?></td>
            <td><?=(isset($rent[$person])) ? $rent[$person] : ""; ?></td>
            <td><?=(isset($rent[$person])) ? number_format($rent[$person]*$duration[$person],2) : ""; ?></td>
            <td><?=(isset($rent[$person])) ? number_format($bills/6,2) : "" ;?></td>
            <td><?=(isset($rent[$person])) ? number_format($total,2) : "" ; ?></td>
            <td><?=(isset($duration[$person])) ? number_format(($total/$duration[$person]),2) : ""; ?></td>
            <td><?=(isset($rent[$person])) ? number_format($owed[$person],2) : ""; ?></td>
            <td><?=(isset($totals['outgoing'][$person])) ? number_format($totals['outgoing'][$person],2) : "" ;?></td>
            <td><?=(isset($rent[$person])) ? number_format($surplus,2) : ""; ?></td>
      	</tr>
        <?php
		if (isset($rent[$person])) $total_initial += $starting_balance[$person];
		if (isset($rent[$person])) $total_rent_month += $rent[$person];
		if (isset($rent[$person])) $total_rent += $rent[$person]*$duration[$person];
		if (isset($rent[$person])) $total_surplus += $surplus;
		$total_paid += $total;
	}
	?>
    <tr>
        <td><strong>Totals</strong></td>
        <td><strong><?=number_format($total_initial,2)?></strong><br />(<?=number_format($first['balance'],2)?>)</td>
        <td><strong><?=number_format($total_rent_month,2)?></strong></td>
        <td><strong><?=number_format($total_rent,2)?></strong><br />(<?=number_format(abs($totals['outgoing']['Rent']+$totals['outgoing']['Council Tax']),2)?>)</td>
        <td><strong><?=number_format($bills,2)?></strong></td>
        <td><strong><?=number_format($total_paid,2)?></strong></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong><?=number_format($total_surplus,2)?></strong><br />(<?=number_format($last['balance'],2)?>)</td>
    </tr>
</table>
<p>* Rent includes council tax</p>
<p>** Virgin Media / TV Licence / Gas and Electricity / Thames Water / Extras (Asda etc.)</p>
<p style='color:red'><strong>&pound;78.40 unaccounted for.</strong></p>
<?php
//debug($unaccounted, true);