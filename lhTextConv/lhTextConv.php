<?php

/**
 * Description of lhTextConv
 * 
 * a set of static methods for text conversion, filtering and comparition
 *
 * @author Peter Datahider
 */
class lhTextConv { 
    
    private static $translit_map = [ 
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'JO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I',
        'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 
        'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'TS', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '',  
        'Э' => 'E', 'Ю' => 'JU', 'Я' => 'JA', 
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'jo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
        'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 
        'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '',  
        'э' => 'e', 'ю' => 'ju', 'я' => 'ja' 
    ];

    private static $smiles_map = [
        ':wink:' => '😉'
    ];

    public static function metaphone($text) {
        $result = mb_strtolower($text, 'UTF-8');
        $result = self::shrinkDuplicates($result);
        $result = self::replaceVovels($result);
        $result = self::replaceConsonants($result);
        $result = self::shrinkDuplicates($result);
        return mb_strtoupper($result, 'UTF-8');
    }
    
    public static function translit($text) {
        $textarray = preg_split("//u", $text);
        $result = '';
        foreach ($textarray as $l) {
            if (isset(self::$translit_map[$l])) {
                $result .= self::$translit_map[$l];
            } else {
                $result .= $l;
            }
        }
        return $result;
    }
    
    public static function metaphoneSimilarity($text1, $text2) {
        similar_text(
            self::translit(self::metaphone($text1)), 
            self::translit(self::metaphone($text2)), 
            $percentage
        );
        return $percentage;
    }
    
    public static function similarity($text1, $text2) {
        similar_text(
            self::translit($text1), 
            self::translit($text2), 
            $percentage
        );
        return $percentage;
    }
    
    public static function levenshtein($text1, $text2) {
        return levenshtein(
            self::translit(self::metaphone($text1)), 
            self::translit(self::metaphone($text2))
        );
    }
    
    public static function genderSubstitutions($string, $gender='m') {
        $result = preg_replace_callback("/\[([^\]|]*?)\|?([^\]|]*)\]/u", function ($matches) use($gender) {
            if ($gender == 'f') {
                return $matches[2];
            } else {
                return $matches[1];
            }
        }, $string);
        return $result;
    }
    
    public static function smilesSubstitutions($template) {
        $result = $template;
        foreach (self::$smiles_map as $key => $value) {
            $result = preg_replace("/$key/", $value, $result);
        }
        return $result;
    }
    
    private static function shrinkDuplicates($text) {   // Нужна для metaphone
        return preg_replace("/(.)\\1+/u", "$1", $text);
    }
    
    private static function replaceVovels($text) {      // Нужна для metaphone
        $result = $text;
        $result = preg_replace("/йо|ио|йе|ие/u", 'и', $result);
        $result = preg_replace("/[оыя]/u", 'а', $result);
        $result = preg_replace("/[еёэ]/u", 'и', $result);
        $result = preg_replace("/ю/u", 'у', $result);
        return $result;
    }

    private static function replaceConsonants($text) {  // Нужна для metaphone
        $result = $text;
        $result = preg_replace("/б(?=([бвгджзкпстфхцчшщь]|\b))/u", 'п', $result);
        $result = preg_replace("/в(?=([бвгджзкпстфхцчшщь]|\b))/u", 'ф', $result);
        $result = preg_replace("/г(?=([бвгджзкпстфхцчшщь]|\b))/u", 'к', $result);
        $result = preg_replace("/д(?=([бвгджзкпстфхцчшщь]|\b))/u", 'т', $result);
        $result = preg_replace("/ж(?=([бвгджзкпстфхцчшщь]|\b))/u", 'ш', $result);
        $result = preg_replace("/з(?=([бвгджзкпстфхцчшщь]|\b))/u", 'с', $result);
        $result = preg_replace("/тс|дс/u", 'ц', $result);
        $result = preg_replace("/чта/u", 'шта', $result);
        return $result;
    }
}
