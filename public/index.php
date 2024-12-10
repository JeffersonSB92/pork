<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../database.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

(require __DIR__ . '/../src/Routes/transactions.php')($app, $pdo);

$app->run();