<?php

require 'vendor/autoload.php';

use DevBabuInd\NonBlockingPHP\Execute;

$query = array('foo'=>'bar', 'Token' => 'Auto Mode');
$execute = new Execute(array('autoMode' => true));

/* * ***********Command Mode***************** */

/* $query = array('foo'=>'bar', 'Token'=>'Command Exec');
  $execute = new Execute(array('autoMode'=>false,'strictMode'=>'command', 'strictRunner'=>'exec')); */
/* $query = array('foo'=>'bar', 'Token'=>'Command passthru');
  $execute = new Execute(array('autoMode'=>false,'strictMode'=>'command', 'strictRunner'=>'passthru')); */

/* $query = array('foo'=>'bar', 'Token'=>'Command shellexec');
  $execute = new Execute(array('autoMode'=>false,'strictMode'=>'command', 'strictRunner'=>'shellexec'));
 */
/* $query = array('foo'=>'bar', 'Token'=>'Command systemexec');
  $execute = new Execute(array('autoMode'=>false,'strictMode'=>'command', 'strictRunner'=>'systemexec'));
 */
/* * ***********Sockets ***************** */

/* $query = array('foo'=>'bar', 'Token'=>'Command stream');
  $execute = new Execute(array('autoMode'=>false,'strictMode'=>'socket', 'strictRunner'=>'stream'));
 */
/* $query = array('foo'=>'bar', 'Token'=>'Command fsock');
  $execute = new Execute(array('autoMode'=>false,'strictMode'=>'socket', 'strictRunner'=>'fsock')); */

/* $query = array('foo'=>'bar', 'Token'=>'Command socketconnect');
  $execute = new Execute(array('autoMode'=>false,'strictMode'=>'socket', 'strictRunner'=>'socketconnect')); */

/* sample auth parameters */
$auth = array('username' => 'test', 'password' => 'test');
/* sample parameters */
$params = array(
    'url' => 'http://www.yourappdomain.com/job.php',
    'command' => 'php /folderpathofyourapp/job.php',
    'auth' => $auth,
    'args' => $query
);

$result = $execute->run($params);
if ($result) {
    echo "Yay! Background call initiated";
} else {
    print_r($execute->getError());
}
exit();