﻿<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// OpenLinker is a web based library system designed to manage 
// journals, ILL, document delivery and OpenURL links
// 
// Copyright (C) 2012, Pablo Iriarte
// 
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
// 
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// Order form for Basel Library network
// 
// The follow customer values must be coded in the link URL :
// my_customer_code
// my_customer_password
// my_customer_name
// 
// 29.03.2016 MDV add input validation using checkInput defined into toolkit.php
require_once ("includes/config.php");
require_once ("includes/authcookie.php");
require_once ("includes/connexion.php");
require_once ("includes/toolkit.php");

if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user")){
    $customerCode = (isset($_GET['my_customer_code']) && isValidInput($_GET['my_customer_code'],10))? $_GET['my_customer_code']:"";
    $meduid = $enreg['PMID'];
    if (empty($meduid)) {
        $meduid = (isset($_GET['meduid']) && isValidInput($_GET['meduid'],20,'s',false))?$_GET['meduid']:"";
    }
    $customerPassword = (isset($_GET['my_customer_password']) && isValidInput($_GET['my_customer_password'],20,'s',false))? $_GET['my_customer_password']:"";
    $customerName = (isset($_GET['my_customer_name']) && isValidInput($_GET['my_customer_name'],20,'s',false))? $_GET['my_customer_name']:"";
    $journal = stripslashes($enreg['titre_periodique']);
    $issn = urlencode(stripslashes($enreg['issn']));
    $year = urlencode(stripslashes($enreg['annee']));
    $volume = urlencode(stripslashes($enreg['volume']));
    if (!isset($issue2))
        $issue2 = urlencode(stripslashes($enreg['numero']));
    $pages = urlencode(stripslashes($enreg['pages']));
    $author = stripslashes($enreg['auteurs']);
    $article = stripslashes($enreg['titre_article']);
/*
    $commentaire = (isset($enreg['nom']) && isValidInput($enreg['nom'],100,'s',false))? urlencode(stripslashes($enreg['nom'].", ")):"";
    $commentaire .= (isset($enreg['prenom']) && isValidInput($enreg['prenom'],100,'s',false))?urlencode(stripslashes($enreg['prenom']))." ":"";
*/
    $commentaire = /*(isset($enreg['illinkid']) && isValidInput($enreg['illinkid'],8,'i',false))? */"Ref interne:"./*urlencode(*/stripslashes($enreg['illinkid'])/*):""*/;
    echo "<h2>Envoi de la commande au réseau Bâle/Berne</h2>\n";
    echo "<FORM method=\"post\" name=\"ILL\" action=\"http://www.ub.unibas.ch/cgi-bin/sfx_dod_m.pl\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"action\" value=\"submit\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"uid\" VALUE=\"$customerCode\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"pwd\" VALUE=\"$customerPassword\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"Source\" VALUE=\"OpenILLink ( $customerName )\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"pickup\" VALUE=\"EMAIL - E-Mail\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"sfxurl\" value=\"#sfxurl\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"meduid\" VALUE=\"$meduid\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"Journal\" VALUE=\"$journal\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"ISSN\" VALUE=\"$issn\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"Year\" VALUE=\"$year\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"Volume\" VALUE=\"$volume\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"Issue\" VALUE=\"$issue2\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"Pages\" VALUE=\"$pages\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"Author\" VALUE=\"$author\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"Article\" VALUE=\"$article\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"bemerkung\" VALUE=\"$commentaire\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"legal\" VALUE=\"on\">\n";
    echo "<INPUT TYPE=\"hidden\" NAME=\"B1\" VALUE=\"Bestellung abschicken\">\n";
    echo "</FORM>\n";
}
else{
    require_once('../includes/config.php');
    require_once('../includes/translations.php');
    require_once ("../includes/header.php");
    require_once ("../includes/loginfail.php");
    require_once ("../includes/footer.php");
}
?>
