<?php
require_once __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_trainer'])) {
        $imie = htmlspecialchars($_POST['imie']);
        $nazwisko = htmlspecialchars($_POST['nazwisko']);
        $dyscyplina_id = $_POST['dyscyplina_id'] ?? null;
        $data_rozpoczecia = $_POST['data_rozpoczecia'] ?? null;

        try {
            $stmt = $conn->prepare("INSERT INTO Trenerzy (imie, nazwisko, dyscyplina_id, data_rozpoczecia_kariery) VALUES (?, ?, ?, ?)");
            $stmt->execute([$imie, $nazwisko, $dyscyplina_id, $data_rozpoczecia]);
            $success = "Dodano nowego trenera: $imie $nazwisko";
        } catch (PDOException $e) {
            $error = "Błąd przy dodawaniu trenera: " . $e->getMessage();
        }
    }
    
    if (isset($_POST['delete_trainer'])) {
        $trener_id = (int)$_POST['trener_id'];
        
        try {
            $stmt = $conn->prepare("DELETE FROM Trenerzy WHERE trener_id = ?");
            $stmt->execute([$trener_id]);
            
            if ($stmt->rowCount() > 0) {
                $success = "Usunięto trenera o ID: $trener_id";
            } else {
                $error = "Nie znaleziono trenera o podanym ID";
            }
        } catch (PDOException $e) {
            $error = "Błąd przy usuwaniu trenera: " . $e->getMessage();
        }
    }
}

try {
    $stmt = $conn->query("SELECT t.*, d.nazwa as dyscyplina_nazwa FROM Trenerzy t LEFT JOIN Dyscypliny d ON t.dyscyplina_id = d.dyscyplina_id ORDER BY t.trener_id");
    $trenerzy = $stmt->fetchAll();
    
    // Pobierz dyscypliny dla selecta
    $stmt = $conn->query("SELECT * FROM Dyscypliny ORDER BY nazwa");
    $dyscypliny = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Błąd przy pobieraniu danych: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trenerzy</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h1>Lista Trenerów</h1>
        
        <?php if (isset($success)): ?>
            <div class="alert success"><?= $success ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>

        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>Dyscyplina</th>
                    <th>Data rozpoczęcia</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trenerzy as $trener): ?>
                <tr>
                    <td><?= htmlspecialchars($trener['trener_id']) ?></td>
                    <td><?= htmlspecialchars($trener['imie']) ?></td>
                    <td><?= htmlspecialchars($trener['nazwisko']) ?></td>
                    <td><?= htmlspecialchars($trener['dyscyplina_nazwa'] ?? 'Brak danych') ?></td>
                    <td><?= htmlspecialchars($trener['data_rozpoczecia_kariery'] ?? 'Nieznana') ?></td>
                    <td class="action-cell">
                        <form method="POST" class="delete-form" onsubmit="return confirm('Czy na pewno chcesz usunąć tego trenera?');">
                            <input type="hidden" name="trener_id" value="<?= $trener['trener_id'] ?>">
                            <button type="submit" name="delete_trainer" class="btn btn-delete">Usuń</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if (empty($trenerzy)): ?>
            <p class="no-data">Brak trenerów w bazie danych</p>
        <?php endif; ?>
        
        <div class="form-container">
            <h2>Dodaj nowego trenera</h2>
            
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="imie">Imię:*</label>
                        <input type="text" id="imie" name="imie" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="nazwisko">Nazwisko:*</label>
                        <input type="text" id="nazwisko" name="nazwisko" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="dyscyplina_id">Specjalizacja:</label>
                        <select id="dyscyplina_id" name="dyscyplina_id">
                            <option value="">-- Wybierz dyscyplinę --</option>
                            <?php foreach ($dyscypliny as $dyscyplina): ?>
                                <option value="<?= $dyscyplina['dyscyplina_id'] ?>">
                                    <?= htmlspecialchars($dyscyplina['nazwa']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="data_rozpoczecia">Data rozpoczęcia kariery:</label>
                        <input type="date" id="data_rozpoczecia" name="data_rozpoczecia">
                    </div>
                </div>
                
                <button type="submit" name="add_trainer" class="btn btn-add">Dodaj trenera</button>
            </form>
        </div>
    </div>
</body>
</html>