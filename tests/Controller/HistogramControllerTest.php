<?php
use Silex\WebTestCase;

class HistogramControllerTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require getenv('KERNEL_DIR') . '/src/app.php';
        $app['session.test'] = true;

        return $app;
    }

    /**
     * @dataProvider positiveDataForHist
     */
    public function testHistPositive($username)
    {
        $client = $this->createClient();
        $client->request('GET', '/histogram/' . $username);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode(), 'Api should return 200 code');
        $this->assertJson($response->getContent(), 'Api should return json');
        $result = json_decode($response->getContent(), 1);
        foreach ($result as $hour => $value) {
            $this->assertInternalType('int', $value, 'Values should be integer');
            $this->assertGreaterThanOrEqual(0, $value, 'Values should be non-negative');
            $this->assertInternalType('int', $hour, 'Keys should be integer');
            $this->assertLessThanOrEqual('23', $hour, 'Hour couldn\'t be greater than 23');
            $this->assertGreaterThanOrEqual('0', $hour, 'Hour couldn\'t be less than 0');
        }
    }

    public function positiveDataForHist()
    {
        return [
            ['zain'],
            ['testUser']
        ];
    }

    /**
     * @dataProvider negativeDataForHist
     * NOTE: That's strange, but Twitter Api returns 401 Unauthorized for non-existent username.
     */
    public function testHistNegative($username)
    {
        $client = $this->createClient();
        $client->request('GET', '/histogram/' . $username);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode(), 'Api should return 404 code');
        $this->assertEquals(
            'Something went wrong.',
            $response->getContent(),
            'Api should return "Something went wrong"'
        );
    }

    public function negativeDataForHist()
    {
        return [
            ['zain1!'],
            ['testUserHasfailed']
        ];
    }
}
