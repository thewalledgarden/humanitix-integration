<?php
define('WP_DEBUG', true);
define('FS_METHOD', 'direct');
define('FS_CHMOD_DIR', (0775 & ~ umask()));
define('FS_CHMOD_FILE', (0664 & ~ umask()));
define('WP_PLUGIN_DIR', dirname(__FILE__) . '/wp-content/plugins');
define('WP_CONTENT_DIR', dirname(__FILE__) . '/wp-content');
define('WP_PLUGIN_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/wp-content/plugins');
define('WPMU_PLUGIN_DIR', dirname(__FILE__) . '/wp-content/mu-plugins');
define('WPMU_PLUGIN_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/wp-content/mu-plugins');
define('DB_NAME', 'exampledb');
define('DB_USER', 'exampleuser');
define('DB_PASSWORD', 'examplepassword');
define('DB_HOST', 'db');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');
$table_prefix = 'wp_';
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
require_once ABSPATH . 'wp-settings.php';