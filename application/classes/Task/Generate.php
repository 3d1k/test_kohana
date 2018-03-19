<?php

class Task_Generate extends Minion_Task
{

    public function get_query_insert()
    {
        return DB::insert("url_info", ["url", "type"]);
    }

    protected function _execute(array $params)
    {
        $cnt = 2000000;
        $rnd_domains = ["google.com", "vk.com", "test.com", "slack.com", "postgresql.org"];
        $rnd_domains_no = range(1, 100);
        $types = ["site", "service", 'landing', 'app'];
        $rnd_path = ["path", "to", "info", "param"];

        $q = $this->get_query_insert();
        for ($i = 0; $i < $cnt; $i++) {
            $domen = $rnd_domains[rand(0, count($rnd_domains) - 1)];
            $n_domen = $rnd_domains_no[rand(1, 99)];

            $arr_path = array_map(function ($v) use ($rnd_path) {
                return $rnd_path[rand(0, count($rnd_path) - 1)] . $v;
            }, range(0, rand(1, 10)));

            $url = "https://" . $domen . $n_domen . "/" . implode("/", $arr_path);
            $type = $types[rand(0, count($types) - 1)];

            try {
                $q->values([$url, $type]);

            } catch (Kohana_Exception $e) {
            }
            if ($i % 25000 == 0) {
                Minion_CLI::write($i);
                $q->execute();
                $q->reset();
                $q = $this->get_query_insert();
            }
        }
        $q->execute();

    }


}