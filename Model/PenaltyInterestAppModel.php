<?php

class PenaltyInterestAppModel extends Model
{
    public $statusCode = STATUS_CODES::INIT;
    public $statusMessage = STATUS_MESSAGES::INIT;

    public function setStatus($status)
    {
        $this->statusCode = constant("STATUS_CODES::$status");
        $this->statusMessage = constant("STATUS_MESSAGES::$status");
    }
}
