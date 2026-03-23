<?php

$tablica = [];
$historia = [];

function wypisz_tablice($tab)
{
    echo "[" . implode(", ", $tab) . "]";
}

while (true) {
    echo ">> ";
    $linia = fgets(STDIN);
    if ($linia === false) {
        break;
    }

    $linia = trim($linia);
    if (empty($linia)) {
        continue;
    }

    $czesci = explode(' ', $linia);
    $polecenie = strtolower($czesci[0]);
    $sukces = false;

    switch ($polecenie) {
        case 'push':
            if (!isset($czesci[1])) {
                echo "Brak argumentu dla: push";
            } else {
                $tablica[] = $czesci[1];
                wypisz_tablice($tablica);
                $sukces = true;
            }
            break;

        case 'pop':
            if (empty($tablica)) {
                echo "Tablica jest pusta";
            } else {
                $ostatni = array_pop($tablica);
                echo "Usunięto: $ostatni";
                wypisz_tablice($tablica);
                $sukces = true;
            }
            break;

        case 'insert':
            if (!isset($czesci[1]) || !isset($czesci[2])) {
                echo "Brak argumentu dla: insert";
            } else {
                array_splice($tablica, (int) $czesci[1], 0, $czesci[2]);
                wypisz_tablice($tablica);
                $sukces = true;
            }
            break;

        case 'delete':
            if (!isset($czesci[1])) {
                echo "Brak argumentu dla: delete";
            } else {
                array_splice($tablica, (int) $czesci[1], 1);
                wypisz_tablice($tablica);
                $sukces = true;
            }
            break;

        case 'sort':
            sort($tablica);
            wypisz_tablice($tablica);
            $sukces = true;
            break;

        case 'rsort':
            rsort($tablica);
            wypisz_tablice($tablica);
            $sukces = true;
            break;

        case 'unique':
            $tablica = array_values(array_unique($tablica));
            wypisz_tablice($tablica);
            $sukces = true;
            break;

        case 'reverse':
            $tablica = array_reverse($tablica);
            wypisz_tablice($tablica);
            $sukces = true;
            break;

        case 'filter':
            if (!isset($czesci[1]) || !isset($czesci[2])) {
                echo "Brak argumentu dla: filter";
            } else {
                $op = $czesci[1];
                $val = $czesci[2];
                $tablica = array_values(array_filter($tablica, function ($item) use ($op, $val) {
                    switch ($op) {
                        case '>': return $item > $val;
                        case '<': return $item < $val;
                        case '>=': return $item >= $val;
                        case '<=': return $item <= $val;
                        case '==': return $item == $val;
                        default: return true;
                    }
                }));
                wypisz_tablice($tablica);
                $sukces = true;
            }
            break;

        case 'chunk':
            if (!isset($czesci[1])) {
                echo "Brak argumentu dla: chunk";
            } else {
                $chunks = array_chunk($tablica, (int) $czesci[1]);
                foreach ($chunks as $idx => $ch) {
                    echo "Chunk " . ($idx + 1) . ": [" . implode(", ", $ch) . "]";
                }
                $sukces = true;
            }
            break;

        case 'slice':
            if (!isset($czesci[1]) || !isset($czesci[2])) {
                echo "Brak argumentu dla: slice";
            } else {
                $fragment = array_slice($tablica, (int) $czesci[1], (int) $czesci[2]);
                wypisz_tablice($fragment);
                $sukces = true;
            }
            break;

        case 'stats':
            if (empty($tablica)) {
                echo "Tablica jest pusta";
            } else {
                $suma = 0;
                $min = $tablica[0];
                $max = $tablica[0];
                foreach ($tablica as $v) {
                    $suma += (float) $v;
                    if ($v < $min) {
                        $min = $v;
                    }
                    if ($v > $max) {
                        $max = $v;
                    }
                }
                $srednia = $suma / count($tablica);
                echo "Suma: $suma | Średnia: $srednia | Min: $min | Max: $max";
                $sukces = true;
            }
            break;

        case 'show':
            wypisz_tablice($tablica);
            $sukces = true;
            break;

        case 'reset':
            $tablica = [];
            echo "Tablica wyczyszczona";
            $sukces = true;
            break;

        case 'save':
            echo json_encode(["dane" => $tablica]) . "";
            $sukces = true;
            break;

        case 'history':
            foreach ($historia as $idx => $cmd) {
                echo ($idx + 1) . ": $cmd";
            }
            $sukces = true;
            break;

        case 'help':
            echo "Dostępne: push, pop, insert, delete, sort, rsort, filter, unique, reverse, chunk, slice, stats, show, reset, save, history, help, exit";
            $sukces = true;
            break;

        case 'exit':
            echo "Do widzenia!";
            exit;

        default:
            echo "Nieznane polecenie: $polecenie";
    }

    if ($sukces) {
        $historia[] = $linia;
        $historia = array_slice($historia, -10);
    }
}
