# coachtech_market

## 環境構築
Dockerビルド

## 使用技術
php: 8.3
Laravel: 12.*
nginx: 1.21.1
mysql: 8.0.26

php artisan storage:link が必要です。

## バリデーション日本語

## .envのStripeをテストキーにする
STRIPE_KEY=pk_test_xxxxx
STRIPE_SECRET=sk_test_xxxxx

## Stripeインストール
$ docker-compose exec php bash
composer require stripe/stripe-php
composer dump-autoload

## storageにpng作成