<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

// проверяем, установлен ли модуль «Информационные блоки»; если да — то подключаем его
if (!CModule::IncludeModule('iblock')) {
    return;
}

$arInfoBlockTypes = CIBlockParameters::GetIBlockTypes();


$arInfoBlocks = array();
$arFilter = array('ACTIVE' => 'Y');
// если уже выбран тип инфоблока, выбираем инфоблоки только этого типа
if (!empty($arCurrentValues['IBLOCK_TYPE'])) {
    $arFilter['TYPE'] = $arCurrentValues['IBLOCK_TYPE'];
}
$rsIBlock = CIBlock::GetList(
    array('SORT' => 'ASC'),
    $arFilter
);
while($iblock = $rsIBlock->Fetch()) {
    $arInfoBlocks[$iblock['ID']] = '['.$iblock['ID'].'] '.$iblock['NAME'];
}

$arInfoBlockSections = array(
    '-' => '[=Выберите=]',
);
$arFilter = array(
    'SECTION_ID' => false, // только корневые разделы
    'ACTIVE' => 'Y' // только активные разделы
);
// если уже выбран тип инфоблока, выбираем разделы, принадлежащие инфоблокам выбранного типа
if (!empty($arCurrentValues['IBLOCK_TYPE'])) {
    $arFilter['IBLOCK_TYPE'] = $arCurrentValues['IBLOCK_TYPE'];
}
// если уже выбран инфоблок, выбираем разделы только этого инфоблока
if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arFilter['IBLOCK_ID'] = $arCurrentValues['IBLOCK_ID'];
}
$result = CIBlockSection::GetList(
    array('SORT' => 'ASC'),
    $arFilter
);
while ($section = $result->Fetch()) {
    $arInfoBlockSections[$section['ID']] = '['.$section['ID'].'] '.$section['NAME'];
}

