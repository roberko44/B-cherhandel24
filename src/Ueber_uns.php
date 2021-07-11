<?php

require_once 'Page.php';


class Ueber_uns extends Page
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
        <span>Home > <b>Über uns</b></span>   
        </div>
        </div>

        <div id="content">
        <h1> Über uns</h1>
        Wir von Bücherhandel24 wollen den Nutzern ein Bücherarchiv zur Verfügung stellen, in denen
        sie alles über die Themen Wirtschaft, Finanzen und Investieren erfahren. </br>
        Die Seite soll jungen Menschen wie Schülern, Auszubildenden und Studenten aufmerksam auf das
        Thema Geld und sparen machen. </br>
        Wir werden selber Bücher hochladen, aber auch
        berühmte Bücher die sich mit dem jeweiligen Thema auseinandersetzen, auf unsere Seite
        aufzählen und bekannt machen. </br>
        Die hochgeladenen Bücher kann jeder, auch nicht registrierte Nutzer, downloaden und für sich nutzen. Es besteht auch die Möglichkeit, dass
        Nutzer die registriert sind, neue Sachen uploaden und auch neue Bücher vorstellen können.

            
        
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
            $page = new Ueber_uns();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Ueber_uns::main();
