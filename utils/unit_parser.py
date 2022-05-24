from bs4 import BeautifulSoup


fraction_list = {       # taken from loi simulator
    "Dralgar Imagar": "1",
    "Aether": "3",
    "Dhar": "5",
    "Dreadd": "4",
    "Vulkan": "2",
    "Ghoro": "6",
    "Crinis": "7",
    "Ascandancy": "8",
    "Demoni": "10"
}

def load_file(file_path: str = "LoI-jednotky.html", encoding: str = "utf-8") -> str:
    with open(file_path, "r", encoding=encoding) as units_file:
        data = units_file.read()

    return data

def extract_data_from_html_table(raw_data: str) -> list:
    unit_list = []
    fraction = "0"
    html_table = BeautifulSoup(raw_data, "html.parser")
    table_rows = html_table.findAll("tr")


    for row in table_rows:
        row_length = len(row)
        if row_length == 0:
            continue

        if row_length == 2:
            fraction = set_fraction(row)
        elif row_length == 16:
            unit_list.append(parse_html_row(row, fraction))

    return unit_list


def parse_html_row(row: str, fraction: str) -> dict:
    print("\n")
    for r in row:
        print(r)
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
        "stav": "1",
        "frakce": fraction,
        "pocetUtoku": "1",
        "schopnosti": {}
    }
    magic = row.select_one(":nth-child(15)")
    abilities = row.select_one(":nth-child(16)")

    set_unit_abilities(unit, magic, abilities)

    print("jednotka: ", unit)


def set_unit_abilities(unit:dict , magic: str, abilities:str) -> None:
    magic = magic.findAll("img")
    abilities = abilities.findAll("img")

    for spell in magic:
        pass

    for ability in abilities:
        pass


def set_fraction(row: str) -> str:
    god = row.find("h3").getText()
    return fraction_list.get(god, "0")


if __name__ == "__main__":
    file_data = load_file("jednotky_test.html")

    data = extract_data_from_html_table(file_data)
