<?php
class CurrencyModel extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function getHistory() {
        $history = [];
        $data = $this->db->query("SELECT * FROM `currencyTable`");
        foreach ($data->result() as $row)
        {
            $history[] = "$row->date $row->name  $row->saleRateNB <br>" ;
        }
        return $history;
    }

    public function apiP24() {
        $data = [];
        $this->db->query("CREATE TABLE IF NOT EXISTS  currencyTable (
    `id` INT (8) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(3) NOT NULL,
    `saleRateNB` FLOAT(16) NOT NULL,
    `date` DATE NOT NULL
    );");
        $date = date('d.m.Y', time());
        $getCurrency = "https://api.privatbank.ua/p24api/exchange_rates?json&date=$date";
        if ($CurrencyInfo = file_get_contents($getCurrency, false)) {
            $CurrencyInfo = json_decode($CurrencyInfo, true);
            $data[] = $CurrencyInfo['exchangeRate'][16];
            $data[] = $CurrencyInfo['exchangeRate'][22];

        } else {
            return ('Error');
        }
        $date = date('Y-m-d', time());

        $this->db->query("INSERT INTO `currencyTable` (`name`, `saleRateNB`, `date`)
        VALUES ('USD', '" . $data[0]['saleRateNB'] . "', '$date')");
        $this->db->query("INSERT INTO `currencyTable` (`name`, `saleRateNB`, `date`) 
        VALUES ('EUR', '" . $data[1]['saleRateNB'] . "', '$date')");
        return ($data);
    }
}
?>