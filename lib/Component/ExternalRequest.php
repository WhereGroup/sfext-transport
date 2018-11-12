<?php


namespace Wheregroup\SymfonyExt\TransportBundle\Component;


use Symfony\Component\HttpFoundation\Request;
use Wheregroup\SymfonyExt\TransportBundle\Component\Request\ServerBagShim;

class ExternalRequest extends Request
{
    /**
     * @inheritdoc
     */
    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->server = new ServerBagShim($this->server->all(), $this->query);
    }
}
