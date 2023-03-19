#Magic names used for units in simluator
MAGIE_LESA = "magieLesa"
MAGIE_LEDU = "magieLedu"
MAGIE_ZEME = "magieZeme"
MAGIE_SMRTI = "magieSmrti"
MAGIE_OHNE = "magieOhne"
MAGIE_SVETLA = "magieSvetla"
MULTI_MAGIE = "multiMagie"

VYVOLA_JEDNOTKU = "vyvolavaJednotku"


unit_states = {
    "live": "1",
    "undead": "2",
    "inanimate": "3"
}


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
        unit["schopnosti"][VYVOLA_JEDNOTKU] = "Goblin Paragán"
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
    elif "Multi Magie" in name: # multi magic is stored in "abilities" section, not "magic" section, so parsing has to be here
        level = name.split("Magie")[1].strip()
        name = MULTI_MAGIE
        unit["schopnosti"][name] = level
    #special cases of multi magic below
    elif "Velmistrovská Magie Ohně" == name:
        name = MULTI_MAGIE
        unit["schopnosti"][name] = "2"
    elif "Požehnaná Magie Světla":
        name = MULTI_MAGIE
        unit["schopnosti"][name] = "m1"


    if unit["nazev"] == "Eternan vyvolávač":    # fix for incomplete data provided in html table
        unit["schopnosti"][VYVOLA_JEDNOTKU] = "Energetický služebník"


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

def get_holy_boost(description: str) -> str:
    return description.split("+")[1].split("%")[0].strip()


def set_magic(unit: dict, magic: str) -> None:
    for spell in magic:
        set_spell(unit, spell)


def set_spell(unit: dict, spell: str) -> None:
    name, description = spell['title'].split(" - ", 1)
    #print("UNIT NAME: ", unit["nazev"], "NAME: ", name, " DESC: ", description)

    if "Magie Lesa" in name:
        level = name.split(" Lesa ")[1].strip()
        name = MAGIE_LESA
        unit["schopnosti"][name] = level

        if level == "1":
            unit["schopnosti"][VYVOLA_JEDNOTKU] = "Ent"
        elif level == "2":
            unit["schopnosti"][VYVOLA_JEDNOTKU] = "Wurm"
        elif level == "3":
            unit["schopnosti"][VYVOLA_JEDNOTKU] = "Starodávný Ent"
        elif level == "6":
            unit["schopnosti"][VYVOLA_JEDNOTKU] = "Kentaur"
    elif "Magie Ledu" in name:
        level = name.split(" Ledu ")[1].strip()
        name = MAGIE_LEDU
        unit["schopnosti"][name] = level

        if level == "3":
            unit["schopnosti"][VYVOLA_JEDNOTKU] = "Ledová koule"
        elif level == "6":
            unit["schopnosti"][VYVOLA_JEDNOTKU] = "Ledová hradba"
    elif "Magie Země" in name:
        level = name.split(" Země ")[1].strip()
        name = MAGIE_ZEME
        unit["schopnosti"][name] = level
    elif "Magie Smrti" in name:
        level = name.split(" Smrti ")[1].strip()
        name = MAGIE_SMRTI
        unit["schopnosti"][name] = level

        if level in ["5", "10"]:
            unit["schopnosti"][VYVOLA_JEDNOTKU] = "Uvězněná duše"
        elif level == "7":
            unit["schopnosti"][VYVOLA_JEDNOTKU] = "Prokletý ent"
        elif level == "13":
            unit["schopnosti"][VYVOLA_JEDNOTKU] = "Duše goblina"
    elif "Magie Ohně" in name:
        level = str(roman_to_arabic(name.split(" Ohně ")[1].strip()))
        name = MAGIE_OHNE
        unit["schopnosti"][name] = level

        if level == "1":
            unit["schopnosti"][VYVOLA_JEDNOTKU] = "Ohnivá koule"
        elif level == "2":
            unit["schopnosti"][VYVOLA_JEDNOTKU] = "Ohnivý imp"
        elif level == "3":
            unit["schopnosti"][VYVOLA_JEDNOTKU] = "Meteorit"
        elif level == "4":
            unit["schopnosti"][VYVOLA_JEDNOTKU] = "Ohnivý přízrak"
        elif level == "5":
            unit["schopnosti"][VYVOLA_JEDNOTKU] = "Rozžhavené magma"
        elif level == "6":
            unit["schopnosti"][VYVOLA_JEDNOTKU] = "Sopka"
        elif level == "7":
            unit["schopnosti"][VYVOLA_JEDNOTKU] = "Stínový drak"
        elif level == "8":
            unit["schopnosti"][VYVOLA_JEDNOTKU] = "Stínový ohař"
        elif level == "9":
            unit["schopnosti"][VYVOLA_JEDNOTKU] = "Stínová bestie"
        elif level == "10":
            unit["schopnosti"][VYVOLA_JEDNOTKU] = "Ohnivý déšť"
    elif "Magie Světla" in name:
        level = name.split(" Světla ")[1].strip()
        name = MAGIE_SVETLA
        unit["schopnosti"][name] = level

        if level in ['2', '3', '4']:
            unit["schopnosti"]["boostMagieSvetla"] = get_holy_boost(description)


def roman_to_arabic(roman_number: str):
    roman_numbers = {'I': 1, 'V': 5, 'X': 10, 'L': 50, 'C': 100, 'D': 500, 'M': 1000, 'IV': 4, 'IX': 9, 'XL': 40,
                     'XC': 90,
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

def set_suicide_multi_attack(unit_list: list) -> None:
    multi_attack_suicide_units = {
        "Ohnivá koule": "2",
        "Meteorit": "3",
        "Ledová koule": "2",
        "Rozžhavené magma": "2",
        "Gobliní pojízdná bomba": "2",
        "Toxická Elementární Bublina": "2",
        "Vlna tsunami": "3",
    }

    for unit in unit_list:
        if unit["nazev"] in multi_attack_suicide_units:
            unit["schopnosti"]["viceutok"] = multi_attack_suicide_units.get(unit["nazev"])
