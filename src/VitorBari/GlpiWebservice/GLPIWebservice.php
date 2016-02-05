<?php namespace VitorBari\GLPIWebservice;


/**
 * Class GLPIWebservice
 * @package VitorBari\GLPIWebservice
 */
class GLPIWebservice
{

    /**
     * @var string
     */
    protected $endpoint = 'http://localhost/plugins/webservices/soap.php';

    /**
     * @var Client
     */
    private $client;

    /**
     * GLPIWebservice constructor.
     */
    public function __construct()
    {
        $this->client = new Client($this->endpoint);
    }

    public function auth()
    {
        return $this;
    }

    // Not authenticated methods

    public function test()
    {

    }

    public function status()
    {

    }

    public function listAllMethods()
    {

    }

    public function listEntities()
    {

    }

    public function listKnowBaseItems()
    {

    }

    public function getDocument()
    {

    }
}