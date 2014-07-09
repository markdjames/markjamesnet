<?php
function debug($input, $exit=false) {
		echo "<pre>";
		print_r($input);
		echo "</pre>";
		if ($exit) exit();
}

$data = file('accounts.csv');
$i = 0;
$current_box = 0;
foreach ($data as $dt){
	$d = array_chunk(str_getcsv($dt, ",", '"'), 7); 
	foreach($d as $val) {
		$accounts[$i]['date'] = $val[0];
		$accounts[$i]['text'] = $val[2];
		$accounts[$i]['value'] = $val[3];
		$accounts[$i]['balance'] = $val[4];
		$i++;
	}
}

date_default_timezone_set('Europe/London');

$date1 = new DateTime('2013-12-01');
$date2 = new DateTime(); 

$diff = $date1->diff($date2);
$months = (($diff->format('%y') * 12) + $diff->format('%m'));



$unacounted = array();

foreach ($accounts as $p) {
	$d = explode("/", $p['date']);
	if (date('Y-m', strtotime($d[2]."-".$d[1]."-".$d[0])) != "2013-11") {
		$by_month[date('Y-m', strtotime($d[2]."-".$d[1]."-".$d[0]))."-01"][] = $p;
	}
}

foreach($by_month as $date=>$accounts) {
	
	$totals['incoming'][$date] = array(
				'Mark'=>0,
				'Samantha'=>0,
				'Refunds'=>0,
				'Savings'=>0,
				'Unaccounted'=>0
			);
	$totals['outgoing'][$date] = array(
				'Mortgage'=>0,
				'Rent'=>0,
				'Credit Card'=>0,
				'Council Tax'=>0,
				'Gas and Electricity'=>0,
				'TV Licence'=>0,
				'Virgin Media'=>0,
				'Thames Water'=>0,
				'Supermarkets'=>0,
				'Insurance'=>0,
				'Takeaways'=>0,
				'Bed'=>0,
				'Amazon'=>0,
				'Travel'=>0,
				'Extras'=>0,
				'Savings'=>0,
				'Cash'=>0,
				'Unaccounted'=>0
			);
	
	foreach ($accounts as $p) {

		if ($p['value']>0) {
			if(
				stripos($p['text'], 'Philharmonia')!==false ||
				stripos($p['text'], 'MJS FIRST DIRECT')!==false ||
				stripos($p['text'], 'ALIENATION')!==false ) 
			{
				$totals['incoming'][$date]['Mark'] += $p['value'];
			
			} elseif(stripos($p['text'], 'MISS S WALKER')!==false) {
				$totals['incoming'][$date]['Samantha'] += $p['value'];
				
			} elseif(stripos($p['text'], 'Refund')!==false) {
				$totals['incoming'][$date]['Refunds'] += $p['value'];
			
			} elseif( 
				stripos($p['text'], 'A/C 66114594')!==false || 
				stripos($p['text'], 'A/C 25151827')!==false
			) {
				$totals['incoming'][$date]['Savings'] += $p['value'];
				
			} else {
				$totals['incoming'][$date]['Unaccounted'] += $p['value'];
				$unaccounted['incoming'][] = $p;
			}
		} else {
				
			if(stripos($p['text'], 'GATEPARKPROPERTIES')!==false) {
				$totals['outgoing'][$date]['Rent'] += $p['value'];
				
			} elseif($p['text']=="'HALIFAX" || $p['text']=="'HALIFAX , INITIAL PAYMENT") {
				$totals['outgoing'][$date]['Mortgage'] += $p['value'];
			
			} elseif(stripos($p['text'], 'Council')!==false || stripos($p['text'], 'Islington')!==false) {
				$totals['outgoing'][$date]['Council Tax'] += $p['value'];
				
			} elseif(
				stripos($p['text'], 'Atlantic')!==false || 
				stripos($p['text'], 'Electric')!==false ||
				stripos($p['text'], 'E.ON')!==false
			) {
				$totals['outgoing'][$date]['Gas and Electricity'] += $p['value'];
				
			} elseif(stripos($p['text'], 'Licence')!==false) {
				$totals['outgoing'][$date]['TV Licence'] += $p['value'];
				
			} elseif(stripos($p['text'], 'Virgin')!==false) {
				$totals['outgoing'][$date]['Virgin Media'] += $p['value'];
				
			} elseif(stripos($p['text'], 'Thames Water')!==false) {
				$totals['outgoing'][$date]['Thames Water'] += $p['value'];
				
			} elseif(stripos($p['text'], 'BARCLAYS PRTNR FIN')!==false) {
				$totals['outgoing'][$date]['Bed'] += $p['value'];
			
			} elseif(
				stripos($p['text'], 'Dominos')!==false || 
				stripos($p['text'], 'WWW.HUNGRYH')!==false
			) {
				$totals['outgoing'][$date]['Takeaways'] += $p['value'];

			} elseif(
				stripos($p['text'], 'Zurich')!==false || 
				stripos($p['text'], 'Admiral')!==false
			) {
				$totals['outgoing'][$date]['Insurance'] += $p['value'];
				
			} elseif(
				stripos($p['text'], 'W M MORRISONS')!==false || 
				stripos($p['text'], 'MARKS AND SPENCER')!==false || 
				stripos($p['text'], 'TESCO STORES')!==false || 
				stripos($p['text'], 'SAINSBURYS')!==false ||
				stripos($p['text'], 'ASDA STORES LTD')!==false ||
				stripos($p['text'], 'ASDA HOME')!==false ||
				stripos($p['text'], 'WAITROSE')!==false
			) {
				$totals['outgoing'][$date]['Supermarkets'] += $p['value'];
				
			} elseif(stripos($p['text'], 'NATWEST PLATINUM')!==false) {
				$totals['outgoing'][$date]['Credit Card'] += $p['value'];
			
			} elseif(stripos($p['text'], 'AMAZON')!==false) {
				$totals['outgoing'][$date]['Amazon'] += $p['value'];
				
			} elseif(
				stripos($p['text'], 'EAST MIDLANDS')!==false || 
				stripos($p['text'], 'EC MAINLINE')!==false || 
				stripos($p['text'], 'FGW')!==false ||
				stripos($p['text'], 'SCOTRAIL')!==false ||
				stripos($p['text'], 'LUL TICKET MACHINE')!==false ||
				stripos($p['text'], 'TFL CC')!==false
			) {
				$totals['outgoing'][$date]['Travel'] += $p['value'];
				
			} elseif( 
				stripos($p['text'], 'Spotify')!==false || 
				stripos($p['text'], 'ALZHEIMERS')!==false || 
				stripos($p['text'], 'ACTION AID')!==false || 
				stripos($p['text'], '1 and 1 Internet')!==false ||
				stripos($p['text'], 'Netflix')!==false ||
				stripos($p['text'], 'H3G')!==false
			) {
				$totals['outgoing'][$date]['Extras'] += $p['value'];
				
			} elseif( 
				stripos($p['text'], 'TO A/C 66114594')!==false || 
				stripos($p['text'], 'TO A/C 25151827')!==false ||
				stripos($p['text'], 'WWW.PERSHING.CO.UK')!==false
			) {
				$totals['outgoing'][$date]['Savings'] += $p['value'];
				
			} elseif(stripos($p['text'], 'MISS S WALKER')!==false) {
				$totals['incoming'][$date]['Samantha'] += $p['value'];

			} elseif(stripos($p['text'], 'MARK JAMES')!==false) {
				$totals['incoming'][$date]['Mark'] += $p['value'];

			} elseif(strlen($p['text']) < 21 && stripos($p['value'], '.')===false && abs((int) $p['value']) <=250 && is_int(abs((int) $p['value'])/10)) {
				$totals['outgoing'][$date]['Cash'] += $p['value'];
				//echo $p['text']."<br />";
			
			} else {
				//debug($p);
				$totals['outgoing'][$date]['Unaccounted'] += $p['value'];
				$unaccounted['outgoing'][] = $p;
			}
		}
	}
	$all_totals[] = $totals;
}
//debug($totals, true);

