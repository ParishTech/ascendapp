# Ascend

**Open-source altar server management system for Catholic parishes.**

Ascend helps parishes track volunteer hours, performance, and training for altar servers while providing spiritual resources and building a community of growing servants.

## Features

- **Clock In/Out System** — Track volunteer hours for each mass, automatically compute total service time
- **Performance Tracking** — Master of Ceremonies logs detailed notes on server demeanor, timeliness, and accuracy with constructive feedback
- **Training Referrals** — MC can flag servers for remedial training with notes on what to improve
- **Training History** — Dashboard shows when each server last completed training and what areas need work
- **Spiritual Resources** — Built-in prayers (vesting, rosary mysteries, daily reflections) to prepare servers spiritually
- **AI-Generated Daily Prayer** — Claude API integration generates personalized daily prayers tied to the liturgical calendar
- **Scripture Integration** — Daily scripture readings pulled from Bible API
- **Role-Based Access** — Separate views for altar servers, MCs (Master of Ceremonies), trainers, and admins

## Tech Stack

- **Frontend**: PHP + HTML + CSS + JavaScript (LEMP stack)
- **Backend**: PHP with MariaDB
- **Database**: MariaDB
- **Python Services**: Claude API (prayer generation), Bible API (scripture)
- **Desktop/Mobile App**: Electron (wraps web app for macOS, Windows, Linux, iOS, Android, and all Linux Mobile Distros)
- **Hosting**: Your own Ubuntu server

## Getting Started

### Prerequisites

- Ubuntu 20.04+ server
- Apache2 or Nginx
- MariaDB 10.3+
- PHP 7.4+
- Python 3.8+
- Node.js 14+ (for Electron builds only)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/ParishTech/ascendapp.git
   cd ascendapp
   ```

2. **Set up MariaDB**
   ```bash
   sudo mysql -u root -p < database/schema.sql
   ```

   Create MariaDB user:
   ```bash
   sudo mysql -u root -p
   CREATE USER 'ascend_user'@'localhost' IDENTIFIED BY 'secure_password';
   GRANT ALL PRIVILEGES ON ascend.* TO 'ascend_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

3. **Set up PHP application**
   ```bash
   sudo cp -r html/* /var/www/html/
   sudo cp -r api/* /var/www/html/api/
   sudo chown -R www-data:www-data /var/www/html/
   sudo mkdir -p /var/www/html/logs
   sudo chmod 777 /var/www/html/logs
   ```

4. **Set up Python service**
   ```bash
   cd python
   pip3 install -r requirements.txt
   ```

   Create systemd service (see docs/SETUP.md for details):
   ```bash
   sudo nano /etc/systemd/system/ascend-prayers.service
   sudo systemctl daemon-reload
   sudo systemctl enable ascend-prayers
   sudo systemctl start ascend-prayers
   ```

5. **Configure environment variables**
   ```bash
   cp .env.example .env
   # Edit .env and add your API keys
   ```

The app will be available at `http://localhost` (or your server's IP/domain).

## Project Structure

```
ascend/
├── html/                      # PHP Frontend (LEMP Stack)
│   ├── index.php              # Home page
│   ├── login.php              # Login form
│   ├── dashboard.php          # Main dashboard
│   ├── clock.php              # Clock in/out interface
│   ├── prayers.php            # Prayers & scripture
│   ├── css/
│   │   └── style.css          # Responsive styling
│   └── js/
│       ├── api-client.js      # API communication
│       └── dashboard.js       # Dashboard logic
│
├── api/                       # PHP Backend API
│   ├── config.php             # Configuration & constants
│   ├── database.php           # Database wrapper class
│   ├── auth.php               # Authentication endpoints
│   ├── clock.php              # Clock in/out endpoints
│   └── notes.php              # Performance notes endpoints
│
├── python/                    # Python Services
│   ├── prayer_service.py      # Claude API + Bible API
│   └── requirements.txt       # Python dependencies
│
├── electron/                  # Desktop/Mobile App
│   ├── main.js                # Electron main process
│   └── package.json           # Electron build config
│
├── database/                  # Database Schema
│   └── schema.sql             # Complete MariaDB schema
│
├── docs/
│   └── SETUP.md               # Detailed setup guide
│
├── .env.example               # Environment variables template
└── LICENSE                    # GNU GPL-3.0 License
```

## API Endpoints

### Authentication
- `POST /api/auth.php?action=register` — Create a new account
- `POST /api/auth.php?action=login` — Login and get session token
- `POST /api/auth.php?action=logout` — Logout and invalidate token
- `GET /api/auth.php?action=verify` — Verify current token

### Clock System
- `POST /api/clock.php?action=in` — Clock in for a mass
- `POST /api/clock.php?action=out` — Clock out from a mass
- `GET /api/clock.php?action=history` — Get clock-in/out history
- `GET /api/clock.php?action=current` — Get current clock status

### Performance Notes
- `POST /api/notes.php?action=add` — MC logs performance notes
- `GET /api/notes.php?action=get` — Get notes for a mass
- `GET /api/notes.php?action=server` — Get feedback history for a server
- `POST /api/notes.php?action=referral` — Create training referral

### Prayers & Resources
- Daily prayers and scripture are served via the frontend (static content + Python service generation)

## Contributing

Contributions are welcome! This is an open-source project designed to serve the Catholic community.

### How to Contribute

1. **Fork the repository**
2. **Create a feature branch** (`git checkout -b feature/your-feature`)
3. **Commit your changes** (`git commit -m 'Add your feature'`)
4. **Push to the branch** (`git push origin feature/your-feature`)
5. **Open a Pull Request** with a clear description

### Development Guidelines

- Follow PHP PSR-12 code style
- Write meaningful commit messages
- Test your changes locally before pushing
- Update documentation for new features
- Keep commits atomic and focused

### Areas Looking for Help

- **Frontend**: UI/UX improvements, accessibility, additional PHP pages (profile, training management)
- **Backend**: Error handling, validation improvements, additional API endpoints
- **Python Services**: Additional prayer generation logic, better scripture integration
- **Documentation**: Deployment guides, troubleshooting, parish setup tutorials
- **Localization**: Spanish and other language translations for prayers and UI
- **Electron Build**: Packaging for iOS/Android using Capacitor, auto-update system

## License

Ascend is licensed under the GNU General Public License v3 (GPL-3.0) — see the [LICENSE](LICENSE) file for details.

This means you're free to use, modify, and distribute this software. Any modifications or derivative works must also be released under the GPL-3.0 license, ensuring the software remains open-source and free for the Church and Catholic community.

## Support

- **Issues**: Report bugs or request features on [GitHub Issues](https://github.com/ParishTech/ascendapp/issues)
- **Discussions**: Ask questions and discuss ideas on [GitHub Discussions](https://github.com/ParishTech/ascendapp/discussions)
- **Email**: Contact your parish administrator

## Acknowledgments

Ascend was built to serve Catholic altar servers and the parishes that support them. Special thanks to:
- The altar servers who inspired this project
- Contributors and testers from the Catholic development community
- Anthropic for the Claude API

---

**In the spirit of the liturgy:** *May this tool help altar servers grow in their vocation, deepen their faith, and serve the Church with joy and fidelity.*

Pax Christi.
