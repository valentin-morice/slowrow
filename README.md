# Slowrow – Anchorless Job Interview

## Prerequisites

- [PHP 8.2+](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) and [npm](https://www.npmjs.com/) (for the SPA)
- [bun](https://bun.sh/) (for the SPA, optional if you use npm/yarn/pnpm)

## Setup

1. **Clone the repository:**

```sh
git clone <your-repo-url>
cd slowrow
```

2. **Install Laravel API dependencies:**

```sh
cd api
composer install
cp .env.example .env
php artisan key:generate
```

3. **Install SPA dependencies:**

```sh
cd ../spa-react
bun install
```

or use `npm install` if you prefer.

## Starting the Development Servers

First, make sure the script has executable permissions:

```sh
chmod +x slowrow.sh
./slowrow.sh
```

This script will:

- Start the Laravel API server (php artisan serve)
- Start the React SPA dev server (npm run dev in the spa directory)

Both servers will run in the background. Press Ctrl+C to stop them.

## Running Laravel Tests

To run the backend (Laravel) tests:

```sh
cd api
php artisan test
```
