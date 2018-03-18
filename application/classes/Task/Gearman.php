<?php  defined('SYSPATH') or die('No direct script access.');

class Task_Gearman extends Minion_Task{

        private $_gearman_worker = null;
        private $_author_model = null;

    public function __construct(){
        $this->_gearman_worker = new GearmanWorker();
        $this->_gearman_worker->addServer("gearmand");
        $this->_gearman_worker->addFunction("authors_list", array($this,"get_all"));
        $this->_gearman_worker->addFunction("author_get", array($this,"get_one"));
        $this->_gearman_worker->addFunction("author_update", array($this,"update"));
        $this->_gearman_worker->addFunction("author_create", array($this,"create"));
        $this->_gearman_worker->addFunction("author_delete", array($this,"delete"));
        $this->_author_model = new Model_Author();
		
    }


    public function get_all($job)
    {
        Minion_CLI::write($job->workload());
        $res = $this->_author_model->get_all()->as_array();
        foreach($res as $item){
            Minion_CLI::write(print_r($job->sendData(json_encode($item)),1));
        }
        $job->sendComplete(json_encode($res));
    }

    public function get_one($job)
    {
        $res = $this->_author_model->get((int)$job->workload())->as_array();
        
        return json_encode($res);
    }

    public function update($job)
    {
        $update_data = json_decode($job->workload(),1);
        $res = $this->_author_model->update($update_data["id"], $update_data["name"], $update_data["surname"], $update_data["type"]);
        
        return json_encode($res);
    }

    public function create($job)
    {
        $insert_data = json_decode($job->workload(),1);      
        Minion_CLI::write($job->workload());
        $res = $this->_author_model->create($insert_data["name"],$insert_data["surname"], $insert_data["type"]);
        return json_encode($res);
    }

    public function delete($job)
    {
        $res = $this->_author_model->delete((int) $job->workload());
        
        return json_encode($res);
    }
    public function _execute(array $params)
    {
        while($this->_gearman_worker->work()){
			
		}
        
    }
}