<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller {


	private $_gearman_client = null;



	public function before()
	{

		$this->_gearman_client = new GearmanClient();
		$this->_gearman_client->addServer("gearmand");
		$this->_gearman_client->setTimeout(30000);
	
	}
	public function action_index()
	{

		$client = $this->_gearman_client;
        $task = new Task_Authors();
        $arr = null;
        //через обьект
        $client->setDataCallback([$task, 'getDataCallback']);
        //через замыкание
        $client->setCompleteCallback(function(GearmanTask $task) use(&$arr){
			$arr = $task->data();
		});
        $client->addTask('authors_list', "list", NULL, uniqid());
        $client->runTasks();
        $authors_list = $task->data;
        $this->render_as_json($authors_list);
		$this->json_string_render($arr);
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
		
		$this->response->body($val)->headers("Content-Type","application/json");
	}
} // End Welcome
