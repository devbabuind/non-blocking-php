<?php

namespace DevBabuInd\NonBlockingPHP\Socket\Runners;

class StreamSocketClient {

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
        return function_exists('stream_socket_client') && function_exists('stream_set_blocking');
    }

    /**
     * @param string  $command
     */
    public function run($urlData, $postString, $auth) {
        $oldErrorReporting = error_reporting();
        error_reporting($oldErrorReporting ^ E_WARNING);
        $stream = stream_socket_client('tcp://' . gethostbyname($urlData['host']) . ':' . $urlData['port'], $errno, $errstr, $this->timeout);
        error_reporting($oldErrorReporting);
        if (!$stream) {
            $this->error = $errstr;
            $this->error_code = $errno;
            return false;
        }


        if (!stream_set_blocking($stream, false)) {
            $this->error = 'Unable to set non blocking mode for the stream';
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

        $is_written = fwrite($stream, $headers);
        if (!$is_written) {
            $this->error = 'Unable to write the headers for the stream';
            return false;
        }
        fclose($stream);
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

}
