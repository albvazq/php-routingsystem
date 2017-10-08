# PHP-RoutingSystem | PHP Framework for MVC pattern implementation

##Index
* <a href="#installation">Installation</a>
* <a href="#configuration">Configuration</a>
    * <a href="#configuration-file">Configuration file</a>
    * <a href="#friendly-urls">Friendly URLs</a>
        * <a href="#apache">Apache</a>
* <a href="#url-mapping">URL Mapping</a>
* <a href="#controllers">Controllers</a>
* <a href="#views">Views</a>
* <a href="#models">Models</a>

## Installation

**PHP-RoutingSystem** requires PHP 5.3+ and its only dependencies (at this moment) are defined in the *composer.json* file.
You just need to define the *public* folder as the root of your site and thats all you need to start working with this framework... well and run:

```shell
composer install
```

To download all the dependencies.

## Configuration

### Configuration file

**PHP-RoutingSystem** uses a mechanisms named *Drivers* (located in core/drivers) to add functionalities to your app. So the configuration must be managed by the *Driver* named **Config.php**. Te configuration driver will load your configuration from "config.ini", file that must be placed in the root of the project, and basically must contain:

```
[defaults]
controller = ""
[db]
host = ""
user = ""
password = ""
name = ""
```

If you want to place more configurations you have to define them inside your own section, and they can be accessed:

```
Drivers\Config::get()->var("section.var", "default");
```

### Friendly URLs

#### Apache
```
#.htaccess
<IfModule mod_rewrite.c>
	Options -MultiViews
	Options +FollowSymLinks
	RewriteEngine On
</IfModule>

<IfModule mod_rewrite.c>
	ReWriteBase /
	RewriteRule ^static - [L]
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule ^(.*)$ index.php?$1 [L]
</IfModule>
```

#### Nginx

Pending...

## URL Mapping

In **PHP-RoutingSystem** every url will be mapped to a controller following the next patterns:

- http://yourdomain.com/ (default controller, index method)

- http://yourdomain.com/method1 (default controller, method1)
- http://yourdomain.com/method1/arg1/... (default controller, method1 with args)

- http://yourdomain.com/controller1 (controller1, index method)
- http://yourdomain.com/controller1/method1 (controller1, method1)
- http://yourdomain.com/controller/method/arg1/{arg2}/... (controller1, method1 with args)

## Controllers

All the controllers must be located in *app/controllers* folder, following the next convention:

If you want to create a new controller for your home page you have to create a new file named:

```
Home.php
```

And fill it with:

```php
<?php

namespace Controllers;

use Core\Controller as Controller;

class Home extends Controller {

    public function index() {
        echo "Home page!"
    }

}
```

If you have not defined the "Home" controller as your default controller you can access to it in this way:

```
http://youredomain.com/home
```

This url will automatically launch the index method in the controller, and you will see the content that you print inside of it.

## Views

**PHP-RoutingSystem** uses Twig as its template engine, but it's already integrated inside the framework's flow and file structure.

All the view classes must be placed inside *app/views* folder and every view is linked to a controller (but a view is not required always by the controller). If you create a view class for your *Home* controller it'll look like:

```php
<?php

namespace Views;

use Core\View as View;

class Home extends View {

    public function homePage($name) {
        echo "This is the home page!";
    }

    public function hello($name) {
        echo "Hello ". $name;
    }

    public function helloArray($data) {
        $this->render("test", $data);
    }
}
```

Then, you can call this class from your controller in any method with:

```php
$mView = $this->getView();
```

And access the required method from the view with:

```php
$mView->show("homePage");
```

As you can see you can access your view methods and send params to them, this can be done in two different ways:

```php
//First mode
$mView->show("hello", array("Sergio")); //Every position in array is an argument in the method

//Second mode
$mView->showDataArray("helloArray", $array); //The entire array is an argument in the method
```

### The **render()** method
Inside a view you can call the method **render()** which will create the template using the Twig template engine, for example:

* If you have created an index template for your home page (that is located in *app/templates/home/index.html*) you can render this (inside of a view method) with:

```php
$this->render("index", $data);
```
where "index" is the name of the file and "$data" is the information that will be passed to the template (following the Twig documentation).

The render method can be used to display templates out of the namespace of the actual view and different formats such as: html, json or xml. This actions can be done by:

```php
$this->render($viewLayout, $dataLayout, $viewFormat = "html");

//or

$this->renderExternal($externalSpace, $viewLayout, $dataLayout, $viewFormat = "html");
```

## Models

All the models must be placed in *app/models* and work with *php-activerecord* library.

An example of this classes could be the model for "users" table (following the php-activerecord conventions) in your database. And it would be:

```php
<?php

namespace Models;

use ActiveRecord;

class User extends ActiveRecord\Model {

}
```


## Donate

[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=FDXA3CAML9EF2)
