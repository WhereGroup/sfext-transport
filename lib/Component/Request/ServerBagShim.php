<?php


namespace Wheregroup\SymfonyExt\TransportBundle\Component\Request;


use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\ServerBag;

/**
 * Adjusted version of ServerBag that automatically keeps its 'QUERY_STRING' entry synchronized with
 * GET parameters from another parameter bag.
 */
class ServerBagShim extends ServerBag
{
    /** @var ParameterBag */
    protected $queryBag;

    public function __construct(array $parameters, ParameterBag $queryBag)
    {
        parent::__construct($parameters);
        $this->queryBag = $queryBag;
    }

    public function get($key, $default = null)
    {
        if ($key === 'QUERY_STRING') {
            return http_build_query($this->queryBag->all(), '', '&') ?: $default;
        } else {
            return parent::get($key, $default);
        }
    }

    public function all()
    {
        return array_replace(parent::all(), array(
            'QUERY_STRING' => $this->get('QUERY_STRING', null),
        ));
    }
}
