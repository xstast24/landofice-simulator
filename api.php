<?php

$plan = $_POST['plan'];  //v pripade demonu je v plan zadana prestiz, v pripade pleneni pocet dobyti
$zam = $_POST['zam'];   //v zam je zadane cislo pleneni/demoniho eventu 5 - karavana, 6 - obetiste, 7 - trosky nezn chramu, 8 - proklety chram

//Gnomi velkodilna
if ($zam == 1) {
    echo (2500 + ceil($plan/2) * 2500)." x Gnóm s kuší\n"; //2500 je zaklad a pak kazde druhe dobyti pribyde (pocinaje od dobyto x1)
    echo (30 + ($plan * 30))." x Těžká balista\n"; //30 je zaklad a pak kazde dobyti pribyde
    echo (30 + ($plan * 30))." x Těžký Katapult\n"; //30 je zaklad a pak kazde dobyti pribyde
    echo (50 + ceil($plan/2) * 50) . " x Osedlaný Mamut s Gnomí Posádkou\n"; //50 je zaklad a pak kazde druhe dobyti pribyde (pocinaje od dobyto x1)
}
//Spolek mocnych
elseif ($zam == 2) {
    //$plan == dobyto X-krat
    $jednotky = array(); //jmeno => pocet
    $jednotky['Mág země'] = 5 + 5 * $plan; //5 je zaklad a pak pribyde za kazde dobyti (pri stavu "3x dobyto" bude 20 magu)
    $jednotky['Exorcista'] = 5 + 5 * $plan;
    $jednotky['Flamekeeper'] = 5 + 5 * $plan;
    $jednotky['Kamenná věž'] = max(1, ceil($plan/2)); //0 je zaklad (ale 1 je minimum), za kazde druhe dobyti 1 pribyde (tzn. dobyto x1 bude 1, x3 bude 2)
    $jednotky['Mág ledu'] = 200 * floor($plan / 25); //200 kazdych 25 dobyti (24x dobyto -> 0 magu, 25x dobyto -> 200 magu)
    $jednotky['Stínový mág'] = 100 * floor($plan / 50); //100 kazdych 50 dobyti
    $jednotky['Stínový arcimág'] = 50 * floor($plan / 75); //50 kazdych 75 dobyti
    $jednotky['Velemág země'] = 10 * floor($plan / 100); //10 kazdych 100 dobyti
    //vloz vsechny jednotky
    foreach ($jednotky as $jmeno => $pocet) {
        if ($pocet > 0) echo "$pocet x $jmeno\n";
    }
    //vloz navic 1x velemag ledu samostatne kazdych 150 dobyti (tzn. 450 dobyti jich bude 1x a 1x a 1x)
    $pocetVelemaguLedu = floor($plan / 150);
    if ($pocetVelemaguLedu > 0) echo str_repeat("1 x Velemág Ledu\n", $pocetVelemaguLedu);
}
//Katakomby
elseif ($zam == 3) {
    $plan += 1;
    echo ($plan * 1000)." x Zombie\n";
    echo ($plan * 50)." x Stín\n";
    echo ($plan * 1000)." x Kostlivec\n";
    echo ($plan * 1000)." x Uvězněná duše\n";
}
//Temna jeskyne
elseif ($zam == 4) {
    $plan -= 1;
    $y = $plan;
    $x = $y / 2;

    function umocni($y, $x)
    {
        $tmp = 1;
        for ($i = 0; $i < $y; $i++) {
            $tmp = $tmp * $x;
            if (($i - $y) == -0.5) {
                $tmp = $tmp / $x;
                $tmp = $tmp * (sqrt($x));
            }
        }
        return $tmp;
    }

    $x = umocni($x, 2);
    $meduza = ceil($x * 4);
    $jed1 = ceil($x * 1000);
    $jed2 = ceil($x * 100);
    if ($plan > -1) {
        echo ($jed1)." x Vlkodlak\n";
        echo ($jed1)." x Sněhobílý Vlkodlak\n";
        echo ($jed1)." x Kancodlak\n";
        echo ($jed1)." x Sněhobílý Kancodlak\n";
        echo ($jed1)." x Medvědodlak\n";
        echo ($jed1)." x Sněhobílý Mědvědodlak\n";
        echo ($jed2)." x Mamutodlak\n";
        echo ($jed2)." x Sněhobílý Mamutodlak\n";
        echo ($meduza)." x Medůza\n";
    } else {
        echo (708)." x Vlkodlak\n";
        echo (708)." x Sněhobílý Vlkodlak\n";
        echo (708)." x Kancodlak\n";
        echo (708)." x Sněhobílý Kancodlak\n";
        echo (708)." x Medvědodlak\n";
        echo (708)." x Sněhobílý Mědvědodlak\n";
        echo (71)." x Mamutodlak\n";
        echo (71)." x Sněhobílý Mamutodlak\n";
        echo (3)." x Medůza\n";
    }
}
//Demoni karavana
elseif ($zam == 5) {
    echo (ceil($plan / 7837))." x Plamenný démon\n";
    echo (ceil($plan / 8807))." x Plamenný démon s bičem\n";
    echo (ceil($plan / 9928))." x Plamenný démon mág\n";
    echo (ceil($plan / 92))." x Následovník démonů - Vyšší Zasvěcenec\n";
    echo (ceil($plan / 403))." x Následovník démonů - Kacíř\n";
}
//Obetiste obsazene demony
else if ($zam == 6){
    echo (ceil($plan / 167))." x Následovník démonů - Kacíř\n";
    echo (ceil($plan / 19146))." x Plamenný démon mág\n";
    echo (ceil($plan / 18495))." x Plamenný démon mág\n";
    echo (ceil($plan / 438859))." x Plamenný démon arcimág\n";
    echo (ceil($plan / 644519))." x Démon Bojový Mág Temnoty\n";
}
//Trosky neznameho chramu
elseif ($zam == 7) {
    echo (ceil($plan / 267876))." x Medůza\n";
    echo (ceil($plan / 121))." x Kamenná socha\n";
}
//Proklety chram
elseif ($zam == 8) {
    echo (ceil($plan / 9990))." x Ohnivý Služebník\n";

    echo (ceil($plan / 1513595))." x Nižší ohnivý splozenec\n";
}

?>
