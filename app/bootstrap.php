<?php

//set date time zone
date_default_timezone_set("America/Managua");

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
        'dbname' => 'laatsteversieolpc', //OLPC_test1 or laatsteversieolpc
        'user' => 'root',
        'charset' => 'utf8mb4',
        'password' => '', //Mysql password
    ),
));

// Use Repository Service Provider — @note: Be sure to install RSP via Composer first!
$app->register(new Knp\Provider\RepositoryServiceProvider(), array(
    'repository.repositories' => array(
        'db.laptops' => 'RNR\\Repository\\LaptopsRepository',
        'db.models' => 'RNR\\Repository\\ModelsRepository',
        'db.movements_types' => 'RNR\\Repository\\Movements_typesRepository',
        'db.movements' => 'RNR\\Repository\\MovementsRepository',
        'db.people' => 'RNR\\Repository\\PeopleRepository',
        'db.performs' => 'RNR\\Repository\\PerformsRepository',
        'db.places_dependencies' => 'RNR\\Repository\\PlacesDependenciesRepository',
        'db.place_types' => 'RNR\\Repository\\PlacestypesRepository',
        'db.places' => 'RNR\\Repository\\PlacesRepository',
        'db.profiles' => 'RNR\\Repository\\ProfilesRepository',
        'db.school_infos' => 'RNR\\Repository\\SchoolinfosRepository',
        'db.statuses' => 'RNR\\Repository\\StatusesRepository',
        'db.users' => 'RNR\\Repository\\UsersRepository',
    )
));

//Define the base path for the media
$app['Inventory.base_url'] = '/';
//Define the base path for the media
$app['Inventory.base_path'] = __DIR__ . '/../public_html' . $app['Inventory.base_url'];

// Path configuration
$app['paths'] = array(
    'root' => __DIR__ . DIRECTORY_SEPARATOR.'..'. DIRECTORY_SEPARATOR,
    'web' => __DIR__ . DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'public_html'.DIRECTORY_SEPARATOR,
);

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
