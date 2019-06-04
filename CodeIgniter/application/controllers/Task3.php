<?php


class Task3 extends CI_Controller {
    public $CurrentCurrency = [];
    public $history = [];

    public function index()
    {
       $this->output->cache(10);
        $this->load->model('CurrencyModel');
        $this->CurrentCurrency = $this->CurrencyModel->apiP24();
        $this->load->view('view');
    }

    public function history(){
        $this->load->model('CurrencyModel');
        $this->history = $this->CurrencyModel->getHistory();
        $this->load->view('history');
    }
}