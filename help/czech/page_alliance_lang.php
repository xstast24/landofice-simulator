<?php

$lang['alliance_header'] = 'Aliance %s';

$lang['alliance_link_chat']		= 'Alianční chat';
$lang['alliance_link_bank']		= 'Klanová pokladnice';
$lang['alliance_link_campaign']	= 'Tažení';
$lang['alliance_link_list']		= 'Seznam Aliancí';
$lang['alliance_link_wars']		= 'Kronika válek aliancí';

// zoznam aliancii
$lang['alliance_lists_header']		= 'Seznam aliancí';
$lang['alliance_lists_tb_name']		= 'Název Aliance';
$lang['alliance_lists_tb_desc']		= 'Popis aliance';
$lang['alliance_lists_tb_val']		= 'Hodnota armády aliance';
$lang['alliance_lists_ali_page']	= 'Alianční stránky';
$lang['alliance_lists_good_member']	= 'Opravdu v alianci %s najdeme dobré spojence?';
$lang['alliance_lists_send_inv']	= 'Požádat o vstup';

// spehovanie aliancie
$lang['alliance_spy_header']		= 'Aliance %s';
$lang['alliance_spy_power']			= 'Aktuální síla: %s';
$lang['alliance_spy_members']		= 'Členové aliance';
$lang['alliance_spy_tb_id']			= 'ID';
$lang['alliance_spy_tb_commander']	= 'Velitel';
$lang['alliance_spy_tb_clan']		= 'Klan';
$lang['alliance_spy_tb_army']		= 'Armáda';
$lang['alliance_spy_chief']			= 'Správce aliance';
$lang['alliance_spy_send_msg']		= 'Poslat posla';
$lang['alliance_spy_link']			= 'Vyslat špehy';

// poziadanie o vstup do ali
$lang['alliance_inv_have_ali']		= 'Veliteli, my už jsme členem aliance.';
$lang['alliance_inv_no_exist'] 		= 'Aliance duchů je pro nás nepřístupná. Nejdřív bychom museli zemřít!';
$lang['alliance_inv_max_members']	= 'Aliance %s již bohužel má %s členů.';
$lang['alliance_inv_send']			= 'Byl odeslán požadavek o přijetí do aliance %s!';

// vytvorenie aliancie
$lang['alliance_craete_header']			= 'Založení aliance';
$lang['alliance_craete_price']			= 'Cena: %s zlatých, %s předzvěsti a %s věhlasu';
$lang['alliance_craete_form_name']		= 'Název';
$lang['alliance_craete_form_desc']		= 'Popis';
$lang['alliance_craete_form_btn']		= 'Vytvořit';
$lang['alliance_craete_msg_resource']	= 'Možnosti tvého kmene nejsou dostatečné na založení aliance';
// zmena vlastnosti ali
$lang['alliance_change_header']		= 'Správa aliance';
$lang['alliance_change_btn_url']	= 'Nastavit stránku';
$lang['alliance_change_btn_desc']	= 'Upravit popis';

// poziadavky
$lang['alliance_have_invitation']	= 'Máme požadavek na vstup do aliance: %s';
$lang['alliance_no_invitation']		= 'Nemáme požadavek na vstup do žádné aliance';
$lang['alliance_no_invitations']	= 'Nemáme žádné žádosti o vstup do aliance.';
$lang['alliance_invitation_request']= 'Velitel %s klanu %s nás žádá o přijetí.';
$lang['alliance_reject_invitation']	= 'Pane, náš kmen nebyl přijat do té jejich směšné aliance...';
$lang['alliance_invitation_header']	= 'Žádosti o přijetí';
$lang['alliance_invitation_max_members']= 'Aliance již bohužel má %s členů';
$lang['alliance_invitation_welcome']	= 'Vítej mezi námi...';
$lang['alliance_invitation_reject']	= 'Zamítnout jej';
$lang['alliance_invitation_confirm']= 'Přijmout jej';

