/**
 * This file contains reusable simulator functions & utilities, that are dependent on the game or other simulator modules.
 * */

const BARVA_UTOCNIK = '#b0c4de' //barva pouzita ve vypisech bitvy pro akce/vysledky utocnika
const BARVA_OBRANCE = 'white' //barva pouzita ve vypisech bitvy pro akce/vysledky obrance
const BARVA_SOUHRN_KOLA = '#C0C0C0' //barva pouzita ve vypisech bitvy pro souhrn kola (na konci kazdeho kola pod utoky)


function showLoadingAnimation() {
    const loader = document.getElementById("obrazek")
    loader.innerHTML = "<div style='position:absolute;top:400px;width:95%;text-align:center'><img src='ajax-loader.gif' alt='Loading...' style='margin:auto;background:black;padding: 5px 40px;border-radius:15px;box-shadow: 0 0 10px white'><div>";
}

function hideLoadingAnimation() {
    document.getElementById("obrazek").innerHTML = '';
}

function getAttackerArmy() {
    return reformatArmyString(document.getElementById("ut").value);
}

function getDefenderArmy() {
    return reformatArmyString(document.getElementById("ob").value);
}

/**Reformat raw army string (e.g. copy paste by user) to a standardized/consistent format, e.g. "\n1,100   x Wurm(Gladiola)\n\n" -> "1100 x Wurm (Gladiola)"*/
function reformatArmyString(armyString) {
    //remove thousands-comma[1,100->1100]; add space before item [unit(item) -> unit (item)]; shrink & remove extra whitespaces
    return armyString.replace(/(\d),(\d)/g, '$1$2').replace(/\(/g, ' (').replace(/\n+/g, '\n').replace(/' '+/g, ' ').trim();
}

async function getBattleResults(attackerArmyString, defenderArmyString) {
    const resultHtmlString = await runEngineSimulation(attackerArmyString, defenderArmyString)
    const resultDoc = new DOMParser().parseFromString(resultHtmlString, 'text/html')
    const attackerStats = resultDoc.getElementById('vysledkyUtocnik')
    const defenderStats = resultDoc.getElementById('vysledkyObrance')

    let attacker = {
        hodnotaCelkem: parseInt(attackerStats.getAttribute('hodnotaCelkem')),
        hodnotaPrezilo: parseInt(attackerStats.getAttribute('hodnotaPrezilo')),
    }
    attacker.hodnotaZabito = attacker.hodnotaCelkem - attacker.hodnotaPrezilo
    attacker.procentoZtraty = 100 * attacker.hodnotaZabito / attacker.hodnotaCelkem

    let defender = {
        hodnotaCelkem: parseInt(defenderStats.getAttribute('hodnotaCelkem')),
        hodnotaPrezilo: parseInt(defenderStats.getAttribute('hodnotaPrezilo'))
    }
    defender.hodnotaZabito = defender.hodnotaCelkem - defender.hodnotaPrezilo
    defender.procentoZtraty = 100 * defender.hodnotaZabito / defender.hodnotaCelkem

    return {utocnik: attacker, obrance: defender}
}


async function runEngineSimulation(attackerArmyString, defenderArmyString) {
    //expected army strings format - the same format as user copy-pastes into the simulator

    const engineParams = new URLSearchParams(); //like ut=xxx&ob=yyy
    engineParams.append('ut', attackerArmyString)
    engineParams.append('ob', defenderArmyString)
    const request = {method: 'POST', body: engineParams};

    //return battle result as HTML string (as is normally displayed in the simulator)
    return await fetch('simulator_engine.php', request)
        .then(result => result.text())
        .catch(err => alert('Unexpected javascript error when trying to run engine_simulation.php'))
}