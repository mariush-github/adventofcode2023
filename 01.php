<?php

$lines = explode(chr(0x0A), file_get_contents(__DIR__. '/inputs/01.txt'));

$lines_test1 = explode(chr(0x0D).chr(0x0A),
'1abc2
pqr3stu8vwx
a1b2c3d4e5f
treb7uchet');

$lines_test2 = explode(chr(0x0D).chr(0x0A),'two1nine
eightwothree
abcone2threexyz
xtwone3four
4nineeightseven2
zoneight234
7pqrstsixteen');


function extractValue($text,$mode=1) {
	$codes = ['one'=>1,'two'=>2,'three'=>3,'four'=>4,'five'=>5, 'six'=>6,'seven'=>7, 'eight'=>8,'nine'=>9];
	$values = array();
	
	for ($i=0;$i<strlen($text);$i++) {
		$c = substr($text,$i,1);
		if (ctype_digit($c)==true) { 
			array_push($values,intval($c)); 
		} else {
			if ($mode==2) {
				foreach ($codes as $sfor =>$repl) {
					if (substr($text,$i,strlen($sfor))==$sfor) {array_push($values,$repl);}
				}
			}
		}
	}
	return ($values[0]*10+$values[count($values)-1]);
}

function calculate(&$lines,$part = 1) {
	$sum = 0;
	foreach ($lines as $key => $liner) {
		$line =trim($liner,"\r\n");
		if ($line!='') {
			$nr = 0;
			$nr = extractValue($line,$part);
			$sum+=$nr;

		}
	}
	return $sum;
}

$sum1 = calculate($lines,1);
echo "part 1 = $sum1 \n";
$sum2 = calculate($lines,2);
echo "part 2 = $sum2 \n";




?>