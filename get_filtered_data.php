<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "walmart_sales");

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

// Build WHERE clause
$where = [];
if (!empty($input['startDate'])) {
    $where[] = "date >= '{$input['startDate']}'";
}
if (!empty($input['endDate'])) {
    $where[] = "date <= '{$input['endDate']}'";
}
if (!empty($input['category'])) {
    $where[] = "category = '{$input['category']}'";
}
if (!empty($input['region'])) {
    $where[] = "city = '{$input['region']}'";
}
if (!empty($input['salesRange'])) {
    switch($input['salesRange']) {
        case '0-100':
            $where[] = "sales BETWEEN 0 AND 100";
            break;
        case '100-500':
            $where[] = "sales BETWEEN 100 AND 500";
            break;
        case '500-1000':
            $where[] = "sales BETWEEN 500 AND 1000";
            break;
        case '1000-5000':
            $where[] = "sales BETWEEN 1000 AND 5000";
            break;
        case '5000+':
            $where[] = "sales >= 5000";
            break;
    }
}

$whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Get KPI data
$kpi_sql = "SELECT 
    COUNT(*) as totalRecords, 
    COALESCE(AVG(sales), 0) as avgSales, 
    COALESCE(MAX(sales), 0) as maxSales,
    COALESCE(MIN(sales), 0) as minSales,
    COALESCE(SUM(sales), 0) as totalSales
    FROM walmart_sales $whereClause";

$kpi_result = $conn->query($kpi_sql);
$kpi_data = $kpi_result->fetch_assoc();

// Get time series data
$time_sql = "SELECT date, SUM(sales) as daily_sales 
FROM walmart_sales $whereClause
GROUP BY date 
ORDER BY date ASC 
LIMIT 50";

$time_result = $conn->query($time_sql);
$time_labels = [];
$time_values = [];

while($row = $time_result->fetch_assoc()) {
    $time_labels[] = $row['date'];
    $time_values[] = $row['daily_sales'];
}

// Get category data
$category_sql = "SELECT category, SUM(sales) as total_sales, AVG(sales) as avg_sales, COUNT(*) as transaction_count
FROM walmart_sales $whereClause
GROUP BY category 
ORDER BY total_sales DESC 
LIMIT 10";

$category_result = $conn->query($category_sql);
$category_labels = [];
$category_total = [];
$category_avg = [];
$category_count = [];

while($row = $category_result->fetch_assoc()) {
    $category_labels[] = $row['category'];
    $category_total[] = $row['total_sales'];
    $category_avg[] = $row['avg_sales'];
    $category_count[] = $row['transaction_count'];
}

// Get region data
$region_sql = "SELECT city as region, AVG(sales) as avg_sales, SUM(sales) as total_sales, COUNT(*) as transaction_count
FROM walmart_sales $whereClause
GROUP BY city 
ORDER BY avg_sales DESC 
LIMIT 10";

$region_result = $conn->query($region_sql);
$region_labels = [];
$region_avg = [];
$region_total = [];
$region_count = [];

while($row = $region_result->fetch_assoc()) {
    $region_labels[] = $row['region'];
    $region_avg[] = $row['avg_sales'];
    $region_total[] = $row['total_sales'];
    $region_count[] = $row['transaction_count'];
}

// Return JSON response
echo json_encode([
    'kpi' => $kpi_data,
    'time' => [
        'labels' => $time_labels,
        'values' => $time_values
    ],
    'category' => [
        'labels' => $category_labels,
        'total' => $category_total,
        'avg' => $category_avg,
        'count' => $category_count
    ],
    'region' => [
        'labels' => $region_labels,
        'avg' => $region_avg,
        'total' => $region_total,
        'count' => $region_count
    ]
]);

$conn->close();
?>