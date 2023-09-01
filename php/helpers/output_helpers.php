<?php

function dd(...$vars)
{
    foreach ($vars as $var)
    {
        var_dump($var);
    }
    // var_dump($var);
    die();
}