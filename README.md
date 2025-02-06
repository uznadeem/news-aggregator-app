# New Aggregator App (Laravel 11)

This is a **Laravel 11** project that aggregates news articles from multiple sources (**NewsAPI.org, The Guardian, and The New York Times**), stores them in a database, and provides API endpoints for fetching and filtering articles.

## Features
- Fetch news from **NewsAPI.org, The Guardian, and NYT**.
- Store articles in a local database.
- Provide API endpoints to retrieve, search, and filter news.
- Run automatic article fetching using Laravel Scheduler.

---

## Installation

### 1. Clone the Repository
```sh
git clone https://github.com/your-repo/news-aggregator-app.git
cd news-aggregator-app
```

### 2. Install Dependencies
```sh
composer install
```

### 3. Create Environment File
Copy `.env.example` to `.env`:
```sh
cp .env.example .env
```

### 4. Generate Application Key
```sh
php artisan key:generate
```

### 5. Configure Database
Update the `.env` file with your database details:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=news_aggregator
DB_USERNAME=root
DB_PASSWORD=
```

Then run migrations:
```sh
php artisan migrate
```

### 6. Set API Keys
Add your API keys to the `.env` file:

```ini
NEWS_API_KEY=your_newsapi_key
GUARDIAN_API_KEY=your_guardian_key
NYT_API_KEY=your_nyt_key
```

### 7. Seed the Database
```sh
php artisan db:seed --class=SourcesTableSeeder
```

### 8. Fetch Initial News Articles
```sh
php artisan news:fetch
```

### 9. Start the Application
```sh
php artisan serve
```

---

## API Endpoints

### Get All Articles
```http
GET /articles
```
**Query Parameters:**
- `search`: Filter articles by title keyword.
- `category`: Filter by category (e.g., sports, tech).
- `source_id`: Filter by news source.
- `date`: Filter by published date.

**Example:**
```http
GET /articles?search=technology&category=science
```

### Get Single Article
```http
GET /articles/{id}
```

---

## Automating News Fetching

To fetch news every hour, set up Laravel Scheduler:
```sh
php artisan schedule:work
```

Or add this cron job:
```sh
* * * * * php /path-to-project/artisan schedule:run >> /dev/null 2>&1
```

---

## Deployment

### Run Optimizations
```sh
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Set Up Queues
```sh
php artisan queue:work
```

---

## License
This project is open-source and available under the [MIT License](LICENSE).