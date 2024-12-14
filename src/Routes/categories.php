<?php

use App\Controllers\CategoriesController;
use App\Models\CategoriesModel;
use Slim\Routing\RouteCollectorProxy;
use Slim\App;

return function (App $app, $pdo) {
    $app->group('/categories', function (RouteCollectorProxy $group) use ($pdo) {
        $categoriesModel = new CategoriesModel($pdo);
        $categoriesController = new CategoriesController($categoriesModel);

        $group->get('/getAll', [$categoriesController, 'getAllCategories']);
        $group->get('/getById', [$categoriesController, 'getCategoryById']);
        $group->post('/create', [$categoriesController, 'createCategory']);
    //     $group->get('/getBySubcategory', [$categoriesController, 'getTransactionsBySubcategory']);
    //     $group->get('/getByType', [$categoriesController, 'getTransactionsByType']);
    //     $group->get('/getByDate', [$categoriesController, 'getTransactionsByDate']);
    //     $group->post('/update', [$categoriesController, 'updateTransaction']);
    //     $group->post('/delete', [$categoriesController, 'deleteTransaction']);
    });
};