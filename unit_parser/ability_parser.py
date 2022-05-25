unit_states = {
    "live": "1",
    "undead": "2",
    "inanimate": "3"
}

summon_unit_table = {      # taken from loi simulator
    "Arcimág ohně": "7",
    "Plamenný démon mág": "7",
    "Plamenný démon arcimág": "17",
    "Démon Bojový Mág Temnoty": "17",
    "Následovník démonů - Kacíř": "12",
    "Dh Ratu řečený Posel ohně": "17",
    "Ragnarokk, prokletý wurm": "242",
    "Eternan vyvolávač": "51",
    "Zasvěcenec boha ledu": "78",
    "Magmatický golem": "89",
    "Flamekeeper": "17",
    "Flamekeeper Lord": "95",
    "Magmatický obr": "999",
    "Soulkeeper": "107",
    "Nemrtvý Druid": "192",
    "Zvěstovatel soudného dne": "17",
    "Nemrtvý ohnivý kouzelník": "7",
    "Goblin Pyroman": "7",
    "Gobliní Vzducholoď": "130",
    "Gobliní patriarcha": "7",
    "Rah Atrog řečený Vyvolávač": "95",
    "Vrchní knežka Enakra": "95",
    "Vulkánův generál": "999",
    "Vulkán bůh ohně": "998",
    "Druid": "242",
    "Pán vod": "264",
    "Veledruid": "66",
    "Šaman Ledu": "509",
    "Anděl věčného ohně": "650",
    "Gobliní Hybridní vzducholoď": "1300",
    "Gobliní Vyztužená vzducholoď": "13000",
    "Železnej kněz": "663",
    "Æthrův šaman Ledu": "509",
    "Aethrův šaman Ledu": "509",
    "Soulkeeper věčné Temnoty": "107",
    "Stínový mág": "709",
    "Stínový arcimág": "710",
    "Zlobří Šaman": "902"
}   # TODO pridat vyvolavani posvatneho obra do simulatoru

def set_abilities(unit: dict, abilities: str) -> None:
    for ability in abilities:
        set_ability(unit, ability)

def set_ability(unit: dict, ability: str) -> None:
    name, description = ability['title'].split(" - ", 1)

    if name == "Dav":
        unit["schopnosti"]["dav"] = "1"
    elif name == "Exterminace":
        unit["schopnosti"]["exterminace"] = "1"
    elif name == "Imunita proti ohni":
        unit["schopnosti"]["imunitaOhen"] = "1"
    elif name == "Imunita led":
        resistance = get_ice_resistance(description)
        unit["schopnosti"]["imunitaLed"] = resistance
    elif name == "Imunita proti magii":
        resistance = get_spell_resistance(description)
        unit["schopnosti"]["imunitaMagie"] = resistance
    elif name == "Multi Útok":
        unit["pocetUtoku"] = get_multi_attack(description)
    elif name == "Nemrtvý":
        unit["stav"] = unit_states["undead"]
    elif name == "Neživý":
        unit["stav"] = unit_states["inanimate"]
    elif name == "Ohnivý štít":
        shield_damage = get_fire_shield_dmg(description)
        unit["schopnosti"]["ohnivyStit"] = shield_damage
    elif name == "Ledový štít":
        shield = get_ice_shield_slowdown(description)
        unit["schopnosti"]["ledovyStit"] = shield
    elif name == "Jed":
        poison_dmg = get_poison_dmg(description)
        unit["schopnosti"]["jedovyUtok"] = poison_dmg
    elif name == "Svatý útok":
        holy_dmg = get_holy_dmg(description)
        unit["schopnosti"]["svatyUtok"] = holy_dmg
    elif name == "Drtivý útok":
        crush_dmg = get_crush_dmg(description)
        unit["schopnosti"]["drtivyUtok"] = crush_dmg
    elif name == "Sabotaz":
        unit["schopnosti"]["sabotaz"] = "1"
    elif name == "Slayer":
        unit["schopnosti"]["slayer"] = "1"
    elif name == "Steč":
        charge_dmg = get_charge_dmg(description)
        unit["schopnosti"]["stec"] = charge_dmg
    elif name == "Temný křik":
        unit["schopnosti"]["temnykrik"] = "1"
    elif name == "Vzkříšení":
        resurrection_chance = get_resurrection_chance(description)
        unit["schopnosti"]["vzkryseni"] = resurrection_chance
    elif name == "Toxicita":
        toxic_dmg = get_toxic_dmg(description)
        unit["schopnosti"]["toxickyStit"] = toxic_dmg
    elif name == "Síť":
        unit["schopnosti"]["sit"] = "1"
    elif name == "Gobliní výsadek":
        unit["schopnosti"]["vyvolavaJednotku"] = summon_unit_table.get(unit["nazev"])
    elif name == "Globálni poškození":
        unit["schopnosti"]["sebevrazedna"] = "1"
    elif name == "Nepředvídatelnost":
        unit["schopnosti"]["nepredvidatelnost"] = "1"
    elif name == "Konstrukce":
        unit["schopnosti"]["konstrukce"] = "1"
    elif name == "Síla goblinů":
        unit["schopnosti"]["silaGoblinu"] = "1"
    elif name == "Dezorientace":
        unit["schopnosti"]["magieEternanu"] = "2"
    elif name == "Blokování":
        block_percentage = get_block_percentage(description)
        unit["schopnosti"]["block"] = block_percentage
    elif name == "Tvrzená kůže":
        unit["schopnosti"]["tvrzenaKuze"] = "1"
    elif name == "Rozložení":
        unit["schopnosti"]["rozlozeni"] = "1"
    elif name == "Kanibalizmus":
        # TODO vyresit jak stanovit hodnoty - simulator tvrdi upir 15 ghul 5, prozatim natvrdo 5
        unit["schopnosti"]["kanibalizmus"] = "5"
    elif name == "Létání":
        unit["schopnosti"]["letani"] = "1"




