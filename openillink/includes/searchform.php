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
// Quick search displayed in all the pages
//
// 17.03.2016, MDV Replaced connector to db from mysql_ to mysqli_
// 01.04.2016 MDV input validation
require_once('connexion.php');
require_once('toolkit.php');

$controlSet = array('id', 'datecom', 'dateenv', 'datefact', 'statut', 'localisation', 'nom', 'email', 'service', 'issn', 'pmid', 'title', 'atitle', 'auteurs', 'reff', 'refb', 'all');
$champ = (isset($_GET['champ']) && isValidInput($_GET['champ'],12,'s',false,$controlSet))?$_GET['champ']:'';

echo "<div class=\"box\"><div class=\"box-content\">\n";
echo "<form action=\"list.php\" method=\"GET\" enctype=\"x-www-form-encoded\" name=\"recherche\">\n";
echo "<input name=\"action\" type=\"hidden\" value=\"recherche\">\n";
echo "<input name=\"folder\" type=\"hidden\" value=\"search\">\n";
echo "&nbsp;&nbsp;&nbsp;<b>Chercher &nbsp;</b>\n";
echo "<select name=\"champ\">\n";
echo "<OPTION VALUE=\"id\"";
if ((isset($champ))&&($champ=='id'))
    echo " selected";
echo ">No de commande</option>\n";
echo "<OPTION VALUE=\"datecom\"";
if ((isset($champ))&&($champ=='datecom'))
    echo " selected";
echo ">Date de la commande (AAAA-MM-JJ)</option>\n";
echo "<OPTION VALUE=\"dateenv\"";
if ((isset($champ))&&($champ=='dateenv'))
    echo " selected";
echo ">Date d'envoi (AAAA-MM-JJ)</option>\n";
echo "<OPTION VALUE=\"datefact\"";
if ((isset($champ))&&($champ=='datefact'))
    echo " selected";
echo ">Date de facturation (AAAA-MM-JJ)</option>\n";
echo "<OPTION VALUE=\"statut\"";
if ((isset($champ))&&($champ=='statut'))
    echo " selected";
echo ">Statut (valeurs numériques)</option>\n";
echo "<OPTION VALUE=\"localisation\"";
if ((isset($champ))&&($champ=='localisation'))
    echo " selected";
echo ">Localisation</option>\n";
echo "<OPTION VALUE=\"nom\"";
if ((isset($champ))&&($champ=='nom'))
    echo " selected";
echo ">Nom du lecteur</option>\n";
echo "<OPTION VALUE=\"email\"";
if ((isset($champ))&&($champ=='email'))
    echo " selected";
echo ">E-mail du lecteur</option>\n";
echo "<OPTION VALUE=\"service\"";
if ((isset($champ))&&($champ=='service'))
    echo " selected";
echo ">Service</option>\n";
echo "<OPTION VALUE=\"issn\"";
if ((isset($champ))&&($champ=='issn'))
    echo " selected";
echo ">ISSN</option>\n";
echo "<OPTION VALUE=\"pmid\"";
if ((isset($champ))&&($champ=='pmid'))
    echo " selected";
echo ">PMID</option>\n";
echo "<OPTION VALUE=\"title\"";
if ((isset($champ))&&($champ=='title'))
    echo " selected";
echo ">Titre du p&eacute;riodique</option>\n";
echo "<OPTION VALUE=\"atitle\"";
if ((isset($champ))&&($champ=='atitle'))
    echo " selected";
echo ">Titre de l'article</option>\n";
echo "<OPTION VALUE=\"auteurs\"";
if ((isset($champ))&&($champ=='auteurs'))
    echo " selected";
echo ">Auteurs</option>\n";
echo "<OPTION VALUE=\"reff\"";
if ((isset($champ))&&($champ=='reff'))
    echo " selected";
echo ">Ref. fournisseur (no Subito...)</option>\n";
echo "<OPTION VALUE=\"refb\"";
if ((isset($champ))&&($champ=='refb'))
    echo " selected";
echo ">Ref. interne à la bibliothèque</option>\n";
echo "<OPTION VALUE=\"all\"";
if ((isset($champ))&&($champ=='all'))
    echo " selected";
echo ">Partout</option>\n";
echo "</select>\n";
echo "<font class=\"titleblack10\"> = &nbsp;\n";
echo "<input name=\"term\" type=\"text\" size=\"30\" value=\"";
if (isset($_GET['term']))
    echo $_GET['term'];
echo "\">\n";
echo "&nbsp;<input type=\"submit\" value=\"Ok\">\n";
echo "&nbsp;&nbsp;&nbsp;<a href=\"#\" class=\"info\" onclick=\"return false\">[Codes des étapes]<span>\n";
$reqstatus="SELECT code, title1 FROM status ORDER BY code ASC";
$resultstatus = dbquery($reqstatus);
while ($rowstatus = iimysqli_result_fetch_array($resultstatus)){
    echo $rowstatus["title1"] . " : " . $rowstatus["code"] . "<br/>\n";
}
echo "</span></a>&nbsp;\n";
echo "</form>\n";
echo "</div></div><div class=\"box-footer\"><div class=\"box-footer-right\"></div></div>\n";
echo "<br/>\n";
?>
