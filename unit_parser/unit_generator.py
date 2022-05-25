def generate_units(unit_data: str, unit_file_path:str = "generovane_jednotky.xml"):
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

        unit_xml_string += f"\t\t\t<schopnost>\n"

        unit_xml_string += f"\t\t\t\t<nazev>{key}</nazev>\n"
        unit_xml_string += f"\t\t\t\t<hodnota>{ability_data[key]}</hodnota>\n"

        unit_xml_string += f"\t\t\t</schopnost>\n"

    return unit_xml_string

