<!-- <div align="center">
<img src="https://raw.githubusercontent.com/TseplyaevAF/energy_faculty/main/public/assets/default/logo.png" >
</div> -->

![logo](https://raw.githubusercontent.com/TseplyaevAF/energy_faculty/main/public/assets/default/logo.png)

![laravel_version](https://img.shields.io/badge/Laravel-8.83.9-red)
![php_version](https://img.shields.io/badge/php-%5E7.3%7C%5E7.4%7C%5E8.1.2-blue)
![commit_activity](https://img.shields.io/github/commit-activity/w/TseplyaevAF/energy_faculty)
                                                                       
# Информационный сервис ЭФ ЗабГУ

Данный проект является backend-частью сайта энергетического факультета и содержит следующее:
1. API
2. Реализацию личных кабинетов

### Установка проекта

- `composer update` либо `composer update —ignore-platform-req=ext-imagick` если будут ошибки
- `npm install`
- `npm run dev`
- Создать файл конфигурации `.env` и заполнить подключение к базе данных
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
- `php artisan migrate`
- `php artisan serve`

> Также вы можете выполнить `php artisan migrate:fresh --seed` для заполнения БД тестовыми данными. В том числе будут созданы аккаунты для основных ролей приложения: Студент, Преподаватель, Сотрудник кафедры, Сотрудник УЦ, Сотрудник деканата.

> Логины и пароли к ним можно узнать здесь: `public/assets/users.json`. Логином считается электронная почта.

> **Данные для входа, а также номера телефонов являются фейковыми**

> В данный момент подтверждение email-адреса после регистрации выключено, так что для тестов можно вводить любую почту.
