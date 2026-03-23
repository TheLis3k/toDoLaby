<?php

$transakcje = [
    ["id" => 1,  "data" => "2024-01-15","kategoria" => "Elektronika","kwota" => 1200.00],
    ["id" => 2,  "data" => "2024-01-22","kategoria" => "Dom",        "kwota" => 350.00],
    ["id" => 3,  "data" => "2024-02-03","kategoria" => "Elektronika","kwota" => 800.00],
    ["id" => 4,  "data" => "2024-02-14","kategoria" => "Odzież",     "kwota" => 250.00],
    ["id" => 5,  "data" => "2024-02-28","kategoria" => "Dom",        "kwota" => 420.00],
    ["id" => 6,  "data" => "2024-03-05","kategoria" => "Elektronika","kwota" => 1500.00],
    ["id" => 7,  "data" => "2024-03-12","kategoria" => "Odzież",     "kwota" => 180.00],
    ["id" => 8,  "data" => "2024-03-19","kategoria" => "Dom",        "kwota" => 290.00],
    ["id" => 9,  "data" => "2024-01-08","kategoria" => "Odzież",     "kwota" => 310.00],
    ["id" => 10, "data" => "2024-01-30","kategoria" => "Elektronika","kwota" => 950.00],
    ["id" => 11, "data" => "2024-02-10","kategoria" => "Dom",        "kwota" => 600.00],
    ["id" => 12, "data" => "2024-03-25","kategoria" => "Odzież",     "kwota" => 430.00],
    ["id" => 13, "data" => "2024-01-18","kategoria" => "Elektronika","kwota" => 2100.00],
    ["id" => 14, "data" => "2024-02-22","kategoria" => "Dom",        "kwota" => 175.00],
    ["id" => 15, "data" => "2024-03-08","kategoria" => "Elektronika","kwota" => 670.00],
    ["id" => 16, "data" => "2024-01-25","kategoria" => "Odzież",     "kwota" => 520.00],
    ["id" => 17, "data" => "2024-02-17","kategoria" => "Elektronika","kwota" => 1350.00],
    ["id" => 18, "data" => "2024-03-14","kategoria" => "Dom",        "kwota" => 480.00],
    ["id" => 19, "data" => "2024-01-12","kategoria" => "Dom",        "kwota" => 230.00],
    ["id" => 20, "data" => "2024-02-05","kategoria" => "Odzież",     "kwota" => 390.00],
];

$pivot = [];
$raw_data = [];
$miesiace = ["2024-01", "2024-02", "2024-03"];

foreach ($transakcje as $t) {
    $kat = $t['kategoria'];
    $msc = substr($t['data'], 0, 7);
    $kwota = $t['kwota'];

    if (!isset($pivot[$kat])) {
        foreach ($miesiace as $m) {
            $pivot[$kat][$m] = 0;
        }
        $raw_data[$kat] = [];
    }

    $pivot[$kat][$msc] += $kwota;
    $raw_data[$kat][] = $kwota;
}

ksort($pivot);

printf("%-14s | %8s | %8s | %8s<br>", "Kategoria", "Styczeń", "Luty", "Marzec");
echo str_repeat("-", 46) . "<br>";

foreach ($pivot as $kat => $dane_msc) {
    printf(
        "%-14s | %8.2f | %8.2f | %8.2f<br>",
        $kat,
        $dane_msc["2024-01"],
        $dane_msc["2024-02"],
        $dane_msc["2024-03"],
    );
}

echo "<br>Odchylenia standardowe (σ):<br>";

$max_sigma = -1;
$max_kat = "";
$sigmas = [];

foreach ($raw_data as $kat => $kwoty) {
    $n = count($kwoty);
    $avg = array_sum($kwoty) / $n;

    $suma_kwadratow = 0;
    foreach ($kwoty as $x) {
        $suma_kwadratow += pow($x - $avg, 2);
    }

    $sigma = sqrt($suma_kwadratow / $n);
    $sigmas[$kat] = $sigma;

    if ($sigma > $max_sigma) {
        $max_sigma = $sigma;
        $max_kat = $kat;
    }

    printf("  %-12s : σ=%.2f (n=%d, avg=%.2f zł)<br>", $kat, $sigma, $n, $avg);
}

printf("<br>Kategoria o największej zmienności: %s (σ=%.2f)<br>", $max_kat, $max_sigma);
