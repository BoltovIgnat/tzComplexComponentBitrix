<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$arVariables = array();


$arComponentVariables = array(
    'sort',
    'dir',
);

$arComponentVariables[] = 'ACTION';

$arDefaultVariableAliases = array(
    'ELEMENT_COUNT' => 'count'
);


$arVariableAliases = CComponentEngine::MakeComponentVariableAliases(
    $arDefaultVariableAliases,    // массив псевдонимов переменных по умолчанию
    $arParams['VARIABLE_ALIASES'] // массив псевдонимов из входных параметров
);

CComponentEngine::InitComponentVariables(
    false,                 // в режиме не ЧПУ всегда false
    $arComponentVariables, // массив имен переменных, которые компонент может получать из запроса
    $arVariableAliases,    // массив псевдонимов переменных
    $arVariables           // массив, в котором возвращаются восстановленные переменные
);

$componentPage = 'popular';
if (isset($arVariables['ACTION']) && $arVariables['ACTION'] == 'element') {
    $componentPage = 'element'; // элемент инфоблока
}
if (isset($arVariables['ACTION']) && $arVariables['ACTION'] == 'section') {
    $componentPage = 'section'; // раздел инфоблока
}

$notFound = false;
// недопустимое значение идентификатора элемента
if ($componentPage == 'element') {
    if ($arParams['USE_CODE_INSTEAD_ID'] == 'Y') { // если используются символьные коды
        if (!(isset($arVariables['ELEMENT_CODE']) && strlen($arVariables['ELEMENT_CODE']) > 0)) {
            $notFound = true;
        }
    } else { // если используются идентификаторы
        if (!(isset($arVariables['ELEMENT_ID']) && ctype_digit($arVariables['ELEMENT_ID']))) {
            $notFound = true;
        }
    }
}
// недопустимое значение идентификатора раздела
if ($componentPage == 'section') {
    if ($arParams['USE_CODE_INSTEAD_ID'] == 'Y') { // если используются символьные коды
        if (!(isset($arVariables['SECTION_CODE']) && strlen($arVariables['SECTION_CODE']) > 0)) {
            $notFound = true;
        }
    } else { // если используются идентификаторы
        if (!(isset($arVariables['SECTION_ID']) && ctype_digit($arVariables['SECTION_ID']))) {
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

$arResult['VARIABLES'] = $arVariables;
$arResult['FOLDER'] = '';
if ($arParams['USE_CODE_INSTEAD_ID'] == 'Y') { // если используются символьные коды
    $arResult['SECTION_URL'] =
        $APPLICATION->GetCurPage().'?ACTION=section'.'&'.$arVariableAliases['SECTION_CODE'].'=#SECTION_CODE#';
    $arResult['ELEMENT_URL'] =
        $APPLICATION->GetCurPage().'?ACTION=element'.'&'.$arVariableAliases['ELEMENT_CODE'].'=#ELEMENT_CODE#';
} else { // если используются идентификаторы
    $arResult['SECTION_URL'] = 
        $APPLICATION->GetCurPage().'?ACTION=section'.'&'.$arVariableAliases['SECTION_ID'].'=#SECTION_ID#';
    $arResult['ELEMENT_URL'] =
        $APPLICATION->GetCurPage().'?ACTION=element'.'&'.$arVariableAliases['ELEMENT_ID'].'=#ELEMENT_ID#';
}

$this->IncludeComponentTemplate($componentPage);