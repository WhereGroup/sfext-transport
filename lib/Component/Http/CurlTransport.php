<?php


namespace Wheregroup\SymfonyExt\TransportBundle\Component\Http;


use Symfony\Component\HttpFoundation\Response;
use Wheregroup\SymfonyExt\TransportBundle\Component\RequestOptions;
use Wheregroup\SymfonyExt\TransportBundle\Component\TransportInterface;

class CurlTransport implements TransportInterface
{
    /** @var ProxySettings|null */
    protected $proxySettings;

    /**
     * @param ProxySettings|array|null $proxySettings
     */
    public function __construct($proxySettings = null)
    {
        if ($proxySettings && is_array($proxySettings)) {
            $proxySettings = ProxySettings::fromArray($proxySettings);
        }
        $this->proxySettings = $proxySettings ?: null;
    }

    public function getUrl($url, RequestOptions $options = null)
    {
        $options = $options ?: RequestOptions::makeDefaults();
        $ch = $this->initCurl($options, $url, 'GET');
        return $this->makeResponse($ch);
    }

    /**
     * @param resource $ch curlish
     * @return Response
     */
    protected function makeResponse($ch)
    {
        $body = curl_exec($ch);
        if ($body === false) {
            $curlError = curl_error($ch);
            curl_close($ch);
            $response = Response::create('');
            $response->setStatusCode(Response::HTTP_SERVICE_UNAVAILABLE, $curlError ?: null);
        } else {
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $response = Response::create($body, $statusCode);
        }
        return $response;
    }

    /**
     * @param RequestOptions $options
     * @param string $url
     * @param string $method
     * @return resource
     */
    protected function initCurl(RequestOptions $options, $url, $method = 'GET')
    {
        $ch = curl_init($url);
        $curlOpts = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_FOLLOWLOCATION => intval(!!$options->followRedirects),
            CURLOPT_MAXREDIRS => $options->followRedirects ? 10 : 0,
            CURLOPT_TIMEOUT => $options->timeout,
            CURLOPT_CONNECTTIMEOUT => $options->connectTimeout,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_FAILONERROR => 0,
        );
        if ($this->proxySettings) {
            $curlOpts += array(
                CURLOPT_PROXY => $this->proxySettings->host,
                CURLOPT_PROXYPORT => $this->proxySettings->port,
            );
            if ($this->proxySettings->user) {
                $curlOpts += array(CURLOPT_PROXYUSERPWD, implode(':', array(
                    curl_escape($ch, $this->proxySettings->user),
                    curl_escape($ch, $this->proxySettings->password),
                )));
            }
        }

        curl_setopt_array($ch, $curlOpts);
        return $ch;
    }
}
