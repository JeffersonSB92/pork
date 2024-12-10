<?php

use App\Controllers\TransactionsController;
use App\Models\TransactionsModel;
use Slim\Routing\RouteCollectorProxy;
use Slim\App;

return function (App $app, $pdo) {
    $app->group('/transactions', function (RouteCollectorProxy $group) use ($pdo) {
        $transactionsModel = new TransactionsModel($pdo);
        $transactionsController = new TransactionsController($transactionsModel);

        $group->get('/getAll', [$transactionsController, 'getAll']);
    });
};