<?php

session_start();

if (isset($_GET['lang'])) {

    $allowed = ['el', 'en', 'ru'];

    if (in_array($_GET['lang'], $allowed)) {
        $_SESSION['lang'] = $_GET['lang'];
    }
}

$lang = $_SESSION['lang'] ?? 'el';

$langFile = __DIR__ . "/../lang/{$lang}.php";

$GLOBALS['translations'] = require $langFile;

function t($key)
{
    return $GLOBALS['translations'][$key] ?? $key;
}