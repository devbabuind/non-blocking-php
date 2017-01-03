non-blocking-php
================

Library for non blocking background jobs in PHP 

Basic Usage
===========

Hello World
-----------

Add this line to your application's index.php file:
```php
require 'vendor/autoload.php';
```

Import the required classes into your namespace:

```php
use DevBabuInd\NonBlockingPHP\Execute;
```

Instantiate a new shell runner:

```php
$execute = new Execute(array('autoMode' => true));
```

Add some parameters:

```php
$query_params = array('foo'=>'bar');
$folder_protection = array('username' => 'test', 'password' => 'test');
/* sample parameters */
$params = array(
    'url' => 'http://www.yourappdomain.com/job.php',
    'command' => 'php /folderpathofyourapp/job.php',
    'auth' => $folder_protection,
    'args' => $query_params
);
```

Now run the job:

```php
$result = $execute->run($params);
```

You can get the output:

```bash
if ($result) {
    echo "Yay! Background call initiated";
} else {
    print_r($execute->getError());
}
```
