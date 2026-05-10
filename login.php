<?php

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Start session so that other modules (e.g. add-car) can identify the logged-in seller
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

require_once __DIR__ . '/db_config.php';

// ============================================================
// 1. 接收并校验前端提交的数据
// ============================================================
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// 基本非空校验
if ($username === '' || $password === '') {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Username and password are required.',
        'field'   => $username === '' ? 'username' : 'password'
    ]);
    exit;
}

// 用户名格式校验（至少6位字母数字）
if (!preg_match('/^[A-Za-z0-9]{6,}$/', $username)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid username format.',
        'field'   => 'username'
    ]);
    exit;
}

// ============================================================
// 2. 连接数据库并查询用户
// ============================================================
try {
    $conn = getDBConnection();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed. Please try again later.'
    ]);
    exit;
}

// 使用 db_config.php 中定义的表名和字段名，实现联调时零修改
$table    = TABLE_USERS;
$colUser  = FIELD_USERNAME;
$colPass  = FIELD_PASSWORD;
$colName  = FIELD_NAME;

// 预处理语句防止 SQL 注入
$sql = "SELECT `id`, `$colUser`, `$colPass`, `$colName` FROM `$table` WHERE `$colUser` = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // 用户名不存在
    $stmt->close();
    $conn->close();
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Login failed. Username does not exist.',
        'field'   => 'username'
    ]);
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();

// ============================================================
// 3. 验证密码（使用 password_verify 配合 password_hash）
// ============================================================
if (!password_verify($password, $user[$colPass])) {
    $conn->close();
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Login failed. Incorrect password.',
        'field'   => 'password'
    ]);
    exit;
}

// ============================================================
// 4. 登录成功，写入 Session 并返回用户信息
// ============================================================
$_SESSION['seller_id'] = (int) $user['id'];
$_SESSION['username']  = $user[$colUser];
$_SESSION['name']      = $user[$colName] ?? $user[$colUser];

$conn->close();

echo json_encode([
    'success'  => true,
    'message'  => 'Login successful. Welcome back, ' . $user[$colUser] . '.',
    'username' => $user[$colUser],
    'name'     => $user[$colName] ?? $user[$colUser]
]);
