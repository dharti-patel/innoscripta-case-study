## News Aggregator Backend (Laravel)

### Setup
1. Copy `.env.example` to `.env` and set DB and API keys:
   - NEWSAPI_KEY, GUARDIAN_KEY, NYTIMES_KEY
2. Run migrations:
   php artisan migrate
3. Install dependencies:
   composer install
4. Start fetch scheduler (on server cron):
   * * * * * php /path-to-project/artisan schedule:run >> /dev/null 2>&1

### Endpoints
- GET /api/articles?q=search&source=newsapi,theguardian&from=2025-10-01&to=2025-10-16&category=business
- GET /api/sources

### Notes
- Adapters live in `app/Services/News/Adapters`.
- Add providers by implementing `SourceAdapterInterface`.
