# Slim REST API
***

This is a JSON REST API build with the use of [Slim Framework](http://www.slimframework.com) and [Doctrine](http://www.doctrine-project.org).

## Installation

### Git clone
To get the latest source you can use git clone.

    $ git clone https://github.com/alibazlamit/todo-list-orlo.git /path/to/slim-rest-api

### Composer
Installation can be done with the use of composer. If you don't have composer yet you can install it by doing:

    $ curl -s https://getcomposer.org/installer | php
    
To install it globaly 
    
    $ sudo mv composer.phar /usr/local/bin/composer
    
### Vendor

    $ cd /path/to/slim-rest-api
    $ composer update
    $ composer install

### Database credentials

    $ cp /path/to/slim-rest-api/config/local.ini.dist /path/to/slim-rest-api/config/local.ini

Edit the credentials in the local.ini file

    [database]
    driv = 'pdo_mysql'
    host = 'localhost'
    port = '3306'
    user = ''
    pass = ''
    name = ''

### Create schema

    $ /path/to/slim-rest-api/vendor/bin/doctrine orm:schema-tool:create
    
### Update schema

    $ /path/to/slim-rest-api/vendor/bin/doctrine orm:schema-tool:update --force
    
## Entities

To find out how Doctrine entities work, see [Object Relation Mapper](http://www.doctrine-project.org/projects/orm.html). The entities can be found in:

    $ cd /path/to/slim-rest-api/src/entity/

## Endpoints

### POST /list
Creates a todo list

**Parameters**

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `name` | required | string  | The name of the list to create   |

**Response**

```
{
    "list": {
        "id": 4,
        "name": "time to shine again"
    }
}
```
___