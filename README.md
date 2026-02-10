# Tau

SPA-приложение для управления задачами: Laravel API + Vue 3.

## Stack

**Backend:**
- Laravel 12
- PHP 8.3
- PostgreSQL 16-alpine
- Redis
- Sanctum

**Frontend:**
- Vue 3
- TypeScript
- Composition API
- Vite
- Axios
- Pinia

## Setup

### Требования

- PHP 8.3
- Composer
- Node.js 22-alpine
- PostgreSQL 16-alpine
- Redis

### Установка

```bash
# Клонировать и перейти в проект
git clone https://github.com/gromsam/tau.git
cd tau

# Установить зависимости PHP 8.3
composer install

# Создать .env
cp .env.example .env
php artisan key:generate
```

## Docker
в
*Запуск*

```bash
docker compose up -d --build

# Создать БД и выполнить миграции
docker exec tau-app php artisan migrate:fresh --seed
```

Остановка: `docker compose down`.

Сервисы:
- **nginx** — http://localhost:8080
- **php** — PHP-FPM, код монтируется как `.:/var/www`
- **postgres** — порт 5432
- **redis** — порт 6379
- **node** — Vite dev на порту 5173

Переменные в `docker-compose.yml` (DB_HOST=postgres, REDIS_HOST=redis).

## API

| Метод | Endpoint | Описание |
|-------|----------|----------|
| POST | /api/login | Вход |
| POST | /api/logout | Выход |
| GET | /api/user | Текущий пользователь |
| GET | /api/tasks | Список задач |
| POST | /api/tasks | Создать задачу |
| PUT | /api/tasks/{id} | Обновить задачу |
| DELETE | /api/tasks/{id} | Удалить задачу |
| GET | /api/stats | Статистика |
| GET | /api/cat-fact | Факт о котах |

## Созданные пользователи

| email  | password      | is_admin        |
|--------|---------------|-----------------|
| admin@test.com   | admin     | true            |
| user1@test.com   | user1     | false           |
| user2@test.com   | user2     | false           |


## Тестирование
### Проведено в интерфейсе с каждым пользователем

Результат:
- Пользователь с правами admin видит все таски и общую метрику.
- Пользователь с правами default видят только свои таски и метрики
