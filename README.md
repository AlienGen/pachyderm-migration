# Pachyderm Migration

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.4-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

A simple and reliable SQL database migration tool for Pachyderm applications. Manage your database schema changes through SQL files with automatic tracking and ordered execution.

## ✨ Features

- **🔄 Automatic Tracking**: Tracks executed migrations automatically
- **📁 SQL File-based**: Simple SQL files for your migrations
- **🔄 Ordered Execution**: Runs migrations in alphabetical order
- **🛡️ Safe**: Prevents duplicate execution
- **⚡ Zero Config**: Works out of the box
- **🤖 Easy Setup**: Simple command to set up the migration structure

## 📋 Requirements

- **PHP**: 8.4 or higher
- **Pachyderm**: Latest version
- **Composer**: For dependency management

## 🚀 Quick Start

### Installation

```bash
composer require aliengen/pachyderm-migration
```

### Setup

After installation, run the setup command to create the necessary files:

```bash
./vendor/bin/pachyderm-migration --setup
```

This will automatically create:
- `database/migrations/` folder for your SQL files
- `migration.php` file for easy execution

### Basic Usage

1. **Create a migration** by adding a SQL file to `database/migrations/`:

```sql
-- database/migrations/001_create_users_table.sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

2. **Run migrations** using any of these methods:

```bash
# Option 1: Using the generated migration.php file
php migration.php

# Option 2: Using the vendor binary directly
./vendor/bin/pachyderm-migration

# Option 3: Using composer run
composer run pachyderm-migration
```

That's it! Your migration will be executed and tracked automatically.

## 📚 Usage

Run all pending migrations using any of these methods:

```bash
# Using the generated file
php migration.php

# Using the vendor binary
./vendor/bin/pachyderm-migration

# Using composer
composer run pachyderm-migration
```

## 📁 Project Structure

```
your-project/
├── database/
│   └── migrations/          # Your SQL migration files
│       ├── 001_create_users.sql
│       ├── 002_create_posts.sql
│       └── 003_add_indexes.sql
├── vendor/                  # Composer dependencies
├── migration.php           # Migration execution script
└── composer.json
```

## 🔧 Configuration

### Database Connection

Ensure your Pachyderm `config.php` file includes the necessary database configuration. The migration tool will use Pachyderm's database service to connect to your database.

## 🤝 Contributing

1. **Fork** the repository
2. **Create** a feature branch (`git checkout -b feature/amazing-feature`)
3. **Commit** your changes (`git commit -m 'Add amazing feature'`)
4. **Push** to the branch (`git push origin feature/amazing-feature`)
5. **Open** a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📞 Support

- **Issues**: [GitHub Issues](https://github.com/aliengen/pachyderm-migration/issues)

---

**Made with ❤️ by the AlienGen team**
