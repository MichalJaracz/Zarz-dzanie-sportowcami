<?php
// Włącz wyświetlanie błędów (tylko na etapie rozwoju!)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Dane do połączenia z MySQL
$host = "localhost";      // Adres serwera
$user = "root";           // Nazwa użytkownika
$password = "";           // Hasło (puste domyślnie w XAMPP)
$database = "proj";   // Nazwa bazy danych

// Nawiązanie połączenia
try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$database", 
        $user, 
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Rzucaj wyjątki przy błędach
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Domyślny format wyników
        ]
    );
    
    // Opcjonalnie: sprawdź czy połączenie działa
    // echo "Połączenie z bazą danych udane!";
    
} catch (PDOException $e) {
    // Jeśli błąd, wyświetl komunikat (tylko na lokalnym serwerze!)
    die("<b>Błąd połączenia z bazą danych:</b> " . $e->getMessage());
}
?>