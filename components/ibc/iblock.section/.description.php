<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    'NAME' => 'Раздел инфоблока', // название компонента
    'DESCRIPTION' => 'Выводит список элементов раздела инфоблока',
    'ICON' => '/images/icon.gif', // иконка компонента относительно папки компонента
    'CACHE_PATH' => 'Y', // показывать кнопку очистки кеша
    'SORT' => 20, // порядок сортировки в визуальном редакторе
    'COMPLEX' => 'N', // признак комплексного компонента
    'PATH' => array( // расположение компонента в визуальном редакторе
        'ID' => 'other_components', // идентификатор верхнего уровеня в редакторе
        'NAME' => 'Прочие компоненты', // название верхнего уровня в редакторе
        'CHILD' => array( // второй уровень в визуальном редакторе
            'ID' => 'other_iblock', // идентификатор второго уровня в редакторе
            'NAME' => 'Информационный блок' // название второго уровня в редакторе
        )
    )
);