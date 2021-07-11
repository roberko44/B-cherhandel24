<?php

require_once 'Page.php';


class Impressum extends Page
{

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
    }


    protected function generateView()
    {
        $this->getViewData();
        $this->generatePageHeader('to do: change headline');

        echo <<<EOT
        <div id="headerDir">
        <span>Home > <b>Impressum</b></span>   
        </div>
        </div>

        <div id="content">
        <h1> Impressum </h1>
        <h2> Bücherhandel24 GmbH </h2>
            
            <p> Kasinostr. 85 </br>
            64293 Darmstadt </br>
            E-Mail: info@buecherhandel24.de </br>
            www: https://www.buecherhandel24.de </br>
            Vertretungsberechtigter Geschäftsführer: Hauke Winkler </br>
            HRB 167263 </br>
            Amtsgericht Darmstadt </p>
            
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
            $page = new Impressum();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Impressum::main();
