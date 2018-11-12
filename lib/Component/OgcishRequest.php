<?php


namespace Wheregroup\SymfonyExt\TransportBundle\Component;


use Wheregroup\SymfonyExt\TransportBundle\Component\Request\CaseInsensitiveParameterBag;

/**
 * ExternalRequest extension that ignores case on all GET parameter
 * keys.
 *
 * This is a base behavior of all OGC specified services.
 *
 * E.g. see section 6.8.1 in http://portal.opengeospatial.org/files/?artifact_id=1441
 * Quote: "Parameter names shall not be case sensitive"
 */
class OgcishRequest extends ExternalRequest
{
    /**
     * @inheritdoc
     */
    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->query = new CaseInsensitiveParameterBag($this->query->all());
    }
}
