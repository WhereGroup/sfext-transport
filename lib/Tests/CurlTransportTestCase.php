<?php


namespace Wheregroup\SymfonyExt\TransportBundle\Tests;


use PHPUnit\Framework\TestCase;
use Wheregroup\SymfonyExt\TransportBundle\Component\Http\CurlTransport;

class CurlTransportTestCase extends TestCase
{

    /** @var CurlTransport */
    public $transport;

    public function setUp()
    {
        $this->transport = new CurlTransport();
    }

    public function testBadDns()
    {
        $r = $this->transport->getUrl('http://kartoffelkatzenhoffentlichregistriertniemand.jemals.so.eine.komische.domain');
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $r);
        $this->assertFalse($r->isOk());
        $this->assertEmpty($r->getContent());
    }

    public function testSimpleHtml()
    {
        $r = $this->transport->getUrl('http://wheregroup.com');
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $r);
        $this->assertTrue($r->isOk());
        $this->assertNotEmpty($r->getContent());
        $trimmed = trim($r->getContent());
        $cleanStart = stripos($trimmed, '<!doctype') === 0 || stripos($trimmed, '<html') === 0;
        $cleanEnd = strripos($trimmed, '</html>') === (strlen($trimmed) - strlen('</html>'));
        $this->assertTrue($cleanStart);
        $this->assertTrue($cleanEnd);
    }
}
