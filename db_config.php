<?php
define('DB_HOST', '127.0.0.1');        
define('DB_PORT', 3306);               
define('DB_USER', 'root');            
define('DB_PASS', '');                 
define('DB_NAME', 'veluxe_motors');   
define('DB_SOCKET', '/tmp/mysql.sock');
define('DB_CHARSET', 'utf8mb4');      


define('TABLE_USERS', 'sellers');             
define('FIELD_USERNAME', 'username');        
define('FIELD_PASSWORD', 'password_hash');    
define('FIELD_NAME', 'name');                 


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
