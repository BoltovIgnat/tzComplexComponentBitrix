<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if ($arParams['SEF_MODE'] == 'Y') {
    require 'support-sef-url.php'; // включен режим поддержки ЧПУ
} else {
    require 'without-sef-url.php'; // не включен режим поддержки ЧПУ
}
