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
        <h2> Finanzstudi GbR </h2>
            
            <p> Kasinostr. 85 </br>
            64293 Darmstadt </br>
            E-Mail: info@finanzstudi.de </br>
            www: https://www.finanzstudi.de </br>
            Vertretungsberechtigter Geschäftsführer: Dilara Köten & Zernab Zeervi </br>
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
