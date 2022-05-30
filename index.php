<?php
ini_set("display_errors", 1); //show PHP errors on the web if they happen

$utocnik="1 x Agranat, řečený zloděj duší(Prokletá kouzelnická róba)
3077 x Zombie(Gladius)
3,077 x Zombie (Gladius)
331 x Bažinný ent
157 x Kostlivec
21 x Stín";
$obrance="1 x Dehinator (Ohnivá zbroj)
12 x Těžký katapult
12 x Těžká balista
40 x Zeď Temné pevnosti
300 x Prokletý Mág
750 x Prokletý válečník(Prapor stínů)
6 x Dehinatorova ochranka";
?>

<!DOCTYPE html>
<html>
<head>
<META http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<meta http-equiv='Content-Style-Type' content='text/css'>
</head>

<body bgcolor='#000000' text='#f5f5f5' link='#b0c4de' vlink='#b0c4de' alink='#b0c4de' topmargin='0'>
<script type='text/javascript' src='ajax.js'></script>
<script type='text/javascript' src='wz_tooltip.js'></script>

<div style='font-family: verdana;font-size: 12px;margin-left: 30px; padding-top: 20px'>
<style type=text/css>
table {color: black; font-size: 11px; font-weight: 900;}
table td {padding: 1px; text-align: center;Vertical-align:top;color:black}
.boj {background:white;color:black;border:2px silver solid;font-size:15px;font-weight:bold}
.boj:hover {cursor:hand;background:#ddd; color:#333}
#eventy input{border:none; background:none; font-weight:bold; color:white; text-decoration:none; font-size:10px}
#eventy input:hover{color:grey; text-decoration:underline; cursor:pointer}
#eventy {margin:auto;width:1100px}
#eventy td {color:white}
.selectionButtonActive {height:15px;width:95%;background:gray;margin:auto;border:2px gray inset}
.selectionButtonActive:hover {cursor:hand}
.selectionButtonInactive {height:15px;width:95%;background:white;margin:auto;border:2px gray outset}
.selectionButtonInactive:hover {cursor:hand}
table#form {color: white}
table#form id {padding: 4px; color: white}
.pleneni.modre {color: deepskyblue!important;}
.pleneni.zelene {color: forestgreen!important;}
.pleneni.cervene {color: darkred!important;}
.chramy.goblini {color: #598959!important;}
.chramy.led {color: #CCFFFF!important;}
.chramy.zivot {color: #27AD27!important;}
.chramy.ohen {color: #FFFF00!important;}
.chramy.aether {color: #CCFFFF!important;}
.chramy.zeme {color: #1D721D!important;}
.promenneDleSilyArmady {color: red!important;}
</style>

<div style='width:750px;margin:auto'>
<table>
<tr><td><div id='selectionButon1' class='selectionButtonInactive' onClick='selectButton(1)'></div></td><td><div class="selectionButtonActive" onClick='selectButton(2)' id='selectionButon2'>⬇</div></td></tr>
<tr>
<td><textarea name='ut' id='ut' rows=10 cols=50><?php echo $utocnik ?></textarea></td>
<td><textarea name='ob' id='ob' rows=10 cols=50><?php echo $obrance ?></textarea></td>
</tr>
</table>
</div>
<br>
<center><input type='submit' onClick="ajaxFunction()" value="BOJ!" class='boj'></center><br>
<center>
    <input type='submit' onClick="window.open('jednotky.php', '_blank');" value="Seznam jednotek" class='boj'>
    <input type='submit' onClick="window.open('pleneni.php', '_blank');" value="Výpis artefaktů u plenění" class='boj'>
</center><br>
<center><input type='submit' onClick="zobrazit()" value="Zobrazit/Skrýt Eventy" class='boj'></center>
<br>
<table id='eventy' style='display:none'>
<tr>
    <td>
        <b>Válka</b><br><br>
        <input style='color:gold' value='Město' type=button onclick='pole("2096 x Ozbrojený vesničan\n26 x Kovář\n13721 x Vesničan\n371 x Městská pěchota\n150 x Městští kušníci\n1 x Těžká balista\n1 x Místodržící(Měděné platy)")'><br>
        <input value='Svatyně eternanů' type=button onclick='pole("50 x Eternan vyvolávač\n11 x Eternan mág\n1 x Eternan dohlížitel")'><br>
        <input value='Chrám Etermanů' type=button onclick='pole("80 x Eternan vyvolávač\n33 x Eternan mág\n2 x Eternan dohlížitel")'><br><br>

        <b>Tažení</b><br><br>
        <input value='Laboratoř 01' type=button onclick='pole("100000 x Otrok s kopím\n100000 x Otrok štítonoš\n1000 x Ohnivý démon\n1000 x Ohnivý démon")'><br>
        <input value='Laboratoř 02' type=button onclick='pole("200 x Plamenný démon mág\n10 x Plamenný démon arcimág\n200 x Plamenný démon mág\n10 x Plamenný démon arcimág\n200 x Plamenný démon mág\n10 x Plamenný démon arcimág")'><br>
        <input value='Laboratoř 03' type=button onclick='pole("200 x Toxický Elementál Obránce\n12 x Toxická Elementární Bublina\n2000 x Hnijící vesničan\n10 x Hnijící Nemrtvý Obr\n20 x Obří kostlivec\n1000 x Toxické oživlé bahno")'><br>
        <input value='Laboratoř 04' type=button onclick='pole("10 x Zmutovaný Wurm\n10000 x Následovník démonů - Vyšší Zasvěcenec\n10000 x Následovník démonů - Vyšší Zasvěcenec\n20000 x Následovník démonů - Zasvěcenec\n20000 x Následovník démonů - Zasvěcenec\n1 x Agranat, řečený zloděj duší(Prokletá kouzelnická róba)")'><br>
        <input value='Město vlků 01' type=button onclick='pole("10,000 x Černý vlk\n10,000 x Šedý vlk\n10,000 x Stínový lovec\n100 x Padlý válečník")'><br>
        <input value='Město vlků 02' type=button onclick='pole("20,000 x Černý vlk\n20,000 x Šedý vlk\n15,000 x Stínový lovec\n10,000 x Vlkodlak\n8,000 x Sněhobílý Vlkodlak")'><br>
        <input value='Město vlků 03' type=button onclick='pole("1,000 x Černý vlk\n1,000 x Šedý vlk\n500 x Stínový lovec\n200 x Vlkodlak\n200 x Sněhobílý Vlkodlak\n20 x Kamenná hradba")'><br>
        <input value='Město vlků 04' type=button onclick='pole("20,000 x Černý vlk\n20,000 x Šedý vlk\n15,000 x Stínový lovec\n10,000 x Vlkodlak\n8,000 x Sněhobílý Vlkodlak")'><br>
        <input value='Město vlků 05' type=button onclick='pole("10,000 x Černý vlk\n10,000 x Šedý vlk\n10,000 x Stínový lovec\n2,000 x Vlkodlak\n4,000 x Sněhobílý Vlkodlak\n500 x Stín\n20 x Temný přízrak\n2,000 x Prokletý válečník (Prapor stínů)\n2,000 x Padlý válečník")'><br>
        <input value='Město vlků 06' type=button onclick='pole("12,500 x Černý vlk\n12,500 x Šedý vlk\n12,500 x Stínový lovec\n1 x Král vlk\n12,500 x Duch vlka\n3,000 x Vlkodlak\n3,000 x Sněhobílý Vlkodlak\n500 x Stín\n40 x Temný přízrak\n2,500 x Prokletý válečník (Prapor stínů)\n2,500 x Padlý válečník")'><br>
        <input value='Království orla 1' type=button onclick='pole("15,000 x Ozbrojený vesničan\n25,000 x Otrok s kopím\n25,000 x Otrok s oštěpy\n25,000 x Otrok štítonoš\n5,000 x Pěšák starého impéria\n5 x Kapitán pěšáků starého impéria\n2,000 x Střelec starého impéria\n2 x Kapitán střelců starého impéria\n1 x Plukovník pěšaků starého impéria")'><br>
        <input value='Království orla 2' type=button onclick='pole("10,000 x Pěšák starého impéria\n10 x Kapitán pěšáků starého impéria\n20 x Kamenná hradba\n5,000 x Střelec starého impéria\n5 x Kapitán střelců starého impéria\n500 x Katapult\n10,000 x Městská pěchota\n10,000 x Městští kušníci")'><br>
        <input value='Království orla 3' type=button onclick='pole("10,000 x Pěšák starého impéria\n10 x Kapitán pěšáků starého impéria\n20 x Kamenná hradba\n5,000 x Střelec starého impéria\n5 x Kapitán střelců starého impéria\n500 x Balista\n10,000 x Městská pěchota\n10,000 x Městští kušníci")'><br>
        <input value='Království orla 4' type=button onclick='pole("15,000 x Těžký pěšák starého impéria\n15 x Kapitán těžkých pěšáků starého impéria\n10,000 x Elitní střelec starého impéria\n10 x Kapitán elitních střelců starého impéria\n1 x Generál starého impéria (Gladius)\n15 x Kamenná hradba\n4 x Kamenná věž\n200 x Těžký katapult\n200 x Těžká balista")'><br>
        <input value='Království orla 5' type=button onclick='pole("20,000 x Ozbrojený vesničan\n500 x Kovář\n50,000 x Vesničan\n15,000 x Otrok s kopím\n15,000 x Otrok s oštěpy\n15,000 x Otrok štítonoš\n7,000 x Městská pěchota\n6,000 x Městští kušníci\n5,000 x Pěšák starého impéria")'><br>
        <input value='Království orla 6' type=button onclick='pole("20,000 x Těžký pěšák starého impéria\n20 x Kapitán těžkých pěšáků starého impéria\n1 x Plukovník těžkých pěšaků starého impéria (Gladius)\n15,000 x Střelec starého impéria\n15 x Kapitán střelců starého impéria\n7,000 x Elitní střelec starého impéria\n7 x Kapitán elitních střelců starého impéria\n20,000 x Pěšák starého impéria\n20 x Kapitán pěšáků starého impéria\n100 x Therysusova elitní stráž (Drtič lebek)\n1 x Král Therysus (Katana z Temné ocele)")'><br>
    </td>

    <td>
        <b>Dobývání</b><br><br>
        <input value='Trpasličí hospoda' type=button onclick='pole("7 x Trpaslík se sekerou\n2 x Trpaslík s kuší\n5 x Trpasličí legionář\n1 x Trpaslík kapitán")'><br>
        <input value='Trpasličí pevnost' type=button onclick='pole("41 x Těžký katapult\n50 x Těžká balista\n605 x Trpaslík se sekerou\n504 x Trpaslík s kuší\n2 x Trpaslík kapitán\n216 x Trpaslík těžké pěchoty\n984 x Trpasličí legionář")'><br>
        <input value='Trpasličí pokladnice' type=button onclick='pole("119 x Těžký katapult\n113 x Těžká balista\n10798 x Trpaslík se sekerou\n4148 x Trpaslík s kuší\n53 x Trpaslík kapitán\n6191 x Trpaslík těžké pěchoty\n6966 x Trpasličí legionář")'><br>
        <input value='Chrám ohně' type=button onclick='pole("5000 x Mág ohně\n500 x Arcimág ohně\n200 x Ohnivý Fénix\n10 x Flamekeeper\n500 x Ohnivý démon\n250 x Efreet\n250 x Efreet\n20 x Meteorit\n2500 x Ohnivá koule\n")'><br>
        <input value='Tábor otrokářů' type=button onclick='pole("1 x Místodržící\n1000 x Otrok s kopím\n500 x Otrok s oštěpy\n800 x Otrok štítonoš")'><br>
        <input value='Obchodní stezky 1' type=button onclick='pole("349 x Barbar\n1 x Náčelník barbarů\n56 x Barbar na koni\n142 x Barbar lučištník")'><br>
        <input value='Obchodní stezky 2' type=button onclick='pole("1 x Ledový obr\n1 x Lesní obr\n1 x Obr lidožrout")'><br>
        <input value='Obchodní stezky 3' type=button onclick='pole("1 x Wurm")'><br>
        <input value='Neznámá indicie 1' type=button onclick='pole("100 x Otrok s kopím\n50 x Otrok s oštěpy\n500 x Otrok štítonoš\n1 x Paladin\n1 x Místodržící\n1 x Kovář\n250 x Efreet\n3 x Vyznavač boha ledu\n2 x Mág ohně")'><br>
        <input value='Neznámá indicie 2' type=button onclick='pole("100 x Elfí lučištník\n5 x Druid\n1 x Lesní obr")'><br>
        <input value='Neznámá indicie 3' type=button onclick='pole("2000 x Elfí lučištník\n500 x Druid\n50 x Lesní obr\n10 x Wurm\n5 x Lesní obr jezdící na Posvátném Wurmovi\n3000 x Ent\n1000 x Starodávný Ent\n750 x Kamenný Golem\n50 x Kamenožrout\n10 x Mág země")'><br>
        <input value='Šedé hory 1' type=button onclick='pole("2 x Nižší ohnivý splozenec")'><br>
        <input value='Šedé hory 2' type=button onclick='pole("2 x Nižší ohnivý splozenec\n10 x Kamenný Obr")'><br>
        <input value='Jeskyně života 1' type=button onclick='pole("1 x wurm\n100 x Elfí hraničář")'><br>
        <input value='Jeskyně života 2' type=button onclick='pole("7,000 x Druid\n2,500 x Veledruid\n1,000 x Obří černý pavouk\n25 x Horský obr\n5 x Prastarý Horský Wurm")'><br>
        <input value='Krystal moci - město' type=button onclick='pole("1 x Kamenná hradba\n16 x Katapult\n20 x Balista\n15,332 x Vesničan\n3,250 x Městská pěchota\n2,935 x Městští kušníci\n1,305 x Temný Elf\n1 x Velitel Temných Elfů")'><br>
    </td>

    <td>
        <b>Plenění</b><br><br>
        <input value='Spolek mocných' class='pleneni modre' type=button onclick='pleneni(2)'><br>
        <input value='Gnomí velkodílna' class='pleneni modre' type=button onclick='pleneni(1)'><br>
        <input value='Temná jeskyně' class='pleneni modre' type=button onclick='pleneni(4)'><br>
        <input value='Katakomby' class='pleneni modre' type=button onclick='pleneni(3)'><br>
        <input value='Svobodné město' class='pleneni zelene' type=button onclick='pole("1 x Kamenná hradba\n17 x Katapult\n5 x Těžký katapult\n20 x Balista\n7 x Těžká balista\n2745 x Městští kušníci\n197 x Osadník s lukem\n17 x Potulný Kouzelník\n11 x Mistr Lovec")'><br>
        <input value='Obři zla' class='pleneni zelene' type=button onclick='pole("50 x Obr lidožrout\n20 x Prokletý Obr\n30 x Hnijící Nemrtvý Obr\n20 x Ohnivý Obr")'><br>
        <input value='Obři dobra' class='pleneni zelene' type=button onclick='pole("50 x Lesní obr\n80 x Ledový obr\n12 x Kamenný Obr")'><br>
        <input value='Země zatracených 1' class='pleneni zelene' type=button onclick='pole("30000 x Zombie\n1000 x Lich\n30 x Temný přízrak\n500 x Padlý válečník")'><br>
        <input value='Země zatracených 2' class='pleneni zelene' type=button onclick='pole("2000 x Stín\n12000 x Kostlivec\n10000 x Uvězněná duše\n30 x Temný přízrak\n500 x Padlý válečník")'><br>
        <input value='Temný les' class='pleneni zelene' type=button onclick='pole("200 x Prokletý Obr")'><br>
        <input value='Otrokářská kolonie 1' class='pleneni zelene' type=button onclick='pole("100 x Otrokář\n10000 x Otrok s kopím\n10000 x Otrok s oštěpy\n10000 x Otrok štítonoš")'><br>
        <input value='Otrokářská kolonie 2' class='pleneni zelene' type=button onclick='pole("100 x Otrokář\n50000 x Otrok s kopím")'><br>
        <input value='Ledový palác' class='pleneni zelene' type=button onclick='pole("1 x Ledový král\n750 x Ledový Přízrak\n750 x Starodávný Ledový Přízrak\n1000 x Ledový Elementál\n100 x Ledový obr")'><br>
        <input value='Dralgarova zahrada 1' class='pleneni zelene' type=button onclick='pole("2000 x Obří zelený pavouk\n2000 x Červený lesní pavouk\n2000 x Černý lesní pavouk\n400 x Zlatý lesní pavouk\n1000 x Lesní Troll\n")'><br>
        <input value='Dralgarova zahrada 2' class='pleneni zelene' type=button onclick='pole("2500 x Druid\n50 x Lesní obr\n5 x Wurm\n1250 x Ent\n1000 x Starodávný Ent")'><br>
        <input value='Trpaslíci 1 - Hlídka u úpatí hor' class='pleneni zelene' type=button onclick='pole("370 x Železný kněz\n8,290 x Trpaslík\n659 x Železný golem\n16,080 x Železný pavouček\n728 x Železný škopion")'><br>
        <input value='Trpaslíci 2 - Strážní věž' class='pleneni zelene' type=button onclick='pole("684 x Železný kněz\n15,032 x Trpaslík\n1,122 x Železný golem\n29,662 x Železný pavouček\n1,285 x Železný škopion\n418 x Železný býk")'><br>
        <input value='Trpaslíci 3 - Citedela' class='pleneni zelene' type=button onclick='pole("1,166 x Železný kněz\n24,366 x Trpaslík\n1,513 x Kamenný chrlič\n562 x Poloautomatická socha\n145 x Železný orel - MK1\n152 x Obrněnej vrhač oštěpů\n132 x Obrněnej válečnej vůz")'><br>
        <input value='Trpaslíci 4 - Železný chrám' class='pleneni zelene' type=button onclick='pole("2,003 x Železný kněz\n45,297 x Trpaslík\n3,992 x Železný škopion\n1,305 x Železný býk\n2,779 x Kamenný chrlič\n921 x Poloautomatická socha\n259 x Železný orel - MK1\n5 x Colosus z temné ocele")'><br>
        <!--Posvatny chram je specialni jednorazovy event pouze pro Dreadda, aby si odemcel Nekromancery-->
        <input style='color:gold' value='Posvátný chrám' type=button onclick='pole("10000 x Svatý válečník\n1500 x Elitní střelec\n1000 x Mnich\n500 x Křižák\n250 x Paladin\n1 x Světlonoš\n25 x Balista\n30 x Těžká balista\n1000 x Renegád")'><br>
        <input value='Prokletý vojevůdce' class='pleneni cervene' type=button onclick='pole("1 x Dehinator (Ohnivá zbroj)\n12 x Těžký katapult\n12 x Těžká balista\n40 x Zeď Temné pevnosti\n300 x Prokletý Mág\n750 x Prokletý válečník(Prapor stínů)\n6 x Dehinatorova ochranka")'><br>
        <input value='Prokletá citadela' class='pleneni cervene' type=button onclick='pole("1 x Královna Medůz(Maska Královny medůz)\n1000 x Temný mág(Kouzelnická róba)\n200 x Vyšší Temný Mág(Prokletá kouzelnická róba)\n32 x Zeď Prokleté citadely\n200 x Prokletý Mág\n1000 x Prokletý válečník(Prapor stínů)\n3000 x Æthrův ledový válečník")'><br>
        <input value='Mucusova pevnost' class='pleneni cervene' type=button onclick='pole("1 x Mucus, král Toxických elementálů(Plášť Mucuse, krále toxických elementálů)\n1500 x Zeď pokrytá slizem\n1000 x Toxický Elementál Obránce\n1000 x Toxický Elementál Útočník\n10 x Toxická Elementární Bublina")'><br>
    </td>


    <td>
        <b>Eventy - začátky</b><br><br>
        <input value='Vesnice' type=button onclick='pole("1 x Ozbrojený vesničan\n10 x Vesničan")'><br>
        <input value='Osada' type=button onclick='pole("95 x Ozbrojený vesničan\n43 x Osadník s lukem\n2 x Katapult\n1 x Velitel osady")'><br>
        <input value='Skupinka barbarů' type=button onclick='pole("17 x Barbar\n1 x Náčelník barbarů")'><br>
        <input value='Tábor barbarských nájezdníků' type=button onclick='pole("46 x Barbar\n1 x Náčelník barbarů\n6 x Barbar na koni\n13 x Barbar lučištník")'><br>
        <input value='Kmen barbarů' type=button onclick='pole("247 x Barbar\n1 x Náčelník barbarů\n46 x Barbar na koni\n66 x Barbar lučištník")'><br>
        <input value='Velký hřbitov' type=button onclick='pole("1095 x Kostlivec\n180 x Zombie\n17 x Lich")'><br>
        <input value='Skrýš banditů' type=button onclick='pole("948 x Bandita\n538 x Bandita s lukem\n1 x Bandita velitel")'><br>
        <input value='Gnómská dílna' type=button onclick='pole("8 x Katapult\n2 x Těžký katapult\n8 x Balista\n2 x Těžká balista\n485 x Gnóm s kuší\n1144 x Otrok štítonoš")'><br><br>

        <b>Eventy - trpaslíci</b><br><br>
        <input value='Bronzový důl' type=button onclick='pole("1,316 x Trpaslík se sekerou\n816 x Trpaslík s kuší\n455 x Trpaslík těžké pěchoty\n3,209 x Trpaslík\n39 x Trpasličí past")'><br>
        <input value='Železný důl' type=button onclick='pole("3,857 x Trpaslík se sekerou\n2,370 x Trpaslík s kuší\n9 x Trpaslík kapitán\n1,363 x Trpaslík těžké pěchoty\n5,063 x Trpaslík\n396 x Trpasličí past")'><br>
        <input value='Trpasličí karavana' type=button onclick='pole("1,045 x Trpaslík\n364 x Železný kněz")'><br>
        <input value='Trpasličí patrola' type=button onclick='pole("816 x Trpaslík se sekerou\n709 x Trpaslík s kuší\n29 x Trpaslík kapitán\n396 x Trpaslík těžké pěchoty\n397 x Trpasličí legionář\n207 x Elitní trpaslík s kuší")'><br>
        <input value='Trpasličí kovárna' type=button onclick='pole("3,013 x Trpaslík se sekerou\n3,199 x Trpaslík s kuší\n99 x Trpaslík kapitán\n2,623 x Trpaslík těžké pěchoty\n1,253 x Trpasličí legionář\n20,064 x Trpaslík\n125 x Balista\n51 x Těžká balista")'><br>
        <input value='Trpasličí skladiště' type=button onclick='pole("5,450 x Trpaslík se sekerou\n3,394 x Trpaslík s kuší\n489 x Trpaslík kapitán\n5,913 x Trpaslík těžké pěchoty (Ocelové pláty)\n2,701 x Trpasličí legionář (Železné pláty)\n8,123 x Trpaslík\n990 x Elitní trpaslík s kuší (Jedovaté střely)\n204 x Trpasličí opevnění\n200 x Těžký katapult\n210 x Těžká balista")'><br>
    </td>

    <td>
        <b>Eventy</b><br><br>
        <input value='Armáda eternanů' type=button onclick='pole("250 x Eternan vyvolávač\n250 x Eternan mág\n250 x Eternan dohlížitel\n10000 x Energetický služebník\n10000 x Energetický služebník")'><br>
        <input value='Bažina' type=button onclick='pole("475 x Oživlé bahno\n10 x Bažinný ent\n28 x Zombie")'><br>
        <input value='Zakletá bažina' type=button onclick='pole("999 x Oživlé bahno\n193 x Bažinný ent\n61 x Kostlivec\n1974 x Zombie\n1 x Temný přízrak\n5 x Stín")'><br>
        <input value='Neklidná Bažina' type=button onclick='pole("1079 x Oživlé bahno\n179 x Bažinný ent\n35 x Kostlivec\n1043 x Zombie")'><br>
        <input value='Bažina utrpení' type=button onclick='pole("1 x Agranat, řečený zloděj duší(Prokletá kouzelnická róba)\n331 x Bažinný ent\n157 x Kostlivec\n3077 x Zombie\n21 x Stín\n11216 x Uvězněná duše")'><br>
        <input value='Posvátný klášter' type=button onclick='pole("5 x Paladin\n10 x Exorcista\n100 x Odpadlík \n20 x Renegád")'><br>
        <input value='Ledové jezero' type=button onclick='pole("100 x Ledový Elementál\n1 x Mág ledu")'><br>
        <input value='Lesík' type=button onclick='pole("100 x Druid\n10 x Ent\n10 x Starodávný Ent")'><br>
        <input value='Malý hvozd' type=button onclick='pole("125 x Druid\n20 x Ent\n10 x Starodávný Ent")'><br>
        <input value='Hnízdo pavouků' type=button onclick='pole("1,000 x Černý lesní pavouk\n1,000 x Červený lesní pavouk\n1,000 x Zelený lesní pavouk")'><br>
        <input value='Opuštěná věž 1' type=button onclick='pole("1 x Flamekeeper\n14 x Ohnivý golem\n4 x Efreet")'><br>
        <input value='Opuštění starý důl 1' type=button onclick='pole("316 x Uvězněná duše")'><br>
        <input value='Opuštění starý důl 2' type=button onclick='pole("73 x Stín")'><br>
        <input value='Ledová kobka' type=button onclick='pole("1 x Nekromant\n47 x Lich\n22 x Nemrtvý ledový kouzelník")'><br>
        <input value='Prokleté údolí' type=button onclick='pole("919 x Vlkodlak\n486 x Sněhobílý Vlkodlak\n1044 x Kancodlak\n461 x Sněhobílý Kancodlak\n959 x Medvědodlak\n533 x Sněhobílý Mědvědodlak\n359 x Mamutodlak\n96 x Sněhobílý Mamutodlak")'><br>
        <input value='Stonehenge' type=button onclick='pole("20 x Druid\n5 x Ent\n5 x Starodávný Ent")'><br>
        <input value='Gobliní opevněná hospoda' type=button onclick='pole("14 x Goblin\n44 x Goblin Fanatik\n25 x Goblin Pyroman\n1 x Gobliní pojízdná bomba")'><br>
        <input value='Svobodná gobliní dílna' type=button onclick='pole("50 x Goblin Fanatik\n5 x Gobliní pojízdná bomba\n25 x Goblin Pyroman\n1 x Gobliní Patriarcha\n10 x Rozžhavené magma\n1 x Nepoužitelný golem")'><br>
        <input value='Staré stoky 1' type=button onclick='pole("4651 x Zombie\n180 x Lich\n2895 x Kostlivec")'><br>
        <input value='Staré stoky 2' type=button onclick='pole("250 x Stín\n10 x Temný přízrak\n1000 x Uvězněná duše")'><br>
        <input value='Opevněné město' type=button onclick='pole("1 x Kamenná hradba\n17 x Katapult\n10 x Těžký katapult\n25 x Balista\n9 x Těžká balista\n2649 x Městští kušníci\n199 x Osadník s lukem\n722 x Gnóm s kuší")'><br>
        <input value='Malý kmen obrů lidožroutů' type=button onclick='pole("12 x Obr lidožrout")'><br>
        <input value='Kmen obrů lidožroutů' type=button onclick='pole("38 x Obr lidožrout")'><br>
        <input value='Trosky neznámého chrámu' class='promenneDleSilyArmady' type=button onclick='silaarmady(7)'><br>
        <input value='Prokletý chrám ohně' class='promenneDleSilyArmady' type=button onclick='silaarmady(8)'><br><br>
    </td>


    <td>
        <b>Eventy - chrámy</b><br><br>
        <input value='Chrám gobliní sekty' class='chramy goblini' type=button onclick='pole("746 x Goblin\n120 x Goblin s Prakem\n25 x Goblin Fanatik\n2 x Gobliní pojízdná bomba\n23 x Goblin Pyroman")'><br>

        <input value='Chrám ledu' class='chramy led' type=button onclick='pole("105 x Vyznavač boha ledu\n12 x Zasvěcenec boha ledu\n9 x Ledový Přízrak")'><br>
        <input value='Svatyně ledu' class='chramy led' type=button onclick='pole("1,191 x Vyznavač boha ledu\n117 x Zasvěcenec boha ledu\n114 x Ledový Přízrak\n10 x Ledový obr")'><br>
        <input value='Dóm ledu' class='chramy led' type=button onclick='pole("2,044 x Zasvěcenec boha ledu\n525 x Ledový Přízrak\n488 x Ledový Elementál\n79 x Ledový obr\n60 x Sněžný obr")'><br>
        <input value='Velechrám ledu' class='chramy led' type=button onclick='pole("4,554 x Zasvěcenec boha ledu\n997 x Ledový Přízrak\n856 x Ledový Elementál\n162 x Starodávný ledový obr\n80 x Sněžný obr")'><br>
        <input value='Posvátný chrám ledu' class='chramy led' type=button onclick='pole("9,121 x Zasvěcenec boha ledu\n1,690 x Starodávný Ledový Přízrak\n2,299 x Starodávny Ledový Elementál\n480 x Starodávný ledový obr\n169 x Snežný obr")'><br>
        <input value='Posvátný dóm ledu' class='chramy led' type=button onclick='pole("28,011 x Mistr boha ledu\n4,934 x Starodávný Ledový Přízrak\n4,706 x Starodávny Ledový Elementál\n999 x Starodávný ledový obr\n1 x Velemág Ledu\n984 x Snežný obr")'><br>
        <input value='Posvátný velechrám ledu' class='chramy led' type=button onclick='pole("48,090 x Ledová koule\n96 x Arcimág ledu\n94,294 x Mistr boha ledu\n14,123 x Starodávny Ledový Elementál\n9,028 x Starodávný Ledový Přízrak\n1 x Velemág Ledu\n2,962 x Starodávný ledový obr\n1,654 x Snežný obr")'><br>

        <input value='Chrám života' class='chramy zivot' type=button onclick='pole("63 x Ent\n89 x Druid\n1 x Lesní obr")'><br>
        <input value='Svatyně života' class='chramy zivot' type=button onclick='pole("997 x Druid\n11 x Lesní obr\n405 x Lesní Troll\n723 x Ent")'><br>
        <input value='Dóm života' class='chramy zivot' type=button onclick='pole("5,855 x Veledruid\n35 x Lesní obr\n1,328 x Prastarý Lesní Troll\n2,583 x Starodávný Ent")'><br>
        <input value='Velechrám života' class='chramy zivot' type=button onclick='pole("18,422 x Veledruid\n95 x Horský obr\n3,475 x Prastarý Lesní Troll\n93 x Jednorožec")'><br>
        <input value='Posvátný chrám života' class='chramy zivot' type=button onclick='pole("47,430 x Veledruid\n5,922 x Obří zelený pavouk\n187 x Horský obr\n170 x Jednorožec\n1 x Bílý pavouk")'><br>
        <input value='Posvátný dóm života' class='chramy zivot' type=button onclick='pole("120,521 x Veledruid\n14,080 x Obří červený pavouk\n8,340 x Lesní Bizon\n686 x Horský obr\n524 x Jednorožec\n1 x Posvátný jednorožec\n1 x Bílý pavouk")'><br>
        <input value='Posvátný velechrám života' class='chramy zivot' type=button onclick='pole("489,020 x Veledruid\n2,866 x Zlatý lesní pavouk\n24,081 x Obří červený pavouk\n14,648 x Lesní Bizon\n1,393 x Prastarý Horský obr\n1,027 x Jednorožec\n1 x Posvátný jednorožec\n1 x Bílý pavouk")'><br>

        <input value='Chrám ohně' class='chramy ohen' type=button onclick='pole("573 x Mág ohně\n55 x Ohnivý golem\n10 x Ohnivý Fénix\n1 x Flamekeeper\n")'><br>
        <input value='Svatyně ohně' class='chramy ohen' type=button onclick='pole("4,866 x Mág ohně\n175 x Magmatický golem\n118 x Ohnivý Fénix\n10 x Flamekeeper\n")'><br>
        <input value='Dóm ohně' class='chramy ohen' type=button onclick='pole("16,653 x Mág ohně\n993 x Magmatický golem\n338 x Ohnivý Fénix\n28 x Flamekeeper\n1 x Magmatický Obr")'><br>
        <input value='Velechrám ohně' class='chramy ohen' type=button onclick='pole("17,243 x Arcimág ohně\n1,793 x Magmatický golem\n466 x Ohnivý Fénix\n55 x Flamekeeper\n5 x Magmatický Obr")'><br>
        <input value='Posvátný chrám ohně' class='chramy ohen' type=button onclick='pole("32,066 x Arcimág ohně\n4,911 x Magmatický golem\n2,379 x Ohnivý Fénix\n98 x Flamekeeper\n12 x Magmatický Obr")'><br>
        <input value='Posvátný dóm ohně' class='chramy ohen' type=button onclick='pole("138,203 x Arcimág ohně\n11,110 x Magmatický golem\n7,656 x Ohnivý Fénix\n2,946 x Anděl věčného ohně\n250 x Flamekeeper\n1 x Flamekeeper Lord\n27 x Magmatický Obr")'><br>

        <input value='Ætherův chrám' class='chramy aether' type=button onclick='pole("905 x Zasvěcenec boha ledu\n98 x Ledový Přízrak\n21 x Ledový obr\n3 x Ledová hydra")'><br>
        <input value='Ætherova svatyně' class='chramy aether' type=button onclick='pole("9,122 x Zasvěcenec boha ledu\n654 x Starodávný Ledový Přízrak\n164 x Ledový obr\n17 x Ledová hydra")'><br>
        <input value='Ætherův dóm' class='chramy aether' type=button onclick='pole("17,429 x Zasvěcenec boha ledu\n3,863 x Ledový válečník\n243 x Ledový obr\n2,447 x Šaman Ledu\n26 x Ledová hydra")'><br>
        <input value='Ætherův velechrám' class='chramy aether' type=button onclick='pole("9,145 x Sestra Ledu\n557 x Ledový obr\n5,295 x Šaman Ledu\n46 x Ledová hydra\n8,279 x Æthrův ledový válečník")'><br>
        <input value='Ætherův posvátný chrám' class='chramy aether' type=button onclick='pole("25,898 x Sestra Ledu\n902 x Ledový obr\n8,006 x Æthrův šaman Ledu\n70 x Ledová hydra\n88 x Æthrova ledová hvězda\n28,389 x Æthrův ledový válečník")'><br>
        <input value='Ætherův posvátný dóm' class='chramy aether' type=button onclick='pole("Az vam prijde, napiste nam jednotky a pridame je (kontakty jsou dole)")'><br>

        <input value='Chrám země' class='chramy zeme' type=button onclick='pole("1,199 x Elfí lučištník\n2 x Wurm\n972 x Ent\n563 x Starodávný Ent\n2 x Mág země")'><br>
        <input value='Svatyně země' class='chramy zeme' type=button onclick='pole("4,247 x Elfí lučištník\n7 x Wurm\n1,832 x Starodávný Ent\n50 x Kamenožrout\n5 x Mág země")'><br>
        <input value='Dóm země' class='chramy zeme' type=button onclick='pole("22,629 x Elfí elitní lučištník\n17 x Wurm\n8 x Horský Wurm\n2,981 x Kamenný Golem\n138 x Kamenožrout\n16 x Mág země")'><br>
        <input value='Velechrám země' class='chramy zeme' type=button onclick='pole("42 x Horský Wurm\n24 x Prastarý Horský Wurm\n45,044 x Elfí hraničář\n8,462 x Mramorový golem\n333 x Mramorožrout\n44 x Mág země")'><br>
        <input value='Posvátný chrám země' class='chramy zeme' type=button onclick='pole("48 x Velemág země\n84,050 x Elfí hraničář\n75 x Prastarý Horský Wurm\n85 x Horský Wurm\n685 x Onyxožrout\n85 x Mág země\n14,888 x Onyxový Golem")'><br>
        <input value='Posvátný dóm země' class='chramy zeme' type=button onclick='pole("Az vam prijde, napiste nam jednotky a pridame je (kontakty jsou dole)")'><br><br>
    </td>


    <td>
        <b>Démoní eventy</b><br><br>
        <input value='Démon' type=button onclick='pole("1 x Plamenný démon s bičem")'><br>
        <input value='Skupinka démonů 1' type=button onclick='pole("10 x Plamenný démon\n2 x Plamenný démon mág")'><br>
        <input value='Skupinka démonů 2' type=button onclick='pole("10 x Ohnivý démon\n1,485 x Ohnivý imp")'><br>
        <input value='Předsunutá hlídka armády démonů' type=button onclick='pole("20 x Plamenný démon\n10 x Plamenný démon s bičem\n5 x Plamenný démon mág")'><br>
        <input value='Ozbrojená skupinka démonů' type=button onclick='pole("20 x Plamenný démon\n20 x Plamenný démon s bičem\n5 x Plamenný démon mág")'><br>
        <input value='Elitní průzkumná jednotka armády démonů' type=button onclick='pole("56 x Démon Křižák\n64 x Démon Křižák\n44 x Démon Těžký Křižák\n36 x Démon Těžký Křižák")'><br>
        <input value='Hlídka zrádců' type=button onclick='pole("2,750 x Následovník démonů - Prokletý Berzeker\n6,440 x Následovník démonů - Zasvěcenec\n8,190 x Následovník démonů - Zasvěcenec\n1,785 x Následovník démonů - Kacíř\n8,470 x Následovník démonů - Zasvěcenec")'><br>
        <input style='color:orange' value='Armáda démonů' type=button onclick='pole("Armáda některého z démonů.\nPokud je postavená předsunutá hlídka, jméno démona se ukáže před útokem vedle tabulky. Nebo je možné poslat 1 vesničana na průzkum, jelikož armáda je speciální event a démon se napevno vybírá už při příchodu eventu (normálně jsou eventy generované až při útoku)")'><br>
        <input value='Démoní karavana' class='promenneDleSilyArmady' type=button onclick='silaarmady(5)'><br>
        <input value='Obětiště obsazené démony' class='promenneDleSilyArmady' type=button onclick='silaarmady(6)'><br><br>


        <b>Démoni</b><br><br>
        <input value='Dh&#039; Arok' type=button onclick='pole("2000 x Otrok s kopím\n2000 x Otrok s kopím\n2000 x Otrok s kopím\n10 x Těžký katapult\n50 x Ohnivý démon")'><br>
        <input value='Dh&#039; Ragh' type=button onclick='pole("100 x Plamenný démon s bičem\n100 x Plamenný démon s bičem\n1000 x Otrok s kopím\n1000 x Otrok s kopím\n1 x Plamenný démon arcimág")'><br>
        <input value='Dh&#039; Karn' type=button onclick='pole("70 x Plamenný démon\n50 x Plamenný démon s bičem\n20 x Plamenný démon mág\n1 x Plamenný démon arcimág\n1 x Plamenný démon arcimág")'><br>
        <input value='Dr&#039; Gor' type=button onclick='pole("200 x Ohnivý démon\n12000 x Následovník démonů - Lidský Odpadlík\n8000 x Následovník démonů - Zasvěcenec\n1000 x Následovník démonů - Vyšší Zasvěcenec\n2500 x Následovník démonů - Berzeker")'><br>
        <input value='Dr&#039; Ghor Re Thor' type=button onclick='pole("100 x Těžký katapult\n100 x Těžká balista\n20000 x Následovník démonů - Lidský Odpadlík\n10000 x Následovník démonů - Zasvěcenec\n5000 x Následovník démonů - Kacíř")'><br>
        <input value='Dr&#039; Thurrgen' type=button onclick='pole("1 x Temný Lord Argon(Amulet velitele)\n2000 x Následovník démonů - Zasvěcenec\n1000 x Následovník démonů - Vyšší Zasvěcenec\n2500 x Následovník démonů - Berzeker\n10000 x Následovník démonů - Kacíř")'><br>
        <input value='Dra&#039; Ghan Tru Tran' type=button onclick='pole("1 x Dh Atrog řečený Válečník(Popel padlích válečníků)\n300 x Plamenný démon\n250 x Plamenný démon s bičem\n200 x Katapult\n250 x Balista")'><br>
        <input value='Dra&#039; Ghottan' type=button onclick='pole("1 x Dh Ratu řečený Posel ohně\n1 x Dh Tog řečený Mág ohně(Ohnivá róba)\n500 x Plamenný démon s bičem\n120 x Plamenný démon mág\n10 x Plamenný démon arcimág")'><br>
        <input value='Tel&#039; Thullgeon' type=button onclick='pole("1 x Ragnarokk, prokletý wurm(Prapor starého druidského cechu)\n1000 x Plamenný démon\n200 x Plamenný démon mág\n10 x Plamenný démon arcimág\n12500 x Následovník démonů - Prokletý Berzeker")'><br>
        <input value='Rah&#039; Ghor Pravus' type=button onclick='pole("1 x Rah Atrog řečený Vyvolávač(Ohnivá zbroj)\n15 x Démon Bojový Mág Temnoty\n1000 x Démon Válečník Temnoty\n1500 x Démon Těžký Křižák\n2000 x Démon Křižák")'><br>
        <input value='Rah&#039; Cosmo Pravus' type=button onclick='pole("1 x Anděl z Temnoty\n25000 x Následovník démonů - Prokletý Berzeker\n1000 x Démon Křižák\n1000 x Démon Těžký Křižák\n1000 x Démon Válečník Temnoty")'><br>
        <input style='color:gold' value='Dr&#039; Talaziel' type=button onclick='pole("50 x Démon Mág Krvavého Měsíce\n300 x Démon Jezdec\n150 x Démon Těžký Jezdec\n3,000 x Stínový jezdec\n1,500 x Stínový Obrnený jezdec")'><br>
        <input style='color:gold' value='Dr&#039; Seharon' type=button onclick='pole("100 x Démon Mág Krvavého Měsíce\n2,500 x Stínový jezdec\n5,000 x Stínový válečník\n200 x Stínový obr\n10,000 x Stínový mág démonů")'><br>
        <input style='color:gold' value='Dra&#039; Astuer' type=button onclick='pole("100 x Démon Kněz Krvavého Měsíce\n800 x Démon Jezdec\n400 x Démon Těžký Jezdec\n800 x Plamenný démon\n300 x Plamenný démon mág")'><br>
        <input style='color:gold' value='Tel&#039; Osroxas' type=button onclick='pole("200 x Démon Kněz Krvavého Měsíce\n200 x Démon Mág Krvavého Měsíce\n1,000 x Démon Jezdec\n500 x Démon Těžký Jezdec\n500 x Stínový obr")'><br>


        <br>
        <input value='Pevnost démonů 01' type=button onclick='pole("10000 x Otrok s kopím\n10000 x Otrok s oštěpy\n10000 x Otrok štítonoš\n200 x Katapult\n200 x Těžká balista\n10 x Ohnivý imp")'><br>
        <input value='Pevnost démonů 05' type=button onclick='pole("10000 x Otrok s kopím\n10000 x Otrok s oštěpy\n10000 x Otrok štítonoš\n200 x Katapult\n200 x Těžká balista\n30 x Ohnivý imp\n2500 x Následovník démonů - Lidský Odpadlík\n1000 x Následovník démonů - Zasvěcenec\n500 x Následovník démonů - Vyšší Zasvěcenec\n750 x Následovník démonů - Berzeker\n300 x Následovník démonů - Kacíř\n10 x Následovník démonů - Prokletý Berzeker\n100 x Ohnivý démon\n75 x Plamenný démon\n50 x Plamenný démon s bičem\n40 x Plamenný démon mág\n5 x Plamenný démon arcimág\n100 x Démon Křižák\n75 x Démon Těžký Křižák\n50 x Démon Válečník Temnoty\n5 x Démon Bojový Mág Temnoty\n1 x Temný Lord Argon(Amulet velitele)\n1 x Dh Atrog řečený Válečník(Popel padlích válečníků)")'><br>
        <input value='Pevnost démonů 06' type=button onclick='pole("10000 x Otrok s kopím\n10000 x Otrok s oštěpy\n10000 x Otrok štítonoš\n200 x Katapult\n200 x Těžká balista\n35 x Ohnivý imp\n2500 x Následovník démonů - Lidský Odpadlík\n1000 x Následovník démonů - Zasvěcenec\n500 x Následovník démonů - Vyšší Zasvěcenec\n750 x Následovník démonů - Berzeker\n300 x Následovník démonů - Kacíř\n10 x Následovník démonů - Prokletý Berzeker\n100 x Ohnivý démon\n75 x Plamenný démon\n50 x Plamenný démon s bičem\n40 x Plamenný démon mág\n5 x Plamenný démon arcimág\n100 x Démon Křižák\n75 x Démon Těžký Křižák\n50 x Démon Válečník Temnoty\n5 x Démon Bojový Mág Temnoty\n1 x Temný Lord Argon(Amulet velitele)\n1 x Dh Atrog řečený Válečník(Popel padlích válečníků)\n1 x Dh Ratu řečený Posel ohně\n1 x Dh Tog řečený Mág ohně(Ohnivá róba)")'><br>
        <input value='Pevnost démonů 07' type=button onclick='pole("10000 x Otrok s kopím\n10000 x Otrok s oštěpy\n10000 x Otrok štítonoš\n200 x Katapult\n200 x Těžká balista\n40 x Ohnivý imp\n2500 x Následovník démonů - Lidský Odpadlík\n1000 x Následovník démonů - Zasvěcenec\n500 x Následovník démonů - Vyšší Zasvěcenec\n750 x Následovník démonů - Berzeker\n300 x Následovník démonů - Kacíř\n10 x Následovník démonů - Prokletý Berzeker\n100 x Ohnivý démon\n75 x Plamenný démon\n50 x Plamenný démon s bičem\n40 x Plamenný démon mág\n5 x Plamenný démon arcimág\n100 x Démon Křižák\n75 x Démon Těžký Křižák\n50 x Démon Válečník Temnoty\n5 x Démon Bojový Mág Temnoty\n1 x Temný Lord Argon(Amulet velitele)\n1 x Dh Atrog řečený Válečník(Popel padlích válečníků)\n1 x Dh Ratu řečený Posel ohně\n1 x Dh Tog řečený Mág ohně(Ohnivá róba)\n1 x Dh Atog řečený sběratel soch(Hůl silového pole)\n1 x Rah Atrog řečený Vyvolávač(Ohnivá zbroj)\n1 x Ragnarokk, prokletý wurm (Prapor starého druidského cechu)")'><br>
    </td>

    <td>
        <b style='color:gray'>Bonusy</b><br><br>
        <!--Old: <input value='Přidat otroky' type=button onclick='pridej("\n1000 x Otrok s kopím\n1000 x Otrok s oštěpy\n1000 x Otrok štítonoš")'><br>-->
        <!--Old: <input value='Přidat zdi imperátorova paláce' type=button onclick='pridej("\n30 x Kamenná hradba\n10 x Kamenná věž\n5 x Věž s balistou")'><br>-->
        <!--Broken: <input style='color:gold' value='Zobrazit klan - Hell' type=button onclick='hraci("hell")'><br>-->
        <!--Broken: <input style='color:gold' value='Zobrazit klan - Heaven' type=button onclick='hraci("heaven")'><br>-->
        <input style='color:gray' value='Ohnivá sekta' type=button onclick='pole("10 x arcimág ohně\n50 x mág ohně\n100 x vulkánův přívrženec")'><br>
        <input style='color:gray' value='Ohnnivý kruh' type=button onclick='pole("1 x ohnivý služebník\n1000 x fanatický vulkánův přívrženec")'><br>
        <input style='color:gray' value='Chrám ohnivé sekty' type=button onclick='pole("1 x flamekeeper\n200 x arcimág ohně\n3000 x mág ohně\n6000 x vulkánův přívrženec")'><br>
        <input style='color:gray' value='Vulkánův výcvikový tábor' type=button onclick='pole("8 x ohnivá zeď\n10 x Vulkánův elitní válečník\n5000 x vulkánův válečník\n10000 x vulkánův přívrženec")'><br>
        <input style='color:gray' value='Vulkánův chrám' type=button onclick='pole("100 x ohnivý služebník\n1000 x vulkánův kněz\n30000 x Fanatický vulkánův přívrženec")'><br>
        <input style='color:gray' value='Prastarý chrám ohnivé sekty' type=button onclick='pole("1 x ohnivý přízrak\n3 x flamekeeper\n1000 x arcimág ohně\n20000 x mág ohně\n40000 x vulkánův přívrženec\n500 x Vulkánův elitní válečník")'><br>
        <input style='color:gray' value='Ohnivá pevnost' type=button onclick='pole("1 x vulkánův generál\n1 x flamekeeper\n60 x ohnivá zeď\n100 x arcimág ohně\n1000 x mág ohně\n60000 x vulkánův přívrženec\n25000 x vulkánův válečník\n5000 x vulkánův elitní válečník")'><br>
        <input style='color:gray' value='Ohnivý palác' type=button onclick='pole("1 x flamekeeper lord\n3 x Ohnivý přízrak\n10 x ohnivý obr\n30 x flamekeeper\n1000 x arcimág ohně\n10000 x mág ohně\n20000 x vulkánův přívrženec\n1000 x vulkánův elitní válečník\n500 x ohnivý služebník")'><br>
        <input style='color:gray' value='Vulkánova univerzita' type=button onclick='pole("1 x vulkánův generál\n1 x flamekeeper lord\n1 x Ohnivý přízrak\n30 x flamekeeper\n500 x Ohnivý Fénix\n700 x ohnivý služebník\n5000 x arcimág ohně\n20000 x mág ohně\n50000 x vulkánův přívrženec\n20000 x vulkánův válečník\n5000 x vulkánův elitní válečník")'><br>
        <input style='color:gray' value='Vulkánova posvátná hora' type=button onclick='pole("1 x nižší ohnivý splozenec\n1 x magmatický obr\n5 x ohnivý obr\n30 x flamekeeper\n500 x ohnivý služebník\n600 x magmatický golem\n1000 x vulkánův válečník\n5000 x vulkánův přívrženec\n10000 x vulkánův kněz\n10000 x Rozžhavené magma\n200000 x Fanatický vulkánův přívrženec")'><br>
        <input style='color:gray' value='Ohnivý palác posvěcený vulkánem' type=button onclick='pole("3 x flamekeeper lord\n10 x ohnivý přízrak\n100 x flamekeeper\n3000 x arcimág ohně\n35000 x mág ohně\n60000 x vulkánův přívrženec\n2000 x vulkánův elitní válečník\n900 x ohnivý služebník\n1500 x efreet")'><br>
        <input style='color:gray' value='Vulkánův nejsvětější svatostánek' type=button onclick='pole("1 x flamekeeper lord\n1 x ohnivý zplozenec\n5 x magmatický obr\n10 x ohnivý přízrak\n10 x ohnivý obr\n50 x flamekeeper\n1000 x magmatický golem\n5000 x vulkánův válečník\n50000 x vulkánův kněz\n100000 x vulkánův přívrženec\n500000 x Fanatický vulkánův přívrženec")'><br>
        <input style='color:gray' value='Vulkánův speciál' type=button onclick='pole("1 x Vulkán bůh ohně\n10 x Ohnivý zplozenec\n40 x Nižší ohnivý splozenec\n50 x Flamekeeper lord\n50 x Vulkánův generál\n100 x magmatický obr\n200 x Ohnivý přízrak\n600 x ohnivý obr\n2000 x Ohnivá zeď\n2000 x flamekeeper\n10000 x ohnivý golem\n10000 x lávový golem\n10000 x magmatický golem\n15000 x ohnivý služebník\n30000 x Ohnivý Fénix\n40000 x efreet\n100000 x arcimág ohně\n100000 x Vulkánův elitní válečník(Válečné bubny)\n100000 x Vulkánův kněz\n500000 x Vulkánův válečník\n500000 x mág ohně(Dalekohled)\n1000000 x Vulkánův přívrženec\n2000000 x Fanatický vulkánův přívrženec(Ohnivá palisáda)")'><br>
        <input style='color:gray' value='Temné pobřeží' type=button onclick='pole("1 x Pán vod\n2 x Worloo\n50 x Vodní drak\n3250 x Temná sépie\n8500 x Oživlá voda\n12500 x Strážce moří\n14655 x Virgo")'><br><br>

        <b style='color:gray'>Císařské armády</b><br><br>
        <input style='color:gray' value='květen 2015 (rip001)' type=button onclick='pole("1 x Generál starého impéria (Ohnivá róba)\n1 x Generál starého impéria\n200 x Kapitán elitních střelců starého impéria\n1 x Generál starého impéria (Ohnivá róba)\n200 x Kapitán rytířů starého impéria\n5,000 x Elitní střelec starého impéria\n5,000 x Rytíř starého impéria\n500 x Kapitán střelců starého impéria\n60 x Železný orel - MK1 (Hůlka ledu)\n3,602 x Elfí lučištník (Bronzový meč)\n10,000 x Střelec starého impéria\n155,798 x Elfí elitní lučištník (Težká bojová sekyra)\n1 x Posvátný jednorožec (Kalich ohně)\n20,000 x Elfí hraničář (Prapor krvavého šílenství)\n519 x Jednorožec (Kouzelnická róba)\n50 x Plukovník pěšaků starého impéria\n6 x Prokletý Obr (Prokletá kouzelnická róba)\n1 x Místodržící\n720 x Ohnivý Služebník (Dralgarův Totem života)\n800 x Kapitán pěšáků starého impéria\n4 x Ohnivý Obr\n30 x Plukovník těžkých pěšaků starého impéria\n5 x Ledový obr (Atherův ledový prapor)\n500 x Kapitán těžkých pěšáků starého impéria\n40,000 x Pěšák starého impéria\n20,000 x Těžký pěšák starého impéria\n6,308 x Lesní Bizon (Vulkánův ohnivý prapor)\n1,000 x Železný škopion (Težká bojová sekyra)\n1,080 x Ledový Přízrak (Kalich ohně)\n68,564 x Veledruid (Prapor starého druidského cechu)\n122,520 x Otrok s oštěpy (Dralgarův Totem života)\n204,574 x Žebrák (Kalich ohně)\n5,718 x Odpadlík (Kalich ohně)\n2,601 x Druid (Kalich ohně)\n44 x Lesní obr (Dralgarův Totem života)\n263,030 x Růžové prasátko (Kalich ohně)\n142,620 x Otrok s kopím (Dralgarův Totem života)\n600 x Železný Golem (Tvrzené ocelové pláty)\n200 x Starodávný Ent\n690,457 x Ozbrojený vesničan (Pírko z anděla)\n131,320 x Otrok štítonoš (Dralgarův Totem života)\n27,362 x Hnijící vesničan (Kalich ohně)\n1,135 x Těžká balista (Atherův ledový prapor)\n200 x Poloautomatická socha (Tvrzené ocelové pláty)")'><br>
    </td>
</tr>
</table>


<div id='obrazek'></div>
<div id='result'></div>
<div style='width: 600px; margin: auto'>
<br>
    <h4>Bugy (Nevyžádané features)</h4>
    1. V LoI se iniciativa snižuje až na konci kola, zatímco v simulátoru okamžitě. S výjimkou případů, kdy iniciativa klesne na 0 a jednotka pak regulérně nezaútočí.
    Toto je ovšem bug k opravení na straně loi a ne v simulátoru :P<br>
    2. Temná jeskyně - úplně špatně. Spolek mocných - chybí některé jednotky.
    V případě, že narazíte na jiné chyby, dejte vědět (viz sekce Kontakty)
    <br><br>
    <h4>Nápady na vylepšení</h4>
    1) Přidat odměny při výhře, 2) Hodnoty min/max armád eventů, 3) Upgrade PHP 5.6 na 7.4/8.1 (vyžaduje podporu na serveru), 4) Spousta dalších v repozitáři v poznamky/TODO.txt<br>
    Pokud se chce kdokoliv podílet na vylepšování/údržbě simulátoru, je vítán (pište na fórum, kontakt níže)!!! ;-)
    <br><br>
    <h4>Kontakty</h4>
    Chyby, nové eventy, nápady na vylepšení, pomoc a podobně pište na oficiální LoI fórum do sekce <a href="http://landofice.com/forum/viewtopic.php?f=6&t=470">Simulátor</a>.<br>
    Můžete věci napřed probrat na herním chatu, ale tam to rychle zapadne, takže to vždy potom napište na fórum.
    Pokud chcete mít jistotu, že se to dostane přímo k vývojářům, vytvořte "issue" přímo v <a href="https://github.com/xstast24/landofice-simulator">github repositáři simulátoru</a>
    (jestli se stránka nenačte, tak nemáte přístup -> napište na fórum a přidáme vás).<br>
    V případě věcí, které se stydíte řešit na fóru (např. velikost armády), můžete psát Botovi na herní mail FLLL@SEZNAM.CZ
    (chodím tam ~1x za měsíc, kdybych dlouho neodpovídal, napište znovu, možná to zapadlo ve spamu).
    <br><br>
    <h4>Credits</h4>
    ❥Bota a ❥Healtonn <small>(reinkarnace simulátoru 2022 a development)</small>
    ❥Hron <small>(informace o jednotkách, herní zkušenosti)</small>
    ❥Nezmar <small>(údržba a vývoj LoI, herní info)</small>
    ❥Rip <small>(dlouhou dobu udržoval a spravoval simulátor do 2021)</small>
    ❥Původní autor <small>(kdo to byl, ať zvedne ruku)</small>
    ❥Drago <small>(tvůrce LoI, ještě stále nám hostuje LoI)</small>
<br><br>
</div>
