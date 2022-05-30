<!DOCTYPE html>
<html>
<head>
<META http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<meta http-equiv='Content-Style-Type' content='text/css'>
<LINK type='text/css' href='atak.css' rel='stylesheet'>
<style type=text/css>
body {color: white;}
.boj {background:white;color:black;border:2px silver solid;font-size:15px;font-weight:bold}
.boj:hover {cursor:hand;background:#ddd; color:#333}
table td {color: white;}
</style>
</head>
<body bgcolor='#000000' text='#f5f5f5' link='#b0c4de' vlink='#b0c4de' alink='#b0c4de' topmargin='0'>
<center><input type='submit' onClick="window.close();" value="Zavřít" class='boj'></center><br>
<?php

echo "<table  align='center' class='text' style='border-style:outset;border-width:3px;'><tr style='background:#000033;text-align:center;'>";
//nadpis tabulky
echo "<tr><td width='300px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "<b>Plenění</b></td></tr>";
//Obři zla
echo "<tr><td width='300px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "Válka na Gargantue-obři zla";
echo "</td>";
echo "<td width='500px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "<a title='Jednotka získá +2 do obrany.'>Bronzové pláty</a> | <a title='Jednotka získá +4 do útoku.'>Rytířský jednoruční meč</a> | <a title='Válečné bubny posíli armádu. +1 inic, +1 útok, -1 obrana. Pouze pro živé jednotky s druhem útoku 1.'>Válečné bubny</a> | <a title='Jednotka získá +4 do obrany, ale její iniciativa klesne o 1.'>Železné pláty</a></td></tr>";
//Obři dobra
echo "<tr><td width='300px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "Válka na Gargantue-obři dobra";
echo "</td>";
echo "<td width='500px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "<a title='Jednotka získá +2 do obrany.'>Bronzové pláty</a> | <a title='Jednotka získá +4 do útoku.'>Rytířský jednoruční meč</a> | <a title='Válečné bubny posíli armádu. +1 inic, +1 útok, -1 obrana. Pouze pro živé jednotky s druhem útoku 1.'>Válečné bubny</a> | <a title='Jednotka získá +4 do obrany, ale její iniciativa klesne o 1.'>Železné pláty</a></td></tr>";
//země zatracených
echo "<tr><td width='300px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "Země zatracených";
echo "</td>";
echo "<td width='500px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "<a title='Jednotka získá +4 do obrany a pokud je Dharova, získá dalších +6.'>Dharova kamenná standarta</a> | <a title='Jednotka získá schopnost magie ledu 1 a pokud je Æthrova, získá navíc +3 do iniciativy.'>Æthrův ledový prapor</a> | <a title='Jednotka získá +15% do životů a pokud je Dralgarova, získá další 35%.'>Dralgarův Totem života</a> | <a title='Jednotka získá +15% do poškození a pokud je Ghorova, získá další 35%.'>Ghorova standarta s nabodnutou hlavou démona</a> | <a title='Jednotka získá +1 do obrany a je imunní vůči ohnivému štítu.'>Kouzelnická róba</a> | <a title='Jednotka získá +6 do obrany, ale její iniciativa klesne o 1.'>Ocelové pláty</a> | <a title='Jednotka získá +3 do obrany a je imunní vůči ohnivému štítu.'>Prokletá kouzelnická róba</a> | <a title='Jednotka získá +8 do obrany, ale její iniciativa klesne o 1.'>Tvrzené ocelové pláty</a> | <a title='Jednotka získá ohnivý štít +1 a pokud je Vulkánova, získá další +2.'>Vulkánův ohnivý prapor</a></td></tr>";
//temný les
echo "<tr><td width='300px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "Temný les";
echo "</td>";
echo "<td width='500px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "<a title='Jednotka získá +4 do obrany a pokud je Dharova, získá dalších +6.'>Dharova kamenná standarta</a> | <a title='Druidové a nemrtví Druidové vyvolávají o 50% více Entů.'>Druidský rituál</a> | <a title='Jednotka získá +1 do obrany a je imunní vůči ohnivému štítu.'>Kouzelnická róba</a> | <a title='Unikátní jednotka se naučí magii ohně 3 a získá ohnivý štít 500.'>Ohnivá róba</a> | <a title='Unikátní jednotce je změněn druh útoku na 2.'>Slonovinový luk</a> | <a title='Jednotka získá +8 do obrany, ale její iniciativa klesne o 1.'>Tvrzené ocelové pláty</a></td></tr>";
//otrokářská kolonie
echo "<tr><td width='300px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "Otrokářská kolonie";
echo "</td>";
echo "<td width='500px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "<a title='Unikátní jednotka sešle počet životů/2 ohnivých koulí a získá ohnivý štít 750.'>Ohnivý bič</a> | <a title='Jednotka s druhem útoku 1 získá +1 do damage a -1 od životů.'>Totem krve</a></td></tr>";
//ledový palác
echo "<tr><td width='300px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "Ledový palác";
echo "</td>";
echo "<td width='500px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "<a title='Jednotka získá schopnost magie ledu 1 a pokud je Æthrova, získá navíc +3 do iniciativy.'>Æthrův ledový prapor</a> | <a title='Jednotka se naučí magii ledu 1.'>Hůlka ledu</a> | <a title='Unikátní jednotka se naučí magii ledu 1.'>Ledová čepel</a> | <a title='Pomocí této formule můžeš složit Hůlku ledu, Svazek ledového mistrovství, Ledovou róbu a vytvořit tak Æthrovu róbu moci.'>Ledová formule</a> | <a title='Unikátní jednotka sešle počet životů/2 ledových koulí a získá ledový štít 30%.'>Svazek ledového mistrovství</a></td></tr>";
//dralgarova zahrada
echo "<tr><td width='300px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "Dralgarova zahrada";
echo "</td>";
echo "<td width='500px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "<a title='Jednotka získá +15% do životů a pokud je Dralgarova, získá další 35%.'>Dralgarův Totem života</a> | <a title='Druidové a nemrtví Druidové vyvolávají o 50% více Entů.'>Druidský rituál</a> | <a title='Vrátí jednoho mrtvého generála k životu.'>Esence života</a> | <a title='Unikátní jednotka sešle počet životů/2 ohnivých koulí a získá ohnivý štít 750.'>Ohnivý bič</a> | <a title='Druidové přivolávají místo Entů Starodávné Enty a Veledruidové přivolávají o 50% více Starodávných Entů.'>Prapor starého druidského cechu</a></td></tr>";
//prokletý vojevůdce
echo "<tr><td width='300px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "Prokletý vojevůdce";
echo "</td>";
echo "<td width='500px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "<a title='Jednotka získá +4 do obrany a pokud je Dharova, získá dalších +6.'>Dharova kamenná standarta</a> | <a title='Jednotka získá +2 do útoku a obrany a pokud je Dreaddova, získá další +4.'>Dreaddův prapor smrti</a> | <a title='Jednotka získá +15% do poškození a pokud je Ghorova, získá další 35%.'>Ghorova standarta s nabodnutou hlavou démona</a> | <a title='Po postavení archeologického tábora zde můžeme nalézt spoustu cenných předmětů.'>Mapa k archeologickému nalezišti</a> | <a title='Podle tohoto plánu budeme schopni postavit školu architektů.'>Plán na stavbu školy architektů</a> | <a title='Podle tohoto plánu můžeme zřídit uprchlický tábor.'>Plány na Uprchlický tábor</a> | <a title='Jednotka získá ohnivý štít +1 a pokud je Vulkánova, získá další +2.'>Vulkánův ohnivý prapor</a></td></tr>";
//prokletá citadela
echo "<tr><td width='300px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "Prokletá citadela";
echo "</td>";
echo "<td width='500px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "<a title='Jednotka získá +4 do obrany a pokud je Dharova, získá dalších +6.'>Dharova kamenná standarta</a> | <a title='Jednotka získá +15% do životů a pokud je Dralgarova, získá další 35%.'>Dralgarův Totem života</a> | <a title='Jednotka získá +2 do útoku a obrany a pokud je Dreaddova, získá další +4.'>Dreaddův prapor smrti</a> | <a title='Vrátí jednoho mrtvého generála k životu.'>Esence života</a> | <a title='Jednotka získá +15% do poškození a pokud je Ghorova, získá další 35%.'>Ghorova standarta s nabodnutou hlavou démona</a> | <a title='Po postavení archeologického tábora zde můžeme nalézt spoustu cenných předmětů.'>Mapa k archeologickému nalezišti</a> | <a title='Jednotka získá +8 do obrany, ale její iniciativa klesne o 1.'>Tvrzené ocelové pláty</a> | <a title='Jednotka získá +4 do obrany, ale její iniciativa klesne o 1.'>Železné pláty</a></td></tr>";
//údolí stínů
echo "<tr><td width='300px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "Údolí stínů";
echo "</td>";
echo "<td width='500px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "<a title='Po postavení archeologického tábora zde můžeme nalézt spoustu cenných předmětů.'>Mapa k archeologickému nalezišti</a> | <a title='Podle této mapy můžete nalézt tábor a poté ho vyplenit.'>Mapa k archeologickému táboru</a> | <a title='Mapa k nalezení zlatého dolu a plány na jeho stavbu.'>Mapa k zlatému nalezišti</a> | <a title='Podle tohoto plánu budeme schopni postavit školu architektů.'>Plán na stavbu školy architektů</a> | <a title='Podle tohoto plánu můžeme zřídit uprchlický tábor.'>Plány na Uprchlický tábor</a></td></tr>";
//mucusova pevnost
echo "<tr><td width='300px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "Mucusova pevnost";
echo "</td>";
echo "<td width='500px' align='center' style='border: 1px dashed gray;height:10px;text-align:center;padding:8px'>";
echo "... vypis artefaktu ...</td></tr>";


echo "</table>";

?>
<script src="sorttable.js" type="text/javascript"></script>