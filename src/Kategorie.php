<?php

require_once 'Page.php';


class Kategorien extends Page
{
    private $kategorien = array();

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
        $sqlAnfrage = "select * from kategorie;";
        $recordset = $this->_database->query($sqlAnfrage);
        if (!$recordset) {
            printf("Query failed.");
        }

        while ($record = $recordset->fetch_assoc()) {
            $tempArray = array(
                "KATEGORIEID" => $record["KATEGORIEID"],
                "KATEGORIENAME" => $record["KATEGORIENAME"]
            );
            $this->kategorien[] = $tempArray;
        }
    }


    protected function generateView()
    {
        $this->getViewData();
        $this->generatePageHeader('to do: change headline');

        echo <<<EOT
        <div id="headerDir">
        <span>Home > <b>Kategorien</b></span>   
        </div>
        </div>
        <div id="content">
        <h1> Kategorien </h1>
        <section class="buecher">

EOT;

        for ($i = 0; $i < count($this->kategorien); $i++) {
            $kname = $this->kategorien[$i]["KATEGORIENAME"];
            echo '<a href="' . $kname . '.php"><section class="kname"> ' . $kname . ' </section></a>';
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
            $page = new Kategorien();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}


Kategorien::main();