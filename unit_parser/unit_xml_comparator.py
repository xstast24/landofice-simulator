import xml.etree.ElementTree as ET


def find_missing_units(file_path1, file_path2):
    file1 = ET.parse(file_path1)
    file2 = ET.parse(file_path2)

    root1 = file1.getroot()
    root2 = file2.getroot()

    original_units = []

    added = []

    for child in root1:
        original_units.append(child[1].text.lower())

    for child in root2:
        if child[1].text.lower() not in original_units:
            added.append(child[1].text.lower())
        else:
            original_units.remove(child[1].text.lower())

    print("CHYBEJICI: ", original_units)
    print("NOVE: ", added)


if __name__ == "__main__":
    xml_file1 = "../jednotky.xml"
    xml_file2 = "generovane_jednotky.xml"

    find_missing_units(xml_file1, xml_file2)