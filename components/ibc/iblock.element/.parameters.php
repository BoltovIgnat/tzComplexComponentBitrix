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
while($iblock = $rsIBlock->Fetch()) {
    $arInfoBlocks[$iblock['ID']] = '['.$iblock['ID'].'] '.$iblock['NAME'];
}


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
        ),

        // идентификатор элемента получать из $_REQUEST["ELEMENT_ID"]
        'ELEMENT_ID' => array(
            'PARENT' => 'BASE',
            'NAME' => 'Идентификатор элемента',
            'TYPE' => 'STRING',
            'DEFAULT' => '={$_REQUEST["ELEMENT_ID"]}',
        ),
        // символьный код элемента получать из $_REQUEST["ELEMENT_CODE"]
        'ELEMENT_CODE' => array(
            'PARENT' => 'BASE',
            'NAME' => 'Символьный код элемента',
            'TYPE' => 'STRING',
            'DEFAULT' => '={$_REQUEST["ELEMENT_CODE"]}',
        ),
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

        // SEO-настройки
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
        'CACHE_TIME'  =>  array('DEFAULT'=>3600),
        'CACHE_GROUPS' => array(
            'PARENT' => 'CACHE_SETTINGS',
            'NAME' => 'Учитывать права доступа',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
    ),
);

// добавляем еще одну настройку — на случай, если элемент инфоблока не найден
CIBlockParameters::Add404Settings($arComponentParameters, $arCurrentValues);