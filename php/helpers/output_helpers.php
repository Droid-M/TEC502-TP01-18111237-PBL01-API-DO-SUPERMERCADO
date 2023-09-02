<?php

function dd(...$vars)
{
    header("HTTP/1.0 500");
    foreach ($vars as $var) {
        var_dump($var);
    }
    // var_dump($var);
    die(1);
}

function dump_array($array, $indent = 0)
{
    header("HTTP/1.0 500");
    $output = '[';
    $innerIndent = str_repeat(' ', $indent + 4);

    foreach ($array as $key => $value) {
        $output .= "\n$innerIndent'$key' => ";

        if (is_array($value)) {
            $output .= dump_array($value, $indent + 4);
        } else {
            $output .= "'$value'";
        }

        $output .= ',';
    }

    $output .= "\n" . str_repeat(' ', $indent) . ']';

    echo $output;
    die(1);
}
