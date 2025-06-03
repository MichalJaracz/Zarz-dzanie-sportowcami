<?php
require_once __DIR__ . '/includes/config.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_athlete'])) {
    $sportowiec_id = (int)$_POST['sportowiec_id'];
    
    try {
        $conn->beginTransaction();
        
        $conn->prepare("DELETE FROM wyniki WHERE sportowiec_id = ?")->execute([$sportowiec_id]);
        
        $stmt = $conn->prepare("DELETE FROM Sportowcy WHERE sportowiec_id = ?");
        $stmt->execute([$sportowiec_id]);
        
        if ($stmt->rowCount() > 0) {
            $conn->commit();
            $success = "Usunięto sportowca o ID: $sportowiec_id";
            header("Refresh:2; url=".$_SERVER['PHP_SELF']);
        } else {
            $conn->rollBack();
            $error = "Nie znaleziono sportowca o podanym ID";
        }
    } catch (PDOException $e) {
        $conn->rollBack();
        $error = "Błąd przy usuwaniu sportowca: " . $e->getMessage();
    }
}

try {
    $sql = "SELECT s.*, 
                   k.nazwa as klub, 
                   t.imie as trener_imie, t.nazwisko as trener_nazwisko
            FROM Sportowcy s
            JOIN Kluby k ON k.klub_id = s.klub_id
            JOIN Trenerzy t ON t.trener_id = s.trener_id
            ORDER BY s.sportowiec_id";
    $sportowcy = $conn->query($sql)->fetchAll();
} catch (PDOException $e) {
    die("Błąd przy pobieraniu wyników: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Sportowców</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h1>Lista Sportowców</h1>
        
        <!-- Komunikaty -->
        <?php if (!empty($success)): ?>
            <div class="alert success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sportowiec</th>
                    <th>Data urodzenia</th>
                    <th>Płeć</th>
                    <th>Kraj pochodzenia</th>
                    <th>Klub</th>
                    <th>Trener</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sportowcy as $sportowiec): ?>
                <tr>
                    <td><?= htmlspecialchars($sportowiec['sportowiec_id']) ?></td>
                    <td><?= htmlspecialchars($sportowiec['imie'].' '.$sportowiec['nazwisko']) ?></td>
                    <td><?= htmlspecialchars($sportowiec['data_urodzenia']) ?></td>
                    <td><?= $sportowiec['plec'] ?: 'Nie określono' ?></td>
                    <td><?= htmlspecialchars($sportowiec['kraj_pochodzenia']) ?></td>
                    <td><?= htmlspecialchars($sportowiec['klub'] ?? 'Brak klubu') ?></td>
                    <td><?= htmlspecialchars($sportowiec['trener_imie'].' '.$sportowiec['trener_nazwisko'] ?? 'Brak trenera') ?></td>             
                    <td class="action-cell">
                        <form method="POST" class="delete-form" onsubmit="return confirm('Czy na pewno chcesz usunąć tego sportowca?');">
                            <input type="hidden" name="sportowiec_id" value="<?= $sportowiec['sportowiec_id'] ?>">
                            <button type="submit" name="delete_athlete" class="btn btn-delete">Usuń</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if (empty($sportowcy)): ?>
            <p class="no-data">Brak wyników w bazie danych</p>
        <?php endif; ?>
    </div>

</body>
</html>