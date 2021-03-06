<?php

namespace Codememory\Components\Console\Traits;

use Exception;
use Symfony\Component\Console\Output\BufferedOutput;
use Codememory\Components\Console\Running;

/**
 * Trait ExecutionTrait
 * @package Codememory\Components\Console\Traits
 *
 * @author  Codememory
 */
trait ExecutionTrait
{

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Asynchronous launch of commands, i.e. all commands
     * will be launched in turn
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param BufferedOutput $output
     *
     * @return Running|ExecutionTrait
     * @throws Exception
     */
    private function asyncExecution(BufferedOutput $output): Running|ExecutionTrait
    {

        if (self::ASYNC_EXECUTION === $this->execution['type']) {
            foreach ($this->getInputs() as $input) {
                $this->app->run($input, $output);

                $this->response[] = $output->fetch();
            }
        }

        return $this;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * First command launch handler
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param BufferedOutput $output
     *
     * @return Running|ExecutionTrait
     * @throws Exception
     */
    private function firstExecution(BufferedOutput $output): Running|ExecutionTrait
    {

        if (self::FIRST_EXECUTION === $this->execution['type']) {
            $this->handlerExecutionByIndex($output, array_key_first($this->getInputs()));
        }

        return $this;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Last command launch handler
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param BufferedOutput $output
     *
     * @return Running|ExecutionTrait
     * @throws Exception
     */
    private function lastExecution(BufferedOutput $output): Running|ExecutionTrait
    {

        if (self::LAST_EXECUTION === $this->execution['type']) {
            $this->handlerExecutionByIndex($output, array_key_last($this->getInputs()));
        }

        return $this;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Handler for launching a command at a specific command index
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param BufferedOutput $output
     *
     * @return Running|ExecutionTrait
     * @throws Exception
     */
    private function specificExecution(BufferedOutput $output): Running|ExecutionTrait
    {

        if (self::SPECIFIC_EXECUTION === $this->execution['type']) {
            $this->handlerExecutionByIndex($output, $this->execution['index']);
        }

        return $this;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Index command launch handler
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param BufferedOutput $output
     * @param int            $index
     *
     * @return Running|ExecutionTrait
     * @throws Exception
     */
    private function handlerExecutionByIndex(BufferedOutput $output, int $index): Running|ExecutionTrait
    {

        $input = $this->getInputs()[$index];

        $this->app->run($input, $output);

        $this->response[] = $output->fetch();

        return $this;

    }

}