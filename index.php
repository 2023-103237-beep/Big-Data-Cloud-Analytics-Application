<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Walmart Sales Dashboard </title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        h1 {
            text-align: center;
            color: #1a73e8;
            margin-bottom: 30px;
        }
        
        /* Filter Controls */
        .filter-panel {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        
        .filter-group label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #555;
            font-size: 14px;
        }
        
        .filter-group select,
        .filter-group input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: #1a73e8;
        }
        
        .filter-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #1a73e8;
            color: white;
        }
        
        .btn-primary:hover {
            background: #1557b0;
        }
        
        .btn-secondary {
            background: #f0f0f0;
            color: #666;
        }
        
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        
        .btn-success {
            background: #34a853;
            color: white;
        }
        
        .btn-success:hover {
            background: #2d8e47;
        }
        
        /* Active Filters Display */
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .filter-tag {
            background: #e8f0fe;
            color: #1a73e8;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .filter-tag button {
            background: none;
            border: none;
            color: #1a73e8;
            cursor: pointer;
            font-size: 16px;
            padding: 0 2px;
        }
        
        .filter-tag button:hover {
            color: #ea4335;
        }
        
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .kpi-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        
        .kpi-card:hover {
            transform: translateY(-5px);
        }
        
        .kpi-card h3 {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .kpi-card .value {
            font-size: 28px;
            font-weight: bold;
            color: #1a73e8;
        }
        
        .chart-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .chart-container h2 {
            color: #333;
            font-size: 20px;
        }
        
        .chart-controls {
            display: flex;
            gap: 10px;
        }
        
        .two-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .error {
            background: #fee;
            color: #c33;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }
        
        @media (max-width: 768px) {
            .two-columns {
                grid-template-columns: 1fr;
            }
            
            .filter-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
        
        /* Export Button */
        .export-section {
            text-align: right;
            margin-top: 20px;
        }
        
        /* Date Range Picker Custom */
        .date-range {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .date-range input {
            flex: 1;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>📊 Walmart Sales Dashboard - Interactive</h1>
        
        <!-- Filter Panel -->
        <div class="filter-panel">
            <div class="filter-grid">
                <div class="filter-group">
                    <label>📅 Date Range</label>
                    <div class="date-range">
                        <input type="date" id="startDate" placeholder="Start Date">
                        <span>to</span>
                        <input type="date" id="endDate" placeholder="End Date">
                    </div>
                </div>
                
                <div class="filter-group">
                    <label>🏷️ Category</label>
                    <select id="categoryFilter">
                        <option value="">All Categories</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>📍 Region/City</label>
                    <select id="regionFilter">
                        <option value="">All Regions</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>💰 Sales Range</label>
                    <select id="salesRangeFilter">
                        <option value="">All Sales</option>
                        <option value="0-100">$0 - $100</option>
                        <option value="100-500">$100 - $500</option>
                        <option value="500-1000">$500 - $1,000</option>
                        <option value="1000-5000">$1,000 - $5,000</option>
                        <option value="5000+">$5,000+</option>
                    </select>
                </div>
            </div>
            
            <div class="filter-actions">
                <button class="btn btn-primary" onclick="applyFilters()">🔍 Apply Filters</button>
                <button class="btn btn-secondary" onclick="resetFilters()">🔄 Reset</button>
                <button class="btn btn-success" onclick="exportData()">📥 Export Data</button>
            </div>
            
            <div id="activeFilters" class="active-filters"></div>
        </div>
        
        <!-- KPI Cards -->
        <div class="kpi-grid" id="kpiContainer">
            <div class="loading">Loading KPIs...</div>
        </div>
        
        <!-- Sales Over Time -->
        <div class="chart-container">
            <div class="chart-header">
                <h2>📈 Sales Over Time</h2>
                <div class="chart-controls">
                    <select id="timeGranularity" onchange="updateTimeChart()">
                        <option value="day">Daily</option>
                        <option value="week">Weekly</option>
                        <option value="month">Monthly</option>
                    </select>
                    <button class="btn btn-secondary" onclick="toggleChartType('time')">📊 Switch to Bar</button>
                </div>
            </div>
            <canvas id="salesTimeChart"></canvas>
        </div>
        
        <!-- Two Columns -->
        <div class="two-columns">
            <div class="chart-container">
                <div class="chart-header">
                    <h2>🏷️ Category Analysis</h2>
                    <div class="chart-controls">
                        <select id="categoryMetric" onchange="updateCategoryChart()">
                            <option value="total">Total Sales</option>
                            <option value="avg">Average Sales</option>
                            <option value="count">Transaction Count</option>
                        </select>
                    </div>
                </div>
                <canvas id="categoryChart"></canvas>
            </div>
            
            <div class="chart-container">
                <div class="chart-header">
                    <h2>📍 Regional Performance</h2>
                    <div class="chart-controls">
                        <select id="regionMetric" onchange="updateRegionChart()">
                            <option value="avg">Average Sales</option>
                            <option value="total">Total Sales</option>
                            <option value="count">Transaction Count</option>
                        </select>
                    </div>
                </div>
                <canvas id="regionChart"></canvas>
            </div>
        </div>
    </div>

<?php
$conn = new mysqli("localhost", "root", "", "walmart_sales");

if ($conn->connect_error) {
    die("<div class='error'>Connection failed: " . $conn->connect_error . "</div>");
}

// Detect column names
$columns_query = "SHOW COLUMNS FROM walmart_sales";
$columns_result = $conn->query($columns_query);
$columns = [];

while($col = $columns_result->fetch_assoc()) {
    $columns[] = $col['Field'];
}

$sales_col = 'sales';
$category_col = 'category';
$region_col = 'city';
$date_col = 'date';

foreach($columns as $col) {
    $col_lower = strtolower($col);
    if(strpos($col_lower, 'sale') !== false || strpos($col_lower, 'revenue') !== false || strpos($col_lower, 'total') !== false) {
        $sales_col = $col;
    }
    if(strpos($col_lower, 'category') !== false || strpos($col_lower, 'product') !== false) {
        $category_col = $col;
    }
    if(strpos($col_lower, 'city') !== false || strpos($col_lower, 'region') !== false) {
        $region_col = $col;
    }
    if(strpos($col_lower, 'date') !== false) {
        $date_col = $col;
    }
}

// Get unique values for filters
$categories = [];
$category_query = "SELECT DISTINCT $category_col FROM walmart_sales ORDER BY $category_col";
$cat_result = $conn->query($category_query);
while($row = $cat_result->fetch_assoc()) {
    $categories[] = $row[$category_col];
}

$regions = [];
$region_query = "SELECT DISTINCT $region_col FROM walmart_sales ORDER BY $region_col";
$reg_result = $conn->query($region_query);
while($row = $reg_result->fetch_assoc()) {
    $regions[] = $row[$region_col];
}

// Get date range
$date_range = [];
$min_date_query = "SELECT MIN($date_col) as min_date, MAX($date_col) as max_date FROM walmart_sales";
$date_result = $conn->query($min_date_query);
$date_range = $date_result->fetch_assoc();

// Initial KPI data (all data)
$kpi_sql = "SELECT 
    COUNT(*) as totalRecords, 
    COALESCE(AVG($sales_col), 0) as avgSales, 
    COALESCE(MAX($sales_col), 0) as maxSales,
    COALESCE(MIN($sales_col), 0) as minSales,
    COALESCE(SUM($sales_col), 0) as totalSales
    FROM walmart_sales";

$kpi_result = $conn->query($kpi_sql);
$kpi_data = $kpi_result->fetch_assoc();

// Initial time series data
$time_sql = "SELECT $date_col as date, SUM($sales_col) as daily_sales 
FROM walmart_sales 
GROUP BY $date_col 
ORDER BY $date_col ASC 
LIMIT 50";

$time_result = $conn->query($time_sql);
$time_labels = [];
$time_values = [];

while($row = $time_result->fetch_assoc()) {
    $time_labels[] = $row['date'];
    $time_values[] = $row['daily_sales'];
}

// Initial category data
$category_sql = "SELECT $category_col as category, SUM($sales_col) as total_sales, AVG($sales_col) as avg_sales, COUNT(*) as transaction_count
FROM walmart_sales 
GROUP BY $category_col 
ORDER BY total_sales DESC 
LIMIT 10";

$category_result = $conn->query($category_sql);
$category_labels = [];
$category_values = [];
$category_avg = [];
$category_count = [];

while($row = $category_result->fetch_assoc()) {
    $category_labels[] = $row['category'];
    $category_values[] = $row['total_sales'];
    $category_avg[] = $row['avg_sales'];
    $category_count[] = $row['transaction_count'];
}

// Initial region data
$region_sql = "SELECT $region_col as region, AVG($sales_col) as avg_sales, SUM($sales_col) as total_sales, COUNT(*) as transaction_count
FROM walmart_sales 
GROUP BY $region_col 
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

$conn->close();
?>

<script>
// Data from PHP
const initialData = {
    kpi: <?php echo json_encode($kpi_data); ?>,
    time: {
        labels: <?php echo json_encode($time_labels); ?>,
        values: <?php echo json_encode($time_values); ?>
    },
    category: {
        labels: <?php echo json_encode($category_labels); ?>,
        total: <?php echo json_encode($category_values); ?>,
        avg: <?php echo json_encode($category_avg); ?>,
        count: <?php echo json_encode($category_count); ?>
    },
    region: {
        labels: <?php echo json_encode($region_labels); ?>,
        avg: <?php echo json_encode($region_avg); ?>,
        total: <?php echo json_encode($region_total); ?>,
        count: <?php echo json_encode($region_count); ?>
    },
    categories: <?php echo json_encode($categories); ?>,
    regions: <?php echo json_encode($regions); ?>,
    dateRange: <?php echo json_encode($date_range); ?>
};

// Populate filter dropdowns
function populateFilters() {
    const categorySelect = document.getElementById('categoryFilter');
    initialData.categories.forEach(cat => {
        const option = document.createElement('option');
        option.value = cat;
        option.textContent = cat;
        categorySelect.appendChild(option);
    });
    
    const regionSelect = document.getElementById('regionFilter');
    initialData.regions.forEach(reg => {
        const option = document.createElement('option');
        option.value = reg;
        option.textContent = reg;
        regionSelect.appendChild(option);
    });
    
    // Set date range
    if (initialData.dateRange.min_date) {
        document.getElementById('startDate').value = initialData.dateRange.min_date;
        document.getElementById('endDate').value = initialData.dateRange.max_date;
    }
}

// Current chart instances
let salesTimeChart, categoryChart, regionChart;
let currentTimeChartType = 'line';
let currentFilters = {};

// Display KPIs
function displayKPIs(data) {
    const formatCurrency = (value) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(value);
    };
    
    document.getElementById('kpiContainer').innerHTML = `
        <div class="kpi-card"><h3>Total Records</h3><div class="value">${data.totalRecords.toLocaleString()}</div></div>
        <div class="kpi-card"><h3>Average Sales</h3><div class="value">${formatCurrency(data.avgSales)}</div></div>
        <div class="kpi-card"><h3>Max Sales</h3><div class="value">${formatCurrency(data.maxSales)}</div></div>
        <div class="kpi-card"><h3>Min Sales</h3><div class="value">${formatCurrency(data.minSales)}</div></div>
        <div class="kpi-card"><h3>Total Sales</h3><div class="value">${formatCurrency(data.totalSales)}</div></div>
    `;
}

// Create/Update Time Chart
function createSalesTimeChart(data, chartType = 'line') {
    const ctx = document.getElementById('salesTimeChart');
    if (salesTimeChart) salesTimeChart.destroy();
    
    const granularity = document.getElementById('timeGranularity').value;
    const processedData = processTimeData(data, granularity);

    salesTimeChart = new Chart(ctx, {
        type: chartType,
        data: {
            labels: processedData.labels,
            datasets: [{
                label: `${granularity.charAt(0).toUpperCase() + granularity.slice(1)} Sales`,
                data: processedData.values,
                borderColor: '#1a73e8',
                backgroundColor: chartType === 'line' ? 'rgba(26,115,232,0.1)' : '#1a73e8',
                fill: chartType === 'line',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            label += new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: 'USD'
                            }).format(context.parsed.y);
                            return label;
                        }
                    }
                }
            }
        }
    });
}

