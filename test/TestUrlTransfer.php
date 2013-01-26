<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'test/FakeCurlApi.php';
require_once 'pages/UrlTransfer.php';

class TestUrlTransfer extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $curlApi = new FakeCurlApi();
        $url = 'http://bitsavers.org/Whatsnew.txt';
        $transfer = new UrlTransfer($url, $curlApi);
        $this->assertNotNull($transfer);
    }

    public function testGet()
    {
        $curlApi = new FakeCurlApi();
        $url = 'http://bitsavers.org/Whatsnew.txt';
        $destination = '../private/Whatsnew.txt';
        $transfer = new UrlTransfer($url, $curlApi);
        $transfer->get($destination);
        $this->assertTrue($curlApi->initCalled);
        $this->assertTrue($curlApi->setoptCalled);
        $this->assertTrue($curlApi->execCalled);
        $this->assertTrue($curlApi->getinfoCalled);
    }
}
