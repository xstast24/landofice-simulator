Unit parser contains python files for generating new units from provided HTML table

Folder test_data contains html tables that can be used as input for unit_parser.py

main file is unit_parser.py. missing_units.py, unit_generator.py function as libraries and do not work on their own (they are called from unit_parser.py)

unit_parser is used to generate new xml file (generovane_jednotky.xml) containing data of all ingame units, which can be suplied into simulator

usage: unit_parser.py <path_to_source_html_file>