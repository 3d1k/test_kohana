<?php  defined('SYSPATH') or die('No direct script access.');

class Task_Console extends Minion_Task{

        private $_gearman_client = null;
        private $_author_model = null;
        private $result = null;

    public function __construct(){
        $this->_gearman_client = new GearmanClient();
        $this->_gearman_client->addServer("gearmand");
        $this->_gearman_client->setTimeout(30000);
        $this->_author_model = new Model_Author();
		
    }


    public function _execute(array $params)
    {
        $client = $this->_gearman_client;
        $client->setDataCallback([$this, 'test']);
        $client->setExceptionCallback([$this, 'test']);
        $client->setWarningCallback([$this, 'test']);
        $client->setCompleteCallback(function (GearmanTask $task){
            print "end";
        });
        $client->addTask('authors_list', "list", NULL, uniqid());
        $task = new Task_Authors();
        $arr = [];

        $client->runTasks();
        var_dump($this->result);
    }

    public function test(GearmanTask $task){
        var_dump($task);
        $this->result = $task->data();
    }
}