<?php

$lang['plunder_header']		= 'Plenění a bojiště';

$lang['plunder_desc']			= 'Místa k plenění a bojiště jsou společná pro všechny hráče. Mohou je dobýjet jen 1x za určitou dobu, pak jsou zablokovaná. Navíc je útok na tyto místa velice náročný a proto může tvá armáda napadnout jen jedno místo za den.';
$lang['plunder_desolation']		= 'Plenění';
$lang['plunder_battle_field']	= 'Bojiště';
$lang['plunder_final_battle']	= 'Finální bitva';

$lang['plunder_total_atk']		= 'Celkem dobyto';

$lang['plunder_spec_need_build']	= 'Nejdřív potřebujeme dostavět všechny budovy';
$lang['plunder_spec_giant_good']	= 'Nemůžeme pomáhat obrům zla';
$lang['plunder_spec_giant_evil']	= 'Nemůžeme pomáhat obrům dobra';
$lang['plunder_spec_fortress']		= 'Na pevnost je možné zaútočit až %s dnů před koncem';
$lang['plunder_spec_remains_one']	= 'zbývá %s den';
$lang['plunder_spec_remains_more']	= 'zbývá %s dnů';

$lang['plunder_atk_weak']			= 'Naše armáda je vyčerpaná';
$lang['plunder_atk_prepare']		= 'Na takovýto útok potřebujeme přípravu';
$lang['plunder_atk_link']			= 'Zaútočit';
$lang['plunder_atk_to']				= 'Zaútočit na';
$lang['plunder_atk_event_prepare']	= 'V přípravě...';
$lang['plunder_atk_at']				= 'Na tohle místo je možné zaútočit za %s';

$lang['plunder_event_desc']		= array(
	''	=>	'Síla a odměna u těchto míst roste s každým dobytím',
	2	=>	'Tato místa mají konstantní sílu',
	3	=>	'Zvláštní druh plenění, síla neroste',
	4	=>	'Po sedmém dobytí tohoto místa končí věk',
);

