<?php
/*
 * This file is part of the Skeetr package.
 *
 * (c) Máximo Cuadros <maximo@yunait.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skeetr\Tests\Silex;
use Skeetr\Silex\SkeetrServiceProvider;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class SkeetrServiceProviderTest extends TestCase {
    public function setUp()
    {
        if (!class_exists('Skeetr\\Client')) {
            $this->markTestSkipped('Skeetr was not installed.');
        }

        $this->app = new Application();
        $this->app->register(new SkeetrServiceProvider());
    }

    public function testRegister()
    {
        $this->assertInstanceOf('Skeetr\Gearman\Worker', $this->app['skeetr.worker']);
        $this->assertInstanceOf('Skeetr\Client', $this->app['skeetr.client']);

        $this->assertSame($this->app['skeetr.worker'], $this->app['skeetr.client']->getWorker());

        $servers = $this->app['skeetr.worker']->getServers();
        $this->assertSame('127.0.0.1:4730', $servers[0]);
    }

    public function testCallback()
    {
        $callback = $this->app['skeetr.client']->getCallback();
        $this->assertInstanceOf('Closure', $callback);

        $app = $this->app;
        $app->get('/', function () use ($app) {
            return 'testCallback';
        });

        $_SERVER['REQUEST_URI'] = '/';
        $this->assertSame('testCallback', $callback());
    }
}