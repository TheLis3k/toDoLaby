<?php

$oceny = [
    "Anna"    => [5, 4, null, 2, null, 3, 4, 5],
    "Bartek"  => [4, 5, 3, null, 2, 4, null, 4],
    "Celina"  => [5, 3, null, 3, null, 4, 5, null],
    "Dawid"   => [2, null, 4, 5, 3, null, 2, 3],
    "Ewa"     => [null, 4, 3, null, 5, 3, 4, 2],
    "Filip"   => [3, 5, 4, 2, null, 5, null, 4],
    "Grażyna" => [5, null, 2, 4, 3, 2, 5, null],
];

$produkty = ["Laptop", "Monitor", "Klawiatura", "Mysz", "Słuchawki", "Kamera", "Tablet", "Głośnik"];

function pearson($userA, $userB)
{
    $common = [];
    for ($i = 0; $i < 8; $i++) {
        if ($userA[$i] !== null && $userB[$i] !== null) {
            $common[] = $i;
        }
    }

    $n = count($common);
    if ($n < 2) {
        return 0;
    }

    $sumA = 0;
    $sumB = 0;
    foreach ($common as $idx) {
        $sumA += $userA[$idx];
        $sumB += $userB[$idx];
    }
    $avgA = $sumA / $n;
    $avgB = $sumB / $n;

    $num = 0;
    $denA = 0;
    $denB = 0;
    foreach ($common as $idx) {
        $diffA = $userA[$idx] - $avgA;
        $diffB = $userB[$idx] - $avgB;
        $num += $diffA * $diffB;
        $denA += pow($diffA, 2);
        $denB += pow($diffB, 2);
    }

    $den = sqrt($denA * $denB);
    return ($den == 0) ? 0 : $num / $den;
}

$similarities = [];
foreach ($oceny as $osoba => $ratingi) {
    if ($osoba === "Anna") {
        continue;
    }
    $similarities[$osoba] = pearson($oceny["Anna"], $ratingi);
}

arsort($similarities);

echo "Podobieństwo Pearsona dla Anny:<br>";
foreach ($similarities as $osoba => $sim) {
    printf("  %s: %.4f<br>", $osoba, $sim);
}
echo "<br>";

$k = 3;
$sasiadImiona = array_slice(array_keys($similarities), 0, $k);
$knn = [];
echo "k=$k sąsiedzi Anny: ";
$sasiedziString = [];
foreach ($sasiadImiona as $imie) {
    $knn[$imie] = $similarities[$imie];
    $sasiedziString[] = sprintf("%s(%.4f)", $imie, $similarities[$imie]);
}
echo implode(', ', $sasiedziString) . "<br><br>";

$rekomendacje = [];
for ($i = 0; $i < count($produkty); $i++) {
    if ($oceny["Anna"][$i] === null) {
        $num = 0;
        $den = 0;
        foreach ($knn as $imie => $sim) {
            if ($oceny[$imie][$i] !== null) {
                $num += $sim * $oceny[$imie][$i];
                $den += abs($sim);
            }
        }
        if ($den != 0) {
            $rekomendacje[$produkty[$i]] = $num / $den;
        }
    }
}

arsort($rekomendacje);

echo "Rekomendacje dla Anny (produkty nieocenione):<br>";
$lp = 1;
foreach ($rekomendacje as $prod => $score) {
    printf("  %d. %-12s — przewidywana ocena: %.2f<br>", $lp++, $prod, $score);
}
echo "<br>";

echo "Zimny start (Hania, 1 ocena):<br>";
echo "  Za mało wspólnych ocen z innymi użytkownikami — brak wiarygodnych korelacji.<br>";

$popularne = [];
for ($i = 0; $i < count($produkty); $i++) {
    $sum = 0;
    $count = 0;
    foreach ($oceny as $o) {
        if ($o[$i] !== null) {
            $sum += $o[$i];
            $count++;
        }
    }
    $popularne[$produkty[$i]] = $sum / $count;
}
arsort($popularne);
$top = key($popularne);
echo "  Strategia: rekomenduj najpopularniejsze produkty (np. $top, średnia: " . round(current($popularne), 2) . ")<br>";
