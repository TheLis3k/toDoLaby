<?php

$dokumenty = [
    0 => "PHP jest językiem skryptowym używanym do tworzenia stron internetowych",
    1 => "Tablice w PHP mogą być indeksowane lub asocjacyjne i bardzo przydatne",
    2 => "Funkcje array_map i array_filter ułatwiają przetwarzanie tablic w PHP",
    3 => "PHP obsługuje tablice wielowymiarowe i zagnieżdżone struktury danych",
    4 => "Serwer Apache współpracuje z PHP do obsługi żądań HTTP i połączeń",
    5 => "Bazy danych MySQL są często używane razem z PHP do przechowywania",
    6 => "Funkcja usort sortuje tablice w PHP według różnych kryteriów i warunków",
    7 => "JavaScript i PHP razem tworzą dynamiczne aplikacje internetowe i serwisy",
    8 => "PHP posiada wbudowane funkcje do pracy z plikami tablicami i bazami",
    9 => "Bezpieczeństwo aplikacji PHP wymaga walidacji danych wejściowych i filtrów",
];

$stopWords = ['i', 'w', 'na', 'do', 'z', 'są', 'lub', 'być', 'może', 'jest', 'się'];

$index = [];
foreach ($dokumenty as $docId => $tekst) {
    $clean = preg_replace('/[^\p{L}\s]/u', '', mb_strtolower($tekst));
    $slowa = preg_split('/\s+/', $clean, -1, PREG_SPLIT_NO_EMPTY);

    foreach ($slowa as $slowo) {
        if (mb_strlen($slowo) < 3 || in_array($slowo, $stopWords)) {
            continue;
        }

        if (!isset($index[$slowo][$docId])) {
            $index[$slowo][$docId] = 0;
        }
        $index[$slowo][$docId]++;
    }
}

$wordCounts = [];
foreach ($index as $word => $docs) {
    $wordCounts[$word] = array_sum($docs);
}
arsort($wordCounts);
$top5 = array_slice($wordCounts, 0, 5, true);

echo "Top 5 najczęstszych słów:<br>";
foreach ($top5 as $word => $count) {
    echo " - '{$word}': {$count}x<br>";
}
echo "<br>";

function search($query, $type, $index)
{
    $foundDocLists = [];
    foreach ($query as $word) {
        $word = mb_strtolower($word);
        if (isset($index[$word])) {
            $foundDocLists[] = array_keys($index[$word]);
        } else {
            if ($type === 'AND') {
                return [];
            }
            $foundDocLists[] = [];
        }
    }

    if (empty($foundDocLists)) {
        return [];
    }

    if ($type === 'AND') {
        $matchingIds = array_intersect(...$foundDocLists);
    } else {
        $matchingIds = array_unique(array_merge(...$foundDocLists));
    }

    $results = [];
    foreach ($matchingIds as $docId) {
        $score = 0;
        $details = [];
        foreach ($query as $word) {
            $count = $index[$word][$docId] ?? 0;
            if ($count > 0) {
                $score += $count;
                $details[] = "$word:$count";
            }
        }
        $results[$docId] = [
            'score' => $score,
            'details' => implode(', ', $details),
        ];
    }

    uasort($results, fn($a, $b) => $b['score'] <=> $a['score']);
    return $results;
}

echo "Wyniki dla (" . implode(' AND ', ["php", "tablice"]) . "):<br>";
$resAnd = search(["php", "tablice"], 'AND', $index);
$i = 1;
foreach ($resAnd as $id => $data) {
    echo $i++ . ". Dokument ID:$id | Score:{$data['score']} ({$data['details']})<br>";
}
echo "<br>";

echo "Wyniki dla (" . implode(' OR ', ["mysql", "javascript"]) . "):<br>";
$resOr = search(["mysql", "javascript"], 'OR', $index);
$i = 1;
foreach ($resOr as $id => $data) {
    echo $i++ . ". Dokument ID:$id | Score:{$data['score']} ({$data['details']})<br>";
}
