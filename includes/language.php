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



function formatDate($date)
{
    if (empty($date)) {
        return "";
    }

    global $lang;

    $timestamp = strtotime($date);

    $months = [

        'el' => [
            1 => 'Ιανουαρίου',
            2 => 'Φεβρουαρίου',
            3 => 'Μαρτίου',
            4 => 'Απριλίου',
            5 => 'Μαΐου',
            6 => 'Ιουνίου',
            7 => 'Ιουλίου',
            8 => 'Αυγούστου',
            9 => 'Σεπτεμβρίου',
            10 => 'Οκτωβρίου',
            11 => 'Νοεμβρίου',
            12 => 'Δεκεμβρίου'
        ],

        'en' => [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ],

        'ru' => [
            1 => 'января',
            2 => 'февраля',
            3 => 'марта',
            4 => 'апреля',
            5 => 'мая',
            6 => 'июня',
            7 => 'июля',
            8 => 'августа',
            9 => 'сентября',
            10 => 'октября',
            11 => 'ноября',
            12 => 'декабря'
        ]

    ];

    $day = date('d', $timestamp);
    $month = $months[$lang][(int)date('n', $timestamp)];
    $year = date('Y', $timestamp);

    if ($lang === 'en') {
        return "$month $day, $year";
    }

    return "$day $month $year";
}