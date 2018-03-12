<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__ . '/../config/config.yml'));
$app->register(new Silex\Provider\SessionServiceProvider(), ['session.test' => getenv('TEST')]);
$app->register(new App\twitterApp\Services\DateTimeConverterService());

$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app['twig'] = $app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
});

$app->before('App\twitterApp\Middleware\TwitterAuth::run');

$app->get('/', 'App\twitterApp\Controller\MainController::main');

// I'm not sure this restriction for username is necessary,
// but did it assumed that this username is the same username to check histogram
$app->get('/hello/{username}', 'App\twitterApp\Controller\HelloController::hello')
    ->assert('username', '[a-zA-Z0-9_]{1,15}');

$app->get('/histogram/{username}', 'App\twitterApp\Controller\HistogramController::histogram')
    ->assert('username', '[a-zA-Z0-9_]{1,15}');

$app->error(
    function (\Exception $e, $code) {
        return new Response('Something went wrong.');
    }
);
return $app;
