<?php

ini_set("display_errors", 0); //nepouzivejte v PHP4

error_reporting(E_ERROR | E_WARNING);

$utocnik = $_POST['ut'];

$obrance = $_POST['ob'];

//$cesta = $_POST['cesta'];



$cesta="jednotky.xml";

$cas1 = explode(" ", microtime());

$cas1 = $cas1[1] + $cas1[0];

$rd = "10000"; /* zaokrouhlování */

//nahodne zaokrouhlování při vyvolávání jednotek např.ohnivého přízraku
function randround($cislo){
    $nahoda = mt_rand(0,100);
    if($nahoda<50) return floor($cislo); else return round($cislo); 
  }

function unique_random($Min,$Max,$num){

  if ($Min>$Max) {

    $min=$Max;

    $max=$Min;

    }

  else {

    $min=$Min;

    $max=$Max;

    }

  if ($num>($max-$min)) $num=($max-$min);

  $values=array();

  $result=array();

  for ($i=$min;$i<=$max;$i++) {

    $values[]=$i;

    }

  for ($j=0;$j<$num;$j++){

    $key=mt_rand(0,count($values)-1);

    $result[]=$values[$key];

    unset($values[$key]);

    sort($values);

    }

  return $result;

  }



if($utocnik!="" and $obrance!=""){

define ("UTK", "#b0c4de");

define ("OBR", "white");

if(!$xml = simplexml_load_file($cesta)) die("<span style='color:red'>Soubor se statistikami jednotek nebyl nalezen.</span>");



class Jednotka {

	var $id, $ident, $nazev, $pocet, $dmg, $utk, $obr, $ziv, $ini, $hod, $typ, $stav, $pocetUtoku, $frakce, $schopnosti, $celkz, $obdrzela_dmg, $poc_celkz, $zkl_ini, $nahoda, $strana, $bojovala, $barva, $art, $popis_artu;



	#Konstruktor jednotek

	function Jednotka($id, $nazev, $pocet, $strana, $vyvolana, $barva, $art) {

		global $xml; $nazev=(trim($nazev));
		
		$this->id = $id * 1;

		$this->nazev = $nazev;

		$this->pocet = $pocet*1;

		$this->vyvolana = $vyvolana * 1;

		$this->strana = $strana * 1;

		$this->barva = $barva;

		$this->art = $art;

		$this->bojovala = 0;

		$this->nahoda = mt_rand(0,1);

		foreach ($xml->jednotka as $jednotka){

			if(trim(strtolower($jednotka->nazev)) == trim(strtolower($nazev))) {
				
				$this->ident = $jednotka->id * 1;
				
				$this->dmg = $jednotka->damage * 1;

				$this->utk = $jednotka->utok * 1;

				$this->obr = $jednotka->obrana * 1;

				$this->ziv = $jednotka->zivoty * 1;

				$this->celkz = $jednotka->zivoty * $pocet * 1;
				
				$this->poc_celkz = $jednotka->zivoty * $pocet * 1;
				
				$this->obdrzela_dmg = 0;
				
				$this->udelala_dmg = 0;

				$this->zkl_ini = $jednotka->iniciativa * 1;

				if($this->zkl_ini == 0){

					$this->zkl_ini = mt_rand(10,30);

					}

				$this->ini = $this->zkl_ini;

   			if($this->vyvolana==1 && ($this->nazev == "Ohnivá koule" or $this->nazev == "Ledová koule" or $this->nazev == "Rozžhavené magma" or $this->nazev == "Ohnivý déšť" )) $this->ini=1;

				$this->hod = $jednotka->hodnota * 1;

				$this->typ = $jednotka->typUtoku * 1;

				#stav 1 - zivy, 2 - nemrtvy, 3 - nezivy
				$this->stav = $jednotka->stav * 1;

				$this->pocetUtoku = $jednotka->pocetUtoku * 1;
				
				#frakce: 1 - Dralgar, 2 - vulkan, 3 - Aether, 4 - Dreadd, 5 - Dhar, 6 - Ghoro, 7 - crinis, 8 - ascendacy
				$this->frakce = $jednotka->frakce * 1;

				foreach ($jednotka->schopnosti->schopnost as $schopnost){

					$nazevSchopnosti = trim($schopnost->nazev);

					$hodnotaSchopnosti = $schopnost->hodnota * 1;

					$schopnosti[$nazevSchopnosti] = $hodnotaSchopnosti;

					}

				$this->schopnosti = $schopnosti;break;

				}

			}

		if($this->celkz=="") die("<span style='color:red'>Neznám jednotku ".$this->nazev."</span>");

		switch($art){

			case "Prapor stínů": if($this->stav == 2) {$this->utk++;$this->obr++;}$popis = "Nemrtvá jednotka získá +1 do útoku a obrany.";break;

			case "Amulet velitele": if($this->stav == 1) {$this->ini++;}$popis = "Nenemrtvá a Neneživá jednotka získa +1 do iniciativi.";break;

			case "Pírko z anděla": if($this->stav == 1) {$this->ziv+=2;}$popis = "Nenemrtvá a Neneživá jednotka získá +2 do životů.";break;

			case "Popel padlých válečníků": $this->schopnosti["imunitaOhen"]=1;$popis = "Unikátní jednotka je imunní proti ohnivému štítu.";break;

			case "Totem krve": if($this->typ == 1) {$this->dmg++;$this->ziv--;}$popis = "Jednotka s druhem útoku 1 získá +1 do damage a -1 od životů.";break;

			case "Prapor starého druidského cechu": if($this->schopnosti["vyvolavaJednotku"] == 242){$this->schopnosti["vyvolavaJednotku"]=66;$popis = "Druidové přivolávají místo Entů Starodávné Enty.";}else if ($this->nazev == "Veledruid"){$this->schopnosti["staze"]=50;$popis = "Veledruidové vyvolávají o 50% více Starodávných entů.";}break;
			
			case "Kostěná flétna": if($this->nazev == "Lich" or $this->nazev == "Arcilich"){$this->schopnosti["staze"]=6;$popis = "Lichové vyvolávají o 50% více kostlivců, Arcilichové vyvolávají o 50% více upírů.";}break;

			case "Čepec vyvolávače počasí": if($this->typ == 2 or $this->typ == 3){if(($this->dmg*0.1)>2){$this->dmg*=1.1;}else{$this->dmg+=2;}$popis = "Střelecká jednotka s druhem útoku 2 nebo 3 získá +10% do poškození (minimálně však 2).";}break;

			case "Kalich ohně": $this->schopnosti["ohnivyStit"]=1;$popis = "Jednotka získá ohnivý štít 1.";break;

			case "Hůlka ohně": $this->schopnosti["ohnivyStit"]=2;$popis = "Jednotka získá ohnivý štít 2.";break;

			case "Hůl ohně": $this->schopnosti["magieOhne"]=1;$this->schopnosti["vyvolavaJednotku"]=7;$popis = "Unikátní jednotka se naučí magii ohně 1.";break;

			case "Dráp draka": $this->dmg*=2;$popis = "Unikátní jednotka získá +100% do damage.";break;

			case "Kostěná hůl": $this->schopnosti["magieSmrti"]=1;$popis = "Unikátní jednotka se naučí magii smrti 1.";break;

			case "Ohnivá zbroj": $this->schopnosti["ohnivyStit"]=$this->dmg*2;$this->obr+=5;$popis = "Unikátní jednotka získá +5 do obrany a ohnivý štít rovnající se 2 násobku jeho damage.";break;

			case "Ledová čepel": $this->schopnosti["magieLedu"]=1;$popis = "Unikátní jednotka se naučí magii ledu 1.";break;

			case "Plášť slabosti": $this->ziv=1;$popis = "Unikátní jednotce klesne počet životů na 1.";break;

			case "Prapor světla": $this->schopnosti["stec"]+=0.5*$this->dmg;if($this->frakce==7){$this->utk*=1.5;$this->ini*=1.3;}$popis = "Nenemrtvá jednotka získá 50% damage do steče, pokud je Crinisina získává navíc 50% do útoku a 30% do iniciativy.";break;

			case "Prapor krve": if($this->stav == 1) {if($this->schopnosti["stec"]>0){$this->schopnosti["stec"]*=3;$this->obr=1;} else $this->schopnosti["stec"]=$this->dmg*2;$this->obr=1;}$popis = "Nenemrtvá a neneživá jednotka získá +200% damage do steče, ale jeji obrana klesne na 1.";break;

			case "Hole silového pole": $this->obr+=30;$popis = "Unikátní jednotka záská +30 do obrany.";break;

			case "Slonovinový luk": $this->typ=2;$popis = "Unikátní jednotce je změněn druh útoku na 2.";break;

			case "Meč paladina": $this->utk+=10;$this->dmg+=7;$this->schopnosti["magieSvetla"]=1;$popis = "Unikátní jednotka získá +10 do útoku a +7 do damage, zároveň se naučí magii světla 1.";break;

			case "Druidský Rituál": if($this->nazev == "Druid" or $this->nazev == "Nemrtvý druid"){$this->schopnosti["staze"]=5;$popis = "Druidové vyvolávají o 50% více Entů.";}break;

			case "Hůlka ledu": $this->schopnosti["magieLedu"]=1;$popis = "Jednotka se naučí magii ledu 1.";break;

			case "Prsten strážce": $this->obr+=20;$this->schopnosti["imunitaOhen"]=1;$popis = "Unikátní jednotka získa +20 do obrany a je imuní vůči ohnivému štítu a bleskům.";break;

			case "Helma ledového válečníka": $this->obr+=5;$this->schopnosti["imunitaOhen"]=1;$this->schopnosti["magieLedu"]=2;$popis = "Unikátní jednotka získa +5 do obrany, je imuní vůči ohnivému štítu, naučí se magii ledu 2.";break;

			case "Boty rychlosti": $this->ini+=5;$popis = "Unikátní jednotka získa +5 do inciativy.";break;

			case "Prapor krvavého šílenství": $this->schopnosti["dav"]=1;$popis = "Jednotka se naučí schopnost dav.";break;

			case "Ohnivá róba": $this->schopnosti["magieOhne"]=3;$this->schopnosti["ohnivyStit"]=500;$this->schopnosti["vyvolavaJednotku"]=17;$popis = "Unikátní jednotka se naučí magii ohně 3 a získá ohnivý štít 500.";break;

			case "Ohnivý bič": $this->schopnosti["magieOhne"]=1;$this->schopnosti["ohnivyStit"]=700;$this->schopnosti["vyvolavaJednotku"]=7;$popis = "Unikátní jednotka se naučí magii ohně 1 a získá ohnivý štít 750.";break;

			case "Arianin svazek ohnivého mistrovství": $this->schopnosti["magieOhne"]=4;$this->schopnosti["ohnivyStit"]=1000;$this->schopnosti["vyvolavaJednotku"]=95;$popis = "Legendární jednotka se naučí magii ohně 4 a získá ohnivý štít 1000.";break;

			case "Měděné pláty": $this->obr+=1;$popis = "Jednotka získá +1 do obrany.";break;

			case "Broznové pláty": $this->obr+=2;$popis = "Jednotka získá +2 do obrany.";break;

			case "Železné pláty": $this->obr+=4;$this->ini-=1;$popis = "Jednotka získá +4 do obrany, ale její iniciativa klesne o 1.";break;

			case "Ocelové pláty": $this->obr+=6;$this->ini-=1;$popis = "Jednotka získá +6 do obrany, ale její iniciativa klesne o 1.";break;

			case "Tvrzené ocelové pláty": $this->obr+=8;$this->ini-=1;$popis = "Jednotka získá +8 do obrany, ale její iniciativa klesne o 1.";break;
			
			case "Pláty z Temné ocele": $this->obr+=11;$this->ini-=2;$popis = "Jednotka získá +11 do obrany, ale její iniciativa klesne o 2.";break;

			case "Maska Královny medůz": $this->schopnosti["magieSmrti"]=3;$this->schopnosti["magieZeme"]=1;$popis = "Legendární jednotka získá Magii země 1 a Magii smrti 3.";break;

case "Kouzelnická róba": $this->schopnosti["imunitaOhen"]=1;$this->obr++;$popis = "Jednotka získá +1 do obrany a je imuní vůči ohnivému štítu.";break;

			case "Prokletá kouzelnická róba": if($this->schopnosti["magieSmrti"]>0){$this->schopnosti["imunitaOhen"]=1;$this->obr+=3;$popis = "Jednotka s Magí smrti získá +3 do obrany a je imuní vůči ohnivému štítu.";}break;

			case "Prapor berzekra": $this->schopnosti["dav"]=1;$this->ini+=2;$this->obr*=0.75;$this->utk*=1.2;$popis = "Jednotka získa +2 do iniciativy, -25% do obrany, +20% do utoku a naučí se dav.";break;

			case "Aetherův ledový prapor": $this->schopnosti["magieLedu"]=1;if($this->frakce==3){$this->ini+=3;$this->schopnosti["magieLedu"]=2;}$popis = "Jednotka získá schopnost Magie Ledu 1, Aethrova jednotka získa schopnost Magie Ledu 2 a navíc +3 do iniciativy";break;
			
	case "Ætherův ledový prapor": $this->schopnosti["magieLedu"]=1;if($this->frakce==3){$this->ini+=3;$this->schopnosti["magieLedu"]=2;}$popis = "Jednotka získá schopnost Magie Ledu 1, Aethrova jednotka získa schopnost Magie Ledu 2 a navíc +3 do iniciativy";break;			

	case "Vulkánův ohnivý prapor": if($this->schopnosti["ohnivyStit"]>0){
			if($this->frakce==2)$this->schopnosti["ohnivyStit"]+=$this->dmg;
			$this->schopnosti["ohnivyStit"]+=1;
			} else $this->schopnosti["ohnivyStit"] = 1;
			if($this->schopnosti["magieOhne"]>0 and $this->frakce==2){$this->schopnosti["posileniOhen"]=1;} $popis = "Jednotka získá ohnivý štít +1 pokud je Vulkánova získá další bonus rovnající se hodnotě její damage do ohnivého štítu a posílení vlastní Magie Ohně.";break;

			case "Dharova kamenná standarta": $this->obr+=4;if($this->frakce==5){$this->obr+=6;}$popis = "Jednotka dostane +4 do obrany pokud je Dharova získá dalších +6.";break;

			case "Dralgarův Totem života": if($this->frakce==1){$this->ziv*=1.5;} else $this->ziv*=1.15; $popis = "Jednotka získá +15% do životů, pokud je Dralgarova získá dalších +35%.";break;

			case "Dreaddův prapor smrti": $this->obr+=2;$this->utk+=2;if($this->frakce==4){$this->obr+=4;$this->utk+=4;}$popis = "Jednotka získá +2 do útoku a obrany pokud je Dreaddova získá dalších +4 do útoku a obrany.";break;

			case "Ghorova standarta s nabodnutou hlavou démona": if($this->frakce==6){$this->dmg*=1.35;} else $this->dmg*=1.15;$popis = "Jednotka získá +15% do damage pokud je Ghorova získá dalších +35%.";break;

			case "Dehinatorův meč": $this->schopnosti["magieSmrti"]=3;$this->schopnosti["imunitaOhen"]=1;$this->schopnosti["exterminace"]=1;$this->dmg*=3;$popis = "Legendární Dehinatorova zbraň. Legendární jednotka získá Imunitu proti ohni, Magii Smrti 3, Exterminaci a její damage je zvýšen o 200%.";break;

			case "Srdce prokletého wurma Ragnarokka": $this->obr+=5;$this->utk+=5;$this->dmg*=1.5;$this->ziv*=1.5;$popis = "Nabito temnou magií z vesmíru Diabolus, poskytuje jednotce +5 útok, +5 obrana, +50% damage a +50% životů.";break;

			case "Jedovaté střely": if($this->typ == 2){
        if ($this->schopnosti["jedovyUtok"] != 0) $this->schopnosti["jedovyUtok"]+=10;
        else $this->schopnosti["jedovyUtok"]=10;
      }$popis = "Jednotka s druhem útoku 2 získá +10% do poškození proti živím cílům.";break;

			case "Drtivé střely": if($this->typ == 2){
        if ($this->schopnosti["drtivyUtok"] != 0) $this->schopnosti["drtivyUtok"]+=10;
        else $this->schopnosti["drtivyUtok"]=10;
			}$popis = "Jednotka s druhem útoku 2 získá +10% do poškození proti neživím cílům.";break;

			case "Posvěcené střely": if($this->typ == 2){
        if ($this->schopnosti["svatyUtok"] != 0) $this->schopnosti["svatyUtok"]+=10;
        else $this->schopnosti["svatyUtok"]=10;
			}$popis = "Jednotka s druhem útoku 2 získá +10% do poškození proti nemrtvým cílům.";break;

			case "Svazek ledového mistrovství": $this->schopnosti["magieLedu"]=3;$this->schopnosti["ledovyStit"]=30;$popis = "Unikátní jednotka se naučí magii ledu 3 a ledový štít 30%.";break;

			case "Ledová róba": $this->schopnosti["imunitaOhen"]=1;$this->schopnosti["ledovyStit"]=10;$popis = "Unikátní jednotka se naučí ledový štít 10% a je imuní proti ohnivému štítu.";break;

			case "Aethrova róba moci": $this->schopnosti["imunitaOhen"]=1;$this->schopnosti["ledovyStit"]=80;$this->schopnosti["magieLedu"]=4;$this->obr+=42;$popis = "Unikátní jednotka získá +42 do obrany, ledový štít 80%, imunitu proti ohnivému štítu a naučí se Magii ledu 4 &#8211; Aethrův dotek.";break;

			case "Obranná palisáda": $this->obr+=20;$this->ini=0;$popis = "Jednotka se skryje za obrannou palisádu. Získá +20 do obrany, ale její iniciativa klesne na 0.";break;

			case "Válečné bubny": if($this->typ == 1 and $this->stav == 1){$this->ini++;$this->utk++;$this->obr=-1;}$popis = "Jednotka s druhem útoku 2 získá +10% do poškození proti živím cílům.";break;

			case "Plán bojiště": if($this->typ == 2 or $this->typ == 3){$this->ini++;$this->utk*=1.2;}$popis = "Není nad to vědět, kam střílet. +20% do útoku, +1 do iniciativy pro jednotky s druhem útoku 2 a 3.";break;

			case "Ohnivá palisáda": $this->obr-=1;$this->ini=0;$this->schopnosti["ohnivyStit"]=3;$popis = "Kdo chce na nás zaútočit musí proběhnout ohněm. Jednotka získá -1 do obrany, iniciativa je snížena na 0 a získá ohnivý štít 3.";break;

			case "Dalekohled": if($this->typ == 2 or $this->typ == 3){$this->ini-=1;$this->utk+=3;}$popis = "Proč jednou při střelbě nezamířit? Jednotka získá -1 do ini, +3 do útoku. Pouze pro jednotky s druhem útoku 2 a 3.";break;

			case "Plášť Mucuse, krále toxických elementálů": $this->utk*=1.2;$this->ini++;$this->dmg*=1.25;$popis = "Plášť legendárního krále toxických elementálů, požene tvé jednotky do útoku. +20% do útoku, +1 inic, +25% do damage.";break;

			case "Posvátný popel": if($this->schopnosti["vzkryseni"]>0){$this->schopnosti["vzkryseni"]+=10;} $popis = "Jednotky se schopností vzkříšení mají zvýšenou šanci na znovuvzkříšení o 10%.";break;

			case "Amulet věznitele": if($this->schopnosti["magieSmrti"]==5){$this->schopnosti["staze"]=7;}$popis = "Jednotka s magii smrti 5 vyvolá o 25% více uvěznených duší.";break;

			case "Santova čepice": $this->schopnosti["imunitaOhen"]=1;$popis = "Štastné a veselé Vánoce.";break;
			
			case "Bronzový meč": $this->utk+=1;$popis = "Jednotka získá +1 do útoku.";break;
			
			case "Gladius": $this->utk+=2;$popis = "Jednotka získá +2 do útoku.";break;
			
			case "Rytířský jednoruční meč": $this->utk+=4;$popis = "Jednotka získá +4 do útoku.";break;
			
			case "Bastard": $this->utk+=7;$this->obr-=2;$popis = "Jednotka získá +7 do útoku, ale její obrana klesne o 2.";break;

      case "Težká bojová sekyra": $this->utk+=10;$this->obr-=3;$popis = "Jednotka získá +10 do útoku, ale její obrana klesne o 3.";break;
      
      case "Katana z Temné ocele": $this->utk+=8;$this->ini+=1;$popis = "Jednotka získá +8 do útoku a +1 do inicitivy.";break;
      
      case "Drtič lebek": $this->utk+=3;$this->ini-=1;$this->schopnosti["slayer"]=1;$popis = "Řemdih s kovovými koulemi. Jednotka získá +3 do útoku, -1 do inicitívy a naučí se schopnost slayer.";break;
      
      case "Kopí hlupáků": $this->schopnosti["exterminace"]=1;$popis = "Unikátní jednotka získa schopnost exterminace.";break;

      case "Excalibur": $this->utk+=15;$this->obr+=10;$this->ini+=5;$popis = "Unikátní jednotka získá +15 do útoku, +10 do obrany a +5 do inicitivy.";break;
      
      case "Ohnivá dračí zbroj": $this->schopnosti["magieOhne"]=7;$this->schopnosti["vyvolavaJednotku"]=645;$this->obr+=5;$this->dmg*=4;$popis = "Unikátní jednotka získá +300% do poškození, +5 do obrany a naučí se Magii ohně 7";break;//vyvolá stínové draky
      
      case "Prsten Života": $this->schopnosti["magieLesa"]=2;$this->schopnosti["vyvolavaJednotku"]=68;$popis = "Unikátní jednotka získá Magii lesa 2";break;
      
     	case "Pírka z anděla": if($this->stav == 1) {$this->ziv+=7;}$popis = "Nenemrtvá a Neneživá jednotka získá +7 do životů.";break;
     	
     	case "Maska Medůzy": $this->schopnosti["magieZeme"]=1;$popis = "Unikátní jednotka získá Magii země 1";break;
     	
     	case "Amulet Beznaděje": $this->schopnosti["magieSmrti"]=3;$popis = "Unikátní jednotka získá Magii smrti 3";break;
     	
     	case "Tutsumasa, Ledová dračí čepel": $this->schopnosti["magieLedu"]=2;$this->dmg*=4;$popis = "Unikátní jednotka získá +300% do damage a naučí se Magii ledu 2";break;
     	
     	case "Kostěná dračí hůl": $this->schopnosti["magieSmrti"]=5;$this->schopnosti["vyvolavaJednotku"]=107;$this->dmg*=4;$popis = "Unikátní jednotka získá +300% do damage a naučí se Magii smrti 5";break;
     	
			case "Rukavice drtivé síly":
        if ($this->schopnosti["drtivyUtok"] != 0) $this->schopnosti["drtivyUtok"]+=100;
        else $this->schopnosti["drtivyUtok"]=100;
			$popis = "JJednotka získá +100% do poškození proti neživým cílům.";break;

			case "Rukavice posvěcení":
        if ($this->schopnosti["svatyUtok"] != 0) $this->schopnosti["svatyUtok"]+=100;
        else $this->schopnosti["svatyUtok"]=100;
			$popis = "Jednotka získá +100% do poškození proti nemrtvým cílům.";break;
			
			case "Mágova róba moci": $this->schopnosti["posileni"]=10;$popis = "Posílí přivolávací a vyvolávací magii jednotky o 10%.";break;
			
			case "Velemágova róba moci": $this->schopnosti["posileni"]=50;$popis = "Posílí přivolávací a vyvolávací magií jednotky o 50%.	";break;
			
			case "Gnomí vozík": $this->dmg*=1.15;$this->obr+=1;
                          if($this->nazev == "Gobliní vzducholoď" or $this->nazev == "Gobliní Hybridní vzducholoď" or $this->nazev == "Gobliní Vyztužená vzducholoď")
                          {$this->schopnosti["staze"]=8;}
                          $popis = "Jednotka získa +15% do poškození a +1 do obrany. Gobliní vzducholodě získávají navíc +50% na množství paragánů.";break;

			}

		$this->celkz = $this->ziv * $this->pocet;
		$this->poc_celkz = $this->ziv * $this->pocet;

		$this->popis_artu = $popis;

		$this->zaokrouhlit();

		}



	#Funce schopností jednotek

	#Štíty jednotek

	function ohnivyStit($obrance, $kill){

		global $jednotka;
		global $zabito_goblinu;

		$dmg = $jednotka[$obrance]->schopnosti["ohnivyStit"]*($jednotka[$obrance]->pocet+$kill);

		$pocet_ziv = $this->celkz - $dmg;

		$zabito = ceil($this->celkz/$this->ziv) - ceil($pocet_ziv/$this->ziv);

		if($zabito>$this->pocet) $zabito = $this->pocet;

		$this->celkz -= $dmg;

		$this->pocet -= $zabito;
		
		if ($this->isgoblin($this->ident)) $zabito_goblinu += $zabito; 

//		if($this->pocet<0) $this->pocet=0; //nadbytecna, uz se testuje vyse

		echo "<span style='color:".$this->barva."'>Kolem jednotky ".$jednotka[$obrance]->toolNazev()." vzplanul ohnivý štít a poranil útočníka za ".prevod($dmg).", zahynulo ".prevod($zabito)." x ".$this->toolNazev()."</span><br>";

		}



	function toxickyStit($obrance, $kill){

		global $jednotka;
		global $zabito_goblinu;

		$dmg = $jednotka[$obrance]->schopnosti["toxickyStit"]*($jednotka[$obrance]->pocet+$kill);

		$pocet_ziv = $this->celkz - $dmg;

		$zabito = ceil($this->celkz/$this->ziv) - ceil($pocet_ziv/$this->ziv);

		if($zabito>$this->pocet) $zabito = $this->pocet;
		
		$this->celkz -= $dmg;

		$this->pocet -= $zabito;
		
		if ($this->isgoblin($this->ident)) $zabito_goblinu += $zabito; 

		echo "<span style='color:".$this->barva."'>Kolem jednotky ".$jednotka[$obrance]->toolNazev()." je toxický oblak, který zraňuje vše živé za ".prevod($dmg).", zahynulo ".prevod($zabito)." x ".$this->toolNazev()."</span><br>";

		}



	function ledovyStit($obrance){

		global $jednotka;


		$this->ini -= max($this->ini*($jednotka[$obrance]->schopnosti["ledovyStit"]/100),1);
		if ($this->ini < 0) $this->ini=0;

		aktualizaceInic();

		$this->zaokrouhlit();

		echo "<span style='color:".$this->barva."'>Kolem jednotky ".$jednotka[$obrance]->toolNazev()." je ledový štít, který spomalil útočníka o ".$jednotka[$obrance]->schopnosti["ledovyStit"]."%</span><br>";

		}



//-------------------Schopnosti---------------------------

	function dav(){

		$this->utk+=$this->utk*$this->pocet/100;

		$this->obr = 1;

		echo "<span style='color:".$this->barva."'>Jednotka ".$this->jmenoArt()." nabírá rychlost! V tomto kole získala " . $this->pocet ."% bonus do útoku, zároveň je ale zranitelnější.</span><br><br>";

		}



	function sebevrazda(){

		$this->dmg/=2;

		$this->schopnosti["viceutok"]-=1;

		echo "<span style='color:".$this->barva."'>Mohutná exploze poničila vše. Po jednotce ".$this->jmenoArt()." nezbylo skoro nic!</span><br>";

		}



	function finalniSebevrazda(){

		$this->pocet=0;

		$this->celkz=-1;

		echo "<span style='color:".$this->barva."'>Mohutná exploze poničila vše. Po jednotce ".$this->jmenoArt()." nezbylo skoro nic!</span><br>";

		}



	function exterminace($obrance){

		global $jednotka;

		$kill = $this->pocet;

		if($kill>$jednotka[$obrance]->pocet) $kill = $jednotka[$obrance]->pocet;

		$jednotka[$obrance]->pocet -= $kill;

		$jednotka[$obrance]->celkz -= $kill*$jednotka[$obrance]->ziv;

		echo "<span style='color:red'>".$this->pocetJmenoArt()." chladnokrevně zabila ze zálohy ".prevod($kill)." x ".$jednotka[$obrance]->jmenoArt()."! Zůstává ".$jednotka[$obrance]->pocetJmenoArt()."</span><br>";

		}



	function sabotaz($obrance){

		global $jednotka;

		$jednotka[$obrance]->dmg-=$this->pocet;

		if($jednotka[$obrance]->dmg<0) $jednotka[$obrance]->dmg=0;

		echo "<span style='color:silver'>".$this->jmenoArt()." provedl sabotáž na zdroje nepřátelské jednotky ".$jednotka[$obrance]->jmenoArt()."</span><br>";

		}



	function temnykrik(){
	
	//má být: 5*(pocet^0.7) az 4.5*(pocet^0.8)

		global $jednotka;

		$poctar = $this->pocet;
		
		$spodni_hranice = 5 * pow($poctar,0.7);
		$horni_hranice = 4.5 * pow($poctar,0.8);
		
		
//		echo $spodni_hranice
//		echo $horni_hranice

		$posileni = mt_rand($spodni_hranice,$horni_hranice);

//		while($poctar > 0){

//			$posileni += mt_rand(1,$posilovac)/1;

//			$poctar-=1;

//			}

		$id=0;

		while($jednotka[$id]){

			if($jednotka[$id]->stav==2){

				$jednotka[$id]->utk*=($posileni/100+1);

				$jednotka[$id]->obr*=($posileni/100+1);

				$jednotka[$id]->ini*=($posileni/100+1);

				$jednotka[$id]->dmg*=($posileni/100+1);

				$jednotka[$id]->zaokrouhlit();

				$poradi[$id][ini] = $jednotka[$id]->ini;

				$poradi[$id][id] = $jednotka[$id]->id;

				}

			$id++;

			}

    $posileni = number_format($posileni, 0, ".",",");
		echo "<span style='color:".$this->barva."'>Bojovým polem se přehnal, jak obrovská tlaková vlna temný křik, který trhal uši živím a nemrtvé posiloval.<br> ".$this->jmenoArt()." posílil nemrtvé o $posileni%</span><br><br>";

		}

function isgoblin($ident){  //identifikace rasa goblin

 if($ident == 245 /*Goblin*/ or $ident == 128 /*Goblin Fanatik*/ or $ident == 130 /*Goblin paragán*/ or $ident == 127 /*Goblin Pyroman*/ or $ident == 125 /*Goblin s Prakem*/ or $ident == 131 /*Gobliní exterminátor*/ or $ident == 133 /*Gobliní hrdina*/ or $ident == 134 /*Gobliní patriarcha*/ or $ident == 132 /*Gobliní sabotér*/ or $ident == 647 /*Goblin na vlkovi*/ or $ident == 648 /*Goblin na medvědovi*/ or $ident == 649 /*Zlobr*/ or $ident == 657 /*Hobgoblin*/ or $ident == 660 /*Pán šelem*/ or $ident == 901 /*Zlobří Šaman*/)
return true;
else
return false;

}



	function silagoblinu(){

		global $jednotka;

		$posileni = $this->pocet * 1;//každý pán šelem zvedá dmg o 1%

		$id=0;

		while($jednotka[$id]){
		
		if ($this->isgoblin($jednotka[$id]->ident)){   	

				$jednotka[$id]->dmg*=($posileni/100+1);

				$jednotka[$id]->zaokrouhlit();

				}

			$id++;

			}

    $posileni = number_format($posileni, 0, ".",",");
		echo "<span style='color:gray'>Pán šelem: Ghoro dej nám sílu. Ghoro dej nám sílu. Ghoro!!!<br> ".$this->jmenoArt()." posílil příslušníky rasy goblinů o $posileni%</span><br><br>";
		
		}


	function vzkryseni($kill){

		if(mt_rand(0, 100) <= $this->schopnosti["vzkryseni"]){

			$kill = round($kill * (mt_rand(50,100)/100));

			$this->pocet += $kill;

			$this->celkz += $kill*$this->ziv;

			if($this->celkz<=0) $this->celkz = $kill*$this->ziv;

			echo "<span style='color:gray'>$kill x ".$this->jmenoArt()." znovu povstal!</span><br>";

			}

		}
		

  //kanibalizmus ghúlů a upírů
	function kanibalizmus($dmg) {
			
        $vyleceno_ghul = round ($dmg * (mt_rand(0,5)/100));
        $vyleceno_upir = round ($dmg * (mt_rand(3,15)/100));
        
        $vyleceno;
        
       if ($this->ident == 684 /*Ghúl*/) {
			
          if(($this->celkz + $vyleceno_ghul) > $this->poc_celkz) $vyleceno_ghul = $this->poc_celkz - $this->celkz;  
       			
          $this->celkz = $this->celkz + $vyleceno_ghul;
          $this->obdrzela_dmg -= $vyleceno_ghul;
          $vyleceno = $vyleceno_ghul;
        }
        
        if ($this->ident == 685 /*Upír*/) {
			
          if(($this->celkz + $vyleceno_upir) > $this->poc_celkz) $vyleceno_upir = $this->poc_celkz - $this->celkz;  
       			
          $this->celkz = $this->celkz + $vyleceno_upir;
          $this->obdrzela_dmg -= $vyleceno_upir;
          $vyleceno = $vyleceno_upir;
        }
        
        
        //dopočítání celkového počtu jednotek ve stacku po oživení.
        $pom = ceil($this->celkz/$this->ziv);
        $pom2 = $this->pocet; //původní počet před vyléčením
        $this->pocet = $pom;
        
        if ($vyleceno != 0) {
        echo "<span style='color:#778899'>Jednotka ".$this->toolNazev()." začala požírat zbytky vnitřností po nepřátelské jednotce a podařilo se ji tím vyléčit ".$vyleceno." života (".($pom-$pom2)." x ".$this->toolNazev()." znovu povstal)</span><br>";
        }
        
			}
	

//------------------konec Schopnosti -----------------------------

//---------------------Magie--------------------------------------

	#Magie Smrti

	function magieSmrti3($obrance){

		global $jednotka;

		$jednotka[$obrance]->ini-=5;

		aktualizaceInic();

		echo "<span style='color:gray'>Obrovský strach a beznaděj doslova zmrazil zasaženou jednotku. Její iniciativa byla sníženo o 5</span><br>";

		}



	function magieSmrti4($obrance){

		global $jednotka;

		$jednotka[$obrance]->utk -= $this->pocet;

		$jednotka[$obrance]->obr -= $this->pocet;

		if($jednotka[$obrance]->utk < 0) $jednotka[$obrance]->utk = 0;

		if($jednotka[$obrance]->obr < 0) $jednotka[$obrance]->obr = 0;

		$jednotka[$obrance]->zaokrouhlit();

		echo "<span style='color:".$this->barva."'>Jednotka byla prokleta silnou kletbou...</span><br>";

		}



	function magieSmrti8($obrance){

		global $jednotka;

		$koef = 0.5*$this->pocet;

		if($koef>100) $koef = 100;

		$jednotka[$obrance]->dmg-= $jednotka[$obrance]->dmg*$koef/100;

		$jednotka[$obrance]->obr-= $jednotka[$obrance]->obr*$koef/100;

		$jednotka[$obrance]->utk-= $jednotka[$obrance]->utk*$koef/100;

		$jednotka[$obrance]->zaokrouhlit();

		echo "<span style='color:".$this->barva."'>Jednotka ".$this->pocetJmenoArt()." zakouzlila snížení bojeschopnosti na ".$jednotka[$obrance]->jmenoArt()."</span><br>";

		}
		
	function magieSmrti11($obrance){

		global $jednotka;
		
    //sníží se životy každé jednotky ve stacku nejméně však na 1
		$jednotka[$obrance]->ziv -= $this->pocet;
		if ($jednotka[$obrance]->ziv < 1) { 
        $jednotka[$obrance]->ziv = 1;
		    $jednotka[$obrance]->celkz = $jednotka[$obrance]->pocet;
		    $jednotka[$obrance]->poc_celkz = $jednotka[$obrance]->pocet;
		} else
		//sníží se tak celkový počet životů jednotky
		$jednotka[$obrance]->celkz -= ($jednotka[$obrance]->pocet*$this->pocet);
		//včetně počátečního počtu životů pro maximální uzdravení např. jednorožcem
		$jednotka[$obrance]->poc_celkz -= ($jednotka[$obrance]->pocet*$this->pocet);
		
//  $jednotka[$obrance]->zaokrouhlit();

		echo "<span style='color:".$this->barva."'>Jednotka byla prokleta silnou kletbou...</span><br>";

		}


	#Magie Země

	function magieZeme1($obrance){

		global $jednotka;

		$kill = $this->pocet;

		if($kill>$jednotka[$obrance]->pocet) $kill = $jednotka[$obrance]->pocet;

		$jednotka[$obrance]->pocet -= $kill;

		$jednotka[$obrance]->celkz -= $kill*$jednotka[$obrance]->ziv;

		echo "<span style='color:red'>".$this->pocetJmenoArt()." proměnil ".prevod($kill)." jednotek ".$jednotka[$obrance]->jmenoArt()." na kámen! Zůstává ".$jednotka[$obrance]->pocetJmenoArt()."</span><br>";

		}
		
	function magieZeme2(){

		global $jednotka;
		
		$posileni = $this->pocet * 1;

	  $id=0;

		while($jednotka[$id]){
				
				if($jednotka[$id]->strana==$this->strana){

        $jednotka[$id]->obr*=($posileni/100+1);
			  $jednotka[$id]->zaokrouhlit();
			  }
			  
			  $id++;

        }
        
    $posileni = number_format($posileni, 0, ".",",");

		echo "<span style='color:".$this->barva."'>Povolávám sílu Matky Země. Matko, stůj při nás! (obrana všech vlastních jednotek posílena o $posileni%)</span><br><br>";


		}




	#Magie Světla

	function magieSvetla1($obrance){

		global $jednotka;

		$kill = $this->pocet;

		if($kill>$jednotka[$obrance]->pocet) $kill = $jednotka[$obrance]->pocet;

		$jednotka[$obrance]->pocet -= $kill;

		$jednotka[$obrance]->celkz -= $kill*$jednotka[$obrance]->ziv;

		echo "<span style='color:#778899'>Oslníví paprsek, který vyslal ".$this->pocetJmenoArt()." proměnil ".prevod($kill)." nemrtvých ".$jednotka[$obrance]->jmenoArt()." v prách! Zůstává ".$jednotka[$obrance]->pocetJmenoArt()."</span><br>";

		}


	function magieSvetla5(){

		global $jednotka;

		$id=0;

		while($jednotka[$id]){

			if($jednotka[$id]->frakce==7){

			$jednotka[$id]->schopnosti["stec"]+=5*$jednotka[$id]->dmg;

			$jednotka[$id]->ini++;

			$jednotka[$id]->zaokrouhlit();

			$poradi[$id][ini] = $jednotka[$id]->ini;

			$poradi[$id][id] = $jednotka[$id]->id;

			}

		$id++;

		}

		echo "<span style='color:".$this->barva."'>".$this->jmenoArt().": Do boje svatí válečníci! Bojujte ve jménu dobra!</span><br><br>";

		}



	function svetlo($co){

		echo "<span style='color:#778899'>".$this->jmenoArt().": Povolávám sílu světla, posil $co</span><br><br>";

		}

	#Magie Ledu

	function magieLedu1($obrance){

			if(mt_rand(0,100)<33){

			global $jednotka;

			$jednotka[$obrance]->ini-=1;

			aktualizaceInic();

			echo "<span style='color:gray'>Zasažená jednotka byla pokryta ledovými krystalky a její iniciativa snížena o 1</span><br>";

			}
			
			}
	

	function magieLedu2($obrance){

		global $jednotka;

		$jednotka[$obrance]->ini-=3;

		aktualizaceInic();

		echo "<span style='color:gray'>Jednotka ".$jednotka[$obrance]->toolNazev()." byla pokryta ledovými krystalky a její iniciativa snížena o 3</span><br>";

		}



	function magieLedu4($obrance){

		global $jednotka;

		$jednotka[$obrance]->ini-=10;

		$jednotka[$obrance]->obr/=2;

		aktualizaceInic();

		echo "<span style='color:gray'>Zasaženou jednotku prostoupil naprostý chlad. Vzduch kolem ní se proměnil v ledové krystalky...</span><br>";

		}



	function magieLedu5($obrance){

		global $jednotka;

		$jednotka[$obrance]->schopnosti[ohnivyStit]=0;

		$jednotka[$obrance]->schopnosti[magieOhne]=0;

		echo "<span style='color:gray'>Zasaženou jednotku prostoupil magický chlad...</span><br>";

		}
		

	function magieLedu7(){

		global $jednotka;

		$id=0;

	  while($jednotka[$id]){

			if($jednotka[$id]->strana!=$this->strana){

		  
		  if($jednotka[$id]->stav==1 and mt_rand(1,100) > $jednotka[$id]->schopnosti["imunitaMagie"] and mt_rand(1,100) > $jednotka[$id]->schopnosti["imunitaLed"]){$jednotka[$id]->ini*=0.8;
			$jednotka[$id]->zaokrouhlit();}
			
			aktualizaceInic();

			}

		$id++;

		}

		  echo "<span style='color:".$this->barva."'>Bitevním polem se prohnala ledová smršť a zpomalila všechno živé</span><br><br>";

		}
		
  function magieLedu9($obrance){

			if(mt_rand(0,100)<60){

			global $jednotka;

			$jednotka[$obrance]->ini = min(round($jednotka[$obrance]->ini*0.6),$jednotka[$obrance]->ini-3);

			aktualizaceInic();

			echo "<span style='color:gray'>Zasažená jednotka byla pokryta ledovými krystalky a její iniciativa byla snížena o 60%, nejméně však o 3.</span><br>";

			}
			
		}

	
	#Magie Lesa
	
	function magieLesa4(){

		global $jednotka;global $poradi;
		$id = 0;
		$poradi="";
		
		while($jednotka[$id]){

			if($jednotka[$id]->strana==$this->strana){
			$poradi[$id][obdrzela_dmg] = $jednotka[$id]->obdrzela_dmg;
			$poradi[$id][id] = $jednotka[$id]->id;

			}
		$id++;
		}
		
		seradit("obdrzela_dmg");  //seřadí podle dmg, kterou jednotka obdržela

		
		$a=0;
		$vyleceno;
	  while($a<count($jednotka)){
	  
	  	$id = $poradi[$a][id];

			if($jednotka[$id]->strana==$this->strana and $jednotka[$id]->celkz > 0 and $jednotka[$id]->id != $this->id and $jednotka[$id]->celkz < $jednotka[$id]->poc_celkz){//jednotka nemůže léčit sama sebe
			
        $vyleceno = round($this->celkz/2);
			
			 if(($jednotka[$id]->celkz + $vyleceno) > $jednotka[$id]->poc_celkz) $vyleceno = $jednotka[$id]->poc_celkz - $jednotka[$id]->celkz;  
       			
        $jednotka[$id]->celkz = $jednotka[$id]->celkz + $vyleceno;
        $jednotka[$id]->obdrzela_dmg -= $vyleceno;
        
        $pom = ceil($jednotka[$id]->celkz/$jednotka[$id]->ziv);
        $pom2 = $jednotka[$id]->pocet; //původní počet před vyléčením
        $jednotka[$id]->pocet = $pom;
        
        if ($vyleceno != 0) {
        echo "<span style='color:#778899'>Jednotka ".$this->pocet." x ".$this->toolNazev()." vyléčila ".($pom-$pom2)." x ".$jednotka[$id]->toolNazev()." (celkem vyléčeno: ".$vyleceno." životů)</span><br>";
        break;
        }
        
			}
		$a++;
		
		}

		}

	function magieLesa5(){
	
		global $jednotka;global $poradi;
		$id = 0;
		$poradi="";
		
		while($jednotka[$id]){

			if($jednotka[$id]->strana==$this->strana){
			$poradi[$id][obdrzela_dmg] = $jednotka[$id]->obdrzela_dmg;
			$poradi[$id][id] = $jednotka[$id]->id;
			
			}
		$id++;
		}
		
		seradit("obdrzela_dmg");  //seřadí podle dmg, kterou jednotka obdržela

		
		$a=0;
		$vyleceno;
	  while($a<count($jednotka)){
	  
	  	$id = $poradi[$a][id];

			if($jednotka[$id]->strana==$this->strana and $jednotka[$id]->celkz > 0 and $jednotka[$id]->id != $this->id and $jednotka[$id]->celkz < $jednotka[$id]->poc_celkz){//jednotka nemůže léčit sama sebe
			
        $vyleceno = max(round($jednotka[$id]->poc_celkz*0.1*$this->pocet),1000);
			
			 if(($jednotka[$id]->celkz + $vyleceno) > $jednotka[$id]->poc_celkz) $vyleceno = $jednotka[$id]->poc_celkz - $jednotka[$id]->celkz;  
       			
        $jednotka[$id]->celkz = $jednotka[$id]->celkz + $vyleceno;
        $jednotka[$id]->obdrzela_dmg -= $vyleceno;
        
        $pom = ceil($jednotka[$id]->celkz/$jednotka[$id]->ziv);
        $pom2 = $jednotka[$id]->pocet; //původní počet před vyléčením
        $jednotka[$id]->pocet = $pom;
        
        if ($vyleceno != 0) {
        echo "<span style='color:#778899'>Jednotka ".$this->pocet." x ".$this->toolNazev()." vyléčila ".($pom-$pom2)." x ".$jednotka[$id]->toolNazev()." (celkem vyléčeno: ".$vyleceno." životů)</span><br>";
        break;
        }
        
			}
		$a++;
		
		}

		}
		
		
		function magiePrastarych3(){  //vyvolá kopii nejsilnější jednotky protistrany

		global $jednotka;global $poradi;
		$id = 0;
		$poradi="";
		
		while($jednotka[$id]){

			if($jednotka[$id]->strana!=$this->strana){ //hledáme v seznamu jednotek protistrany
			$poradi[$id][hod] = $jednotka[$id]->hod; //tu s nejvyšší hodnotou
			$poradi[$id][id] = $jednotka[$id]->id;
			
			}
		$id++;
		}
		
		seradit("hod");  //seřadí podle hodnoty nepřátelské jednotky

		
		$a=0;
	  while($a<count($jednotka)){
	  
	  	$id = $poradi[$a][id];

			if($jednotka[$id]->strana!=$this->strana and $jednotka[$id]->celkz > 0 ){//vytvoří se kopie živé nepřátelské jednotky
			
        $index=count($jednotka);
        
        $nahoda = mt_rand(10,36)/10;
        //vyvola nejméně jednu jednotku nebo v rozmezi 50-600 hodnoty jednotky za kazdy svuj zivot 
        $vyvola = round((($this->celkz * $nahoda))/$jednotka[$id]->hod);
       
       //pokud má sílu k vyvolání nejsilnější jednotky, jinak bude zkoušet vyvolat druhou nejsilnější
       if ($vyvola != 0) {
       
        //je potřeba zařídit, aby šly dělat stínové jednotky stínových jednotek.
        if($jednotka[$id]->vyvolana == 1)   
        $nazev = str_replace("Stínový ","",$jednotka[$id]->nazev);
        else $nazev = $jednotka[$id]->nazev;
       
        
        // stinove jednotky nemuzou donekonecna sumonovat nove stinove jednotky.
        if($jednotka[$id]->schopnosti["magiePrastarych"]==3) { 
          $jednotka[$index] = new Jednotka($index, $nazev, $vyvola, $this->strana, 1, $this->barva, "");
          $jednotka[$index]->schopnosti["magiePrastarych"]=0;
          }
          
        
        else
			  $jednotka[$index] = new Jednotka($index, $nazev, $vyvola, $this->strana, 1, $this->barva, "");
			  $jednotka[$index]->obr = 0;
			  $jednotka[$index]->nazev = "Stínový ".$jednotka[$index]->nazev;
			  
			  //navýšení celkové hodnoty o hodnotu stínových jednotek
			  global $global_hodnota;
			  $global_hodnota[$this->strana] =$global_hodnota[$this->strana] + ($vyvola*$jednotka[$index]->hod);		

        
        echo "<span style='color:".$this->barva."'>Jednotka ".$this->pocet." x ".$this->toolNazev()." započala krvavý rituál pro vyvolání stínové magie a stvořila celkem ".$vyvola." x ".$jednotka[$index]->toolNazev()."</span><br><br>";
        
        $this->pocet = round($this->pocet/2);
        $this->celkz = $this->ziv * $this->pocet;
        $this->obdrzela_dmg = $this->poc_celkz - $this->celkz;
        
        break;
        }
			}
		$a++;
		
		}

		}
		
		
	function magiePrastarych4(){

		global $jednotka;

		$posileni = $this->pocet;

		$id=0;

		while($jednotka[$id]){

			if($jednotka[$id]->frakce==$this->frakce){

				$jednotka[$id]->utk*=($posileni/100+1);

				$jednotka[$id]->obr*=($posileni/100+1);

				$jednotka[$id]->dmg*=($posileni/100+1);

				$jednotka[$id]->zaokrouhlit();

				}

			$id++;

			}

    $posileni = number_format($posileni, 0, ".",",");
		echo "<span style='color:".$this->barva."'>Bitevním polem se prohnal náznak temné magie. Pak oblohu zakryly temné mraky a když se rozestoupili na nebi se zjevil Rudý Měsíc, jako předzvěst krvavého dne jenž následoval.</span><br><br>";

		}	
		

//------------------konec Magie -------------------------------------------------




	#Magie Eternanů

	function magieEternanu2($obrance){

		global $jednotka;

		$jednotka[$obrance]->dmg*=0.75;

		$jednotka[$obrance]->zaokrouhlit();

		echo "<span style='color:#778899'>".$this->toolNazev()." dezorientovala protivníka (-25% do damage)</span><br>";

		}



	#Prasatará Magie

	function staze(){

		global $jednotka;

		$id=0;

		while($jednotka[$id]){

			if($jednotka[$id]->vyvolana==1){

			$jednotka[$id]->ini=0;

			$poradi[$id][ini] = $jednotka[$id]->ini;

			$poradi[$id][id] = $jednotka[$id]->id;

			}

		$id++;

		}

		}



	#Neoficiální schopnosti BEGIN

	function magieVody1(){

		global $jednotka;

		$id=0;

		while($jednotka[$id]){

			if($jednotka[$id]->strana!=$this->strana){

			$jednotka[$id]->dmg*=0.9;

			$jednotka[$id]->zaokrouhlit();

			}

		$id++;

		}

		  echo "<span style='color:".$this->barva."'>Nad bojištěm se náhle objevila temná mračna, z nichž se spustil prudký déšť, <b>".$this->pocetJmenoArt()."</b> přivolal bouři.</span><br><br>";

		}



	function magieVody3($obrance){

		global $jednotka;

		$kill = $this->pocet;

		if($kill>$jednotka[$obrance]->pocet) $kill = $jednotka[$obrance]->pocet;

		$jednotka[$obrance]->pocet -= $kill;

		$jednotka[$obrance]->celkz -= $kill*$jednotka[$obrance]->ziv;

		echo "<span style='color:red'>".$this->pocetJmenoArt()." stáhl pod hladinu ".prevod($kill)." x ".$jednotka[$obrance]->jmenoArt()."! Zůstává ".$jednotka[$obrance]->pocetJmenoArt()."</span><br>";

		}



	function magieOhne17(){

		global $jednotka;

		$id=0;

		while($jednotka[$id]){

			if($jednotka[$id]->nazev=="Meteorit" or $jednotka[$id]->nazev=="Rozžhavené magma" or $jednotka[$id]->nazev=="Ohnivá koule"){

			$nahoda = mt_rand(5,10)/1000;

			$jednotka[$id]->utk+=$jednotka[$id]->utk*$nahoda*$this->pocet/100;

			$jednotka[$id]->zaokrouhlit();

			}

		$id++;

		}

		  echo "<span style='color:".$this->barva."'>Jednotka ".$this->pocetJmenoArt()." svou vírou posílila ohnivá kouzla.</span><br><br>";

		}

	#Neoficiální schopnosti END



		#Vypisování artů, počtu a názvů jednotek

	function toolNazev(){

	    $text = "<table><tr><td>Damage</td><td>" . prevod($this->dmg) . "</td><td>Útok</td><td>" . prevod($this->utk) . "</td></tr><tr><td>Obrana</td><td>" . prevod($this->obr) . "</td><td>Životy</td><td>" . prevod($this->ziv) . "</td></tr><tr><td>Iniciativa</td><td>" . prevod($this->ini) . "</td><td>Hodnota</td><td>" . prevod($this->hod) . "</td></tr><tr><td>Typ útoku</td><td>" . prevod($this->typ) . "</td><td>Stav</td><td>";

		if($this->stav == 1){$text .= "Živá";}

		elseif($this->stav == 2){$text .= "Nemrtvá";}

		else{$text .= "Neživá";}

		$text = $text . "</td></tr>";



		  $i = 0;$e = 0;$n = "x";

  while($i < 32){

    switch($i){

          case 1: if($this->schopnosti[stec] > 0){$n = "Steč"; $zkr = stec;$e++;} break;

          case 2: if($this->schopnosti[slayer] == 1){$n = "Slayer"; $zkr = nic;$e++;} break;

          case 3: if($this->schopnosti[magieLesa] > 0){$n = "Magie Lesa"; $zkr = magieLesa;$e++;} break;

          case 4: if($this->schopnosti[magieZeme] > 0){$n = "Magie Země"; $zkr = magieZeme;$e++;} break;

          case 5: if($this->schopnosti[magieLedu] > 0){$n = "Magie Ledu"; $zkr = magieLedu;$e++;} break;

          case 6: if($this->schopnosti[magieOhne] > 0){$n = "Magie Ohně"; $zkr = magieOhne;$e++;} break;

          case 7: if($this->schopnosti[magieSmrti] > 0){$n = "Magie Smrti"; $zkr = magieSmrti;$e++;} break;

          case 8: if($this->schopnosti[magieSvetla] > 0){$n = "Magie Světla"; $zkr = magieSvetla;$e++;} break;

          case 9: if($this->schopnosti[sebevrazedna] > 0){$n = "Sebevražedná"; $zkr = nic;$e++;} break;

          case 10: if($this->pocetUtoku > 1){$n = "Multiútok "; $zkr = multiutok;$e++;} break;

          case 11: if($this->schopnosti[ohnivyStit] > 0){$n = "Ohnivý štít"; $zkr = ohnivyStit;$e++;} break;

          case 12: if($this->schopnosti[ledovyStit] > 0){$n = "Ledový štít"; $zkr = ledovyStit;$e++;} break;

          case 13: if($this->schopnosti[toxickyStit] > 0){$n = "Toxický štít"; $zkr = toxickyStit;$e++;} break;

          case 14: if($this->schopnosti[drtivyUtok] > 0){$n = "Drtivý útok"; $zkr = drtivyUtok;$e++;} break;

          case 15: if($this->schopnosti[svatyUtok] > 0){$n = "Svatý útok"; $zkr = svatyUtok;$e++;} break;

          case 16: if($this->schopnosti[jedovyUtok] > 0){$n = "Jed"; $zkr = jedovyUtok;$e++;} break;

          case 17: if($this->schopnosti[dav] > 0){$n = "Dav"; $zkr = nic;$e++;} break;

          case 18: if($this->schopnosti[exterminace] > 0){$n = "Exterminace"; $zkr = nic;$e++;} break;

          case 19: if($this->schopnosti[imunitaOhen] > 0){$n = "Imunita na oheň"; $zkr = nic;$e++;} break;

          case 20: if($this->schopnosti[sabotaz] > 0){$n = "Sabotér"; $zkr = nic;$e++;} break;

          case 21: if($this->schopnosti[temnykrik] > 0){$n = "Temný křik"; $zkr = nic;$e++;} break;

          case 22: if($this->schopnosti[vzkryseni] > 0){$n = "Vzkříšení"; $zkr = nic;$e++;} break;

          case 23: if($this->schopnosti[staze] == 1){$n = "Prastará magie"; $zkr = nic;$e++;} break;

          case 24: if($this->schopnosti[magieEternanu] > 0){$n = "Eternanská magie"; $zkr = magieEternanu;$e++;} break;

		      case 25: if($this->schopnosti[magieVody] > 0){$n = "Magie vody"; $zkr = magieVody;$e++;} break;
		      
		      case 26: if($this->schopnosti[imunitaMagie] > 0) {$n = "Imunita proti Magii"; $zkr = imunitaMagie;$e++;}break;
		      
		      case 27: if($this->schopnosti[imunitaLed] > 0) {$n = "Imunita proti Ledu"; $zkr = imunitaLed;$e++;}break;
		      
		      case 28: if($this->schopnosti[silaGoblinu] > 0){$n = "Síla goblinů"; $zkr = silaGoblinu;$e++;} break;
		      
		      case 29: if($this->schopnosti[konstrukce] > 0){$n = "Konstrukce"; $zkr = konstrukce;$e++;} break;
		      
		      case 30: if($this->schopnosti[kanibalizmus] > 0){$n = "Kanibalizmus"; $zkr = kanibalizmus;$e++;} break;
		      
		      case 31: if($this->schopnosti[magiePrastarych] > 0){$n = "Magie Prastarých"; $zkr = magiePrastarych;$e++;} break;

		      
		      
	#při přidání nové nezapomenout nahoře zvednout čítač u case !

          }

    if($n != "x"){

		$hodnotaSchopnosti = $this->schopnosti[$zkr];

		if($zkr=="multiutok") $hodnotaSchopnosti=$this->pocetUtoku;

      if(($e % 2) != 0){$text = $text . "<tr><td>$n</td><td>" . $hodnotaSchopnosti . "</td>";}

      else{$text = $text . "<td>$n</td><td>" . $hodnotaSchopnosti . "</td></tr>";}

    }

  $n = "x";

  $i++;

  }



		$tab = "DELAY, 10, BORDERSTYLE, 'dashed', PADDING, 0, FADEIN, 250, FONTWEIGHT, 'bold', OPACITY, 86";

		return "<span onmouseover=\"Tip('$text', $tab);\" onmouseout=\"UnTip()\"'>" . $this->nazev . "</span>";

		}



	function pocetJmenoArt(){

		$ret = prevod($this->pocet)." x ". $this->toolNazev();

		if($this->art) $ret .= $this->art();

		return $ret;

		}



	function jmenoArt(){

		$ret = $this->toolNazev();

		if($this->art) $ret .= $this->art();

		return $ret;

		}



	function art(){

		$tab = "DELAY, 10, BORDERSTYLE, 'dashed', PADDING, 5, FADEIN, 250, FONTWEIGHT, 'bold', OPACITY, 86, WIDTH, 250";

		return "<span style='color:#bdb76b'> (<span onmouseover=\"Tip('".$this->popis_artu."', $tab);\" onmouseout=\"UnTip()\"'>".$this->art."</span>)</span>";

		}



	function vypsat() {

		return $this->pocetJmenoArt() . "<br>";

	}



	function nemoznoUtocit(){

		echo "<span style='color:".$this->barva."'>". $this->toolNazev() . " nemá na koho útočit</span><br>";

		}



	function nemoznoHybat(){

		echo "<div><span style='color:".$this->barva."'>". $this->toolNazev() . " se nemůže hýbat</span><br></div><br>";

		}



	function zaokrouhlit(){

		$this->ziv=round($this->ziv);

		//$this->celkz=$this->ziv*$this->pocet;

		$this->dmg=round($this->dmg);

		$this->utk=round($this->utk);

		$this->obr=round($this->obr);

		$this->schopnosti["stec"]=round($this->schopnosti["stec"]);

		}



	function obnovitIni(){

		$this->nahoda = mt_rand(1,99999999999)/100000000000;
		
		$this->ini -= $this->nahoda;

		if($this->vyvolana==1 and $this->ini > 0.9 and $this->ini < 1.1 and $this->zkl_ini!=1){

			$this->ini = $this->zkl_ini;

			}

		$this->ini += $this->nahoda;

		}



	function vypocetDMG($bonus){

		global $id_obrance; global $jednotka;global $aktualniKolo;

		$damage = $this->dmg;

		$damage += $damage*$bonus/100;
		
		//nova schpnost minotaura zablokovat uder
		if($jednotka[$id_obrance]->schopnosti["block"] > 0 and mt_rand(1, 100) <= $jednotka[$id_obrance]->schopnosti["block"]) {
			
		echo "<span style='color:".$this->barva."'>".$jednotka[$id_obrance]->nazev." úspěšně zablokoval úder.<br>";
		$dmg = 0;
		return $dmg;
		}

		if($this->schopnosti["stec"] > 0 and $aktualniKolo==3) $damage += $this->schopnosti["stec"];

		if ($this->utk >= $jednotka[$id_obrance]->obr){

			$dmg = $this->pocet * $damage *(1+(($this->utk - $jednotka[$id_obrance]->obr)/100)*4);

			}

		elseif($this->utk < $jednotka[$id_obrance]->obr and $this->schopnosti["slayer"] == 0){

			$dmg = $this->pocet * $damage*(1+($this->utk - $jednotka[$id_obrance]->obr)/50);
			

			}

		else{ //utoci jednotka se slayerem a utok je mensi nez obrana

			$dmg = $this->pocet * $damage;

			}

		if(($jednotka[$id_obrance]->obr - $this->utk)>=25 and $this->schopnosti["slayer"] == 0){

//			$dmg = $this->pocet * 0.55;
        $dmg = $this->pocet * (round(mt_rand(1,10))/10);

			}

		return round($dmg);

		}



	function zabitych($dmg){

	global $id_obrance; global $jednotka;

		$jednotka[$id_obrance]->celkz -= $dmg;

		$kill = $jednotka[$id_obrance]->pocet - ceil($jednotka[$id_obrance]->celkz / $jednotka[$id_obrance]->ziv);

        if ($kill > $jednotka[$id_obrance]->pocet){

			$kill = $jednotka[$id_obrance]->pocet;

			}

		$jednotka[$id_obrance]->pocet -= $kill;

		return $kill;

		}



		function nekromancer($kill, $obr){

			global $jednotka;
  
      //nejvýše se oživý počet zabitých jednotek
			$pocet_vyvolanych=ceil($kill*mt_rand(1,99)/100);

		  //omezeni počtu oživených na max počet jednotek, které oživovali.		
		 	
		 	if($this->schopnosti["magieSmrti"]==1) {
          $pocet_vyvolanych = min($this->pocet*5,$pocet_vyvolanych);
          //lich s kostěnou flétnou
          if ($this->schopnosti["staze"]==6) $pocet_vyvolanych *= 1.5; 
			}
		 
		 
		  if($this->schopnosti["magieSmrti"]==2) {
          $pocet_vyvolanych= min($this->pocet*mt_rand(1,2),$pocet_vyvolanych);        
			}
		  
		  if($this->schopnosti["magieSmrti"]==9) {
          $pocet_vyvolanych= min($this->pocet*5,$pocet_vyvolanych); 
			}
		  
			if($this->schopnosti["magieSmrti"]==12) {
          $pocet_vyvolanych= min($this->pocet,$pocet_vyvolanych);
          //arcilich s kostěnou flétnou
          if ($this->schopnosti["staze"]==6) $pocet_vyvolanych *= 1.5;
			}
			
			$random = mt_rand(0, 100);

			if($this->schopnosti["magieSmrti"]==6 and $jednotka[$obr]->stav==1 and $jednotka[$obr]->schopnosti["magieOhne"]==1) $jednotka_naz = "Nemrtvý ohnivý kouzelník";

			elseif($this->schopnosti["magieSmrti"]==6 and $jednotka[$obr]->stav==1 and $jednotka[$obr]->schopnosti["magieOhne"]==3) $jednotka_naz = "Zvěstovatel soudného dne";

			elseif($this->schopnosti["magieSmrti"]==6 and $jednotka[$obr]->stav==1 and $jednotka[$obr]->schopnosti["magieLedu"]>0) $jednotka_naz = "Nemrtvý ledový kouzelník";

			elseif($this->schopnosti["magieSmrti"]==6 and $jednotka[$obr]->stav==1 and $jednotka[$obr]->schopnosti["magieLesa"]>0) $jednotka_naz = "Nemrtvý Druid";

			elseif($this->schopnosti["magieSmrti"]==6 and $jednotka[$obr]->stav==1 and ($jednotka[$obr]->ziv>=10000 OR $jednotka[$obr]->nazev == "ledový obr"OR $jednotka[$obr]->nazev == "prokletý obr")) $jednotka_naz = "Obří kostlivec";

			elseif($this->schopnosti["magieSmrti"]==6 and $jednotka[$obr]->stav==1 and $random<=80) $jednotka_naz = "Lich";
	
			elseif($this->schopnosti["magieSmrti"]==9 and $jednotka[$obr]->stav==1) $jednotka_naz = "Ghúl";   
			
			elseif($this->schopnosti["magieSmrti"]==12 and $jednotka[$obr]->stav==1) $jednotka_naz = "Upír";     
			
			elseif($jednotka[$obr]->stav==1)$jednotka_naz = "Kostlivec";

			else $pocet_vyvolanych=0;



			if($pocet_vyvolanych>0){$index=count($jednotka);$jednotka[$index] = new Jednotka($index, $jednotka_naz, $pocet_vyvolanych, $this->strana, 1, $this->barva, "");
			global $global_hodnota;
			$global_hodnota[$this->strana] =$global_hodnota[$this->strana] + ($pocet_vyvolanych*$jednotka[$index]->hod);	

			echo "<span style='color:".$this->barva."'>Jednotka ". $this->pocetJmenoArt() . " vskřísila ".$jednotka[$index]->pocetJmenoArt()."</span><br>";}

			}


		function vyvolat(){

		global $jednotka;$special=0;
    global $zabito_goblinu;

			$od = "Jednotka"; $co = "přivolala celkem";$jak="";

			switch($this->schopnosti["vyvolavaJednotku"]){

				case 242: $jednotka_naz = "Ent";$pocet_vyvolanych = floor($this->celkz/180);if($this->schopnosti["staze"]==5){$pocet_vyvolanych=round($pocet_vyvolanych*1.5);}break;

				case 66: $jednotka_naz = "Starodávný Ent";$pocet_vyvolanych = round($this->celkz/169);if($this->schopnosti["staze"]==50){$pocet_vyvolanych=round($pocet_vyvolanych*1.5);}break;

				case 7: $jednotka_naz = "Ohnivá koule";if($this->schopnosti["posileniOhen"]==1) $pocet_vyvolanych = floor(($this->pocet/2)*1.5); else $pocet_vyvolanych = floor($this->pocet/2);$od="Na rukou jednotky";$co="se vytvořili malé ohnivé koule, za okamžik už letí";$jak=" proti nepříteli!";break;

				case 78: $jednotka_naz = "Ledová koule";$pocet_vyvolanych = floor($this->pocet/2);$od="Na rukou jednotky";$co="se vytvořili malé ledové koule, za okamžik už letí";$jak="!";break;

				case 264:$jednotka_naz = "Vlna tsunami";$pocet_vyvolanych = $this->pocet;$od="Z moře se řítí obrovská masa vody jednotka";break;

				case 17:$jednotka_naz = "Meteorit";$poctar = $this->pocet;while($poctar > 0){$nahoda = unique_random(1,2,1);$pocet_vyvolanych += $nahoda[0];$poctar-=1;} if($this->schopnosti["posileniOhen"]==1){$pocet_vyvolanych = round(($pocet_vyvolanych/3)*1.5);} else $pocet_vyvolanych = round($pocet_vyvolanych/3);$od="Nebesa zabarvila rudá barva jednotka";break;
				
				case 95: $jednotka_naz = "Ohnivý přízrak"; $pocet_vyvolanych=randround(max($this->pocet*0.5,1));if($this->schopnosti["posileniOhen"]==1) $pocet_vyvolanych = randround($pocet_vyvolanych*1.5);   $co="otevřela ohnivý průchod, kterým prošel"; break;
				
				case 650: $jednotka_naz = "Ohnivý déšť"; $poctar = $this->pocet;while($poctar > 0){$nahoda = mt_rand(30,120);$pocet_vyvolanych += $nahoda;$poctar-=1;} if($this->schopnosti["posileniOhen"]==1){$pocet_vyvolanych = round($pocet_vyvolanych * 1.5);} else $pocet_vyvolanych = round($pocet_vyvolanych);$od="Rudé mraky prostoupili Andela věčného ohne. Pak začala z nebe padat ohnivá smrt.";break;

				case 130: $jednotka_naz = "Goblin Paragán"; $pocet_vyvolanych=$this->pocet*100;
                                   if ($this->schopnosti["staze"]==8) $pocet_vyvolanych *= 1.5; $od = "Z"; $co = "vyskákalo";break;
				
				case 1300: $jednotka_naz = "Goblin Paragán"; $pocet_vyvolanych=$this->pocet*200;
				                           if ($this->schopnosti["staze"]==8) $pocet_vyvolanych *= 1.5; $od = "Z"; $co = "vyskákalo";break;
								
				case 13000: $jednotka_naz = "Goblin Paragán"; $pocet_vyvolanych=$this->pocet*400;
				                           if ($this->schopnosti["staze"]==8) $pocet_vyvolanych *= 1.5; $od = "Z"; $co = "vyskákalo";break;

				case 12: $jednotka_naz = "Ohnivý imp";if($this->schopnosti["posileniOhen"]==1){$pocet_vyvolanych=round(($this->pocet*2)*1.5);} else $pocet_vyvolanych=$this->pocet*2;break;

				case 107: $jednotka_naz = "Uvězněná duše";
				           
				           if($this->schopnosti["magieSmrti"]==5) {
				             
				             $poctar = $this->pocet;
                      while($poctar > 0){
                      
                        $vyvolal = mt_rand(150,400);
			                  $pocet_vyvolanych += $vyvolal ;

			                  $poctar-=1;
			                  }
                      
                      $od=""; $co="povolal";break;
                  
				          } else if ($this->schopnosti["magieSmrti"]==10) {
				          	  
				          	  $poctar = $this->pocet;
                      while($poctar > 0){
                      
                        $vyvolal = mt_rand(300,800);
			                  $pocet_vyvolanych += $vyvolal ;

			                  $poctar-=1;
			                  }
                      
                      $od=""; $co="povolal";break;
				          
				          }		
				

				case 89: $jednotka_naz = "Rozžhavené magma";if($this->schopnosti["posileniOhen"]==1){$pocet_vyvolanych=round($this->pocet*1.5);} else $pocet_vyvolanych=$this->pocet; $od=""; $co = "vrhnul proti nepříteli";$jak="!";break;

				case 51: $jednotka_naz = "Energetický služebník";$pocet_vyvolanych=$this->pocet*10;break;

				case 192: $jednotka_naz = "Prokletý Ent";$pocet_vyvolanych = floor($this->celkz/150);if($this->schopnosti["staze"]==5){$pocet_vyvolanych=round($pocet_vyvolanych*1.5);}break;
				
				case 509: $jednotka_naz = "Ledová hradba";$pocet_vyvolanych= floor($this->celkz/250);break;
				
				case 663: $jednotka_naz = "Recyklovanej kočkodlak";$pocet_vyvolanych= mt_rand($this->pocet,$this->pocet*2);break;
				
				case 645: $jednotka_naz = "Stínový drak";$pocet_vyvolanych= mt_rand(0,3);break;
				
				case 68: $jednotka_naz = "Wurm";$pocet_vyvolanych= mt_rand(1,6);break;
				
				case 709: $jednotka_naz = "Stínový ohař";if($this->schopnosti["posileniOhen"]==1) $pocet_vyvolanych = floor(($this->celkz/mt_rand(20,55))*1.5); else $pocet_vyvolanych = floor($this->celkz/mt_rand(20,55));break;
				
				case 710: $jednotka_naz = "Stínová bestie";if($this->schopnosti["posileniOhen"]==1) $pocet_vyvolanych = floor(($this->celkz/mt_rand(20,55))*1.5); else $pocet_vyvolanych = floor($this->celkz/mt_rand(20,55));break;
				
				case 902: $jednotka_naz = "Duše goblina";
//počet duší, které zvládne povolat
	$pocet_vyvolanych = ($this->celkz*(0.95+rand(0,10)/100)) / 10; // pocet zivotov/10 +/- 5%
//pokud má artefakt, muze jich povolat vic	
	if($this->schopnosti["posileni"] > 0) $pocet_vyvolanych = randround(($pocet_vyvolanych*($this->schopnosti["posileni"]+100))/100);
//pokud muze povolat vice dusi nez bylo zabito goblinu	
  if($pocet_vyvolanych > $zabito_goblinu) $pocet_vyvolanych = $zabito_goblinu;
//vyvolané duše odečíst od zabitých goblinů
  if($pocet_vyvolanych > 0){
  $zabito_goblinu -= $pocet_vyvolanych ;
  $this->utk  = round($this->utk/2);
  $this->obr	= round($this->obr/2);
  $this->dmg	= round($this->dmg/2);
  }	
  $od=""; $co=" provedl rituál smrti. Celkem se jim povedlo vytrhnout ze spáru smrti";$jak = " a poštvat je zpátky proti nepříteli.";break;
				
				case 998: $special=2;$pocet_vyvolanych=1;break;

				case 999: $special=1;$pocet_vyvolanych=1;break;

				}

			$index=count($jednotka);

			if($special==0 and $pocet_vyvolanych>0){
			
			//posílení vyvolávací a přivolávací magie
			 if($this->schopnosti["posileni"] > 0 and $this->schopnosti["magieSmrti"] != 13) $pocet_vyvolanych = randround(($pocet_vyvolanych*($this->schopnosti["posileni"]+100))/100);

			echo "<span style='color:".$this->barva."'>";

			$jednotka[$index] = new Jednotka($index, $jednotka_naz, $pocet_vyvolanych, $this->strana, 1, $this->barva, "");
			
			//započítání do celkové hodnoty jednotek
			global $global_hodnota;
			$global_hodnota[$this->strana] =$global_hodnota[$this->strana] + ($pocet_vyvolanych*$jednotka[$index]->hod);		

			echo "$od ". $this->pocetJmenoArt() . " $co $pocet_vyvolanych x ".$jednotka[$index]->toolNazev()."$jak</span><br>";}

			elseif($pocet_vyvolanych>0 and $special==1){

				$magma = mt_rand(($this->pocet*50),($this->pocet*250));
				if($this->schopnosti["posileniOhen"]==1) $magma=round($magma*1.5);
				if ($this->schopnosti["posileni"] > 0) $magma = round(($magma*($this->schopnosti["posileni"]+100))/100);

				$gule = mt_rand(($this->pocet*200),($this->pocet*1500));
				if($this->schopnosti["posileniOhen"]==1) $gule=round($gule*1.5);
				if ($this->schopnosti["posileni"] > 0) $gule = round(($gule*($this->schopnosti["posileni"]+100))/100);

				$mete = mt_rand(($this->pocet*2),($this->pocet*5));
				if($this->schopnosti["posileniOhen"]==1) $mete=round($mete*1.5);
				if ($this->schopnosti["posileni"] > 0) $mete = round(($mete*($this->schopnosti["posileni"]+100))/100);

			global $global_hodnota;
	
				$jednotka[$index] = new Jednotka($index, "Meteorit", $mete, $this->strana, 1, $this->barva, "");
				$global_hodnota[$this->strana] =$global_hodnota[$this->strana] + ($mete*$jednotka[$index]->hod);	

				$jednotka[$index+1] = new Jednotka($index+1, "Rozžhavené magma", $magma, $this->strana, 1, $this->barva, "");
				$global_hodnota[$this->strana] =$global_hodnota[$this->strana] + ($magma*$jednotka[$index+1]->hod);	

				$jednotka[$index+2] = new Jednotka($index+2, "Ohnivá koule", $gule, $this->strana, 1, $this->barva, "");
			  $global_hodnota[$this->strana] =$global_hodnota[$this->strana] + ($gule*$jednotka[$index+2]->hod);	

				echo "<span style='color:".$this->barva."'>Země se roztrhla. K nebesům letí žhavá lává, oheň a kusy skal. <b>".$this->toolNazev()."</b> vytvořil sopku!<br>Sopka vrhla k nebesům " . prevod($magma) . " x ".$jednotka[$index+1]->toolNazev().", " . prevod($mete) . " x ".$jednotka[$index]->toolNazev().", " . prevod($gule) . " x ".$jednotka[$index+2]->toolNazev()."</span><br><br>";

				}

			elseif($pocet_vyvolanych>0 and $special==2){

				$magma = mt_rand(10000,20000);
				if($this->schopnosti["posileniOhen"]==1) $magma=round($magma*1.5);
				if ($this->schopnosti["posileni"] > 0) $magma = round(($magma*($this->schopnosti["posileni"]+100))/100);

				$gule = mt_rand(100000,500000);
        if($this->schopnosti["posileniOhen"]==1) $gule=round($gule*1.5);
        if ($this->schopnosti["posileni"] > 0) $gule = round(($gule*($this->schopnosti["posileni"]+100))/100);
        
				$mete = mt_rand(100,200);
				if($this->schopnosti["posileniOhen"]==1) $mete=round($mete*1.5);
				if ($this->schopnosti["posileni"] > 0) $mete = round(($mete*($this->schopnosti["posileni"]+100))/100);

        global $global_hodnota;
        
				$jednotka[$index] = new Jednotka($index, "Meteorit", $mete, $this->strana, 1, $this->barva, "");
				$global_hodnota[$this->strana] =$global_hodnota[$this->strana] + ($mete*$jednotka[$index]->hod);	

				$jednotka[$index+1] = new Jednotka($index+1, "Rozžhavené magma", $magma, $this->strana, 1, $this->barva, "");
				$global_hodnota[$this->strana] =$global_hodnota[$this->strana] + ($magma*$jednotka[$index+1]->hod);	

				$jednotka[$index+2] = new Jednotka($index+2, "Ohnivá koule", $gule, $this->strana, 1, $this->barva, "");
				$global_hodnota[$this->strana] =$global_hodnota[$this->strana] + ($gule*$jednotka[$index+2]->hod);	

				echo "<span style='color:".$this->barva."'>Rudá mračna zkázy zakryla celé bojiště. Z nebes se řítí žhavá lává, oheň a kusy skal. <b>".$this->toolNazev()."</b> vytvořil ohnivou bouři!<br>Ohnivá bouře vrhla na nepřítele " . prevod($magma) . " x ".$jednotka[$index+1]->toolNazev().", " . prevod($mete) . " x ".$jednotka[$index]->toolNazev().", " . prevod($gule) . " x ".$jednotka[$index+2]->toolNazev()."</span><br><br>";

				}

			}



	function utokNa($obrance){

		global $jednotka; global $aktualniKolo;$bonus=0;
		global $zabito_goblinu;


		if($this->schopnosti["drtivyUtok"]>0 and $jednotka[$obrance]->stav==3) {

			$bonus = $this->schopnosti["drtivyUtok"];

			echo "<span style='color:#778899'>Jednotka ". $this->jmenoArt() . " použíla drtivý útok a získala +".$this->schopnosti["drtivyUtok"]."% do poškození</span><br>";

			}

		if($this->schopnosti["svatyUtok"]>0 and $jednotka[$obrance]->stav==2) {

			$bonus = $this->schopnosti["svatyUtok"];

			echo "<span style='color:#778899'>Jednotka ". $this->jmenoArt() . " použíla svatý útok a získala +".$this->schopnosti["svatyUtok"]."% do poškození</span><br>";

			}

		if($this->schopnosti["jedovyUtok"]>0 and $jednotka[$obrance]->stav==1) {

			$bonus = $this->schopnosti["jedovyUtok"];

			echo "<span style='color:#778899'>Jednotka ". $this->jmenoArt() . " použíla jedový útok a získala +".$this->schopnosti["jedovyUtok"]."% do poškození</span><br>";

			}

		if($aktualniKolo==3 and $this->schopnosti["stec"]>0) echo "<span style='color:".$this->barva."'>Jednotka ". $this->toolNazev() . " v plné rychlosti prošla skrz nepřátelskou lini (v tomto kole získala +".prevod($this->schopnosti["stec"]*$this->pocet)." do poškození)</span><br>";

		echo "<span style='color:".$this->barva."'>". $this->pocetJmenoArt() . " útočí na ".$jednotka[$obrance]->pocetJmenoArt()."</span><br>";

		$dmg = $this->vypocetDMG($bonus);

		$kill = $this->zabitych($dmg);
		
// pokud umrel goblin, zvednout citac zabitych goblinu
   if ($this->isgoblin($jednotka[$obrance]->ident)) $zabito_goblinu += $kill;  
		
		$jednotka[$obrance]->obdrzela_dmg += $dmg;
		
		//kolik jednotka udělala v aktuálním kole dmg
		$this->udelala_dmg = $dmg;
		
		echo "<span style='color:".$this->barva."'>". $this->pocetJmenoArt() . " zmasakroval (".prevod($dmg).") ".prevod($kill)." x ".$jednotka[$obrance]->jmenoArt()." (".prevod($jednotka[$obrance]->celkz).")</span><br>";

		if($this->schopnosti["magieSmrti"]==1 or $this->schopnosti["magieSmrti"]==6 or $this->schopnosti["magieSmrti"]==9) $this->nekromancer($kill, $obrance);
		
		//arcilichove ozivuje nejvýše počet zabitých/2
		if($this->schopnosti["magieSmrti"]==12) $this->nekromancer($kill/2, $obrance);

		return $kill;

		}

}



function aktualizaceInic(){

	$id=0;global $poradi; global $jednotka;

	while($poradi[$id]){

		$poradi[$id][ini] = $jednotka[$id]->ini;

		$poradi[$id][id] = $jednotka[$id]->id;

		$id++;

		}

	}



function parsekJednotky($zdroj, $strana, $barva){

	global $jednotka;

	$utocnici = explode("\n", $zdroj);

	$index=0;

	while($utocnici[$index]){

		$bojovnik = explode(" x ", $utocnici[$index]);
		
		$pocetJednotek = $bojovnik[0];
		$pocetJednotek = trim(Str_Replace(",","",$pocetJednotek));

		$nazevJednotky = $bojovnik[1];

		$art = explode("(", $nazevJednotky);

		if($art[1] != ""){

			$nazevJednotky = $art[0];

			$art = trim(Str_Replace (")", "", $art[1]));

			}

		else{$art="";}

		if(similar_text("velitel klanu",$nazevJednotky)==13) $nazevJednotky="Generál starého impéria";

		$indexTridy = count($jednotka);

		$jednotka[$indexTridy] = new Jednotka($indexTridy, $nazevJednotky, $pocetJednotek, $strana, 0, $barva, $art);

		$index++;

		}

	}



function seradit($orderby){

	$sortarray="";$val="";global $poradi;

	FOREACH ($poradi AS $val)

	{$sortarray[] = $val[$orderby];}

	array_multisort($sortarray,SORT_DESC,$poradi);

	}
	


function seraditrandom(){

global $poradi;

shuffle($poradi);

}

function najit($nazevHodnoty, $hodnota){

	global $poradi;global $jednotka;

	$a=0;

	$id = $poradi[$a][id];

	while($a<count($jednotka)){

		if($jednotka[$id]->$nazevHodnoty == $hodnota || $jednotka[$id]->celkz <= 0 ) {$a++;$id = $poradi[$a][id];}

		else {$id = $poradi[$a][id];break;}

		}

	return $id;

}





function prevod($cislo){

    return number_format($cislo, 0, ".",",");

	}



function magieSvetla(){

	$a=0; global $jednotka; 

	while($jednotka[$a]){

		if($jednotka[$a]->schopnosti["magieSvetla"]>1){

			if($jednotka[$a]->schopnosti["magieSvetla"]==2)     $jednotka[$a]->obr*=1.2;

			elseif($jednotka[$a]->schopnosti["magieSvetla"]==3) $jednotka[$a]->utk*=1.2;

			elseif($jednotka[$a]->schopnosti["magieSvetla"]==4) $jednotka[$a]->ini*=1.2;

			$jednotka[$a]->zaokrouhlit;

			}

		$a++;

		}

}

	parsekJednotky($utocnik, 1, UTK);

	parsekJednotky($obrance, -1, OBR);



$a=0;

while($jednotka[$a]){

	$poradi[$a][ini] = $jednotka[$a]->ini;

	$poradi[$a][celkz] = $jednotka[$a]->celkz;

	$poradi[$a][id] = $jednotka[$a]->id;

	$strana=$jednotka[$a]->strana;

	if($strana == 1) {$ataker .= $jednotka[$a]->vypsat();$hodnota[$strana]+=$jednotka[$a]->pocet*$jednotka[$a]->hod;}

	elseif($strana == -1) {$defender .= $jednotka[$a]->vypsat();$hodnota[$strana]+=$jednotka[$a]->pocet*$jednotka[$a]->hod;}

	$a++;

}

$global_hodnota[1]=$hodnota[1];

$global_hodnota[-1]=$hodnota[-1];



echo "<div style='color:".UTK."'>$ataker</div>";

echo "<div style='color:".OBR."'>$defender</div>";



$pocetKol = 5;

$aktualniKolo = 1;

$zabito_goblinu = 0;//pocet zabitych goblinich jednotek

// echo "<span style='color:".$this->barva."'>".$zabito_goblinu." zabitogoblinu</span><br>";


while($aktualniKolo<=$pocetKol){

	echo "<br><hr color='#c0c0c0'><h3>kolo $aktualniKolo</h3>";

	$index=0;

	magieSvetla();

	while($jednotka[$index]){

    aktualizaceInic(); //tohle tu musí byt, nevím proč, bez toho neseřadí správně dle ini

		seradit("ini");

		$id = "";

		$id = najit("bojovala", 1);

		if($id=="") $id=0;



		//Jednotka ještě nebojovala?

		if($jednotka[$id]->bojovala==0){

      $aktualniUtok = 0;//počet útoků, které jednotka v tomto kole provedla

			//Má jednotka kladný počet životů a současně to není hradba
			if($jednotka[$id]->celkz>0 && ($jednotka[$id]->dmg>0 or $jednotka[$id]->stav!= 3)){



			//Má jednotka dostatek iniciativy?
			if($jednotka[$id]->ini < 0.85) $jednotka[$id]->nemoznoHybat();

			else{

			//Vyvolávání jednotek

      //vyvolání pouze v prvním kole
			if ($aktualniKolo==1 and $jednotka[$id]->schopnosti["vyvolavaJednotku"]>0 and $jednotka[$id]->ident != 651 and $jednotka[$id]->ident != 664 and $jednotka[$id]->ident != 901) $jednotka[$id]->vyvolat(); //anděl vyvolává déšť až od 2. kola, kněží taky
			//vyvolávání v ostatních kolech -energ. služebník, vulcanovo kouzlo, ohnivý dést, rec. kockodlak,duse goblina
			if ($aktualniKolo!=1 and ($jednotka[$id]->schopnosti["vyvolavaJednotku"]==51 or $jednotka[$id]->schopnosti["vyvolavaJednotku"]==998 or $jednotka[$id]->schopnosti["vyvolavaJednotku"]==650 or $jednotka[$id]->schopnosti["vyvolavaJednotku"]==663)) $jednotka[$id]->vyvolat();
			



			//Schopnosti prováděné před samotným útokem

			if($aktualniKolo == 1){

				if($jednotka[$id]->schopnosti["dav"]==1) $jednotka[$id]->dav();

				if($jednotka[$id]->schopnosti["temnykrik"]==1) $jednotka[$id]->temnykrik();
				
				if($jednotka[$id]->schopnosti["silaGoblinu"]==1) $jednotka[$id]->silaGoblinu();

				if($jednotka[$id]->schopnosti["magieSvetla"]==5) $jednotka[$id]->magieSvetla5();
				
				if($jednotka[$id]->schopnosti["magieLedu"]==7) $jednotka[$id]->magieLedu7();

				if($jednotka[$id]->schopnosti["magieVody"]==1) $jednotka[$id]->magieVody1();
				
				if($jednotka[$id]->schopnosti["magieZeme"]==2) $jednotka[$id]->magieZeme2();

				if($jednotka[$id]->schopnosti["magieOhne"]==17) $jednotka[$id]->magieOhne17();
				
				if($jednotka[$id]->schopnosti["magiePrastarych"]==4) $jednotka[$id]->magiePrastarych4();

				}
				
			if(($aktualniKolo == 1 or $aktualniKolo == 2) and $jednotka[$id]->schopnosti["magiePrastarych"]==3){ 
			$jednotka[$id]->magiePrastarych3();
//			$aktualniUtok = 1; //krvavý rituál se pokládá za útok
			}

			if(($aktualniKolo == 1 or $aktualniKolo == 2) and $jednotka[$id]->schopnosti["staze"]==1) $jednotka[$id]->staze();

			if($jednotka[$id]->schopnosti["magieSvetla"]==2) $jednotka[$id]->svetlo("naše zbroje");

			elseif($jednotka[$id]->schopnosti["magieSvetla"]==3) $jednotka[$id]->svetlo("naše zbraně");

			elseif($jednotka[$id]->schopnosti["magieSvetla"]==4) $jednotka[$id]->svetlo("naši rychlost");



			//Může jednotka provést útok v tomto kole ?

			IF (($aktualniKolo == 1 and ($jednotka[$id]->typ == 4 or $jednotka[$id]->typ == 3)) or ($aktualniKolo == 2 and ($jednotka[$id]->typ == 4 or $jednotka[$id]->typ == 3 or $jednotka[$id]->typ == 2)) or (($aktualniKolo == 3 or $aktualniKolo == 4 or $aktualniKolo == 5) and ($jednotka[$id	]->typ == 4 or $jednotka[$id]->typ == 1 or $jednotka[$id]->typ == 2))){



					$opak_strany =  $jednotka[$id]->strana;

//			  $aktualniUtok = 0;

					while($aktualniUtok < $jednotka[$id]->pocetUtoku and $jednotka[$id]->celkz > 0){
					

						$idx=0;

						while($jednotka[$idx]){

							$poradi[$idx][ini] = $jednotka[$idx]->ini;

							$poradi[$idx][celkz] = $jednotka[$idx]->celkz;

							$poradi[$idx][id] = $jednotka[$idx]->id;$idx++;

							}


						//Seřadit obránce dle počtu životů

						seradit("celkz");

						$id_obrance = najit("strana", $opak_strany);

							echo "<div>";
							
						//chaoticka hydra utoci  nepredvidatelnosti
						if($jednotka[$id]->schopnosti["nepredvidatelnost"]==1) {
								
							  seraditrandom();

                $id_obrance = najit("strana", $opak_strany);
								
								
								}
								
						if($jednotka[$id]->ini < 0.85) $jednotka[$id]->nemoznoHybat();

            else{
            

							//Je zde na koho útočit?

							if($jednotka[$id_obrance]->celkz<=0){
							
							 //pokud jednotka má schopnost léčit, bude léčit i když nemá na koho útočit
                if($jednotka[$id]->schopnosti["magieLesa"] == 4) $jednotka[$id]->magieLesa4();
                if($jednotka[$id]->schopnosti["magieLesa"] == 5) $jednotka[$id]->magieLesa5();

								$jednotka[$id]->nemoznoUtocit();

								}

							else{
							
							  //proběhne utok, vše co se má zakouzlit před ním je třeba dát nad toto
								$zabito = $jednotka[$id]->utokNa($id_obrance);

								if($jednotka[$id]->schopnosti["sabotaz"]==1) $jednotka[$id]->sabotaz($id_obrance);

								if($jednotka[$id]->schopnosti["magieEternanu"]==2 and mt_rand(1,100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"]) $jednotka[$id]->magieEternanu2($id_obrance);					
								
								if($jednotka[$id]->schopnosti["magieSmrti"]==4 and $jednotka[$id_obrance]->stav==1 and mt_rand(1,100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"]) $jednotka[$id]->magieSmrti4($id_obrance);

								elseif($jednotka[$id]->schopnosti["magieSmrti"]==8 and mt_rand(1,100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"]) $jednotka[$id]->magieSmrti8($id_obrance);
								
								if($jednotka[$id]->schopnosti["magieSmrti"]==11 and $jednotka[$id_obrance]->stav==1 and mt_rand(1,100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"]) $jednotka[$id]->magieSmrti11($id_obrance);


								if($jednotka[$id]->schopnosti["magieSmrti"]==3 and $jednotka[$id_obrance]->stav==1 and mt_rand(1,100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"]) $jednotka[$id]->magieSmrti3($id_obrance);

								if($jednotka[$id]->schopnosti["magieLedu"]==1 and mt_rand(1,100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"] and mt_rand(1,100) > $jednotka[$id_obrance]->schopnosti["imunitaLed"]) $jednotka[$id]->magieLedu1($id_obrance);

								elseif($jednotka[$id]->schopnosti["magieLedu"]==2 and mt_rand(1,100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"] and mt_rand(1,100) > $jednotka[$id_obrance]->schopnosti["imunitaLed"]) $jednotka[$id]->magieLedu2($id_obrance);

								elseif($jednotka[$id]->schopnosti["magieLedu"]==4 and mt_rand(1,100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"] and mt_rand(1,100) > $jednotka[$id_obrance]->schopnosti["imunitaLed"]) $jednotka[$id]->magieLedu4($id_obrance);

								elseif($jednotka[$id]->schopnosti["magieLedu"]==5 and mt_rand(1,100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"] and mt_rand(1,100) > $jednotka[$id_obrance]->schopnosti["imunitaLed"]) $jednotka[$id]->magieLedu5($id_obrance);
								
								if($jednotka[$id]->schopnosti["kanibalizmus"] != 0 and $jednotka[$id_obrance]->stav==1) $jednotka[$id]->kanibalizmus($jednotka[$id]->udelala_dmg);
								
								if ($jednotka[$id]->schopnosti["vyvolavaJednotku"]==902)  $jednotka[$id]->vyvolat();




								if($jednotka[$id_obrance]->schopnosti["ohnivyStit"]>0 and $jednotka[$id]->schopnosti["imunitaOhen"]!=1 and $jednotka[$id]->schopnosti["ohnivyStit"]=="" and $jednotka[$id]->typ==1) $jednotka[$id]->ohnivyStit($id_obrance, $zabito);

								if($jednotka[$id_obrance]->schopnosti["toxickyStit"]>0 and $jednotka[$id]->schopnosti["toxickyStit"]=="" and $jednotka[$id]->typ==1 and $jednotka[$id]->stav==1) $jednotka[$id]->toxickyStit($id_obrance, $zabito);

								if($jednotka[$id_obrance]->schopnosti["ledovyStit"]>0 and $jednotka[$id]->schopnosti["ledovyStit"]=="" and $jednotka[$id]->typ==1) $jednotka[$id]->ledovyStit($id_obrance);

								if($zabito>0 and $jednotka[$id_obrance]->schopnosti["vzkryseni"]) $jednotka[$id_obrance]->vzkryseni($zabito);

								if($jednotka[$id]->schopnosti["exterminace"] == 1 and $jednotka[$id_obrance]->stav<3) $jednotka[$id]->exterminace($id_obrance);

								if($jednotka[$id]->schopnosti["magieZeme"] == 1 and mt_rand(1,100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"]) $jednotka[$id]->magieZeme1($id_obrance);

								if($jednotka[$id]->schopnosti["magieVody"] == 3 and mt_rand(1,100) > $jednotka[$id_obrance]->schopnosti["imunitaMagie"]) $jednotka[$id]->magieVody3($id_obrance);

								if($jednotka[$id]->schopnosti["magieSvetla"] == 1 and $jednotka[$id_obrance]->stav==2) $jednotka[$id]->magieSvetla1($id_obrance);
								
								if($jednotka[$id]->schopnosti["sebevrazedna"] and $jednotka[$id]->schopnosti["viceutok"]>1 and ( $jednotka[$id_obrance]->celkz<=0 or $jednotka[$id_obrance]->obdrzela_dmg >= $jednotka[$id_obrance]->poc_celkz )){

                  $jednotka[$id_obrance]->obdrzela_dmg = $jednotka[$id_obrance]->poc_celkz - $jednotka[$id_obrance]->celkz; //když meteorit zničí fenixe a ti se ozivi, musi se zase "vynulovat" obdrzena dmg
                  
									$aktualniUtok-=1;

									$jednotka[$id]->sebevrazda();

									}

								elseif($jednotka[$id]->schopnosti["sebevrazedna"]) $jednotka[$id]->finalniSebevrazda();

								//pokud jednotka má schopnost léčit, bude léčit poté, co zaútočí (pokud je naživu)
                  if($jednotka[$id]->schopnosti["magieLesa"] == 4 && $jednotka[$id]->celkz > 0) $jednotka[$id]->magieLesa4();
                  if($jednotka[$id]->schopnosti["magieLesa"] == 5 && $jednotka[$id]->celkz > 0) $jednotka[$id]->magieLesa5();
					
								}//konec podmínky, kdy jednotka má na koho utocit
								                         		
								
							} //konec podmínky když má jednotka dostatek inic pro útok

							echo "</div><br>";



						$aktualniUtok++;
						

						}

					}

				}

			}

			//$index=-1;

			}

		if($jednotka[$id]->bojovala==0) $jednotka[$id]->bojovala=1;



		while($jednotka[$a]){

			$poradi[$a][ini] = $jednotka[$a]->ini;

			$poradi[$a][id] = $jednotka[$a]->id;

			$a++;

			}



		$index++;

		}



	$a=0;

	$zmena="";

	while($jednotka[$a]){

		$jednotka[$a]->bojovala=0;

		$jednotka[$a]->obnovitIni();

		$poradi[$a][ini] = $jednotka[$a]->ini;

		$poradi[$a][id] = $jednotka[$a]->id;

		$strana=$jednotka[$a]->strana;

		if($strana == 1) {$zmena[$strana]+=$jednotka[$a]->pocet*$jednotka[$a]->hod;}

		elseif($strana == -1) {$zmena[$strana]+=$jednotka[$a]->pocet*$jednotka[$a]->hod;}

		$a++;

		}

	if($aktualniKolo!=5){

	echo "<br>Zprávy z bojiště:<br><div style='color:#C0C0C0'>Ztratili jsme za poslední kolo <span style='color:#B22222'>" . prevod($hodnota[1]-$zmena[1]) . "</span> (" . number_format((100*($hodnota[1]-$zmena[1]))/$global_hodnota[1], 2, '.', ' ') . "%) hodnoty armády. Zůstává nám " . prevod($zmena[1]) . " (" . number_format((100*$zmena[1])/$global_hodnota[1], 2, '.', ' ') ."%)<br> Protivníkovy jsme za poslední kolo zničili <span style='color:#B22222'>" . prevod($hodnota[-1]-$zmena[-1]) . "</span> (" . number_format((100*($hodnota[-1]-$zmena[-1]))/$global_hodnota[-1], 2, '.', ' ') . "%) hodnoty armády. Protivníkovy zůstává " . prevod($zmena[-1]) . " (" . number_format((100*$zmena[-1])/$global_hodnota[-1], 2, '.', ' ') ."%)</div><br>";}



	$hodnota[1]-=$hodnota[1]-$zmena[1];

	$hodnota[-1]-=$hodnota[-1]-$zmena[-1];



	$aktualniKolo++;

	}



echo "--------------------------------------------------------------<br><br>";

ECHO "<div style='color:#b0c4de'>Útočník přežilo:<br>";



$a=0;$hodnota="";$ataker="";$defender="";

while($jednotka[$a]){

	$strana=$jednotka[$a]->strana;

	if($strana == 1) {if($jednotka[$a]->vyvolana==1) {$ataker .= "<div style='color:#708090'>";$hodnota[$strana]+=$jednotka[$a]->pocet*$jednotka[$a]->hod;/*započítáme hodnotu i když je vyvolaná*/} else{$ataker .= "<div>";$hodnota[$strana]+=$jednotka[$a]->pocet*$jednotka[$a]->hod;}$ataker .= $jednotka[$a]->vypsat();$ataker .="</div>";}

	elseif($strana == -1) {$defender .= $jednotka[$a]->vypsat();$hodnota[$strana]+=$jednotka[$a]->pocet*$jednotka[$a]->hod;}

	$a++;

	}



echo "<div style='color:".UTK."'>$ataker</div>";

ECHO "Celkem hodnota zabité armády: " . prevod($global_hodnota[1]-$hodnota[1]) . "/" . prevod($global_hodnota[1]) . " (" . number_format((100*($global_hodnota[1]-$hodnota[1]))/$global_hodnota[1], 2, ',', ' ') ."%)<br><br></div>";

  echo "Obránce přežilo:<br>";

echo "<div style='color:".OBR."'>$defender</div>";

ECHO "Celkem hodnota zabité armády: " . prevod($global_hodnota[-1]-$hodnota[-1]) . "/" . prevod($global_hodnota[-1]) . " (" . number_format((100*($global_hodnota[-1]-$hodnota[-1]))/$global_hodnota[-1], 2, ',', ' ') ."%)<br><br></div>";


echo "<center><input type='submit' onClick=\"ajaxFunction()\" value=\"BOJ!\" class='boj'></center><br>";

$cas2 = explode(" ", microtime());

echo "<center>" . (round((($cas2[1] + $cas2[0]) - $cas1) * $rd)) / $rd . "s</center>";

}

?>
