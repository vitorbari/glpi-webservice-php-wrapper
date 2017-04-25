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
        return $this->soapClient->__soapCall(
            'genericExecute',
            array(new SoapParam($args, 'params'))
        );
    }

    /**
     * @param $login_name
     * @param $login_password
     * @param $username
     * @param $password
     * @return mixed
     */
    public function auth($login_name, $login_password, $username = null, $password = null)
    {
        return $this->call(array(
            'method'         => 'glpi.doLogin',
            'login_name'     => $login_name,
            'login_password' => $login_password,
            'username'       => $username,
            'password'       => $password,
        ));
    }
}
