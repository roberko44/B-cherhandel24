<?php

require_once 'Page.php';


class Kommentar extends Page
{

    private $buchid;
    private $kommentar = array();
    private $allebuchnamen = array();
    private $buchname;

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
        if ($this->buchid) {
            // ALLE KOMMENTARE AUS DB HOLEN

            $sqlanfrage = "select * from kommentar, nutzer where kommentar.BUCHID = $this->buchid and kommentar.NUTZERID = nutzer.NUTZERID order by DATUM DESC;";
            $this->_recordset = $this->_database->query($sqlanfrage); //SQL Anfrage
            if (!$this->_recordset) {
                printf("Query failed: %s\n", $this->_recordset->error);
                exit();
            }

            while ($Record = $this->_recordset->fetch_assoc()) {

                $tempArray = array(

                    "KOMMENTARID" => $Record["KOMMENTARID"],
                    "BUCHID" => $Record["BUCHID"],
                    "NUTZERID" => $Record["NUTZERID"],
                    "KOMMENTAR" => htmlspecialchars($Record["KOMMENTAR"]),
                    "DATUM" => $Record["DATUM"],
                    "NUTZERNAME" => $Record["NUTZERNAME"]
                );

                $this->kommentar[] = $tempArray;
            }



            ////////BUCHNAME FÜR SPAN AUS DB HOLEN////////////////////////////////////////////////////
            $sqlanfrage = "select BUCHNAME from buch where BUCHID = $this->buchid;";
            $this->_recordset = $this->_database->query($sqlanfrage); //SQL Anfrage
            if (!$this->_recordset) {
                printf("Query failed: %s\n", $this->_recordset->error);
                exit();
            }

            while ($Record = $this->_recordset->fetch_assoc()) {

                $tempArray = array(
                    "BUCHNAME" => $Record["BUCHNAME"]
                );

                $this->allebuchnamen[] = $tempArray;
            }
            $this->buchname = $this->allebuchnamen[0]["BUCHNAME"];   // an Stelle 0 weil ja eh nur 1 Buchname selected wurde
            //////////////////////////////////////////////////////////////////////////////////////////






            $this->_recordset->free();
        }
    }


    protected function generateView()
    {
        $this->getViewData();
        $this->generatePageHeader('to do: change headline');

        echo <<<EOT
        <div id="headerDir">
        <span>Home > Kommentare > <b>$this->buchname</b></span>   
        </div>
        </div>
        
        <div id="content" class="content">
        <div class="kommentarform">
    
        <h1> Kommentare</h1>
EOT;
        if (isset($_SESSION["userid"])) {
            echo <<<EOT

        <form  action="Kommentar.php" id="upload" method="post">
        <textarea  name="newkommentar" id="newkommi" rows="4" cols="70" placeholder="öffentlich kommentieren..." required></textarea>
  
        <!-- damit wieder die selbe Kommentarseite aufgerufen wird--!>
        <input type="hidden" name = "buchid" value=$this->buchid>

        <p class = "uploadbox"><input type ="submit"   id="hochladen" value = "kommentieren" /></p>
        </form>
        
EOT;
        }
        echo <<<EOT
        
        </div>

        <div class="allekommis">
EOT;
        for ($i = 0; $i < count($this->kommentar); $i++) {
            $kommentar = $this->kommentar[$i]["KOMMENTAR"];
            $nutzername = $this->kommentar[$i]["NUTZERNAME"];
            $datum = $this->kommentar[$i]["DATUM"];

            echo <<<EOT
            
            <div class="kommi">
            <p class="item_nutzer" ><b>$nutzername</b></p> -  <p class="item_datum" >$datum</p>
            <p class="item_kommentar">$kommentar</br></p>
            </div>
             
EOT;
        }

        echo <<<EOT
        </div>

        </div>
EOT;

        $this->generatePageFooter();
    }


    protected function processReceivedData()
    {
        parent::processReceivedData();

        //buchid wird abgefangen um zu schauen welches buch gesucht wird
        if (isset($_POST["buchid"])) {
            if ($_POST["buchid"] != null) {
                $this->buchid = $_POST["buchid"];
            }
        }


        //kommentar wird in die DB geschrieben

        /*nutzerid aus session, timestamp + kommentar wird in die db geschrieben*/
        if (isset($_POST["newkommentar"])) {
            if ($_POST["newkommentar"] != null) {
                $userid = $_SESSION["userid"];
                $kommiinsert = "INSERT INTO `kommentar` (`BUCHID`, `NUTZERID`, `KOMMENTAR`) VALUES ($this->buchid, $userid, '" . $_POST["newkommentar"] . "')  ; ";
                $this->_database->query($kommiinsert);
            }
        }


    }


    public static function main()
    {
        try {
            $page = new Kommentar();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Kommentar::main();
