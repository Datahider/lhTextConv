<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define('LH_LIB_ROOT', './');

require_once LH_LIB_ROOT . 'lhTextConv/lhTextConv.php';

$keys = ['Витавский', 'Витовский', 'Швардсенеггер', 'ШВОРЦИНЕГИР', 'Витенберг', 'Виттенберг', 'Насанов', 'Насонов', 'Нассонов', 'Носонов', 'Пермаков', 'Пермяков', 'Перьмяков', 'Борщ', 'ПАЛЕЦ', 'ПЛАЩ', 'ЩУП'];
$metaphone = ['ВИТАФСКИЙ', 'ВИТАФСКИЙ', 'ШВАРЦИНИГИР', 'ШВАРЦИНИГИР', 'ВИТИНБИРК', 'ВИТИНБИРК', 'НАСАНАФ', 'НАСАНАФ', 'НАСАНАФ', 'НАСАНАФ', 'ПИРМАКАФ', 'ПИРМАКАФ', 'ПИРМАКАФ', 'БАРЩ', 'ПАЛИЦ', 'ПЛАЩ', 'ЩУП'];
$translit = ['Vitavskij', 'Vitovskij', 'Shvardsenegger', 'SHVORTSINEGIR', 'Vitenberg', 'Vittenberg', 'Nasanov', 'Nasonov', 'Nassonov', 'Nosonov', 'Permakov', 'Permjakov', 'Permjakov', 'Borsch', 'PALETS', 'PLASCH', 'SCHUP'];
$similarity = [ false, true, false, true, false, true, false, true, true, true, false, true, true, false, false, false, false ];
$smiles = [ 'Привет :wink:!' => 'Привет 😉!', "Я :flushed: шизею" => 'Я 😳 шизею' ];
$best_match = [
    [["Петя"], "Петя", 0, 100],
    [["Пупа", "Петя", "Пепа"], "Петя", 1, 100],
    [["Преве", "Превед", "Превед "], "Привет", 1, 100],
    [["Похожест", "Похожесть", "Похожесть "], "Пахожесть", 0, 100],
    [["Доброго", "Доброго времени", "Доброго времени суток"], "Доброго времени дня", 1, 88.235294117647],
    [["Не", "Нет"], "Ни", 0, 100],
    [[], "Любая фигня", null, 100], // Процент остается с прошлого вызова, т.к. в таком случае мы его не трогаем
    [[''], "Любая фигня", 0, 0],
];
$commutatives = [
    ["Петя", "Пепа"],
    ["Тузя", "Маргарита"],
    ["Маргарита", "Маргоша"]
];

$gender = [
    'Я пош[ел|ла] гулять т.к. был[а] пьян[а]' => [ 'Я пошел гулять т.к. был пьян', 'Я пошла гулять т.к. была пьяна'],
    'Он[а] был[а] дура[к|]' => [ 'Он был дурак', 'Она была дура' ],
    'Ты прав[а][, друг мой|]!' => [ 'Ты прав, друг мой!', 'Ты права!' ],
];

$test_metaphone = array_combine($keys, $metaphone);

echo "Проверка метафона";
foreach ($test_metaphone as $key => $value) {
    $m = lhTextConv::metaphone($key);
    if ($value != $m) {
        echo "FAIL!!! - Получено: \"$m\", ожидалось: \"$value\"";
        die();
    }
    echo '.';
}
echo "Ok\n";

$test_translit = array_combine($keys, $translit);
echo "Проверка транслита";
foreach ($test_translit as $key => $value) {
    $t = lhTextConv::translit($key);
    if ($value != $t) {
        echo "FAIL!!! - Получено: \"$t\", ожидалось: \"$value\"";
        die();
    }
    echo '.';
}
echo "Ok\n";

$test_similarity = array_combine($keys, $similarity);
echo "Проверка metaphoneSimilarity";
$last = '';
foreach ($test_similarity as $key => $value) {
    $s = lhTextConv::metaphoneSimilarity($key, $last);
    $equals = ($s == 100);
    if ( $equals != $value ) {
        echo "FAIL!!! - Ожидалось $key " . ($value ? 'равно' : 'не равно') . " $last\n";        
        die();
    }
    $last = $key;
    echo '.';
}
echo "Ok\n";

echo "Проверка изменения пола";
foreach ($gender as $key => $value) {
    $f = lhTextConv::genderSubstitutions($key, 'f');
    $m = lhTextConv::genderSubstitutions($key, 'm');
    
    if ($value[0] != $m) {
        echo "FAIL!!! - Получено: \"$m\", ожидалось: \"$value[0]\"";
        die();
    }
    echo '.';
    if ($value[1] != $f) {
        echo "FAIL!!! - Получено: \"$f\", ожидалось: \"$value[1]\"";
        die();
    }
    echo '.';
}
echo "Ok\n";

echo "Проверка преобразования смайлов";
foreach ($smiles as $key => $value) {
    $t = lhTextConv::smilesSubstitutions($key);
    
    if ($value != $t) {
        echo "FAIL!!! - Получено: \"$t\", ожидалось: \"$value\"";
        die();
    }
    echo '.';
}
echo "Ok\n";

echo "Проверка коммутативности";
foreach ($commutatives as $test_set) {
    echo '.';
    $r1 = lhTextConv::metaphoneSimilarity($test_set[0], $test_set[1]);
    $r2 = lhTextConv::metaphoneSimilarity($test_set[1], $test_set[0]);
    if ($r1 != $r2) {
        throw new Exception("$r1 != $r2");
    }
}
echo "Ok\n";

echo "Проверка bestMatch";
foreach ($best_match as $test_set) {
    echo '.';
    $r = lhTextConv::bestMatch($test_set[0], $test_set[1], $percentage);
    if ($r !== $test_set[2]) {
        throw new Exception("Awaiting $test_set[2], got $r");
    }
    if (round($percentage, 3) != round($test_set[3], 3)) {
        throw new Exception("Awaiting percentage to be $test_set[3], got $percentage");
    }
}
echo "Ok\n";

echo "Проверка split";

$splits = [
    ["О сколько нам открытий чудных готовит просвещенья дух. И опыт - сын ошибок трудных, и гений - парадоксов друг, и случай - бог изобретатель", [
        "О", "сколько", "нам", "открытий", "чудных", "готовит", "просвещенья", "дух", ".", "И", "опыт",
        "-", "сын", "ошибок", "трудных", ",", "и", "гений", "-", "парадоксов", "друг", ",", "и", "случай",
        "-", "бог", "изобретатель"
    ]],
    ["Привет, Петь, у нас сломался принтер.",["Привет", ",", "Петь", ",", "у", "нас", "сломался", "принтер", "."]]
];
foreach ($splits as $test_set) {
    echo '.';
    $r = lhTextConv::split($test_set[0]);
    if ((count($test_set[1]) == 0) && (count($r) != 0)) {
        throw new Exception("Awaiting result to be empty but got: ". print_r($r, true));
    }
    for ($index = 0; $index < count($test_set[1]); $index++) {
        if ($test_set[1][$index] != $r[$index]) {
            throw new Exception("Awaiting lexemme $index to be ". $test_set[1][$index]. " but got: ". print_r($r[$index], true));
        }
    }
}
echo "Ok\n";
