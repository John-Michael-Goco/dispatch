# Dispatch Management System

A modern dispatch management system built with Laravel and Vue.js, designed to streamline dispatch operations and improve efficiency.

## Tech Stack

- **Backend**: Laravel (PHP Framework)
- **Frontend**: Vue.js 3
- **CSS Framework**: Bootstrap 5 + Tailwind CSS
- **Build Tool**: Vite
- **Database**: MySQL
- **Development Environment**: XAMPP

## Prerequisites

- PHP >= 8.1
- Node.js >= 16
- Composer
- MySQL
- XAMPP (for local development)
- Git

## Installation

1. Clone the repository:
```bash
git clone [https://github.com/John-Michael-Goco/dispatch]
cd dispatch
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node.js dependencies:
```bash
npm install
```

4. Create a copy of the environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_dispatch
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Import the database:
```bash
mysql -u your_username -p db_dispatch < db_dispatch.sql
```

8. Run database migrations:
```bash
php artisan migrate
```

## Development

1. Start the Laravel development server:
```bash
php artisan serve
```

2. In a separate terminal, start the Vite development server:
```bash
npm run dev
```

The application will be available at `http://localhost:8000`

## Building for Production

1. Build the frontend assets:
```bash
npm run build
```

2. Optimize Laravel:
```bash
php artisan optimize
```

## Project Structure

```
dispatch/
├── app/                    # Application core
│   ├── Http/              # Controllers, Middleware, Requests
│   ├── Models/            # Eloquent models
│   ├── Notifications/     # Notification classes
│   └── Providers/         # Service providers
├── resources/             # Frontend resources
│   ├── js/               # JavaScript/Vue components
│   ├── sass/             # SASS styles
│   ├── css/              # CSS files
│   └── views/            # Blade templates
├── routes/               # Route definitions
├── public/              # Public assets
├── database/            # Migrations and seeders
├── config/              # Configuration files
└── tests/               # Automated tests
```

## Features

### Core Features
- User authentication and authorization
- Dispatch management and tracking
- Real-time updates and notifications
- Responsive design for all devices
- API endpoints for mobile integration

### Technical Features
- RESTful API architecture
- Vue.js components for dynamic UI
- Bootstrap 5 + Tailwind CSS for styling
- Database migrations and seeding
- Automated testing support

## API Documentation

The API documentation is available at `/api/documentation` when running the application locally.

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Coding Standards
- Follow PSR-12 coding standards for PHP
- Use ESLint for JavaScript/Vue.js code
- Write meaningful commit messages
- Include tests for new features

## Security

If you discover any security-related issues, please email [jmgoco416@gmail.com] instead of using the issue tracker.

## Support

For support, please:
1. Check the documentation
2. Search existing issues
3. Create a new issue if needed

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