$arComponentParameters = array( // кроме групп по умолчанию, добавляем свои группы настроек
    'GROUPS' => array(
        'POPULAR_SETTINGS' => array(
            'NAME' => 'Настройки главной страницы',
            'SORT' => 800
        ),
        'SECTION_SETTINGS' => array(
            'NAME' => 'Настройки страницы раздела',
            'SORT' => 900
        ),
        'ELEMENT_SETTINGS' => array(
            'NAME' => 'Настройки страницы элемента',
            'SORT' => 1000
        ),
    ),

    'PARAMETERS' => array(

        'IBLOCK_TYPE' => array(
            'PARENT' => 'BASE',
            'NAME' => 'Тип инфоблока',
            'TYPE' => 'LIST',
            'VALUES' => $arInfoBlockTypes,
            'REFRESH' => 'Y',
        ),
        'IBLOCK_ID' => array(
            'PARENT' => 'BASE',
            'NAME' => 'Инфоблок',
            'TYPE' => 'LIST',
            'VALUES' => $arInfoBlocks,
            'REFRESH' => 'Y',
        ),

        'USE_CODE_INSTEAD_ID' => array(
            'PARENT' => 'BASE',
            'NAME' => 'Использовать символьный код вместо ID',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
        ),
        'ADD_SECTIONS_CHAIN' => Array(
            'PARENT' => 'BASE',
            'NAME' => 'Включать родителей в цепочку навигации',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
        'POPULAR_ROOT_SECTIONS' => array(
            'PARENT' => 'POPULAR_SETTINGS',
            'NAME' => 'Показывать корневые разделы',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
        'POPULAR_SECTIONS' => array(
            'PARENT' => 'POPULAR_SETTINGS',
            'NAME' => 'Выберите разделы инфоблока',
            'TYPE' => 'LIST',
            'VALUES' => $arInfoBlockSections,
            'MULTIPLE'=>'Y',
            'REFRESH' => 'Y',
        ),
        'POPULAR_ELEMENT_COUNT' => array(
            'PARENT' => 'POPULAR_SETTINGS',
            'NAME' => 'Максимальное количество элементов в разделе',
            'TYPE' => 'STRING',
            'DEFAULT' => '3',
        ),
        'POPULAR_SET_PAGE_TITLE' => array(
            'PARENT' => 'POPULAR_SETTINGS',
            'NAME' => 'Устанавливать заголовок страницы из названия инфоблока',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
        'POPULAR_SET_BROWSER_TITLE' => array(
            'PARENT' => 'POPULAR_SETTINGS',
            'NAME' => 'Устанавливать заголовок окна браузера из названия инфоблока',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
        'SECTION_ELEMENT_COUNT' => array(
            'PARENT' => 'SECTION_SETTINGS',
            'NAME' => 'Количество элементов на странице',
            'TYPE' => 'STRING',
            'DEFAULT' => '3',
        ),
        'SECTION_SET_PAGE_TITLE' => array(
            'PARENT' => 'SECTION_SETTINGS',
            'NAME' => 'Устанавливать заголовок страницы для раздела',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
        'SECTION_SET_BROWSER_TITLE' => array(
            'PARENT' => 'SECTION_SETTINGS',
            'NAME' => 'Устанавливать заголовок окна браузера для раздела',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
        'SECTION_SET_META_KEYWORDS' => array(
            'PARENT' => 'SECTION_SETTINGS',
            'NAME' => 'Устанавливать мета-тег keywords для раздела',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
        'SECTION_SET_META_DESCRIPTION' => array(
            'PARENT' => 'SECTION_SETTINGS',
            'NAME' => 'Устанавливать мета-тег description для раздела',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
        'ELEMENT_SET_PAGE_TITLE' => array(
            'PARENT' => 'ELEMENT_SETTINGS',
            'NAME' => 'Устанавливать заголовок страницы для элемента',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
        'ELEMENT_SET_BROWSER_TITLE' => array(
            'PARENT' => 'ELEMENT_SETTINGS',
            'NAME' => 'Устанавливать заголовок окна браузера для элемента',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
        'ELEMENT_SET_META_KEYWORDS' => array(
            'PARENT' => 'ELEMENT_SETTINGS',
            'NAME' => 'Устанавливать мета-тег keywords для элемента',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
        'ELEMENT_SET_META_DESCRIPTION' => array(
            'PARENT' => 'ELEMENT_SETTINGS',
            'NAME' => 'Устанавливать мета-тег description для элемента',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
        'VARIABLE_ALIASES' => array( // это для работы в режиме без ЧПУ
            'SECTION_ID' => array('NAME' => 'Идентификатор раздела'),
            'SECTION_CODE' => array('NAME' => 'Символьный код раздела'),
            'ELEMENT_ID' => array('NAME' => 'Идентификатор элемента'),
            'ELEMENT_CODE' => array('NAME' => 'Символьный код элемента'),
        ),
        'SEF_MODE' => array( // это для работы в режиме ЧПУ
            'popular' => array(
                'NAME' => 'Главная страница',
                'DEFAULT' => '',
            ),
            'section' => array(
                'NAME' => 'Страница раздела',
                'DEFAULT' => 'category/id/#SECTION_ID#/',
            ),
            'element' => array(
                'NAME' => 'Страница элемента',
                'DEFAULT' => 'item/id/#ELEMENT_ID#/',
            ),
        ),

        /*
         * Настройки кэширования
         */
        'CACHE_TIME'  =>  array('DEFAULT' => 3600),
        'CACHE_GROUPS' => array( // учитываться права доступа при кешировании?
            'PARENT' => 'CACHE_SETTINGS',
            'NAME' => 'Учитывать права доступа',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
    ),
);

// настройка постраничной навигации
CIBlockParameters::AddPagerSettings(
    $arComponentParameters,
    'Элементы',  // $pager_title
    false,       // $bDescNumbering
    true         // $bShowAllParam
);

// настройки на случай, если раздел или элемент не найдены, 404 Not Found
CIBlockParameters::Add404Settings($arComponentParameters, $arCurrentValues);
