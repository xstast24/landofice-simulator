<!DOCTYPE html>
<html>

<head>
	<META http-equiv='Content-Type' content='text/html; charset=UTF-8'>
	<meta http-equiv='Content-Style-Type' content='text/css'>
	<LINK type='text/css' href='atak.css' rel='stylesheet'>
	<style type=text/css>
		body {
			color: white;
		}

		.boj {
			background: white;
			color: black;
			border: 2px silver solid;
			font-size: 15px;
			font-weight: bold
		}

		.boj:hover {
			cursor: hand;
			background: #ddd;
			color: #333
		}

		table td {
			color: white;
		}
	</style>
</head>

<body bgcolor='#000000' text='#f5f5f5' link='#b0c4de' vlink='#b0c4de' alink='#b0c4de' topmargin='0'>
	<center><input type='submit' onClick="window.close();" value="Zavřít" class='boj'></center><br>
	<?php
    include_once("simulator_engine.php");
	echo "<table  class=\"sortable\" align='center' class='text' style='border-style:outset;border-width:3px;'><tr style='background:#000033;text-align:center;'>
	<td><br></td>
	<td>Název</td>
	<td>Damage</td>
	<td>Útok</td>
	<td>Obrana</td>
	<td>Iniciativa</td>
	<td>Druh útoku</td>
	<td>Životů</td>
	<td>Hodnota</td>	
	<td><br></td>
	<td><br></td>
