<?php
require_once __DIR__ . '/includes/config.php';

$success = '';
$error = '';

try {
    $sportowcy = $conn->query("SELECT sportowiec_id, imie, nazwisko FROM Sportowcy ORDER BY nazwisko")->fetchAll();
    
    $zawody = $conn->query("SELECT z.zawody_id, z.nazwa, d.nazwa as dyscyplina 
                           FROM Zawody z 
                           JOIN Dyscypliny d ON z.dyscyplina_id = d.dyscyplina_id
                           ORDER BY z.data_rozpoczecia DESC")->fetchAll();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_result'])) {
            $zawody_id = (int)$_POST['zawody_id'];
            $sportowiec_id = (int)$_POST['sportowiec_id'];
            $pozycja = !empty($_POST['pozycja']) ? (int)$_POST['pozycja'] : null;
            $wynik = htmlspecialchars($_POST['wynik'] ?? '');
            $notatki = htmlspecialchars($_POST['notatki'] ?? '');

            try {
                $stmt = $conn->prepare("INSERT INTO Wyniki (zawody_id, sportowiec_id, pozycja, wynik, notatki) 
                                       VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$zawody_id, $sportowiec_id, $pozycja, $wynik, $notatki]);
                $success = "Dodano nowy wynik zawodów";
                
            } catch (PDOException $e) {
                $error = "Błąd przy dodawaniu wyniku: " . $e->getMessage();
            }
        }
        
        if (isset($_POST['delete_result'])) {
            $wynik_id = (int)$_POST['wynik_id'];
            
            try {
                $stmt = $conn->prepare("DELETE FROM Wyniki WHERE wynik_id = ?");
                $stmt->execute([$wynik_id]);
                
                if ($stmt->rowCount() > 0) {
                    $success = "Usunięto wynik zawodów";
                    header("Refresh:2; url=".$_SERVER['PHP_SELF']);
                } else {
                    $error = "Nie znaleziono wyniku o podanym ID";
                }
            } catch (PDOException $e) {
                $error = "Błąd przy usuwaniu wyniku: " . $e->getMessage();
            }
        }
    }

    $sql = "SELECT w.*, 
                   s.imie as sportowiec_imie, s.nazwisko as sportowiec_nazwisko,
                   z.nazwa as zawody_nazwa, d.nazwa as dyscyplina_nazwa
            FROM Wyniki w
            JOIN Sportowcy s ON w.sportowiec_id = s.sportowiec_id
            JOIN Zawody z ON w.zawody_id = z.zawody_id
            JOIN Dyscypliny d ON z.dyscyplina_id = d.dyscyplina_id
            ORDER BY w.zawody_id, w.pozycja";
    $wyniki = $conn->query($sql)->fetchAll();

} catch (PDOException $e) {
    die("Błąd przy pobieraniu danych: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wyniki zawodów</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h1>Wyniki zawodów</h1>
        
        <?php if (!empty($success)): ?>
            <div class="alert success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Zawody</th>
                    <th>Dyscyplina</th>
                    <th>Sportowiec</th>
                    <th>Pozycja</th>
                    <th>Wynik</th>
                    <th>Notatki</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($wyniki as $wynik): ?>
                <tr>
                    <td><?= htmlspecialchars($wynik['zawody_nazwa']) ?></td>
                    <td><?= htmlspecialchars($wynik['dyscyplina_nazwa']) ?></td>
                    <td><?= htmlspecialchars($wynik['sportowiec_imie'].' '.$wynik['sportowiec_nazwisko']) ?></td>
                    <td><?= $wynik['pozycja'] ?: 'Nie określono' ?></td>
                    <td><?= htmlspecialchars($wynik['wynik']) ?></td>
                    <td><?= htmlspecialchars($wynik['notatki'] ?? 'Brak notatki') ?></td>
                    <td class="action-cell">
                        <form method="POST" class="delete-form" onsubmit="return confirm('Czy na pewno chcesz usunąć ten wynik?');">
                            <input type="hidden" name="wynik_id" value="<?= $wynik['wynik_id'] ?>">
                            <button type="submit" name="delete_result" class="btn btn-delete">Usuń</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if (empty($wyniki)): ?>
            <p class="no-data">Brak wyników w bazie danych</p>
        <?php endif; ?>
        
        <div class="form-container">
            <h2>Dodaj nowy wynik</h2>
            
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="zawody_id">Zawody:*</label>
                        <select id="zawody_id" name="zawody_id" required>
                            <option value="">-- Wybierz zawody --</option>
                            <?php foreach ($zawody as $zawod): ?>
                                <option value="<?= $zawod['zawody_id'] ?>">
                                    <?= htmlspecialchars($zawod['nazwa']) ?> (<?= htmlspecialchars($zawod['dyscyplina']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="sportowiec_id">Sportowiec:*</label>
                        <select id="sportowiec_id" name="sportowiec_id" required>
                            <option value="">-- Wybierz sportowca --</option>
                            <?php foreach ($sportowcy as $sportowiec): ?>
                                <option value="<?= $sportowiec['sportowiec_id'] ?>">
                                    <?= htmlspecialchars($sportowiec['imie'].' '.$sportowiec['nazwisko']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="pozycja">Pozycja:</label>
                        <input type="number" id="pozycja" name="pozycja" min="1">
                    </div>
                    
                    <div class="form-group">
                        <label for="wynik">Wynik:</label>
                        <input type="text" id="wynik" name="wynik">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="notatki">Notatki:</label>
                    <textarea id="notatki" name="notatki" rows="2"></textarea>
                </div>
                
                <button type="submit" name="add_result" class="btn btn-add">Dodaj wynik</button>
            </form>
        </div>
    </div>
</body>
</html>