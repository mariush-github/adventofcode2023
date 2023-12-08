<?php
$start_time = microtime(true);
$content = trim(file_get_contents(__DIR__. '/inputs/03.txt'),"\r\n");

// wrap the whole map into a square of dots to simplify code later
$content = str_pad('.',140,'.').chr(0x0A).$content.chr(0x0A).str_pad('.',140,'.').chr(0x0A);
$lines = explode(chr(0x0A),$content);
foreach($lines as $idx=>$line) {
	$lines[$idx] = '.'.$line.'.';
}
// now our map is 142 x 142 instead of 140x140
$numbers = [];
$numbers_cnt = -1;
$gears  = [];

// find numbers and gears, add them to arrays

for ($y=1;$y<count($lines)-1;$y++) {
	$line = $lines[$y];
	$c_prev = '.';
	for ($x=1;$x<strlen($line)-1;$x++) {
		$c = substr($line,$x,1);
		if (ctype_digit($c)==true) { // digit, is previous one digit or not
			if (ctype_digit($c_prev)==true) { // this digit is continuation of number already logged
				$numbers[$numbers_cnt]['last'] = $x; // update last character position
				$numbers[$numbers_cnt]['value'] .=$c;
			} else { // a new number starts
				$number = array('y'=>$y, 'first'=>$x,'last'=>$x, 'value'=>$c,'valid'=>false);
				$numbers_cnt++;
				$numbers[$numbers_cnt]=$number;
			}
		} else {
			if ($c!='.') { // it's a potential gear, generate a unique key for it and add it to gears.
				$key = $x.'_'.$y;
				$gears[$key] = '';
			}
		}
		$c_prev=$c;
	}
}

function add_number($x,$y,$value) {
	global $gears; 
	$key = $x.'_'.$y;
	if (isset($gears[$key])==false) return false;
	if (strpos($gears[$key],' '.$value.' ')!=false) return false;
	$gears[$key] .= ' '.$value.' ';
}
// look around the number to see if there's a gear, if gear is found, add the number to the gear's list of numbers. and update valid status of the number
foreach ($numbers as $idx=>$nr) {
	$c = substr($lines[$nr['y']],$nr['first']-1,1); if ((ctype_digit($c)==false) && ($c!='.')) { $numbers[$idx]['valid'] = true; $ret = add_number($nr['first']-1,$nr['y'],$nr['value']); }// left 
	$c = substr($lines[$nr['y']],$nr['last']+1,1); if ((ctype_digit($c)==false) && ($c!='.')) {$numbers[$idx]['valid'] = true;  $ret = add_number($nr['last']+1,$nr['y'],$nr['value']); }  // right
	if ($numbers[$idx]['valid']==false) { // line above and top corners 
		for ($x=$nr['first']-1;$x<=$nr['last']+1;$x++) { 
			$c = substr($lines[$nr['y']-1],$x,1); if ((ctype_digit($c)==false) && ($c!='.')) { $numbers[$idx]['valid'] = true;  $ret = add_number($x,$nr['y']-1,$nr['value']);  } 
		}
	}
	if ($numbers[$idx]['valid']==false) { // line below and bottom corners 
		for ($x=$nr['first']-1;$x<=$nr['last']+1;$x++) { 
			$c = substr($lines[$nr['y']+1],$x,1); if ((ctype_digit($c)==false) && ($c!='.')) {$numbers[$idx]['valid'] = true;  $ret = add_number($x,$nr['y']+1,$nr['value']); } 
		} 
	}
}
// cleanup gears and calculate sum for part 2 
$sum2 = 0;
foreach ($gears as $gear) {
	// trim extra spaces 
	$g = trim(str_replace('  ',' ',$gear));
	$items = explode(' ',$g);
	if (count($items)==2) { 
		$product = intval($items[0])*intval($items[1]); $sum2 += $product;
	}
}

$sum1 = 0;
foreach ($numbers as $idx=>$nr) {
	if ($nr['valid']==true) $sum1 += intval($nr['value']);
}
echo "part 1 = $sum1\n";
echo "part 2 = $sum2\n";

?>