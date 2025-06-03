<?php
require_once __DIR__ . '/includes/config.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_club'])) {
    $nazwa = htmlspecialchars($_POST['nazwa']);
    $kraj = htmlspecialchars($_POST['kraj']);
    $data_zalozenia = $_POST['data_zalozenia'];
    $adres_siedziby = htmlspecialchars($_POST['adres_siedziby']);
    
    try {
        $stmt = $conn->prepare("INSERT INTO Kluby 
                              (nazwa, kraj, data_zalozenia, adres_siedziby) 
                              VALUES (?, ?, ?, ?)");
        $stmt->execute([$nazwa, $kraj, $data_zalozenia, $adres_siedziby]);
        $success = "Dodano klub: $nazwa";
    } catch (PDOException $e) {
        $error = "Błąd przy dodawaniu klubu: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_club'])) {
    $klub_id = (int)$_POST['klub_id'];
    
    try {
        $conn->beginTransaction();
        
        $conn->prepare("DELETE FROM Sportowcy WHERE klub_id = ?")->execute([$klub_id]);
        
        $stmt = $conn->prepare("DELETE FROM Kluby WHERE klub_id = ?");
        $stmt->execute([$klub_id]);
        
        if ($stmt->rowCount() > 0) {
            $conn->commit();
            $success = "Usunięto klub o ID: $klub_id";
            header("Refresh:2; url=".$_SERVER['PHP_SELF']);
        } else {
            $conn->rollBack();
            $error = "Nie znaleziono klubu o podanym ID";
        }
    } catch (PDOException $e) {
        $conn->rollBack();
        $error = "Błąd przy usuwaniu klubu: " . $e->getMessage();
    }
}

try {
    $stmt = $conn->query("SELECT * FROM Kluby ORDER BY klub_id");
    $clubs = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Błąd przy pobieraniu klubów: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kluby sportowe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h1>Lista klubów sportowych</h1>
        
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
                    <th>Kraj</th>
                    <th>Data założenia</th>
                    <th>Adres siedziby</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clubs as $club): ?>
                <tr>
                    <td><?= htmlspecialchars($club['klub_id']) ?></td>
                    <td><?= htmlspecialchars($club['nazwa']) ?></td>
                    <td><?= htmlspecialchars($club['kraj'] ?? 'Brak danych') ?></td>
                    <td><?= $club['data_zalozenia'] ?? 'Nieznana' ?></td>
                    <td><?= htmlspecialchars($club['adres_siedziby'] ?? 'Brak danych') ?></td>
                    <td class="action-cell">
                        <form method="POST" class="delete-form" onsubmit="return confirm('Czy na pewno chcesz usunąć ten klub? Wszyscy powiązani sportowcy również zostaną usunięci!');">
                            <input type="hidden" name="klub_id" value="<?= $club['klub_id'] ?>">
                            <button type="submit" name="delete_club" class="btn btn-delete">Usuń</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if (empty($clubs)): ?>
            <p class="no-data">Brak klubów w bazie danych</p>
        <?php endif; ?>

        <div class="form-container">
            <h2>Dodaj nowy klub</h2>
            
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nazwa">Nazwa:*</label>
                        <input type="text" id="nazwa" name="nazwa" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="kraj">Kraj:*</label>
                        <input type="text" id="kraj" name="kraj" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="data_zalozenia">Data założenia:</label>
                        <input type="date" id="data_zalozenia" name="data_zalozenia">
                    </div>
                    
                    <div class="form-group">
                        <label for="adres_siedziby">Adres siedziby:</label>
                        <input type="text" id="adres_siedziby" name="adres_siedziby">
                    </div>
                </div>
                
                <button type="submit" name="add_club" class="btn btn-add">Dodaj klub</button>
            </form>
        </div>
    </div>
</body>
</html>