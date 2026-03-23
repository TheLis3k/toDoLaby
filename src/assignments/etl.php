<?php

$rekordy = [
    ["id" => 1,  "imie" => "anna",    "wiek" => "25",  "email" => "anna@test.com",   "wynik" => 92.5],
    ["id" => 2,  "imie" => "Bartosz", "wiek" => "abc", "email" => "bartosz@test.com","wynik" => 78.0],
    ["id" => 3,  "imie" => "celina",  "wiek" => "31",  "email" => "celina@test.com", "wynik" => 105.0],
    ["id" => 4,  "imie" => "Dawid",   "wiek" => "45",  "email" => "",               "wynik" => 66.5],
    ["id" => 5,  "imie" => "EWA",     "wiek" => "28",  "email" => "ewa@test.com",    "wynik" => 88.0],
    ["id" => 6,  "imie" => "filip",   "wiek" => "130", "email" => "filip@test.com",  "wynik" => 74.0],
    ["id" => 7,  "imie" => "Grażyna", "wiek" => "52",  "email" => "anna@test.com",   "wynik" => 91.0],
    ["id" => 8,  "imie" => "Henryk",  "wiek" => "19",  "email" => "henryk@test.com", "wynik" => -5.0],
    ["id" => 9,  "imie" => "irena",   "wiek" => "37",  "email" => "irena@test.com",  "wynik" => 83.5],
    ["id" => 10, "imie" => "JANEK",   "wiek" => "22",  "email" => "janek@test.com",  "wynik" => 55.0],
    ["id" => 11, "imie" => "Kasia",   "wiek" => "29",  "email" => "kasia@test.com",  "wynik" => 97.0],
    ["id" => 12, "imie" => "Leon",    "wiek" => "41",  "email" => "leon@test.com",   "wynik" => 62.0],
    ["id" => 13, "imie" => "Marta",   "wiek" => "0",   "email" => "marta@test.com",  "wynik" => 79.5],
    ["id" => 14, "imie" => "norbert", "wiek" => "33",  "email" => "norbert@test.com","wynik" => 86.0],
    ["id" => 15, "imie" => "Ola",     "wiek" => "26",  "email" => "ola@test.com",    "wynik" => 91.0],
];

function waliduj(array $dane): array
{
    $wynik = ['valid' => [], 'rejected' => []];
    $seenEmails = [];

    foreach ($dane as $r) {
        $emailClean = trim($r['email']);

        if (empty($emailClean)) {
            $wynik['rejected'][] = "ID {$r['id']} ({$r['imie']}): pusty email";
            continue;
        }

        if (in_array($emailClean, $seenEmails)) {
            $wynik['rejected'][] = "ID {$r['id']} ({$r['imie']}): duplikat email '{$emailClean}'";
            continue;
        }

        $wiekValid = filter_var($r['wiek'], FILTER_VALIDATE_INT);
        if ($wiekValid === false || $wiekValid < 1 || $wiekValid > 120) {
            $wynik['rejected'][] = "ID {$r['id']} ({$r['imie']}): nieprawidłowy wiek '{$r['wiek']}'";
            continue;
        }

        if ($r['wynik'] < 0.0 || $r['wynik'] > 100.0) {
            $wynik['rejected'][] = "ID {$r['id']} ({$r['imie']}): wynik poza zakresem [0–100]: {$r['wynik']}";
            continue;
        }

        $seenEmails[] = $emailClean;
        $wynik['valid'][] = $r;
    }
    return $wynik;
}

function transformuj(array $dane): array
{
    $transformed = [];
    foreach ($dane as $r) {
        $transformed[] = [
            'id' => $r['id'],
            'imie' => ucfirst(strtolower($r['imie'])),
            'wiek' => (int) $r['wiek'],
            'email' => trim($r['email']),
            'wynik' => (float) $r['wynik'],
        ];
    }
    return $transformed;
}

$etapE = waliduj($rekordy);
$odrzucone = $etapE['rejected'];

$etapT = transformuj($etapE['valid']);

echo "=== Etap E: Walidacja ===<br>";
echo "Odrzucone rekordy (" . count($odrzucone) . "):<br>";
foreach ($odrzucone as $err) {
    echo " - $err<br>";
}
echo "<br>";

echo "=== Etap L: Finalna baza (" . count($etapT) . " rekordów) ===<br>";
printf("%-12s | %4s | %-25s | %5s | %s<br>", "Imię", "Wiek", "Email", "Wynik", "Ocena");
echo str_repeat("-", 65) . "<br>";

$statystyki = ['A' => [], 'B' => [], 'C' => [], 'D' => []];

foreach ($etapT as $r) {
    if ($r['wynik'] >= 90) {
        $ocena = 'A';
    } elseif ($r['wynik'] >= 75) {
        $ocena = 'B';
    } elseif ($r['wynik'] >= 60) {
        $ocena = 'C';
    } else {
        $ocena = 'D';
    }

    $statystyki[$ocena][] = $r['wynik'];

    printf(
        "%-12s | %4d | %-25s | %5.1f | %s<br>",
        $r['imie'],
        $r['wiek'],
        $r['email'],
        $r['wynik'],
        $ocena,
    );
}

echo "<br>Rozkład ocen:<br>";
foreach ($statystyki as $kat => $oceny) {
    $ile = count($oceny);
    $srednia = $ile > 0 ? array_sum($oceny) / $ile : 0;
    printf(" %s: %d studentów, średnia: %.1f%%<br>", $kat, $ile, $srednia);
}
