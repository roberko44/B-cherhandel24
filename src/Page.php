<?php
session_start();

include './controller/sql/DbCrud.php';


abstract class Page
{

    protected $_database = null;
    protected $_recordset;

    protected $sql = null;

    protected function __construct()
    {
        $this->_database = new MySQLi("yoda.media.h-da.de", "pwws20_04", "KeLuGuGu%83", "pwws20db04");
        // $this->_database = new MySQLi("127.0.0.1", "root", "", "buecherhandel24");
        if ($this->_database == null || !$this->_database->connect_errno) {
            $this->_database->set_charset("utf8");
        }

        $this->sql = new DbCrud($this->_database, $this->_recordset);
    }

    protected function __destruct()
    {
    }

    protected function generatePageHeader($headline = "")
    {
        $headline = htmlspecialchars($headline);
        header("Content-type: text/html; charset=UTF-8");

        //EOT Notation benutzt
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <head>
            <link rel="stylesheet" href="./stylesheet/style.css">
            <link rel="icon" href="./img/assets/favicon.ico" type="image/ico">
        </head>
        <body>
        <div id="container">
        <div id="header">
            <div id="headerLogoLoginSearch">
                <div id="logo">
                <img src="./img/assets/Logo.png">
                </div>
                <div id="loginSearch">
EOT;
        if (!isset($_SESSION["userid"])) {
            echo <<<EOT
                <a href="Login.php">
                <button  id="btnLogin">Login</button>
                </a>
EOT;
        } else {
            $userid = $_SESSION["userid"];
            $username = $_SESSION["username"];
            echo <<<EOT
                <a href="Logout.php">
                <button  id="btnLogin">Logout Username: $username</button></br>
                </a>
EOT;
        }


        echo <<<EOT
                <form action="Suche.php" method="post">
                    <input id="searchInput" name="suche" type="text" placeholder="Suche">
                </form>
                </div>
            </div>
            
        <div id="headerNav">
            <a href="Home.php">Home</a>

            <a href="Kategorie.php">Kategorien</a>
            <a href="Ueber_uns.php">Über Uns</a>
            <a href="Impressum.php">Impressum</a>
            <a href="Meine_uploads.php">Meine Uploads</a>
        </div>
       
EOT;
    }


    protected function generatePageFooter()
    {
        echo <<<EOT
        <div id="footer">
            <div id="footerContent">
                <a href="Impressum.php">Impressum</a>
                <span class="footerSpace">|</span>
                <a href="Datenschutz.php">Datenschutz</a>
            </div>
        </div>
        </div>
        </body>
        </html>
EOT;
    }

    protected function processReceivedData()
    {
        if (get_magic_quotes_gpc()) {
            throw new Exception("Bitte schalten Sie magic_quotes_gpc in php.ini aus!");
        }
    }
}
