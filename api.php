<?php

$plan = $_POST['plan'];  //v pripade demonu je v plan zadana prestiz

$zam = $_POST['zam'];   //v zam je zadane cislo pleneni/demoniho eventu 5 - karavana, 6 - obetiste, 7 - trosky nezn chramu, 8 - proklety chram

if ($zam == 1){

$plan += 1;

$i = 2;

$plan2 = 1;

while ($i <= $plan){

//je sudé?

$zao = ceil($i/2) - $i / 2;

if ($zao == 0){

$plan2 += 1;

}

$i += 1;

}

echo ($plan2 * 2500) . " x Gnóm s kuší\n";

echo ($plan * 30) . " x Těžká balista\n";

echo ($plan * 30) . " x Těžký Katapult\n";

echo ($plan2 * 50) . " x Osedlaný Mamut s Gnomí Posádkou\n";

}

elseif ($zam == 2){

$plan += 1;

$i = 2;

$plan2 = 1;

while ($i <= $plan){

//je sudé?

$zao = ceil($i/2) - $i / 2;

if ($zao != 0){

$plan2 += 1;

}

$i += 1;

}

echo ($plan * 5) . " x Mág země\n";

echo ($plan * 5) . " x Exorcista\n";

echo ($plan * 5) . " x Flamekeeper\n";

echo ($plan2 * 1) . " x Kamenná věž\n";

}

elseif ($zam == 3){

$plan += 1;

echo ($plan * 1000) . " x Zombie\n";

echo ($plan * 50) . " x Stín\n";

echo ($plan * 1000) . " x Kostlivec\n";

echo ($plan * 1000) . " x Uvězněná duše\n";

}

elseif ($zam == 4){

$plan -= 1;

$y = $plan;

$x = $y/2;

function umocni($y,$x) {

$tmp = 1;

for ($i=0; $i<$y; $i++) {

$tmp = $tmp*$x;

if (($i - $y) == -0.5){

$tmp = $tmp/$x;

$tmp = $tmp*(sqrt($x));

}

}

return $tmp;

}

$x = umocni($x,2) ;

$meduza = ceil($x * 4);

$jed1 = ceil($x * 1000);

$jed2 = ceil($x * 100);

if ($plan > -1){

echo ($jed1) . " x Vlkodlak\n";

echo ($jed1) . " x Sněhobílý Vlkodlak\n";

echo ($jed1) . " x Kancodlak\n";

echo ($jed1) . " x Sněhobílý Kancodlak\n";

echo ($jed1) . " x Medvědodlak\n";

echo ($jed1) . " x Sněhobílý Mědvědodlak\n";

echo ($jed2) . " x Mamutodlak\n";

echo ($jed2) . " x Sněhobílý Mamutodlak\n";

echo ($meduza) . " x Medůza\n";

}

else {

echo (708) . " x Vlkodlak\n";

echo (708) . " x Sněhobílý Vlkodlak\n";

echo (708) . " x Kancodlak\n";

echo (708) . " x Sněhobílý Kancodlak\n";

echo (708) . " x Medvědodlak\n";

echo (708) . " x Sněhobílý Mědvědodlak\n";

echo (71) . " x Mamutodlak\n";

echo (71) . " x Sněhobílý Mamutodlak\n";

echo (3) . " x Medůza\n";

}

}

else if ($zam == 5){ //demoni karavana

echo (ceil($plan / 7837)) . " x Plamenný démon\n";

echo (ceil($plan / 8807)) . " x Plamenný démon s bičem\n";

echo (ceil($plan / 9928)) . " x Plamenný démon mág\n";

echo (ceil($plan / 92)) . " x Následovník démonů - Vyšší Zasvěcenec\n";

echo (ceil($plan / 403)) . " x Následovník démonů - Kacíř\n";

}

else if ($zam == 6){ //obětiště obsazené démony

echo (ceil($plan / 167)) . " x Následovník démonů - Kacíř\n";

echo (ceil($plan / 19146)) . " x Plamenný démon mág\n";

echo (ceil($plan / 18495)) . " x Plamenný démon mág\n";

echo (ceil($plan / 438859)) . " x Plamenný démon arcimág\n";

echo (ceil($plan / 644519)) . " x Démon Bojový Mág Temnoty\n";

}

else if ($zam == 7){ //trosky neznámého chrámu

echo (ceil($plan / 267876)) . " x Medůza\n";

echo (ceil($plan / 121)) . " x Kamenná socha\n";

}

else if ($zam == 8){ //prokletý chrám

echo (ceil($plan / 9990)) . " x Ohnivý Služebník\n";

echo (ceil($plan / 1513595)) . " x Nižší ohnivý splozenec\n";

}

?>
