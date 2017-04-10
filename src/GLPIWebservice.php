<?php namespace VitorBari\GLPIWebservice;

use InvalidArgumentException;
use VitorBari\GLPIWebservice\Exceptions\NotAuthenticatedException;
use VitorBari\GLPIWebservice\Services\ServiceInterface;

/**
 * Class GLPIWebservice
 *
 * @package VitorBari\GLPIWebservice
 */
class GLPIWebservice
{

    /**
     * @var ServiceInterface $service
     */
    protected $service;

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
     * Initiate this class with a subclass of ServiceInterface. There are two
     * service subclasses available:
     * - Service\Soap: Service which makes calls to the GLPI Webservice
     * - Service\Stub: Service stub for test purposes (unit tests)
     *
     * @param ServiceInterface $service
     */
    public function __construct(ServiceInterface $service) {
        $this->service = $service;
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
        $this->isLogged(true);

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
    public function auth($glpi_user, $glpi_pass, $ws_user = null, $ws_pass = null)
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

        $response = $this->service->call($args);

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
        return $this->service->call(array(
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
    public function getMyInfo($id2name = false)
    {
        $args = array(
            'method'  => 'glpi.getMyInfo',
            'session' => $this->getSessionHash()
        );

        if (isset($id2name)) {
            $args['id2name'] = true;
        }

        return $this->service->call($args);
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
        return $this->service->call(array(
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
        return $this->service->call(array(
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
        return $this->service->call(array(
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
        return $this->service->call(array(
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
        $result = $this->service->call(array(
            'method' => 'glpi.listEntities',
            'count'  => true
        ));

        if (isset($result['count'])) {
            return (int)$result['count'];
        }

        return null;
    }

    public function listKnowBaseItems()
    {
        // TODO
    }

    /**
     * Retrieve a document if the authenticated user can view it.
     *
     * @param $document int of the document
     * @param null $ticket ID of the ticket (if document is attached to a ticket)
     * @param bool $id2name option to enable id to name translation of dropdown fields
     * @return mixed
     */
    public function getDocument($document, $ticket = null, $id2name = false)
    {
        $args = array(
            'method'   => 'glpi.getDocument',
            'session'  => $this->getOptionalSessionHash(),
            'document' => $document,
            'ticket'   => $ticket
        );

        if (isset($id2name)) {
            $args['id2name'] = true;
        }

        return $this->service->call($args);
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
        return $this->service->call(array(
                'method'  => 'glpi.createTicket',
                'session' => $this->getSessionHash(),
                'mine'    => true
            ) + $params);
    }

    /**
     * Retrieve information on a existing ticket if the authenticated user can view it.
     *
     * @param $ticket ID of the ticket
     * @param bool $id2name option to enable id to name translation of dropdown fields
     * @return mixed
     */
    public function getTicket($ticket, $id2name = false)
    {
        $args = array(
            'method'  => 'glpi.getTicket',
            'session' => $this->getSessionHash(),
            'ticket'  => $ticket
        );

        if (isset($id2name)) {
            $args['id2name'] = true;
        }

        return $this->service->call($args);
    }

    /**
     * List the Tickets the current authenticated user can view.
     *
     * @param null $status : 1 (new), 2 (assign), 3 (plan), 4 (waiting), 5 (solved), 'notold','old','process','all', 'notclosed'
     * @param bool $id2name option to enable id to name translation of dropdown fields
     * @param null $limit result will not contains more than # item. By default, limit is the GLPI configured value (list_limit_max)
     * @return mixed
     */
    public function listTickets($status = null, $id2name = false, $limit = null)
    {
        $args = array(
            'method'  => 'glpi.listTickets',
            'session' => $this->getSessionHash()
        );

        if (isset($status)) {
            $args['status'] = $status;
        }

        if (isset($id2name)) {
            $args['id2name'] = true;
        }

        if (isset($limit)) {
            $args['limit'] = (int)$limit;
        }

        return $this->service->call($args);
    }

    /**
     * Count the Tickets the current authenticated user can view.
     *
     * @param null $status : 1 (new), 2 (assign), 3 (plan), 4 (waiting), 5 (solved), 'notold','old','process','all', 'notclosed'
     * @return int|null
     */
    public function countTickets($status = null)
    {
        $args = array(
            'method'  => 'glpi.listTickets',
            'session' => $this->getSessionHash(),
            'count'   => true
        );

        if (isset($status)) {
            $args['status'] = $status;
        }

        if (isset($result['count'])) {
            return (int)$result['count'];
        }

        return null;
    }

    /**
     * Add a document to a existing ticket if the authenticated user can edit it.
     * Base64 and uri cannot be set together
     *
     * @param $ticket : ID of the ticket
     * @param $name : name of the document to be updoaded
     * @param $uri : uri of the document to be uploaded
     * @param $base64 : content of the document base64 encoded string
     * @param null $content : if present, also add a followup (if doc add succeed)
     * @return int|null
     */
    public function addTicketDocument($ticket, $name, $uri = null, $base64 = null, $content = null)
    {
        if (($base64 && $uri) || (empty($base64) && empty($uri))) {
            throw new InvalidArgumentException('You must pass base64 or uri.');
        }

        $args = array(
            'method'  => 'glpi.addTicketDocument',
            'session' => $this->getSessionHash(),
            'ticket'  => $ticket,
            'name'    => $name,
        );

        if (isset($uri)) {
            $args['uri'] = $uri;
        }

        if (isset($base64)) {
            $args['base64'] = $base64;
        }

        if (isset($content)) {
            $args['content'] = $content;
        }

        return $this->service->call($args);
    }

    /**
     * Add a followup to a existing ticket if the authenticated user can edit it.
     * For solved ticket, reopen or close option is mandatory
     * For closed ticket, adding a new followup is refused
     *
     * @param $ticket : ID of the ticket, mandatory
     * @param $content : of the new followup, mandatory
     * @param $users_login : users login - if you want to check rights of user with checkApprobationSolution function for logged user not allowed, optional
     * @param $source : name of the 'RequestType' (created if needed), optional, default WebServices
     * @param $private : optional boolean, default 0
     * @param $reopen : set ticket to working state (deny solution for "solved" ticket or answer for "waiting" ticket)
     * @param $close : close a "solved" ticket (approve the solution)
     * @return mixed
     */
    public function addTicketFollowup(
        $ticket,
        $content,
        $users_login = false,
        $source = null,
        $private = null,
        $reopen = null,
        $close = null
    ) {
        $args = array(
            'method'  => 'glpi.addTicketFollowup',
            'session' => $this->getSessionHash(),
            'ticket'  => $ticket,
            'content' => $content,
        );

        foreach (compact('users_login', 'source', 'private', 'reopen', 'close') as $key => $value) {
            if (isset($value)) {
                $args[$key] = $value;
            }
        }

        return $this->service->call($args);
    }

    /**
     * Add a new observer to a existing ticket.
     * Current user can add himself to a ticket he can view.
     * Others users can be added if allowed to update the ticket.
     *
     * @param $ticket : ID of the ticket, mandatory
     * @param $user : ID of the user to add, optional, default to connected user
     * @return mixed
     */
    public function addTicketObserver($ticket, $user = null)
    {
        $args = array(
            'method'  => 'glpi.addTicketObserver',
            'session' => $this->getSessionHash(),
            'ticket'  => $ticket
        );

        if (isset($user)) {
            $args['user'] = $user;
        }

        return $this->service->call($args);
    }

    /**
     * Answer to the ticket satisfaction survey
     *
     * @param $ticket : ID of the ticket, mandatory
     * @param int $satisfaction : integer from 0 to 5, mandatory
     * @param null $comment : text, optional
     * @return mixed
     */
    public function setTicketSatisfaction($ticket, $satisfaction, $comment = null)
    {
        if (!is_numeric($satisfaction) || ($satisfaction < 0 || $satisfaction > 5)) {
            throw new InvalidArgumentException('Satisfaction must be an integer from 0 to 5.');
        }

        $args = array(
            'method'       => 'glpi.setTicketSatisfaction',
            'session'      => $this->getSessionHash(),
            'ticket'       => $ticket,
            'satisfaction' => $satisfaction,
        );

        if (isset($comment)) {
            $args['comment'] = $comment;
        }

        return $this->service->call($args);
    }

    /**
     * Answer to a ticket approval request
     *
     * @param $approval : ID of the request, mandatory
     * @param int $status : integer, mandatory - must be from : 1 (none), 2 (waiting), 3 (accepted), 4 (refused)
     * @param null $comment : text, optional (mandatory if status=4)
     * @return mixed
     */
    public function setTicketValidation($approval, $status, $comment = null)
    {
        if (!is_numeric($status) || ($status < 1 || $status > 4)) {
            throw new InvalidArgumentException('Status must be an integer from 1 to 4.');
        }

        if ($status == 4 && empty($comment)) {
            throw new InvalidArgumentException('Please specify the comment.');
        }

        $args = array(
            'method'   => 'glpi.setTicketValidation',
            'session'  => $this->getSessionHash(),
            'approval' => $approval,
            'status'   => $status,
        );

        if (isset($comment)) {
            $args['comment'] = $comment;
        }

        return $this->service->call($args);
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
        return $this->service->call(array(
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
        return $this->service->call(array(
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
        return $this->service->call(array(
            'method'  => 'glpi.listGroups',
            'session' => $this->getSessionHash(),
            'mine'    => true
        ));
    }

    /**
     * Count groups of the current entities
     *
     * @return int / null
     */
    public function countGroups()
    {
        $result = $this->service->call(array(
            'method'  => 'glpi.listGroups',
            'session' => $this->getSessionHash(),
            'count'   => true
        ));

        if (isset($result['count'])) {
            return (int)$result['count'];
        }

        return null;
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
        return $this->service->call(array(
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
    private function isLogged($throw_exception = false)
    {
        if (isset($this->session['session'])) {
            return true;
        }

        if ($throw_exception) {
            throw new NotAuthenticatedException;
        }

        return false;
    }

    /**
     * Get current session hash
     *
     * @return mixed
     * @throws NotAuthenticatedException
     */
    private function getSessionHash()
    {
        $this->isLogged(true);

        return $this->session['session'];
    }

    /**
     * Get current session hash if it exists
     *
     * @return mixed
     */
    private function getOptionalSessionHash()
    {
        if ($this->isLogged(false)) {
            return $this->session['session'];
        }

        return null;
    }
}
