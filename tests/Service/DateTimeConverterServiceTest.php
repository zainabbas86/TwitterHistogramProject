<?php
use Silex\WebTestCase;

class DateTimeConverterServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForConvert
     */
    public function testConvert($params, $expected)
    {
        $service = new App\Service\DateTimeConverterService();

        $convert = function ($time, $offset) {
            return $this->convert($time, $offset);
        };

        $wrapper = $convert->bindTo($service, $service);
        $result = $wrapper($params[0], $params[1]);
        $this->assertEquals($result, $expected);

    }

    public function dataForConvert()
    {
        return [
            [
                ['Wed Mar 30 07:18:39 +0000 2016', 21600],
                13
            ],
            [
                ['Fri Apr 01 19:16:42 +0000 2016', -14400],
                15
            ],
            [
                ['Mon Mar 05 22:08:25 +0000 2007', -14400],
                18
            ]
        ];
    }
}
