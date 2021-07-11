<?php

require_once 'Page.php';
include './controller/entity/Buch.php';

class Meine_uploads extends Page
{

    private $meinebücher = array();
    private $message;
    private $err = false;

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
        if (isset($_SESSION["userid"])) {
            $userid = $_SESSION["userid"];
            $this->_recordset = $this->_database->query("select * from buch where NUTZERID = '$userid'"); //SQL Anfrage
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
    }


    protected function generateView()
    {
        $this->getViewData();
        $this->generatePageHeader('to do: change headline');

        // wenn nicht eingeloggt
        if (!isset($_SESSION["userid"])) {
            echo <<<EOT
        <div id="headerDir">
        <span>Home > Meine Uploads</span>   
        </div>
        </div>
        <div id="content">
        <h1> Meine Uploads</h1>


        <a href="Login.php">
        <button id="einlogbutton"> Bitte erst einloggen</button>
        </a>
EOT;



            // wenn eingeloggt
        } else {
            $userid = $_SESSION["userid"];
            $username = $_SESSION["username"];
            echo <<<EOT
        <div id="headerDir">
        <span>Home > Meine Uploads > <b>$username</b></span>   
        </div>
        </div>
        <div id="content">
        <div class="form">
        <h1> Meine Uploads</h1>

        
        <form enctype="multipart/form-data" action="Meine_uploads.php" id="upload" method="post">
        <section class = "uploadform">
        <div class="uploadLeft">
        <div class="divTest">
        <div class = "left">   Bild:                </div>
        <div class = "right"><input type="file"      name="BILD"                 id="buchbild"                                                  required/></div>
        </div>
        
        <div class="divTest">
        <div class = "left">   Buchpdf:                </div>
        <div class = "right"><input type="file"      name="BUCHINHALT"           id="buchinhalt"                                                required/></div>
        </div>

        <div class="divTest">
        <div class = "left">   Name:                </div>
        <div class = "right"><input type="text"      name="BUCHNAME"             id="buchname"               placeholder="Buchname"             required/></div>
        </div>

        <div class="divTest">
        <div class = "left">   Beschreibung:                </div>
        <div class = "right"><input type="text"      name="BESCHREIBUNG"         id="beschreibung"           placeholder="Beschreibung"         required/></div>
        </div>
        </div>

        <div class="uploadRight">
        <div class="divTest">
        <div class = "left">   Autor:                </div>
        <div class = "right"><input type="text"      name="AUTOR"                id="buchautor"              placeholder="Autor"                required/></div>
        </div>

        <div class="divTest">
        <div class = "left">   Erscheinungsdatum:                </div>
        <div class = "right"><input type="date"      name="ERSCHEINUNGSDATUM"    id="buchdatum"                                                 required/></div>
        </div>

        <div class="divTest">
        <div class = "left">   Kategorie:                </div>
        <div class = "right"><select name="KATEGORIE">  <option>Fonds</option>  <option>Aktien</option> <option>Steuern</option> <option>Immobilien</option> </select></div>
        </div>
        
        <div class="divTest">
        <div class = "left">   Unterkategorie:                </div>
        <div class = "right"><input type="text"      name="UNTERKATEGORIE"                                   placeholder="Unterkategorie"       /></div>
        </div>
        </div>
        </div>
        
        
EOT;

            //$class = $this->err ? 'errorclass' : 'rightclass';
            if (isset($_SESSION["err"]) && isset($_SESSION["errMsg"])) {
                $class = $_SESSION["err"] ? 'errorclass' : 'rightclass';
                echo '<div class = ' . $class . '> ' . $_SESSION["errMsg"] . ' </div>';
            }

            echo <<<EOT
        </section>
        <p class = "uploadbox"><input type ="submit"   id="hochladen" value = "hochladen" /></p>
        </form>
      




        <section class="meineBuecher">
        <section class="buecher">
EOT;

            for ($i = 0; $i < count($this->meinebücher); $i++) {
                $this->meinebücher[$i]->showUpload();
            }

            echo <<<EOT
        </section>
        </section>
EOT;
        }


        echo <<<EOT
        </div>
EOT;

        $this->generatePageFooter();
        unset($_SESSION["err"]);
        unset($_SESSION["errMsg"]);
    }


