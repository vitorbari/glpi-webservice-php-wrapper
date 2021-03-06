# glpi-webservice-php-wrapper

[![Build Status](https://travis-ci.org/vitorbari/glpi-webservice-php-wrapper.svg?branch=master)](https://travis-ci.org/vitorbari/glpi-webservice-php-wrapper)
[![StyleCI](https://styleci.io/repos/51149054/shield)](https://styleci.io/repos/51149054)
[![Coverage Status](https://coveralls.io/repos/github/vitorbari/glpi-webservice-php-wrapper/badge.svg?branch=master)](https://coveralls.io/github/vitorbari/glpi-webservice-php-wrapper?branch=master)


<a name="introduction"></a>
## Introduction

A simple object orientated wrapper for the [GLPI Webservice Plugin](https://forge.glpi-project.org/projects/webservices), written in PHP.

The intention of this class is to allow PHP application developers quick and easy interaction with the GLPI Webservice Plugin, without having to worry about the finer details of the Webservice.

## Installation

Via Composer.

```
composer require vitorbari/glpi-webservice-php-wrapper
```

## Usage Example

```php
use VitorBari\GLPIWebservice\Services\Soap;
use VitorBari\GLPIWebservice\GLPIWebservice;

// Create soap adapter
// Currently, only soap is implemented, but the plugin also supports XMLRPC and REST
$endpoint = 'http://[glpi-url]/plugins/webservices/soap.php';
$soapClient = new SoapClient(null, array('uri' => $endpoint, 'location' => $endpoint));
$service = new Soap($soapClient);

$glpi = new GLPIWebservice($service);

$glpi->auth('username', 'password');
$glpi->listUserGroups();

// You can use method chaining
$glpi->auth('username', 'password')->listUserGroups();
```

## Methods

### Not authenticated methods
* `$glpi->test()` Simple ping test method
* `$glpi->status()` Give the GLPI status
* `$glpi->listAllMethods()` List all the method allowed to current client
* `$glpi->listEntities()` Return list of current entities defined by server configuration for the client
* `$glpi->countEntities()` Return number of current entities defined by server configuration for the client
* `$glpi->listKnowBaseItems()` *Not Implemented*
* `$glpi->getDocument($document, $ticket = null, $id2name = false)` Retrieve a document if the authenticated user can view it

### Session
* `$glpi->auth($glpi_user, $glpi_pass, $ws_user = null, $ws_pass = null)` Authenticate a user
* `$glpi->logout()` Logout current user
* `$glpi->getSession()` Get current session
* `$glpi->getMyInfo($id2name=false)` List the information about the authenticated user

### Authenticated methods

#### Ticket
* `$glpi->getTicket($ticket, $id2name=false)` Retrieve information on a existing ticket if the authenticated user can view it
* `$glpi->createTicket($title, $content, $params = array())` Create a new ticket
* `$glpi->listTickets($status=null, $id2name=false)` List the Tickets the current authenticated user can view
* `$glpi->countTickets($status=null)` Count the Tickets the current authenticated user can view
* `$glpi->addTicketDocument($ticket, $name, $uri = null, $base64 = null, $content = null)` Add a document to an existing ticket if the authenticated user can edit it
* `$glpi->addTicketFollowup($ticket, $content, $users_login = false, $source = null, $private = null, $reopen = null, $close = null)` Add a followup to an existing ticket if the authenticated user can edit it
* `$glpi->addTicketObserver($ticket, $user = null)` Add a new observer to an existing ticket
* `$glpi->setTicketSatisfaction($ticket, $satisfaction, $comment = null)` Answer to the ticket satisfaction survey
* `$glpi->setTicketValidation($approval, $status, $comment = null)` Answer to a ticket approval request
* `$glpi->setTicketSolution($ticket, $type, $solution)` Solution for a ticket
* `$glpi->setTicketAssign($ticket, $user = null, $group = null, $supplier = null, $user_email = null, $use_email_notification = null)` Assign a ticket

#### Dropdown
* `$glpi->listDropdownValues($dropdown)` Search for values in a dropdown table

#### Group
* `$glpi->listGroups()` List groups of the current entities
* `$glpi->listUserGroups()` List groups of connected user
* `$glpi->countGroups()` Count groups of the current entities

#### Object
* `$glpi->getObject($itemtype, $id, $params = array())` Retrieve information on a existing object if the authenticated user is a super-admin
* `$glpi->createObjects()` *Not Implemented*
* `$glpi->deleteObjects()` *Not Implemented*
* `$glpi->updateObjects()` *Not Implemented*
* `$glpi->linkObjects()` *Not Implemented*
* `$glpi->listObjects()` *Not Implemented*

## Requirements

* PHP >= 5.6.0
* PHP Soap Extension

## Versions

It has only been tested on GLPI v0.85.3 and Webservice Plugin v1.5.0.