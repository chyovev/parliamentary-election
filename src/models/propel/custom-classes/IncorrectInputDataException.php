<?php

/**
 * This exception gets thrown when there's something wrong
 * with the data that the user has provided, such as:
 *     – incorrect IDs which break constraints
 *     – arithemtical discrepancies (more votes than people)
 */

class IncorrectInputDataException extends Exception {

    private $fields;

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(array $fields, $code = 0, Throwable $previous = null) {
        $this->fields = $fields;

        parent::__construct('Incorrect data', $code, $previous);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function __toString(): string {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getFields(): array {
        return $this->fields;
    }
}