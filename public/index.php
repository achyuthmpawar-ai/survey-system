<?php

use Slim\Factory\AppFactory;
use App\Controllers\HomeController;
use App\Controllers\AdminController;
use App\Middleware\AuthMiddleware;
use App\Models\Database;

require __DIR__ . '/../vendor/autoload.php';

// Initialize database
Database::get();

// Create Slim app
$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);
$app->addRoutingMiddleware();

// Public routes
$app->get('/', [HomeController::class, 'index']);
$app->get('/survey/{slug}', [HomeController::class, 'showSurvey']);
$app->post('/survey/{slug}/submit', [HomeController::class, 'submitSurvey']);

// Admin login (no auth required)
$app->get('/admin/login', [AdminController::class, 'loginForm']);
$app->post('/admin/login', [AdminController::class, 'login']);
$app->get('/admin/logout', [AdminController::class, 'logout']);

// Protected admin routes
$app->get('/admin/dashboard', [AdminController::class, 'dashboard'])->add(AuthMiddleware::class);
$app->post('/admin/surveys/create', [AdminController::class, 'createSurvey'])->add(AuthMiddleware::class);
$app->post('/admin/surveys/{id}/toggle', [AdminController::class, 'toggleSurvey'])->add(AuthMiddleware::class);
$app->get('/admin/surveys/{id}/results', [AdminController::class, 'viewResults'])->add(AuthMiddleware::class);
$app->get('/admin/surveys/{id}/download', [AdminController::class, 'downloadResults'])->add(AuthMiddleware::class);

$app->run();
