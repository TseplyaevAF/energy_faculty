## Установка pgAdmin
1. Скачать и установить: https://disk.yandex.ru/d/DfjP_zWtteunVw
2. В файле `C:\Program Files\PostgreSQL\13\data\postgresql.conf` изменить `scram-sha-256` на `md5` в `password_encryption`
3. В файле `C:\Program Files\PostgreSQL\13\data\pg_hba.conf` также изменить везде на `md5`
4. Проделать след. действия: https://sun9-40.userapi.com/impg/hT-MMJvyaQVwzQOapF0XwPu_AemSx1fpHh2l4g/5XDmx7KxRXk.jpg?size=722x388&quality=96&sign=5f5c1b3c764ad6460feb3a65ee142484&type=album

## Установка xampp
1. Скачать и установить https://www.apachefriends.org/ru/index.html
2. Пошаманить в след. файлах (куда ж без этого):
    1. В файле `xampp\php\php.ini` раскомментировать: `extension=pdo_pgsql, extension=pgsql`
    2. В `xampp\apache\conf\httpd.conf` добавить строчку: LoadFile `"C:\Program Files\PostgreSQL\13\bin\libpq.dll"`
    3. В конец файла `C:\xampp\apache\conf\extra\httpd-vhosts.conf` вставить:
        ```
        <VirtualHost energy_faculty.com:80>
        DocumentRoot "C:\xampp\htdocs\energy_faculty\public"
            ServerName energy_faculty.com
            ServerAlias www.energy_faculty.com
            <Directory "c:/xampp/htdocs/energy_faculty/public">
            Require all granted
        </Directory>
        </VirtualHost>
        ```
    4. В конец файла `C:\Windows\System32\drivers\etc\hosts` вставить: `127.0.0.1 www.energy_faculty.com energy_faculty.com`

## Установка проекта
1. `git clone https://github.com/TseplyaevAF/energy_faculty.git`
2. Скачать и установить composer: https://getcomposer.org/
3. Зайди в папку с проектом и в консоль вписать: `composer update`
4. Скопировать файл *.env.example*, переименовав его в просто *.env* и заполнить данные подключения к БД


**Также нужно добавить в переменные среды**
https://sun9-68.userapi.com/impg/qOxrpbg2Ht8rhW_6R8J3gfSreTkxeVbr0P_3cg/PvuFs5VZPlM.jpg?size=527x501&quality=96&sign=fbc92fc8cff7d5bfe2314cc6b6cd0be3&type=album
