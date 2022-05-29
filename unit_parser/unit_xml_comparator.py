import sys
import xml.etree.ElementTree as ET


def find_missing_units(file_path1, file_path2):
    original_xml = ET.parse(file_path1)
    new_xml = ET.parse(file_path2)

    original_xml = original_xml.getroot()
    new_xml = new_xml.getroot()

    original_units = []
    original_units_lowercase = []

    added = []

    for child in original_xml:
        original_units.append(child[1].text)
        original_units_lowercase.append(child[1].text.lower())

    for child in new_xml:
        if child[1].text.lower() not in original_units_lowercase:
            added.append(child[1].text)
        else:
            original_units_lowercase.remove(child[1].text.lower())
            original_units = [unit for unit in original_units if unit.lower() != child[1].text.lower()]

    print("CHYBEJICI (byly ve starem, nejsou v novem):\n", "\n".join(sorted(original_units)))
    print("\nNOVE:\n", "\n".join(sorted(added)))


if __name__ == "__main__":
    original_file = "../jednotky.xml"
    new_file = "generovane_jednotky.xml"

    if len(sys.argv) >= 2:
        original_file = sys.argv[1]
    if len(sys.argv) >= 3:
        new_file = sys.argv[2]


    find_missing_units(original_file, new_file)
