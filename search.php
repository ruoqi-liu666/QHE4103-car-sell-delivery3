<?php
require_once 'db_connect.php';

$model = isset($_GET['model']) ? trim($_GET['model']) : '';
$year = isset($_GET['year']) ? trim($_GET['year']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Search - Online Car Sale</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .search-form {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .search-form h2 {
            margin-bottom: 15px;
            color: #444;
        }

        .form-group {
            display: inline-block;
            margin-right: 15px;
            margin-bottom: 10px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .form-group input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            width: 200px;
        }

        .search-btn {
            padding: 10px 25px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 25px;
        }

        .search-btn:hover {
            background-color: #0056b3;
        }

        .results {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .results h2 {
            margin-bottom: 15px;
            color: #444;
        }

        .car-card {
            border: 1px solid #eee;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 6px;
            background: #fafafa;
        }

        .car-card h3 {
            color: #333;
            margin-bottom: 8px;
        }

        .car-card p {
            color: #666;
            margin-bottom: 5px;
        }

        .car-card .price {
            color: #e74c3c;
            font-size: 18px;
            font-weight: bold;
        }

        .no-results {
            text-align: center;
            color: #999;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Online Car Sale - Search</h1>

        <div class="search-form">
            <h2>Find Your Car</h2>
            <form action="search.php" method="GET">
                <div class="form-group">
                    <label for="model">Model</label>
                    <input type="text" id="model" name="model" placeholder="e.g. Camry, Civic" value="<?php echo htmlspecialchars($model); ?>">
                </div>
                <div class="form-group">
                    <label for="year">Year</label>
                    <input type="number" id="year" name="year" placeholder="e.g. 2022" min="1900" max="2030" value="<?php echo htmlspecialchars($year); ?>">
                </div>
                <button type="submit" class="search-btn">Search</button>
            </form>
        </div>

        <div class="results">
            <h2>Search Results</h2>
            <?php
            if ($model === '' && $year === '') {
                echo '<div class="no-results"><p>Enter a model or year above to search for cars.</p></div>';
            } else {
                // Build query with prepared statement
                $sql = "SELECT cars.*, sellers.full_name AS seller_name FROM cars JOIN sellers ON cars.seller_id = sellers.seller_id WHERE 1=1";
                $params = [];
                $types = '';

                if ($model !== '') {
                    $sql .= " AND (cars.model LIKE ? OR cars.make LIKE ?)";
                    $searchModel = "%" . $model . "%";
                    $params[] = $searchModel;
                    $params[] = $searchModel;
                    $types .= 'ss';
                }

                if ($year !== '') {
                    $sql .= " AND cars.year = ?";
                    $params[] = (int)$year;
                    $types .= 'i';
                }

                $sql .= " ORDER BY cars.created_at DESC";

                $stmt = $conn->prepare($sql);

                if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                }

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($car = $result->fetch_assoc()) {
                        echo '<div class="car-card">';
                        echo '<h3>' . htmlspecialchars($car['make']) . ' ' . htmlspecialchars($car['model']) . ' (' . htmlspecialchars($car['year']) . ')</h3>';
                        echo '<p class="price">$' . number_format($car['price'], 2) . '</p>';
                        echo '<p><strong>Color:</strong> ' . htmlspecialchars($car['color']) . '</p>';
                        echo '<p><strong>Mileage:</strong> ' . number_format($car['mileage']) . ' km</p>';
                        echo '<p><strong>Description:</strong> ' . htmlspecialchars($car['description']) . '</p>';
                        echo '<p><strong>Seller:</strong> ' . htmlspecialchars($car['seller_name']) . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="no-results"><p>No cars found matching your search criteria.</p></div>';
                }

                $stmt->close();
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