</tr>";

	$xml = simplexml_load_file("jednotky.xml");

	foreach ($xml->jednotka as $jednotka) {
		echo "<tr><td width='50px' align='center' style='border: 1px dashed gray;height:50px;text-align:center;padding:8px'>" . $jednotka->id . "</td><td align='center' style='border: 1px dashed gray;height:50px;text-align:center;padding:8px'>" . $jednotka->nazev . "</td>";
		echo "<td style='border: 1px dashed gray;height:50px;text-align:center;padding:8px'>" . $jednotka->damage . "</td>";
		echo "<td style='border: 1px dashed gray;height:50px;text-align:center;padding:8px'>" . $jednotka->utok . "</td>";
		echo "<td style='border: 1px dashed gray;height:50px;text-align:center;padding:8px'>" . $jednotka->obrana . "</td>";
		echo "<td style='border: 1px dashed gray;height:50px;text-align:center;padding:8px'>" . $jednotka->iniciativa . "</td>";
		echo "<td style='border: 1px dashed gray;height:50px;text-align:center;padding:8px'>" . $jednotka->typUtoku . "</td>";
		echo "<td style='border: 1px dashed gray;height:50px;text-align:center;padding:8px'>" . $jednotka->zivoty . "</td>";
		echo "<td style='border: 1px dashed gray;height:50px;text-align:center;padding:8px'>" . $jednotka->hodnota . "</td>";
		echo "<td style='border: 1px dashed gray;height:50px;text-align:center;padding:8px'>";
		foreach ($jednotka->schopnosti->schopnost as $schopnost) {
			$nazevSchopnosti = trim($schopnost->nazev);
			$hodnotaSchopnosti = $schopnost->hodnota * 1;
			if ($nazevSchopnosti == DAV) {
				echo "<img src='images/trample.jpg' alt='Dav' title='Dav - Za každého jednotlivce získá jednotka jako celek 1% do útoku. Naopak je ale snížena obrana.'> ";
			} elseif ($nazevSchopnosti == STEC) {
				echo "<img src='images/stec.gif' alt='Steč' title='Steč - Jednotka dostává v 3tím kole " . $hodnotaSchopnosti . " do damage. Nefunguje na jednotky s velkým rozdílem obrany a útoku.'> ";
			} elseif ($nazevSchopnosti == IMUNITA_MAGIE) {
				echo "<img src='images/neni.gif' alt='Imunita Magie' title='Imunita proti magii - Jednotka má " . $hodnotaSchopnosti . "% šanci na zablokování kouzel, které na ni působí.'> ";
			} elseif ($nazevSchopnosti == "sit") {
				echo "<img src='images/sit.png' alt='Síť' title='Síť - Jednotka má šanci zpomalit nepřítele na jedno kolo.'> ";
			} elseif ($nazevSchopnosti == SLAYER) {
				echo "<img src='images/slayer.gif' alt='Slayer' title='Slayer - Jednotka ignoruje obranu napadené jednotky.'> ";
			} elseif ($nazevSchopnosti == MAGIE_LESA and $hodnotaSchopnosti == 1) {
				echo "<img src='images/magie_les_1.png' alt='Magie lesa 1' title='Magie lesa 1 - Tato jednotka vyvolává v boji Enty.'> ";
			} elseif ($nazevSchopnosti == MAGIE_LESA and $hodnotaSchopnosti == 3) {
				echo "<img src='images/magie_les_3.png' alt='Magie lesa 3' title='Magie lesa 3 - Tato jednotka vyvolává v boji Starodávné enty.'> ";
			} elseif ($nazevSchopnosti == MAGIE_LESA and $hodnotaSchopnosti == 4) {
				echo "<img src='images/magie_les_4.png' alt='Magie lesa 4' title='Magie lesa 4 - Jednotka léčí přátelské jednotky. Mrtvou jednotku není možné vyléčit. Maximálně může vyléčit počet_léčitelů * životy_léčitele/2.'> ";
			} elseif ($nazevSchopnosti == MAGIE_LESA and $hodnotaSchopnosti == 5) {
				echo "<img src='images/magie_les_5.png' alt='Magie lesa 5' title='Magie lesa 5 - Jednotka léčí přátelské jednotky. Mrtvou jednotku není možné vyléčit. Může vyléčit až 10% ze životů, které jednotka měla před bojem.'> ";
			} elseif ($nazevSchopnosti == MAGIE_ZEME and $hodnotaSchopnosti == 1) {
				echo "<img src='images/magie_zeme_1.jpg' alt='Magie země 1' title='Magie země 1 - X útočníků s touto magií promění stejný počet nepřátel v kámen, který se posléze rozpadne.'> ";
			} elseif ($nazevSchopnosti == MAGIE_ZEME and $hodnotaSchopnosti == 2) {
				echo "<img src='images/neni.gif' alt='Magie země 2' title='Magie země 2 - Jednotka v prvním kole přidá X% obrany, kde X je počet sesilatelů kouzla'> ";
			} elseif ($nazevSchopnosti == MAGIE_LEDU and ($hodnotaSchopnosti == 1)) {
				echo "<img src='images/magie_led_1.jpg' alt='Magie ledu 1' title='Magie ledu 1 - Jednotka zasaženou jednotku pokryje ledovými krystaly a sníží tak její iniciativu o 1.'> ";
			} elseif ($nazevSchopnosti == MAGIE_LEDU and $hodnotaSchopnosti == 2) {
				echo "<img src='images/magie_led_2.jpg' alt='Magie ledu 2' title='Magie ledu 2 - Jednotka zasaženou jednotku pokryje ledovými krystaly a sníží tak její iniciativu o 3.'> ";
			} elseif ($nazevSchopnosti == MAGIE_LEDU and $hodnotaSchopnosti == 3) {
				echo "<img src='images/magieledu3.gif' alt='Magie ledu 3' title='Magie ledu 3 - Jednotka vyvolá ledová koule, která mají šanci snížit iniciativu jednotce, kterou zasáhnou'> ";
			} elseif ($nazevSchopnosti == MAGIE_LEDU and $hodnotaSchopnosti == 4) {
				echo "<img src='images/magie_led_4.jpg' alt='Magie ledu 4' title='Magie ledu 4 - Zasažené jednotce je snížena iniciativa o 10, a její obrana je snížena na polovinu'> ";
			} elseif ($nazevSchopnosti == MAGIE_LEDU and $hodnotaSchopnosti == 5) {
				echo "<img src='images/magie_led_5.jpg' alt='Magie ledu 5' title='Magie ledu 5 - Zruší zasažené jednotce magii ohně a ohnivý štít.'> ";
			} elseif ($nazevSchopnosti == MAGIE_LEDU and $hodnotaSchopnosti == 6) {
				echo "<img src='images/magieledu6.gif' alt='Magie ledu 6' title='Magie ledu 6 - Jednotka dokáže vytvořit ledovou hradbu.'> ";
			} elseif ($nazevSchopnosti == MAGIE_LEDU and $hodnotaSchopnosti == 7) {
				echo "<img src='images/neni.gif' alt='Magie ledu 7' title='Magie ledu 7 - Jednotka přivolá magickou bouři.'> ";
			}
			
			if ($nazevSchopnosti == MAGIE_OHNE and $hodnotaSchopnosti == 1) {
				echo "<img src='images/magie_ohen_1.png' alt='Magie ohně I' title='Magie ohně I - Jednotka sešle Ohnivou kouli.'> ";
			} elseif ($nazevSchopnosti == MAGIE_OHNE and $hodnotaSchopnosti == 2) {
				echo "<img src='images/magieohen2.gif' alt='Magie ohně II' title='Magie ohně II - Jednotka vyvolá Ohnivé impy.'> ";
			} elseif ($nazevSchopnosti == MAGIE_OHNE and $hodnotaSchopnosti == 3) {
				echo "<img src='images/magie_ohen_3.png' alt='Magie ohně III' title='Magie ohně III - Jednotka sesílá Meteority.'> ";
			} elseif ($nazevSchopnosti == MAGIE_OHNE and $hodnotaSchopnosti == 4) {
				echo "<img src='images/magie_ohen_4.jpg' alt='Magie ohně IV' title='Magie ohně IV - Jednotka přivolá Ohnivý přízrak.'> ";
			} elseif ($nazevSchopnosti == MAGIE_OHNE and $hodnotaSchopnosti == 5) {
				echo "<img src='images/magie_ohen_5.jpg' alt='Magie ohně V' title='Magie ohně V - Jednotka vrhá proti nepříteli magmatické kameny'> ";
			} elseif ($nazevSchopnosti == MAGIE_OHNE and $hodnotaSchopnosti == 6) {
				echo "<img src='images/magie_ohen_6.png' alt='Magie ohně VI' title='Magie ohně VI - Vulkán. Kouzlo jež nese jméno samotného boha nejenom že musí být opravdu ničivé, ono i ničivé je.'> ";
			} elseif ($nazevSchopnosti == MAGIE_OHNE and $hodnotaSchopnosti == 7) {
				echo "<img src='images/neni.gif' alt='Magie ohně VII' title='Magie ohně VII - Jednotka má šanci na přivolání Stínových draků.'> ";
			} elseif ($nazevSchopnosti == MAGIE_OHNE and $hodnotaSchopnosti == 8) {
				echo "<img src='images/magie_ohen_8.jpg' alt='Magie ohně VIII' title='Magie ohně VIII - Tato jednotka přivolá Stínové ohaře.'> ";
			} elseif ($nazevSchopnosti == MAGIE_OHNE and $hodnotaSchopnosti == 9) {
				echo "<img src='images/magie_ohen_9.jpg' alt='Magie ohně IV' title='Magie ohně IV - Jednotka Přivolá stínové bestie.'> ";
			} elseif ($nazevSchopnosti == MAGIE_OHNE and $hodnotaSchopnosti == 10) {
				echo "<img src='images/magie_ohen_10.jpg' alt='Magie ohně X' title='Magie ohně X - Tato jednotka přivolává Ohnivý déšť.'> ";
			} elseif ($nazevSchopnosti == MAGIE_SMRTI and $hodnotaSchopnosti == 1) {
				echo "<img src='images/magie_smrt_1.jpg' alt='Magie smrti 1' title='Magie smrti 1 - Jednotka vytvoří ze zabité živé jednotky Kostlivce.'> ";
			} elseif ($nazevSchopnosti == MAGIE_SMRTI and $hodnotaSchopnosti == 2) {
				echo "<img src='images/magiesmrti2.gif' alt='Magie smrti 2' title='Magie smrti 2 - Jednotka po zabití nepřátelské jednotky vzkřísí část jako Lichy.'> ";
			} elseif ($nazevSchopnosti == MAGIE_SMRTI and $hodnotaSchopnosti == 3) {
				echo "<img src='images/magie_smrt_3.jpg' alt='Magie smrti 3' title='Magie smrti 3 - Jednotka demoralizuje napadenou živou jednotku a sníží tak její iniciativu o 5.'> ";
			} elseif ($nazevSchopnosti == MAGIE_SMRTI and $hodnotaSchopnosti == 4) {
				echo "<img src='images/magie_smrt_4.png' alt='Magie smrti 4' title='Magie smrti 4 - Jednotka oslabuje živého protivníka o počet jednotek sesilatele.'> ";
			} elseif ($nazevSchopnosti == MAGIE_SMRTI and $hodnotaSchopnosti == 5) {
				echo "<img src='images/magie_smrt_5.png' alt='Magie smrti 5' title='Magie smrti 5 - Jednotka přivolá 150 až 400 Uvězněných duší.'> ";
			} elseif ($nazevSchopnosti == MAGIE_SMRTI and $hodnotaSchopnosti == 6) {
				echo "<img src='images/magie_smrt_6.jpg' alt='Magie smrti 6' title='Magie smrti 6 - Jednotka dokáže vytvořit ze zabité jednotky nemrtvé, kteří majívlastnosti svých předchůdců.'> ";
			} elseif ($nazevSchopnosti == MAGIE_SMRTI and $hodnotaSchopnosti == 7) {
				echo "<img src='images/magie_smrt_7.jpg' alt='Magie smrti 7' title='Magie smrti 7 - Jednotka dokáže přivolat Prokleté enty.'> ";
			} elseif ($nazevSchopnosti == MAGIE_SMRTI and $hodnotaSchopnosti == 8) {
				echo "<img src='images/neni.gif' alt='Magie smrti 8' title='Magie smrti 8 - Za každou jednotku se sníží útok, obrana a poškození zasaženého soupeře o 0.5% (hodnota postupně klesá).'> ";
			} elseif ($nazevSchopnosti == MAGIE_SMRTI and $hodnotaSchopnosti == 9) {
				echo "<img src='images/magie_smrt_9.jpg' alt='Magie smrti 9' title='Magie smrti 9 - Jednotka vytvoří ze zabité živé jednotky Ghúly.'> ";
			} elseif ($nazevSchopnosti == MAGIE_SMRTI and $hodnotaSchopnosti == 10) {
				echo "<img src='images/neni.gif' alt='Magie smrti 10' title='Magie smrti 10 - Jednotka přivolá 300 až 800 Uvězněných duší.'> ";
			} elseif ($nazevSchopnosti == MAGIE_SMRTI and $hodnotaSchopnosti == 11) {
				echo "<img src='images/magie_smrt_11.png' alt='Magie smrti 11' title='Magie smrti 11 - Jednotka oslabuje živého protivníka o počet jednotek sesilatele.'> ";
			} elseif ($nazevSchopnosti == MAGIE_SMRTI and $hodnotaSchopnosti == 12) {
				echo "<img src='images/magie_smrt_12.jpg' alt='Magie smrti 12' title='Magie smrti 12 - Jednotka po zabití nepřátelské jednoty vzkřísí část mrtvých jako Upíra.'> ";
			} elseif ($nazevSchopnosti == MAGIE_SMRTI and $hodnotaSchopnosti == 13) {
				echo "<img src='images/neni.gif' alt='Magie smrti 13' title='Magie smrti 13 - Jednotka povolá ze zabitích goblinů Gobliní Přízraky.'> ";
			} elseif ($nazevSchopnosti == MAGIE_SVETLA and $hodnotaSchopnosti == 1) {
				echo "<img src='images/magie_svetla_1.png' alt='Magie světla 1' title='Magie světla 1 - Jednotka promění nemrtvou jednotku na prach.'> ";
			} elseif ($nazevSchopnosti == MAGIE_SVETLA and $hodnotaSchopnosti == 2) {
				$hodnotaSchopnosti = getLightMagicValue($jednotka);
				echo "<img src='images/magie_svetla_2.png' alt='Magie světla 2' title='Magie světla 2 - Jednotka získá +$hodnotaSchopnosti% do obrany každé kolo.'> ";
			} elseif ($nazevSchopnosti == MAGIE_SVETLA and $hodnotaSchopnosti == 3) {
				$hodnotaSchopnosti = getLightMagicValue($jednotka);
				echo "<img src='images/magie_svetla_3.png' alt='Magie světla 3' title='Magie světla 3 - Jednotka získá +$hodnotaSchopnosti% do útoku každé kolo.'> ";
			} elseif ($nazevSchopnosti == MAGIE_SVETLA and $hodnotaSchopnosti == 4) {
				$hodnotaSchopnosti = getLightMagicValue($jednotka);
				echo "<img src='images/magie_svetla_4.png' alt='Magie světla 4' title='Magie světla 4 - Jednotka získá +$hodnotaSchopnosti% do iniciativy každé kolo.'> ";
			} elseif ($nazevSchopnosti == MAGIE_SVETLA and $hodnotaSchopnosti == 5) {
				echo "<img src='images/magie_svetla_5.png' alt='Magie světla 5' title='Magie světla 5 - Všechno jednotky Crinis nebo jednotky ovládající libovolnou magii světla získají +1 do iniciativy a navíc se jejich steč zvedne o 5x jejich damage (platí i pro jednotky bez steče)'> ";
			} elseif ($nazevSchopnosti == OHNIVY_STIT) {
				echo "<img src='images/ohnivy_stit.jpg' alt='Ohnivý štít $hodnotaSchopnosti' title='Ohnivý štít- Pokud je jednotka napadena, obdrží protivník " . $hodnotaSchopnosti . " poškození. Některé jednotky mohou být vůči ohnivému štítu imuní.'> ";
			} elseif ($nazevSchopnosti == LEDOVY_STIT) {
				echo "<img src='images/ledovy_stit.jpg' alt='Ledový štít $hodnotaSchopnosti' title='Ledový štít - sníží iniciativu útočníka o $hodnotaSchopnosti %'> ";
			} elseif ($nazevSchopnosti == TOXICKY_STIT) {
				echo "<img src='images/toxicita.jpg' alt='Toxicita $hodnotaSchopnosti' title='Toxicita - Jednotka má kolem sebe toxický opar, který zraňuje živé útočníky o $hodnotaSchopnosti'> ";
			} elseif ($nazevSchopnosti == DRTIVY_UTOK) {
				echo "<img src='images/drtive_sipy.png' alt='Drtivý útok $hodnotaSchopnosti' title='Drtivý útok - Jednotka získá + $hodnotaSchopnosti% proti neživým jednotkám.'> ";
			} elseif ($nazevSchopnosti == SVATY_UTOK) {
				echo "<img src='images/svate_sipy.png' alt='Svatý útok $hodnotaSchopnosti' title='Svatý útok - Jednotka získá + $hodnotaSchopnosti% proti nemrtvým jednotkám.'> ";
			} elseif ($nazevSchopnosti == JEDOVY_UTOK) {
				echo "<img src='images/otravene_sipy.png' alt='Otrávený útok $hodnotaSchopnosti' title='Otrávený útok - Jednotka získá + $hodnotaSchopnosti% proti živým jednotkám.'> ";
			} elseif ($nazevSchopnosti == EXTERMINACE) {
				echo "<img src='images/exterminace.jpg' alt='Exterminace' title='Exterminace'> ";
			} elseif ($nazevSchopnosti == IMUNITA_OHEN) {
				echo "<img src='images/imunita_ohen.png' alt='Imunita proti ohni' title='Imunita proti ohni - Tato jednotka je imunní vůči některým kouzlům ohně a ohnivému štítu.'> ";
			} elseif ($nazevSchopnosti == SABOTAZ) {
				echo "<img src='images/sabotaz.gif' alt='Sabotáž' title='Sabotáž - Strategie na bitevním poli je velice důležitá a štěstí přeje připraveným. Jednotky, které ovládají tuto schopnost jsou velkým přínosem pro armádu. Na začátku bitvy se vplíží do nepřátelského tábora a sabotují nejsilnější z jednotek. Ta je pak v boji slabší a nezpůsobuje takové zranění.'> ";
			} elseif ($nazevSchopnosti == TEMNY_KRIK) {
				echo "<img src='images/temny_krik.png' alt='Temný křik' title='Temný křik - Jednotka v prvním kole přidá X až X*3 % doútoku, obrany, poškození a iniciativy nemrtvým jednotkám, kde X je počet sesilatelů.'> ";
			} elseif ($nazevSchopnosti == KANIBALIZMUS) {
				echo "<img src='images/kanibalizmus.jpg' alt='Kanibalizmus' title='Kanibalizmus - Jednotka požírá mrtvé a tím léčí sama sebe.'> ";
			} elseif ($nazevSchopnosti == VZKRISENI) {
				echo "<img src='images/neni.gif' alt='Vzkříšení' title='Vzkříšení - Je zde 66% šance na zaktivování kouzla, které oživí 50-100% padlých jednotek.'> ";
			} elseif ($nazevSchopnosti == NEPREDVIDATELNOST) {
				echo "<img src='images/neni.gif' alt='Nepředvídatelnost' title='Nepředvídatelnost - Jednotka dokáže zaútočit na jakoukoliv nepřátelskou jednotku na bojišti.'> ";
			} elseif ($nazevSchopnosti == MAGIE_ETERNANU and $hodnotaSchopnosti == 1) {
				echo "<img src='images/neni.gif' alt='Eternanská magie 1' title='Eternanská magie 1'> ";
			} elseif ($nazevSchopnosti == MAGIE_ETERNANU and $hodnotaSchopnosti == 2) {
				echo "<img src='images/neni.gif' alt='Eternanská magie 2' title='Eternanská magie 2'> ";
			} elseif ($nazevSchopnosti == "block") {
				echo "<img src='images/block.jpg' alt='Block $hodnotaSchopnosti' title='Block - Jednotka blokuje část obdrženého poškození. Snižuje obdržené zranění o $hodnotaSchopnosti%.'> ";
			} elseif ($nazevSchopnosti == VYVOLAVA_JEDNOTKU and $jednotka->nazev == "Gobliní Vzducholoď") {
				echo "<img src='images/goblini_vysadek.jpg' alt='Gobliní výsadek' title='Gobliní výsadek - Jednotka vyloží (počet*100) Gobliních paragánů.'> ";
			} elseif ($nazevSchopnosti == VYVOLAVA_JEDNOTKU and $jednotka->nazev == "Gobliní Hybridní Vzducholoď") {
				echo "<img src='images/goblini_vysadek.jpg' alt='Gobliní výsadek' title='Gobliní výsadek - Jednotka vyloží (počet*200) Gobliních paragánů.'> ";
			} elseif ($nazevSchopnosti == VYVOLAVA_JEDNOTKU and $jednotka->nazev == "Gobliní Vyztužená Vzducholoď") {
				echo "<img src='images/goblini_vysadek.jpg' alt='Gobliní výsadek' title='Gobliní výsadek - Jednotka vyloží (počet*400) Gobliních paragánů.'> ";
			} elseif ($nazevSchopnosti == "zed" and $hodnotaSchopnosti == 1) {
				echo "<img src='images/zed.jpg' alt='zeď' title='Jednoduše zeď - Nehybná ale stabilní'> ";
			} elseif ($nazevSchopnosti == SEBEVRAZEDNY and $hodnotaSchopnosti == 1) {
				echo "<img src='images/global_dmg.png' alt='globální poškození' title='Globální poškození - Jednotka při útoku zničí sama sebe.'> ";
			} elseif ($nazevSchopnosti == IMUNITA_LED) {
				echo "<img src='images/vulnerability_led.png' alt='Imunita Led' title='Imunita Led - Jednotka má " . $hodnotaSchopnosti . "% šanci na zablokování něterých kouzel ledu.'> ";
			} elseif ($nazevSchopnosti == "Letani") {
				echo "<img src='images/flying.jpg' alt='Létání' title='Létání - Jednotka umí létat.'> ";
			} elseif ($nazevSchopnosti == SILA_GOBLINU) {
				echo "<img src='images/goblin_power.jpg' alt='Síla goblinů' title='Síla goblinů - Posiluje všechny příslušníky rasy goblinů.'> ";
			} elseif ($nazevSchopnosti == KONSTRUKCE) {
				echo "<img src='images/neni.gif' alt='Konstrukce' title='Konstrukce - Každá jednotka vyrobí každé kolo 1-2 recyklované kočkodlaky.'> ";
			}
		}

		if (($jednotka->stav) == STAV_NEMRTVA) {
			echo "<img src='images/nemrtvi.png' alt='Nemrtvý' title='Nemrtvý - Jednotka je nemrtvá.'> ";
		} elseif (($jednotka->stav) == STAV_NEZIVA) {
			echo "<img src='images/nezivy.jpg' alt='Neživá' title='Neživá - Jednotka je neživá.'> ";
		}
		if (($jednotka->pocetUtoku) > 1) {
			echo "<img src='images/multi_dmg.jpg' alt='Multi Útok " . $jednotka->pocetUtoku . "' title='Multi Útok - Tato jednotka útočí " . $jednotka->pocetUtoku . " x za kolo.'> ";
		}
		echo "</td></tr>";
	}
	echo "</table><br>";

	function getLightMagicValue($jednotka){
		foreach ($jednotka->schopnosti->schopnost as $schopnost){
			if ($schopnost->nazev == "boostMagieSvetla")	return $schopnost->hodnota;
		}
	}
	?>
	<script src="sorttable.js" type="text/javascript"></script>
