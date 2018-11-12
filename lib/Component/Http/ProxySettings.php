<?php


namespace Wheregroup\SymfonyExt\TransportBundle\Component\Http;


/**
 * POD object to encapsulate http / https proxy settings
 */
class ProxySettings
{
    public $host;
    public $port;
    public $user;
    public $password;

    public static $defaultValues = array(
        'port' => 8080,
        'user' => null,
        'password' => null,
    );

    public function __construct($host, $port, $user = null, $password = null)
    {
        if ((!$user) xor (!$password)) {
            throw new \InvalidArgumentException("User and password must be either both empty or both non-empty");
        }

        $this->host = $host;
        $this->port = intval($port);
        $this->user = $user ?: null;
        $this->password = $password ?: null;
    }

    /**
     * Construct instance from array. Expects array keys 'host' (mandatory), 'port' (optional; default 8080),
     * 'user' (optional), 'password' (required if user is set, required empty if user is not set).
     *
     * @param array $values
     * @return static
     */
    public static function fromArray($values)
    {
        $validKeys = array(
            'host',
            'port',
            'user',
            'password',
        );
        $invalidKeys = array_diff(array_keys($values), $validKeys);
        if ($invalidKeys) {
            throw new \InvalidArgumentException("Unsupported keys " . implode(', ', $invalidKeys));
        }
        if (!$values || !array_key_exists('host', $values)) {
            throw new \InvalidArgumentException("Missing / empty mandatory value for host");
        }
        if (empty($values['port'])) {
            $values['port'] = static::$defaultValues['port'];
        }
        // add user + password null defaults
        $values += static::$defaultValues;
        return new static($values['host'], $values['port'], $values['user'], $values['password']);
    }
}
