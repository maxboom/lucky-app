# Lucky App

A PHP 8.2 web application built with Domain-Driven Design (DDD) principles.

## Architecture

```
src/
├── Domain/                     # Business logic — no framework dependencies
│   ├── User/
│   │   ├── Entity/User.php                 # User aggregate root
│   │   ├── ValueObject/                    # UserId, Username, PhoneNumber, AccessLink
│   │   └── Repository/UserRepositoryInterface.php
│   └── Game/
│       ├── Entity/GameResult.php           # Game result aggregate
│       ├── ValueObject/                    # GameResultId, GameOutcome (enum)
│       └── Repository/GameResultRepositoryInterface.php
│
├── Application/                # Use cases — orchestrate domain
│   ├── User/UseCase/           # RegisterUser, RegenerateLink, DeactivateLink
│   └── Game/UseCase/           # PlayGame, GetGameHistory
│
├── Infrastructure/             # Framework & DB details
│   ├── Persistence/MySQL/      # MySQLUserRepository, MySQLGameResultRepository
│   ├── Http/Controller/        # HomeController, PageController
│   └── Container.php           # Simple DI container
│
└── UI/
    ├── public/index.php         # Front controller / router
    └── templates/               # Plain PHP templates
```

## Requirements

- PHP 8.2+ with `pdo`, `pdo_mysql` extensions
- MySQL 8.0+
- Composer

---

## 🚀 Quick Start — Docker (recommended)

### 1. Clone the repository
```bash
git clone <repository-url>
cd lucky-app
```

### 2. Start all services
```bash
docker-compose up --build
```

### 3. Install dependencies
```bash
composer install
```

The app will be available at **http://localhost:8000** once MySQL is healthy (takes ~15s on first run).

> Schema is applied automatically via `database/schema.sql` on first MySQL start.

---

## 🛠 Manual Setup (without Docker)

### 1. Clone the repository
```bash
git clone <repository-url>
cd lucky-app
```

### 2. Install dependencies
```bash
composer install
```

### 3. Configure environment
```bash
cp .env.example .env
# Edit .env and set your MySQL credentials
```

### 4. Create the database and apply schema
```bash
mysql -u root -p -e "CREATE DATABASE lucky_app CHARACTER SET utf8mb4;"
mysql -u root -p -e "CREATE USER 'lucky'@'localhost' IDENTIFIED BY 'lucky'; GRANT ALL ON lucky_app.* TO 'lucky'@'localhost';"
mysql -u lucky -p lucky_app < database/schema.sql
```

### 5. Start the built-in PHP server
```bash
php -S 0.0.0.0:8000 -t src/UI/public src/UI/public/index.php
```

Open **http://localhost:8000**

---

## Features

| Feature | Description |
|---|---|
| Registration | Form with Username + Phone Number |
| Unique access link | 64-char hex token, valid for 7 days |
| Regenerate link | New token, new 7-day TTL |
| Deactivate link | Permanently disable current token |
| ImFeelingLucky | Roll 1–1000; even = Win, odd = Lose |
| Win calculation | >900 → 70%, >600 → 50%, >300 → 30%, ≤300 → 10% |
| History | Last 3 game results |

## Running Tests

```bash
composer install
./vendor/bin/phpunit tests/
```
