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
