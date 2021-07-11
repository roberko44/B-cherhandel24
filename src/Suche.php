<?php

require_once 'Page.php';
include './controller/entity/Buch.php';

class Suche extends Page
{

    private $meinebuecher = array();
    private $search;
    private $status = false;


    protected function __construct()
    {
        parent::__construct();
    }


    protected function __destruct()
    {
        parent::__destruct();
    }


    protected function getViewData()
    {
        if ($this->search) {

            $this->_recordset = $this->_database->query("select * from buch where BUCHNAME like '%$this->search%' or AUTOR like '%$this->search%' or BESCHREIBUNG like '%$this->search%';"); //SQL Anfrage
            if (!$this->_recordset) {
                printf("Query failed: %s\n", $this->_recordset->error);
                exit();
            }

            while ($Record =  $this->_recordset->fetch_assoc()) {
                $buch = new Buch($Record);
                $this->meinebuecher[] = $buch;
            }
            $this->_recordset->free();

            if ($this->meinebuecher) {
                $this->status = true;
            }
        }
    }


    protected function generateView()
    {
        $this->getViewData();
        $this->generatePageHeader('to do: change headline');

        echo <<<EOT
        <div id="headerDir">
        <span>Home > <b>Suche</b></span>   
        </div>
        </div>
        <div id="content" class="content">
        <h1> Suche </h1>
EOT;

        if ($this->status) {
            for ($i = 0; $i < count($this->meinebuecher); $i++) {
                $this->meinebuecher[$i]->showBuch();
            }
        } elseif (!$this->status) {
            echo 'Leider nichts gefunden.';
        }

        echo <<<EOT
        
        </div>
EOT;



        $this->generatePageFooter();
    }


    protected function processReceivedData()
    {
        parent::processReceivedData();
        if (isset($_POST["suche"])) {

            if ($_POST["suche"] != null) {
                $this->search = $_POST["suche"];
            }
        }
    }


    public static function main()
    {
        try {
            $page = new Suche();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Suche::main();
