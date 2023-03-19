import logging
import os.path
from os.path import dirname, abspath
from ability_parser import MAGIE_OHNE, MAGIE_LEDU, MAGIE_SMRTI, MAGIE_SVETLA, VYVOLA_JEDNOTKU

logger = logging.getLogger(__name__)

SCRIPT_DIRECTORY = dirname(dirname(abspath(__file__)))
TARGET_PATH = os.path.join(SCRIPT_DIRECTORY, 'jednotky.xml')


def generate_units(unit_data: list, unit_file_path: str = TARGET_PATH):
    logger.info(f'Exporting units as "{unit_file_path}"')
    xml_file = open(unit_file_path, 'w', encoding="utf-8")
    xml_file.write("<jednotky>\n")

    for unit in unit_data:
        xml_file.write(generate_unit(unit))

    xml_file.write("</jednotky>\n")
    xml_file.close()


def generate_unit(unit_data: dict) -> str:
    unit_xml_string = "\t<jednotka>\n"

    for key in unit_data.keys():
        if key == "schopnosti":
            unit_xml_string += f"\t\t<{key}>\n"

            abilities = unit_data[key]
            unit_xml_string += generate_abilities(abilities)

            unit_xml_string += f"\t\t</{key}>\n"
        else:
            unit_xml_string += f"\t\t<{key}>{unit_data[key]}</{key}>\n"

    unit_xml_string += "\t</jednotka>\n"

    return unit_xml_string


def generate_abilities(ability_data: dict) -> str:
    unit_xml_string = ""

    for key in ability_data.keys():
        if key == "multiMagie":
            unit_xml_string += generate_multi_magic(ability_data[key])
        else:
            unit_xml_string += f"\t\t\t<schopnost>\n"

            unit_xml_string += f"\t\t\t\t<nazev>{key}</nazev>\n"
            unit_xml_string += f"\t\t\t\t<hodnota>{ability_data[key]}</hodnota>\n"

            unit_xml_string += f"\t\t\t</schopnost>\n"

    return unit_xml_string

def generate_multi_magic(level: str) -> str:
    if level == "1":
        abilities = {MAGIE_OHNE: "1", VYVOLA_JEDNOTKU: "Ohnivá koule", MAGIE_LEDU: "1", MAGIE_SMRTI: "4"}
        return generate_abilities(abilities)
    elif level == "2":
        abilities = {MAGIE_OHNE: "3", VYVOLA_JEDNOTKU: "Meteorit"}
        abilities_xml_string = generate_abilities(abilities)
        abilities = {MAGIE_OHNE: "4", VYVOLA_JEDNOTKU: "Ohnivý přízrak"}
        abilities_xml_string += generate_abilities(abilities)
        return abilities_xml_string
    elif level == "3":
        abilities = {MAGIE_SVETLA: "2"}
        abilities_xml_string = generate_abilities(abilities)
        abilities = {MAGIE_SVETLA: "3"}
        abilities_xml_string += generate_abilities(abilities)
        abilities = {MAGIE_SVETLA: "4"}
        abilities_xml_string += generate_abilities(abilities)
        return abilities_xml_string
