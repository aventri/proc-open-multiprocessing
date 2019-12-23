<?php

namespace aventri\Multiprocessing\IPC;

final class SocketInitializer
{
    /**
     * @var string
     */
    public $unixSocketFile;

    /**
     * @var int
     */
    public $procId;

    /**
     * @var int
     */
    public $poolId;

    /**
     * @return string
     */
    public function getUnixSocketFile()
    {
        return $this->unixSocketFile;
    }

    /**
     * @param string $unixSocketFile
     */
    public function setUnixSocketFile($unixSocketFile)
    {
        $this->unixSocketFile = $unixSocketFile;
    }

    /**
     * @return int
     */
    public function getProcId()
    {
        return $this->procId;
    }

    /**
     * @param int $procId
     */
    public function setProcId($procId)
    {
        $this->procId = $procId;
    }

    /**
     * @return int
     */
    public function getPoolId()
    {
        return $this->poolId;
    }

    /**
     * @param int $poolId
     */
    public function setPoolId($poolId)
    {
        $this->poolId = $poolId;
    }
}