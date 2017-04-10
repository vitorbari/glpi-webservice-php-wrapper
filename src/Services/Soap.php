<?php namespace VitorBari\GLPIWebservice\Services;

use SoapClient;
use SoapParam;

class Soap implements ServiceInterface
{
    /**
     * @var SoapClient
     */
    protected $soapClient;

    /**
     * Client constructor.
     * @param SoapClient $client
     */
    public function __construct(SoapClient $client)
    {
        $this->soapClient = $client;
    }

    /**
     * @param array $args
     * @return mixed
     */
    public function call(array $args)
    {
        return $this->soapClient->__soapCall('genericExecute',
            array(new SoapParam($args, 'params')));
    }
}