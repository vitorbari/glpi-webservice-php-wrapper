<?php namespace VitorBari\GLPIWebservice;

use VitorBari\GLPIWebservice\Exceptions\NotAuthenticatedException;


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
     * GLPI Session
     *
     * @var array
     *
     * array(5) {
     * 'id' =>
     * string(2) "36"
     * 'name' =>
     * string(10) "vitor.bari"
     * 'realname' =>
     * string(4) "Bari"
     * 'firstname' =>
     * string(5) "Vitor"
     * 'session' =>
     * string(26) "(hash)"
     * }
     */
    private $session;

    /**
     * GLPIWebservice constructor.
     * @param null $endpoint
     */
    public function __construct($endpoint = NULL)
    {
        if (!empty($endpoint)) {
            $this->endpoint = $endpoint;
        }

        $this->client = new Client($this->endpoint);
    }

    public function getSession()
    {
        if (empty($this->session) || !is_array($this->session)) {
            throw new NotAuthenticatedException();
        }

        // Return the session except the hash
        $session = $this->session;

        if(array_key_exists('session', $session)) {
            unset($session['session']);
        } else {
            throw new NotAuthenticatedException();
        }

        return $session;
    }

    public function auth($glpi_user, $glpi_pass, $ws_user = NULL, $ws_pass = NULL)
    {
        $args = array(
            'method'         => 'glpi.doLogin',
            'login_name'     => $glpi_user,
            'login_password' => $glpi_pass
        );

        if (isset($ws_user)) {
            $args['username'] = $ws_user;
        }

        if (isset($ws_pass)) {
            $args['password'] = $ws_pass;
        }

        $this->session = $this->client->call($args);

        return $this;
    }

    // Not authenticated methods

    /**
     * Simple ping test method
     * It also give version information of glpi and of plugins which provides methods
     *
     * @return array
     *  glpi => version of glpi,
     *  foo => version of foo plugin
     */
    public function test()
    {
        return $this->client->call(array(
                                       'method' => 'glpi.test'
                                   ));
    }

    /**
     * Give the GLPI status
     *
     * @return array
     *  slavedb_xxx (ok / offline / time diff)
     *  maindb (ok / offline)
     *  sessiondir (ok / not writable)
     *  OCS_xxx (ok / offline)
     *  LDAP_yyy (ok / offline)
     *  glpi (ok / error : global status)
     */
    public function status()
    {
        return $this->client->call(array(
                                       'method' => 'glpi.status'
                                   ));
    }

    /**
     * List all the method allowed to current client
     *
     *
     * @return array
     *  key is method name, value is internal function name or, most often, an array with classname and methodname
     */
    public function listAllMethods()
    {
        return $this->client->call(array(
                                       'method' => 'glpi.listAllMethods'
                                   ));
    }

    /**
     * Return list of current entities defined by server configuration for the client,
     * or currently activated for the user (when authenticated)
     *
     * @return array of entities
     */
    public function listEntities()
    {
        return $this->client->call(array(
                                       'method' => 'glpi.listAllMethods'
                                   ));
    }


    /**
     * Return number of current entities defined by server configuration for the client,
     * or currently activated for the user (when authenticated)
     *
     * @return int / null
     */
    public function countEntities()
    {
        $result = $this->client->call(array(
                                          'method' => 'glpi.listAllMethods',
                                          'count'  => TRUE
                                      ));

        if (isset($result['count'])) {
            return (int)$result['count'];
        }

        return NULL;
    }

    public function listKnowBaseItems()
    {
        // TODO
    }

    public function getDocument()
    {
        // TODO
    }
}