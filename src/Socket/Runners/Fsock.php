<?php

namespace DevBabuInd\NonBlockingPHP\Socket\Runners;

use DevBabuInd\NonBlockingPHP\Socket;

class Fsock {

    /**
     * @var integer
     */
    protected $timeout = 30;

    /**
     * @var integer
     */
    protected $error_code;

    /**
     * @var string
     */
    protected $error;

    /**
     * @return boolean
     */
    public function isEnabled() {
        return function_exists('fsockopen');
    }

    /**
     * @param string  $command
     * @return boolean
     */
    public function run($urlData, $postString, $auth) {
        $fsock = fsockopen(gethostbyname($urlData['host']), $urlData['port'], $errno, $errstr, $this->timeout);
        if (!$fsock) {
            $this->error = $errstr;
            $this->error_code = $errno;
            return false;
        }

        $headers = '';
        $headers .= "POST " . $urlData['path'] . " HTTP/1.0\r\n";
        $headers .= "Host: " . $urlData['host'] . "\r\n";
        if ($auth != '') {
            $headers .= $auth . "\r\n";
        }
        $headers .= "User-agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:16.0) Gecko Firefox/16.0\r\n";
        $headers .= "Content-Type: application/x-www-form-urlencoded; charset=UTF-8\r\n";
        $headers .= "Content-Length: " . strlen($postString) . "\r\n";
        $headers .= "Connection: Close\r\n";
        $headers .= "\r\n" . $postString;

        $is_written = fwrite($fsock, $headers);
        if (!$is_written) {
            $this->error = 'Unable to write the headers for the stream';
            return false;
        }
        fclose($fsock);
        return true;
    }

    /**
     * @return array
     */
    public function getError() {
        return array(
            'error' => $this->error,
            'error_code' => $this->error_code
        );
    }

    /**
     * @return string|null
     */
    public function getReturnValue() {
        return $this->returnValue;
    }

}
