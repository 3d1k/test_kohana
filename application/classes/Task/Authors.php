<?php  defined('SYSPATH') or die('No direct script access.');

class Task_Authors
{
    public $status = null;
    public $data = null;
    public $recieve_data = [];




    public function getDataCallback($task){
        $this->recieve_data[] = json_decode($task->data());
        
    }

    public function completeCallback($task)
    {
        $this->data = $task->data();
    }

    public function fail($task)
    {
        print("errr");
    }


}