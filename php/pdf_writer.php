<?php

require_once BASEPATH . "/php/_config.php";
require(BASEPATH . '/php/fpdf182/MultiCellHTML.php');

class PDF extends PDF_HTML
{
    var $avoid_linebreak = 0;
    var $medium_id = 0;
    var $strain = null;
    var $mod_id = null;
    var $pdo = null;
    var $copyright = "DSMZ";

    function __construct()
    {
        parent::__construct();
        global $pdo;
        $this->pdo = $pdo;
    }



    function Header()
    {
        $this->Image('img/header_coi.png', 20, 15, 160);

        $this->SetFont('Helvetica', 'B', 10);
        // Move to the right
        // $this->Cell(80);
        // Title
        // $this->SetTextColor(128);
        // $this->Cell(0, 10, $title, 0, 0, 'L');
        // Line break
        $this->Ln(23);
    }

    function Footer()
    {
        $year = date("Y");
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Helvetica italic 8
        $this->SetFont('Helvetica', 'I', 8);
        // Text color in gray
        $this->SetTextColor(128);

        $foot = "© " . $this->copyright . " - All rights reserved";
        $cr = utf8_decode($foot);

        $this->Cell(0, 10, $cr, 0, 0, 'L');
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . " of {nb}", 0, 0, 'R');
    }


    function WriteHtmlCell($cellWidth, $html)
    {
        $rm = $this->rMargin;
        $this->SetRightMargin($this->w - $this->GetX() - $cellWidth);
        $this->WriteHtml($html);
        $this->SetRightMargin($rm);
    }

    // function PrintStep($steps)
    // {
    //     $this->SetFont('Helvetica', '', 10);
    //     foreach ($steps as $step) {
    //         $input = utf8_decode($step);
    //         if ($input === null) return null;
    //         $text = preg_replace('/(Ac|Ag|Al|Am|Ar|As|At|Au|B|Ba|Be|Bh|Bi|Bk|Br|C|Ca|Cd|Ce|Cf|Cl|Cm|Co|Cr|Cs|Cu|Ds|Db|Dy|Er|Es|Eu|F|Fe|Fm|Fr|Ga|Gd|Ge|H|He|Hf|Hg|Ho|Hs|I|In|Ir|K|Kr|La|Li|Lr|Lu|Md|Mg|Mn|Mo|Mt|N|Na|Nb|Nd|Ne|Ni|No|Np|O|Os|P|Pa|Pb|Pd|Pm|Po|Pr|Pt|Pu|Ra|Rb|Re|Rf|Rg|Rh|Rn|Ru|S|Sb|Sc|Se|Sg|Si|Sm|Sn|Sr|Ta|Tb|Tc|Te|Th|Ti|Tl|Tm|U|V|W|Xe|Y|Yb|Zn|Zr|\))(\d+)/', '$1<sub>$2</sub>', $input);
    //         $text = preg_replace('/(DSM\s\d+)/', '<u>$1</u>', $text);

    //         $this->WriteHTML($text . "<br>");
    //         $this->Ln(1);
    //     }
    // }


    function AcceptPageBreak()
    {
        if (empty($this->avoid_linebreak)) {
            return true;
        } else {
            return false;
        }
    }

}
