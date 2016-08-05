# glpi-webservice-php-wrapper

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
use VitorBari\GLPIWebservice\GLPIWebservice;

$glpi = new GLPIWebservice('http://[glpi-url]/plugins/webservices/soap.php');

$glpi->auth('vitor.bari', '1234')->listGroups();
$glpi->listUserGroups();
```

## Methods

### Not authenticated methods
* `$glpi->test()` Simple ping test method
* `$glpi->status()` Give the GLPI status
* `$glpi->listAllMethods()` List all the method allowed to current client
* `$glpi->listEntities()` Return list of current entities defined by server configuration for the client
* `$glpi->countEntities()` Return number of current entities defined by server configuration for the client
* `$glpi->listKnowBaseItems()` *Not Implemented*
* `$glpi->getDocument()` *Not Implemented*

### Session
* `$glpi->auth($glpi_user, $glpi_pass, $ws_user = NULL, $ws_pass = NULL)` Authenticate a user
* `$glpi->logout()` Logout current user
* `$glpi->getSession()` Get current session
* `$glpi->getMyInfo($id2name=FALSE)` List the information about the authenticated user

### Authenticated methods

#### Ticket
* `$glpi->getTicket($ticket, $id2name=FALSE)` Retrieve information on a existing ticket if the authenticated user can view it
* `$glpi->createTicket($params = array())` Create a new ticket
* `$glpi->listTickets($status=NULL, $id2name=FALSE)` List the Tickets the current authenticated user can view
* `$glpi->countTickets($status=NULL)` Count the Tickets the current authenticated user can view
* `$glpi->addTicketDocument()` *Not Implemented*
* `$glpi->addTicketFollowup()` *Not Implemented*
* `$glpi->addTicketObserver()` *Not Implemented*
* `$glpi->setTicketSatisfaction()` *Not Implemented*
* `$glpi->setTicketValidation()` *Not Implemented*
* `$glpi->setTicketSolution()` *Not Implemented*
* `$glpi->setTicketAssign()` *Not Implemented*

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

* PHP >= 5.3.0
* PHP Soap Extension

## Versions

It has only been tested on GLPI v0.85.3 and Webservice Plugin v1.5.0.