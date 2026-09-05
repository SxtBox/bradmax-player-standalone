# 🎬 Bradmax Player - Standalone PHP Edition

**Neon UI Styled | Shared Hosting Compatible | Zero Dependencies**

## Features

✨ **Standalone PHP**  
✨ **Beautiful Neon UI** - Cyberpunk-inspired interface  
✨ **Upload Manager** - Easy custom player file management  
✨ **Player Info** - View version, skin, and upload date  
✨ **Responsive Design** - Works on all devices  
✨ **Zero Dependencies** - Pure PHP and CSS/JS  
✨ **Shared Hosting** - Works on any PHP 8.x.+ hosting  

## Requirements

- PHP 8.0 or higher
- Write permissions for `/uploads` directory
- Web server (Apache, Nginx, LiteSpeed, etc.)
- 50MB disk space (minimal)

## Installation

### Step 1: Download & Upload

```bash
git clone https://github.com/SxtBox/bradmax-player-standalone.git
cd bradmax-player-standalone
```

Or download the ZIP and extract to your hosting.

### Step 2: Set Permissions

```bash
chmod 755 uploads/
chmod 644 assets/css/*
chmod 644 includes/*
```

### Step 3: Access Application

```
http://yourdomain.com/bradmax-player-standalone/
```

That's it! 🎉

## Usage

### 1. Get Custom Player

1. Visit [bradmax.com](https://bradmax.com)
2. Sign up for free account
3. Create and configure your player
4. Download `bradmax_player.js`

### 2. Upload to Manager

1. Open the application in your browser
2. Go to "Upload Custom Player" section
3. Select the downloaded `bradmax_player.js` file
4. Click "Upload Player"

### 3. View Information

Once uploaded, you'll see:
- Player version
- Skin/theme name
- Upload date and time
- Option to remove and use default

## Directory Structure

```
bradmax-player-standalone/
├── index.php                  # Main entry point
├── includes/
│   └── BradmaxPlayer.php      # Core application class
├── assets/
│   ├── css/
│   │   ├── neon.css           # Neon UI styling
│   │   └── style.css          # Additional styles
│   ├── js/
│   │   └── default_player.js  # Default player (optional)
│   └── img/
├── uploads/                   # Custom player files
│   └── .gitkeep
└── README.md
```

## Customization

### Change Neon Theme Colors

Edit `assets/css/neon.css` and modify the `:root` CSS variables:

```css
:root {
    --neon-cyan: #00ff41;      /* Primary color */
    --neon-purple: #bc13fe;    /* Secondary color */
    --neon-pink: #ff006e;      /* Accent color */
    --neon-blue: #0099ff;      /* Link color */
    --neon-green: #39ff14;     /* Success color */
    --neon-yellow: #ffff00;    /* Warning color */
    --neon-red: #ff0040;       /* Error color */
    --bg-dark: #0a0e27;        /* Dark background */
    --bg-darker: #050812;      /* Darker background */
}
```

### Modify UI Text

Edit `includes/BradmaxPlayer.php` in the `run()` method to customize:
- Headers and titles
- Button text
- Help messages
- Placeholder text

### Change Maximum File Size

In `includes/BradmaxPlayer.php`, find:
```php
$maxSize = 5 * 1024 * 1024; // 5MB
```

Change to desired size (in bytes).

## Security Features

✅ **File Extension Validation** - Only `.js` files accepted  
✅ **File Size Limit** - Maximum 5MB per file  
✅ **Content Validation** - Verifies Bradmax player code  
✅ **Output Escaping** - HTML-escapes all user data  
✅ **Directory Protection** - Write-only uploads directory  

## Troubleshooting

### "Failed to save file" Error

**Solution:**
```bash
chmod 755 uploads/
chown www-data:www-data uploads/  # For Apache on Linux
```

### File Not Uploading

**Check:**
1. File is named `bradmax_player.js`
2. File size is under 5MB
3. File contains valid Bradmax player code
4. Directory has write permissions

### Permission Denied Error

**Solution:**
```bash
# For cPanel/Shared Hosting
chmod 777 uploads/

# For VPS/Dedicated
chmod 755 uploads/
chown www-data:www-data uploads/
```

### Blank Page on Load

**Check:**
1. PHP version is 5.6 or higher: `<?php phpinfo(); ?>`
2. No errors in server logs
3. `index.php` and `includes/` folder exist
4. Try clearing browser cache

## Performance Tips

- Use gzip compression on your server
- Enable browser caching (1 hour for CSS/JS)
- Minify custom player files if possible
- Use CDN for assets (optional)

## Updating

To update the application:

1. Backup your `uploads/` directory
2. Download latest version
3. Replace files (except `uploads/`)
4. Restore `uploads/` directory

## API Integration

You can integrate the player into your website using:

```html
<div id="bradmax-player"></div>
<script src="bradmax-player-standalone/uploads/bradmax_player.js"></script>
```

Refer to Bradmax documentation for full player API.

## Support & Resources

- 🌐 [Bradmax Official](https://bradmax.com)
- 📧 [Support Email](mailto:support@bradmax.com)
- 📚 [Documentation](https://bradmax.com/docs)
- 🐛 [Report Issues](https://github.com/SxtBox/bradmax-player-standalone/issues)

## FAQ

**Q: Can I use this without WordPress?**  
A: Yes! This is a standalone application that doesn't require WordPress.

**Q: Can I modify the UI?**  
A: Absolutely! Edit `assets/css/neon.css` to change colors and styles.

**Q: Is it secure?**  
A: Yes, it includes file validation, size limits, and output escaping.

**Q: Does it work on shared hosting?**  
A: Yes, it only requires PHP 8.x.+ and write permissions.

**Q: Can I use multiple custom players?**  
A: Currently supports one active player. Previous files are overwritten.

## License

GPLv3 or later - See LICENSE file for details.

## Version Info

- **App Version:** 1.1.32
- **Bradmax Player:** 2.15.50
- **Last Updated:** 2026
- **PHP Requirement:** 8.x.+

## Credits

Based on Bradmax Player
PHP with Neon UI styling.

---

**Made with ❤️ for video enthusiasts**
