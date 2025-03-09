<?php
$serverName = "LIVESDIGITALCLO"; // O la IP del servidor
$database = "DailyLedgerDB";
$username = "sa";
$password = "1234";

try {
    $conn = new PDO("sqlsrv:server=$serverName;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
