# WIAM

## Запуск проекта

docker-compose up -d

docker-compose exec php bash

composer install

php yii migrate

## Работа проекта

### Добавление займа

curl -X POST http://localhost/requests -H 'Content-Type: application/json' -d '{"user_id": 1, "amount": 3000, "term": 30}'

### Запуск обработки заявки

curl -X GET http://localhost/processor?delay=5
