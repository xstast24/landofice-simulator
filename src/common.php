<?php

/**Reformat raw army string (e.g. copy paste by user) to a standardized/consistent format, e.g. "\n1,100   x Wurm(Gladiola)[multi]\n\n" -> "1100 x Wurm (Gladiola) [multi]"*/
function reformatArmyString($army): string
{
    //remove thousands-comma[1,100->1000]; add space before item [unit(item) -> unit (item)]; shrink & remove extra whitespaces
    //return armyString.replace(/(\d),(\d)/g, '$1$2').replace(/\(/g, ' (').replace(/\n+/g, '\n').replace(/' '+/g, ' ').trim();
    $army = preg_replace('/(\d),(\d)/', '$1$2', $army); //remove thousands-comma: 1,100 -> 1100
    $army = preg_replace('/\(/', ' (', $army); //add space before item: unit(item) -> unit (item)
    $army = preg_replace('/\[/', ' [', $army); //add space before multimagic: unit[multimag] -> unit [multimag]
    $army = preg_replace('/ +/', ' ', $army); //remove duplicite whitespaces (some might've been added in the previous steps)
    $army = preg_replace('/\n+/', "\n", $army); //remove extra newlines
    return trim($army);
}


/**Nahodne zaokrouhlovani dolu ci nahoru.*/
function randround($cislo) : int
{
    return rand(0, 1) == 0 ? floor($cislo) : ceil($cislo);
}


/**Compare 2 strings while ignoring any accents/diacritics and case, e.g. "áŤů" == "atu.
 * Return true if same, false if different*/
function compareStringsIgnoringDiacriticsAndCase($str1, $str2): bool
{
    return strtolower(removeAccents($str1)) == strtolower(removeAccents($str2));
}


/**Remove diacritics/accents from given string*/
function removeAccents($str) : string
{
    //source https://stackoverflow.com/a/34649673/7684041, https://github.com/lingtalfi/Bat/blob/master/StringTool.php
    static $map = [
        // single letters
        'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','ą'=>'a','å'=>'a','ā'=>'a','ă'=>'a','ǎ'=>'a','ǻ'=>'a',
        'À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Ą'=>'A','Å'=>'A','Ā'=>'A','Ă'=>'A','Ǎ'=>'A','Ǻ'=>'A',
        'ç'=>'c','ć'=>'c','ĉ'=>'c','ċ'=>'c','č'=>'c','Ç'=>'C','Ć'=>'C','Ĉ'=>'C','Ċ'=>'C','Č'=>'C',
        'ď'=>'d','đ'=>'d','Ð'=>'D','Ď'=>'D','Đ'=>'D',
        'è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ę'=>'e','ē'=>'e','ĕ'=>'e','ė'=>'e','ě'=>'e',
        'È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E','Ę'=>'E','Ē'=>'E','Ĕ'=>'E','Ė'=>'E','Ě'=>'E',
        'ƒ' => 'f',
        'ĝ'=>'g','ğ'=>'g','ġ'=>'g','ģ'=>'g','Ĝ'=>'G','Ğ'=>'G','Ġ'=>'G','Ģ'=>'G',
        'ĥ'=>'h','ħ'=>'h','Ĥ'=>'H','Ħ'=>'H',
        'ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ĩ'=>'i','ī'=>'i','ĭ'=>'i','į'=>'i','ſ'=>'i','ǐ'=>'i',
        'Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ĩ'=>'I','Ī'=>'I','Ĭ'=>'I','Į'=>'I','İ'=>'I','Ǐ'=>'I',
        'ĵ'=>'j','Ĵ'=>'J',
        'ķ'=>'k','Ķ'=>'K',
        'ł'=>'l','ĺ'=>'l','ļ'=>'l','ľ'=>'l','ŀ'=>'l','Ł'=>'L','Ĺ'=>'L','Ļ'=>'L','Ľ'=>'L','Ŀ'=>'L',
        'ñ'=>'n','ń'=>'n','ņ'=>'n','ň'=>'n','ŉ'=>'n','Ñ'=>'N','Ń'=>'N','Ņ'=>'N','Ň'=>'N',
        'ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o','ð'=>'o','ø'=>'o','ō'=>'o','ŏ'=>'o','ő'=>'o','ơ'=>'o','ǒ'=>'o','ǿ'=>'o',
        'Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ō'=>'O','Ŏ'=>'O','Ő'=>'O','Ơ'=>'O','Ǒ'=>'O','Ǿ'=>'O',
        'ŕ'=>'r','ŗ'=>'r','ř'=>'r','Ŕ'=>'R','Ŗ'=>'R','Ř'=>'R',
        'ś'=>'s','š'=>'s','ŝ'=>'s','ş'=>'s','Ś'=>'S','Š'=>'S','Ŝ'=>'S','Ş'=>'S',
        'ţ'=>'t','ť'=>'t','ŧ'=>'t','Ţ'=>'T','Ť'=>'T','Ŧ'=>'T',
        'ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u','ũ'=>'u','ū'=>'u','ŭ'=>'u','ů'=>'u','ű'=>'u','ų'=>'u','ư'=>'u','ǔ'=>'u','ǖ'=>'u','ǘ'=>'u','ǚ'=>'u','ǜ'=>'u',
        'Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ũ'=>'U','Ū'=>'U','Ŭ'=>'U','Ů'=>'U','Ű'=>'U','Ų'=>'U','Ư'=>'U','Ǔ'=>'U','Ǖ'=>'U','Ǘ'=>'U','Ǚ'=>'U','Ǜ'=>'U',
        'ŵ' => 'w','Ŵ'=>'W',
        'ý'=>'y','ÿ'=>'y','ŷ'=>'y','Ý'=>'Y','Ÿ'=>'Y','Ŷ'=>'Y',
        'ż'=>'z','ź'=>'z','ž'=>'z','Ż'=>'Z','Ź'=>'Z','Ž'=>'Z',
        // accentuated ligatures
        'Ǽ'=>'A','ǽ'=>'a',
    ];
    return strtr($str, $map);
}