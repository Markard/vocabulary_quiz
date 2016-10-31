## Приложение для тестирования перевода слова.

### Используемые технологии:
* PHP 7
    * Angular 1
    * Symfony 3.1
    * composer
* MySQL
* NodeJs 4

### Установка:

1) Заходим в директорию с проектом
```bash
cd vocabulary_quiz
```
2) Устанавливаем backend зависимости. Вас попросят установить параметры связанные с рабочим окружением. Все значения кроме: _database_host_, _database_name_, _database_user_, _database_password_ можно оставить по умолчанию.
```bash
cd backend/ && composer install && cd ../
```
3) Устанавливаем frontend зависимости.
```bash
cd frontend && npm install && node_modules/bower/bin/bower install && cd ../
```
4) Выполняем миграции. Они создадут базовую схему бд и заполнят данными.
```bash
php backend/bin/console doctrine:migrations:migrate
```

### Запуск сервера:

1) Запускаем сервер для backend
```bash
php backend/bin/console server:start 127.0.0.1:8001 --force
```
2) Запускаем сервер для frontend
 ```bash
node frontend/node_modules/http-server/bin/http-server frontend -p8002
```

### Пояснения:
* [Документация по API](http://127.0.0.1:8001)
* [Запуск приложения](http://127.0.0.1:8002/#/)