$lang['plunder_events'] = array(
	1	=> array(
		'name'		=> 'Spolek Mocných',
		'desc'		=> 'Ve spolku mocných jsou silní čarodějové a mágové z Land of Ice. Můžeme zde získat velké množství run',
		'desc_long'	=> 'Je to místo, kde nejmocnější z kouzelníků na Land of Ice mají svůj chrám, pokud se ti jej podaří dobýt, získáš kromě slávy i několik run.',
	),
	2	=> array(
		'name'		=> 'Gnomí velkodílna',
		'desc'		=> 'V Gnomí velkodílně se vyrábějí těžké obléhací stroje.',
		'desc_long'	=> 'Gnomové zde vyrábí velké množství kvalitních obléhacích prostředků.',
	),
	3	=> array(
		'name'		=> 'Temná jeskyně',
		'desc'		=> 'Co za hrůzy skrývá Temná jeskyně?',
		'desc_long'	=> 'Nikdo neví co se uvnitř skrývá.',
	),
	4	=> array(
		'name'		=> 'Iluzionární Ostrov',
		'desc'		=> 'Že by to byla jen iluze?',
	),
	5	=> array(
		'name'		=> 'Katakomby',
		'desc'		=> 'Pach smrti se line z katakomb...',
	),
	6	=> array(
		'name'		=> 'Svobodné město',
		'desc'		=> 'Nejen démoni vlastní města. Některá se brání jejich nadvládě, ale i všemu okolo.',
		'desc_long'	=> 'Některá města se postavila svému osudu a osvobodila se od nadvlády démonů. Většinou se jedná o pohraniční města a tak je démoni nechávají na pokoji, protože je zaměstnávají klany. '
						. 'Tyto města je možné postupně dobýt a připojit k našemu klanu.',
	),
	7	=> array(
		'name'		=> 'Válka na Gargantue',
		'desc'		=> 'Pomoct obrům dobra proti obrům zla',
		'desc_long'	=> 'Pomoct obrům dobra proti obrům zla. Více informací se dozvíme v <a href="help/main.php?hledej=valka_obru" target="_blank">knihovně</a>',
	),
	8	=> array(
		'name'		=> 'Válka na Gargantue',
		'desc'		=> 'Pomoct obrům zla proti obrům dobra',
		'long_desc'	=> 'Pomoct obrům zla proti obrům dobra. Více informací se dozvíme v <a href="help/main.php?hledej=valka_obru" target="_blank">knihovně</a>',
	),
	9	=> array(
		'name'		=> 'Zěmě zatracených',
		'desc'		=> 'Kdysi se zde odehrávaly obrovské bitvy. Na bitevních polích zůstaly statisíce mrtvých, '
						. 'protože nezbylo dostatek živých, aby pohřbili všechna těla.',
		'long_desc'	=> 'Jsou části Land of Ice, kde se kdysi odehrávali obrovské bitvy. Na bitevních polích zůstali statisíce mrtvých, protože živých nezbylo dostatek, '
						. 'aby pohřbili všechna těla. Jejich duše zde zůstaly a hlídají magické předměty mocných válečníků, kteří zde zemřeli.',
	),
	10	=> array(
		'name'		=> 'Temný les',
		'desc'		=> 'Vysoké a husté větve nepropustí žádné světlo. Kdo ví, co se skrývá v temném lese...',
		'desc_long'	=> 'Strach z tohoto místa je pověstný. Někteří lidé říkají jaká velká prokletá monstra zde žijí. Jiní zase zmiňují mocné mágy užívající temnou magii...',
	),
	11	=> array(
		'name'		=> 'Otrokářská kolonie',
		'desc'		=> 'Vykupují lidi, ale co takhle jim nějaké zabavit?',
		'desc_long'	=> 'Pokud se nám jí podaří dobýt můžeme zabavit velké množství lidí, které pak můžeme například prodat otrokářům.',
	),
	12	=> array(
		'name'		=> 'Ledový Palác',
		'desc'		=> 'Obrovský ledový palác skrývá velice mrazivá tajemství.',
		'desc_long'	=> 'Na trůnu z čistého ledu, sedí Ledový král. Kdo se opováží napadnout jeho palác, ten bude zničen...',
	),
	13	=> array(
		'name'		=> 'Dralgarova Zahrada',
		'desc'		=> 'Obrovský chrám zasvěcený Dralgarovi Imagarovi',
		'desc_long'	=> 'Gigantický chrám vytesaný do skály. Kolem něj je neprostupný hvozd.',
	),
	14	=> array(
		'name'		=> 'Železné hory',
		'desc'		=> 'Hory obývají trpaslíci, kteří vzývají železného boha.',
		'desc_long'	=> 'Železný hory patří mezi jedny z nejvyšších hor na Land of Ice. Jsou také bohaté na nerostné suroviny, a proto se zde usadil klan trpaslíku.'
						. 'Tihle trpaslíci jsou ale velice podivní. Říká se, že vzývají železného boha, o kterém nikdy nikdo neslyšel.',
		'links'	=> array(
			1 => 'Hlídku u úpatí hor',
			2 => 'Strážní věž',
			3 => 'Citadelu',
			4 => 'Železný chrám',
		),
	),
	50	=> array(
		'name'		=> 'Prokletý vojevůdce',
		'desc'		=> 'Troufneš si proti prokletému vojevůdci Dehinatorovi?',
		'desc_long'	=> 'Dokážeš dobýt Temnou pevnost, kde sídlí <a href="http://landofice.com/wiki/index.php?title=Dehinator" target="_blank">Dehinator</a>?',
	),
	51	=> array(
		'name'		=> 'Prokletá citadela',
		'desc'		=> 'Prokletá citadela je sídlem Královny medúz...',
		'desc_long'	=> 'Dokážeš dobýt Prokletou citadelu, domov <a href="http://landofice.com/wiki/index.php?title=Královna_Medúz" target="_blank">Královny Medúz</a>?',
	),
	52	=> array(
		'name'		=> 'Údolí stínů',
		'desc'		=> 'Tajemstvím opředené údolí skrývá stíny těch nejmocnějších armád',
		'desc_long'	=> 'Legendární <a href="http://landofice.com/wiki/index.php?title=Údolí_stínů" target="_blank">údolí stínů</a> skrývá stíny těch nejmocnějších armád...',
	),
	53	=> array(
		'name'		=> 'Mucusova pevnost',
		'desc'		=> 'Pevnost krále Toxických elementálů má vysoké zdi pokryté jedovatým slizem.',
		'desc_long'	=> 'Pevnost Mucuse krále toxických elementálů. Chrání ji velké hradby pokryté toxickým slizem.',
	),
	99	=> array(
		'name'		=> 'Sedm pevností',
		'desc'		=> 'Sedm pevností zásobuje Bránu energií. Pokud bude dobyto všech sedm Brána se zavře.',
		'desc_long'	=> 'Musí být dobyto všech sedm. Není jiné možnosti, není jiné záchrany...',
		'type_att'	=> 'Dobýt'
	),
);



$lang['plunder_']		= '';
?>

<?php // echo $this->lang->line('');
 