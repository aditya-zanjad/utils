<?php

declare(strict_types=1);

namespace AdityaZanjad\Utils\Commands;

use Exception;
use AdityaZanjad\Utils\Abstracts\Command;
use AdityaZanjad\Utils\Exceptions\CommandFailed;

/**
 * @link https://www.php.net/manual/en/features.commandline.webserver.php
 */
class StartDevelopmentServer extends Command
{
    /**
     * @var array<string, int|string> $options
     */
    protected array $options;

    /**
     * @var array<string, int|string> $options
     */
    public function __construct(array $options = [])
    {
        $this->options['host']      =   $options['host'] ?? '127.0.0.1';
        $this->options['port']      =   $options['port'] ?? '8000';
        $this->options['path']      =   $options['path'] ?? '/';
        $this->options['tries']     =   (int) ($options['tries'] ?? 10);
        $this->options['exception'] =   (bool) ($options['exception'] ?? true);
    }

    /**
     * @inheritDoc
     */
    public function execute(): bool
    {
        // Obtain & validate the command arguments.
        $args = array_merge($this->options, $this->getArguments());
        $this->validate($args);

        // Prepare the command that we want to execute.
        $args['path']   =   trim($args['path'], '/');
        $command        =   "php -S {$args['host']}:{$args['port']} -t {$args['path']}/";

        // Make variable needed inside the loop as well for the command execution.
        $port   =   $args['port'];
        $code   =   null;
        $output =   null;
        $status =   false;

        // Try executing the command. If the execution fails, increment the port
        // number by one & retry executing the command.
        for ($i = 0; $i < $args['tries']; $i++) {
            exec($command, $output, $code);

            if ($code === 0) {
                $status = true;
                break;
            }

            if ($i > 0) {
                echo PHP_EOL;
                echo "Retrying at [{$args['host']}:{$port}]";
            }

            echo PHP_EOL;
            echo "Failed to start the development server at [{$args['host']}:{$port}]";
            echo PHP_EOL;

            $code   =   null;
            $output =   null;

            $port++;
            sleep(1);
        }

        // If the command execution fails & throwing up the exception is allowed.
        if ($status === false && $args['throw_exception'] === true) {
            echo PHP_EOL . PHP_EOL . PHP_EOL;
            throw new CommandFailed("Failed to start the local development server.", (int) $code, $output);
        }

        return $status;
    }

    /**
     * Validate the given options.
     *
     * @param   array<string, mixed> $options
     *
     * @throws  \Exception
     *
     * @return  void
     */
    protected function validate(array $options)
    {
        if (!filter_var($options['host'], FILTER_VALIDATE_DOMAIN)) {
            throw new Exception("The option [host] must be a valid host name.");
        }

        if (!filter_var($options['port'], FILTER_VALIDATE_INT)) {
            throw new Exception("The option [port] must be an integer.");
        }

        if ($options['port'] < 0 || $options['port'] > 65535) {
            throw new Exception("The value of the option [port] must be between the range [0 - 65535].");
        }

        if (!is_dir($options['path']) || !file_exists($options['path'])) {
            throw new Exception("The value of the option [path] must be a valid path to either a directory or a file.");
        }

        if (!filter_var($options['tries'], FILTER_VALIDATE_INT)) {
            throw new Exception("The value of the option [tries] must be an integer value.");
        }

        if ($options['tries'] < 1 || $options['tries'] > 100) {
            throw new Exception("The value of the option [tries] must not be less than 1 OR greater than 100.");
        }
    }
}
