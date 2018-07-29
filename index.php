<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define('LH_LIB_ROOT', './');

require_once LH_LIB_ROOT . 'lhTextConv/lhTextConv.php';

$keys = ['Витавский', 'Витовский', 'Швардсенеггер', 'Шворцинегир', 'Витенберг', 'Виттенберг', 'Насанов', 'Насонов', 'Нассонов', 'Носонов', 'Пермаков', 'Пермяков', 'Перьмяков'];
$metaphone = ['ВИТАФСКИЙ', 'ВИТАФСКИЙ', 'ШВАРЦИНИГИР', 'ШВАРЦИНИГИР', 'ВИТИНБИРК', 'ВИТИНБИРК', 'НАСАНАФ', 'НАСАНАФ', 'НАСАНАФ', 'НАСАНАФ', 'ПИРМАКАФ', 'ПИРМАКАФ', 'ПИРМАКАФ'];
$translit = ['Vitavskij', 'Vitovskij', 'Shvardsenegger', 'Shvortsinegir', 'Vitenberg', 'Vittenberg', 'Nasanov', 'Nasonov', 'Nassonov', 'Nosonov', 'Permakov', 'Permjakov', 'Permjakov'];

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
