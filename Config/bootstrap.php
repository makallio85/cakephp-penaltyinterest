<?php

class STATUS_CODES
{
    const INIT = 404;
    const BAD_REQUEST = 400;
    const INVALID_FIRST_DATE = 400;
    const INVALID_INTEREST_DATE = 400;
    const INVALID_PAYMENT_DATE = 400;
    const INVALID_INTEREST_PERCENT_TYPE = 400;
    const INVALID_COUNTRY = 400;
    const SUCCESS = 200;
}

class STATUS_MESSAGES
{
    const INIT = 'Not found.';
    const BAD_REQUEST = 'Request does not meet requirements. See this link for more information.';
    const INVALID_FIRST_DATE = 'Invalid first date or it is missing.';
    const INVALID_INTEREST_DATE = 'Invalid interest date or it is missing.';
    const INVALID_PAYMENT_DATE = 'Invalid payment date or it is missing.';
    const INVALID_INTEREST_PERCENT_TYPE = 'Invalid interest type or it is missing.';
    const INVALID_INTEREST_PERCENT = 'Invalid interest percent or it is missing.';
    const INVALID_COUNTRY = 'Invalid country or it is missing.';
    const SUCCESS = 'Success.';
}
