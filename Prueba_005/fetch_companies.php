<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "db_connection.php";

// Get tenant
$tenant = isset($_GET['tenant']) ? preg_replace('/[^A-Za-z0-9_]/', '', $_GET['tenant']) : null;
//$tenant = "DL001_SE001_OR001_00006"; // Temporal para pruebas

if (!$tenant) {
    echo json_encode(["error" => "No se encuentran tenants"]);
    exit;
}

// Construit tabla con los tenant
$tableName = "{$tenant}_OPE_DocumentBASE";

// Validar si tabla existe
$tableCheckQuery = "SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = :tableName";
$stmt = $conn->prepare($tableCheckQuery);
$stmt->bindParam(':tableName', $tableName, PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    echo json_encode(["error" => "Tabla no existe"]);
    exit;
}

// Query para buscar empresas
try {
    $stmt = $conn->prepare("SELECT DISTINCT CAST(Empresa AS VARCHAR(255)) AS Empresa FROM $tableName ORDER BY Empresa");
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode($results, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>