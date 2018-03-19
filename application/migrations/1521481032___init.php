<?php defined('SYSPATH') or die('No direct access allowed.');

class Migration1521481032_Init extends Migration
{

    /**
     * Returns migtation ID
     *
     * @return integer
     */
    public function id()
    {
        return 1521481032;
    }

    /**
     * Returns migtation name
     *
     * @return string
     */
    public function name()
    {
        return 'Init';
    }

    /**
     * Returns migtation info
     *
     * @return string
     */
    public function info()
    {
        return '';
    }

    /**
     * Takes a migration
     *
     * @return void
     */
    public function up()
    {

        DB::query(null ,"  CREATE TABLE IF NOT EXISTS authors (
        id      serial PRIMARY KEY,
  name    DATE         NOT NULL,
  surname VARCHAR(255) UNIQUE,
  type    VARCHAR(255) NOT NULL
)")->execute();

        DB::query(null , "  CREATE TABLE IF NOT EXISTS url_info (
        id   serial PRIMARY KEY,
  url  VARCHAR(255) NOT NULL,
  type VARCHAR(255) 
)")->execute();
        DB::query(null , "INSERT INTO url_info (url, type) VALUES
    ('https://google.com/path/to/info?param=1','site'),
  ('https://google2.com/path/to/info?param=2','service'),
  ('https://google.com/path/to/info?param=3','site'),
  ('https://google2.com/path/to/info?param=4','site'),
  ('https://google.com/path/to/info?param=5','site'),
  ('https://google2.com/path/to/info?param=6','service')")->execute();

        DB::query(null , "CREATE TABLE IF NOT EXISTS domen_type_count (domen VARCHAR(255) NOT NULL,type  VARCHAR(255) ,cnt INTEGER  NOT NULL,PRIMARY KEY (domen, type))")->execute();


    }

    /**
     * Removes migration
     *
     * @return void
     */
    public function down()
    {

    }

} // End Migration1521481032_Init
