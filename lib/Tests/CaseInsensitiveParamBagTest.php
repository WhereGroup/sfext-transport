<?php


namespace Wheregroup\SymfonyExt\TransportBundle\Tests;


use PHPUnit\Framework\TestCase;
use Wheregroup\SymfonyExt\TransportBundle\Component\Request\CaseInsensitiveParameterBag;


class CaseInsensitiveParamBagTest extends TestCase
{
    public function testCiRemove()
    {
        $initParams = array(
            'KATZE' => 'grau',
        );
        $bag = new CaseInsensitiveParameterBag($initParams);
        $this->assertTrue($bag->has('katze'));
        $this->assertTrue($bag->has('KATZE'));
        $lcKeys = array_map('strtolower', array_keys($bag->all()));
        $this->assertTrue(in_array('katze', $lcKeys));
        $bag->remove('KaTzE');
        $this->assertFalse($bag->has('katze'));
        $this->assertFalse($bag->has('KATZE'));
        $lcKeys = array_map('strtolower', array_keys($bag->all()));
        $this->assertFalse(in_array('katze', $lcKeys));
    }

    public function testCiAdd()
    {
        $bag = new CaseInsensitiveParameterBag();
        $this->assertFalse($bag->has('katze'));
        $this->assertFalse($bag->has('KATZE'));
        $this->assertFalse($bag->has('KaTzE'));
        $this->assertSame(null, $bag->get('KATZE', null));
        $bag->add(array(
            'kAtZe' => 'grau',
        ));
        $this->assertTrue($bag->has('katze'));
        $this->assertTrue($bag->has('KATZE'));
        $this->assertTrue($bag->has('KaTzE'));
        $this->assertSame('grau', $bag->get('KATZE'));
    }

    public function testCiSet()
    {
        $bag = new CaseInsensitiveParameterBag();
        $this->assertFalse($bag->has('katze'));
        $this->assertFalse($bag->has('KATZE'));
        $this->assertFalse($bag->has('KaTzE'));
        $this->assertSame(null, $bag->get('KATZE', null));
        $bag->set('kAtZe', 'grau');
        $this->assertTrue($bag->has('katze'));
        $this->assertTrue($bag->has('KATZE'));
        $this->assertTrue($bag->has('KaTzE'));
        $this->assertSame('grau', $bag->get('KATZE'));
    }

    public function testCiReplaceViaSet()
    {
        $initParams = array(
            'KATZE' => 'grau',
        );
        $bag = new CaseInsensitiveParameterBag($initParams);
        $this->assertTrue($bag->has('katze'));
        $this->assertTrue($bag->has('KATZE'));
        $this->assertSame('grau', $bag->get('KaTzE'));
        $bag->set('katZE', 'schwarz');
        $this->assertTrue($bag->has('katze'));
        $this->assertTrue($bag->has('KATZE'));
        $this->assertSame('schwarz', $bag->get('KaTzE'));
    }

    public function testCiReplaceViaAdd()
    {
        $initParams = array(
            'KATZE' => 'grau',
        );
        $updateParams = array(
            'katZE' => 'schwarz',
        );
        $bag = new CaseInsensitiveParameterBag($initParams);
        $this->assertTrue($bag->has('katze'));
        $this->assertTrue($bag->has('KATZE'));
        $this->assertSame('grau', $bag->get('KaTzE'));
        $bag->add($updateParams);
        $this->assertTrue($bag->has('katze'));
        $this->assertTrue($bag->has('KATZE'));
        $this->assertSame('schwarz', $bag->get('KaTzE'));
    }

    public function testLastKeyCaseIsPreserved()
    {
        $bag = new CaseInsensitiveParameterBag();
        $bag->set('katze', 'grau');
        $this->assertTrue(in_array('katze', array_keys($bag->all())));
        $this->assertFalse(in_array('KATZE', array_keys($bag->all())));
        $bag->set('KATZE', 'grau');
        $this->assertTrue(in_array('KATZE', array_keys($bag->all())));
        $this->assertFalse(in_array('katze', array_keys($bag->all())));
    }
}
