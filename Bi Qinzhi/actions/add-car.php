<?php

declare(strict_types=1);

require __DIR__ . '/../includes/app.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with('../add-car.php', 'error', 'Invalid request method.');
}

$sellerId = (int) ($_POST['seller_id'] ?? 0);
$make = trim($_POST['make'] ?? '');
$model = trim($_POST['model'] ?? '');
$year = (int) ($_POST['manufacture_year'] ?? 0);
$price = (float) ($_POST['price'] ?? 0);
$mileage = (int) ($_POST['mileage'] ?? 0);
$color = trim($_POST['color'] ?? '');
$fuelType = trim($_POST['fuel_type'] ?? '');
$transmission = trim($_POST['transmission'] ?? '');
$location = trim($_POST['location'] ?? '');
$imagePath = trim($_POST['image_path'] ?? '');
$description = trim($_POST['description'] ?? '');
$currentYear = (int) date('Y') + 1;

if ($sellerId <= 0 || !seller_exists($pdo, $sellerId)) {
    redirect_with('../add-car.php', 'error', 'Please choose a registered seller.');
}

if (!preg_match('/^[A-Za-z0-9\s-]{2,60}$/', $make)) {
    redirect_with('../add-car.php', 'error', 'Brand must be 2-60 letters, numbers, spaces, or hyphens.');
}

if (!preg_match('/^[A-Za-z0-9\s.-]{1,80}$/', $model)) {
    redirect_with('../add-car.php', 'error', 'Model must be 1-80 letters, numbers, spaces, dots, or hyphens.');
}

if ($year < 1980 || $year > $currentYear) {
    redirect_with('../add-car.php', 'error', 'Manufacture year is outside the allowed range.');
}

if ($price <= 0 || $price > 99999999) {
    redirect_with('../add-car.php', 'error', 'Price must be greater than 0.');
}

if ($mileage < 0 || $mileage > 2000000) {
    redirect_with('../add-car.php', 'error', 'Mileage must be between 0 and 2,000,000.');
}

if (!preg_match('/^[A-Za-z\s-]{2,40}$/', $color)) {
    redirect_with('../add-car.php', 'error', 'Color must be 2-40 letters, spaces, or hyphens.');
}

if (!in_array($fuelType, ['Petrol', 'Diesel', 'Hybrid', 'Electric'], true)) {
    redirect_with('../add-car.php', 'error', 'Please choose a valid fuel type.');
}

if (!in_array($transmission, ['Automatic', 'Manual'], true)) {
    redirect_with('../add-car.php', 'error', 'Please choose a valid transmission.');
}

if (!preg_match('/^[A-Za-z0-9\s,.-]{2,120}$/', $location)) {
    redirect_with('../add-car.php', 'error', 'Location contains unsupported characters.');
}

if (!valid_texture_path($imagePath)) {
    redirect_with('../add-car.php', 'error', 'Please choose a valid vehicle image.');
}

if (strlen($description) < 20 || strlen($description) > 1000) {
    redirect_with('../add-car.php', 'error', 'Description must be between 20 and 1000 characters.');
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO vehicles
            (seller_id, make, model, manufacture_year, price, mileage, color, fuel_type, transmission, location, image_path, description)
         VALUES
            (:seller_id, :make, :model, :manufacture_year, :price, :mileage, :color, :fuel_type, :transmission, :location, :image_path, :description)'
    );
    $stmt->execute([
        'seller_id' => $sellerId,
        'make' => $make,
        'model' => $model,
        'manufacture_year' => $year,
        'price' => $price,
        'mileage' => $mileage,
        'color' => $color,
        'fuel_type' => $fuelType,
        'transmission' => $transmission,
        'location' => $location,
        'image_path' => $imagePath !== '' ? $imagePath : null,
        'description' => $description,
    ]);
} catch (PDOException $exception) {
    redirect_with('../add-car.php', 'error', 'Vehicle could not be saved. Please try again.');
}

redirect_with('../inventory.php', 'success', 'Vehicle listing saved successfully.');

