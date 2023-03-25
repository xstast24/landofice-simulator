import logging
import os.path
import sys

from bs4 import BeautifulSoup, element

from ability_parser import set_abilities, set_magic, set_suicide_multi_attack
from missing_units import handle_missing_units
from unit_generator import generate_units

fraction_list = {  # taken from loi simulator
    "Dralgar Imagar": "1",
    "Aether": "3",
    "Dhar": "5",
    "Dreadd": "4",
    "Vulkan": "2",
    "Ghoro": "6",
    "Crinis": "7",
    "Ascandancy": "8",
}

units_to_remove = ["NIC", "Není", "SMAZAT", "Agresivní velikonoční kuřátko", "Červený gumový medvídek",
                   "Zelený gumový medvídek", "Žlutý gumový medvídek", "Kapitán Gummibärchen", "Velikonoční králíček",
                   "Ohnivý Elementál", "Vzdušný Elementál", "Vodní Elementál", "Zemní Elementál"]


def load_file(file_path: str = "LoI-jednotky.html", encoding: str = "utf-8") -> str:
    with open(file_path, "r", encoding=encoding) as units_file:
        data = units_file.read()

    return data


def extract_data_from_html_table(raw_data: str) -> list:
    unit_list = []
    fraction = "0"
    html_table = BeautifulSoup(raw_data, "html.parser")
    table_rows = html_table.findAll("tr")
    last_row = ""

    for row in table_rows:
        row_length = len(row)
        if row_length == 0:
            continue

        # this if is needed to reset fraction setting after when any unit section from html doc has finished parsing
        if row_length == 14:
            field_names = map(BeautifulSoup.get_text, row.findAll("th"))
            if "Jmeno" in field_names and set_fraction(last_row) == "0":
                fraction = "0"
        elif row_length == 2:
            fraction = set_fraction(row)
        elif row_length == 16:
            unit_list.append(parse_html_row(row, fraction))

        last_row = row

    unit_list = handle_missing_units(unit_list)
    set_suicide_multi_attack(unit_list)

    unit_list = [unit for unit in unit_list if unit["nazev"] not in units_to_remove]
    set_unique_units(unit_list)

    return unit_list


def parse_html_row(row: str, fraction: str) -> dict:
    race = row.select_one(":nth-child(14)").getText().strip()
    unit = {
        "id": row.select_one(":nth-child(2)").getText(),
        "nazev": row.select_one(":nth-child(3)").getText(),
        "utok": row.select_one(":nth-child(4)").getText(),
        "obrana": row.select_one(":nth-child(5)").getText(),
        "damage": row.select_one(":nth-child(6)").getText(),
        "zivoty": row.select_one(":nth-child(7)").getText(),
        "iniciativa": row.select_one(":nth-child(8)").getText(),
        "typUtoku": row.select_one(":nth-child(9)").getText(),
        "hodnota": row.select_one(":nth-child(10)").getText(),
        "stav": "1",  # default value is living unit, will be modified later during processing unit abilities
        "frakce": "10" if race == "demon" else fraction,
        "pocetUtoku": "1",
        "schopnosti": {}
    }

    magic = row.select_one(":nth-child(15)")
    abilities = row.select_one(":nth-child(16)")
    set_unit_abilities(unit, magic, abilities)

    return unit


def set_unit_abilities(unit: dict, magic: str, abilities: str) -> None:
    magic = magic.findAll("img")
    abilities = abilities.findAll("img")

    set_magic(unit, magic)
    set_abilities(unit, abilities)


# deprecated
def set_default_multi_magic(units: list) -> None:
    for unit in units:
        if unit["nazev"] == "Světlonoš":
            unit["schopnosti"]["magieSvetla"] = "5"
        elif unit["nazev"] == "Zasvěcenec světla":
            unit["schopnosti"]["magieSvetla"] = "2"
        elif unit["nazev"] == "Flamekeeper Lord":
            unit["schopnosti"]["magieOhne"] = "4"
            unit["schopnosti"]["vyvolavaJednotku"] = "Ohnivý přízrak"
        elif unit["nazev"] == "Gobliní Patriarcha":
            unit["schopnosti"]["magieLedu"] = "1"
        elif unit["nazev"] == "Potulný Kouzelník":
            unit["schopnosti"]["magieLedu"] = "1"


def set_fraction(row: element.Tag) -> str:
    try:
        god = row.find("h3").getText()
        return fraction_list.get(god, "0")
    except AttributeError:
        return "0"


def set_unique_units(units: list) -> None:
    unique_units = ["Posvátný jednorožec", "Generál starého impéria", "Dreaddův Vyvolený", "Flamekeeper Lord",
                    "Gobliní Hrdina"]
    semi_unique_units = {"Kamenný Kraken"}

    for unit in units:
        if unit['nazev'] in unique_units:
            unit["schopnosti"]["unikatni"] = "1"
        if unit['nazev'] in semi_unique_units:
            unit["schopnosti"]["pseudoUnikatni"] = "1"


if __name__ == "__main__":
    logging.basicConfig(stream=sys.stdout, level=logging.INFO)  # init logger

    file_path = os.path.join("test_data", "vsechny_jednotky.html")
    if len(sys.argv) == 2:
        file_path = sys.argv[1]

    logging.info(f'Loading units from "{file_path}"')
    file_data = load_file(file_path)

    unit_data = extract_data_from_html_table(file_data)

    generate_units(unit_data)
