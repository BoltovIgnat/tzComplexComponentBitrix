<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

// проверяем, установлен ли модуль «Информационные блоки»; если да — то подключаем его
if (!CModule::IncludeModule('iblock')) {
    return;
}


$arIBlockType = CIBlockParameters::GetIBlockTypes();

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
while ($iblock = $rsIBlock->Fetch()) {
    $arInfoBlocks[$iblock['ID']] = '['.$iblock['ID'].'] '.$iblock['NAME'];
}

/*
 * Настройки компонента
 */
$arComponentParameters = array(
    'GROUPS' => array( // кроме групп по умолчанию, добавляем свою группу настроек
        'SEO_SETTINGS' => array(
            'NAME' => 'Настройки SEO',
            'SORT' => 800
        ),
    ),
    'PARAMETERS' => array(
        // выбор типа инфоблока
        'IBLOCK_TYPE' => array(
            'PARENT' => 'BASE',
            'NAME' => 'Выберите тип инфоблока',
            'TYPE' => 'LIST',
            'VALUES' => $arIBlockType,
            'REFRESH' => 'Y',
        ),
        // выбор самого инфоблока
        'IBLOCK_ID' => array(
            'PARENT' => 'BASE',
            'NAME' => 'Выберите инфоблок',
            'TYPE' => 'LIST',
            'VALUES' => $arInfoBlocks,
            'REFRESH' => 'Y',
        ),

        // идентификатор раздела получать из $_REQUEST["SECTION_ID"]
        'SECTION_ID' => array(
            'PARENT' => 'BASE',
            'NAME' => 'Идентификатор раздела',
            'TYPE' => 'STRING',
            'DEFAULT' => '={$_REQUEST["SECTION_ID"]}',
        ),
        // символьный код раздела получать из $_REQUEST["SECTION_CODE"]
        'SECTION_CODE' => array(
            'PARENT' => 'BASE',
            'NAME' => 'Символьный код раздела',
            'TYPE' => 'STRING',
            'DEFAULT' => '={$_REQUEST["SECTION_CODE"]}',
        ),
        'ELEMENT_COUNT' => array(
            'PARENT' => 'BASE',
            'NAME' => 'Количество элементов на странице',
            'TYPE' => 'STRING',
            'DEFAULT' => '3',
        ),

        // использовать символьный код вместо ID; если отмечен этот checkbox,
        // в визуальном редакторе надо будет обязательно изменить SECTION_URL
        // и ELEMENT_URL, чтобы вместо #SECTION_ID# и #ELEMENT_ID# в шаблонах
        // ссылок использовались #SECTION_CODE# и #ELEMENT_CODE#
        'USE_CODE_INSTEAD_ID' => array(
            'PARENT' => 'URL_TEMPLATES',
            'NAME' => 'Использовать символьный код вместо ID',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
        ),
        // шаблон ссылки на страницу раздела
        'SECTION_URL' => array(
            'PARENT' => 'URL_TEMPLATES',
            'NAME' => 'URL, ведущий на страницу с содержимым раздела',
            'TYPE' => 'STRING',
            'DEFAULT' => 'category/id/#SECTION_ID#/'
        ),
        // шаблон ссылки на страницу элемента
        'ELEMENT_URL' => array(
            'PARENT' => 'URL_TEMPLATES',
            'NAME' => 'URL, ведущий на страницу с содержимым элемента',
            'TYPE' => 'STRING',
            'DEFAULT' => 'item/id/#ELEMENT_ID#/'
        ),

        // настройки SEO
        'SET_PAGE_TITLE' => array(
            'PARENT' => 'SEO_SETTINGS',
            'NAME' => 'Устанавливать заголовок страницы',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
        'SET_BROWSER_TITLE' => array(
            'PARENT' => 'SEO_SETTINGS',
            'NAME' => 'Устанавливать заголовок окна браузера',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
        'SET_META_KEYWORDS' => array(
            'PARENT' => 'SEO_SETTINGS',
            'NAME' => 'Устанавливать мета-тег keywords',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
        'SET_META_DESCRIPTION' => array(
            'PARENT' => 'SEO_SETTINGS',
            'NAME' => 'Устанавливать мета-тег description',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),

        // включать раздел в цепочку навигации?
        'ADD_SECTIONS_CHAIN' => Array(
            'PARENT' => 'ADDITIONAL_SETTINGS',
            'NAME' => 'Включать раздел в цепочку навигации',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),

        // настройки кэширования
        'CACHE_TIME'  =>  array('DEFAULT' => 3600),
        'CACHE_GROUPS' => array(
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

// добавляем еще одну настройку — на случай, если раздел инфоблока не найден
CIBlockParameters::Add404Settings($arComponentParameters, $arCurrentValues);
