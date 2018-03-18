<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller {


	private $_gearman_client = null;
	private $_gearman_worker = null;

	
	public function before()
	{
		$this->_gearman_client = new GearmanClient();
		$this->_gearman_client->addServer("gearmand");
		$this->_gearman_client->setTimeout(30000);
	
	}
	public function action_index()
	{
		// $list = $this->_gearman_client->doNormal("authors_list","list");
		// ob_start();
		$client = new GearmanClient();
		$client->addServer("gearmand");
		$results = "";
		$client->addTask('authors_list', "list", NULL, uniqid());
		$task = new Task_Authors();
		$arr = [];
		// $client->setDataCallback([$task, 'getDataCallback']);
		$client->setCompleteCallback(function($task) use(&$arr){
			var_dump($task);
			$arr = $task->data();
			// $this->json_string_render($results);
			return $arr;
		});
		// var_dump($client->setFailCallback([$task, 'fail']));
		$client->runTasks();
		var_dump($client->returnCode());
		// var_dump($task->recieve_data);
		// ob_end_flush();  
		$this->json_string_render(json_encode($arr));
	}

	public function action_get()
	{
		$id = $this->request->param('id');
		$author = $this->_gearman_client->doNormal("author_get", $id);
		$this->json_string_render($author);
	}

	public function action_create()
	{
		//TODO проверить данные
		$post_data = $this->request->post();
		$result = $this->_gearman_client->doNormal("author_create",  json_encode($post_data));
		$this->json_string_render($result);
	}


	public function action_update()
	{
		$id = $this->request->param('id');
		$post_data = $this->request->post();
		$post_data["id"] = $id; 

		$_gearman_client->addTask('author_update', $data);
		$result = $this->_gearman_client-doNormal("author_update", json_encode($post_data));
		$this->json_string_render($result);
	}

	public function action_delete()
	{
		$id = $this->request->param('id');
		$result = $this->_gearman_client->doNormal("author_delete", $id);
		$this->json_string_render($result);
	}



	private function render_as_json($val){
		
		$this->response->body(json_encode($val))->headers("Content-Type","application/json");
	}
	private function json_string_render($val){
		
		$this->response->body($val);
	}
} // End Welcome
