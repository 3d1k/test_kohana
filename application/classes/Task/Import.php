<?php  defined('SYSPATH') or die('No direct script access.');



class Task_Import extends Minion_Task
{

    /*

     postgres=# select count(*) from url_info;
      count
    ---------
     7057000
    (1 row)

     postgres=# select count(*) from domen_type_count;
     count
    -------
      1983
    (1 row)

    время выполнения 402 секунды

     */

    protected function _execute(array $params)
    {

        $start = time();
        Minion_CLI::write("start");
        DB::query(NULL, "TRUNCATE TABLE domen_type_count ")->execute();
        DB::query(Database::INSERT, "
        INSERT into domen_type_count (domen, type, cnt) 
         SELECT REGEXP_REPLACE(REGEXP_REPLACE(url, '(^(https?://)?(www\.)?)', ''),  '(\/.*)', '' ) domen,type, count(*) cnt from url_info group by 1,type
        ")->execute();
        Minion_CLI::write(time() - $start);
    }
}