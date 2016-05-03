# glpi-webservice-php-wrapper

**This is an alpha, experimental package!**

<a name="introduction"></a>
## Introduction

A simple object orientated wrapper for the [GLPI Webservice Plugin](https://forge.glpi-project.org/projects/webservices), written in PHP.

The intention of this class is to allow PHP application developers quick and easy interaction with the GLPI Webservice Plugin, without having to worry about the finer details of the Webservice.

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

### Authenticated methods
* `$glpi->listDropdownValues($dropdown)` Search for values in a dropdown table
* `$glpi->listGroups()` List groups of the current entities
* `$glpi->listUserGroups()` List groups of connected user
* `$glpi->countGroups()` Count groups of the current entities
* `$glpi->createTicket($params = array())` Create a new ticket
* `$glpi->getTicket($ticket, $id2name=FALSE)` Retrieve information on a existing ticket if the authenticated user can view it
* `$glpi->getObject($itemtype, $id, $params = array())` Retrieve information on a existing object if the authenticated user is a super-admin


## Requirements

* PHP >= 5.3.0
* PHP Soap Extension

## Versions

It has only been tested on GLPI v0.85.3 and Webservice Plugin v1.5.0.