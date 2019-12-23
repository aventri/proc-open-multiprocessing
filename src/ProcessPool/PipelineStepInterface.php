<?php

namespace aventri\Multiprocessing\ProcessPool;

interface PipelineStepInterface
{
    public function getWorkQueue();
    public function getRunningJobs();
    public function createProcs();
    public function getProc($id);
    public function initializeNewProcs($new = array());
}