<?php

require_once './Page.php';
include './controller/entity/Buch.php';

class Bearbeiten extends Page
{

    private $buchid;
    private $meinebücher = array();
    private $allebuchnamen = array();

    private $buchname;
    private $beschreibung;
    private $erscheinungsdatum;
    private $autor;
    private $bild;
    private $buchinhalt;
    private $kategorieid;
    private $unterkategorieid;
    private $kategorie;
    private $unterkategorie;
    private $kat1;
    private $kat2;
    private $kat3;
    private $kat4;
    private $subcategoryid;

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
        $sqlanfrage = "select * from buch where BUCHID = '$this->buchid';";
        $this->_recordset = $this->_database->query($sqlanfrage); //SQL Anfrage
        if (!$this->_recordset) {
            printf("Query failed: %s\n", $this->_recordset->error);
            exit();
        }

        while ($Record =  $this->_recordset->fetch_assoc()) {

            $buch = new Buch($Record);
            $this->meinebücher[] = $buch;
        }




        ////////BUCHNAME FÜR SPAN AUS DB HOLEN////////////////////////////////////////////////////
        $sqlanfrage = "select * from buch where BUCHID = '$this->buchid';";
        $this->_recordset = $this->_database->query($sqlanfrage); //SQL Anfrage
        if (!$this->_recordset) {
            printf("Query failed: %s\n", $this->_recordset->error);
            exit();
        }

        while ($Record = $this->_recordset->fetch_assoc()) {

            $tempArray = array(
                "BUCHNAME" => $Record["BUCHNAME"],
                "BESCHREIBUNG" => $Record["BESCHREIBUNG"],
                "ERSCHEINUNGSDATUM" => $Record["ERSCHEINUNGSDATUM"],
                "AUTOR" => $Record["AUTOR"],
                "BILD" => $Record["BILD"],
                "BUCHINHALT" => $Record["BUCHINHALT"],
                "KATEGORIEID"  => $Record["KATEGORIEID"],
                "UNTERKATEGORIEID"  => $Record["UNTERKATEGORIEID"]
            );

            $this->allebuchnamen[] = $tempArray;
        }
        $this->buchname = $this->allebuchnamen[0]["BUCHNAME"];   // an Stelle 0 weil ja eh nur 1 Buchname selected wurde
        $this->beschreibung = $this->allebuchnamen[0]["BESCHREIBUNG"];
        $this->erscheinungsdatum = $this->allebuchnamen[0]["ERSCHEINUNGSDATUM"];
        $this->autor = $this->allebuchnamen[0]["AUTOR"];
        $this->bild = $this->allebuchnamen[0]["BILD"];
        $this->buchinhalt = $this->allebuchnamen[0]["BUCHINHALT"];
        $this->kategorieid = $this->allebuchnamen[0]["KATEGORIEID"];
        $this->unterkategorieid = $this->allebuchnamen[0]["UNTERKATEGORIEID"];
        //////////////////////////////////////////////////////////////////////////////////////////


        ////////KATEGORIE AUS DB HOLEN////////////////////////////////////////////////////
        $sqlAnfrage = "select * from kategorie where KATEGORIEID = $this->kategorieid";
        $this->_recordset = $this->_database->query($sqlAnfrage);
        if (!$this->_recordset) {
            printf("Query failed.");
        }

        while ($record = $this->_recordset->fetch_assoc()) {
            $this->kategorie = $record["KATEGORIENAME"];
        }
        if ($this->kategorie == "Fonds") {
            $this->kat1 = "selected";
        }
        if ($this->kategorie == "Aktien") {
            $this->kat2 = "selected";
        }
        if ($this->kategorie == "Steuern") {
            $this->kat3 = "selected";
        }
        if ($this->kategorie == "Immobilien") {
            $this->kat4 = "selected";
        }

        //////////////////////////////////////////////////////////////////////////////////////////



