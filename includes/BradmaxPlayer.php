<?php
/**
 * Main Bradmax Player Application Class
 */

class BradmaxPlayer {
    private $uploadInfo = array();
    private $customPlayerExists = false;
    private $customizedPlayerPath;

    const CUSTOMIZED_PLAYER_FILE = '/uploads/bradmax_player.js';
    const DEFAULT_PLAYER_FILE = '/assets/js/default_player.js';

    // Image paths for help tips
    const CUSTOM_PLAYER_TIP_SCREEN_01 = '/assets/img/screen_01_signup.jpg';
    const CUSTOM_PLAYER_TIP_SCREEN_02 = '/assets/img/screen_02_signin.jpg';
    const CUSTOM_PLAYER_TIP_SCREEN_03 = '/assets/img/screen_03_add_player.jpg';
    const CUSTOM_PLAYER_TIP_SCREEN_04 = '/assets/img/screen_04_configure_player.jpg';
    const CUSTOM_PLAYER_TIP_SCREEN_05 = '/assets/img/screen_05_generate_player.jpg';
    const CUSTOM_PLAYER_TIP_SCREEN_06 = '/assets/img/screen_06_download_file.jpg';

    public function __construct() {
        $this->customizedPlayerPath = APP_ROOT . self::CUSTOMIZED_PLAYER_FILE;
		//$this->customizedPlayerPath = APP_URL . self::CUSTOMIZED_PLAYER_FILE;
        $this->handleRequests();
    }

