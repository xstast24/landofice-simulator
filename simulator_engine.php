<?php

ini_set("display_errors", 1); //show PHP errors if they happen
error_reporting(E_ERROR | E_WARNING);

require_once 'src/common.php'; //import shared methods/utils


$utocnik = reformatArmyString($_POST['ut']);
$obrance = reformatArmyString($_POST['ob']);
//$cesta = $_POST['cesta'];


$cesta = "jednotky.xml";

$cas1 = explode(" ", microtime());

$cas1 = $cas1[1] + $cas1[0];

$rd = "10000"; /* zaokrouhlování */

function unique_random($Min, $Max, $num)
{

	if ($Min > $Max) {

		$min = $Max;

		$max = $Min;
	} else {

		$min = $Min;

		$max = $Max;
	}

	if ($num > ($max - $min)) $num = ($max - $min);

	$values = array();

	$result = array();

	for ($i = $min; $i <= $max; $i++) {

		$values[] = $i;
	}

	for ($j = 0; $j < $num; $j++) {

		$key = mt_rand(0, count($values) - 1);

		$result[] = $values[$key];

		unset($values[$key]);

		sort($values);
	}

	return $result;
}



if ($utocnik != "" and $obrance != "") {
	define("UTK", "#b0c4de");
	define("OBR", "white");

	if (!$xml = simplexml_load_file($cesta)) die("<span style='color:red'>Soubor se statistikami jednotek nebyl nalezen.</span>");

	class Jednotka
	{
		var $id, $ident, $nazev, $pocet, $dmg, $utk, $obr, $ziv, $ini, $hod, $typ, $stav, $pocetUtoku, $frakce, $schopnosti, $celkem_zivotu, $obdrzela_dmg, $poc_celkem_zivotu, $zkl_ini, $nahoda, $strana, $bojovala, $barva, $art, $popis_artu;

		#Konstruktor jednotek
		function __construct($id, $nazev, $pocet, $strana, $vyvolana, $barva, $art)
		{
			global $xml;
			$nazev = (trim($nazev));
			$this->id = $id * 1;
			$this->nazev = $nazev;
			$this->pocet = $pocet * 1;
			$this->vyvolana = $vyvolana * 1;
			$this->strana = $strana * 1;
			$this->barva = $barva;
			$this->art = $art;
			$this->bojovala = 0;
			$this->nahoda = rand(0, 1);

            foreach ($xml->jednotka as $jednotka) { //prochazi vsechny jednotky v xml, az narazi na stejne jmeno, nacte z ni hodnoty
                //porovnavani jmena ignoruje diakritiku a velikost pisma, pac jsou hrubky v eventech, napr. Sněžný / Snežny Obr (jinak v eventu, v klan army atp.)
                if (compareStringsIgnoringDiacriticsAndCase($jednotka->nazev, $nazev)) {
                    $this->nazev = $jednotka->nazev; //prepis nazev vlozeny userem na nazev z XML, ktery pak pouzivame vsude (i v hardcoded casech)
                    $this->ident = $jednotka->id * 1;
                    $this->dmg = $jednotka->damage * 1;
                    $this->utk = $jednotka->utok * 1;
                    $this->obr = $jednotka->obrana * 1;
                    $this->ziv = $jednotka->zivoty * 1;
                    $this->celkem_zivotu = $jednotka->zivoty * $pocet * 1;
                    $this->poc_celkem_zivotu = $jednotka->zivoty * $pocet * 1;
                    $this->obdrzela_dmg = 0;
                    $this->udelala_dmg = 0;
                    $this->zkl_ini = $jednotka->iniciativa * 1;
                    if ($this->nazev == "Meteorit") {
                        $this->zkl_ini = rand(10, 30);
                    }

                    $this->ini = $this->zkl_ini;
                    if ($this->vyvolana == 1 && ($this->nazev == "Ohnivá koule" or $this->nazev == "Ledová koule" or $this->nazev == "Rozžhavené magma" or $this->nazev == "Ohnivý déšť")) {
                        $this->ini = 1;
                    }

                    $this->hod = $jednotka->hodnota * 1;
                    $this->typUtoku = $jednotka->typUtoku * 1;
                    #stav 1 - zivy, 2 - nemrtvy, 3 - nezivy
                    $this->stav = $jednotka->stav * 1;
                    $this->pocetUtoku = $jednotka->pocetUtoku * 1;
                    #frakce: 0 - nezařazeno, 1 - Dralgar, 2 - vulkan, 3 - Aether, 4 - Dreadd, 5 - Dhar, 6 - Ghoro, 7 - crinis, 8 - ascendacy
                    $this->frakce = $jednotka->frakce * 1;

                    foreach ($jednotka->schopnosti->schopnost as $schopnost) {
                        $nazevSchopnosti = trim($schopnost->nazev);
                        $hodnotaSchopnosti = $schopnost->hodnota;
                        $schopnosti[$nazevSchopnosti] = $hodnotaSchopnosti;
                    }
                    $this->schopnosti = $schopnosti;
                    break;
                }
            }

			if ($this->celkem_zivotu == "") die("<span style='color:red'>Neznám jednotku " . $this->nazev . "</span>");

			switch ($art) {

				case "Prapor stínů":
					if ($this->stav == 2) {
						$this->utk++;
						$this->obr++;
					}

					$popis = "Nemrtvá jednotka získá +1 do útoku a obrany.";
					break;

				case "Amulet velitele":
					if ($this->stav == 1)
						$this->ini++;

					$popis = "Nenemrtvá a Neneživá jednotka získa +1 do iniciativy.";
					break;

				case "Pírko z anděla":
					if ($this->stav == 1)
						$this->ziv += 2;
					$popis = "Nenemrtvá a Neneživá jednotka získá +2 do životů.";

					break;

				case "Pírka z anděla":
					if ($this->stav == 1)
						$this->ziv += 7;

					$popis = "Nenemrtvá a Neneživá jednotka získá +7 do životů.";
					break;

				case "Popel padlých válečníků":
					$this->schopnosti["imunitaOhen"] = 1;
					$popis = "Unikátní jednotka je imunní proti ohnivému štítu.";
					break;

				case "Totem krve":
					if ($this->typUtoku == 1) {
						$this->dmg++;
						$this->ziv--;
					}

					$popis = "Jednotka s druhem útoku 1 získá +1 do damage a -1 od životů.";
					break;

				case "Prapor starého druidského cechu":
					if ($this->nazev == "Druid") {
						$this->schopnosti["vyvolavaJednotku"] = "Starodávný Ent";
						$popis = "Druidové přivolávají místo Entů Starodávné Enty.";
					} else if ($this->nazev == "Veledruid") {
						$this->schopnosti["staze"] = 50;
						$popis = "Veledruidové vyvolávají o 50% více Starodávných entů.";
					}
					break;

				case "Kostěná flétna":
					if ($this->nazev == "Lich" or $this->nazev == "Arcilich") {
						$this->schopnosti["staze"] = 6;
						$popis = "Lichové vyvolávají o 50% více kostlivců, Arcilichové vyvolávají o 50% více upírů.";
					}
					break;

				case "Čepec vyvolávače počasí":
					if ($this->typUtoku == 2 or $this->typUtoku == 3) {
						if (($this->dmg * 0.1) > 2) {
							$this->dmg *= 1.1;
						} else {
							$this->dmg += 2;
						}
						$popis = "Střelecká jednotka s druhem útoku 2 nebo 3 získá +10% do poškození (minimálně však 2).";
					}
					break;

				case "Kalich ohně":
					$this->schopnosti["ohnivyStit"] = 1;
					$popis = "Jednotka získá ohnivý štít 1.";
					break;

				case "Hůlka ohně":
					$this->schopnosti["ohnivyStit"] = 2;
					$popis = "Jednotka získá ohnivý štít 2.";
					break;

				case "Hůl ohně":
					$this->schopnosti["magieOhne"] = 1;
					$this->schopnosti["vyvolavaJednotku"] = "Ohnivá koule";
					$popis = "Unikátní jednotka se naučí magii ohně 1.";
					break;

				case "Dráp draka":
					$this->dmg *= 2;
					$popis = "Unikátní jednotka získá +100% do damage.";
					break;

				case "Kostěná hůl":
					$this->schopnosti["magieSmrti"] = 1;
					$popis = "Unikátní jednotka se naučí magii smrti 1.";
					break;

				case "Ohnivá zbroj":
					$this->schopnosti["ohnivyStit"] = $this->dmg * 2;
					$this->obr += 5;
					$popis = "Unikátní jednotka získá +5 do obrany a ohnivý štít rovnající se 2 násobku jeho damage.";
					break;

				case "Ledová čepel":
					$this->schopnosti["magieLedu"] = 1;
					$popis = "Unikátní jednotka se naučí magii ledu 1.";
					break;

				case "Plášť slabosti":
					$this->ziv = 1;
					$popis = "Unikátní jednotce klesne počet životů na 1.";
					break;

				case "Prapor světla":
					$this->schopnosti["stec"] += 0.5 * $this->dmg;
					if ($this->frakce == 7) {
						$this->utk = ceil($this->utk * 1.5);
						$this->ini = ceil($this->ini * 1.3);
					}

					$popis = "Nenemrtvá jednotka získá 50% damage do steče, pokud je Crinisina získává navíc 50% do útoku a 30% do iniciativy.";
					break;

				case "Prapor krve":
					if ($this->stav == 1) {
						if ($this->schopnosti["stec"] > 0) {
							$this->schopnosti["stec"] *= 3;
							$this->obr = 1;
						} else
							$this->schopnosti["stec"] = $this->dmg * 2;
						$this->obr = 1;
					}

					$popis = "Nenemrtvá a neneživá jednotka získá +200% damage do steče, ale jeji obrana klesne na 1.";
					break;

				case "Hole silového pole":
					$this->obr += 30;
					$popis = "Unikátní jednotka záská +30 do obrany.";
					break;

				case "Slonovinový luk":
					$this->typUtoku = 2;
					$popis = "Unikátní jednotce je změněn druh útoku na 2.";
					break;

				case "Meč paladina":
					$this->utk += 10;
					$this->dmg += 7;
					$this->schopnosti["magieSvetla"] = 1;
					$popis = "Unikátní jednotka získá +10 do útoku a +7 do damage, zároveň se naučí magii světla 1.";
					break;

				case "Druidský Rituál":
					if ($this->nazev == "Druid" or $this->nazev == "Nemrtvý druid") {
						$this->schopnosti["staze"] = 5;
						$popis = "Druidové vyvolávají o 50% více Entů.";
					}
					break;

				case "Hůlka ledu":
					$this->schopnosti["magieLedu"] = 1;
					$popis = "Jednotka se naučí magii ledu 1.";
					break;

				case "Prsten strážce":
					$this->obr += 20;
					$this->schopnosti["imunitaOhen"] = 1;
					$popis = "Unikátní jednotka získa +20 do obrany a je imuní vůči ohnivému štítu a bleskům.";
					break;

				case "Helma ledového válečníka":
					$this->obr += 5;
					$this->schopnosti["imunitaOhen"] = 1;
					$this->schopnosti["magieLedu"] = 2;
					$popis = "Unikátní jednotka získa +5 do obrany, je imuní vůči ohnivému štítu, naučí se magii ledu 2.";
					break;

				case "Boty rychlosti":
					$this->ini += 5;
					$popis = "Unikátní jednotka získa +5 do inciativy.";
					break;

				case "Prapor krvavého šílenství":
					$this->schopnosti["dav"] = 1;
					$popis = "Jednotka se naučí schopnost dav.";
					break;

				case "Ohnivá róba":
					$this->schopnosti["magieOhne"] = 3;
					$this->schopnosti["ohnivyStit"] = 500;
					$this->schopnosti["vyvolavaJednotku"] = "Meteorit";
					$popis = "Unikátní jednotka se naučí magii ohně 3 a získá ohnivý štít 500.";
					break;

				case "Ohnivý bič":
					$this->schopnosti["magieOhne"] = 1;
					$this->schopnosti["ohnivyStit"] = 700;
					$this->schopnosti["vyvolavaJednotku"] = "Ohnivá koule";
					$popis = "Unikátní jednotka se naučí magii ohně 1 a získá ohnivý štít 750.";
					break;

				case "Arianin svazek ohnivého mistrovství":
					$this->schopnosti["magieOhne"] = 4;
					$this->schopnosti["ohnivyStit"] = 1000;
					$this->schopnosti["vyvolavaJednotku"] = "Ohnivý přízrak";
					$popis = "Legendární jednotka se naučí magii ohně 4 a získá ohnivý štít 1000.";
					break;

				case "Měděné pláty":
					$this->obr += 1;
					$popis = "Jednotka získá +1 do obrany.";
					break;

				case "Bronzové pláty":
					$this->obr += 2;
					$popis = "Jednotka získá +2 do obrany.";
					break;

				case "Železné pláty":
					$this->obr += 4;
					$this->ini -= 1;
					$popis = "Jednotka získá +4 do obrany, ale její iniciativa klesne o 1.";
					break;

				case "Ocelové pláty":
					$this->obr += 6;
					$this->ini -= 1;
					$popis = "Jednotka získá +6 do obrany, ale její iniciativa klesne o 1.";
					break;

				case "Tvrzené ocelové pláty":
					$this->obr += 8;
					$this->ini -= 1;
					$popis = "Jednotka získá +8 do obrany, ale její iniciativa klesne o 1.";
					break;

				case "Pláty z Temné ocele":
					$this->obr += 11;
					$this->ini -= 2;
					$popis = "Jednotka získá +11 do obrany, ale její iniciativa klesne o 2.";
					break;

				case "Maska Královny medůz":
					$this->schopnosti["magieSmrti"] = 3;
					$this->schopnosti["magieZeme"] = 1;
					$popis = "Legendární jednotka získá Magii země 1 a Magii smrti 3.";
					break;

				case "Kouzelnická róba":
					$this->schopnosti["imunitaOhen"] = 1;
					$this->obr++;
					$popis = "Jednotka získá +1 do obrany a je imuní vůči ohnivému štítu.";
					break;

				case "Prokletá kouzelnická róba":
					if ($this->schopnosti["magieSmrti"] > 0) {
						$this->schopnosti["imunitaOhen"] = 1;
						$this->obr += 3;
						$popis = "Jednotka s Magí smrti získá +3 do obrany a je imuní vůči ohnivému štítu.";
					}
					break;

				case "Prapor berzekra":
					$this->schopnosti["dav"] = 1;
					$this->ini += 2;
					$this->obr *= 0.75;
					$this->utk *= 1.2;
					$popis = "Jednotka získa +2 do iniciativy, -25% do obrany, +20% do utoku a naučí se dav.";
					break;

				case "Aetherův ledový prapor":
					$this->schopnosti["magieLedu"] = 1;
					if ($this->frakce == 3) {
						$this->ini += 3;
						$this->schopnosti["magieLedu"] = 2;
					}

					$popis = "Jednotka získá schopnost Magie Ledu 1, Aethrova jednotka získa schopnost Magie Ledu 2 a navíc +3 do iniciativy";
					break;

				case "Ætherův ledový prapor":
					$this->schopnosti["magieLedu"] = 1;
					if ($this->frakce == 3) {
						$this->ini += 3;
						$this->schopnosti["magieLedu"] = 2;
					}

					$popis = "Jednotka získá schopnost Magie Ledu 1, Aethrova jednotka získa schopnost Magie Ledu 2 a navíc +3 do iniciativy";
					break;

				case "Vulkánův ohnivý prapor":
					if ($this->schopnosti["ohnivyStit"] > 0) {
						if ($this->frakce == 2)
							$this->schopnosti["ohnivyStit"] += $this->dmg;

						$this->schopnosti["ohnivyStit"] += 1;
					} else
						$this->schopnosti["ohnivyStit"] = 1;

					if ($this->schopnosti["magieOhne"] > 0 and $this->frakce == 2)
						$this->schopnosti["posileniOhen"] = 1;

					$popis = "Jednotka získá ohnivý štít +1 pokud je Vulkánova získá další bonus rovnající se hodnotě její damage do ohnivého štítu a posílení vlastní Magie Ohně.";
					break;

				case "Dharova kamenná standarta":
					$this->obr += 4;
					if ($this->frakce == 5)
						$this->obr += 6;

					$popis = "Jednotka dostane +4 do obrany pokud je Dharova získá dalších +6.";
					break;

				case "Dralgarův Totem života":
					if ($this->frakce == 1)
						$this->ziv *= 1.5;
					else
						$this->ziv *= 1.15;
					$popis = "Jednotka získá +15% do životů, pokud je Dralgarova získá dalších +35%.";
					break;

				case "Dreaddův prapor smrti":
					$this->obr += 2;
					$this->utk += 2;
					if ($this->frakce == 4) {
						$this->obr += 4;
						$this->utk += 4;
					}

					$popis = "Jednotka získá +2 do útoku a obrany pokud je Dreaddova získá dalších +4 do útoku a obrany.";
					break;

				case "Ghorova standarta s nabodnutou hlavou démona":
					if ($this->frakce == 6)
						$this->dmg *= 1.35;
					else
						$this->dmg *= 1.15;

					$popis = "Jednotka získá +15% do damage pokud je Ghorova získá dalších +35%.";
					break;

				case "Dehinatorův meč":
					$this->schopnosti["magieSmrti"] = 3;
					$this->schopnosti["imunitaOhen"] = 1;
					$this->schopnosti["exterminace"] = 1;
					$this->dmg *= 3;
					$popis = "Legendární Dehinatorova zbraň. Legendární jednotka získá Imunitu proti ohni, Magii Smrti 3, Exterminaci a její damage je zvýšen o 200%.";
					break;

				case "Srdce prokletého wurma Ragnarokka":
					$this->obr += 5;
					$this->utk += 5;
					$this->dmg *= 1.5;
					$this->ziv *= 1.5;
					$popis = "Nabito temnou magií z vesmíru Diabolus, poskytuje jednotce +5 útok, +5 obrana, +50% damage a +50% životů.";
					break;

				case "Jedovaté střely":
					if ($this->typUtoku == 2) {
						if ($this->schopnosti["jedovyUtok"] != 0)
							$this->schopnosti["jedovyUtok"] += 10;
						else
							$this->schopnosti["jedovyUtok"] = 10;
					}

					$popis = "Jednotka s druhem útoku 2 získá +10% do poškození proti živým cílům.";
					break;

				case "Drtivé střely":
					if ($this->typUtoku == 2) {
						if ($this->schopnosti["drtivyUtok"] != 0)
							$this->schopnosti["drtivyUtok"] += 10;
						else
							$this->schopnosti["drtivyUtok"] = 10;
					}

					$popis = "Jednotka s druhem útoku 2 získá +10% do poškození proti neživým cílům.";
					break;

				case "Posvěcené střely":
					if ($this->typUtoku == 2) {
						if ($this->schopnosti["svatyUtok"] != 0)
							$this->schopnosti["svatyUtok"] += 10;
						else
							$this->schopnosti["svatyUtok"] = 10;
					}

					$popis = "Jednotka s druhem útoku 2 získá +10% do poškození proti nemrtvým cílům.";
					break;

				case "Svazek ledového mistrovství":
					$this->schopnosti["magieLedu"] = 3;
					$this->schopnosti["ledovyStit"] = 30;
					$popis = "Unikátní jednotka se naučí magii ledu 3 a ledový štít 30%.";
					break;

				case "Ledová róba":
					$this->schopnosti["imunitaOhen"] = 1;
					$this->schopnosti["ledovyStit"] = 10;
					$popis = "Unikátní jednotka se naučí ledový štít 10% a je imuní proti ohnivému štítu.";
					break;

				case "Aethrova róba moci":
					$this->schopnosti["imunitaOhen"] = 1;
					$this->schopnosti["ledovyStit"] = 80;
					$this->schopnosti["magieLedu"] = 4;
					$this->obr += 42;
					$popis = "Unikátní jednotka získá +42 do obrany, ledový štít 80%, imunitu proti ohnivému štítu a naučí se Magii ledu 4 &#8211; Aethrův dotek.";
					break;

				case "Obranná palisáda":
					$this->obr += 20;
					$this->ini = 0;
					$popis = "Jednotka se skryje za obrannou palisádu. Získá +20 do obrany, ale její iniciativa klesne na 0.";
					break;

				case "Válečné bubny":
					if ($this->typUtoku == 1 and $this->stav == 1) {
						$this->ini++;
						$this->utk++;
						$this->obr -= 1;
					}
					$popis = "Válečné bubny posílí armádu. +1 iniciativa, +1 útok, -1 obrana. Pouze pro živé jednotky s druhem útoku 1";
					break;

				case "Plán bojiště":
					if ($this->typUtoku == 2 or $this->typUtoku == 3) {
						$this->ini++;
						$this->utk *= 1.2;
					}

					$popis = "Není nad to vědět, kam střílet. +20% do útoku, +1 do iniciativy pro jednotky s druhem útoku 2 a 3.";
					break;

				case "Ohnivá palisáda":
					$this->obr -= 1;
					$this->ini = 0;
					$this->schopnosti["ohnivyStit"] = 3;
					$popis = "Kdo chce na nás zaútočit musí proběhnout ohněm. Jednotka získá -1 do obrany, iniciativa je snížena na 0 a získá ohnivý štít 3.";
					break;

				case "Dalekohled":
					if ($this->typUtoku == 2 or $this->typUtoku == 3) {
						$this->ini -= 1;
						$this->utk += 3;
					}

					$popis = "Proč jednou při střelbě nezamířit? Jednotka získá -1 do ini, +3 do útoku. Pouze pro jednotky s druhem útoku 2 a 3.";
					break;

				case "Plášť Mucuse, krále toxických elementálů":
					$this->utk *= 1.2;
					$this->ini++;
					$this->dmg *= 1.25;
					$popis = "Plášť legendárního krále toxických elementálů, požene tvé jednotky do útoku. +20% do útoku, +1 inic, +25% do damage.";
					break;

				case "Posvátný popel":
					if ($this->schopnosti["vzkryseni"] > 0)
						$this->schopnosti["vzkryseni"] += 10;

					$popis = "Jednotky se schopností vzkříšení mají zvýšenou šanci na znovuvzkříšení o 10%.";
					break;

				case "Amulet věznitele":
					if ($this->schopnosti["magieSmrti"] == 5)
						$this->schopnosti["staze"] = 7;

					$popis = "Jednotka s magii smrti 5 vyvolá o 25% více uvěznených duší.";
					break;

				case "Santova čepice":
					$this->schopnosti["imunitaOhen"] = 1;
					$popis = "Štastné a veselé Vánoce.";
					break;

				case "Bronzový meč":
					$this->utk += 1;
					$popis = "Jednotka získá +1 do útoku.";
					break;

				case "Gladius":
					$this->utk += 2;
					$popis = "Jednotka získá +2 do útoku.";
					break;

				case "Rytířský jednoruční meč":
					$this->utk += 4;
					$popis = "Jednotka získá +4 do útoku.";
					break;

				case "Bastard":
					$this->utk += 7;
					$this->obr -= 2;
					$popis = "Jednotka získá +7 do útoku, ale její obrana klesne o 2.";
					break;

				case "Težká bojová sekyra":
					$this->utk += 10;
					$this->obr -= 3;
					$popis = "Jednotka získá +10 do útoku, ale její obrana klesne o 3.";
					break;

				case "Katana z Temné ocele":
					$this->utk += 8;
					$this->ini += 1;
					$popis = "Jednotka získá +8 do útoku a +1 do inicitivy.";
					break;

				case "Drtič lebek":
					$this->utk += 3;
					$this->ini -= 1;
					$this->schopnosti["slayer"] = 1;
					$popis = "Řemdih s kovovými koulemi. Jednotka získá +3 do útoku, -1 do inicitívy a naučí se schopnost slayer.";
					break;

				case "Kopí hlupáků":
					$this->schopnosti["exterminace"] = 1;
					$popis = "Unikátní jednotka získa schopnost exterminace.";
					break;

				case "Excalibur":
					$this->utk += 15;
					$this->obr += 10;
					$this->ini += 5;
					$popis = "Unikátní jednotka získá +15 do útoku, +10 do obrany a +5 do inicitivy.";
					break;

				case "Ohnivá dračí zbroj":
					$this->schopnosti["magieOhne"] = 7;
					$this->schopnosti["vyvolavaJednotku"] = "Stínový drak";
					$this->obr += 5;
					$this->dmg *= 4;
					$popis = "Unikátní jednotka získá +300% do poškození, +5 do obrany a naučí se Magii ohně 7";
					break;

				case "Prsten Života":
					$this->schopnosti["magieLesa"] = 2;
					$this->schopnosti["vyvolavaJednotku"] = "Wurm";
					$popis = "Unikátní jednotka získá Magii lesa 2";
					break;

				case "Maska Medůzy":
					$this->schopnosti["magieZeme"] = 1;
					$popis = "Unikátní jednotka získá Magii země 1";
					break;

				case "Amulet Beznaděje":
					$this->schopnosti["magieSmrti"] = 3;
					$popis = "Unikátní jednotka získá Magii smrti 3";
					break;

				case "Tutsumasa, Ledová dračí čepel":
					$this->schopnosti["magieLedu"] = 2;
					$this->dmg *= 4;
					$popis = "Unikátní jednotka získá +300% do damage a naučí se Magii ledu 2";
					break;

				case "Kostěná dračí hůl":
					$this->schopnosti["magieSmrti"] = 5;
					$this->schopnosti["vyvolavaJednotku"] = "Uvězněná duše";
					$this->dmg *= 4;
					$popis = "Unikátní jednotka získá +300% do damage a naučí se Magii smrti 5";
					break;

				case "Rukavice drtivé síly":
					if ($this->schopnosti["drtivyUtok"] != 0)
						$this->schopnosti["drtivyUtok"] += 100;
					else
						$this->schopnosti["drtivyUtok"] = 100;

					$popis = "JJednotka získá +100% do poškození proti neživým cílům.";
					break;

				case "Rukavice posvěcení":
					if ($this->schopnosti["svatyUtok"] != 0)
						$this->schopnosti["svatyUtok"] += 100;
					else
						$this->schopnosti["svatyUtok"] = 100;

					$popis = "Jednotka získá +100% do poškození proti nemrtvým cílům.";
					break;

				case "Mágova róba moci":
					$this->schopnosti["posileni_vyvolavani"] = 10;
					$popis = "Posílí přivolávací a vyvolávací magii jednotky o 10%.";
					break;

				case "Velemágova róba moci":
					$this->schopnosti["posileni_vyvolavani"] = 50;
					$popis = "Posílí přivolávací a vyvolávací magií jednotky o 50%.	";
					break;

				case "Gnomí vozík":
					$this->dmg *= 1.15;
					$this->obr += 1;
					if ($this->nazev == "Gobliní vzducholoď" or $this->nazev == "Gobliní Hybridní vzducholoď" or $this->nazev == "Gobliní Vyztužená vzducholoď")
						$this->schopnosti["staze"] = 8;

					$popis = "Jednotka získa +15% do poškození a +1 do obrany. Gobliní vzducholodě získávají navíc +50% na množství paragánů.";
					break;
			}

			$this->celkem_zivotu = $this->ziv * $this->pocet;
			$this->poc_celkem_zivotu = $this->ziv * $this->pocet;

			$this->popis_artu = $popis;

			$this->zaokrouhlit();
		}



		#Funce schopností jednotek

		#Štíty jednotek

		function ohnivyStit($obrance, $kill)
		{

			global $jednotka;
			global $zabito_goblinu;

			$dmg = $jednotka[$obrance]->schopnosti["ohnivyStit"] * ($jednotka[$obrance]->pocet + $kill);

			$pocet_ziv = $this->celkem_zivotu - $dmg;

			$zabito = ceil($this->celkem_zivotu / $this->ziv) - ceil($pocet_ziv / $this->ziv);

			if ($zabito > $this->pocet) $zabito = $this->pocet;

			$this->celkem_zivotu -= $dmg;

			$this->pocet -= $zabito;

			if ($this->isgoblin($this->frakce, $this->stav)) $zabito_goblinu += $zabito;

			//		if($this->pocet<0) $this->pocet=0; //nadbytecna, uz se testuje vyse

			echo "<span style='color:" . $this->barva . "'>Kolem jednotky " . $jednotka[$obrance]->toolNazev() . " vzplanul ohnivý štít a poranil útočníka za " . prevod($dmg) . ", zahynulo " . prevod($zabito) . " x " . $this->toolNazev() . "</span><br>";
		}



		function toxickyStit($obrance, $kill)
		{

			global $jednotka;
			global $zabito_goblinu;

			$dmg = $jednotka[$obrance]->schopnosti["toxickyStit"] * ($jednotka[$obrance]->pocet + $kill);

			$pocet_ziv = $this->celkem_zivotu - $dmg;

			$zabito = ceil($this->celkem_zivotu / $this->ziv) - ceil($pocet_ziv / $this->ziv);

			if ($zabito > $this->pocet) $zabito = $this->pocet;

			$this->celkem_zivotu -= $dmg;

			$this->pocet -= $zabito;

			if ($this->isgoblin($this->frakce, $this->stav)) $zabito_goblinu += $zabito;

			echo "<span style='color:" . $this->barva . "'>Kolem jednotky " . $jednotka[$obrance]->toolNazev() . " je toxický oblak, který zraňuje vše živé za " . prevod($dmg) . ", zahynulo " . prevod($zabito) . " x " . $this->toolNazev() . "</span><br>";
		}



		function ledovyStit($obrance)
		{

			global $jednotka;


			$this->ini -= max($this->ini * ($jednotka[$obrance]->schopnosti["ledovyStit"] / 100), 1);
			if ($this->ini < 0) $this->ini = 0;

			aktualizaceInic();

			$this->zaokrouhlit();

			echo "<span style='color:" . $this->barva . "'>Kolem jednotky " . $jednotka[$obrance]->toolNazev() . " je ledový štít, který spomalil útočníka o " . $jednotka[$obrance]->schopnosti["ledovyStit"] . "%</span><br>";
		}



		//-------------------Schopnosti---------------------------

		function dav()
		{

			$this->utk += $this->utk * $this->pocet / 100;

			$this->obr = 1;

			echo "<span style='color:" . $this->barva . "'>Jednotka " . $this->jmenoArt() . " nabírá rychlost! V tomto kole získala " . $this->pocet . "% bonus do útoku, zároveň je ale zranitelnější.</span><br><br>";
		}



		function sebevrazda()
		{

			$this->dmg /= 2;

			$this->schopnosti["viceutok"] -= 1;

			echo "<span style='color:" . $this->barva . "'>Mohutná exploze poničila vše. Po jednotce " . $this->jmenoArt() . " nezbylo skoro nic!</span><br>";
		}



		function finalniSebevrazda()
		{

			$this->pocet = 0;

			$this->celkem_zivotu = -1;

			echo "<span style='color:" . $this->barva . "'>Mohutná exploze poničila vše. Po jednotce " . $this->jmenoArt() . " nezbylo skoro nic!</span><br>";
		}



		function exterminace($obrance)
		{

			global $jednotka;

			$kill = $this->pocet;

			if ($kill > $jednotka[$obrance]->pocet) $kill = $jednotka[$obrance]->pocet;

			$jednotka[$obrance]->pocet -= $kill;

			$jednotka[$obrance]->celkem_zivotu -= $kill * $jednotka[$obrance]->ziv;

			echo "<span style='color:red'>" . $this->pocetJmenoArt() . " chladnokrevně zabila ze zálohy " . prevod($kill) . " x " . $jednotka[$obrance]->jmenoArt() . "! Zůstává " . $jednotka[$obrance]->pocetJmenoArt() . "</span><br>";
		}



		function sabotaz($obrance)
		{

			global $jednotka;

			$jednotka[$obrance]->dmg -= $this->pocet;

			if ($jednotka[$obrance]->dmg < 0) $jednotka[$obrance]->dmg = 0;

			echo "<span style='color:silver'>" . $this->jmenoArt() . " provedl sabotáž na zdroje nepřátelské jednotky " . $jednotka[$obrance]->jmenoArt() . "</span><br>";
		}



		function temnykrik()
		{
			global $jednotka;

			$poctar = $this->pocet;

			//vypocet podle http://landofice.com/wiki/index.php?title=Schopnosti
			$hranice1 = 5 * pow($poctar, 0.7);
			$hranice2 = 4.5 * pow($poctar, 0.8);
			$spodni_hranice = min($hranice1, $hranice2);
			$horni_hranice = max($hranice2, $hranice1);;
			$posileni = mt_rand($spodni_hranice, $horni_hranice);

			$id = 0;
			while ($jednotka[$id]) {
				if ($jednotka[$id]->stav == 2) {
					$jednotka[$id]->utk *= ($posileni / 100 + 1);
					$jednotka[$id]->obr *= ($posileni / 100 + 1);
					$jednotka[$id]->ini *= ($posileni / 100 + 1);
					$jednotka[$id]->dmg *= ($posileni / 100 + 1);
					$jednotka[$id]->zaokrouhlit();
					$poradi[$id]['ini'] = $jednotka[$id]->ini;
					$poradi[$id]['id'] = $jednotka[$id]->id;
				}
				$id++;
			}

			$posileni = number_format($posileni, 0, ".", ",");
			echo "<span style='color:" . $this->barva . "'>Bojovým polem se přehnal, jak obrovská tlaková vlna temný křik, který trhal uši živím a nemrtvé posiloval.<br> " . $this->jmenoArt() . " posílil nemrtvé o $posileni%</span><br><br>";
		}

		function isgoblin($frakce, $stav)
		{  //identifikace rasa ziveho goblina
			if ($frakce == 6 && $stav == 1)
				return true;
			else
				return false;
		}



		function silagoblinu()
		{

			global $jednotka;

			$posileni = $this->pocet * 1; //každý pán šelem zvedá dmg o 1%

			$id = 0;

			while ($jednotka[$id]) {

				if ($this->isgoblin($jednotka[$id]->frakce, $jednotka[$id]->stav)) {

					$jednotka[$id]->dmg *= ($posileni / 100 + 1);

					$jednotka[$id]->zaokrouhlit();
				}

				$id++;
			}

			$posileni = number_format($posileni, 0, ".", ",");
			echo "<span style='color:gray'>Pán šelem: Ghoro dej nám sílu. Ghoro dej nám sílu. Ghoro!!!<br> " . $this->jmenoArt() . " posílil příslušníky rasy goblinů o $posileni%</span><br><br>";
		}


		function vzkryseni($kill)
		{

			if (mt_rand(0, 100) <= $this->schopnosti["vzkryseni"]) {

				$kill = round($kill * (mt_rand(50, 100) / 100));

				$this->pocet += $kill;

				$this->celkem_zivotu += $kill * $this->ziv;

				if ($this->celkem_zivotu <= 0) $this->celkem_zivotu = $kill * $this->ziv;

				echo "<span style='color:gray'>$kill x " . $this->jmenoArt() . " znovu povstal!</span><br>";
			}
		}


		//kanibalizmus ghúlů a upírů
		function kanibalizmus($dmg)
		{

			$vyleceno_ghul = round($dmg * (mt_rand(0, 5) / 100));
			$vyleceno_upir = round($dmg * (mt_rand(3, 15) / 100));

			$vyleceno = 0;

			if ($this->ident == 684 /*Ghúl*/) {

				if (($this->celkem_zivotu + $vyleceno_ghul) > $this->poc_celkem_zivotu) $vyleceno_ghul = $this->poc_celkem_zivotu - $this->celkem_zivotu;

				$this->celkem_zivotu = $this->celkem_zivotu + $vyleceno_ghul;
				$this->obdrzela_dmg -= $vyleceno_ghul;
				$vyleceno = $vyleceno_ghul;
			}

			if ($this->ident == 685 /*Upír*/) {

				if (($this->celkem_zivotu + $vyleceno_upir) > $this->poc_celkem_zivotu) $vyleceno_upir = $this->poc_celkem_zivotu - $this->celkem_zivotu;

				$this->celkem_zivotu = $this->celkem_zivotu + $vyleceno_upir;
				$this->obdrzela_dmg -= $vyleceno_upir;
				$vyleceno = $vyleceno_upir;
			}


			//dopočítání celkového počtu jednotek ve stacku po oživení.
			$pom = ceil($this->celkem_zivotu / $this->ziv);
			$pom2 = $this->pocet; //původní počet před vyléčením
			$this->pocet = $pom;

			if ($vyleceno != 0) {
				echo "<span style='color:#778899'>Jednotka " . $this->toolNazev() . " začala požírat zbytky vnitřností po nepřátelské jednotce a podařilo se ji tím vyléčit " . $vyleceno . " života (" . ($pom - $pom2) . " x " . $this->toolNazev() . " znovu povstal)</span><br>";
			}
		}


		//------------------konec Schopnosti -----------------------------

		//---------------------Magie--------------------------------------

		#Magie Smrti

		function magieSmrti3($obrance)
		{

			global $jednotka;

			$jednotka[$obrance]->ini -= 5;

			aktualizaceInic();

			echo "<span style='color:gray'>Obrovský strach a beznaděj doslova zmrazil zasaženou jednotku. Její iniciativa byla sníženo o 5</span><br>";
		}



		function magieSmrti4($obrance)
		{

			global $jednotka;

			$jednotka[$obrance]->utk -= $this->pocet;

			$jednotka[$obrance]->obr -= $this->pocet;

			if ($jednotka[$obrance]->utk < 0) $jednotka[$obrance]->utk = 0;

			if ($jednotka[$obrance]->obr < 0) $jednotka[$obrance]->obr = 0;

			$jednotka[$obrance]->zaokrouhlit();

			echo "<span style='color:" . $this->barva . "'>Jednotka byla prokleta silnou kletbou...</span><br>";
		}



		function magieSmrti8($obrance)
		{

			global $jednotka;

			$koef = 0.5 * $this->pocet;

			if ($koef > 100) $koef = 100;

			$jednotka[$obrance]->dmg -= $jednotka[$obrance]->dmg * $koef / 100;

			$jednotka[$obrance]->obr -= $jednotka[$obrance]->obr * $koef / 100;

			$jednotka[$obrance]->utk -= $jednotka[$obrance]->utk * $koef / 100;

			$jednotka[$obrance]->zaokrouhlit();

			echo "<span style='color:" . $this->barva . "'>Jednotka " . $this->pocetJmenoArt() . " zakouzlila snížení bojeschopnosti na " . $jednotka[$obrance]->jmenoArt() . "</span><br>";
		}

		function magieSmrti11($obrance)
		{

			global $jednotka;

			//sníží se životy každé jednotky ve stacku nejméně však na 1
			$jednotka[$obrance]->ziv -= $this->pocet;
			if ($jednotka[$obrance]->ziv < 1) {
				$jednotka[$obrance]->ziv = 1;
				$jednotka[$obrance]->celkem_zivotu = $jednotka[$obrance]->pocet;
				$jednotka[$obrance]->poc_celkem_zivotu = $jednotka[$obrance]->pocet;
			} else
				//sníží se tak celkový počet životů jednotky
				$jednotka[$obrance]->celkem_zivotu -= ($jednotka[$obrance]->pocet * $this->pocet);
			//včetně počátečního počtu životů pro maximální uzdravení např. jednorožcem
			$jednotka[$obrance]->poc_celkem_zivotu -= ($jednotka[$obrance]->pocet * $this->pocet);

			//  $jednotka[$obrance]->zaokrouhlit();

			echo "<span style='color:" . $this->barva . "'>Jednotka byla prokleta silnou kletbou...</span><br>";
		}


		#Magie Země

		function magieZeme1($obrance)
		{

			global $jednotka;

			$kill = $this->pocet;

			if ($kill > $jednotka[$obrance]->pocet) $kill = $jednotka[$obrance]->pocet;

			$jednotka[$obrance]->pocet -= $kill;

			$jednotka[$obrance]->celkem_zivotu -= $kill * $jednotka[$obrance]->ziv;

			echo "<span style='color:red'>" . $this->pocetJmenoArt() . " proměnil " . prevod($kill) . " jednotek " . $jednotka[$obrance]->jmenoArt() . " na kámen! Zůstává " . $jednotka[$obrance]->pocetJmenoArt() . "</span><br>";
		}

		function magieZeme2()
		{

			global $jednotka;

			$posileni = $this->pocet * 1;

			$id = 0;

			while ($jednotka[$id]) {

				if ($jednotka[$id]->strana == $this->strana) {

					$jednotka[$id]->obr *= ($posileni / 100 + 1);
					$jednotka[$id]->zaokrouhlit();
				}

				$id++;
			}

			$posileni = number_format($posileni, 0, ".", ",");

			echo "<span style='color:" . $this->barva . "'>Povolávám sílu Matky Země. Matko, stůj při nás! (obrana všech vlastních jednotek posílena o $posileni%)</span><br><br>";
		}




		#Magie Světla

		function magieSvetla1($obrance)
		{

			global $jednotka;

			$kill = $this->pocet;

			if ($kill > $jednotka[$obrance]->pocet) $kill = $jednotka[$obrance]->pocet;

			$jednotka[$obrance]->pocet -= $kill;

			$jednotka[$obrance]->celkem_zivotu -= $kill * $jednotka[$obrance]->ziv;

			echo "<span style='color:#778899'>Oslníví paprsek, který vyslal " . $this->pocetJmenoArt() . " proměnil " . prevod($kill) . " nemrtvých " . $jednotka[$obrance]->jmenoArt() . " v prách! Zůstává " . $jednotka[$obrance]->pocetJmenoArt() . "</span><br>";
		}


		function magieSvetla5()
		{

			global $jednotka;

			$id = 0;

			while ($jednotka[$id]) {

				if ($jednotka[$id]->frakce == 7) {

					$jednotka[$id]->schopnosti["stec"] += 5 * $jednotka[$id]->dmg;

					$jednotka[$id]->ini++;

					$jednotka[$id]->zaokrouhlit();

					$poradi[$id]['ini'] = $jednotka[$id]->ini;

					$poradi[$id]['id'] = $jednotka[$id]->id;
				}

				$id++;
			}

			echo "<span style='color:" . $this->barva . "'>" . $this->jmenoArt() . ": Do boje svatí válečníci! Bojujte ve jménu dobra!</span><br><br>";
		}



		function svetlo($co)
		{

			echo "<span style='color:#778899'>" . $this->jmenoArt() . ": Povolávám sílu světla, posil $co</span><br><br>";
		}

		#Magie Ledu

		function magieLedu1($obrance)
		{

			if (mt_rand(0, 100) < 33) {

				global $jednotka;

				$jednotka[$obrance]->ini -= 1;

				aktualizaceInic();

				echo "<span style='color:gray'>Zasažená jednotka byla pokryta ledovými krystalky a její iniciativa snížena o 1</span><br>";
			}
		}


		function magieLedu2($obrance)
		{

			global $jednotka;

			$jednotka[$obrance]->ini -= 3;

			aktualizaceInic();

			echo "<span style='color:gray'>Jednotka " . $jednotka[$obrance]->toolNazev() . " byla pokryta ledovými krystalky a její iniciativa snížena o 3</span><br>";
		}



		function magieLedu4($obrance)
		{

			global $jednotka;

			$jednotka[$obrance]->ini -= 10;

			$jednotka[$obrance]->obr /= 2;

			aktualizaceInic();

			echo "<span style='color:gray'>Zasaženou jednotku prostoupil naprostý chlad. Vzduch kolem ní se proměnil v ledové krystalky...</span><br>";
		}



		function magieLedu5($obrance)
		{

			global $jednotka;

			$jednotka[$obrance]->schopnosti['ohnivyStit'] = 0;

			$jednotka[$obrance]->schopnosti['magieOhne'] = 0;

			echo "<span style='color:gray'>Zasaženou jednotku prostoupil magický chlad...</span><br>";
		}


		function magieLedu7()
		{

			global $jednotka;

			$id = 0;

			while ($jednotka[$id]) {

				if ($jednotka[$id]->strana != $this->strana) {


					if ($jednotka[$id]->stav == 1 and mt_rand(1, 100) > $jednotka[$id]->schopnosti["imunitaMagie"] and mt_rand(1, 100) > $jednotka[$id]->schopnosti["imunitaLed"]) {
						$jednotka[$id]->ini *= 0.8;
						$jednotka[$id]->zaokrouhlit();
					}

					aktualizaceInic();
				}

				$id++;
			}

			echo "<span style='color:" . $this->barva . "'>Bitevním polem se prohnala ledová smršť a zpomalila všechno živé</span><br><br>";
		}

		function magieLedu9($obrance)
		{

			if (mt_rand(0, 100) < 60) {

				global $jednotka;

				$jednotka[$obrance]->ini = min(round($jednotka[$obrance]->ini * 0.6), $jednotka[$obrance]->ini - 3);

				aktualizaceInic();

				echo "<span style='color:gray'>Zasažená jednotka byla pokryta ledovými krystalky a její iniciativa byla snížena o 60%, nejméně však o 3.</span><br>";
			}
		}


		#Magie Lesa

		function magieLesa4()
		{

			global $jednotka;
			global $poradi;
			$id = 0;
			$poradi = [];

			while ($jednotka[$id]) {
				if ($jednotka[$id]->strana == $this->strana) {
					$poradi[$id]['obdrzela_dmg'] = $jednotka[$id]->obdrzela_dmg;
					$poradi[$id]['id'] = $jednotka[$id]->id;
				}
				$id++;
			}

			seradit("obdrzela_dmg");  //seřadí podle dmg, kterou jednotka obdržela
			$a = 0;
			$vyleceno = 0;

			while ($a < count($jednotka)) {
				$id = $poradi[$a]['id'];

				if (
					$jednotka[$id]->strana == $this->strana and $jednotka[$id]->celkem_zivotu > 0 and $jednotka[$id]->id != $this->id and
					$jednotka[$id]->celkem_zivotu < $jednotka[$id]->poc_celkem_zivotu
				) { //jednotka nemůže léčit sama sebe
					$koeficient_pocet = 0;
					$koeficient_zivoty = 0;

					if ($this->ziv <= 100) {
						$koeficient_pocet = 0.05;
						$koeficient_zivoty = 1 / 2;
					} else if ($this->ziv <= 500) {
						$koeficient_pocet = 0.1;
						$koeficient_zivoty = 2 / 3;
					} else if ($this->ziv <= 1000) {
						$koeficient_pocet = 0.2;
						$koeficient_zivoty = 1;
					} else if ($this->ziv <= 2000) {
						$koeficient_pocet = 0.4;
						$koeficient_zivoty = 3 / 2;
					} else {
						$koeficient_pocet = 0.8;
						$koeficient_zivoty = 2;
					}

					$vyleceno = round($this->pocet * $this->ziv * $koeficient_zivoty  * pow($this->pocet, $koeficient_pocet));
					if (($jednotka[$id]->celkem_zivotu + $vyleceno) > $jednotka[$id]->poc_celkem_zivotu)
						$vyleceno = $jednotka[$id]->poc_celkem_zivotu - $jednotka[$id]->celkem_zivotu;

					$jednotka[$id]->celkem_zivotu = $jednotka[$id]->celkem_zivotu + $vyleceno;
					$jednotka[$id]->obdrzela_dmg -= $vyleceno;

					$pom = ceil($jednotka[$id]->celkem_zivotu / $jednotka[$id]->ziv);
					$pom2 = $jednotka[$id]->pocet; //původní počet před vyléčením
					$jednotka[$id]->pocet = $pom;

					if ($vyleceno != 0) {
						echo "<span style='color:#778899'>Jednotka " . $this->pocet . " x " . $this->toolNazev() .
							" vyléčila " . ($pom - $pom2) . " x " . $jednotka[$id]->toolNazev() . " (celkem vyléčeno: " . $vyleceno . " životů)</span><br>";
						break;
					}
				}

				$a++;
			}
		}

		function magieLesa5()
		{

			global $jednotka;
			global $poradi;
			$id = 0;
			$poradi = [];

			while ($jednotka[$id]) {

				if ($jednotka[$id]->strana == $this->strana) {
					$poradi[$id]['obdrzela_dmg'] = $jednotka[$id]->obdrzela_dmg;
					$poradi[$id]['id'] = $jednotka[$id]->id;
				}
				$id++;
			}

			seradit("obdrzela_dmg");  //seřadí podle dmg, kterou jednotka obdržela


			$a = 0;
			$vyleceno = 0;
			while ($a < count($jednotka)) {

				$id = $poradi[$a]['id'];

				if ($jednotka[$id]->strana == $this->strana and $jednotka[$id]->celkem_zivotu > 0 and $jednotka[$id]->id != $this->id and $jednotka[$id]->celkem_zivotu < $jednotka[$id]->poc_celkem_zivotu) { //jednotka nemůže léčit sama sebe

					$vyleceno = max(round($jednotka[$id]->poc_celkem_zivotu * 0.15 * $this->pocet), 1000);

					if (($jednotka[$id]->celkem_zivotu + $vyleceno) > $jednotka[$id]->poc_celkem_zivotu) $vyleceno = $jednotka[$id]->poc_celkem_zivotu - $jednotka[$id]->celkem_zivotu;

					$jednotka[$id]->celkem_zivotu = $jednotka[$id]->celkem_zivotu + $vyleceno;
					$jednotka[$id]->obdrzela_dmg -= $vyleceno;

					$pom = ceil($jednotka[$id]->celkem_zivotu / $jednotka[$id]->ziv);
					$pom2 = $jednotka[$id]->pocet; //původní počet před vyléčením
					$jednotka[$id]->pocet = $pom;

					if ($vyleceno != 0) {
						echo "<span style='color:#778899'>Jednotka " . $this->pocet . " x " . $this->toolNazev() . " vyléčila " . ($pom - $pom2) . " x " . $jednotka[$id]->toolNazev() . " (celkem vyléčeno: " . $vyleceno . " životů)</span><br>";
						break;
					}
				}
				$a++;
			}
		}


		function magiePrastarych3()
		{  //vyvolá kopii nejsilnější jednotky protistrany

			global $jednotka;
			global $poradi;
			$id = 0;
			$poradi = [];

			while ($jednotka[$id]) {

				if ($jednotka[$id]->strana != $this->strana) { //hledáme v seznamu jednotek protistrany
					$poradi[$id]['hod'] = $jednotka[$id]->hod; //tu s nejvyšší hodnotou
					$poradi[$id]['id'] = $jednotka[$id]->id;
				}
				$id++;
			}

			seradit("hod");  //seřadí podle hodnoty nepřátelské jednotky
			$a = 0;
			
			while ($a < count($jednotka)) {
				$id = $poradi[$a]['id'];

				if ($jednotka[$id]->strana != $this->strana and $jednotka[$id]->celkem_zivotu > 0 and $jednotka[$id]->schopnosti['unikatni'] != "1") { //vytvoří se kopie živé nepřátelské jednotky
					$index = count($jednotka);
					$nahoda = mt_rand(10, 36) / 10;
					//vyvola nejméně jednu jednotku nebo v rozmezi 50-600 hodnoty jednotky za kazdy svuj zivot 
					$vyvola = round((($this->celkem_zivotu * $nahoda)) / $jednotka[$id]->hod);

					//pokud má sílu k vyvolání nejsilnější jednotky, jinak bude zkoušet vyvolat druhou nejsilnější
					if ($vyvola != 0) {

						//je potřeba zařídit, aby šly dělat stínové jednotky stínových jednotek.
						if ($jednotka[$id]->vyvolana == 1)
							$nazev = str_replace("Stínový ", "", $jednotka[$id]->nazev);
						else $nazev = $jednotka[$id]->nazev;

						// stinove jednotky nemuzou donekonecna sumonovat nove stinove jednotky.
						if ($jednotka[$id]->schopnosti["magiePrastarych"] == 3) {
							$jednotka[$index] = new Jednotka($index, $nazev, $vyvola, $this->strana, 1, $this->barva, "");
							$jednotka[$index]->schopnosti["magiePrastarych"] = 0;
						} else
							$jednotka[$index] = new Jednotka($index, $nazev, $vyvola, $this->strana, 1, $this->barva, "");

						$jednotka[$index]->obr = 0;
						$jednotka[$index]->nazev = "Stínový " . $jednotka[$index]->nazev;

						//navýšení celkové hodnoty o hodnotu stínových jednotek
						global $global_hodnota;
						$global_hodnota[$this->strana] = $global_hodnota[$this->strana] + ($vyvola * $jednotka[$index]->hod);

						echo "<span style='color:" . $this->barva . "'>Jednotka " . $this->pocet . " x " . $this->toolNazev() . " započala krvavý rituál pro vyvolání stínové magie a stvořila celkem " . $vyvola . " x " . $jednotka[$index]->toolNazev() . "</span><br><br>";

						$this->pocet = round($this->pocet / 2);
						$this->celkem_zivotu = $this->ziv * $this->pocet;
						$this->obdrzela_dmg = $this->poc_celkem_zivotu - $this->celkem_zivotu;

						break;
					}
				}
				$a++;
			}
		}


		function magiePrastarych4()
		{

			global $jednotka;

			$posileni = $this->pocet;

			$id = 0;

			while ($jednotka[$id]) {

				if ($jednotka[$id]->frakce == $this->frakce) {

					$jednotka[$id]->utk *= ($posileni / 100 + 1);

					$jednotka[$id]->obr *= ($posileni / 100 + 1);

					$jednotka[$id]->dmg *= ($posileni / 100 + 1);

					$jednotka[$id]->zaokrouhlit();
				}

				$id++;
			}

			$posileni = number_format($posileni, 0, ".", ",");
			echo "<span style='color:" . $this->barva . "'>Bitevním polem se prohnal náznak temné magie. Pak oblohu zakryly temné mraky a když se rozestoupili na nebi se zjevil Rudý Měsíc, jako předzvěst krvavého dne jenž následoval.</span><br><br>";
		}


		//------------------konec Magie -------------------------------------------------




		#Magie Eternanů

		function magieEternanu2($obrance)
		{

			global $jednotka;

			$jednotka[$obrance]->dmg *= 0.75;

			$jednotka[$obrance]->zaokrouhlit();

			echo "<span style='color:#778899'>" . $this->toolNazev() . " dezorientovala protivníka (-25% do damage)</span><br>";
		}



		#Prasatará Magie

		function staze()
		{

			global $jednotka;

			$id = 0;

			while ($jednotka[$id]) {

				if ($jednotka[$id]->vyvolana == 1) {

					$jednotka[$id]->ini = 0;

					$poradi[$id]['ini'] = $jednotka[$id]->ini;

					$poradi[$id]['id'] = $jednotka[$id]->id;
				}

				$id++;
			}
		}



		#Neoficiální schopnosti BEGIN

		function magieVody1()
		{

			global $jednotka;

			$id = 0;

			while ($jednotka[$id]) {

				if ($jednotka[$id]->strana != $this->strana) {

					$jednotka[$id]->dmg *= 0.9;

					$jednotka[$id]->zaokrouhlit();
				}

				$id++;
			}

			echo "<span style='color:" . $this->barva . "'>Nad bojištěm se náhle objevila temná mračna, z nichž se spustil prudký déšť, <b>" . $this->pocetJmenoArt() . "</b> přivolal bouři.</span><br><br>";
		}



		function magieVody3($obrance)
		{

			global $jednotka;

			$kill = $this->pocet;

			if ($kill > $jednotka[$obrance]->pocet) $kill = $jednotka[$obrance]->pocet;

			$jednotka[$obrance]->pocet -= $kill;

			$jednotka[$obrance]->celkem_zivotu -= $kill * $jednotka[$obrance]->ziv;

			echo "<span style='color:red'>" . $this->pocetJmenoArt() . " stáhl pod hladinu " . prevod($kill) . " x " . $jednotka[$obrance]->jmenoArt() . "! Zůstává " . $jednotka[$obrance]->pocetJmenoArt() . "</span><br>";
		}



		function magieOhne17()
		{

			global $jednotka;

			$id = 0;

			while ($jednotka[$id]) {

				if ($jednotka[$id]->nazev == "Meteorit" or $jednotka[$id]->nazev == "Rozžhavené magma" or $jednotka[$id]->nazev == "Ohnivá koule") {

					$nahoda = mt_rand(5, 10) / 1000;

					$jednotka[$id]->utk += $jednotka[$id]->utk * $nahoda * $this->pocet / 100;

					$jednotka[$id]->zaokrouhlit();
				}

				$id++;
			}

			echo "<span style='color:" . $this->barva . "'>Jednotka " . $this->pocetJmenoArt() . " svou vírou posílila ohnivá kouzla.</span><br><br>";
		}

		#Neoficiální schopnosti END



		#Vypisování artů, počtu a názvů jednotek

		function toolNazev()
		{

			$text = "<table><tr><td>Damage</td><td>" . prevod($this->dmg) . "</td><td>Útok</td><td>" . prevod($this->utk) . "</td></tr><tr><td>Obrana</td><td>" . prevod($this->obr) . "</td><td>Životy</td><td>" . prevod($this->ziv) . "</td></tr><tr><td>Iniciativa</td><td>" . prevod($this->ini) . "</td><td>Hodnota</td><td>" . prevod($this->hod) . "</td></tr><tr><td>Typ útoku</td><td>" . prevod($this->typUtoku) . "</td><td>Stav</td><td>";

			if ($this->stav == 1) {
				$text .= "Živá";
			} elseif ($this->stav == 2) {
				$text .= "Nemrtvá";
			} else {
				$text .= "Neživá";
			}

			$text = $text . "</td></tr>";



			$i = 0;
			$e = 0;
			$n = "x";

			while ($i < 32) {

				switch ($i) {

					case 1:
						if ($this->schopnosti['stec'] > 0) {
							$n = "Steč";
							$zkr = 'stec';
							$e++;
						}
						break;

					case 2:
						if ($this->schopnosti['slayer'] == 1) {
							$n = "Slayer";
							$zkr = 'nic';
							$e++;
						}
						break;

					case 3:
						if ($this->schopnosti['magieLesa'] > 0) {
							$n = "Magie Lesa";
							$zkr = 'magieLesa';
							$e++;
						}
						break;

					case 4:
						if ($this->schopnosti['magieZeme'] > 0) {
							$n = "Magie Země";
							$zkr = 'magieZeme';
							$e++;
						}
						break;

					case 5:
						if ($this->schopnosti['magieLedu'] > 0) {
							$n = "Magie Ledu";
							$zkr = 'magieLedu';
							$e++;
						}
						break;

					case 6:
						if ($this->schopnosti['magieOhne'] > 0) {
							$n = "Magie Ohně";
							$zkr = 'magieOhne';
							$e++;
						}
						break;

					case 7:
						if ($this->schopnosti['magieSmrti'] > 0) {
							$n = "Magie Smrti";
							$zkr = 'magieSmrti';
							$e++;
						}
						break;

					case 8:
						if ($this->schopnosti['magieSvetla'] > 0) {
							$n = "Magie Světla";
							$zkr = 'magieSvetla';
							$e++;
						}
						break;

					case 9:
						if ($this->schopnosti['sebevrazedna'] > 0) {
							$n = "Sebevražedná";
							$zkr = 'nic';
							$e++;
						}
						break;

					case 10:
						if ($this->pocetUtoku > 1) {
							$n = "Multiútok ";
							$zkr = 'multiutok';
							$e++;
						}
						break;

					case 11:
						if ($this->schopnosti['ohnivyStit'] > 0) {
							$n = "Ohnivý štít";
							$zkr = 'ohnivyStit';
							$e++;
						}
						break;

					case 12:
						if ($this->schopnosti['ledovyStit'] > 0) {
							$n = "Ledový štít";
							$zkr = 'ledovyStit';
							$e++;
						}
						break;

					case 13:
						if ($this->schopnosti['toxickyStit'] > 0) {
							$n = "Toxický štít";
							$zkr = 'toxickyStit';
							$e++;
						}
						break;

					case 14:
						if ($this->schopnosti['drtivyUtok'] > 0) {
							$n = "Drtivý útok";
							$zkr = 'drtivyUtok';
							$e++;
						}
						break;

					case 15:
						if ($this->schopnosti['svatyUtok'] > 0) {
							$n = "Svatý útok";
							$zkr = 'svatyUtok';
							$e++;
						}
						break;

					case 16:
						if ($this->schopnosti['jedovyUtok'] > 0) {
							$n = "Jed";
							$zkr = 'jedovyUtok';
							$e++;
						}
						break;

					case 17:
						if ($this->schopnosti['dav'] > 0) {
							$n = "Dav";
							$zkr = 'nic';
							$e++;
						}
						break;

					case 18:
						if ($this->schopnosti['exterminace'] > 0) {
							$n = "Exterminace";
							$zkr = 'nic';
							$e++;
						}
						break;

					case 19:
						if ($this->schopnosti['imunitaOhen'] > 0) {
							$n = "Imunita na oheň";
							$zkr = 'nic';
							$e++;
						}
						break;

					case 20:
						if ($this->schopnosti['sabotaz'] > 0) {
							$n = "Sabotér";
							$zkr = 'nic';
							$e++;
						}
						break;

					case 21:
						if ($this->schopnosti['temnykrik'] > 0) {
							$n = "Temný křik";
							$zkr = 'nic';
							$e++;
						}
						break;

					case 22:
						if ($this->schopnosti['vzkryseni'] > 0) {
							$n = "Vzkříšení";
							$zkr = 'nic';
							$e++;
						}
						break;

					case 23:
						if ($this->schopnosti['staze'] == 1) {
							$n = "Prastará magie";
							$zkr = 'nic';
							$e++;
						}
						break;

					case 24:
						if ($this->schopnosti['magieEternanu'] > 0) {
							$n = "Eternanská magie";
							$zkr = 'magieEternanu';
							$e++;
						}
						break;

					case 25:
						if ($this->schopnosti['magieVody'] > 0) {
							$n = "Magie vody";
							$zkr = 'magieVody';
							$e++;
						}
						break;

					case 26:
						if ($this->schopnosti['imunitaMagie'] > 0) {
							$n = "Imunita proti Magii";
							$zkr = 'imunitaMagie';
							$e++;
						}
						break;

					case 27:
						if ($this->schopnosti['imunitaLed'] > 0) {
							$n = "Imunita proti Ledu";
							$zkr = 'imunitaLed';
							$e++;
						}
						break;

					case 28:
						if ($this->schopnosti['silaGoblinu'] > 0) {
							$n = "Síla goblinů";
							$zkr = 'silaGoblinu';
							$e++;
						}
						break;

					case 29:
						if ($this->schopnosti['konstrukce'] > 0) {
							$n = "Konstrukce";
							$zkr = 'konstrukce';
							$e++;
						}
						break;

					case 30:
						if ($this->schopnosti['kanibalizmus'] > 0) {
							$n = "Kanibalizmus";
							$zkr = 'kanibalizmus';
							$e++;
						}
						break;

					case 31:
						if ($this->schopnosti['magiePrastarych'] > 0) {
							$n = "Magie Prastarých";
							$zkr = 'magiePrastarych';
							$e++;
						}
						break;



						#při přidání nové nezapomenout nahoře zvednout čítač u case !

				}

				if ($n != "x") {

					$hodnotaSchopnosti = $this->schopnosti[$zkr];

					if ($zkr == "multiutok") $hodnotaSchopnosti = $this->pocetUtoku;

					if (($e % 2) != 0) {
						$text = $text . "<tr><td>$n</td><td>" . $hodnotaSchopnosti . "</td>";
					} else {
						$text = $text . "<td>$n</td><td>" . $hodnotaSchopnosti . "</td></tr>";
					}
				}

				$n = "x";

				$i++;
			}



			$tab = "DELAY, 10, BORDERSTYLE, 'dashed', PADDING, 0, FADEIN, 250, FONTWEIGHT, 'bold', OPACITY, 86";

			return "<span onmouseover=\"Tip('$text', $tab);\" onmouseout=\"UnTip()\"'>" . $this->nazev . "</span>";
		}



		function pocetJmenoArt()
		{

			$ret = prevod($this->pocet) . " x " . $this->toolNazev();

			if ($this->art) $ret .= $this->art();

			return $ret;
		}



		function jmenoArt()
		{

			$ret = $this->toolNazev();

			if ($this->art) $ret .= $this->art();

			return $ret;
		}



		function art()
		{

			$tab = "DELAY, 10, BORDERSTYLE, 'dashed', PADDING, 5, FADEIN, 250, FONTWEIGHT, 'bold', OPACITY, 86, WIDTH, 250";

			return "<span style='color:#bdb76b'> (<span onmouseover=\"Tip('" . $this->popis_artu . "', $tab);\" onmouseout=\"UnTip()\"'>" . $this->art . "</span>)</span>";
		}



		function vypsat()
		{

			return $this->pocetJmenoArt() . "<br>";
		}



		function nemoznoUtocit()
		{

			echo "<span style='color:" . $this->barva . "'>" . $this->toolNazev() . " nemá na koho útočit</span><br>";
		}



		function nemoznoHybat()
		{

			echo "<div><span style='color:" . $this->barva . "'>" . $this->toolNazev() . " se nemůže hýbat</span><br></div><br>";
		}



		function zaokrouhlit()
		{

			$this->ziv = round($this->ziv);

			//$this->celkem_zivotu=$this->ziv*$this->pocet;

			$this->dmg = round($this->dmg);

			$this->utk = round($this->utk);

			$this->obr = round($this->obr);

			$this->schopnosti["stec"] = round($this->schopnosti["stec"]);
		}



		function obnovitIni()
		{

			$this->nahoda = mt_rand(1, 99999999999) / 100000000000;

			$this->ini -= $this->nahoda;

			if ($this->vyvolana == 1 and $this->ini > 0.9 and $this->ini < 1.1 and $this->zkl_ini != 1) {

				$this->ini = $this->zkl_ini;
			}

			$this->ini += $this->nahoda;
		}


		function vypocetDMG($bonus)
		{
			global $id_obrance;
			global $jednotka;
			global $aktualniKolo;

			$damage = $this->dmg;

			$damage += $damage * $bonus / 100;

			if ($this->schopnosti["stec"] > 0 and $aktualniKolo == 3) $damage += $this->schopnosti["stec"];

			if ($this->utk >= $jednotka[$id_obrance]->obr) {
				$dmg = $this->pocet * $damage * (1 + (($this->utk - $jednotka[$id_obrance]->obr) / 100) * 4);
			} elseif ($this->utk < $jednotka[$id_obrance]->obr and $this->schopnosti["slayer"] == 0) {
				$dmg = $this->pocet * $damage * (1 + ($this->utk - $jednotka[$id_obrance]->obr) / 50);
			} else { //utoci jednotka se slayerem a utok je mensi nez obrana

				$dmg = $this->pocet * $damage;
			}

			if (($jednotka[$id_obrance]->obr - $this->utk) >= 25 and $this->schopnosti["slayer"] == 0) {
				//			$dmg = $this->pocet * 0.55;
				$dmg = $this->pocet * (round(mt_rand(1, 10)) / 10);
			}

			//nova schpnost minotaura zablokovat cast poskozeni
			if ($jednotka[$id_obrance]->schopnosti["block"] > 0) {
				$blocked_dmg = round($dmg * ($jednotka[$id_obrance]->schopnosti['block'] / 100)); //block je cele cislo v procentech, e.g. block 30
				echo "<span style='color:gray'> Jednotce ", $jednotka[$id_obrance]->nazev, " se úspěšně podařilo zablokovat ", $jednotka[$id_obrance]->schopnosti['block'], "% nepřátelského útoku ($blocked_dmg).<br>";
				$dmg -= $blocked_dmg;
			}

			return round($dmg);
		}


		function zabitych($dmg)
		{

			global $id_obrance;
			global $jednotka;

			$jednotka[$id_obrance]->celkem_zivotu -= $dmg;

			$kill = $jednotka[$id_obrance]->pocet - ceil($jednotka[$id_obrance]->celkem_zivotu / $jednotka[$id_obrance]->ziv);

			if ($kill > $jednotka[$id_obrance]->pocet) {

				$kill = $jednotka[$id_obrance]->pocet;
			}

			$jednotka[$id_obrance]->pocet -= $kill;

			return $kill;
		}



		function nekromancer($kill, $obr)
		{

			global $jednotka;

			//nejvýše se oživý počet zabitých jednotek
			$pocet_vyvolanych = ceil($kill * mt_rand(1, 99) / 100);

			//omezeni počtu oživených na max počet jednotek, které oživovali.		

			if ($this->schopnosti["magieSmrti"] == 1) {
				$pocet_vyvolanych = min($this->pocet * 5, $pocet_vyvolanych);
				//lich s kostěnou flétnou
				if ($this->schopnosti["staze"] == 6) $pocet_vyvolanych *= 1.5;
			}


			if ($this->schopnosti["magieSmrti"] == 2) {
				$pocet_vyvolanych = min($this->pocet * mt_rand(1, 2), $pocet_vyvolanych);
			}

			if ($this->schopnosti["magieSmrti"] == 9) {
				$pocet_vyvolanych = min($this->pocet * 5, $pocet_vyvolanych);
			}

			if ($this->schopnosti["magieSmrti"] == 12) {
				$pocet_vyvolanych = min($this->pocet, $pocet_vyvolanych);
				//arcilich s kostěnou flétnou
				if ($this->schopnosti["staze"] == 6) $pocet_vyvolanych *= 1.5;
			}

			$random = mt_rand(0, 100);

			if ($this->schopnosti["magieSmrti"] == 6 and $jednotka[$obr]->stav == 1 and $jednotka[$obr]->schopnosti["magieOhne"] == 1) $jednotka_nazev = "Nemrtvý ohnivý kouzelník";

			elseif ($this->schopnosti["magieSmrti"] == 6 and $jednotka[$obr]->stav == 1 and $jednotka[$obr]->schopnosti["magieOhne"] == 3) $jednotka_nazev = "Zvěstovatel soudného dne";

			elseif ($this->schopnosti["magieSmrti"] == 6 and $jednotka[$obr]->stav == 1 and $jednotka[$obr]->schopnosti["magieLedu"] > 0) $jednotka_nazev = "Nemrtvý ledový kouzelník";

			elseif ($this->schopnosti["magieSmrti"] == 6 and $jednotka[$obr]->stav == 1 and $jednotka[$obr]->schopnosti["magieLesa"] > 0) $jednotka_nazev = "Nemrtvý Druid";

			elseif ($this->schopnosti["magieSmrti"] == 6 and $jednotka[$obr]->stav == 1 and ($jednotka[$obr]->ziv >= 10000 or $jednotka[$obr]->nazev == "ledový obr" or $jednotka[$obr]->nazev == "prokletý obr")) $jednotka_nazev = "Obří kostlivec";

			elseif ($this->schopnosti["magieSmrti"] == 6 and $jednotka[$obr]->stav == 1 and $random <= 80) $jednotka_nazev = "Lich";

			elseif ($this->schopnosti["magieSmrti"] == 9 and $jednotka[$obr]->stav == 1) $jednotka_nazev = "Ghúl";

			elseif ($this->schopnosti["magieSmrti"] == 12 and $jednotka[$obr]->stav == 1) $jednotka_nazev = "Upír";

			elseif ($jednotka[$obr]->stav == 1) $jednotka_nazev = "Kostlivec";

			else $pocet_vyvolanych = 0;



			if ($pocet_vyvolanych > 0) {
				$index = count($jednotka);
				$jednotka[$index] = new Jednotka($index, $jednotka_nazev, $pocet_vyvolanych, $this->strana, 1, $this->barva, "");
				global $global_hodnota;
				$global_hodnota[$this->strana] = $global_hodnota[$this->strana] + ($pocet_vyvolanych * $jednotka[$index]->hod);

				echo "<span style='color:" . $this->barva . "'>Jednotka " . $this->pocetJmenoArt() . " vskřísila " . $jednotka[$index]->pocetJmenoArt() . "</span><br>";
			}
		}


		function vyvolat()
		{
			global $jednotka;
			$special = 0;
			global $zabito_goblinu;

			$od = "Jednotka";
			$co = "přivolala celkem";
			$jak = "";
			$jednotka_nazev = $this->schopnosti["vyvolavaJednotku"];
			switch ($jednotka_nazev) {
					# TODO pridat vyvolavani posvatneho obra do simulatoru
					# TODO predelat spravne pocty podle jednotky
					# TODO konstrukce
				case "Ent":					# Magie Lesa 1
					$pocet_vyvolanych = floor($this->celkem_zivotu / 180);
					if ($this->schopnosti["staze"] == 5) {
						$pocet_vyvolanych = round($pocet_vyvolanych * 1.5);
					}
					break;

				case "Wurm":				# Magie Lesa 2
					$pocet_vyvolanych = mt_rand(1, 6);
					break;

				case "Starodávný Ent":		# Magie Lesa 3
					$pocet_vyvolanych = round($this->celkem_zivotu / 169);
					if ($this->schopnosti["staze"] == 50) {
						$pocet_vyvolanych = round($pocet_vyvolanych * 1.5);
					}
					break;

				case "Kentaur":				# Magie Lesa 6
					break;	# TODO

				case "Ledová koule":		# Magie Ledu 3
					$od = "Na rukou jednotky";
					$co = "se vytvořily malé ledové koule, za okamžik už letí";
					$jak = "!";
					$pocet_vyvolanych = floor($this->pocet * (0.97 + rand(0, 10) / 100) / 2);
					break;

				case "Ledová hradba":		# Magie Ledu 6
					$pocet_vyvolanych = floor($this->celkem_zivotu / 250);
					break;

				case "Ohnivá koule":		# Magie ohně 1
					if ($this->nazev == "Mág ohně")
						$pocet_vyvolanych = floor($this->pocet * (0.91 + rand(0, 20) / 100) / 4);
					else if ($this->nazev == "Arcimág ohně")
						$pocet_vyvolanych = floor($this->pocet * (0.97 + rand(0, 10) / 100));
					else
						$pocet_vyvolanych = floor($this->pocet * (0.97 + rand(0, 10) / 100) / 2);

					if ($this->schopnosti["posileniOhen"] == 1)
						$pocet_vyvolanych *= 1.5;

					$od = "Na rukou jednotky";
					$co = "se vytvořili malé ohnivé koule, za okamžik už letí";
					$jak = " proti nepříteli!";
					break;

				case "Ohnivý imp":			# Magie ohně 2
					$pocet_vyvolanych = round($this->pocet * 2);

					if ($this->schopnosti["posileniOhen"] == 1)
						$pocet_vyvolanych *= 1.5;

					break;

				case "Meteorit":			# Magie ohně 3
					$pocet_vyvolanych = randround($this->pocet / 2);

					if ($this->schopnosti["posileniOhen"] == 1)
						$pocet_vyvolanych = randround($pocet_vyvolanych * 1.5);

					$od = "Nebesa zabarvila rudá barva jednotka";
					break;

				case "Ohnivý přízrak":		# Magie ohně 4
					$pocet_vyvolanych = randround(max($this->pocet * 0.5, 1));

					if ($this->schopnosti["posileniOhen"] == 1)
						$pocet_vyvolanych = randround($pocet_vyvolanych * 1.5);

					$co = "otevřela ohnivý průchod, kterým prošel";
					break;

				case "Rozžhavené magma":	# Magie ohně 5
					$pocet_vyvolanych = $this->pocet;

					if ($this->schopnosti["posileniOhen"] == 1)
						$pocet_vyvolanych = round($pocet_vyvolanych * 1.5);

					$co = "vrhnul proti nepříteli";
					$jak = "!";
					break;

				case "Sopka":				# Magie ohně 6
					$special = 1;
					$pocet_vyvolanych = 1;
					break;

				case "Stínový drak":		# Magie ohně 7
					$pocet_vyvolanych = mt_rand(0, 3);
					break;

				case "Stínový ohař":		# Magie ohně 8
					$pocet_vyvolanych = floor($this->celkem_zivotu / mt_rand(20, 55));

					if ($this->schopnosti["posileniOhen"] == 1)
						$pocet_vyvolanych = floor($pocet_vyvolanych * 1.5);

					break;

				case "Stínová bestie":		# Magie ohně 9
					$pocet_vyvolanych = floor($this->celkem_zivotu / mt_rand(20, 55));

					if ($this->schopnosti["posileniOhen"] == 1)
						$pocet_vyvolanych = floor($pocet_vyvolanych * 1.5);

					break;

				case "Ohnivý déšť":			# Magie ohně 10
					$poctar = $this->pocet;
					while ($poctar > 0) {
						$nahoda = mt_rand(30, 120);
						$pocet_vyvolanych += $nahoda;
						$poctar -= 1;
					}

					if ($this->schopnosti["posileniOhen"] == 1)
						$pocet_vyvolanych * 1.5;

					$pocet_vyvolanych = round($pocet_vyvolanych);
					$od = "Rudé mraky prostoupili Andela věčného ohne. Pak začala z nebe padat ohnivá smrt.";
					break;

				case "Uvězněná duše":
					if ($this->schopnosti["magieSmrti"] == 5)					# Magie Smrti 5
						$vyvolal = mt_rand(150, 400);
					else if ($this->schopnosti["magieSmrti"] == 10)				# Magie Smrti 10
						$vyvolal = mt_rand(300, 800);

					$poctar = $this->pocet;
					while ($poctar > 0) {
						$pocet_vyvolanych += $vyvolal;
						$poctar -= 1;
					}

					$od = "";
					$co = "povolal";
					break;

				case "Prokletý ent":		# Magie Smrti 7
					$pocet_vyvolanych = floor($this->celkem_zivotu / 150);
					if ($this->schopnosti["staze"] == 5)
						$pocet_vyvolanych = round($pocet_vyvolanych * 1.5);

					break;

				case "Goblin Paragán":		# Výsadek
					switch ($this->nazev) {
						case "Gobliní Vzducholoď":
							$pocet_vyvolanych = $this->pocet * 100;
							break;
						case "Gobliní Hybridní vzducholoď":
							$pocet_vyvolanych = $this->pocet * 200;
							break;
						case "Gobliní Vyztužená vzducholoď":
							$pocet_vyvolanych = $this->pocet * 400;
							break;
					}

					if ($this->schopnosti["staze"] == 8)
						$pocet_vyvolanych *= 1.5;

					$od = "Z";
					$co = "vyskákalo";
					break;

				case "Duše goblina":		# Magie smrti 13
					//počet duší, které zvládne povolat
					$pocet_vyvolanych = ($this->celkem_zivotu * (0.95 + rand(0, 10) / 100)) / 10; // pocet zivotov/10 +/- 5%
					//pokud má artefakt, muze jich povolat vic	
					if ($this->schopnosti["posileni_vyvolavani"] > 0) $pocet_vyvolanych = randround(($pocet_vyvolanych * ($this->schopnosti["posileni_vyvolavani"] + 100)) / 100);
					//pokud muze povolat vice dusi nez bylo zabito goblinu	
					if ($pocet_vyvolanych > $zabito_goblinu) $pocet_vyvolanych = $zabito_goblinu;
					//vyvolané duše odečíst od zabitých goblinů
					if ($pocet_vyvolanych > 0) {
						$zabito_goblinu -= $pocet_vyvolanych;
						$this->utk = round($this->utk / 2);
						$this->obr = round($this->obr / 2);
						$this->dmg = round($this->dmg / 2);
					}

					$od = "";
					$co = " provedl rituál smrti. Celkem se jim povedlo vytrhnout ze spáru smrti";
					$jak = " a poštvat je zpátky proti nepříteli.";
					break;

				case "Energetický služebník":			# Magie Prastarých 2
					$pocet_vyvolanych = $this->pocet * 10;
					break;

				case "Recyklovaný kočkodlak":
					$pocet_vyvolanych = mt_rand($this->pocet, $this->pocet * 2);
					break;

				case "Vlna tsunami":
					$pocet_vyvolanych = $this->pocet;
					$od = "Z moře se řítí obrovská masa vody jednotka";
					break;

					# TODO tohle asi bude reprezentovat ohnivou bouri - checknout ve starych jednotkach pred generovanim jake maji hodnoty buh ohne
				case "998":
					$special = 2;
					$pocet_vyvolanych = 1;
					break;
			}	// konec switche na vyvolavani

			$index = count($jednotka);

			if ($special == 0 and $pocet_vyvolanych > 0) {
				//posílení vyvolávací a přivolávací magie
				if ($this->schopnosti["posileni_vyvolavani"] > 0 and $this->schopnosti["magieSmrti"] != 13) $pocet_vyvolanych = randround(($pocet_vyvolanych * ($this->schopnosti["posileni_vyvolavani"] + 100)) / 100);

				echo "<span style='color:" . $this->barva . "'>";

				$jednotka[$index] = new Jednotka($index, $jednotka_nazev, $pocet_vyvolanych, $this->strana, 1, $this->barva, "");

				//započítání do celkové hodnoty jednotek
				global $global_hodnota;
				$global_hodnota[$this->strana] = $global_hodnota[$this->strana] + ($pocet_vyvolanych * $jednotka[$index]->hod);

				echo "$od " . $this->pocetJmenoArt() . " $co $pocet_vyvolanych x " . $jednotka[$index]->toolNazev() . "$jak</span><br>";
			} else if ($special == 1 and $pocet_vyvolanych > 0) {	#ohendluj sopku
				$magma = mt_rand(($this->pocet * 50), ($this->pocet * 250));
				$koule = mt_rand(($this->pocet * 200), ($this->pocet * 1500));
				$mete = mt_rand(($this->pocet * 2), ($this->pocet * 5));

				if ($this->schopnosti["posileniOhen"] == 1) {
					$magma = round($magma * 1.5);
					$koule = round($koule * 1.5);
					$mete = round($mete * 1.5);
				}

				if ($this->schopnosti["posileni_vyvolavani"] > 0) {
					$magma = round(($magma * ($this->schopnosti["posileni_vyvolavani"] + 100)) / 100);
					$koule = round(($koule * ($this->schopnosti["posileni_vyvolavani"] + 100)) / 100);
					$mete = round(($mete * ($this->schopnosti["posileni_vyvolavani"] + 100)) / 100);
				}

				global $global_hodnota;
				$jednotka[$index] = new Jednotka($index, "Meteorit", $mete, $this->strana, 1, $this->barva, "");
				$global_hodnota[$this->strana] = $global_hodnota[$this->strana] + ($mete * $jednotka[$index]->hod);

				$jednotka[$index + 1] = new Jednotka($index + 1, "Rozžhavené magma", $magma, $this->strana, 1, $this->barva, "");
				$global_hodnota[$this->strana] = $global_hodnota[$this->strana] + ($magma * $jednotka[$index + 1]->hod);

				$jednotka[$index + 2] = new Jednotka($index + 2, "Ohnivá koule", $koule, $this->strana, 1, $this->barva, "");
				$global_hodnota[$this->strana] = $global_hodnota[$this->strana] + ($koule * $jednotka[$index + 2]->hod);

				echo "<span style='color:" . $this->barva . "'>Země se roztrhla. K nebesům letí žhavá lává, oheň a kusy skal. <b>" . $this->toolNazev() .
					"</b> vytvořil sopku!<br>Sopka vrhla k nebesům " . prevod($magma) . " x " . $jednotka[$index + 1]->toolNazev() . ", " .
					prevod($mete) . " x " . $jednotka[$index]->toolNazev() . ", " . prevod($koule) . " x " . $jednotka[$index + 2]->toolNazev() . "</span><br><br>";
			} elseif ($pocet_vyvolanych > 0 and $special == 2) {
				$magma = mt_rand(10000, 20000);
				if ($this->schopnosti["posileniOhen"] == 1) $magma = round($magma * 1.5);
				if ($this->schopnosti["posileni_vyvolavani"] > 0) $magma = round(($magma * ($this->schopnosti["posileni_vyvolavani"] + 100)) / 100);

				$koule = mt_rand(100000, 500000);
				if ($this->schopnosti["posileniOhen"] == 1) $koule = round($koule * 1.5);
				if ($this->schopnosti["posileni_vyvolavani"] > 0) $koule = round(($koule * ($this->schopnosti["posileni_vyvolavani"] + 100)) / 100);

				$mete = mt_rand(100, 200);
				if ($this->schopnosti["posileniOhen"] == 1) $mete = round($mete * 1.5);
				if ($this->schopnosti["posileni_vyvolavani"] > 0) $mete = round(($mete * ($this->schopnosti["posileni_vyvolavani"] + 100)) / 100);

				global $global_hodnota;

				$jednotka[$index] = new Jednotka($index, "Meteorit", $mete, $this->strana, 1, $this->barva, "");
				$global_hodnota[$this->strana] = $global_hodnota[$this->strana] + ($mete * $jednotka[$index]->hod);

				$jednotka[$index + 1] = new Jednotka($index + 1, "Rozžhavené magma", $magma, $this->strana, 1, $this->barva, "");
				$global_hodnota[$this->strana] = $global_hodnota[$this->strana] + ($magma * $jednotka[$index + 1]->hod);

				$jednotka[$index + 2] = new Jednotka($index + 2, "Ohnivá koule", $koule, $this->strana, 1, $this->barva, "");
				$global_hodnota[$this->strana] = $global_hodnota[$this->strana] + ($koule * $jednotka[$index + 2]->hod);

				echo "<span style='color:" . $this->barva . "'>Rudá mračna zkázy zakryla celé bojiště. Z nebes se řítí žhavá lává, oheň a kusy skal. <b>" . $this->toolNazev() . "</b> vytvořil ohnivou bouři!<br>Ohnivá bouře vrhla na nepřítele " . prevod($magma) . " x " . $jednotka[$index + 1]->toolNazev() . ", " . prevod($mete) . " x " . $jednotka[$index]->toolNazev() . ", " . prevod($koule) . " x " . $jednotka[$index + 2]->toolNazev() . "</span><br><br>";
			}
		}	// konec funkce vyvolat



		function utokNa($obrance)
		{

			global $jednotka;
			global $aktualniKolo;
			$bonus = 0;
			global $zabito_goblinu;


			if ($this->schopnosti["drtivyUtok"] > 0 and $jednotka[$obrance]->stav == 3) {

				$bonus = $this->schopnosti["drtivyUtok"];

				echo "<span style='color:#778899'>Jednotka " . $this->jmenoArt() . " použíla drtivý útok a získala +" . $this->schopnosti["drtivyUtok"] . "% do poškození</span><br>";
			}

			if ($this->schopnosti["svatyUtok"] > 0 and $jednotka[$obrance]->stav == 2) {

				$bonus = $this->schopnosti["svatyUtok"];

				echo "<span style='color:#778899'>Jednotka " . $this->jmenoArt() . " použíla svatý útok a získala +" . $this->schopnosti["svatyUtok"] . "% do poškození</span><br>";
			}

			if ($this->schopnosti["jedovyUtok"] > 0 and $jednotka[$obrance]->stav == 1) {

				$bonus = $this->schopnosti["jedovyUtok"];

				echo "<span style='color:#778899'>Jednotka " . $this->jmenoArt() . " použíla jedový útok a získala +" . $this->schopnosti["jedovyUtok"] . "% do poškození</span><br>";
			}

			if ($aktualniKolo == 3 and $this->schopnosti["stec"] > 0) echo "<span style='color:" . $this->barva . "'>Jednotka " . $this->toolNazev() . " v plné rychlosti prošla skrz nepřátelskou lini (v tomto kole získala +" . prevod($this->schopnosti["stec"] * $this->pocet) . " do poškození)</span><br>";

			echo "<span style='color:" . $this->barva . "'>" . $this->pocetJmenoArt() . " útočí na " . $jednotka[$obrance]->pocetJmenoArt() . "</span><br>";

			$dmg = $this->vypocetDMG($bonus);

			$kill = $this->zabitych($dmg);

			// pokud umrel goblin, zvednout citac zabitych goblinu

			if ($this->isgoblin($jednotka[$obrance]->frakce, $jednotka[$obrance]->stav)) $zabito_goblinu += $kill;
			$jednotka[$obrance]->obdrzela_dmg += $dmg;

			//kolik jednotka udělala v aktuálním kole dmg
			$this->udelala_dmg = $dmg;

			echo "<span style='color:" . $this->barva . "'>" . $this->pocetJmenoArt() . " zmasakroval (" . prevod($dmg) . ") " . prevod($kill) . " x " . $jednotka[$obrance]->jmenoArt() . " (" . prevod($jednotka[$obrance]->celkem_zivotu) . ")</span><br>";

			if ($this->schopnosti["magieSmrti"] == 1 or $this->schopnosti["magieSmrti"] == 6 or $this->schopnosti["magieSmrti"] == 9) $this->nekromancer($kill, $obrance);

			//arcilichove ozivuje nejvýše počet zabitých/2
			if ($this->schopnosti["magieSmrti"] == 12) $this->nekromancer($kill / 2, $obrance);

			return $kill;
		}
	}



	function aktualizaceInic()
	{

		$id = 0;
		global $poradi;
		global $jednotka;

		while ($poradi[$id]) {

			$poradi[$id]['ini'] = $jednotka[$id]->ini;

			$poradi[$id]['id'] = $jednotka[$id]->id;

			$id++;
		}
	}

	function reformatMagic($magic){
		# method used to convert user input magic to code magic. Example: Magie svetla -> magieSvetla
		if (strcasecmp($magic, "magie světla") == 0) return "magieSvetla";
		if (strcasecmp($magic, "magie lesa") == 0) return "magieLesa";
		if (strcasecmp($magic, "magie smrti") == 0) return "magieSmrti";
		if (strcasecmp($magic, "magie ledu") == 0) return "magieLedu";
		if (strcasecmp($magic, "magie ohne") == 0) return "magieOhne";
	}



	function parsekJednotky($zdroj, $strana, $barva)
	{
		global $jednotka;
		$utocnici = explode("\n", $zdroj);
		$index = 0;

		while ($utocnici[$index]) {
			$bojovnik = explode(" x ", $utocnici[$index]);
			$pocetJednotek = $bojovnik[0];
			$pocetJednotek = trim(Str_Replace(",", "", $pocetJednotek));
			$nazevJednotky = $bojovnik[1];

			$artefact = false;
			$multimagic = false;
			$art = "";
			if (strpos($nazevJednotky, "(") !== false && strpos($nazevJednotky, ")") !== false) $artefact = true;
			if (strpos($nazevJednotky, "[") !== false && strpos($nazevJednotky, "]") !== false) $multimagic = true;

			if ($artefact && $multimagic){
				$tmp = explode("(", $nazevJednotky);
				$nazevJednotky = $tmp[0];

				$tmp = explode(")", $tmp[1]);
				$art = trim($tmp[0]);

				$tmp = Str_Replace(" [", "", $tmp[1]);
				$tmp = Str_Replace("[", "", $tmp);
				$tmp = Str_Replace("]", "", $tmp);

				$tmp = explode(" ", $tmp);
				$level = $tmp[2];
				$magic = reformatMagic($tmp[0] . " " . $tmp[1]);
			} else if ($artefact){
				$art = explode("(", $nazevJednotky);
				$nazevJednotky = $art[0];
				$art = trim(Str_Replace(")", "", $art[1]));
			} else if ($multimagic){
				$tmp = explode("[", $nazevJednotky);
				$nazevJednotky = $tmp[0];

				$magic = trim(Str_Replace("]", "", $tmp[1]));
				$magic = explode(" ", $magic);
				$level = $magic[2];
				$magic = reformatMagic($magic[0] . " " . $magic[1]);
			}

			if (similar_text("velitel klanu", $nazevJednotky) == 13) $nazevJednotky = "Generál starého impéria";

			if (empty($jednotka))
				$indexTridy = 0;
			else
				$indexTridy = count($jednotka);

			$jednotka[$indexTridy] = new Jednotka($indexTridy, $nazevJednotky, $pocetJednotek, $strana, 0, $barva, $art);

			if ($multimagic){
				$jednotka[$indexTridy]->schopnosti[$magic] = $level;

				//specialni pripady multimagie pro nektere jednotky
				if ($jednotka[$indexTridy]->nazev == "Flamekeeper Lord"){
					if ($jednotka[$indexTridy]->schopnosti[$magic] == "3"){
						$jednotka[$indexTridy]->schopnosti["vyvolavaJednotku"] = "Meteorit";
					} else if ($jednotka[$indexTridy]->schopnosti[$magic] == "4"){
						$jednotka[$indexTridy]->schopnosti["vyvolavaJednotku"] = "Ohnivý přízrak";
					}
				}
			}

			$index++;
		}
	}



	function seradit($orderby)
	{

		global $poradi;
		$sortarray = [];
		$val = "";
		foreach ($poradi as $val) {
			$sortarray[] = $val[$orderby];
		}

		array_multisort($sortarray, SORT_DESC, $poradi);
	}



	function seraditrandom()
	{

		global $poradi;

		shuffle($poradi);
	}

	function najit($nazevHodnoty, $hodnota)
	{

		global $poradi;
		global $jednotka;

		$a = 0;

		$id = $poradi[$a]['id'];

		while ($a < count($jednotka)) {

			if ($jednotka[$id]->$nazevHodnoty == $hodnota || $jednotka[$id]->celkem_zivotu <= 0) {
				$a++;
				$id = $poradi[$a]['id'];
			} else {
				$id = $poradi[$a]['id'];
				break;
			}
		}

		return $id;
	}





	function prevod($cislo)
	{

		return number_format($cislo, 0, ".", ",");
	}



	function magieSvetla()
	{

		$a = 0;
		global $jednotka;

		while ($jednotka[$a]) {

			if ($jednotka[$a]->schopnosti["magieSvetla"] > 1) {

				if ($jednotka[$a]->schopnosti["magieSvetla"] == 2)     $jednotka[$a]->obr *= 1.2;

				elseif ($jednotka[$a]->schopnosti["magieSvetla"] == 3) $jednotka[$a]->utk *= 1.2;

				elseif ($jednotka[$a]->schopnosti["magieSvetla"] == 4) $jednotka[$a]->ini *= 1.2;

				$jednotka[$a]->zaokrouhlit;
			}

			$a++;
		}
	}

	global $jednotka; //inicializovana nize v parseru jednotek

	parsekJednotky($utocnik, 1, UTK);

	parsekJednotky($obrance, -1, OBR);


	$a = 0;
	while ($jednotka[$a]) {

		$poradi[$a]['ini'] = $jednotka[$a]->ini;

		$poradi[$a]['celkem_zivotu'] = $jednotka[$a]->celkem_zivotu;

		$poradi[$a]['id'] = $jednotka[$a]->id;

		$strana = $jednotka[$a]->strana;

		if ($strana == 1) {
			$ataker .= $jednotka[$a]->vypsat();
			$hodnota[$strana] += $jednotka[$a]->pocet * $jednotka[$a]->hod;
		} elseif ($strana == -1) {
			$defender .= $jednotka[$a]->vypsat();
			$hodnota[$strana] += $jednotka[$a]->pocet * $jednotka[$a]->hod;
		}

		$a++;
	}

	$global_hodnota[1] = $hodnota[1];

	$global_hodnota[-1] = $hodnota[-1];



	echo "<div style='color:" . UTK . "'>$ataker</div>";

	echo "<div style='color:" . OBR . "'>$defender</div>";



	$pocetKol = 5;

	$aktualniKolo = 1;

	$zabito_goblinu = 0; //pocet zabitych goblinich jednotek

	// echo "<span style='color:".$this->barva."'>".$zabito_goblinu." zabitogoblinu</span><br>";


	while ($aktualniKolo <= $pocetKol) {

		echo "<br><hr color='#c0c0c0'><h3>kolo $aktualniKolo</h3>";

		$index = 0;

		magieSvetla();

		while ($jednotka[$index]) {

			aktualizaceInic(); //tohle tu musí byt, nevím proč, bez toho neseřadí správně dle ini

			seradit("ini");

			$id = "";

			$id = najit("bojovala", 1);

			if ($id == "") $id = 0;



			//Jednotka ještě nebojovala?

			if ($jednotka[$id]->bojovala == 0) {

				$aktualniUtok = 0; //počet útoků, které jednotka v tomto kole provedla

				//Má jednotka kladný počet životů a současně to není hradba
				if ($jednotka[$id]->celkem_zivotu > 0 && ($jednotka[$id]->dmg > 0 or $jednotka[$id]->stav != 3)) {



					//Má jednotka dostatek iniciativy?
					if ($jednotka[$id]->ini < 0.85) $jednotka[$id]->nemoznoHybat();

					else {

						//Vyvolávání jednotek

						//vyvolání pouze v prvním kole
						if ($aktualniKolo == 1 and $jednotka[$id]->nazev != "Anděl věčného ohně" and $jednotka[$id]->nazev != "Železný kněz" and $jednotka[$id]->nazev != "Zlobří Šaman") $jednotka[$id]->vyvolat(); //anděl vyvolává déšť až od 2. kola, kněží taky
						//vyvolávání v ostatních kolech -energ. služebník, vulcanovo kouzlo, ohnivý dést, rec. kockodlak,duse goblina
						if ($aktualniKolo != 1 and ($jednotka[$id]->schopnosti["vyvolavaJednotku"] == "Energetický služebník" or $jednotka[$id]->schopnosti["vyvolavaJednotku"] == "998" or $jednotka[$id]->schopnosti["vyvolavaJednotku"] == "Ohnivý déšť" or $jednotka[$id]->schopnosti["vyvolavaJednotku"] == "Duše goblina")) $jednotka[$id]->vyvolat();




						//Schopnosti prováděné před samotným útokem

						if ($aktualniKolo == 1) {

							if ($jednotka[$id]->schopnosti["dav"] == 1) $jednotka[$id]->dav();

							if ($jednotka[$id]->schopnosti["temnykrik"] == 1) $jednotka[$id]->temnykrik();

							if ($jednotka[$id]->schopnosti["silaGoblinu"] == 1) $jednotka[$id]->silaGoblinu();

							if ($jednotka[$id]->schopnosti["magieSvetla"] == 5) $jednotka[$id]->magieSvetla5();

							if ($jednotka[$id]->schopnosti["magieLedu"] == 7) $jednotka[$id]->magieLedu7();

							if ($jednotka[$id]->schopnosti["magieVody"] == 1) $jednotka[$id]->magieVody1();

							if ($jednotka[$id]->schopnosti["magieZeme"] == 2) $jednotka[$id]->magieZeme2();

							if ($jednotka[$id]->schopnosti["magieOhne"] == 17) $jednotka[$id]->magieOhne17();

							if ($jednotka[$id]->schopnosti["magiePrastarych"] == 4) $jednotka[$id]->magiePrastarych4();
						}

						if (($aktualniKolo == 1 or $aktualniKolo == 2) and $jednotka[$id]->schopnosti["magiePrastarych"] == 3) {
							$jednotka[$id]->magiePrastarych3();
							//			$aktualniUtok = 1; //krvavý rituál se pokládá za útok
						}

						if (($aktualniKolo == 1 or $aktualniKolo == 2) and $jednotka[$id]->schopnosti["staze"] == 1) $jednotka[$id]->staze();

						if ($jednotka[$id]->schopnosti["magieSvetla"] == 2) $jednotka[$id]->svetlo("naše zbroje");

						elseif ($jednotka[$id]->schopnosti["magieSvetla"] == 3) $jednotka[$id]->svetlo("naše zbraně");

						elseif ($jednotka[$id]->schopnosti["magieSvetla"] == 4) $jednotka[$id]->svetlo("naši rychlost");



						//Může jednotka provést útok v tomto kole ?

						if (($aktualniKolo == 1 and ($jednotka[$id]->typUtoku == 4 or $jednotka[$id]->typUtoku == 3)) or ($aktualniKolo == 2 and ($jednotka[$id]->typUtoku == 4 or $jednotka[$id]->typUtoku == 3 or $jednotka[$id]->typUtoku == 2)) or (($aktualniKolo == 3 or $aktualniKolo == 4 or $aktualniKolo == 5) and ($jednotka[$id]->typUtoku == 4 or $jednotka[$id]->typUtoku == 1 or $jednotka[$id]->typUtoku == 2))) {



							$opak_strany =  $jednotka[$id]->strana;

							//			  $aktualniUtok = 0;

							while ($aktualniUtok < $jednotka[$id]->pocetUtoku and $jednotka[$id]->celkem_zivotu > 0) {


								$idx = 0;

								while ($jednotka[$idx]) {

									$poradi[$idx]['ini'] = $jednotka[$idx]->ini;

									$poradi[$idx]['celkem_zivotu'] = $jednotka[$idx]->celkem_zivotu;

									$poradi[$idx]['id'] = $jednotka[$idx]->id;
									$idx++;
								}


								//Seřadit obránce dle počtu životů

								seradit("celkem_zivotu");

								$id_obrance = najit("strana", $opak_strany);

								echo "<div>";

								//chaoticka hydra utoci  nepredvidatelnosti
								if ($jednotka[$id]->schopnosti["nepredvidatelnost"] == 1) {

									seraditrandom();

									$id_obrance = najit("strana", $opak_strany);
								}

								if ($jednotka[$id]->ini < 0.85) $jednotka[$id]->nemoznoHybat();

								else {


									//Je zde na koho útočit?

									if ($jednotka[$id_obrance]->celkem_zivotu <= 0) {

										//pokud jednotka má schopnost léčit, bude léčit i když nemá na koho útočit
										if ($jednotka[$id]->schopnosti["magieLesa"] == 4) $jednotka[$id]->magieLesa4();
										if ($jednotka[$id]->schopnosti["magieLesa"] == 5) $jednotka[$id]->magieLesa5();

										$jednotka[$id]->nemoznoUtocit();
									} else {

										//proběhne utok, vše co se má zakouzlit před ním je třeba dát nad toto
										$zabito = $jednotka[$id]->utokNa($id_obrance);

										if ($jednotka[$id]->schopnosti["sabotaz"] == 1) $jednotka[$id]->sabotaz($id_obrance);

										if ($jednotka[$id]->schopnosti["magieEternanu"] == 2 and mt_rand(1, 100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"]) $jednotka[$id]->magieEternanu2($id_obrance);

										if ($jednotka[$id]->schopnosti["magieSmrti"] == 4 and $jednotka[$id_obrance]->stav == 1 and mt_rand(1, 100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"]) $jednotka[$id]->magieSmrti4($id_obrance);

										elseif ($jednotka[$id]->schopnosti["magieSmrti"] == 8 and mt_rand(1, 100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"]) $jednotka[$id]->magieSmrti8($id_obrance);

										if ($jednotka[$id]->schopnosti["magieSmrti"] == 11 and $jednotka[$id_obrance]->stav == 1 and mt_rand(1, 100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"]) $jednotka[$id]->magieSmrti11($id_obrance);


										if ($jednotka[$id]->schopnosti["magieSmrti"] == 3 and $jednotka[$id_obrance]->stav == 1 and mt_rand(1, 100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"]) $jednotka[$id]->magieSmrti3($id_obrance);

										if ($jednotka[$id]->schopnosti["magieLedu"] == 1 and mt_rand(1, 100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"] and mt_rand(1, 100) > $jednotka[$id_obrance]->schopnosti["imunitaLed"]) $jednotka[$id]->magieLedu1($id_obrance);

										elseif ($jednotka[$id]->schopnosti["magieLedu"] == 2 and mt_rand(1, 100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"] and mt_rand(1, 100) > $jednotka[$id_obrance]->schopnosti["imunitaLed"]) $jednotka[$id]->magieLedu2($id_obrance);

										elseif ($jednotka[$id]->schopnosti["magieLedu"] == 4 and mt_rand(1, 100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"] and mt_rand(1, 100) > $jednotka[$id_obrance]->schopnosti["imunitaLed"]) $jednotka[$id]->magieLedu4($id_obrance);

										elseif ($jednotka[$id]->schopnosti["magieLedu"] == 5 and mt_rand(1, 100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"] and mt_rand(1, 100) > $jednotka[$id_obrance]->schopnosti["imunitaLed"]) $jednotka[$id]->magieLedu5($id_obrance);

										if ($jednotka[$id]->schopnosti["kanibalizmus"] != 0 and $jednotka[$id_obrance]->stav == 1) $jednotka[$id]->kanibalizmus($jednotka[$id]->udelala_dmg);

										if ($jednotka[$id]->schopnosti["vyvolavaJednotku"] == 902)  $jednotka[$id]->vyvolat();




										if ($jednotka[$id_obrance]->schopnosti["ohnivyStit"] > 0 and $jednotka[$id]->schopnosti["imunitaOhen"] != 1 and $jednotka[$id]->schopnosti["ohnivyStit"] == "" and $jednotka[$id]->typUtoku == 1) $jednotka[$id]->ohnivyStit($id_obrance, $zabito);

										if ($jednotka[$id_obrance]->schopnosti["toxickyStit"] > 0 and $jednotka[$id]->schopnosti["toxickyStit"] == "" and $jednotka[$id]->typUtoku == 1 and $jednotka[$id]->stav == 1) $jednotka[$id]->toxickyStit($id_obrance, $zabito);

										if ($jednotka[$id_obrance]->schopnosti["ledovyStit"] > 0 and $jednotka[$id]->schopnosti["ledovyStit"] == "" and $jednotka[$id]->typUtoku == 1) $jednotka[$id]->ledovyStit($id_obrance);

										if ($zabito > 0 and $jednotka[$id_obrance]->schopnosti["vzkryseni"]) $jednotka[$id_obrance]->vzkryseni($zabito);

										if ($jednotka[$id]->schopnosti["exterminace"] == 1 and $jednotka[$id_obrance]->stav < 3) $jednotka[$id]->exterminace($id_obrance);

										if ($jednotka[$id]->schopnosti["magieZeme"] == 1 and mt_rand(1, 100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"]) $jednotka[$id]->magieZeme1($id_obrance);

										if ($jednotka[$id]->schopnosti["magieVody"] == 3 and mt_rand(1, 100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"]) $jednotka[$id]->magieVody3($id_obrance);

										if ($jednotka[$id]->schopnosti["magieSvetla"] == 1 and $jednotka[$id_obrance]->stav == 2) $jednotka[$id]->magieSvetla1($id_obrance);

										if ($jednotka[$id]->schopnosti["sebevrazedna"] and $jednotka[$id]->schopnosti["viceutok"] > 1 and ($jednotka[$id_obrance]->celkem_zivotu <= 0 or $jednotka[$id_obrance]->obdrzela_dmg >= $jednotka[$id_obrance]->poc_celkem_zivotu)) {

											$jednotka[$id_obrance]->obdrzela_dmg = $jednotka[$id_obrance]->poc_celkem_zivotu - $jednotka[$id_obrance]->celkem_zivotu; //když meteorit zničí fenixe a ti se ozivi, musi se zase "vynulovat" obdrzena dmg

											$aktualniUtok -= 1;

											$jednotka[$id]->sebevrazda();
										} elseif ($jednotka[$id]->schopnosti["sebevrazedna"]) $jednotka[$id]->finalniSebevrazda();

										//pokud jednotka má schopnost léčit, bude léčit poté, co zaútočí (pokud je naživu)
										if ($jednotka[$id]->schopnosti["magieLesa"] == 4 && $jednotka[$id]->celkem_zivotu > 0) $jednotka[$id]->magieLesa4();
										if ($jednotka[$id]->schopnosti["magieLesa"] == 5 && $jednotka[$id]->celkem_zivotu > 0) $jednotka[$id]->magieLesa5();
									} //konec podmínky, kdy jednotka má na koho utocit


								} //konec podmínky když má jednotka dostatek inic pro útok

								echo "</div><br>";



								$aktualniUtok++;
							}
						}
					}
				}

				//$index=-1;

			}

			if ($jednotka[$id]->bojovala == 0) $jednotka[$id]->bojovala = 1;



			while ($jednotka[$a]) {

				$poradi[$a]['ini'] = $jednotka[$a]->ini;

				$poradi[$a]['id'] = $jednotka[$a]->id;

				$a++;
			}



			$index++;
		}



		$a = 0;

		$zmena = [];

		while ($jednotka[$a]) {

			$jednotka[$a]->bojovala = 0;

			$jednotka[$a]->obnovitIni();

			$poradi[$a]['ini'] = $jednotka[$a]->ini;

			$poradi[$a]['id'] = $jednotka[$a]->id;

			$strana = $jednotka[$a]->strana;

			if ($strana == 1) {
				$zmena[$strana] += $jednotka[$a]->pocet * $jednotka[$a]->hod;
			} elseif ($strana == -1) {
				$zmena[$strana] += $jednotka[$a]->pocet * $jednotka[$a]->hod;
			}

			$a++;
		}

		if ($aktualniKolo != 5) {

			echo "<br>Zprávy z bojiště:<br><div style='color:#C0C0C0'>Ztratili jsme za poslední kolo <span style='color:#B22222'>" . prevod($hodnota[1] - $zmena[1]) . "</span> (" . number_format((100 * ($hodnota[1] - $zmena[1])) / $global_hodnota[1], 2, '.', ' ') . "%) hodnoty armády. Zůstává nám " . prevod($zmena[1]) . " (" . number_format((100 * $zmena[1]) / $global_hodnota[1], 2, '.', ' ') . "%)<br> Protivníkovy jsme za poslední kolo zničili <span style='color:#B22222'>" . prevod($hodnota[-1] - $zmena[-1]) . "</span> (" . number_format((100 * ($hodnota[-1] - $zmena[-1])) / $global_hodnota[-1], 2, '.', ' ') . "%) hodnoty armády. Protivníkovy zůstává " . prevod($zmena[-1]) . " (" . number_format((100 * $zmena[-1]) / $global_hodnota[-1], 2, '.', ' ') . "%)</div><br>";
		}



		$hodnota[1] -= $hodnota[1] - $zmena[1];

		$hodnota[-1] -= $hodnota[-1] - $zmena[-1];



		$aktualniKolo++;
	}

	$a = 0;
	$hodnota = [];
	$ataker = "";
	$defender = "";

	while ($jednotka[$a]) {

		$strana = $jednotka[$a]->strana;

		if ($strana == 1) {
			if ($jednotka[$a]->vyvolana == 1) {
				$ataker .= "<div style='color:#708090'>";
				$hodnota[$strana] += $jednotka[$a]->pocet * $jednotka[$a]->hod;/*započítáme hodnotu i když je vyvolaná*/
			} else {
				$ataker .= "<div>";
				$hodnota[$strana] += $jednotka[$a]->pocet * $jednotka[$a]->hod;
			}
			$ataker .= $jednotka[$a]->vypsat();
			$ataker .= "</div>";
		} elseif ($strana == -1) {
			$defender .= $jednotka[$a]->vypsat();
			$hodnota[$strana] += $jednotka[$a]->pocet * $jednotka[$a]->hod;
		}

		$a++;
	}


	echo "--------------------------------------------------------------<br><br>";

	$utocnikCelkemHodnota = $global_hodnota[1];
	$utocnikPreziloHodnota = $hodnota[1];
	$utocnikZabitoHodnota = $utocnikCelkemHodnota - $utocnikPreziloHodnota;
	$utocnikZtratyProcento = 100 * $utocnikZabitoHodnota / $utocnikCelkemHodnota; //0 = vsichni zivi, 100 = vsichni mrtvi
	echo "<div id='vysledkyUtocnik' style='color:#b0c4de' hodnotaCelkem='$utocnikCelkemHodnota' hodnotaPrezilo='$utocnikPreziloHodnota'>"; //atributy uchovavaji hodnotu, aby byla snadno dostupna odjinud (javascript)
	echo "Útočník přežilo:<br>";
	echo "<div style='color:" . UTK . "'>$ataker</div>";
	echo "Celkem hodnota zabité armády: " . prevod($utocnikZabitoHodnota) . "/" . prevod($utocnikCelkemHodnota) . " (" . number_format($utocnikZtratyProcento, 2, ',', ' ') . "%)<br><br>";
	echo "</div>";

	$obranceCelkemHodnota = $global_hodnota[-1];
	$obrancePreziloHodnota = $hodnota[-1];
	$obranceZabitoHodnota = $obranceCelkemHodnota - $obrancePreziloHodnota;
	$obranceZtratyProcento = 100 * $obranceZabitoHodnota / $obranceCelkemHodnota;
	echo "<div id='vysledkyObrance' hodnotaCelkem='$obranceCelkemHodnota' hodnotaPrezilo='$obrancePreziloHodnota'>"; //atributy uchovavaji hodnotu, aby byla snadno dostupna odjinud (javascript)
	echo "Obránce přežilo:<br>";
	echo "<div style='color:" . OBR . "'>$defender</div>";
	echo "Celkem hodnota zabité armády: " . prevod($obranceZabitoHodnota) . "/" . prevod($obranceCelkemHodnota) . " (" . number_format($obranceZtratyProcento, 2, ',', ' ') . "%)<br><br>";
	echo "</div>";


	echo "<center>
            <input type='button' id='tlacitkoBojDole' onClick=\"ajaxFunction()\" value=\"BOJ\" class='boj'>
            <input type='button' id='tlacitkoOpakovaneSimulaceDole' onClick=\"opakovanaSimulace(20)\" value=\"20x\" class='boj'>
          </center><br>";

	$cas2 = explode(" ", microtime());
	echo "<center>" . (round((($cas2[1] + $cas2[0]) - $cas1) * $rd)) / $rd . "s</center>";
}
