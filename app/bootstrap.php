<?php

// Require Composer Autoloader
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

// Create new Silex App
$app = new Silex\Application();

// App Configuration
$app['debug'] = true;

// Use Twig — @note: Be sure to install Twig via Composer first!
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'views'
));

// Use Doctrine — @note: Be sure to install Doctrine via Composer first!
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbhost' => 'localhost',
        'dbname' => 'testolpc',
        'user' => 'root',
        'charset' => 'utf8mb4',
        'password' => '', //Mysql password
    ),
));

// Use Repository Service Provider — @note: Be sure to install RSP via Composer first!
$app->register(new Knp\Provider\RepositoryServiceProvider(), array(
    'repository.repositories' => array(
        'db.users' => 'RNR\\Repository\\usersRepository',
        'db.laptops' => 'RNR\\Repository\\LaptopsRepository'
    )
));

//Define the base path for the media
$app['Inventory.base_url'] = '';
//Define the base path for the media
$app['Inventory.base_path'] = __DIR__ . '/../public_html' . $app['Inventory.base_url'];

// Path configuration
$app['paths'] = array(
    'root' => __DIR__ . DIRECTORY_SEPARATOR.'..'. DIRECTORY_SEPARATOR,
    'web' => __DIR__ . DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'public_html'.DIRECTORY_SEPARATOR,
);

//DOMPDF REQUIRES
// Composer's auto-loading functionality
require "../vendor/autoload.php";

// Use UrlGenerator Service Provider - @note: Be sure to install "symfony/twig-bridge" via Composer if you want to use the `url` & `path` functions in Twig
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

// Use Validator Service Provider - @note: Be sure to install "symfony/validator" via Composer first!
$app->register(new Silex\Provider\ValidatorServiceProvider());

// Use Form Service Provider - @note: Be sure to install "symfony/form" & "symfony/twig-bridge" via Composer first!
$app->register(new Silex\Provider\FormServiceProvider());

// Use UrlGenerator Service Provider - @note: Be sure to install "symfony/twig-bridge" via Composer if you want to use the `url` & `path` functions in Twig
$app->register(new Silex\Provider\SessionServiceProvider());

// Use Translation Service Provider because without it our form won't work
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
));

//Add pagination to the global $app variable
$app['twig']->addFilter(new \Twig_SimpleFilter('querystring', function ($arr) {
    $querystring = '';
    if (sizeof($arr) > 0) {
        $querystringItems = array();
    foreach ($arr as $k => $v) {
        $querystringItems[] = $k . '=' . urlencode($v);
    }
        $querystring = '?' . implode('&', $querystringItems);
    }
    return $querystring;
}));