<?php


namespace Wheregroup\SymfonyExt\TransportBundle\Tests;


use PHPUnit\Framework\TestCase;
use Wheregroup\SymfonyExt\TransportBundle\Component\ExternalRequest;

class ExternalRequestTest extends TestCase
{
    public static $simpleUrls = array(
        'https://osm-demo.wheregroup.com/',
        'https://osm-demo.wheregroup.com/beta-karotin?blaukraut=blau&katze=grau',
    );

    public static $basicAuthUrls = array(
        'https://fee:geheimnis@wald.de:8787/kartoffeln?katze=grau&blaukraut=blau',
    );

    public function testSimpleUrlReconstruction()
    {
        foreach (static::$simpleUrls as $testUrl) {
            $r = ExternalRequest::create($testUrl);
            $this->assertEquals($testUrl, $r->getUri());
        }
    }

    public function testBasicAuthReconstruction()
    {
        foreach (static::$basicAuthUrls as $testUrl) {
            $urlParts = parse_url($testUrl);
            $r = ExternalRequest::create($testUrl);
            $this->assertEquals($urlParts['user'], $r->headers->get('php-auth-user'));
            $this->assertEquals($urlParts['pass'], $r->headers->get('php-auth-pw'));
            $this->assertStringStartsWith('Basic ', $r->headers->get('authorization'));
        }
    }

    public function testParamAppend()
    {
        $baseUrl = '?kartoffel=runzelig';
        $r = ExternalRequest::create($baseUrl);
        $newParams = array(
            'qqq' => 'simpel',
            'rrr' => 'leer zeichen',
            'sss' => 'doppel:punkt',
            'ttt' => 'und&zeichen',
            'u u' => 'zu kodierender key!',
        );
        $r->query->add($newParams);
        foreach ($newParams as $newKey => $newValue) {
            $this->assertTrue($r->query->has($newKey));
            $this->assertEquals($r->query->get($newKey), $newValue);
        }
        $rawFragments = explode('&', implode('', array_slice(explode('?', $r->getUri()), 1)));
        foreach ($newParams as $newKey => $newValue) {
            $fragmentMatch = rawurlencode($newKey) . '=' . rawurlencode($newValue);
            $message = var_export($fragmentMatch, true) . " not found in query string";
            $this->assertTrue(in_array($fragmentMatch, $rawFragments, true), $message);
        }
    }

    public function testAutomaticQueryStringUpdate()
    {
        $params = array(
            'a' => '1',
        );
        $addParams = array(
            'b' => '2',
            'c' => '3',
        );
        $baseUrl = '?' . http_build_query($params, '', '&');
        $r = ExternalRequest::create($baseUrl);
        $this->assertEquals($r->server->get('QUERY_STRING'), substr($baseUrl, 1));
        $this->assertEquals($r->server->get('QUERY_STRING'), $r->getQueryString());
        $r->query->add($addParams);
        $this->assertEquals($r->server->get('QUERY_STRING'), $r->getQueryString());
    }
}
