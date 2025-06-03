<?php
require_once __DIR__ . '/includes/config.php';

// Przykładowe kwerendy
$raporty = [
    [
        'tytul' => 'Top 5 sportowców z najwięcej medalami',
        'sql' => "SELECT s.imie, s.nazwisko, COUNT(w.wynik_id) as liczba_medali
                 FROM Wyniki w
                 JOIN Sportowcy s ON w.sportowiec_id = s.sportowiec_id
                 WHERE w.pozycja <= 3
                 GROUP BY s.sportowiec_id
                 ORDER BY liczba_medali DESC
                 LIMIT 5"
    ],
    [
        'tytul' => 'Średni wiek sportowców w klubach',
        'sql' => "SELECT k.nazwa as klub, 
                        FLOOR(AVG(TIMESTAMPDIFF(YEAR, s.data_urodzenia, CURDATE()))) as sredni_wiek
                 FROM Sportowcy s
                 JOIN Kluby k ON s.klub_id = k.klub_id
                 GROUP BY k.klub_id
                 ORDER BY sredni_wiek DESC"
    ],
    [
        'tytul' => 'Najbliższe zawody',
        'sql' => "SELECT z.nazwa, d.nazwa as dyscyplina, 
                        z.data_rozpoczecia, z.miejsce
                 FROM Zawody z
                 JOIN Dyscypliny d ON z.dyscyplina_id = d.dyscyplina_id
                 WHERE z.data_rozpoczecia >= CURDATE()
                 ORDER BY z.data_rozpoczecia
                 LIMIT 5"
    ]
];

// Wybrany raport
$wybrany_raport = $_GET['raport'] ?? 0;
$wyniki = [];
$naglowki = [];

if (isset($raporty[$wybrany_raport])) {
    try {
        $stmt = $conn->query($raporty[$wybrany_raport]['sql']);
        $wyniki = $stmt->fetchAll();
        
        // Pobierz nagłówki kolumn
        if (!empty($wyniki)) {
            $naglowki = array_keys($wyniki[0]);
        }
    } catch (PDOException $e) {
        $error = "Błąd wykonania zapytania: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raporty</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .raporty-container {
            display: flex;
            gap: 20px;
        }
        .raporty-menu {
            width: 250px;
        }
        .raporty-content {
            flex: 1;
        }
        .raport-link {
            display: block;
            padding: 10px;
            margin: 5px 0;
            background: #f0f0f0;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }
        .raport-link:hover, .raport-link.active {
            background: #3498db;
            color: white;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h1>Raporty i statystyki</h1>
        
        <div class="raporty-container">
            <div class="raporty-menu">
                <h3>Dostępne raporty</h3>
                <?php foreach ($raporty as $index => $raport): ?>
                    <a href="?raport=<?= $index ?>" 
                       class="raport-link <?= $wybrany_raport == $index ? 'active' : '' ?>">
                        <?= htmlspecialchars($raport['tytul']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
            
            <div class="raporty-content">
                <?php if (isset($error)): ?>
                    <div class="alert error"><?= $error ?></div>
                <?php elseif (!empty($wyniki)): ?>
                    <h2><?= htmlspecialchars($raporty[$wybrany_raport]['tytul']) ?></h2>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <?php foreach ($naglowki as $naglowek): ?>
                                    <th><?= ucfirst(str_replace('_', ' ', $naglowek)) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($wyniki as $wiersz): ?>
                                <tr>
                                    <?php foreach ($wiersz as $wartosc): ?>
                                        <td><?= htmlspecialchars($wartosc) ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Wybierz raport z menu po lewej stronie</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>