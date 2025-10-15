<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Convertitore di Temperatura</title>
</head>
<body>
    <h2>Convertitore di Temperatura</h2>

    <form method="post" action="">
        <label>Temperatura:</label>
        <input type="text" name="temp" required>

        <br><br>

        <label>Da:</label>
        <select name="from">
            <option value="C">Celsius</option>
            <option value="F">Fahrenheit</option>
            <option value="K">Kelvin</option>
        </select>

        <label>A:</label>
        <select name="to">
            <option value="C">Celsius</option>
            <option value="F">Fahrenheit</option>
            <option value="K">Kelvin</option>
        </select>

        <br><br>
        <button type="submit" name="converti">Converti</button>
    </form>

    <br><br>

<?php
    //array assoc. e formule conversione
    $conversioni = [
        'C' => [
            'F' => fn($t) => ($t * 9/5) + 32,
            'K' => fn($t) => $t + 273.15
        ],
        'F' => [
            'C' => fn($t) => ($t - 32) * 5/9,
            'K' => fn($t) => ($t - 32) * 5/9 + 273.15
        ],
        'K' => [
            'C' => fn($t) => $t - 273.15,
            'F' => fn($t) => ($t - 273.15) * 9/5 + 32
        ]
    ];

    // se converti è pieno fa controllo su input altrimenti è vuoto
    echo isset($_POST['converti'])
        ? (
            (is_numeric(str_replace(',', '.', $_POST['temp'])))
                ? (
                    ($_POST['from'] == $_POST['to'])
                        ? "Nessuna conversione"
                        : (
                            isset($conversioni[$_POST['from']][$_POST['to']])
                                ? "Risultato: " . $_POST['temp'] . "° " . $_POST['from'] . " = " .
                                $conversioni[$_POST['from']][$_POST['to']](str_replace(',', '.', $_POST['temp'])) . "° " . $_POST['to']
                                : "Combinazione non valida."
                        )
                )
                : "Inserisci un numero valido."
        )
        : "";

    // gestione log
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'IP sconosciuto';
    $porta = $_SERVER['REMOTE_PORT'] ?? 'Porta sconosciuta';
    $dataOra = date('Y-m-d H:i:s');

    // temperatura + unità
    $tempInserita = isset($_POST['temp']) ? $_POST['temp'] : 'N/A';
    $unita = isset($_POST['from']) ? $_POST['from'] : '';

    // log dettagliato
    $log = "Accesso il $dataOra da IP: $ip, Porta: $porta, Temperatura inserita: {$tempInserita}°{$unita}\n\n";



    $handler = fopen('data/log.txt', 'a');
    fwrite($handler, $log);
    fclose($handler);
?>


</body>
</html>
