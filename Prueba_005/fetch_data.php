<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "db_connection.php";

// Obtener parametros
$tenant = isset($_GET['tenant']) ? preg_replace('/[^A-Za-z0-9_]/', '', $_GET['tenant']) : null;
$empresa = isset($_GET['empresa']) ? trim($_GET['empresa']) : null;

if (!$tenant || !$empresa) {
    echo json_encode(["error" => "Pametros faltantes"]);
    exit;
}

// Datos de tabla de tenants
$tableName = "{$tenant}_OPE_DocumentBASE";

// Prevencion de errores
ob_clean();
ob_start();

try {
    $stmt = $conn->prepare("
        SELECT 
            CAST(FacturaNUM AS VARCHAR(255)) AS FacturaNUM,
            CAST(FacturaDATE AS DATE) AS FacturaDATE,
            CAST(FacturaNETO AS NUMERIC(12,4)) AS FacturaNETO,
            CAST(FacturaIVA AS NUMERIC(12,4)) AS FacturaIVA,
            CAST(FacturaBRUTO AS NUMERIC(12,4)) AS FacturaBRUTO,
            CAST(FacturaTERMINOPAGO AS VARCHAR(255)) AS FacturaTERMINOPAGO,
            CAST(RutaDoc AS VARCHAR(MAX)) AS RutaDoc
        FROM $tableName
        WHERE CAST(Empresa AS VARCHAR(255)) = :empresa
ORDER BY FacturaDATE DESC
    ");
    $stmt->bindParam(':empresa', $empresa, PDO::PARAM_STR);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

ob_end_flush();
?>