    private function handleRequests() {
        // Handle file upload
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['bradmax_player_file'])) {
            $this->uploadCustomPlayer();
        }

        // Handle player removal
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_player'])) {
            $this->removeCustomPlayer();
        }

        // Check if custom player exists
        $this->customPlayerExists = is_file($this->customizedPlayerPath);
    }

    private function uploadCustomPlayer() {
        if (empty($_FILES['bradmax_player_file'])) {
            $this->uploadInfo = array('error' => 'No file selected.');
            return;
        }

        $file = $_FILES['bradmax_player_file'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // Check file size
        if ($file['size'] > $maxSize) {
            $this->uploadInfo = array('error' => 'File size exceeds 5MB limit.');
            return;
        }

        // Check file extension
        $fileName = basename($file['name']);
        if (!preg_match('/\.js$/i', $fileName)) {
            $this->uploadInfo = array('error' => 'Invalid file. Only JavaScript files (.js) are allowed.');
            return;
        }

        // Read file content
        $content = file_get_contents($file['tmp_name']);

        // Validate Bradmax player content
        if (strpos($content, 'bradmax_player_v') === false && 
            strpos($content, 'bradmax.player.') === false) {
            $this->uploadInfo = array('error' => 'File does not contain valid Bradmax player code.');
            return;
        }

        // Save file
        if (file_put_contents($this->customizedPlayerPath, $content)) {
            $this->uploadInfo = array('success' => 'Player uploaded successfully!');
            $this->customPlayerExists = true;
        } else {
            $this->uploadInfo = array('error' => 'Failed to save file. Check directory permissions.');
        }
    }

    private function removeCustomPlayer() {
        if (file_exists($this->customizedPlayerPath)) {
            if (unlink($this->customizedPlayerPath)) {
                $this->uploadInfo = array('success' => 'Custom player removed. Using default.');
                $this->customPlayerExists = false;
            } else {
                $this->uploadInfo = array('error' => 'Failed to remove file.');
            }
        }
    }

    private function getCustomPlayerInfo() {
        if (!$this->customPlayerExists) {
            return array();
        }

        $result = array();
        $result['modification_ts'] = filemtime($this->customizedPlayerPath);

        $content = file_get_contents($this->customizedPlayerPath);

        // Get player version
        $result['version'] = 'Unknown';
        if (preg_match('/bradmax_player_v([0-9\.]+)/', $content, $m)) {
            $result['version'] = $m[1];
        }

        if (preg_match('/getPluginVersion\(\){return "v([0-9\.]+)"}/', $content, $m)) {
            $result['version'] = $m[1];
        }

        // Get player skin
        $result['skin'] = 'Unknown';
        if (preg_match('/"skin":"([^"]+)"/', $content, $m)) {
            $result['skin'] = $m[1];
        }

        if (preg_match('/theme\/([^\/]+)\/layout.html"/', $content, $m)) {
            $result['skin'] = $m[1];
        }

        return $result;
    }

    private function renderHelpTip() {
        $baseUrl = dirname($_SERVER['PHP_SELF']);
        ?>
        <div class="help-section neon-glow-yellow">
            <div class="help-header">
                <h2>ℹ️ How to Get Custom Player</h2>
                <p class="help-intro">You are using default Bradmax player v<?php echo htmlspecialchars(BRADMAX_PLAYER_VERSION); ?></p>
            </div>
            <div class="help-content">
                <div class="step-list">
                    <div class="step-item">
                        <span class="step-number">1</span>
                        <div class="step-text">
                            <strong>Sign up</strong> at 
                            <a href="https://bradmax.com/site/en/signup" target="_blank">bradmax.com</a>
                        </div>
                    </div>
                    <div class="step-item">
                        <span class="step-number">2</span>
                        <div class="step-text">
                            <strong>Sign in</strong> and navigate to Players List
                        </div>
                    </div>
                    <div class="step-item">
                        <span class="step-number">3</span>
                        <div class="step-text">
                            <strong>Create new player</strong> with your desired configuration
                        </div>
                    </div>
                    <div class="step-item">
                        <span class="step-number">4</span>
                        <div class="step-text">
                            <strong>Configure</strong> skin, colors, and other features
                        </div>
                    </div>
                    <div class="step-item">
                        <span class="step-number">5</span>
                        <div class="step-text">
                            <strong>Generate</strong> the player files
                        </div>
                    </div>
                    <div class="step-item">
                        <span class="step-number">6</span>
                        <div class="step-text">
                            <strong>Download</strong> bradmax_player.js and upload it here
                        </div>
                    </div>
                </div>
            </div>
            <div class="help-footer">
                <p class="free-text">✨ It's completely free!</p>
            </div>
        </div>
        <?php
    }
    
public function run() {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bradmax Player Manager</title>
	<link rel="shortcut icon" href="https://kodi.al/favicon.ico"/>
    <link href="<?php echo APP_URL; ?>assets/css/neon.css" rel="stylesheet">
    <link href="<?php echo APP_URL; ?>assets/css/style.css" rel="stylesheet">
</head>
<body class="neon-bg">
    <div class="stars"></div>
    
    <div class="container">
        <div class="neon-card">
            <div class="neon-header">
                <div>
                    <h1>🎬 Bradmax Player</h1>
                    <p class="subtitle">Professional Video Player Manager</p>
                </div>
                <span class="version-badge">v<?php echo APP_VERSION; ?></span>
            </div>
            <!-- Messages -->
            <?php if (isset($this->uploadInfo['error'])): ?>
                <div class="alert alert-error neon-glow-red">
                    <span class="icon">⚠️</span>
                    <div>
                        <strong>Error</strong>
                        <p><?php echo htmlspecialchars($this->uploadInfo['error']); ?></p>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (isset($this->uploadInfo['success'])): ?>
                <div class="alert alert-success neon-glow-green">
                    <span class="icon">✅</span>
                    <div>
                        <strong>Success</strong>
                        <p><?php echo htmlspecialchars($this->uploadInfo['success']); ?></p>
                    </div>
                </div>
<?php endif; ?>
<!-- Current Player Info -->
<?php if ($this->customPlayerExists): ?>
                <div class="player-info neon-glow-cyan">
                    <h2>📊 Current Player Info</h2>
                    <?php 
                    $info = $this->getCustomPlayerInfo();
                    $modTime = new DateTime();
                    $modTime->setTimestamp($info['modification_ts']);
                    ?>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Version</label>
                            <span class="value">v<?php echo htmlspecialchars($info['version']); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Skin</label>
                            <span class="value"><?php echo htmlspecialchars($info['skin']); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Uploaded</label>
                            <span class="value"><?php echo $modTime->format('Y-m-d H:i:s'); ?></span>
                        </div>
                    </div>
                    <form method="POST" class="remove-form">
                        <button type="submit" name="remove_player" class="btn btn-danger neon-glow-red" onclick="return confirm('Remove current player and use default?');">
                            🗑️ Remove Custom Player
                        </button>
                    </form>
                </div>
            <?php endif; ?>
            <!-- Upload Form -->
            <div class="upload-section neon-glow-purple">
                <h2>📤 Upload Custom Player</h2>
                <form method="POST" enctype="multipart/form-data" class="upload-form">
                    <div class="form-group">
                        <label for="bradmax_player_file" class="file-label">
                            <span class="file-icon">📁</span>
                            <span class="file-text">Select bradmax_player.js file</span>
                        </label>
                        <input type="file" name="bradmax_player_file" id="bradmax_player_file" accept=".js" required>
                    </div>
                    <button type="submit" class="btn btn-primary neon-glow-cyan">
                        ⬆️ Upload Player
                    </button>
                </form>
            </div>
            <!-- Help Section -->
            <?php if (!$this->customPlayerExists): ?>
                <?php $this->renderHelpTip(); ?>
            <?php endif; ?>
            <!-- Footer -->
            <div class="footer">
                <p>Bradmax Player v<?php echo BRADMAX_PLAYER_VERSION; ?> | Standalone Edition</p>
                <p>📚 <a href="https://bradmax.com" target="_blank">Learn more at Bradmax</a></p>
				<span>&copy; <?php echo date('Y');?> <a href="https://kodi.al/" target="_blank">TRC4</a></span>
            </div>
        </div>
    </div>
</body>
</html>
<?php
}
}
?>