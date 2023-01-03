<!-- <div align="center">
<img src="https://raw.githubusercontent.com/TseplyaevAF/energy_faculty/main/public/assets/default/logo.png" >
</div> -->

![logo](https://raw.githubusercontent.com/TseplyaevAF/energy_faculty/main/public/assets/default/logo.png)

![laravel_version](https://img.shields.io/badge/Laravel-8.83.9-red)
![php_version](https://img.shields.io/badge/php-7.4-blue)
![commit_activity](https://img.shields.io/github/commit-activity/w/TseplyaevAF/energy_faculty)
                                                                       
# Информационный сервис ЭФ ЗабГУ

Данный проект является backend-частью сайта энергетического факультета и содержит следующее:
1. API
2. Реализацию личных кабинетов

### Установка проекта

- `composer update` либо `composer update —ignore-platform-req=ext-imagick` если будут ошибки
- `npm install`
- `npm run dev`
- Создать файл конфигурации `.env` и заполнить подключение к БД:
    - ```
        DB_CONNECTION=pgsql
        DB_HOST=127.0.0.1
        DB_PORT=5432
        DB_DATABASE=your_db_name
        DB_USERNAME=your_username
        DB_PASSWORD=your_password
        ```
- `php artisan key:generate`
- `php artisan storage:link`
- создать новую бд, назвав её energy_faculty, а затем выполнить `php artisan migrate`
- `php artisan serve`
- Чтобы создать аккаунт админа, выполните `php artisan db:seed --class=AdminUserSeeder`. После чего по пути `\storage\app\private` создастся файл `admin_password.txt` с данными для входа.

### Настройка для работы функций openssl в ОС Windows
1. Зайти в переменные среды
2. Под блоком "Системные переменные" нажать "создать":
    - Имя: OPENSSL_CONF
    - Значение: C:\php\extras\ssl\openssl.cnf
4. Обязательно перезагрузить компьютер

### Настройка для работы вебсокет-сервера
Вебсокет-сервер используется для раздела "События группы".
- Пример .env файла:
```
BROADCAST_DRIVER=pusher
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
# sync|database
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=1440
```
- Запуск: `php artisan websockets:serve`
> если QUEUE_CONNECTION равняется database, то должна быть запущена очередь: `php artisan queue:work`
____

> Также вы можете выполнить `php artisan migrate:fresh --seed` для заполнения БД тестовыми данными. В том числе будут созданы аккаунты для основных ролей приложения: Студент, Преподаватель, Сотрудник кафедры, Сотрудник УЦ, Сотрудник деканата.

> Логины и пароли к ним можно узнать здесь: `public/assets/users.json`. Логином считается электронная почта.

> **Данные для входа, а также номера телефонов являются фейковыми**

> В данный момент подтверждение email-адреса после регистрации выключено, так что для тестов можно вводить любую почту.
