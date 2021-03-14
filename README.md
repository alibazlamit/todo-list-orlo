# Slim REST API

---

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

### Create database with mysql

Follow this guide to install mysql  [Mysql](https://dev.mysql.com/doc/mysql-installation-excerpt/5.7/en/)

```
    $ mysql -u root -p
    mysql> CREATE DATABASE todo;
    Query OK, 1 row affected (0.00 sec)
```

### Create schema

    $ /path/to/slim-rest-api/vendor/bin/doctrine orm:schema-tool:create

### Update schema

    $ /path/to/slim-rest-api/vendor/bin/doctrine orm:schema-tool:update --force

## RUN Locally

Run the following command to start the API localy from inside the /path/to/slim-rest-api
`php -S localhost:8080 -t public public/index.php`

## Entities

To find out how Doctrine entities work, see [Object Relation Mapper](http://www.doctrine-project.org/projects/orm.html). The entities can be found in:

    $ cd /path/to/slim-rest-api/src/entity/

## Endpoints

### POST /list

Creates a todo list

**Parameters**

|   Name | Required |  Type  | Description                    |
| -----: | :------: | :----: | ------------------------------ |
| `name` | required | string | The name of the list to create |

**Response**

```
{
    "list": {
        "id": 4,
        "name": "time to shine again"
    }
}
```

**Curl**

```
curl --location --request POST '{host_url}:8080/list' \
--header 'Content-Type: application/json' \
--data-raw '{
    "name":"time to shine again"
}'
```

---

### DELETE /list

Deletes a todo list

**Parameters**

| Name | Required |  Type  | Description                  |
| ---: | :------: | :----: | ---------------------------- |
| `id` | required | string | The id of the list to delete |

**Curl**

```
curl --location --request DELETE '{host_url}:8080/list/3' \
--header 'Content-Type: application/json'
```

---

### PUT /list

renames a todo list

**Parameters**

|   Name | Required |  Type  | Description                 |
| -----: | :------: | :----: | --------------------------- |
| `name` | required | string | The update name of the list |

**Response**

```
{
    "list": {
        "id": 1,
        "name": "updated"
    }
}
```

**Curl**

```
curl --location --request PUT '{host_url}:8080/list/1' \
--header 'Content-Type: application/json' \
--data-raw '{
    "name":"updated"
}'
```

---

### POST /list/{list_id}/items

Creates a todo items

**Parameters**

This endpoint accepts and array of `{ "items":[]}` with the following structure:

|          Name | Required |  Type   | Description                                                  |
| ------------: | :------: | :-----: | ------------------------------------------------------------ |
|     `list_id` | required | string  | The id of the list to add items to                           |
| `description` | required | string  | The description of the item to do                            |
|     `due_due` | required | string  | The due date of the item i.e. `2021-03-15` without timestamp |
|   `completed` | optional | boolean | The status of the item Completed or not.                     |

**Response**

```
{
    "item": [
        {
            "id": 11,
            "description": "must do today",
            "dueDate": "2021-03-15",
            "completed": false
        },
        {
            "id": 12,
            "description": "must do tommorow",
            "dueDate": "2021-03-15",
            "completed": false
        }
    ]
}
```

**Curl**

```
curl --location --request POST '{host_url}:8080/list/1/items' \
--header 'Content-Type: application/json' \
--data-raw '{
    "items": [
        {
            "description": "must do today",
            "due_date": "2021-03-15",
            "completed": false
        },
        {
            "description": "must do tommorow",
            "due_date": "2021-03-15 ",
            "completed": false
        }
    ]
}'
```

---

### PUT /list/{list_id}/items/{item_id}

renames a todo list

**Parameters**

|          Name | Required |  Type   | Description                                                  |
| ------------: | :------: | :-----: | ------------------------------------------------------------ |
|     `list_id` | required | string  | The id of the list                                           |
|     `item_id` | required | string  | The id of the item to update                                 |
| `description` | optional | string  | The description of the item to do                            |
|     `due_due` | optional | string  | The due date of the item i.e. `2021-03-15` without timestamp |
|   `completed` | optional | boolean | The status of the item Completed or not.                     |

**Response**

```
{
    "item": {
        "id": 2,
        "description": "updated12",
        "dueDate": "2021-03-15",
        "completed": "1"
    }
}
```

**Curl**

```
curl --location --request PUT '{host_url}:8080/list/1/items/2' \
--header 'Content-Type: application/json' \
--data-raw '{
    "description":"updated12",
    "due_date":"2021-03-15",
    "completed": true
}'
```

---

### DELETE /list/{list_id}/items/{item_id}

Deletes a todo list

**Parameters**

|      Name | Required |  Type  | Description                  |
| --------: | :------: | :----: | ---------------------------- |
|      `id` | required | string | The id of the list           |
| `item_id` | required | string | The id of the item to delete |

**Curl**

```
curl --location --request DELETE '{host_url}:8080/list/1/items/1' \
--header 'Content-Type: application/json'
```

---

### GET /list/{list_id}/items?due_date={due_date}&completed={completed}

List todo items and filter them by `{list_id}`, `{due_date}` and `{completed}`.

**Parameters**

|        Name | Required |  Type  | Description               |
| ----------: | :------: | :----: | ------------------------- |
|   `list_id` | required | string | The id of list to filter  |
|  `due_date` | required | string | The due_date to filter    |
| `completed` | required | string | The item status to filter |

**Response**

```
{
    "items": [
        {
            "id": 5,
            "description": "must do today",
            "dueDate": "2017-02-15",
            "completed": false
        },
        {
            "id": 6,
            "description": "must do tommorow",
            "dueDate": "2017-02-15",
            "completed": true
        }
    ]
}
```

**Curl**

```
curl --location --request GET '{host_url}:8080/list/2/items?due_date=2017-02-15&completed=false'
```

---
