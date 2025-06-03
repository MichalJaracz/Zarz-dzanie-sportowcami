<?php
require_once __DIR__ . '/includes/config.php';

// Pobierz listę klubów i trenerów do selectów
$kluby = $conn->query("SELECT klub_id, nazwa FROM Kluby ORDER BY nazwa")->fetchAll();
$trenerzy = $conn->query("SELECT trener_id, imie, nazwisko FROM Trenerzy ORDER BY nazwisko")->fetchAll();

// Obsługa formularza dodawania sportowca
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_athlete'])) {
    $imie = htmlspecialchars($_POST['imie']);
    $nazwisko = htmlspecialchars($_POST['nazwisko']);
    $data_urodzenia = $_POST['data_urodzenia'];
    $plec = $_POST['plec'];
    $kraj = htmlspecialchars($_POST['kraj']);
    $klub_id = intval($_POST['klub_id']);
    $trener_id = intval($_POST['trener_id']);
    
    try {
        $stmt = $conn->prepare("INSERT INTO Sportowcy 
                              (imie, nazwisko, data_urodzenia, plec, kraj_pochodzenia, klub_id, trener_id) 
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$imie, $nazwisko, $data_urodzenia, $plec, $kraj, $klub_id, $trener_id]);
        $success = "Dodano sportowca: $imie $nazwisko";
    } catch (PDOException $e) {
        $error = "Błąd: " . $e->getMessage();
    }
}

$total_athletes = $conn->query("SELECT COUNT(*) as total FROM Sportowcy")->fetch()['total'];
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>System Sportowy</title>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <h1>Witaj w systemie zarządzania sportowcami</h1>
        
        <div class="stat">
            <h3>Statystyki systemu</h3>
            <p>Liczba sportowców w bazie: <b><?= $total_athletes ?></b></p>
        </div>
    
        <div class="form-container">
            <h2>Dodaj nowego sportowca</h2>
            
            <?php if (isset($success)): ?>
                <div class="alert success"><?= $success ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert error"><?= $error ?></div>
            <?php endif; ?>
            
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
                        <label for="data_urodzenia">Data urodzenia:*</label>
                        <input type="date" id="data_urodzenia" name="data_urodzenia" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="plec">Płeć:*</label>
                        <select id="plec" name="plec" required>
                            <option value="">-- Wybierz --</option>
                            <option value="M">Mężczyzna</option>
                            <option value="K">Kobieta</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="kraj">Kraj pochodzenia:</label>
                    <input type="text" id="kraj" name="kraj">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="klub_id">Klub:</label>
                        <select id="klub_id" name="klub_id">
                            <option value="">-- Brak klubu --</option>
                            <?php foreach ($kluby as $klub): ?>
                                <option value="<?= $klub['klub_id'] ?>">
                                    <?= htmlspecialchars($klub['nazwa']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="trener_id">Trener:</label>
                        <select id="trener_id" name="trener_id">
                            <option value="">-- Brak trenera --</option>
                            <?php foreach ($trenerzy as $trener): ?>
                                <option value="<?= $trener['trener_id'] ?>">
                                    <?= htmlspecialchars($trener['imie'].' '.$trener['nazwisko']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <button type="submit" name="add_athlete" class="btn">Dodaj sportowca</button>
            </form>
        </div>
    </div>
</body>
</html>