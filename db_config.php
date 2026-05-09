<?php
/**
 * Database Configuration - Veluxe Motors Seller Module
 * 
 * ===================== 联调说明 =====================
 * 当需要对接组员创建的用户注册数据库时，只需修改下方
 * 「联调配置区」中的数据库连接参数和表/字段映射即可。
 * ==================================================
 */

// ============================================================
// 【联调配置区】- 对接组员数据库时修改此处
// ============================================================

// 数据库连接参数（联调时替换为组员提供的数据库信息）
define('DB_HOST', '127.0.0.1');        // 组员数据库主机地址
define('DB_PORT', 3306);               // 组员数据库端口
define('DB_USER', 'root');             // 组员数据库用户名
define('DB_PASS', '');                 // 组员数据库密码
define('DB_NAME', 'veluxe_motors');    // 组员数据库名称
define('DB_SOCKET', '/tmp/mysql.sock');// Unix Socket路径（如不需要设为空字符串）
define('DB_CHARSET', 'utf8mb4');       // 字符集

// 用户表和字段映射（对接 Liu Ruoqi / Bi Qinzhi 注册模块的 sellers 表）
define('TABLE_USERS', 'sellers');             // 用户表名
define('FIELD_USERNAME', 'username');         // 用户名字段
define('FIELD_PASSWORD', 'password_hash');    // 密码字段（password_hash() 哈希值）
define('FIELD_NAME', 'name');                 // 显示名称字段

// ============================================================
// 数据库连接函数
// ============================================================

/**
 * 获取 mysqli 数据库连接
 * @return mysqli 数据库连接对象
 * @throws Exception 连接失败时抛出异常
 */
function getDBConnection(): mysqli {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $conn = new mysqli(
        DB_HOST,
        DB_USER,
        DB_PASS,
        DB_NAME,
        DB_PORT,
        DB_SOCKET !== '' ? DB_SOCKET : null
    );

    $conn->set_charset(DB_CHARSET);

    return $conn;
}
