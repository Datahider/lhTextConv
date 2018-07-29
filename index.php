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

$gender = [
    'Я пош[ел|ла] гулять т.к. был[а] пьян[а]' => [ 'Я пошел гулять т.к. был пьян', 'Я пошла гулять т.к. была пьяна'],
    'Он[а] был[а] дура[к|]' => [ 'Он был дурак', 'Она была дура' ]
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
