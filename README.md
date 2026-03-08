# coachtech_market

## 環境構築
Dockerビルド
git clone

## 使用技術
php: 8.3
Laravel: 12.*
nginx: 1.21.1
mysql: 8.0.26

php artisan storage:link が必要です。

## メール認証は mailtrap 使用
## .env の mail 部分を mailtrap 用に変える
MAIL_MAILER="smtp"
MAIL_SCHEME=null
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME="サンドボックスのユーザーネーム"
MAIL_PASSWORD="サンドボックスのパスワード"
MAIL_FROM_ADDRESS="no-reply@example"
MAIL_FROM_NAME="${APP_NAME}"

## .env
SESSION_DRIVER=file


## Stripeインストール
docker-compose exec php bash
composer require stripe/stripe-php
composer dump-autoload

## Stripe テストキー設定
STRIPE_KEY="xxxx"
STRIPE_SECRET="xxxx"

## 仕様説明（コーチの許可あり）
・Sold商品はクリックできない
・出品後はトップページへ遷移
・購入後はトップページへ遷移

・プロフィール未登録ユーザーは出品、購入、マイページ、コメントでプロフィール設定画面へ遷移

## Dusk インストール
composer install
php artisan dusk:install

docker-compose exec php bash
composer require --dev laravel/dusk
php artisan dusk:install

## .env.dusk.local の設定
APP_ENV=local
APP_URL=http://localhost:8080

DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite

## Duskの実行
php artisan dusk