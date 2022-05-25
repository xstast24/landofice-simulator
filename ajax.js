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
    document.getElementById("obrazek").innerHTML = "<div style='position:absolute;top:400px;width:95%'><center><img style='margin:auto;background:black;padding-left:40px;padding-right:40px;border-radius:15px;box-shadow: 0 0 4px white' src='ajax-loader.gif'><center><div>";

    var ut;

    var ob;

    var getdate = new Date();  //Used to prevent caching during ajax call

    if (xmlhttp) {
        xmlhttp.open("POST", "simulator_engine.php", true); //calling testing.php using POST method

        xmlhttp.onreadystatechange = handleServerResponse;

        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        ut = document.getElementById("ut").value;

        ob = document.getElementById("ob").value;

        //cesta = document.getElementById("cesta").value;	

        xmlhttp.send("ut=" + ut + "&ob=" + ob); //Posting txtname to PHP File
    }
}


function handleServerResponse() {
    if (xmlhttp.readyState == 4) {
        if (xmlhttp.status == 200) {
            document.getElementById("obrazek").innerHTML = "";

            document.getElementById("result").innerHTML = xmlhttp.responseText;
        } else {
            document.getElementById("obrazek").innerHTML = "";

            alert("Error during AJAX call. Please try again");
        }
    }
}


function zobrazit() {
    if (document.getElementById("eventy").style.display == 'none') {document.getElementById("eventy").style.display = 'block';} else document.getElementById("eventy").style.display = 'none';
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
    return document.getElementById("selectionButon1").getAttribute('class') === 'selectionButtonActive' ? document.getElementById('ut') : document.getElementById('ob');
}

function pole(obsah) {
    aktivniPole().value = obsah;
}

function pridej(obsah) {
    aktivniPole().value += obsah;
}


function ajaxFunction2(server) {
    id = prompt("ID klanu:", "");

    var getdate = new Date();  //Used to prevent caching during ajax call

    if (xmlhttp) {
        xmlhttp.open("POST", "download.php", true); //calling testing.php using POST method

        xmlhttp.onreadystatechange = handleServerResponse2;

        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xmlhttp.send("id=" + id + "&server=" + server); //Posting txtname to PHP File
    }
}


function handleServerResponse2() {
    if (xmlhttp.readyState == 4) {
        if (xmlhttp.status == 200) {
            if (document.getElementById("selectionButon1").name == '0' || document.getElementById("selectionButon1").style.border == '2px outset #808080') document.getElementById("ob").value = xmlhttp.responseText;

            else document.getElementById("ut").value = xmlhttp.responseText;
        } else {
            alert("Error during AJAX call. Please try again");
        }
    }
}


function ajaxFunction3(el) {
    id = prompt("Počet dobytí:", "");

    var getdate = new Date();  //Used to prevent caching during ajax call

    if (xmlhttp) {
        xmlhttp.open("POST", "api.php", true); //calling testing.php using POST method

        xmlhttp.onreadystatechange = handleServerResponse3;

        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xmlhttp.send("plan=" + id + "&zam=" + el); //Posting txtname to PHP File
    }
}

function ajaxFunction4(el) {
    id = prompt("Zadej svoji sílu armády:", "");

    var getdate = new Date();  //Used to prevent caching during ajax call

    if (xmlhttp) {
        xmlhttp.open("POST", "api.php", true); //calling testing.php using POST method

        xmlhttp.onreadystatechange = handleServerResponse3;

        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xmlhttp.send("plan=" + id + "&zam=" + el); //Posting txtname to PHP File
    }
}


function handleServerResponse3() {
    if (xmlhttp.readyState == 4) {
        if (xmlhttp.status == 200) {
            if (document.getElementById("selectionButon1").name == '0' || document.getElementById("selectionButon1").style.border == '2px outset #808080') document.getElementById("ob").value = xmlhttp.responseText;

            else document.getElementById("ut").value = xmlhttp.responseText;
        } else {
            alert("Error during AJAX call. Please try again");
        }
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


function smaz(el) {
    alert(el.value);

    if (el.value == 'jednotky.xml') el.value = 'file://C:/';
}