// uvodna stranka aliancie
$lang['alliance_home_is_member']	= 'Jsme členem aliance %s';
// valky
$lang['alliance_war_tb_ali']	= 'Aliance';
$lang['alliance_war_tb_type']	= 'Druh války';
$lang['alliance_war_tb_time']	= 'Čas výzvy';
$lang['alliance_war_tb_begin']	= 'Začátek';
$lang['alliance_war_tb_status']= 'Status';
$lang['alliance_war_types']	= array(
	1 => "Potyčka", 2 => "Nájezdy", 3 => "Dobyvačná", 4 => "Totální"
);
$lang['alliance_war_types_long_1']	= "Potyčka (bojuje se jen o předzvěst a věhlas), cena %s zlatých";
$lang['alliance_war_types_long_2']	= "Nájezd (bojuje se o suroviny), cena %s zlatých";
$lang['alliance_war_types_long_3']	= "Dobývání (bojuje se o suroviny a města), cena %s zlatých";
$lang['alliance_war_types_long_4']	= "Totální (naprostá válka), cena %s zlatých";
$lang['alliance_war_status']	= array(
	'wait'	=> "Čeká na potvrzení",
	'conf'	=> "Potvrzená",
	'win'	=> "Ukončená vítězstvím",
	'lose'	=> "Ukončená porážkou",
	'cancel'=> "Zrušena"
);
$lang['alliance_war_atk']		= 'Zaútočit';
// potvrdit vojnu
$lang['alliance_war_confirm_err']	= 'Další válku nemůžeme přijmout, dokud nedobojujeme tuhle!';
$lang['alliance_war_confirm_ok']	= 'Válka byla přijata!';
// valky nami vyhlasene
$lang['alliance_cwar_header']	= 'Války námi vyhlášené';
$lang['alliance_cwar_nowar']	= 'Zatím jsme nevyhlásili žádnou válku ...';
// valky proti nam vyhlasene
$lang['alliance_dwar_header']	= 'Války proti nám vyhlášené';
$lang['alliance_dwar_nowar']	= 'Zatím nám ještě nikdo válku nevyhlásil ...';
$lang['alliance_dwar_conf']	= 'Přijmout výzvu';
// vyhlasit vojnu
$lang['alliance_war_declare_header']	= 'Vyhlásit válku alianci';
$lang['alliance_war_declare_who']		= 'ID aliance';
$lang['alliance_war_declare_who_valid']= 'Cíl útoku';
$lang['alliance_war_declare_type']		= 'Druh války';
$lang['alliance_war_declare_time']		= 'Vyhlásit válku na den: %s měsíc: %s rok: %s hodinu: %s minutu: %s ';
$lang['alliance_war_declare_note']		= 'Pokud protivník nepotvrdí válku do času jejího začátku, je válka zrušena (peníze se nevracejí)';
$lang['alliance_war_declare_help']		= 'VÍCE INFO';
$lang['alliance_war_declare_day']		= 'den';
$lang['alliance_war_declare_month']	= 'měsíc';
$lang['alliance_war_declare_year']		= 'rok';
$lang['alliance_war_declare_hour']		= 'hodina';
$lang['alliance_war_declare_minute']	= 'minuta';
$lang['alliance_war_declare_msg_myself']	= 'Pane, také s vámi souhlasím, že by bylo správné udělat čistku, ale levnější bude je prostě vyházet...';
$lang['alliance_war_declare_msg_we_weak']	= 'Nemáme dostatečné množství bojeschopných klanů (%s)';
$lang['alliance_war_declare_msg_no_gold']	= 'Válku si nemůžeme dovolit';
$lang['alliance_war_declare_msg_bad_time']	= 'Nemůžeme vyhlásit válku na svatého Dindy';
$lang['alliance_war_declare_msg_no_type']	= 'Takovou válku ještě nevymysleli.';
$lang['alliance_war_declare_msg_they_weak']= 'Protivník nemá dostatečné množství bojeschopných klanů (%s)';
$lang['alliance_war_declare_msg_ok']		= 'K vyhlášení války dojde za 10 sekund...';
$lang['alliance_war_declare_send_msg']		= 'Tímto vaší alianci vyzývám k válce. Váš velitel jí může čestně přijmout, anebo zbaběle odmítnout!';
// clenova aliance
$lang['alliance_member_header']			= 'Členové aliance';
$lang['alliance_member_exclude']		= 'Vyloučit z aliance';
$lang['alliance_member_exclude_conf']	= 'Opravdu si to ten chudák zaslouží?';
$lang['alliance_member_exclude_msg']	= 'Váš kmen byl vyloučen z aliance %s. Ztratili jsme i nějaký věhlas a předzvěst.';
$lang['alliance_member_change_chief']	= 'Předat správcovství';
$lang['alliance_member_change_chief_mag']	= 'Pane, máme správcovství!';
$lang['alliance_member_demon_atk']		= 'Město tohoto klanu je v obležení démona. Můžeme jej osvobodit.';

// opustenie aliancie
$lang['alliance_leave_link']		= 'Opustit Alianci';
$lang['alliance_leave_link_conf']	= 'Pokud alianci opustíme, ztratíme svoji hrdost!';
$lang['alliance_leave_price']		= 'bude nás to stát %s&#37; věhlasu a %s&#37; předzvěsti';
$lang['alliance_leave_goodbye']		= 'Opouštím Vás, mí spolubojovníci...<br>Velitel %s, klan %s';

// {$this->lang->line('')}
 
