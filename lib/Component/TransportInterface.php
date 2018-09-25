<?php


namespace Wheregroup\SymfonyExt\TransportBundle\Component;


use Symfony\Component\HttpFoundation\Response;

interface TransportInterface
{
    /**
     * @param string $url
     * @param RequestOptions
     * @return Response
     */
    public function getUrl($url, RequestOptions $options = null);
}
