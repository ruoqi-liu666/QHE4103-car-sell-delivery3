<?php

declare(strict_types=1);

require __DIR__ . '/../includes/app.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with('../register.php', 'error', 'Invalid request method.');
}

$name = trim($_POST['name'] ?? '');
$address = trim($_POST['address'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (!preg_match('/^[A-Za-z\s]+$/', $name)) {
    redirect_with('../register.php', 'error', 'Name can contain letters and spaces only.');
}

if (!preg_match('/^[A-Za-z0-9\s,.-]+$/', $address)) {
    redirect_with('../register.php', 'error', 'Address can contain letters, numbers, spaces, comma, dot, and hyphen only.');
}

if (!preg_match('/^1[3-9]\d{9}$/', $phone)) {
    redirect_with('../register.php', 'error', 'Phone number must be a valid China mobile number.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/\.(cn|com)$/i', $email)) {
    redirect_with('../register.php', 'error', 'Email must contain exactly one @ and end with .cn or .com.');
}

if (!preg_match('/^[A-Za-z0-9]{6,}$/', $username)) {
    redirect_with('../register.php', 'error', 'Username must be at least 6 alphanumeric characters.');
}

if (!preg_match('/^[A-Za-z0-9]{6,}$/', $password)) {
    redirect_with('../register.php', 'error', 'Password must be at least 6 alphanumeric characters.');
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO sellers (name, address, phone, email, username, password_hash)
         VALUES (:name, :address, :phone, :email, :username, :password_hash)'
    );
    $stmt->execute([
        'name' => $name,
        'address' => $address,
        'phone' => $phone,
        'email' => $email,
        'username' => $username,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
    ]);
} catch (PDOException $exception) {
    if ($exception->getCode() === '23000') {
        redirect_with('../register.php', 'error', 'Username or email already exists.');
    }

    redirect_with('../register.php', 'error', 'Registration failed. Please try again.');
}

redirect_with('../add-car.php', 'success', 'Seller saved. You can add a vehicle now.');

