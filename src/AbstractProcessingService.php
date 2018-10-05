<?php


class AbstractProcessingService
{
    private $_pm_process_terminator;

    protected function serviceSafePoint()
    {
        if ($this->_pm_process_terminator === null) {

            $handler = function ($signo) {
                $this->_pm_process_terminator = true;
            };

            pcntl_signal(SIGTERM, $handler);

            $this->_pm_process_terminator = false;

            return true;
        }

        pcntl_signal_dispatch();

        if ($this->_pm_process_terminator === false) {
            return true;
        }

        echo "Shutting down...\n";
        return false;
    }

}