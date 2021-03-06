﻿<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017 CHUV.
// Original author(s): Pablo Iriarte <pablo@iriarte.ch>
// Other contributors are listed in the AUTHORS file at the top-level
// directory of this distribution.
// 
// OpenILLink is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// OpenILLink is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with OpenILLink.  If not, see <http://www.gnu.org/licenses/>.
// 
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// links table : edit form
// 
require_once ("config.php");
require_once ("authcookie.php");
require_once ("connexion.php");
require_once ("includes/toolkit.php");

$id="";
$montitle = "Gestion des liens";
$id= ((!empty($_GET['id'])) && isValidInput($_GET['id'],11,'s',false)) ? $_GET['id'] : "";
if (!empty($_COOKIE['illinkid'])){
    if (($monaut == "admin")||($monaut == "sadmin")){
        if ($id!=""){
            $myhtmltitle = $configname[$lang] . " : édition du lien " . $id;
            $montitle = "Gestion des liens : édition de la fiche " . $id;
            require ("headeradmin.php");
            $req = "SELECT * FROM links WHERE id = ?";
            $result = dbquery($req, array($id), 'i');
            $nb = iimysqli_num_rows($result);
            if ($nb == 1) {
                echo "<h1>" . $montitle . "</h1>\n";
                echo "<br /></b>";
                echo "<ul>\n";
                $enreg = iimysqli_result_fetch_array($result);
                $linkid = $enreg['id'];
                $linktitle = $enreg['title'];
                $linkurl = $enreg['url'];
                $linksearch_issn = $enreg['search_issn'];
                $linksearch_isbn = $enreg['search_isbn'];
                $linksearch_ptitle = $enreg['search_ptitle'];
                $linksearch_btitle = $enreg['search_btitle'];
                $linksearch_atitle = $enreg['search_atitle'];
                $linkorder_ext = $enreg['order_ext'];
                $linkorder_form = $enreg['order_form'];
                $linkopenurl = $enreg['openurl'];
                $linklibrary = $enreg['library'];
                $linkactive = $enreg['active'];
                $linkordonnancement = $enreg['ordonnancement'];
                $linkurl_encoded = $enreg['url_encoded'];
                $linkskip_words = $enreg['skip_words'];
                $linkskip_txt_after_mark = $enreg['skip_txt_after_mark'];
                echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
                echo "<input name=\"table\" type=\"hidden\" value=\"links\">\n";
                echo "<input name=\"id\" type=\"hidden\" value=\"".htmlspecialchars($linkid)."\">\n";
                echo "<input name=\"action\" type=\"hidden\" value=\"update\">\n";
                echo "<table id=\"hor-zebra\" class=\"genericEditFormOIL\">\n";
                echo "<tr><td></td><td><input type=\"submit\" value=\"Enregistrer les modifications\">\n";
                echo "&nbsp;&nbsp;<input type=\"button\" value=\"Annuler\" onClick=\"self.location='list.php?table=links'\">\n";
                echo "&nbsp;&nbsp;<input type=\"button\" value=\"Supprimer\" onClick=\"self.location='update.php?action=delete&table=links&id=" . $linkid . "'\"></td></tr>\n";
                echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
                echo "<tr><td><b>Nom *</b></td><td>\n";
                echo "<input name=\"title\" type=\"text\" size=\"60\" value=\"" . htmlspecialchars($linktitle) . "\"></td></tr>\n";
                echo "</td></tr>\n";
                echo "<tr><td><b>URL *</b></td><td><input name=\"url\" type=\"text\" size=\"50\" value=\"" . htmlspecialchars($linkurl) . "\">&nbsp;&nbsp;";
                echo "<input name=\"openurl\" value=\"1\" type=\"checkbox\"";
                if ($linkopenurl == 1)
                    echo " checked";
                echo "> OpenURL</td></tr>\n";
                /* MDV - 15.12.2015 : added line to update link position in the displayed list of link  in the table*/
                echo "<tr><td><b>Position dans la liste</b></td><td><input name=\"ordonnancement\" type=\"text\" size=\"5\" value=\"" . htmlspecialchars($linkordonnancement). "\">&nbsp;&nbsp;</td></tr>";
                echo "<tr><td><b>Lien de recherche par identifiant</b></td><td>";
                echo "<input name=\"search_issn\" value=\"1\" type=\"checkbox\"";
                if ($linksearch_issn == 1)
                    echo " checked";
                echo ">ISSN &nbsp;&nbsp;|&nbsp;&nbsp; ";
                echo "<input name=\"search_isbn\" value=\"1\" type=\"checkbox\"";
                if ($linksearch_isbn == 1)
                    echo " checked";
                echo ">ISBN";
                echo "</td></tr>\n";
                echo "<tr><td><b>Lien de recherche par titre</b></td><td>";
                echo "<input name=\"search_ptitle\" value=\"1\" type=\"checkbox\"";
                if ($linksearch_ptitle == 1)
                    echo " checked";
                echo ">de périodique&nbsp;&nbsp;|&nbsp;&nbsp; ";
                echo "<input name=\"search_btitle\" value=\"1\" type=\"checkbox\"";
                if ($linksearch_btitle == 1)
                    echo " checked";
                echo ">du livre&nbsp;&nbsp;|&nbsp;&nbsp; ";
                echo "<input name=\"search_atitle\" value=\"1\" type=\"checkbox\"";
                if ($linksearch_atitle == 1)
                    echo " checked";
                echo ">Titre d'article ou chapitre\n";
                echo "</td></tr>\n";
                echo "<tr><td><b>Lien de commande</b></td><td>";
                echo "<input name=\"order_ext\" value=\"1\" type=\"checkbox\"";
                if ($linkorder_ext == 1)
                    echo " checked";
                echo ">Formulaire externe &nbsp;&nbsp;|&nbsp;&nbsp; ";
                echo "<input name=\"order_form\" value=\"1\" type=\"checkbox\"";
                if ($linkorder_form == 1)
                    echo " checked";
                echo ">Formulaire interne\n";
                echo "</td></tr>\n";
                echo "<tr><td><b>Bibliothèque d'attribution</b></td><td>\n";
                echo "<select name=\"library\">\n";
                $reqlibraries="SELECT code, name1, name2, name3, name4, name5 FROM libraries ORDER BY name1 ASC";
                $optionslibraries="";
                $resultlibraries = dbquery($reqlibraries);
                $nblibs = iimysqli_num_rows($resultlibraries);
                if ($nblibs > 0){
                    while ($rowlibraries = iimysqli_result_fetch_array($resultlibraries)){
                        $codelibraries = $rowlibraries["code"];
                        $namelibraries["fr"] = $rowlibraries["name1"];
                        $namelibraries["en"] = $rowlibraries["name2"];
                        $namelibraries["de"] = $rowlibraries["name3"];
                        $namelibraries["it"] = $rowlibraries["name4"];
                        $namelibraries["es"] = $rowlibraries["name5"];
                        $optionslibraries.="<option value=\"" . htmlspecialchars($codelibraries) . "\"";
                        if ($linklibrary == $codelibraries)
                            $optionslibraries.=" selected";
                        $optionslibraries.=">" . htmlspecialchars($namelibraries[$lang]) . "</option>\n";
                    }
                    echo $optionslibraries;
                }
                echo "</select></td></tr>\n";
                echo "<tr><td><b>Transformer les arguments de l'url d'UTF-8 vers ISO-8859-1</b></td>".
                '<td><input type="checkbox"  value="1" '.
                (($linkurl_encoded == 1)?' checked="checked" ':'').
                'name="url_encoded" id="url_encoded" /></td></tr>';
                echo "<tr><td><b>Ignorer les mots du titre du périodique/livre</b></td>".
                "<td><input name=\"skip_words\" value=\"1\" type=\"checkbox\"".
                ($linkskip_words == 1?" checked":'')."> non signifiants ('of', 'the', 'The', '&', 'and', '-') | ".
                "<input name=\"skip_txt_after_mark\" value=\"1\" type=\"checkbox\"".
                ($linkskip_txt_after_mark == 1?" checked":'')."> après le symbole (':', '=', '.', ';', '(')</td></tr>\n";
                echo "<tr><td><b>Lien actif</b></td><td><input name=\"active\" value=\"1\" type=\"checkbox\"";
                if ($linkactive == 1)
                    echo " checked";
                echo "></td></tr>\n";
                echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
                echo "<tr><td></td><td><input type=\"submit\" value=\"Enregistrer les modifications\">\n";
                echo "&nbsp;&nbsp;<input type=\"button\" value=\"Annuler\" onClick=\"self.location='list.php?table=links'\">\n";
                echo "&nbsp;&nbsp;<input type=\"button\" value=\"Supprimer\" onClick=\"self.location='update.php?action=delete&table=links&id=" . $linkid . "'\"></td></tr>\n";
                echo "</table>\n";
                echo "</form><br /><br />\n";
                require ("footer.php");
            }
            else{
                echo "<center><br/><b><font color=\"red\">\n";
                echo "La fiche " . $id . " n'a pas été trouvé dans la base.</b></font>\n";
                echo "<br /><br /><b>Veuillez relancer de nouveau votre recherche ou contactez l'administrateur de la base : " . $configemail . "</b></center><br /><br /><br /><br />\n";
                require ("footer.php");
            }
        }
        else{
            require ("header.php");
            //require ("menurech.php");
            echo "<center><br/><b><font color=\"red\">\n";
            echo "La fiche n'a pas été trouvé dans la base.</b></font>\n";
            echo "<br /><br /><b>Veuillez relancer de nouveau votre recherche ou contactez l'administrateur de la base : " . $configemail . "</b></center><br /><br /><br /><br />\n";
            echo "<br /><br />\n";
            echo "</ul>\n";
            echo "\n";
            require ("footer.php");
        }
    }
    else{
        require ("header.php");
        echo "<center><br/><b><font color=\"red\">\n";
        echo "Vos droits sont insuffisants pour éditer cette fiche</b></font></center><br /><br /><br /><br />\n";
        require ("footer.php");
    }
}
else{
    require ("header.php");
    require ("loginfail.php");
    require ("footer.php");
}
?>
