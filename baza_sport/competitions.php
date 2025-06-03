<?php
require_once __DIR__ . '/includes/config.php';

$success = '';
$error = '';

try {
    $dyscypliny = $conn->query("SELECT * FROM Dyscypliny ORDER BY nazwa")->fetchAll();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_competition'])) {
            $nazwa = htmlspecialchars($_POST['nazwa']);
            $dyscyplina_id = $_POST['dyscyplina_id'] ? (int)$_POST['dyscyplina_id'] : null;
            $data_rozpoczecia = $_POST['data_rozpoczecia'];
            $data_zakonczenia = !empty($_POST['data_zakonczenia']) ? $_POST['data_zakonczenia'] : null;
            $miejsce = htmlspecialchars($_POST['miejsce'] ?? '');
            $organizator = htmlspecialchars($_POST['organizator'] ?? '');

            try {
                $stmt = $conn->prepare("INSERT INTO Zawody 
                                      (nazwa, dyscyplina_id, data_rozpoczecia, data_zakonczenia, miejsce, organizator) 
                                      VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nazwa, $dyscyplina_id, $data_rozpoczecia, $data_zakonczenia, $miejsce, $organizator]);
                $success = "Dodano nowe zawody: $nazwa";
            } catch (PDOException $e) {
                $error = "Błąd przy dodawaniu zawodów: " . $e->getMessage();
            }
        }

        if (isset($_POST['delete_competition'])) {
            $zawody_id = (int)$_POST['zawody_id'];
            
            try {
                $conn->beginTransaction();
                
                $conn->prepare("DELETE FROM Wyniki WHERE zawody_id = ?")->execute([$zawody_id]);
                
                $stmt = $conn->prepare("DELETE FROM Zawody WHERE zawody_id = ?");
                $stmt->execute([$zawody_id]);
                
                if ($stmt->rowCount() > 0) {
                    $conn->commit();
                    $success = "Usunięto zawody o ID: $zawody_id";
                    header("Refresh:2; url=".$_SERVER['PHP_SELF']);
                } else {
                    $conn->rollBack();
                    $error = "Nie znaleziono zawodów o podanym ID";
                }
            } catch (PDOException $e) {
                $conn->rollBack();
                $error = "Błąd przy usuwaniu zawodów: " . $e->getMessage();
            }
        }
    }

    $zawody = $conn->query("SELECT z.*, d.nazwa as dyscyplina_nazwa 
                           FROM Zawody z
                           LEFT JOIN Dyscypliny d ON z.dyscyplina_id = d.dyscyplina_id
                           ORDER BY z.data_rozpoczecia DESC")->fetchAll();

} catch (PDOException $e) {
    die("Błąd przy pobieraniu danych: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zawody sportowe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h1>Lista Zawodów</h1>
        
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
                    <th>Dyscyplina</th>
                    <th>Data</th>
                    <th>Miejsce</th>
                    <th>Organizator</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($zawody as $zawod): ?>
                <tr>
                    <td><?= $zawod['zawody_id'] ?></td>
                    <td><?= htmlspecialchars($zawod['nazwa']) ?></td>
                    <td><?= htmlspecialchars($zawod['dyscyplina_nazwa'] ?? 'Brak danych') ?></td>
                    <td>
                        <?= date('d.m.Y', strtotime($zawod['data_rozpoczecia'])) ?>
                        <?php if ($zawod['data_zakonczenia']): ?>
                            - <?= date('d.m.Y', strtotime($zawod['data_zakonczenia'])) ?>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($zawod['miejsce']) ?></td>
                    <td><?= htmlspecialchars($zawod['organizator']) ?></td>
                    <td class="action-cell">
                        <form method="POST" class="delete-form" onsubmit="return confirm('Czy na pewno chcesz usunąć te zawody? Wszystkie powiązane wyniki również zostaną usunięte.');">
                            <input type="hidden" name="zawody_id" value="<?= $zawod['zawody_id'] ?>">
                            <button type="submit" name="delete_competition" class="btn btn-delete">Usuń</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if (empty($zawody)): ?>
            <p class="no-data">Brak zawodów w bazie danych</p>
        <?php endif; ?>

        <div class="form-container">
            <h2>Dodaj nowe zawody</h2>
            
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nazwa">Nazwa zawodów:*</label>
                        <input type="text" id="nazwa" name="nazwa" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="dyscyplina_id">Dyscyplina:</label>
                        <select id="dyscyplina_id" name="dyscyplina_id">
                            <option value="">-- Wybierz dyscyplinę --</option>
                            <?php foreach ($dyscypliny as $dyscyplina): ?>
                                <option value="<?= $dyscyplina['dyscyplina_id'] ?>">
                                    <?= htmlspecialchars($dyscyplina['nazwa']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Data:</label>
                        <div class="date-inputs">
                            <input type="date" id="data_rozpoczecia" name="data_rozpoczecia" required>
                            <span>do</span>
                            <input type="date" id="data_zakonczenia" name="data_zakonczenia">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="miejsce">Miejsce:</label>
                        <input type="text" id="miejsce" name="miejsce">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="organizator">Organizator:</label>
                    <input type="text" id="organizator" name="organizator">
                </div>
                
                <button type="submit" name="add_competition" class="btn btn-add">Dodaj zawody</button>
            </form>
        </div>
    </div>
</body>
</html>