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
        else:
            unit_list.append(parse_html_row(row, fraction))

    return unit_list


def parse_html_row(row: str, fraction: str) -> dict:
    pass


def set_fraction(row: str) -> str:
    god = row.find("h3").getText()
    return fraction_list.get(god, "0")


if __name__ == "__main__":
    file_data = load_file()

    data = extract_data_from_html_table(file_data)
