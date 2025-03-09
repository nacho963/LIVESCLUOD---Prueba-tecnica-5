<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "conexion.php";

try {
    $stmt = $conn->query("SELECT * FROM DL001_SE001_OR001_00006_OPE_DocumentBASE");
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($resultados);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
