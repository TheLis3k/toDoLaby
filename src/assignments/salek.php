<?php

$zadania = [
    ["id" => 1,  "nazwa" => "T01", "start" => 480,  "koniec" => 600],
    ["id" => 2,  "nazwa" => "T02", "start" => 510,  "koniec" => 720],
    ["id" => 3,  "nazwa" => "T03", "start" => 540,  "koniec" => 660],
    ["id" => 4,  "nazwa" => "T04", "start" => 600,  "koniec" => 690],
    ["id" => 5,  "nazwa" => "T05", "start" => 660,  "koniec" => 780],
    ["id" => 6,  "nazwa" => "T06", "start" => 690,  "koniec" => 840],
    ["id" => 7,  "nazwa" => "T07", "start" => 720,  "koniec" => 810],
    ["id" => 8,  "nazwa" => "T08", "start" => 780,  "koniec" => 900],
    ["id" => 9,  "nazwa" => "T09", "start" => 840,  "koniec" => 960],
    ["id" => 10, "nazwa" => "T10", "start" => 480,  "koniec" => 540],
    ["id" => 11, "nazwa" => "T11", "start" => 570,  "koniec" => 630],
    ["id" => 12, "nazwa" => "T12", "start" => 750,  "koniec" => 870],
    ["id" => 13, "nazwa" => "T13", "start" => 900,  "koniec" => 990],
    ["id" => 14, "nazwa" => "T14", "start" => 495,  "koniec" => 555],
    ["id" => 15, "nazwa" => "T15", "start" => 870,  "koniec" => 930],
];

function minutyNaCzas(int $m): string
{
    return floor($m / 60) . ":" . str_pad($m % 60, 2, '0', STR_PAD_LEFT);
}

$zadaniaSingle = $zadania;
usort($zadaniaSingle, fn($a, $b) => $a['koniec'] <=> $b['koniec']);

$wybraneJednaSala = [];
$ostatniKoniec = -1;

foreach ($zadaniaSingle as $z) {
    if ($z['start'] >= $ostatniKoniec) {
        $wybraneJednaSala[] = $z;
        $ostatniKoniec = $z['koniec'];
    }
}

echo "Algorytm zachłanny (jedna sala):<br>";
echo "Wybrane zadania (" . count($wybraneJednaSala) . "): ";
$nazwyWybrane = array_column($wybraneJednaSala, 'nazwa');
echo implode(', ', $nazwyWybrane) . "<br>";

echo "Kolejność decyzji: ";
$decyzje = [];
foreach ($wybraneJednaSala as $z) {
    $decyzje[] = "{$z['nazwa']}(" . minutyNaCzas($z['start']) . "–" . minutyNaCzas($z['koniec']) . ")";
}
echo implode(' → ', $decyzje) . "<br><br>";

$konflikty = [];
foreach ($zadania as $idxA => $zA) {
    $licznik = 0;
    foreach ($zadania as $idxB => $zB) {
        if ($idxA === $idxB) {
            continue;
        }
        if (max($zA['start'], $zB['start']) < min($zA['koniec'], $zB['koniec'])) {
            $licznik++;
        }
    }
    $konflikty[$zA['nazwa']] = $licznik;
}

arsort($konflikty);
$najbardziejKonfliktowe = key($konflikty);
$maxKonfliktow = current($konflikty);

echo "Konflikty:<br>";
echo "Najbardziej konfliktowe: $najbardziejKonfliktowe ($maxKonfliktow kolizji z innymi zadaniami)<br><br>";

$zadaniaMulti = $zadania;
usort($zadaniaMulti, fn($a, $b) => $a['start'] <=> $b['start']);

$sale = [];
foreach ($zadaniaMulti as $z) {
    $przypisano = false;
    foreach ($sale as &$sala) {
        $ostatnieZadanie = end($sala);
        if ($ostatnieZadanie['koniec'] <= $z['start']) {
            $sala[] = $z;
            $przypisano = true;
            break;
        }
    }
    if (!$przypisano) {
        $sale[] = [$z];
    }
}

echo "Minimalna liczba sal: " . count($sale) . "<br>";
foreach ($sale as $nr => $harmonogram) {
    echo "Sala " . ($nr + 1) . ": ";
    $wpisy = [];
    foreach ($harmonogram as $z) {
        $wpisy[] = "{$z['nazwa']}(" . minutyNaCzas($z['start']) . "–" . minutyNaCzas($z['koniec']) . ")";
    }
    echo implode(', ', $wpisy) . "<br>";
}
