<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Silex\Application\TwigTrait;

$app = new Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

$api = Atoz\Parser::LoadApi(__DIR__.'/../api.json', '/', '/Action', '/Object');

$mainController = new App\Controller\Main($app, $api);

$app->before(function () use($mainController) {
    $mainController->before();
});

$app->get('/action/{ref}', function ($ref) use($mainController) {
    return $mainController->Action($ref);
})->assert('ref', ".*");

$app->get('/object/{ref}', function ($ref) use($mainController) {
    return $mainController->Object($ref);
})->assert('ref', ".*");

$app->get('/', function() use($mainController) { 
    return $mainController->Index();
});

$app->error(function (\Exception $e, $code) use ($mainController) {
    return $mainController->Error($code, $e->getMessage());
});

return $app;