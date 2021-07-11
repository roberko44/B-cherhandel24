<?php

require_once 'Page.php';
include './controller/entity/Buch.php';

class Aktien extends Page
{

    private $meinebücher = array();

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
        $this->_recordset = $this->_database->query("select * from buch where kategorieid = 2"); //SQL Anfrage
        if (!$this->_recordset) {
            printf("Query failed: %s\n", $this->_recordset->error);
            exit();
        }

        while ($Record =  $this->_recordset->fetch_assoc()) {

            $buch = new Buch($Record);
            $this->meinebücher[] = $buch;
        }
        $this->_recordset->free();
    }


    protected function generateView()
    {
        $this->getViewData();
        $this->generatePageHeader('to do: change headline');

        echo <<<EOT
        <div id="headerDir">
        <span>Home > Kategorien > <b>Aktien</b></span>   
        </div>
        </div>

        <div id="content">
        <h1> Aktien </h1>
        <section class="buecher">
            
EOT;
        for ($i = 0; $i < count($this->meinebücher); $i++) {
            $this->meinebücher[$i]->showBuch();
        }

        echo <<<EOT

        </section>
        </div>
EOT;
        $this->generatePageFooter();
    }


    protected function processReceivedData()
    {
        parent::processReceivedData();
    }


    public static function main()
    {
        try {
            $page = new Aktien();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Aktien::main();
