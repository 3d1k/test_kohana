<?php  defined('SYSPATH') or die('No direct script access.');

class Model_Author extends Model{


    public function get_all()
    {
        return DB::query(Database::SELECT, "SELECT * FROM authors")->execute();
    }

    public function get($id){
        return DB::query(Database::SELECT, "SELECT * FROM authors WHERE id = :id ")->bind(":id", $id)->execute();
    }

    public function create($name, $surname, $type)
    {
        return DB::insert("authors", ["name", "surname", "type"])->values([$name, $surname, $type])->execute();
    }

    public function update($id,$name, $surname, $type)
    {
        return DB::update("authors")->set(["name"=>$name, "surname"=>$surname, "type" => $type])->where("id","=",$id)->execute();
    }

    public function delete($id)
    {
        return DB::delete("authors")->where("id","=",$id)->execute();
    }
}