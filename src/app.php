<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Silex\Application\TwigTrait;

// Define these in a config file at some point.
$ref_separator = '/';
$object_prefix = $ref_separator.'Object';
$action_prefix = $ref_separator.'Action';


$app = new Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

$app['twig'] = $app->share($app->extend("twig", function (\Twig_Environment $twig, Silex\Application $app) use($object_prefix, $action_prefix) {
    $twig->addFilter(new Twig_SimpleFilter('parseLinks', function ($text) use($object_prefix, $action_prefix) {
         // Convert: #/Object/Foo/Bar# to <a href="/object/Foo/Bar" class="object">[/Object/Foo/Bar]</a>
         $text = preg_replace('/\#'.str_replace('/', '\/', $object_prefix).'([^\#]+)\#/', '<a href="/object$1" class="object">['.$object_prefix.'$1]</a>', $text);

         // Convert: !/Action/Foo/Bar! to <a href="/action/Foo/Bar" class="action">[/Action/Foo/Bar]</a>
         $text = preg_replace('/\!'.str_replace('/', '\/', $action_prefix).'([^\!]+)\!/', '<a href="/action$1" class="action">['.$action_prefix.'$1]</a>', $text);

         return $text;
    }));

    return $twig;
}));

$api = Atoz\Parser::LoadApi(__DIR__.'/../api.json', $ref_separator, $action_prefix, $object_prefix);

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