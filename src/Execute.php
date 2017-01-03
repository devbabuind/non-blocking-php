<?php

namespace DevBabuInd\NonBlockingPHP;

use DevBabuInd\NonBlockingPHP\Command\Command;
use DevBabuInd\NonBlockingPHP\Socket\Socket;

class Execute {

    /**
     * @var boolean
     */
    protected $autoMode = true;

    /**
     * @var string
     */
    protected $strictMode = null;

    /**
     * @var string
     */
    protected $strictRunner = 'all';

    /**
     * @var string
     */
    protected $currentMode;

    /**
     * @var string
     */
    protected $error;

    /**
     * @var string
     */
    protected $error_code;

    /**
     * @return void
     */
    public function __construct($params) {
        $this->setParams($params);
        $this->init();
        return $this;
    }

    /**
     * @return void
     */
    public function init() {
        if ($this->autoMode || $this->strictMode === 'command') {
            $this->currentMode = new Command($this->strictRunner);
        }

        if ($this->strictMode === 'socket') {

            $this->currentMode = new Socket($this->strictRunner);
        }
    }

    /**
     * @return boolean
     */
    public function run($params) {
        if ($this->autoMode) {
            if ($this->commandRunner($params)) {
                return true;
            } else if ($this->socketRunner($params)) {
                return true;
            } else {
                return false;
            }
        }

        if ($this->strictMode === 'command') {
            if ($this->commandRunner($params)) {
                return true;
            } else {
                return false;
            }
        }

        if ($this->strictMode === 'socket') {
            if ($this->socketRunner($params)) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @return boolean
     */
    public function commandRunner($params) {
        $command = new Command($this->strictRunner);
        if ($command->runBackgroundJob($params['command'], $params['args'])) {
            return true;
        } else {
            $errorData = $command->getError();
            $this->error = $errorData['error'];
            $this->error_code = $errorData['error_code'];
            return false;
        }
    }

    /**
     * @return boolean
     */
    public function socketRunner($params) {
        $socket = new Socket($this->strictRunner);
        if ($socket->runBackgroundJob($params['url'], $params['args'], $params['auth'])) {
            return true;
        } else {
            $errorData = $socket->getError();
            $this->error = $errorData['error'];
            $this->error_code = $errorData['error_code'];
            return false;
        }
    }

    /**
     * @param $setParams array
     * @return void
     */
    private function setParams($setParams) {
        if (isset($setParams['autoMode'])) {
            $this->autoMode = $setParams['autoMode'];
        }

        if (!$this->autoMode && isset($setParams['strictMode'])) {
            $this->strictMode = $setParams['strictMode'];
        }

        if (!$this->autoMode && isset($setParams['strictRunner'])) {
            $this->strictRunner = $setParams['strictRunner'];
        }
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
     * @return array
     */
    public function serverRequirement(){
        $command = new Command();
        $commandCheck = $command->serverCheck();
        $socket = new Socket();
        $socketCheck = $socket->serverCheck();
        $result = array_merge($commandCheck, $socketCheck);
        return $result;
    }

}
