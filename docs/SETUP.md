# Ascend Setup Guide

## Prerequisites
- Ubuntu 20.04+ server
- Apache2 or Nginx
- MariaDB server
- PHP 7.4+
- Python 3.8+
- Node.js 14+ (for Electron builds)

## 1. Server Setup

### Install Dependencies
```bash
sudo apt update
sudo apt install apache2 mariadb-server php php-mysql php-curl python3-pip nodejs npm
```

### Enable PHP Modules
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Create MariaDB User
```bash
sudo mysql -u root -p
```

```sql
CREATE USER 'ascend_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON ascend.* TO 'ascend_user'@'localhost';
FLUSH PRIVILEGES;
```

### Import Database Schema
```bash
sudo mysql -u ascend_user -p ascend < database/schema.sql
```

## 2. PHP Application Setup

### Copy to Web Root
```bash
sudo cp -r html/* /var/www/html/
sudo chown -R www-data:www-data /var/www/html/
```

### Create API Directory
```bash
sudo mkdir -p /var/www/html/api
sudo cp api/* /var/www/html/api/
sudo chmod 755 /var/www/html/api
```

### Create Logs Directory
```bash
sudo mkdir -p /var/www/html/logs
sudo chmod 777 /var/www/html/logs
```

### Set PHP Configuration
Edit `/etc/php/7.4/apache2/php.ini`:
```
upload_max_filesize = 20M
post_max_size = 20M
max_execution_time = 300
```

## 3. Python Service Setup

### Install Dependencies
```bash
cd python
pip3 install -r requirements.txt
```

### Create Systemd Service
Create `/etc/systemd/system/ascend-prayers.service`:
```ini
[Unit]
Description=Ascend Prayer Service
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/html/python
ExecStart=/usr/bin/python3 /var/www/html/python/prayer_service.py
Restart=always

[Install]
WantedBy=multi-user.target
```

Enable the service:
```bash
sudo systemctl daemon-reload
sudo systemctl enable ascend-prayers
sudo systemctl start ascend-prayers
```

## 4. phpMyAdmin Setup (Optional but Recommended)

```bash
sudo apt install phpmyadmin
```

Access at: `http://your-server/phpmyadmin`

## 5. SSL Certificate (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-apache
sudo certbot certonly --apache -d yourdomain.com
```

Update Apache config to use SSL.

## 6. Environment Variables

Copy `.env.example` to `.env`:
```bash
cp .env.example .env
```

Edit `.env` and add your API keys:
```
CLAUDE_API_KEY=sk-...
BIBLE_API_KEY=...
```

## 7. Build Electron App (for macOS/Windows/Android/iOS)

### macOS Build
```bash
cd electron
npm install
npm run build-mac
```

### Windows Build
```bash
npm run build-win
```

### Linux Build
```bash
npm run build-linux
```

### iOS/Android Build (using React Native or Capacitor)
Consider wrapping the web app with Capacitor for mobile:
```bash
npm install -g @capacitor/cli
npx cap init
npx cap add ios
npx cap add android
npx cap open ios
npx cap open android
```

## 8. Access the Application

- **Web:** `http://your-server/`
- **phpMyAdmin:** `http://your-server/phpmyadmin`

## 9. Backup

Set up regular backups:
```bash
0 2 * * * mysqldump -u ascend_user -p ascend > /var/www/backups/ascend_$(date +\%Y\%m\%d).sql
```

## Troubleshooting

### Database Connection Issues
Check PHP error log: `/var/log/apache2/error.log`

### API Errors
Check Python service: `sudo systemctl status ascend-prayers`

### Permission Issues
```bash
sudo chown -R www-data:www-data /var/www/html/
sudo chmod -R 755 /var/www/html/
```

## Support

For issues or questions, open a GitHub issue at:
https://github.com/ParishTech/ascendapp
