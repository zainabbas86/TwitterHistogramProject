<?php
namespace App\twitterApp\Middleware;

use Silex\Application;
use App\twitterApp\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp;

/**
 * Middleware provides application-only Twitter auth and stores bearer in session
 * Class TwitterAuth
 * @package twitterApp\Middleware
 */
class TwitterAuth
{
    /**
     * Main middleware method. Calls Twitter OAuth API to obtain new access token for further requests 
     * and stores it in session. 
     * 
     * @param Request $request
     * @param Application $app
     * @throws Exception
     */
    public static function run(Request $request, Application $app)
    {
        if (!$app['session']) {
            throw new Exception('Session not initialized, please register SessionProvider');
        }

        if (!$app['session']->has('twitter.bearer')) {
            if (empty($app['parameters']['twitter'])) {
                throw new Exception('Twitter Authentication parameters not set');
            }
            $bearer = $app['parameters']['twitter']['key'] . ':' . $app['parameters']['twitter']['secret'];
            $bearerCredentials = base64_encode($bearer);

            $client = new GuzzleHttp\Client();
            try {
                $response = $client->post(
                    'https://api.twitter.com/oauth2/token',
                    [
                        'form_params' => ['grant_type' => 'client_credentials'],
                        'headers'     => [
                            'Authorization' => 'Basic ' . $bearerCredentials,
                            'Content-Type'  =>
                                'application/x-www-form-urlencoded;charset=UTF-8'
                        ]
                    ]
                );
            } catch (\Exception $e) {
                throw new Exception($e->getMessage());
            }
            $app['session']->set('twitter.bearer', json_decode($response->getBody(), 1)['access_token']);
        }
    }

    /**
     * Main middleware method. Calls Twitter OAuth API to obtain new access token for further requests
     * and stores it in session.
     *
     * @param Request $request
     * @param Application $app
     * @throws Exception
     */
    public static function runTest(Request $request, Application $app)
    {
        if (!$app['session']) {
            throw new Exception('Session not initialized, please register SessionProvider');
        }

        if (!$app['session']->has('twitter.bearer')) {
            if (empty($app['parameters']['twitter'])) {
                throw new Exception('Twitter Authentication parameters not set');
            }
            $bearer = $app['parameters']['twitter']['key'] . ':' . $app['parameters']['twitter']['secret'];

            $client = new GuzzleHttp\Client(['defaults' => [
                'verify' => false
            ]]);
            // Add Dummy Response here
            $response = '{"token_type":"bearer","access_token":"$bearer"}';
            $app['session']->set('twitter.bearer', json_decode($response, 1)['access_token']);
        }
    }
}
