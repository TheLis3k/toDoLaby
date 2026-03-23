<?php

function s_push(array &$stos, $val): void
{
    array_splice($stos, count($stos), 0, [$val]);
}

function s_pop(array &$stos)
{
    if (count($stos) === 0) {
        return null;
    }
    $top = $stos[count($stos) - 1];
    array_splice($stos, -1, 1);
    return $top;
}

function s_peek(array $stos)
{
    return count($stos) > 0 ? $stos[count($stos) - 1] : null;
}

function waliduj_nawiasy($napis)
{
    $stos = [];
    $pary = [')' => '(', ']' => '[', '}' => '{'];
    $otwierajace = ['(', '[', '{'];

    for ($i = 0; $i < strlen($napis); $i++) {
        $znak = $napis[$i];
        if (in_array($znak, $otwierajace)) {
            s_push($stos, $znak);
        } elseif (isset($pary[$znak])) {
            if (count($stos) === 0 || s_pop($stos) !== $pary[$znak]) {
                return false;
            }
        }
    }
    return count($stos) === 0;
}

function oblicz_onp($wyrazenie)
{
    $stos = [];
    $tokeny = explode(' ', $wyrazenie);

    foreach ($tokeny as $token) {
        if (is_numeric($token)) {
            s_push($stos, (float) $token);
        } else {
            $b = s_pop($stos);
            $a = s_pop($stos);
            switch ($token) {
                case '+': s_push($stos, $a + $b);
                    break;
                case '-': s_push($stos, $a - $b);
                    break;
                case '*': s_push($stos, $a * $b);
                    break;
                case '/': s_push($stos, $a / $b);
                    break;
            }
        }
    }
    return s_pop($stos);
}

$wyrazenia_ONP = [
    "5 2 + 3 *",
    "15 7 1 1 + - / 3 * 2 1 1 + + -",
    "4 13 5 / +",
    "2 3 + 4 * 5 -",
    "100 50 25 / -",
];

$napisy_nawiasy = [
    "[({()})]",
    "((())",
    "{[()]}",
    "([)]",
    "",
];

$bufor = array_fill(0, 5, 0);
$pos = 0;

for ($i = 0; $i < 5; $i++) {
    $nawiasy_ok = waliduj_nawiasy($napisy_nawiasy[$i]);
    $wynik_onp = oblicz_onp($wyrazenia_ONP[$i]);

    $bufor[$pos % 5] = $wynik_onp;
    $pos++;

    $status_nawiasy = $nawiasy_ok ? "OK  " : "BŁĄD";
    echo "[" . ($i + 1) . "] Nawiasy \"{$napisy_nawiasy[$i]}\": $status_nawiasy | ONP \"{$wyrazenia_ONP[$i]}\" = $wynik_onp<br>";
}

echo "Bufor cykliczny (ostatnie 5 wyników): [" . implode(', ', $bufor) . "]<br>";
