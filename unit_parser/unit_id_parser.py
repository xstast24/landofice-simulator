from bs4 import BeautifulSoup

file_path = "LoI-jednotky.html"
if __name__ == "__main__":
    with open(file_path, "r", encoding="utf-8") as units_file:
        data = units_file.read()

        html_table = BeautifulSoup(data, "html.parser")
        table_rows = html_table.findAll("tr")

        file = open("jednotky_id.txt", 'a', encoding="utf-8")
        new_data = ""
        for row in table_rows:
            row_length = len(row)
            if row_length == 0:
                continue

            elif row_length == 16:
                jmeno = row.select_one(":nth-child(3)").getText()
                id = row.select_one(":nth-child(2)").getText()
                new_data += jmeno + " : " + id + "\n"

        file.write(new_data)
        file.close()