def get_charge_dmg(description: str) -> str:
    return description.split(" do ")[0].split("kole ")[1].strip()

def get_ice_resistance(description: str) -> str:
    return description.split(" má ")[1].split("%")[0].strip()

def get_spell_resistance(description: str) -> str:
    return description.split(" má ")[1].split("%")[0].strip()

def get_multi_attack(description: str) -> str:
    return description.split("útočí ")[1].split(" x ")[0].strip()

def get_fire_shield_dmg(description: str) -> str:
    return description.split(" poškození")[0].split(" protivník ")[1].strip()

def get_ice_shield_slowdown(description: str) -> str:
    return description.split(" o ")[1].split("%")[0].strip()

def get_poison_dmg(description: str) -> str:
    return description.split("+")[1].split("%")[0].strip()

def get_holy_dmg(description: str) -> str:
    return description.split("+")[1].split("%")[0].strip()

def get_crush_dmg(description: str) -> str:
    return description.split("+")[1].split("%")[0].strip()

def get_resurrection_chance(description: str) -> str:
    return description.split("zde ")[1].split("%")[0].strip()

def get_toxic_dmg(description: str) -> str:
    return description.split(" o ")[1].strip()

def get_block_percentage(description: str) -> str:
    return description.split("zablokuje ")[1].split("%")[0].strip()

def set_magic(unit: dict, magic: str) -> None:
    for spell in magic:
        set_spell(unit, spell)

def set_spell(unit: dict, spell: str) -> None:
    name, description = spell['title'].split(" - ", 1)

    if unit["nazev"] in summon_unit_table:
        unit["schopnosti"]["vyvolavaJednotku"] = summon_unit_table.get(unit["nazev"])

    if "Magie Lesa" in name:
        level = name.split(" Lesa ")[1].strip()
        name = "magieLesa"
        unit["schopnosti"][name] = level
    elif "Magie Ledu" in name:
        level = name.split(" Ledu ")[1].strip()
        name = "magieLedu"
        unit["schopnosti"][name] = level
    elif "Magie Země" in name:
        level = name.split(" Země ")[1].strip()
        name = "magieZeme"
        unit["schopnosti"][name] = level
    elif "Magie Smrti" in name:
        level = name.split(" Smrti ")[1].strip()
        name = "magieSmrti"
        unit["schopnosti"][name] = level
    elif "Magie Ohně" in name:
        level = roman_to_arabic(name.split(" Ohně ")[1].strip())
        name = "magieOhne"
        unit["schopnosti"][name] = str(level)
    elif "Magie Světla" in name:
        level = name.split(" Světla ")[1].strip()
        name = "magieSvetla"
        unit["schopnosti"][name] = level
    # TODO potom poresit chybejici magii prastarych


def roman_to_arabic(roman_number: str):
    roman_numbers = {'I': 1, 'V': 5, 'X': 10, 'L': 50, 'C': 100, 'D': 500, 'M': 1000, 'IV': 4, 'IX': 9, 'XL': 40, 'XC': 90,
             'CD': 400, 'CM': 900}
    i = 0
    arabic_number = 0
    while i < len(roman_number):
        if i + 1 < len(roman_number) and roman_number[i:i + 2] in roman_numbers:
            arabic_number += roman_numbers[roman_number[i:i + 2]]
            i += 2
        else:
            arabic_number += roman_numbers[roman_number[i]]
            i += 1
    return arabic_number