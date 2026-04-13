<?php

use Slim\Factory\AppFactory;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/config/database.php';

session_start();
define('BASE_URL', '/survey-system/public');
$app = AppFactory::create();
$app->setBasePath('/survey-system/public');

$app->addBodyParsingMiddleware();

// controllers
require __DIR__ . '/../app/controllers/AuthController.php';
require __DIR__ . '/../app/controllers/AdminController.php';
require __DIR__ . '/../app/controllers/SurveyController.php';

$auth = new AuthController();
$admin = new AdminController();
$survey = new SurveyController();

/* AUTH */
$app->get('/login', function ($req, $res) use ($auth) {
    return $auth->loginPage($req, $res);
});

$app->post('/login', function ($req, $res) use ($auth) {
    return $auth->login($req, $res);
});

$app->get('/logout', function ($req, $res) use ($auth) {
    return $auth->logout($req, $res);
});

/* ADMIN */
$app->get('/admin', function ($req, $res) use ($admin) {
    return $admin->dashboard($req, $res);
});

$app->post('/admin/upload', function ($req, $res) use ($admin) {
    return $admin->uploadCSV($req, $res);
});

$app->get('/admin/toggle/{id}', function ($req, $res, $args) use ($admin) {
    return $admin->toggle($req, $res, $args);
});

$app->get('/admin/download/{id}', function ($req, $res, $args) use ($admin) {
    return $admin->download($req, $res, $args);
});
$app->get('/admin', function ($req, $res) use ($admin) {
    return $admin->dashboard($req, $res);
});
/* SURVEY */
$app->get('/survey/{slug}', function ($req, $res, $args) use ($survey) {
    return $survey->show($req, $res, $args);
});

$app->post('/survey/{slug}', function ($req, $res, $args) use ($survey) {
    return $survey->submit($req, $res, $args);
});

$app->run();