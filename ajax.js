var time_variable;


function getXMLObject()  //XML OBJECT
{
    var xmlHttp = false;

    try {
        xmlHttp = new ActiveXObject("Msxml2.XMLHTTP")  // For Old Microsoft Browsers
    } catch (e) {
        try {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP")  // For Microsoft IE 6.0+
        } catch (e2) {
            xmlHttp = false   // No Browser accepts the XMLHTTP Object then false
        }
    }

    if (!xmlHttp && typeof XMLHttpRequest != 'undefined') {
        xmlHttp = new XMLHttpRequest();        //For Mozilla, Opera Browsers
    }

    return xmlHttp;  // Mandatory Statement returning the ajax object created
}


var xmlhttp = new getXMLObject();	//xmlhttp holds the ajax object


function ajaxFunction() {
    showLoadingAnimation()

    var ut;

    var ob;

    var getdate = new Date();  //Used to prevent caching during ajax call

    if (xmlhttp) {
        xmlhttp.open("POST", "simulator_engine.php", true); //calling testing.php using POST method

        xmlhttp.onreadystatechange = handleServerResponse;

        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        ut = document.getElementById("ut").value.trim(); //get value from field and strip any excessive whitespaces around it

        ob = document.getElementById("ob").value.trim();

        //cesta = document.getElementById("cesta").value;	

        xmlhttp.send("ut=" + ut + "&ob=" + ob); //Posting txtname to PHP File
    }
}

function handleServerResponse() {
    if (xmlhttp.readyState == 4) {
        hideLoadingAnimation()
        if (xmlhttp.status == 200) {
            document.getElementById("result").innerHTML = xmlhttp.responseText;
        } else {
            alert("Error during AJAX call. Please try again");
        }
    }
}


function zobrazit() {
    // zobrazi/skryje seznam vsech eventu, kde si hrac muze vybrat
    const seznamEventu = document.getElementById("eventy")
    seznamEventu.style.display === 'none' ? seznamEventu.style.display = 'block' : seznamEventu.style.display = 'none'
}


function selectButton(el) {
    if (el == 2) {
        //select button 2 (activate input field for defender)
        let button2 = document.getElementById("selectionButon2")
        button2.textContent = '⬇';
        button2.setAttribute('class', 'selectionButtonActive');
        //de-select button 1
        let button1 = document.getElementById("selectionButon1")
        button1.textContent = '';
        button1.setAttribute('class', 'selectionButtonInactive');
    } else {
        //select button 1 (activate input field for attacker)
        let button1 = document.getElementById("selectionButon1")
        button1.textContent = '⬇';
        button1.setAttribute('class', 'selectionButtonActive');
        //de-select button 2
        let button2 = document.getElementById("selectionButon2")
        button2.textContent = '';
        button2.setAttribute('class', 'selectionButtonInactive');
    }
}


function aktivniPole() {
    let pole = document.getElementById("selectionButon1").getAttribute('class') === 'selectionButtonActive' ? 'ut' : 'ob'
    return document.getElementById(pole)
}

function pole(obsah) {
    aktivniPole().value = obsah;
}

function pridej(obsah) {
    aktivniPole().value += obsah;
}


function putServerResponseIntoActiveArmyField() {
    //put result (list of units) of the http request into the active army field (attacker/defender)
    if (xmlhttp.readyState == 4) {
        if (xmlhttp.status == 200) {
            aktivniPole().value = xmlhttp.responseText;
        } else {
            alert("Error during AJAX call. Please try again");
        }
    }
}

function ajaxFunction2(server) {
    let id = prompt("ID klanu:", "");

    var getdate = new Date();  //Used to prevent caching during ajax call

    if (xmlhttp) {
        xmlhttp.open("POST", "download.php", true); //calling testing.php using POST method

        xmlhttp.onreadystatechange = putServerResponseIntoActiveArmyField;

        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xmlhttp.send("id=" + id + "&server=" + server); //Posting txtname to PHP File
    }
}

