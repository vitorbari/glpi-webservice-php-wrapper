<?php namespace VitorBari\GLPIWebservice;

use VitorBari\GLPIWebservice\Exceptions\NotAuthenticatedException;


/**
 * Class GLPIWebservice
 *
 * @package VitorBari\GLPIWebservice
 */
class GLPIWebservice
{

    /**
     * GLPI Webservice SOAP endpoint
     *
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
     *
     * @param null $endpoint
     */
    public function __construct($endpoint = NULL)
    {
        if (!empty($endpoint)) {
            $this->endpoint = $endpoint;
        }

        $this->client = new Client($this->endpoint);
    }


    //
    // Session methods
    //

    /**
     * Get current session
     *
     * @return array
     * @throws NotAuthenticatedException
     */
    public function getSession()
    {
        $this->isLogged(TRUE);

        // Return the session except the hash
        $session = $this->session;

        if (array_key_exists('session', $session)) {
            unset($session['session']);
        }

        return $session;
    }

    /**
     * Authenticate a user
     *
     * @param $glpi_user
     * @param $glpi_pass
     * @param null $ws_user
     * @param null $ws_pass
     * @return $this
     */
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

        $response = $this->client->call($args);

        $this->session = $response;

        return $this;
    }

    /**
     * Logout current user
     *
     * @return mixed
     */
    public function logout()
    {
        return $this->client->call(array(
                                       'method'  => 'glpi.doLogout',
                                       'session' => $this->getSessionHash()
                                   ));
    }

    /**
     * List the information about the authenticated user
     *
     * @param bool $id2name
     * @return mixed
     */
    public function getMyInfo($id2name = FALSE)
    {
        $args = array(
            'method'  => 'glpi.getMyInfo',
            'session' => $this->getSessionHash()
        );

        if (isset($id2name)) {
            $args['id2name'] = TRUE;
        }

        return $this->client->call($args);
    }

    //
    // Not authenticated methods
    //

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
                                       'method' => 'glpi.listEntities'
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
                                          'method' => 'glpi.listEntities',
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

    //
    // Authenticated methods
    //


    // ========================================
    // Tickets
    // ========================================

    /**
     * Create a new ticket
     *
     * @param array $params
     * @return mixed
     */
    public function createTicket($params = array())
    {
        return $this->client->call(array(
                                       'method'  => 'glpi.createTicket',
                                       'session' => $this->getSessionHash(),
                                       'mine'    => TRUE
                                   ) + $params);
    }

    /**
     * Retrieve information on a existing ticket if the authenticated user can view it.
     *
     * @param $ticket ID of the ticket
     * @param bool $id2name option to enable id to name translation of dropdown fields
     * @return mixed
     */
    public function getTicket($ticket, $id2name = FALSE)
    {
        $args = array(
            'method'  => 'glpi.getTicket',
            'session' => $this->getSessionHash(),
            'ticket'  => $ticket
        );

        if (isset($id2name)) {
            $args['id2name'] = TRUE;
        }

        return $this->client->call($args);
    }

    /**
     * List the Tickets the current authenticated user can view.
     *
     * @param null $status : 1 (new), 2 (assign), 3 (plan), 4 (waiting), 5 (solved), 'notold','old','process','all', 'notclosed'
     * @param bool $id2name option to enable id to name translation of dropdown fields
     * @param null $limit result will not contains more than # item. By default, limit is the GLPI configured value (list_limit_max)
     * @return mixed
     */
    public function listTickets($status = NULL, $id2name = FALSE, $limit = NULL)
    {
        $args = array(
            'method'  => 'glpi.listTickets',
            'session' => $this->getSessionHash()
        );

        if (isset($status)) {
            $args['status'] = $status;
        }

        if (isset($id2name)) {
            $args['id2name'] = TRUE;
        }

        if (isset($limit)) {
            $args['limit'] = (int) $limit;
        }

        return $this->client->call($args);
    }

    /**
     * Count the Tickets the current authenticated user can view.
     *
     * @param null $status : 1 (new), 2 (assign), 3 (plan), 4 (waiting), 5 (solved), 'notold','old','process','all', 'notclosed'
     * @return int|null
     */
    public function countTickets($status = NULL)
    {
        $args = array(
            'method'  => 'glpi.listTickets',
            'session' => $this->getSessionHash(),
            'count'   => TRUE
        );

        if (isset($status)) {
            $args['status'] = $status;
        }

        if (isset($result['count'])) {
            return (int)$result['count'];
        }

        return NULL;
    }

    // ========================================
    // Dropdown
    // ========================================

    /**
     * Search for values in a dropdown table
     *
     * @param $dropdown : name of the dropdown (mandatory). Must a GLPI class name or a Special dropdown name.
     *
     * @return array
     */
    public function listDropdownValues($dropdown)
    {
        return $this->client->call(array(
                                       'method'   => 'glpi.listDropdownValues',
                                       'session'  => $this->getSessionHash(),
                                       'dropdown' => $dropdown
                                   ));
    }

    // ========================================
    // Group
    // ========================================

    /**
     * List groups of the current entities
     *
     * @return array
     */
    public function listGroups()
    {
        return $this->client->call(array(
                                       'method'  => 'glpi.listGroups',
                                       'session' => $this->getSessionHash()
                                   ));
    }

    /**
     * List groups of connected user
     *
     * @return array
     */
    public function listUserGroups()
    {
        return $this->client->call(array(
                                       'method'  => 'glpi.listGroups',
                                       'session' => $this->getSessionHash(),
                                       'mine'    => TRUE
                                   ));
    }

    /**
     * Count groups of the current entities
     *
     * @return int / null
     */
    public function countGroups()
    {
        $result = $this->client->call(array(
                                          'method'  => 'glpi.listGroups',
                                          'session' => $this->getSessionHash(),
                                          'count'   => TRUE
                                      ));

        if (isset($result['count'])) {
            return (int)$result['count'];
        }

        return NULL;
    }

    // ========================================
    // Object
    // ========================================

    /**
     * Retrieve information on a existing object if the authenticated user is a super-admin.
     *
     * @param $itemtype the object type
     * @param $id the ID of object
     * @param array $params (show_label / show_name)
     * @return mixed
     */
    public function getObject($itemtype, $id, $params = array())
    {
        return $this->client->call(array(
                                       'method'   => 'glpi.getObject',
                                       'session'  => $this->getSessionHash(),
                                       'itemtype' => $itemtype,
                                       'id'       => $id
                                   ) + $params);
    }

    //
    // ========================
    //

    /**
     * Checks if a session exists
     *
     * @param bool $throw_exception
     * @return bool
     * @throws NotAuthenticatedException
     */
    private function isLogged($throw_exception = FALSE)
    {
        if (isset($this->session['session'])) {
            return TRUE;
        }

        if ($throw_exception) {
            throw new NotAuthenticatedException;
        }

        return FALSE;
    }

    /**
     * Get current session hash
     *
     * @return mixed
     * @throws NotAuthenticatedException
     */
    private function getSessionHash()
    {
        $this->isLogged(TRUE);

        return $this->session['session'];
    }
}