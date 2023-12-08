<?php

$lines = explode(chr(0x0A), trim(file_get_contents(__DIR__. '/inputs/02.txt'),"\r\n"));

//12 red cubes, 13 green cubes, and 14 blue cubes
$maximums = array ( 'red'=>12, 'green'=>13,'blue'=>14);

$sum = 0;
$sum2= 0;
foreach ($lines as $line) {
	$pos = strpos($line,':');
	$number = intval(substr(substr($line,0,$pos),5));
	$line = trim(substr($line,$pos+1));
	$line = str_replace([',',';'],['',''],$line);
	$maxline = array ( 'red'=>0, 'green'=>0,'blue'=>0);
	$valid=true;
	$parts = explode(' ',$line);
	foreach ($parts as $idx=>$part) {
		if (($part=='red') || ($part=='green') || ($part=='blue')) {
			$value = intval($parts[$idx-1]);
			if ($value>$maximums[$part]) $valid=false;
			if ($value>$maxline[$part]) $maxline[$part]=$value;
		}
	}
	echo $number."::".$line."::".( $valid==true ? "true" : "false" ).":: maxs= ".serialize($maxline)."\n";
	if ($valid==true) $sum += $number;
	$sum2 += $maxline['red']*$maxline['green']*$maxline['blue'];
}
echo "part 1 = $sum\n";
echo "part 2 = $sum2\n";

?>