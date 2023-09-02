<?php

// function dd(...$vars)
// {
//     header("HTTP/1.0 500");
//     foreach ($vars as $var) {
//         var_dump($var);
//     }
//     // var_dump($var);
//     die(1);
// }

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

// IMPORTANT - O trecho de código abaixo foi adaptado de "https://stackoverflow.com/a/47964924"

function pseud_dump(mixed $data)
{
    if (is_null($data)) {
        $str = "<i>NULL</i>";
    } elseif ($data == "") {
        $str = "<i>Empty</i>";
    } elseif (is_array($data)) {
        if (count($data) == 0) {
            $str = "<i>Empty array.</i>";
        } else {
            $str = "<table style=\"border-bottom:0px solid #000;\" cellpadding=\"0\" cellspacing=\"0\">";
            foreach ($data as $key => $value) {
                $str .= "<tr><td style=\"background-color:#008B8B; color:#FFF;border:1px solid #000;\">" . $key . "</td><td style=\"border:1px solid #000;\">" . pseud_dump($value) . "</td></tr>";
            }
            $str .= "</table>";
        }
    } elseif (is_resource($data)) {
        while ($arr = mysql_fetch_array($data)) {
            $data_array[] = $arr;
        }
        $str = pseud_dump($data_array);
    } elseif (is_object($data)) {
        $str = pseud_dump(get_object_vars($data));
    } elseif (is_bool($data)) {
        $str = "<i>" . ($data ? "True" : "False") . "</i>";
    } else {
        $str = $data;
        $str = preg_replace("/\n/", "<br>\n", $str);
    }
    return $str;
}

function dump(mixed $data)
{
    echo pseud_dump($data) . "<br>\n";
}

function dd(mixed $data)
{
    header("HTTP/1.0 500");
    echo pseud_dump($data) . "<br>\n";
    exit;
}
