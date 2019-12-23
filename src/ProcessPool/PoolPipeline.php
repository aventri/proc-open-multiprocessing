<?php

namespace aventri\Multiprocessing\ProcessPool;

use aventri\Multiprocessing\Mp;
use InvalidArgumentException;

abstract class PoolPipeline extends Mp
{
    /**
     * @var Pool[]
     */
    protected $procWorkerPools;

    public function __construct($pools = array())
    {
        foreach ($pools as $pool) {
            if (!($pool instanceof Pool)) {
                throw new InvalidArgumentException("PoolPipeline accepts a list of Pool instances");
            }
        }
        $this->procWorkerPools = $pools;
    }

    public abstract function start();

    protected abstract function process();
}