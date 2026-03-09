# coachtech_market


## 使用技術
php: 8.3  
Laravel: 12.*  
nginx: 1.21.1  
mysql: 8.0.26

### ログイン認証技術
    fortify
### バリデーション技術
    formrequest
### 決済技術
    Stripe
### メール認証技術
    mailtrap


## 環境構築
git clone  
PC上でDockerアプリを起動  

#### Docker設定
docker-compose up -d --build  
docker-compose exec php bash  

#### Laravelセットアップ
composer install  
cp .env.example .env

##### APP_KEY の作成
php artisan key:generate

##### 変更権限の付与
exit  
sudo chmod -R 777 *  
code .

##### .env の書き変え
APP_LOCALE=ja  
APP_FAKER_LOCALE=ja_JP

DB_CONNECTION=mysql  
DB_HOST=mysql  
DB_PORT=3306  
DB_DATABASE=laravel_db  
DB_USERNAME=laravel_user  
DB_PASSWORD="xxx" <- 記入

SESSION_DRIVER=file

MAIL_MAILER="smtp"  
MAIL_HOST=sandbox.smtp.mailtrap.io  
MAIL_USERNAME="xxx" <- 記入  
MAIL_PASSWORD="xxx" <- 記入  
MAIL_FROM_ADDRESS="no-reply@example"

##### .env に追記　Stripeテストキー設定
STRIPE_KEY="xxx" <- 記入  
STRIPE_SECRET="xxx" <- 記入

#### Stripeインストール
docker-compose exec php bash  
composer require stripe/stripe-php  
composer dump-autoload

#### Dusk インストール
composer require --dev laravel/dusk  
php artisan dusk:install

##### Duskの実行
php artisan dusk

#### ストレージディレクトリと公開ディレクトリの結び付け
php artisan storage:link

#### ダミーデータ作成
php artisan migrate  
php artisan db:seed

## 仕様説明
### コーチの許可あり
・Sold商品はクリックできない  
・出品後はトップページへ遷移  
・購入後はトップページへ遷移

### コーチから可不可の判断できないと伺った仕様
・プロフィール未登録ユーザーは出品、購入、マイページ、コメントでプロフィール設定画面へ遷移