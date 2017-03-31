<?php namespace VitorBari\GLPIWebservice;

use SoapClient;
use SoapParam;

class Client
{
    /**
     * @var SoapClient
     */
    protected $soapClient;

    /**
     * Client constructor.
     * @param $endpoint
     */
    public function __construct($endpoint)
    {
        $this->soapClient = new SoapClient(null, array('uri'      => $endpoint,
                                                       'location' => $endpoint));
    }

    public function call($args)
    {
        return $this->soapClient->__soapCall('genericExecute', array(new SoapParam($args, 'params')));
    }
}
