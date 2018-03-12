<?php
namespace App\twitterApp\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HelloController
 * @package App\Controller
 */
class HelloController
{
    /**
     * @param Request $request
     * @param Application $app
     * @param string $username
     * @return Response
     */
    public function hello(Request $request, Application $app, $username)
    {
        return new Response('Hello ' . $app->escape($username));
    }
}
