<?php
use Silex\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function createApplication() {
        $app = require getenv('KERNEL_DIR') . '/app.php';
        $app['session.test'] = true;
        return $app;
    }

    public function testMainPage()
    {
        $client = $this->createClient();
        $client->request('GET', '/');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode(), 'Api should return 404 code');
        $this->assertEquals('Try /hello/:name', $response->getContent(),'Api should return "Try /hello/:name"');
    }
}
