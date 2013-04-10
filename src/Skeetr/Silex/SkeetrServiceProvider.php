<?php
/*
 * This file is part of the Skeetr package.
 *
 * (c) Máximo Cuadros <maximo@yunait.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skeetr\Silex;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class SkeetrServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['skeetr.worker_class'] = 'Skeetr\\Gearman\\Worker';
        $app['skeetr.worker'] = $app->share(function () use ($app) {
            $worker = new $app['skeetr.worker_class']();
            $worker->addServer($app['skeetr.host'], $app['skeetr.port']);

            return $worker;
        });

        $app['skeetr.client_class'] = 'Skeetr\\Client';
        $app['skeetr.client'] = $app->share(function () use ($app) {
            $client = new $app['skeetr.client_class']($app['skeetr.worker']);
            $client->setLogger($app['logger']);
            $client->setCallback(function() use ($app) { 
                $response = $app->handle(Request::createFromGlobals());
                return $response->getContent();
            });

            return $client;
        });

        $app['skeetr.host'] = '127.0.0.1';
        $app['skeetr.port'] = 4730;
    }

    public function boot(Application $app)
    {

    }
}