<?php

/**
 * This exception gets thrown when there's something wrong
 * with the data that the user has provided, such as:
 *     – incorrect IDs which break constraints
 *     – arithemtical discrepancies (more votes than people)
 */

class IncorrectInputDataException extends Exception {

    ///////////////////////////////////////////////////////////////////////////
    public function __construct($message, $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function __toString(): string {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}