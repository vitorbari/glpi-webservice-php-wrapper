<?php namespace VitorBari\GLPIWebservice;

use SoapClient;

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

}