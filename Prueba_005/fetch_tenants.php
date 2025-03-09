<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "db_connection.php";

try {
    $query = "SELECT DISTINCT 
                 LEFT(TABLE_NAME, CHARINDEX('_OPE_DocumentBASE', TABLE_NAME) - 1) AS tenant_code 
              FROM INFORMATION_SCHEMA.TABLES 
              WHERE TABLE_NAME LIKE '%_OPE_DocumentBASE'";

    $stmt = $conn->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Debug: Print query results
    if (empty($results)) {
        echo json_encode(["error" => "No hay tenants"]);
    } else {
        echo json_encode($results, JSON_UNESCAPED_UNICODE);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>