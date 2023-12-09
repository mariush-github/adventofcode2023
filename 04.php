<?php

$lines = explode(chr(0x0A), trim(file_get_contents(__DIR__. '/inputs/04.txt'),"\r\n"));

$cards = array();

$sum1 = 0;
$sum2 = 0;
for ($i=1;$i<=count($lines);$i++) $cards[$i] = array('copies'=>1, 'score'=>0, 'matches'=>0);

foreach ($lines as $line) {
	$line = trim(substr($line,4));
	$line = str_replace('  ',' ',$line);
	$parts = explode('|',$line);
	$n_win = explode(' ',trim($parts[0]));
	$n_all = explode(' ',trim($parts[1]));
	$card = intval($n_win[0]);
	$numbers_win = array();
	foreach ($n_win as $idx=>$value) { if ($idx!=0) $numbers_win[$value] = 1; }
	$score = 0;
	$matches = 0;
	foreach ($n_all as $idx=>$value) { if (isset($numbers_win[$value])==true) { $score = ($score==0) ? 1 : ($score*2); $matches++; } }
	$sum1 += $score;
	//echo $card."::".json_encode($n_win)."::".json_encode($n_all)."::".$score."::".$matches."::"."\n";
	$cards[$card]['score'] = $score;
	$cards[$card]['matches'] = $matches;
	if ($matches>0) {
		for ($i=1;$i<=$matches;$i++) $cards[$card+$i]['copies'] = $cards[$card+$i]['copies'] + $cards[$card]['copies'];
	}

}
$sum2 = 0;
foreach ($cards as $card) $sum2 += $card['copies'];
echo "part 1 = $sum1\n";
echo "part 2 = $sum2\n";

?>