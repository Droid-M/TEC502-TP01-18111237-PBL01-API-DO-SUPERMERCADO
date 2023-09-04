<?php

function remove_repeated_chars($string)
{
    $pattern = '/(.)\1+/';
    $replacement = '$1';
    $result = preg_replace($pattern, $replacement, $string);
    return $result;
}

function float_to_currency($numero)
{
    return number_format($numero, 2, '.', '');
}
