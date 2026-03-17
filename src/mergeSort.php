<?php
function mergeSort($arr, &$comparisons) {
    $count = count($arr);
    if ($count <= 1) {
        return $arr;
    }
    $mid = (int) ($count / 2);
    $left = array_slice($arr, 0, $mid);
    $right = array_slice($arr, $mid);
    $leftSorted = mergeSort($left, $comparisons);
    $rightSorted = mergeSort($right, $comparisons);
    return merge($leftSorted, $rightSorted, $comparisons);
}

function merge($left, $right, &$comparisons) {
    $result = [];
    while (!empty($left) && !empty($right)) {
        $comparisons++;
        if ($left[0] <= $right[0]) {
            $result[] = array_shift($left);
        } else {
            $result[] = array_shift($right);
        }
    }
    return array_merge($result, $left, $right);
}

function complexity($comparisons, $n) {
    $k = $comparisons / ($n * log($n, 2));
    return $k;
}

$tablice = [
    [5, 3, 8, 1, 9, 2],
    [38, 27, 43, 3, 9, 82, 10, 15],
    [64, 25, 12, 22, 11, 90, 3, 47, 71, 38, 55, 8],
    [25, 24, 23, 22, 21, 20, 19, 18, 17, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1],
];

foreach ($tablice as $tablica) {
    $n = count($tablica);
    $comparisons = 0;
    $sorted = mergeSort($tablica, $comparisons);
    $sortedVerify = sort($tablica);
    $wspolczynnik = complexity($comparisons, $n);
    if($sorted == $sortedVerify){
        $isSorted = "Tak!";
    } else { $isSorted = "Nie :("; }
    echo "n=" . $n . "<br>";
    echo "| Wejście: [" . implode(", ", $tablica) . "]<br>";
    echo "| Wyjście: [" . implode(", ", $sorted) . "]<br>";
    echo "| Porównania: " . $comparisons . " | K: " . $wspolczynnik . "<br>";
    echo "| Poprawnie posortowane: " . $isSorted . "<br><br>";
}
?>