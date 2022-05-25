missing_units = [
    {
        "id": "",
        "nazev": "SMAZAT",
        "utok": "",
        "obrana": "",
        "damage": "",
        "zivoty": "",
        "iniciativa": "",
        "typUtoku": "",
        "hodnota": "",
        "stav": "",
        "frakce": "",
        "pocetUtoku": "",
        "schopnosti": {
        }
    },
    {
        "id": "999802",
        "nazev": "Démon Kněz Krvavého Měsíce",
        "utok": "5",
        "obrana": "5",
        "damage": "15",
        "zivoty": "130",
        "iniciativa": "8",
        "typUtoku": "2",
        "hodnota": "5000",
        "stav": "1",
        "frakce": "10",
        "pocetUtoku": "1",
        "schopnosti": {
            "magiePrastarych": "4"
        }
    },
    {
        "id": "999804",
        "nazev": "Démon Mág Krvavého Měsíce",
        "utok": "20",
        "obrana": "35",
        "damage": "50",
        "zivoty": "1040",
        "iniciativa": "10",
        "typUtoku": "4",
        "hodnota": "5000",
        "stav": "1",
        "frakce": "10",
        "pocetUtoku": "1",
        "schopnosti": {
            "magiePrastarych": "3"
        }
    },
]

def handle_missing_units(units_list: list) -> list:
    missing_unit_names = [missing_unit["nazev"] for missing_unit in missing_units]

    units_list = [unit for unit in units_list if unit["nazev"] not in missing_unit_names]
    units_list += missing_units

    return units_list
