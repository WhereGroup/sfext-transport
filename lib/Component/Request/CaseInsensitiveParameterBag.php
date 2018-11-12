<?php


namespace Wheregroup\SymfonyExt\TransportBundle\Component\Request;


use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * ParameterBag extension that ignores case on keys.
 */
class CaseInsensitiveParameterBag extends ParameterBag
{
    /** @var string[] */
    protected $internalKeyMap;

    public function __construct(array $parameters = array())
    {
        parent::__construct($parameters);
        $this->updateKeyRemapping();
    }

    protected function updateKeyRemapping()
    {
        $internalKeyMap = array();
        foreach (array_keys($this->parameters) as $key) {
            $internalKey = strtolower($key);
            $internalKeyMap[$internalKey] = $key;
        }
        $this->internalKeyMap = $internalKeyMap;
    }

    public function set($key, $value)
    {
        $this->add(array(
            $key => $value,
        ));
    }

    public function add(array $parameters = array())
    {
        $replaceKeys = array();
        foreach (array_keys($parameters) as $newKey) {
            $internalKey = strtolower($newKey);
            $replaceKeys[$internalKey] = 1;
        }
        $this->parameters = array_diff_key($this->parameters, $replaceKeys);
        parent::add($parameters);
        $this->updateKeyRemapping();
    }

    public function get($key, $default = null)
    {
        $internalKey = strtolower($key);
        if (array_key_exists($internalKey, $this->internalKeyMap)) {
            $realKey = $this->internalKeyMap[$internalKey];
            return $this->parameters[$realKey];
        } else {
            return null;
        }
    }

    public function has($key)
    {
        $internalKey = strtolower($key);
        return array_key_exists($internalKey, $this->internalKeyMap);
    }

    public function remove($key)
    {
        $internalKey = strtolower($key);
        if (array_key_exists($internalKey, $this->internalKeyMap)) {
            parent::remove($this->internalKeyMap[$internalKey]);
            unset($this->internalKeyMap[$internalKey]);
        }
    }
}