    protected function processReceivedData()
    {
        parent::processReceivedData();

        ///für Löschen
        if (isset($_POST["loeschenbuchid"])) {
            if ($_POST["loeschenbuchid"] != null) {
                $buchid = $_POST["loeschenbuchid"];
                $select  = "delete from kommentar where BUCHID = $buchid;  ";
                $this->_database->query($select);
                $select  = "delete from buch where BUCHID = $buchid;  ";
                $this->_database->query($select);
            }
        }
        ///

        $error = true;

        if (isset($_FILES["BILD"]) && isset($_FILES["BUCHINHALT"]) && isset($_POST["BUCHNAME"]) && isset($_POST["BESCHREIBUNG"]) && isset($_POST["AUTOR"]) && isset($_POST["ERSCHEINUNGSDATUM"]) && isset($_POST["KATEGORIE"]) && isset($_POST["UNTERKATEGORIE"])) {
            if (($_FILES["BILD"]  != null) &&  ($_FILES["BUCHINHALT"]  != null) &&  ($_POST["BUCHNAME"]  != null) &&  ($_POST["BESCHREIBUNG"]  != null) &&  ($_POST["AUTOR"]  != null) &&  ($_POST["ERSCHEINUNGSDATUM"]  != null) &&  ($_POST["KATEGORIE"]  != null)) {
                $error = false;

                $str = $_FILES["BILD"]["type"];
                $find = "image";
                if (strpos($str, $find) !== false) {
                    $uploaddir = 'img/';
                    $uploadfile = $uploaddir . basename($_FILES['BILD']['name']);

                    if (move_uploaded_file($_FILES['BILD']['tmp_name'], $uploadfile)) {
                        var_dump("Datei ist valide und wurde erfolgreich hochgeladen.\n");
                    } else {
                        var_dump("Möglicherweise eine Dateiupload-Attacke!\n");
                    }
                } else {
                    $error = true;
                    $_SESSION["err"] = true;
                    $_SESSION["errMsg"] = "Es ist etwas schiefgelaufen. Checken sie nochmal ihre uploads.";
                    $this->reloadPage();
                }

                $str = $_FILES["BUCHINHALT"]["type"];
                $find = "pdf";
                if (strpos($str, $find) !== false) {
                    $uploaddir = 'pdf/';
                    $uploadfile = $uploaddir . basename($_FILES['BUCHINHALT']['name']);

                    if (move_uploaded_file($_FILES['BUCHINHALT']['tmp_name'], $uploadfile)) {
                        var_dump("Datei ist valide und wurde erfolgreich hochgeladen.\n");
                    } else {
                        var_dump("Möglicherweise eine Dateiupload-Attacke!\n");
                    }
                } else {
                    $error = true;
                    $_SESSION["err"] = true;
                    $_SESSION["errMsg"] = "Es ist etwas schiefgelaufen. Checken sie nochmal ihre uploads.";
                    $this->reloadPage();
                }
            }
        }



        //wenn alles ausgefüllt ist (außer Unterkategorie) dann schreib alles in die DB
        if (!$error) {


            // 1. Damit wir die kategorieid für die Tabelle Unterkategorie haben (weil wir erst die Unterkategorie inserten müssen)
            $insertkategorie = 0;

            if ($_POST["KATEGORIE"] == "Fonds") {
                $insertkategorie = 1;
            }
            if (
                $_POST["KATEGORIE"] == "Aktien"
            ) {
                $insertkategorie = 2;
            }
            if ($_POST["KATEGORIE"] == "Steuern") {
                $insertkategorie = 3;
            }
            if ($_POST["KATEGORIE"] == "Immobilien") {
                $insertkategorie = 4;
            }

            // 2. Die Unterkategorie in die Datenbank inserten
            $insertunterkategorie = $_POST['UNTERKATEGORIE'];
            //Unterkategorieid
            $unterkategorieid = 'NULL';

            //Überprüfen ob Unterkategorie leer gelassen wurde
            if ($_POST["UNTERKATEGORIE"] == null) {
                $insertunterkategorie = "";
            } else {
                // Überprüfen ob Unterkategorie schon existiert
                $select = "SELECT * FROM unterkategorie WHERE UNTERKATEGORIENAME = '$insertunterkategorie' AND KATEGORIEID = '$insertkategorie';";
                $result = $this->sql->getRecords($select);

                if (!$result) {
                    $this->_database->query("INSERT INTO unterkategorie (`KATEGORIEID`, `UNTERKATEGORIENAME`) VALUES ('$insertkategorie'  , '$insertunterkategorie' ); ");
                    $select = "SELECT * FROM unterkategorie WHERE UNTERKATEGORIENAME = '$insertunterkategorie' AND KATEGORIEID = '$insertkategorie';";
                    $result = $this->sql->getRecords($select);
                    $unterkategorieid = $result[0]['UNTERKATEGORIEID'];
                } else {
                    $unterkategorieid = $result[0]['UNTERKATEGORIEID'];
                }
            }


            // 3. Das Buch in die Datenbank inserten
            $userid = $_SESSION["userid"];
            $buchinsert = "INSERT INTO buch (`NUTZERID`, `KATEGORIEID`, `UNTERKATEGORIEID` , `BUCHNAME`, `BESCHREIBUNG`, `ERSCHEINUNGSDATUM`, `AUTOR`, `BILD`, `BUCHINHALT`) VALUES ($userid, $insertkategorie, $unterkategorieid, ' " . $_POST["BUCHNAME"] . " ', '" . $_POST["BESCHREIBUNG"] . "', '" . $_POST["ERSCHEINUNGSDATUM"] . "', '" . $_POST["AUTOR"] . "', 'img/" . $_FILES["BILD"]["name"] . "', 'pdf/" . $_FILES["BUCHINHALT"]["name"] . "'   ); ";

            $this->_database->query($buchinsert);

            $_SESSION["err"] = false;
            $_SESSION["errMsg"] = "Erfolgreich hinzugefügt";
            $this->reloadPage();
        }
    }

    public function reloadPage()
    {
        header("Location: Meine_uploads.php");
        exit();
    }


    public static function main()
    {
        try {
            $page = new Meine_uploads();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Meine_uploads::main();
