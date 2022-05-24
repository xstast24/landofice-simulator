unit_states = {
    "live": "1",
    "undead": "2",
    "inanimate": "3"
}

def set_abilities(unit: dict, abilities: str) -> None:
    for ability in abilities:
        parse_ability(unit, ability)

def parse_ability(unit: dict, ability: str) -> None:
    print("ABILITY: ", ability['title'])
    name, description = ability['title'].split(" - ")
    print("ABILITY: ", name)

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
        shield_damage = get_fire_shield_damage(description)
        unit["schopnosti"]["ohnivyStit"] = shield_damage
    elif name == "Ledový štít":
        shield = get_ice_shield_slowdown(description)
        unit["schopnosti"]["ledovyStit"] = shield
    elif name == "Steč":
        charge_dmg = get_charge_dmg(description)
        unit["schopnosti"]["stec"] = charge_dmg


def get_charge_dmg(description: str) -> str:
    return description.split(" do ")[0].split("kole ")[1].strip()

def get_ice_resistance(description: str) -> str:
    return description.split(" má ")[1].split("%")[0].strip()

def get_spell_resistance(description: str) -> str:
    return description.split(" má ")[1].split("%")[0].strip()

def get_multi_attack(description: str) -> str:
    return description.split("útočí ")[1].split(" x ")[0].strip()

def get_fire_shield_damage(description: str) -> str:
    return description.split(" poškození")[0].split(" protivník ")[1].strip()

def get_ice_shield_slowdown(description: str) -> str:
    return description.split(" o ")[1].split("%")[0].strip()