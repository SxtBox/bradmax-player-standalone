<?php
/**
 * Bradmax Player - Standalone PHP Application
 * Version: 1.1.32
 * Neon UI Style
 * For Shared Hosting
 */

define("APP_VERSION", "1.1.32");
define("BRADMAX_PLAYER_VERSION", "2.15.50");
define("APP_ROOT", dirname(__FILE__));
define("APP_URL", rtrim((!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off" ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["SCRIPT_NAME"]), "/") . "/");
define("UPLOADS_DIR", APP_ROOT . "/uploads");
define("ASSETS_DIR", APP_ROOT . "/assets");
define("VIEWS_DIR", APP_ROOT . "/views");

// Create uploads directory if it doesn"t exist
if (!is_dir(UPLOADS_DIR)) {
    mkdir(UPLOADS_DIR, 0755, true);
}

require_once APP_ROOT . "/includes/BradmaxPlayer.php";

$app = new BradmaxPlayer();
$app->run();
?>