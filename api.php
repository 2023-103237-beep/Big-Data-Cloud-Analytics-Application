<?php
include 'db.php';
header('Content-Type: application/json');

$branch = $_GET['branch'] ?? 'all';

/* BUILD QUERY */
if ($branch == "all") {
    $sql = "SELECT 
                SUM(Total) AS total_sales,
                COUNT(*) AS total_orders,
                AVG(Rating) AS avg_rating
            FROM walmart";
} else {
    $sql = "SELECT 
                SUM(Total) AS total_sales,
                COUNT(*) AS total_orders,
                AVG(Rating) AS avg_rating
            FROM walmart
            WHERE Branch='$branch'";
}

$result = $conn->query($sql);
$data = $result->fetch_assoc();

/* FAKE CHART DATA (you can replace later with real SQL) */
$data["chart"] = [
    "branches" => ["A","B","C"],
    "sales" => [12000, 15000, 9000],
    "months" => ["Jan","Feb","Mar"],
    "trend" => [5000,7000,9000],
    "categories" => ["Food","Electronics","Clothing"],
    "category_values" => [40,35,25]
];

echo json_encode($data);
?>