function ajaxFunction3(el) {
    let id = prompt("Počet dobytí:", "");

    var getdate = new Date();  //Used to prevent caching during ajax call

    if (xmlhttp) {
        xmlhttp.open("POST", "api.php", true); //calling testing.php using POST method

        xmlhttp.onreadystatechange = putServerResponseIntoActiveArmyField;

        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xmlhttp.send("plan=" + id + "&zam=" + el); //Posting txtname to PHP File
    }
}

function ajaxFunction4(el) {
    let id = prompt("Zadej svoji sílu armády:", "");
    id = id.replace(/\s/g, '').trim() //remove whitespaces, so user can copy paste value from LoI main page

    var getdate = new Date();  //Used to prevent caching during ajax call

    if (xmlhttp) {
        xmlhttp.open("POST", "api.php", true); //calling testing.php using POST method

        xmlhttp.onreadystatechange = putServerResponseIntoActiveArmyField;

        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xmlhttp.send("plan=" + id + "&zam=" + el); //Posting txtname to PHP File
    }
}


function hraci(server) {
    ajaxFunction2(server);
}

function pleneni(el) {
    ajaxFunction3(el);
}

function silaarmady(el) {
    ajaxFunction4(el);
}


/**Simuluje utok armadou utocnika na vsechny eventy ze sekce a vypise vysledky.*/
async function simulovatCelouSekci(nazevSekce) {
    if (!CONFIG.simulateSections.enabled) {console.info('Simulace celych sekci je vypnuta -> skip'); return}

    showLoadingAnimation()
    const casZacatku = Date.now()

    const sekce = document.getElementById(nazevSekce)
    const eventy = sekce.getElementsByTagName('input')
    const armadaUtocnika = getAttackerArmy()
    const pocetOpakovaniPerEvent = CONFIG.simulateSections.retries_per_event //kazdy utok se opakuje a vezme se nejhorsi vysledek, aby se zabranilo falesne pozitivnimu vysledku pri velke nahode

    let souhrnVysledku = document.createElement('div')
    for (const event of eventy) {
        const armadaObrance = prectiArmaduEventu(event) //vraci null pro dynamicke armady (napr. modre pleneni) - at si je hrac simuluje radeji rucne
        const vysledek = armadaObrance === null ? null : await ziskejNejhorsiVsledekSimulace(armadaUtocnika, armadaObrance, pocetOpakovaniPerEvent)
        souhrnVysledku.appendChild(vytvorVyslednyElement(event, await vysledek))
        souhrnVysledku.appendChild(document.createElement('br'))
    }

    zobrazSouhrnVysledku(souhrnVysledku)
    hideLoadingAnimation()

    function zobrazSouhrnVysledku(souhrnnyElement) {
        souhrnnyElement.appendChild(document.createElement('br'))
        souhrnnyElement.appendChild(document.createElement('br'))

        //vytvori dodatecny popis/vysvetleni simulace
        const dodatecneInfo = document.createElement('span')
        dodatecneInfo.textContent = `Každý event byl simulován ${pocetOpakovaniPerEvent}x, zobrazen nejhorší výsledek. Náhoda ale může způsobit i horší výsledky -> raději ručně ověřit.`
        dodatecneInfo.style.color = BARVA_SOUHRN_KOLA
        souhrnnyElement.appendChild(dodatecneInfo)

        const casBehu = ((Date.now() - casZacatku) / 1000) //ms -> s
        const infoCasBehu = document.createElement('span')
        infoCasBehu.textContent = `${casBehu}s`
        infoCasBehu.style.color = BARVA_SOUHRN_KOLA
        souhrnnyElement.appendChild(document.createElement('br'))
        souhrnnyElement.appendChild(infoCasBehu)

        document.getElementById("result").innerHTML = souhrnnyElement.innerHTML; //nestaci jen appendChild(), protoze potrebujeme prepsat existujici obsah
    }

    function vytvorVyslednyElement(event, vysledek) {
        //TODO udelat rozklikavaci spoiler s celym vypisem
        const kontejner = document.createElement('div')
        //vytvor nadpis eventu
        const jmenoEventu = document.createElement('span')
        jmenoEventu.textContent = event.value //event.value obsahuje jmeno eventu
        jmenoEventu.style.fontWeight = 'bold'
        jmenoEventu.style.fontSize = 'large'
        kontejner.appendChild(jmenoEventu)
        kontejner.appendChild(document.createElement('br'))
        //vytvor informace o vysledku
        if (vysledek === null) {
            const infoPreskoceniEventu = document.createElement('span')
            infoPreskoceniEventu.textContent = 'Přeskočeno. Armádu eventu je dynamická (např. závislá na počtu dobytí).'
            infoPreskoceniEventu.style.color = BARVA_SOUHRN_KOLA
            kontejner.appendChild(infoPreskoceniEventu)
        } else {
            jmenoEventu.style.color = vysledek.obrance.hodnotaPrezilo < 100 ? 'green' : 'white' //uspesne dobyty event bude mit zelene jmeno
            const vysledekUtocnik = document.createElement('span')
            vysledekUtocnik.textContent = `Celkem hodnota zabité armády: ${vysledek.utocnik.hodnotaZabito}/${vysledek.utocnik.hodnotaCelkem}`
            vysledekUtocnik.style.color = BARVA_UTOCNIK
            const vysledekUtocnikProcento = document.createElement('span')
            vysledekUtocnikProcento.textContent = ` (${vysledek.utocnik.procentoZtraty.toFixed(2)}%)`;
            vysledekUtocnikProcento.style.fontWeight = 'bold'
            vysledekUtocnikProcento.style.color = BARVA_UTOCNIK

            const vysledekObrance = document.createElement('span')
            vysledekObrance.textContent = `Celkem hodnota zabité armády: ${vysledek.obrance.hodnotaZabito}/${vysledek.obrance.hodnotaCelkem}`;
            vysledekObrance.style.color = BARVA_OBRANCE
            const vysledekObranceProcento = document.createElement('span')
            vysledekObranceProcento.textContent = ` (${vysledek.obrance.procentoZtraty.toFixed(2)}%)`;
            vysledekObranceProcento.style.fontWeight = 'bold'
            vysledekObranceProcento.style.color = BARVA_OBRANCE

            kontejner.appendChild(vysledekUtocnik)
            kontejner.appendChild(vysledekUtocnikProcento)
            kontejner.appendChild(document.createElement('br'))
            kontejner.appendChild(vysledekObrance)
            kontejner.appendChild(vysledekObranceProcento)
        }

        return kontejner
    }

    function prectiArmaduEventu(event) {
        const onclickValue = event.getAttribute('onclick')
        if (onclickValue.startsWith('pole(')) {
            const armada = onclickValue.replace(/^pole\(['"]/, '').replace(/['"]\)$/, ''); //pole("armada") --> armada
            return reformatArmyString(armada.replace(/\\n/g, '\n')) //nahradi znaky '\n' symbolem noveho radku (bylo nacteno ze stringu, takze to byly jen znaky);
        }
        else return null; //dynamicke hodnoty armady, napr. pleneni dle poctu dobyti -> preskocit
        //TODO ziskat armadu kdyz je zavisla na sile utocnika
        // - silu mam k dispozici (dopocitam z fejk utoku na 1 x ruzove prasatko) a jde ziskat stejne jako vysledek bitvy pres fetch (viz ajaxFunction3 a runEngineSimulation)
    }

    async function ziskejNejhorsiVsledekSimulace(armadaUtocnika, armadaObrance, opakovani) {
        let nejhorsi = null
        for (let i=0; i<opakovani; i++) {
            let vysledek = await getBattleResults(armadaUtocnika, armadaObrance)
            // Nejhorsi vysledek je nejmene zabitych obrancu. Pokud je zabitych stejne (napr. oba utoky 100% uspech), tak potom je horsi vetsi ztrata utocnika
            if (nejhorsi == null) {
                nejhorsi = vysledek
            } else {
                if (vysledek.obrance.hodnotaPrezilo > nejhorsi.obrance.hodnotaPrezilo) {
                    nejhorsi = vysledek;
                } else if (vysledek.obrance.hodnotaPrezilo === nejhorsi.obrance.hodnotaPrezilo) {
                    if (vysledek.utocnik.hodnotaPrezilo < nejhorsi.utocnik.hodnotaPrezilo) nejhorsi = vysledek;
                }
            }
        }
        return nejhorsi
    }
}
