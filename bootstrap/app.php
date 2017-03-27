<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/



/*
|--------------------------------------------------------------------------
| Configure Log
|--------------------------------------------------------------------------
|
| Do NOT change the order of the handlers added to Monolog!
| The code allows us to have separate file for every log level
|
*/
$app->configureMonologUsing(function($monolog) {
	$bubble = false;
    $monolog->pushHandler(new Monolog\Handler\StreamHandler(storage_path("/logs/debug.log"), Monolog\Logger::DEBUG, $bubble));
    $monolog->pushHandler(new Monolog\Handler\StreamHandler(storage_path("/logs/info.log"), Monolog\Logger::INFO, $bubble));
    $monolog->pushHandler(new Monolog\Handler\StreamHandler(storage_path("/logs/notice.log"), Monolog\Logger::NOTICE, $bubble));
    $monolog->pushHandler(new Monolog\Handler\StreamHandler(storage_path("/logs/warning.log"), Monolog\Logger::WARNING, $bubble));
    $monolog->pushHandler(new Monolog\Handler\StreamHandler(storage_path("/logs/error.log"), Monolog\Logger::ERROR, $bubble));
    $monolog->pushHandler(new Monolog\Handler\StreamHandler(storage_path("/logs/critical.log"), Monolog\Logger::CRITICAL, $bubble));
    $monolog->pushHandler(new Monolog\Handler\StreamHandler(storage_path("/logs/alert.log"), Monolog\Logger::ALERT, $bubble));
    $monolog->pushHandler(new Monolog\Handler\StreamHandler(storage_path("/logs/emergency.log"), Monolog\Logger::EMERGENCY, $bubble));
});

return $app;
