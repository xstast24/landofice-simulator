/**
 * Konfigurace javascriptovych funkci. Vyuzivame separatni config.js navic vedle hlavniho config.ini,
 * protoze z hlavniho config.ini se snadno dostanou informace do PHP, ale tezko do JS (pokazde opakovat async download config.ini + parsing).
 * */

const CONFIG = {
    simulateSections: {
        //Kliknuti na nazev sekce simuluje utok nad vsemi eventy v dane sekci a vypise souhrn. Muze zatezovat server.
        enabled: true,
        retries_per_event: 3 // <1;N> pocet opakovani simulace eventu, vezme se nejhorsi vysledek, aby se zabranilo nahodnym uspechum a hrac si nerozbil armadu
    }
}