        ////////UNTERKATEGORIE AUS DB HOLEN////////////////////////////////////////////////////
        $sqlAnfrage = "select * from unterkategorie where UNTERKATEGORIEID = $this->unterkategorieid ";
        $this->_recordset = $this->_database->query($sqlAnfrage);
        if ($this->_recordset) {
            while ($record = $this->_recordset->fetch_assoc()) {
                $this->unterkategorie = $record["UNTERKATEGORIENAME"];
            }
        }
        if (!$this->unterkategorie) {
            $this->unterkategorie = "/";
        }
        //////////////////////////////////////////////////////////////////////////////////////////

    }


    protected function generateView()
    {
        $this->getViewData();
        $this->generatePageHeader('to do: change headline');

        echo <<<EOT
        <div id="headerDir">
        <span>Home > Bearbeiten > <b>$this->buchname</b></span>   
        </div>
        </div>

        <h1> Bearbeiten </h1>
        <div id="content" class ="content">
        <div id="bearbeitungsform">



        <div id="left">
EOT;
        for ($i = 0; $i < count($this->meinebücher); $i++) {
            $this->meinebücher[$i]->showBearbeitungsBild();
        }

        echo <<<EOT
        </div>


        <div id="right">
        <form enctype="multipart/form-data" action="Bearbeiten.php" id="upload" method="post">

        <p><b>Neues Buchbild: </b></br> <input type="file" name="BILD" id="buchbild"/></p>
        
        <input type="hidden" name = "OLD_BILD" value="$this->bild">

        <p><b>Neuer Buchinhalt: </b></br> <input type="file" name="BUCHINHALT"  id="buchinhalt" /></p>

        <input type="hidden" name = "OLD_BUCHINHALT" value="$this->buchinhalt">

        <p><b>Neuer Buchname: </b></br> <input id="show_input" type="text" value="$this->buchname" name="BUCHNAME"  id="buchname"  placeholder="Buchname" required ></p>

        <p><b>Neue Beschreibung: </b></br> <input id="show_input" type="text"  value="$this->beschreibung" name="BESCHREIBUNG"  id="beschreibung"   placeholder="Beschreibung" required /></p>

        <p><b>Neues Erscheinungsdatum: </b></br> <input type="date" value="$this->erscheinungsdatum"  name="ERSCHEINUNGSDATUM"    id="buchdatum" required /></p>

        <p><b>Neue Kategorie: </b></br> <select name="KATEGORIE">  <option $this->kat1>Fonds</option>  <option $this->kat2> Aktien</option> <option $this->kat3>Steuern</option> <option $this->kat4>Immobilien</option> </select> </p>

        <p><b>Neue Unterkategorie: </b></br> <input id="show_input" type="text" value="$this->unterkategorie" name="UNTERKATEGORIE"   placeholder="Unterkategorie" required /></p>

        <p><b>Neuer Autor: </b></br> <input id="show_input" type="text" value="$this->autor"  name="AUTOR"   id="buchautor"   placeholder="Autor" required /></p>

        <input type="hidden" name = "buchid" value=$this->buchid>

        <p><input type ="submit"   id="bearbeiten" value = "speichern" /></p>

        </form>
        </div>

        </div>
        </div>
EOT;

        $this->generatePageFooter();
    }


    protected function processReceivedData()
    {
        parent::processReceivedData();


        if (isset($_POST["buchid"])) {
            if ($_POST["buchid"] != null) {
                $this->buchid = $_POST["buchid"];
            }
        }

        if (isset($_FILES["BILD"]) && isset($_FILES["BUCHINHALT"]) && -isset($_POST["BUCHNAME"]) && isset($_POST["BESCHREIBUNG"]) && isset($_POST["AUTOR"]) && isset($_POST["ERSCHEINUNGSDATUM"]) && isset($_POST["KATEGORIE"]) && isset($_POST["UNTERKATEGORIE"])) {
            if (($_POST["BUCHNAME"]  != null) &&  ($_POST["BESCHREIBUNG"]  != null) &&  ($_POST["AUTOR"]  != null) &&  ($_POST["ERSCHEINUNGSDATUM"]  != null) &&  ($_POST["KATEGORIE"]  != null)) {
                $bookname = $_POST["BUCHNAME"];
                $desc = $_POST["BESCHREIBUNG"];
                $author = $_POST["AUTOR"];
                $date = $_POST["ERSCHEINUNGSDATUM"];
                $subcategory = $_POST["UNTERKATEGORIE"];

                if ($_POST["KATEGORIE"] == "Fonds") {
                    $categoryid = 1;
                }
                if ($_POST["KATEGORIE"] == "Aktien") {
                    $categoryid = 2;
                }
                if ($_POST["KATEGORIE"] == "Steuern") {
                    $categoryid = 3;
                }
                if ($_POST["KATEGORIE"] == "Immobilien") {
                    $categoryid = 4;
                }

                $uploadBild = "";
                $uploaddir = "img/";
                if (($_FILES["BILD"]["name"]  != "")) {
                    $uploadBild = $uploaddir . basename($_FILES['BILD']['name']);
                    if (move_uploaded_file($_FILES['BILD']['tmp_name'], $uploadBild)) {
                        //var_dump("Datei ist valide und wurde erfolgreich hochgeladen.\n");
                    } else {
                        //var_dump("Möglicherweise eine Dateiupload-Attacke!\n");
                    }
                } else {
                    $uploadBild = $_POST["OLD_BILD"];
                }

                $uploadPDF = "";
                $uploaddir2 = "pdf/";
                if (($_FILES["BUCHINHALT"]["name"]  != "")) {
                    $uploadPDF = $uploaddir2 . basename($_FILES['BUCHINHALT']['name']);
                    if (move_uploaded_file($_FILES['BUCHINHALT']['tmp_name'], $uploadPDF)) {
                        //var_dump("Datei ist valide und wurde erfolgreich hochgeladen.\n");
                    } else {
                        //var_dump("Möglicherweise eine Dateiupload-Attacke!\n");
                    }
                } else {
                    $uploadPDF = $_POST["OLD_BUCHINHALT"];
                }

                if ($subcategory != '/' || $subcategory != '') {
                    $select = "select * from unterkategorie WHERE KATEGORIEID = $categoryid AND  UNTERKATEGORIENAME = '$subcategory'";
                    $this->_recordset = $this->_database->query($select); //SQL Anfrage
                    if ($this->_recordset->num_rows == 0) {
                        $select = "INSERT INTO unterkategorie (KATEGORIEID, UNTERKATEGORIENAME) VALUES ( $categoryid, '" . $subcategory . "');";
                        $this->_recordset = $this->_database->query($select);

                        $select = "select * from unterkategorie WHERE KATEGORIEID = $categoryid AND  UNTERKATEGORIENAME = '$subcategory'";
                        $this->_recordset = $this->_database->query($select); //SQL Anfrage
                    }

                    while ($Record = $this->_recordset->fetch_assoc()) {
                        $this->subcategoryid = $Record["UNTERKATEGORIEID"];
                    }
                }

                $select = "UPDATE buch SET BUCHINHALT = '$uploadPDF', BILD = '$uploadBild' , BUCHNAME = '$bookname', BESCHREIBUNG = '$desc' ,AUTOR = '$author', ERSCHEINUNGSDATUM = '$date', KATEGORIEID = '$categoryid', UNTERKATEGORIEID = '$this->subcategoryid' WHERE BUCHID = $this->buchid;";
                $this->_database->query($select);
            }

        }
    }


    public static function main()
    {
        try {
            $page = new Bearbeiten();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Bearbeiten::main();
