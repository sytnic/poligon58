# Установка Laravel 5.8
> Vagrant

Под Vagrant потребуется 2 файла: <br>
- Vagrantfile и 
- bootstrap.sh <br>

На основе bootstrap.sh <br>
в /etc/apache2/sites-enabled/default.conf автоматически будет настроено <br>
- имя сайта (оно может иметь дополнительный префикс), <br>
- корневая папка сайта.

В Windows имя сайта также нужно прописать в hosts .

> Запуск: vagrant up

Если потребуется старая версия composer (не понадобилась): <br>
https://stackoverflow.com/questions/64597051/how-to-downgrade-or-install-a-specific-version-of-composer

> Проверка сайта в браузере (подгружается из корневой папки)

> Установка Laravel

Этой командой из текущей папки устанавливается версия 5.8 в пустую подпапку (blog), она создастся автоматически.
    
    composer create-project --prefer-dist laravel/laravel blog "5.8.*"

После чего нужно вырезать и вставить все файлы в верхнюю папку из подпапки.

> Проверка в браузере

> Разрешения для папок
    
    sudo chmod 777 -R storage && sudo chmod 777 -R bootstrap/cache

Vagrant и VirtualBox жестко привязывают права к общей с Windows папке и ее вложенностям. Поменять права вряд ли удастся. <br><br>

> Установка дебаг-бара

Будет прописан в /vendor
    
При ошибке установки barryvdh/laravel-debugbar на Laravel 5.8 командой

        composer require barryvdh/laravel-debugbar --dev 

встала версия 3.0

        composer require barryvdh/laravel-debugbar:~3.0 --dev

 Debugbar включается/отключается в файле .env

    APP_DEBUG=true
        или
    APP_DEBUG=false

> Подстройка под MySQL
 
 Для MySQL/MariaDB нужно будет добавить следующие строки в <br>
 app\Providers\AppServiceProvider.php, <br>
    описание здесь: <br>
    Index Lengths & MySQL / MariaDB
    https://laravel.com/docs/5.7/migrations#indexes :

    
    ...
    use Illuminate\Support\Facades\Schema;
    ...    
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

> Создание нового соединения в Workbench

Заполнить:
- connection name: любое
- connection method: Standart TCP/IP over SSH
- SSH hostname: 127.0.0.1:2222 (Вагрант подключается через :2222)
- SSH username: vagrant (это особенность Вагранта, при подключении командой - vagrant ssh)
- SSH keyfile: отсутствует
- MySQL hostname: 127.0.0.1
- MySQL server port: 3306
- username: poligon58 (создавался в bootstrap.sh как $PROJECT_SLUG)

Вход через SSH в MySQL: 
- vagrant vagrant (особенности Вагранта по SSH)
- poligon58 poligon58 (особенности настройки $PROJECT_SLUG в bootstrap.sh в строке # Create a database user)

> Создание базы данных

Она уже создана из bootstrap.sh. Использовалась строка типа
    
    CREATE SCHEMA `poligon` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    
Кодировка utf8mb4 описана здесь:

    Index Lengths & MySQL / MariaDB
        https://laravel.com/docs/5.7/migrations#indexes :
    Laravel uses the utf8mb4 character set by default

> Настройка .env файла

    DB_DATABASE=poligon58
    DB_USERNAME=poligon58
    DB_PASSWORD=poligon58

Значения заданы с помощью $PROJECT_SLUG в bootstrap.sh
<hr>

# Git

> Отключение папок в .gitignore

    _example.md
    /.vagrant
    /storage
    /bootstrap/cache

> git init

> git add .

> git commit -m "..."

> git tag -a 0.1

После создания удаленного репозитория:

> git remote add origin https://github.com/...

> git push origin master

> git push 0.1

> git branch develop

> git checkout develop

<hr>








