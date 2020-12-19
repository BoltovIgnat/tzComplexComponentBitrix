<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$arVariables = array();

$arComponentVariables = array(
    'sort',
    'dir',
);

$arDefaultVariableAliases404 = array(
    'section' => array(
        'ELEMENT_COUNT' => 'count',
    ),
);

if ($arParams['USE_CODE_INSTEAD_ID'] == 'Y') { // если используются символьные коды
    $arDefaultUrlTemplates404 = array(
        'popular' => '',
        'section' => 'category/code/#SECTION_CODE#/',
        'element' => 'item/code/#ELEMENT_CODE#/',
    );
} else { // если используются идентификаторы
    $arDefaultUrlTemplates404 = array(
        'popular' => '',
        'section' => 'category/id/#SECTION_ID#/',
        'element' => 'item/id/#ELEMENT_ID#/',
    );
}

$arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates(
    $arDefaultUrlTemplates404,
    $arParams['SEF_URL_TEMPLATES']
);

$arVariableAliases = CComponentEngine::MakeComponentVariableAliases(
    $arDefaultVariableAliases404,
    $arParams['VARIABLE_ALIASES']
);

$componentPage = CComponentEngine::ParseComponentPath(
    $arParams['SEF_FOLDER'],
    $arUrlTemplates, 
    $arVariables
);

if ($componentPage === false && parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == $arParams['SEF_FOLDER']) {
    $componentPage = 'popular';
}

// Если определить файл шаблона не удалось, показываем  страницу 404 Not Found
if (empty($componentPage) && CModule::IncludeModule('iblock')) {
    \Bitrix\Iblock\Component\Tools::process404(
        trim($arParams['MESSAGE_404']) ?: 'Элемент или раздел инфоблока не найден',
        true,
        $arParams['SET_STATUS_404'] === 'Y',
        $arParams['SHOW_404'] === 'Y',
        $arParams['FILE_404']
    );
    return;
}


$notFound = false;
// недопустимое значение идентификатора элемента
if ($componentPage == 'element') {
    if ($arParams['USE_CODE_INSTEAD_ID'] == 'Y') { // если используются символьные коды
        if ( ! (isset($arVariables['ELEMENT_CODE']) && strlen($arVariables['ELEMENT_CODE']) > 0)) {
            $notFound = true;
        }
    } else { // если используются идентификаторы
        if ( ! (isset($arVariables['ELEMENT_ID']) && ctype_digit($arVariables['ELEMENT_ID']))) {
            $notFound = true;
        }
    }
}
// недопустимое значение идентификатора раздела
if ($componentPage == 'section') {
    if ($arParams['USE_CODE_INSTEAD_ID'] == 'Y') { // если используются символьные коды
        if ( ! (isset($arVariables['SECTION_CODE']) && strlen($arVariables['SECTION_CODE']) > 0)) {
            $notFound = true;
        }
    } else { // если используются идентификаторы
        if ( ! (isset($arVariables['SECTION_ID']) && ctype_digit($arVariables['SECTION_ID']))) {
            $notFound = true;
        }
    }
}
// показываем страницу 404 Not Found
if ($notFound) {
    \Bitrix\Iblock\Component\Tools::process404(
        trim($arParams['MESSAGE_404']) ?: 'Элемент или раздел инфоблока не найден',
        true,
        $arParams['SET_STATUS_404'] === 'Y',
        $arParams['SHOW_404'] === 'Y',
        $arParams['FILE_404']
    );
    return;
}


CComponentEngine::InitComponentVariables(
    $componentPage,
    $arComponentVariables,
    $arVariableAliases,
    $arVariables
);

$arResult['VARIABLES'] = $arVariables;
$arResult['FOLDER'] = $arParams['SEF_FOLDER'];
$arResult['SECTION_URL'] = $arParams['SEF_FOLDER'].$arParams['SEF_URL_TEMPLATES']['section'];
$arResult['ELEMENT_URL'] = $arParams['SEF_FOLDER'].$arParams['SEF_URL_TEMPLATES']['element'];

$this->IncludeComponentTemplate($componentPage);
