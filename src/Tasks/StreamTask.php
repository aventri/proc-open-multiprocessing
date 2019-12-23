<?php

namespace aventri\Multiprocessing\Tasks;

use aventri\Multiprocessing\Exceptions\ChildException;
use aventri\Multiprocessing\IPC\StreamInitializer;
use aventri\Multiprocessing\IPC\WakeTime;
use aventri\Multiprocessing\Task;
use \Exception;

/**
 * Inside a process script, StreamTask interacts with the Pool through streams.
 * @package aventri\Multiprocessing;
 */
class StreamTask extends EventTask
{
    /**
     * @var Task
     */
    private $consumer;
    /**
     * @var resource
     */
    private $stdin;

    public function __construct(Task $consumer, StreamInitializer $initializer, $stdin)
    {
        $this->consumer = $consumer;
        $this->procId = $initializer->getProcId();
        $this->poolId = $initializer->getPoolId();
        $this->stdin = $stdin;
    }

    public function error(Exception $e)
    {
        fwrite(STDERR, serialize($e));
        exit(1);
    }

    /**
     * Writes the data to the output stream.
     * @param mixed $data
     */
    public function write($data)
    {
        echo serialize($data);
    }

    /**
     * Start listening for incoming data from STDIN
     */
    public function listen()
    {
        if (!empty(ob_get_status())) {
            ob_end_clean();
        }
        $stdin = $this->stdin;
        stream_set_blocking($stdin, 0);
        stream_set_blocking(STDERR, 0);
        stream_set_timeout($stdin, 10);
        $this->setupErrorHandler();

        while (true) {
            $read = array($stdin);
            $write = NULL;
            $except = NULL;
            stream_select($read, $write, $except, null);
            $buffer = "";
            while($f = stream_get_contents($stdin)) {
                $buffer .= $f;
            }
            //don't try to unserialize if we have nothing ready from STDIN, this will save cpu cycles
            if ($buffer === "") {
                continue;
            }
            $data = unserialize($buffer);
            if ($data === self::DEATH_SIGNAL) {
                exit(0);
            }
            if ($data instanceof WakeTime) {
                $this->wakeUpAt($data);
                continue;
            }
            try {
                $this->consumer->consume($data);
            } catch (Exception $e) {
                $ex = new ChildException($e);
                $this->error($ex);
            }
        }
    }
}