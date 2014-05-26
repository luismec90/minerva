<?php

$b1 = ["A", "B", "C"];
$b2 = ["D", "E", "F", "G"];
$b3 = ["H", "I"];
$b4 = ["J", "K", "L"];
$A = array($b1, $b2, $b3, $b4);

cartesiano(0, $A, sizeof($A), "");

function cartesiano($j, $A, $n, $str) {
    if ($j == $n) {
        echo "$str<br>";
    } else {
        for ($i = 0; $i < sizeof($A[$j]); $i++) {
            $str.=$A[$j][$i];
            cartesiano($j + 1, $A, $n, $str);
            $str = substr($str, 0, -1);
        }
    }
}
