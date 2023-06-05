## How to run?

-   Clone this repository `git clone https://github.com/shahadat015/referral.git`
-   Install composer dependencies `composer install`
-   Copy example env file and rename .env `cp .env.example .env`
-   Generate Application Key `php artisan key:generate`
-   Change your environment variables

```
APP_URL="http://localhost:8000"
QUEUE_CONNECTION=database
DB_DATABASE=referrals
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=user_username
MAIL_PASSWORD=your_password
```

-   Migrate tables & seed database `php artisan migrate --seed`
-   Install npm dependencies `npm install`
-   Run queue worker `php artisan queue:work`
-   Run Project `php artisan serve` and `npm run dev` and [Go To http://localhost:8000](http://localhost:8000)
-   You'r done!

-   Run test for Referral feature `./vendor/bin/pest tests/Feature/ReferralTest.php`
