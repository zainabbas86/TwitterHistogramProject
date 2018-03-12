<?php

namespace App\twitterApp\Services;

use Pimple\Container;
use Silex\Application;
use Pimple\ServiceProviderInterface;
use DateTime, DateInterval;

/**
 * Class DateTimeConverterService
 * @package twitterApp\Services
 */
class DateTimeConverterService implements ServiceProviderInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $this->container = $container;

        $container['datetime.hour'] = $container->protect(
            function ($utcTime, $offset) {
                return $this->convert($utcTime, $offset);
            }
        );
    }

    /**
     * @param string $utcTime
     * @param int $offset
     * @return string
     */
    protected function convert($utcTime, $offset)
    {
        $createdAt = new DateTime($utcTime);
        $interval = new DateInterval('PT' . abs($offset) . 'S');

        if ($offset < 0) {
            $interval->invert = 1;
        }
        $createdAt->add($interval);

        return $createdAt->format('G');
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
        // do nothing
    }
}
