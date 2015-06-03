<?php

/**
 * app/Plugin/PenaltyInterest/Model/PenaltyInterestAppModel.php
 */

/**
 * PenaltyInterest plugin AppModel
 */
class PenaltyInterestAppModel extends AppModel
{
    /**
     * Status code
     * @var int
     */
    public $statusCode = STATUS_CODES::INIT;

    /**
     * Status message
     * @var string
     */
    public $statusMessage = STATUS_MESSAGES::INIT;

    /**
     * Set status
     *
     * @param  string $status
     * @return true
     */
    public function setStatus($status)
    {
        $this->statusCode = constant("STATUS_CODES::$status");
        $this->statusMessage = constant("STATUS_MESSAGES::$status");

        return true;
    }

    /**
     * Get status code
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Get status message
     *
     * @return string
     */
    public function getStatusMessage()
    {
        return $this->statusMessage;
    }
}