// Process time data based on granularity
function processTimeData(data, granularity) {
    // This is a simplified version - you might want to implement actual aggregation
    return data;
}

// Create/Update Category Chart
function createCategoryChart(data) {
    const ctx = document.getElementById('categoryChart');
    if (categoryChart) categoryChart.destroy();
    
    const metric = document.getElementById('categoryMetric').value;
    let chartData = [];
    let label = '';
    
    switch(metric) {
        case 'total':
            chartData = data.total;
            label = 'Total Sales';
            break;
        case 'avg':
            chartData = data.avg;
            label = 'Average Sales';
            break;
        case 'count':
            chartData = data.count;
            label = 'Transaction Count';
            break;
    }

    categoryChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: label,
                data: chartData,
                backgroundColor: '#34a853',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            if (metric === 'count') {
                                label += context.parsed.y.toLocaleString();
                            } else {
                                label += new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: 'USD'
                                }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
}

// Create/Update Region Chart
function createRegionChart(data) {
    const ctx = document.getElementById('regionChart');
    if (regionChart) regionChart.destroy();
    
    const metric = document.getElementById('regionMetric').value;
    let chartData = [];
    
    switch(metric) {
        case 'avg':
            chartData = data.avg;
            break;
        case 'total':
            chartData = data.total;
            break;
        case 'count':
            chartData = data.count;
            break;
    }

    regionChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.labels,
            datasets: [{
                data: chartData,
                backgroundColor: [
                    '#1a73e8','#34a853','#fbbc05','#ea4335',
                    '#9c27b0','#00acc1','#ff7043','#8bc34a',
                    '#ff6f00','#4e342e'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '60%',
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.parsed;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = ((value / total) * 100).toFixed(1);
                            
                            if (metric === 'count') {
                                return `${label}: ${value.toLocaleString()} (${percentage}%)`;
                            } else {
                                return `${label}: ${new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: 'USD'
                                }).format(value)} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        }
    });
}

// Apply filters (AJAX call to get filtered data)
async function applyFilters() {
    const filters = {
        startDate: document.getElementById('startDate').value,
        endDate: document.getElementById('endDate').value,
        category: document.getElementById('categoryFilter').value,
        region: document.getElementById('regionFilter').value,
        salesRange: document.getElementById('salesRangeFilter').value
    };
    
    currentFilters = filters;
    updateActiveFilters();
    
    showLoading();
    
    try {
        const response = await fetch('get_filtered_data.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(filters)
        });
        
        const data = await response.json();
        
        if (data.error) {
            showError(data.error);
            return;
        }
        
        // Update all charts and KPIs
        displayKPIs(data.kpi);
        createSalesTimeChart(data.time, currentTimeChartType);
        updateCategoryChartData(data.category);
        updateRegionChartData(data.region);
        
    } catch (error) {
        showError('Error fetching data: ' + error.message);
    }
}

// Update active filters display
function updateActiveFilters() {
    const container = document.getElementById('activeFilters');
    let html = '';
    
    if (currentFilters.startDate || currentFilters.endDate) {
        html += `<span class="filter-tag">📅 ${currentFilters.startDate || 'Start'} to ${currentFilters.endDate || 'End'}
            <button onclick="clearFilter('date')">×</button></span>`;
    }
    if (currentFilters.category) {
        html += `<span class="filter-tag">🏷️ ${currentFilters.category}
            <button onclick="clearFilter('category')">×</button></span>`;
    }
    if (currentFilters.region) {
        html += `<span class="filter-tag">📍 ${currentFilters.region}
            <button onclick="clearFilter('region')">×</button></span>`;
    }
    if (currentFilters.salesRange) {
        html += `<span class="filter-tag">💰 ${currentFilters.salesRange}
            <button onclick="clearFilter('salesRange')">×</button></span>`;
    }
    
    container.innerHTML = html;
}

// Clear specific filter
function clearFilter(type) {
    switch(type) {
        case 'date':
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';
            break;
        case 'category':
            document.getElementById('categoryFilter').value = '';
            break;
        case 'region':
            document.getElementById('regionFilter').value = '';
            break;
        case 'salesRange':
            document.getElementById('salesRangeFilter').value = '';
            break;
    }
    applyFilters();
}

// Reset all filters
function resetFilters() {
    document.getElementById('startDate').value = initialData.dateRange.min_date || '';
    document.getElementById('endDate').value = initialData.dateRange.max_date || '';
    document.getElementById('categoryFilter').value = '';
    document.getElementById('regionFilter').value = '';
    document.getElementById('salesRangeFilter').value = '';
    
    // Reset to initial data
    displayKPIs(initialData.kpi);
    createSalesTimeChart(initialData.time);
    createCategoryChart(initialData.category);
    createRegionChart(initialData.region);
    
    currentFilters = {};
    updateActiveFilters();
}

// Toggle chart type
function toggleChartType(chartId) {
    if (chartId === 'time') {
        currentTimeChartType = currentTimeChartType === 'line' ? 'bar' : 'line';
        const btn = document.querySelector('.chart-header .btn-secondary');
        btn.textContent = currentTimeChartType === 'line' ? '📊 Switch to Bar' : '📈 Switch to Line';
        createSalesTimeChart(initialData.time, currentTimeChartType);
    }
}

// Update charts when metric changes
function updateTimeChart() {
    createSalesTimeChart(initialData.time, currentTimeChartType);
}

function updateCategoryChart() {
    createCategoryChart(initialData.category);
}

function updateRegionChart() {
    createRegionChart(initialData.region);
}

// Store updated data for charts
function updateCategoryChartData(data) {
    initialData.category = data;
    createCategoryChart(data);
}

function updateRegionChartData(data) {
    initialData.region = data;
    createRegionChart(data);
}

// Export functionality
function exportData() {
    const data = {
        filters: currentFilters,
        kpi: initialData.kpi,
        exportDate: new Date().toISOString()
    };
    
    const jsonStr = JSON.stringify(data, null, 2);
    const blob = new Blob([jsonStr], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `walmart_sales_export_${new Date().toISOString().split('T')[0]}.json`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

// UI Helpers
function showLoading() {
    // Add loading indicators
}

function showError(message) {
    console.error(message);
    alert('Error: ' + message);
}

// Initialize dashboard
function init() {
    populateFilters();
    displayKPIs(initialData.kpi);
    createSalesTimeChart(initialData.time);
    createCategoryChart(initialData.category);
    createRegionChart(initialData.region);
}

// Start the dashboard
init();

</script>

<!-- Create a separate PHP file for AJAX filtering -->
<?php
// Save this as get_filtered_data.php
$filter_code = <<<'PHP'
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
PHP;

// Save the filter processing file
file_put_contents('get_filtered_data.php', $filter_code);
?>

</body>
</html>