<?php
require_once __DIR__ . '/includes/config.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_discipline'])) {
        $nazwa = htmlspecialchars($_POST['nazwa']);
        $typ = $_POST['typ'];
        $opis = htmlspecialchars($_POST['opis'] ?? '');

        try {
            $stmt = $conn->prepare("INSERT INTO Dyscypliny (nazwa, typ, opis) VALUES (?, ?, ?)");
            $stmt->execute([$nazwa, $typ, $opis]);
            $success = "Dodano nową dyscyplinę: $nazwa";
        } catch (PDOException $e) {
            $error = "Błąd przy dodawaniu dyscypliny: " . $e->getMessage();
        }
    }
    
    if (isset($_POST['delete_discipline'])) {
        $dyscyplina_id = (int)$_POST['dyscyplina_id'];
        
        try {
            $conn->beginTransaction();
            
            $conn->prepare("UPDATE Trenerzy SET dyscyplina_id = NULL WHERE dyscyplina_id = ?")->execute([$dyscyplina_id]);
            
            $stmt = $conn->prepare("DELETE FROM Dyscypliny WHERE dyscyplina_id = ?");
            $stmt->execute([$dyscyplina_id]);
            
            if ($stmt->rowCount() > 0) {
                $conn->commit();
                $success = "Usunięto dyscyplinę o ID: $dyscyplina_id";
                header("Refresh:2; url=".$_SERVER['PHP_SELF']);
            } else {
                $conn->rollBack();
                $error = "Nie znaleziono dyscypliny o podanym ID";
            }
        } catch (PDOException $e) {
            $conn->rollBack();
            $error = "Błąd przy usuwaniu dyscypliny: " . $e->getMessage();
        }
    }
}

try {
    $stmt = $conn->query("SELECT * FROM Dyscypliny ORDER BY dyscyplina_id");
    $disciplines = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Błąd przy pobieraniu dyscyplin: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dyscypliny sportowe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h1>Lista dyscyplin sportowych</h1>
        
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
                    <th>Nazwa</th>
                    <th>Typ</th>
                    <th>Opis</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($disciplines as $discipline): ?>
                <tr>
                    <td><?= htmlspecialchars($discipline['dyscyplina_id']) ?></td>
                    <td><?= htmlspecialchars($discipline['nazwa']) ?></td>
                    <td><?= htmlspecialchars($discipline['typ']) ?></td>
                    <td><?= htmlspecialchars($discipline['opis']) ?></td>
                    <td class="action-cell">
                        <form method="POST" class="delete-form" onsubmit="return confirm('Czy na pewno chcesz usunąć tę dyscyplinę? Wszystkie powiązania z trenerami zostaną usunięte.');">
                            <input type="hidden" name="dyscyplina_id" value="<?= $discipline['dyscyplina_id'] ?>">
                            <button type="submit" name="delete_discipline" class="btn btn-delete">Usuń</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if (empty($disciplines)): ?>
            <p class="no-data">Brak dyscyplin w bazie danych</p>
        <?php endif; ?>
        
        <div class="form-container">
            <h2>Dodaj nową dyscyplinę</h2>
            
            <form method="POST">
                <div class="form-group">
                    <label for="nazwa">Nazwa dyscypliny:*</label>
                    <input type="text" id="nazwa" name="nazwa" required>
                </div>
                
                <div class="form-group">
                    <label for="typ">Typ dyscypliny:*</label>
                    <select id="typ" name="typ" required>
                        <option value="">-- Wybierz typ --</option>
                        <option value="indywidualna">Indywidualna</option>
                        <option value="zespołowa">Zespołowa</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="opis">Opis (opcjonalnie):</label>
                    <textarea id="opis" name="opis" rows="3"></textarea>
                </div>
                
                <button type="submit" name="add_discipline" class="btn btn-add">Dodaj dyscyplinę</button>
            </form>
        </div>
    </div>
</body>
</html>