$first = reset($accounts);
$last = end($accounts);

function randomColor() {
	$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
    return $color;
}

?>
<style>
body {
	font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
}
td, th {
	padding:10px;
	border:1px solid #ccc;
	border-spacing:0;
}
tr:hover {
	background-color:#3CF;
}
canvas{
}
</style>
<script src='js/chart.min.js' type="text/javascript"></script>

<canvas id="canvas" height="450" width="1000"></canvas>
<!--canvas id="canvas2" height="450" width="1000"></canvas-->
<canvas id="canvas3" height="450" width="1000"></canvas>

<?php
$months = array();
$data['incoming'] = array();
$data['outgoing'] = array();

foreach ($totals as $dir=>$total) {
	$total_average = array();
	$total_total = 0;
	?>
    <h3><?=ucwords($dir)?></h3>
    <table cellpadding="10" cellspacing="0">
        <tr>
            <th>&nbsp;</th>
            <?php
            foreach ($total as $date=>$details) {
                ?>
                <th><?=date('F Y', strtotime($date))?></th>
                <?php
                $months[date('F Y', strtotime($date))] = date('F Y', strtotime($date));
            }
            ?>
            <th style='border-left:2px solid #000'>Average</th>
            <th style='border-left:2px solid #000'>Totals</th>
        </tr>
        <?php
        $_totals = $total;
        foreach ($total as $date=>$details) {
           
            foreach ($details as $type=>$value) {
            	$color = ($dir=='outgoing') ? randomColor() : '#ffffff' ;
                ?>
                <tr style='background-color:<?=$color;?>'>
                    <td><?=$type?></td>
                    <?php
                    $averages = array();
                    foreach ($_totals as $date=>$details) {
                        ?>
                        <td><?=abs($details[$type])?></td>
                        <?php
                        if ($details[$type]!=0) $averages[] = $details[$type];
                    }

					$total_total += abs(array_sum($averages));

					if ($dir=='outgoing') $data['expenses'][] = array('value'=>abs($details[$type]), 'color'=>$color);
                    ?>
                    <td style='border-left:2px solid #000'><?=(count($averages)) ? number_format(abs(array_sum($averages))/count($averages), 2) : 0 ;?></td>
                    <td style='border-left:2px solid #000'><?=number_format(abs(array_sum($averages)), 2);?></td>
                </tr>
                <?php
            }
            break;
        }
		
        ?>
        <tr>
            <td>&nbsp;</td>
            <?php
            foreach ($_totals as $date=>$details) {
                ?>
                <td><?=abs(array_sum($details))?></td>
                <?php
                $total_average[] = abs(array_sum($details));
                $data[$dir][] = abs(array_sum($details));
            }
            ?>
            <td style='border-left:2px solid #000'><?=number_format(abs(array_sum($total_average)/count($total_average)), 2);?></td>
            <td style='border-left:2px solid #000'><?=number_format($total_total, 2);?></td>
        </tr>
    </table>
    <?php	
	if (is_array($unaccounted[$dir])) {
		?>
		<h3>Unaccount > &pound;50</h3>
        <ul>
			<?php
			foreach ($unaccounted[$dir] as $un) {
				if (abs($un['value'])>50) {
					?>
	                <li><?=$un['date']." / ".$un['text']." / ".$un['value']?></li>
	                <?php
            	}
			}
			?>
       	</ul>
        <?php
	}       
}
?>
<script>
var lineChartData = {
	labels : [<?= '"'.implode('","', $months).'"'; ?>],
	datasets : [
		{
			fillColor : "rgba(139,255,139,0.5)",
			strokeColor : "rgba(0,255,0,1)",
			pointColor : "rgba(139,255,139,1)",
			pointStrokeColor : "#fff",
			data : [<?= implode(",", $data['incoming']); ?>]
		},
		{
			fillColor : "rgba(255,139,139,0.5)",
			strokeColor : "rgba(255,0,0,1)",
			pointColor : "rgba(255,139,139,1)",
			pointStrokeColor : "#fff",
			data : [<?= implode(",", $data['outgoing']); ?>]
		}
	]
}

var barChartData = {
	labels : [<?= '"'.implode('","', $months).'"'; ?>],
	datasets : [
		{
			fillColor : "rgba(220,220,220,0.5)",
			strokeColor : "rgba(220,220,220,1)",
			data : [65,59,90,81,56,55,40]
		},
		{
			fillColor : "rgba(151,187,205,0.5)",
			strokeColor : "rgba(151,187,205,1)",
			data : [28,48,40,19,96,27,100]
		}
	]
}

var pieData = <?=json_encode($data['expenses']); ?>;

var myLine = new Chart(document.getElementById("canvas").getContext("2d")).Line(lineChartData);
//var myBar = new Chart(document.getElementById("canvas2").getContext("2d")).Bar(barChartData);
var myPie = new Chart(document.getElementById("canvas3").getContext("2d")).Pie(pieData, {
	segmentShowStroke : false
});

</script>