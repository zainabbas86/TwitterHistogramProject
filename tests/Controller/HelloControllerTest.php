<?php
use Silex\WebTestCase;

/**
 * Class HelloControllerTest
 */
class HelloControllerTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require getenv('KERNEL_DIR') . '/app.php';
        $app['session.test'] = true;

        return $app;
    }

    /**
     * @dataProvider positiveDataForHello
     */
    public function testHelloPositiv($expected)
    {
        $client = $this->createClient();
        $client->request('GET', '/hello/' . $expected);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode(), 'Api should return 200 code');
        $this->assertEquals(
            'Hello ' . $expected,
            $response->getContent(),
            'Api should return "Hello ' . $expected . ''
        );
    }

    public function positiveDataForHello()
    {
        return [
            ['letch1k'],
            ['barackobama']
        ];
    }

    /**
     * @dataProvider negativeDataForHello
     */
    public function testHelloNegative($expected)
    {
        $client = $this->createClient();
        $client->request('GET', '/hello/' . $expected);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode(), 'Api should return 404 code');
        $this->assertEquals(
            'Something went wrong.',
            $response->getContent(),
            'Api should return "Something went wrong"'
        );
    }

    public function negativeDataForHello()
    {
        return [
            ['letch1!'],
            ['barackobamaisthebestemperorever']
        ];
    }
}
