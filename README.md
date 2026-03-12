# Ascend

**Open-source altar server management system for Catholic parishes.**

Ascend helps parishes track volunteer hours, performance, and training for altar servers—while providing spiritual resources and building a community of growing servants.


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

- **Frontend**: React + TypeScript, Tailwind CSS
- **Backend**: Node.js + Express, JWT authentication
- **Database**: PostgreSQL
- **APIs**: Claude API (prayer generation), Bible API (scripture), Anthropic SDK
- **Deployment**: Vercel (frontend), Railway/Render (backend)

## Getting Started

### Prerequisites

- Node.js 18+
- PostgreSQL 12+
- npm or yarn
- Preferably a Linux server (ex. Ubuntu 24.04, Fedora, etc.)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/samptry/ascend.git
   cd ascend
   ```

2. **Set up the backend**
   ```bash
   cd backend
   npm install
   ```

   Create a `.env` file:
   ```
   DATABASE_URL=postgresql://user:password@localhost:5432/ascend
   JWT_SECRET=your_jwt_secret_here
   CLAUDE_API_KEY=your_claude_api_key
   BIBLE_API_KEY=your_bible_api_key
   NODE_ENV=development
   ```

   Run database migrations:
   ```bash
   npm run migrate
   ```

   Start the backend:
   ```bash
   npm run dev
   ```

3. **Set up the frontend**
   ```bash
   cd ../frontend
   npm install
   ```

   Create a `.env.local` file:
   ```
   REACT_APP_API_URL=http://localhost:5000
   ```

   Start the frontend:
   ```bash
   npm start
   ```

The app will be available at `http://localhost:3000`.

## Project Structure

```
ascend/
├── backend/              # Node.js/Express API
│   ├── src/
│   │   ├── routes/       # API endpoints
│   │   ├── models/       # Database models
│   │   ├── middleware/   # Auth, validation
│   │   ├── services/     # Business logic
│   │   └── utils/        # Helpers (Claude API, Bible API)
│   ├── migrations/       # Database migrations
│   └── package.json
├── frontend/             # React web app
│   ├── src/
│   │   ├── pages/        # Dashboard, clock in, performance, prayers
│   │   ├── components/   # Reusable UI components
│   │   ├── hooks/        # Custom React hooks
│   │   ├── api/          # API client functions
│   │   └── styles/       # Tailwind config
│   └── package.json
├── docs/                 # Logo, design files, documentation
├── LICENSE               # MIT License
└── README.md             # This file
```

## API Endpoints (Overview)

### Authentication
- `POST /auth/register` — Create a new account
- `POST /auth/login` — Login and get JWT token
- `POST /auth/refresh` — Refresh access token

### Servers
- `GET /servers` — List all servers (admins only)
- `GET /servers/:id` — Get server profile and stats
- `PUT /servers/:id` — Update server info

### Clock System
- `POST /clock/in` — Clock in for a mass
- `POST /clock/out` — Clock out from a mass
- `GET /clock/history` — Get clock-in/out history for a server

### Performance Notes
- `POST /notes` — MC logs performance notes for a server
- `GET /notes/:serverId` — Get feedback history for a server
- `POST /notes/:noteId/referral` — Create training referral

### Training
- `GET /training/history` — Get training history
- `POST /training/complete` — Mark training session as completed
- `GET /training/pending` — Get pending training referrals

### Prayers & Resources
- `GET /prayers/daily` — Get today's AI-generated prayer
- `GET /prayers/vesting` — Get vesting prayers
- `GET /prayers/rosary` — Get rosary mysteries
- `GET /scripture/daily` — Get scripture reading of the day

## Contributing

Contributions are welcome! This is an open-source project designed to serve the Catholic community.

### How to Contribute

1. **Fork the repository**
2. **Create a feature branch** (`git checkout -b feature/your-feature`)
3. **Commit your changes** (`git commit -m 'Add your feature'`)
4. **Push to the branch** (`git push origin feature/your-feature`)
5. **Open a Pull Request** with a clear description

### Development Guidelines

- Follow the existing code style (ESLint + Prettier)
- Write meaningful commit messages
- Test your changes locally before pushing
- Update documentation for new features
- Keep commits atomic and focused

### Areas Looking for Help

- **Frontend**: UI/UX improvements, accessibility, mobile responsiveness
- **Backend**: API optimization, error handling, testing coverage
- **Documentation**: Guides for parishes, API docs, deployment instructions
- **Localization**: Spanish and other language translations for prayers and UI
- **Features**: Training modules, advanced reporting, integration with parish management systems

## License

Ascend is licensed under the GNU General Public License v3 (GPL-3.0) — see the [LICENSE](LICENSE) file for details.

This means you're free to use, modify, and distribute this software. Any modifications or derivative works must also be released under the GPL-3.0 license, ensuring the software remains open-source and free for the Church and Catholic community.

## Support

- **Issues**: Report bugs or request features on [GitHub Issues](https://github.com/samptry/ascend/issues)
- **Discussions**: Ask questions and discuss ideas on [GitHub Discussions](https://github.com/samptry/ascend/discussions)
- **Email**: spatryreal@gmail.com OR sanctorumfidei@gmail.com

## Acknowledgments

Ascend was built to serve Catholic altar servers and the parishes that support them. Special thanks to:
- The altar servers who inspired this project
- Contributors and testers from the Catholic development community
- Anthropic for the Claude API

---

**In the spirit of the liturgy:** *May this tool help altar servers grow in their vocation, deepen their faith, and serve the Church with joy and fidelity.*

Pax Christi.
