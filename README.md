# Invoice Management System for Restaurant

A **modern, scalable, and maintainable web-based system** for managing invoices, orders, and menus in restaurants. Designed with **Laravel 11**, Tailwind CSS, and MySQL, it provides a clean architecture, modular services, and ready-to-use features for admins and staff.

---

## 🏗 Architecture Overview

The system follows **Clean Architecture** and **Service-Repository patterns** for maintainability and scalability:

* **Controllers** handle HTTP requests and responses.
* **Services** contain business logic, validation, and orchestration.
* **Repositories** handle data access with Eloquent models.
* **Cache Services** optimize performance for frequently accessed data.
* **Events & Listeners** provide hooks for model changes.
* **Blade + Tailwind CSS** for modern, responsive UI.
* **Dockerized development environment** for consistent deployment.

**Flow Example**:

```
HTTP Request -> Controller -> Service -> Repository -> Database
                             -> Cache Service -> Response
```

---

## 🔹 Key Features

* **Role-Based Authentication**: Admins and waiters with secure login (Laravel Breeze/Sanctum).
* **Invoice Management**: Create, view, download (PDF) invoices.
* **Order Management**: Real-time tracking of customer orders.
* **Menu Management**: CRUD operations for categories, menu items.
* **Reporting**: Sales summaries, daily/weekly reports.
* **File Uploads**: Campaign or menu images stored via polymorphic media model.
* **Caching Layer**: Optimized read operations for dashboards and lists.
* **Event-Driven Updates**: Dispatch model change events for audit or notifications.

---

## 🛠 Tech Stack

| Layer           | Technology                      |
| --------------- | ------------------------------- |
| Backend         | PHP 8.2+, Laravel 11            |
| Frontend        | Blade, Tailwind CSS             |
| Database        | MySQL 8+                        |
| Caching         | Redis (optional)                |
| Dev Environment | Docker + Docker Compose         |
| Testing         | PHPUnit, Pest (optional)        |
| Deployment      | Nginx, Docker, AWS/DigitalOcean |

---

## ⚙ Installation & Setup (Senior Level)

### 1. Clone & Install Dependencies

```bash
git clone https://github.com/thaeshwesin29/Invoice-Management-System-For-Restaurant.git
cd Invoice-Management-System-For-Restaurant
composer install
npm install
cp .env.example .env
php artisan key:generate
```

### 2. Configure Environment

* `.env` settings for **DB, Cache (Redis optional), Mail, Storage**.
* Enable **queue driver** for async notifications or exports.

### 3. Migrate & Seed

```bash
php artisan migrate --seed
```

### 4. Compile Assets

```bash
npm run dev       # development
npm run build     # production
```

### 5. Run Application

```bash
php artisan serve
```

---

## 🔍 Testing

* **Unit tests** for services and repositories.
* **Feature tests** for HTTP endpoints.
* Example:

```bash
php artisan test
```

* Follow **TDD principles**: Business logic tested via services, not controllers.

---

## 💡 Design Considerations for Senior Developers

* **Service-Repository Pattern**: Promotes separation of concerns.
* **Polymorphic Media Handling**: Single `Media` model handles images/videos for multiple entities.
* **Dynamic Event Dispatching**: `dispatchModelChangedEvent($entity->id, 'update')` triggers audit/log updates.
* **Caching**: Dashboard performance optimized with Redis cache layer.
* **Code Quality**: PSR-12 compliant, Laravel helper functions, and strict type hints.

---

## 🚀 Deployment

1. Build Docker images:

```bash
docker compose build
```

2. Run containers:

```bash
docker compose up -d
```

3. Configure **Nginx/Apache** for production.
4. Set **.env APP\_ENV=production**, **APP\_DEBUG=false**.
5. Run migrations and seeders in production:

```bash
docker compose exec app php artisan migrate --force
```

6. Setup **cron jobs** for scheduled tasks like cache clearing or invoice notifications.

---

## 🤝 Contributing

* Follow **PSR-12**, **Clean Architecture**, **Laravel conventions**.
* Create feature branches: `feature/<name>` or bugfix branches: `bugfix/<name>`.
* Use **GitHub Pull Requests** for code reviews.
* Write **tests** for all new features.

---

## 📄 License

MIT License. See [LICENSE](LICENSE).
