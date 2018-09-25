<?php


namespace Wheregroup\SymfonyExt\TransportBundle\Component;


class RequestOptions
{
    public $timeout = 20;
    public $connectTimeout = 4;
    public $followRedirects = true;

    public function __construct($timeout = 20, $followRedirects = true, $connectTimeout = 4)
    {
        $this->timeout = $timeout;
        $this->followRedirects = $followRedirects;
        $this->connectTimeout = $connectTimeout;
    }

    public static function makeDefaults()
    {
        return new static();
    }
}
