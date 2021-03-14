<?php

use App\Controller\TodoItemController;
use App\Controller\TodoListController;
use Slim\App;
use Slim\Container;


require_once __DIR__ . '/../vendor/autoload.php';

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$con = new Container($configuration);
$app = new App($con);
$con = $app->getContainer();

$con['TodoListController'] = function ($con) {
    return new TodoListController($con);
};

$con['TodoItemController'] = function ($con) {
    return new TodoItemController($con);
};


//LIST ENDPOINTS
$app->get('/list', 'TodoListController:get');
$app->get('/list/{id}', 'TodoListController:get');
$app->post('/list', 'TodoListController:create');
$app->put('/list/{id}', 'TodoListController:put');
$app->delete('/list/{id}', 'TodoListController:delete');

//ITEMS ENDPOINTS
$app->post('/list/{id}/items', 'TodoItemController:create');
$app->put('/list/{id}/items/{itemId}', 'TodoItemController:put');
$app->delete('/list/{id}/items/{itemId}', 'TodoItemController:delete');
$app->get('/list/{id}/items', 'TodoItemController:get');

$app->run();
