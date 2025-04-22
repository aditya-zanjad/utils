<?php

declare(strict_types=1);

namespace AdityaZanjad\Utils\Exceptions;

use Exception;

/**
 * @version 1.0
 */
class CommandFailed extends Exception
{
    /**
     * @var mixed $output
     */
    protected $output;

    /**
     * @param   string  $message
     * @param   int     $code
     * @param   mixed   $output
     */
    public function __construct(string $message = 'The command has failed!', int $code, $output = [])
    {
        $this->output = $output;
        parent::__construct($message, $code);
    }

    /**
     * Get the output of the executed command.
     *
     * @return mixed
     */
    public function getOutput()
    {
        return $this->output;
    }
}
