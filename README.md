ProcessManagerBundle
=========================

Установка:
composer require ecredit/process-manager
============
============

Добавляем bundle:
/config/bundles.php

<?php

return [
    ...
    Ecredit\ProcessManagerBundle\EcreditProcessManagerBundle::class => ['all' => true],
];
============
============

Создаем миграцию:
php bin/console doctrine:migrations:diff

Применяем миграцию:
php bin/console doctrine:migrations:migrate
============
============

Создаем конфиг ecredit_process_manager.yaml
Пример:

ecredit_process_manager:
  service:
    name: 'v4-platform'
    instance_name: '%env(INSTANCE_NAME)%'
  commands:
    -
      command: "/usr/local/bin/php /srv/api/bin/console messenger:consume amqp_notification"
      threads: 5
      
============
============

В работе

Запустить supervisor
php bin/console ecredit:supervisor:run

Проверить статусы в консоли
php bin/console ecredit:supervisor:health

Проверить через API
/health/supervisor

