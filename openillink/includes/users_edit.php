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
// Table users : formulaire de modification
// 
require ("config.php");
require ("authcookie.php");
require_once ("connexion.php");
require_once ("toolkit.php");

$id="";
$action="";
$montitle = "Gestion des utilisateurs";
if (!empty($_GET['id'])) {
	$id=isValidInput($_GET['id'],11,'i',false)?$_GET['id']:'';
}
$validActionSet = array('updateprofile', 'update', 'new');
if (!empty($_GET['action'])) {
	$action=isValidInput($_GET['action'],15,'s',false,$validActionSet)?$_GET['action']:'';
}
if (!empty($_COOKIE['illinkid'])){
    if (($monaut == "admin")||($monaut == "sadmin")||(($monaut == "user")&&($action == "updateprofile"))){
        if (($action == "updateprofile")||($id!="")){
            if (($id!="")&&(($monaut == "admin")||($monaut == "sadmin"))){
                $req = "SELECT * FROM users WHERE users.user_id = ?";
                $myhtmltitle = $configname[$lang] . " : édition de la fiche utilisateur " . htmlspecialchars($id);
                $montitle = "Gestion des utilisateurs : édition de la fiche utilisateur " . htmlspecialchars($id);
				$params = array($id);
				$param_types = "i";
            }
            if ($action == "updateprofile"){
                $req = "SELECT * FROM users WHERE users.login = ?";
                $myhtmltitle = $configname[$lang] . " : édition de mon profil";
                $montitle = "Gestion des utilisateurs : édition de mon profil (" . htmlspecialchars($monlog) . ")";
				$params = array($monlog);
				$param_types = "s";
            }
            require ("headeradmin.php");
            $result = dbquery($req, $params, $param_types);
            $nb = iimysqli_num_rows($result);
            if ($nb == 1){
                echo "<h1>" . $montitle . "</h1>\n";
                echo "<br /></b>";
                echo "<ul>\n";
                $enreg = iimysqli_result_fetch_array($result);
                $user_id = $enreg['user_id'];
                $name = $enreg['name'];
                $email = $enreg['email'];
                $login = $enreg['login'];
                $status = $enreg['status'];
                $admin = $enreg['admin'];
                $password = $enreg['password'];
                $library = $enreg['library'];
                if (($monaut != "sadmin")&&($monlog != $login)&&($admin < 3)){
                    echo "<center><br/><b><font color=\"red\">\n";
                    echo "Vos droits sont insuffisants pour éditer cette fiche</b></font></center><br /><br /><br /><br />\n";
                    require ("footer.php");
                }
                else{
                    echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
                    echo "<input name=\"table\" type=\"hidden\" value=\"users\">\n";
                    echo "<input name=\"id\" type=\"hidden\" value=\"".htmlspecialchars($user_id)."\">\n";
                    if (($monaut == "admin")||($monaut == "sadmin"))
                        echo "<input name=\"action\" type=\"hidden\" value=\"update\">\n";
                    if (($monaut == "user")&&($action == "updateprofile"))
                        echo "<input name=\"action\" type=\"hidden\" value=\"updateprofile\">\n";
                    echo "<table id=\"hor-zebra\">\n";
/*
                    echo "<tr><td></td><td><input type=\"submit\" value=\"Enregistrer les modifications\">\n";
                    echo "&nbsp;&nbsp;<input type=\"button\" value=\"Annuler\" onClick=\"self.location='list.php?table=users'\">\n";
                    echo "&nbsp;&nbsp;<input type=\"button\" value=\"Supprimer\" onClick=\"self.location='update.php?action=delete&table=users&id=" . $user_id . "'\"></td></tr>\n";
                    echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
*/
                    if (($monaut == "admin")||($monaut == "sadmin")){
                        echo "<tr><td class=\"odd\"><b>Nom *</b></td><td class=\"odd\"><input name=\"name\" type=\"text\" size=\"60\" value=\"".htmlspecialchars($name)."\"></td></tr>\n";
                    }
                    echo "<tr><td><b>E-mail</b></td><td><input name=\"email\" type=\"text\" size=\"60\" value=\"".htmlspecialchars($email)."\"></td></tr>\n";
                    if (($monaut == "admin")||($monaut == "sadmin")){
                        echo "<tr><td class=\"odd\"><b>Login *</b></td><td class=\"odd\"><input name=\"login\" type=\"text\" size=\"60\" value=\"".htmlspecialchars($login)."\"></td></tr>\n";
                    }
                    if (($monaut == "admin")||($monaut == "sadmin")){
                        echo "<tr><td><b>Status *</b></td><td><input type=\"radio\" name=\"status\" value=\"1\"/";
                        if ($status == 1)
                            echo " checked";
                        echo "> Actif  |  <input type=\"radio\" name=\"status\" value=\"0\"/";
                        if ($status == 0)
                            echo " checked";
                        echo "> Inactif</td></tr>\n";
                        echo "<tr><td class=\"odd\"><b>Droits *</b></td><td class=\"odd\">\n";
                        echo "<select name=\"admin\" id=\"admin\">\n";
                        echo "<option value=\"1\"";
                        if ($admin == 1)
                            echo " selected";
						if ($monaut != "sadmin") {
							echo " disabled";
						}
                        echo ">Super administrateur</option>\n";
                        echo "<option value=\"2\"";
                        if ($admin == 2)
                            echo " selected";
                        echo ">Administrateur</option>\n";

						echo "<option value=\"3\"";
						if ($admin == 3)
							echo " selected";
						echo ">Collaborateur</option>\n";
						echo "<option value=\"9\"";
						if ($admin == 9)
							echo " selected";
						echo ">Invité</option>\n";
						echo "</select>\n";
					}
                    // Library field
                    if (($monaut == "admin")||($monaut == "sadmin")){
                        echo "<tr><td><b>Bibliothèque *</b></td><td>\n";
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
                                if ($library == $codelibraries)
                                    $optionslibraries.=" selected";
                                $optionslibraries.=">" . htmlspecialchars($namelibraries[$lang]) . "</option>\n";
                            }
                            echo $optionslibraries;
                        }
                        echo "</select></td></tr>\n";
                    }
                    echo "<tr><td class=\"odd\"><b>Nouveau password</b></td><td class=\"odd\"><input name=\"newpassword1\" type=\"password\" size=\"30\" value=\"\"></td></tr>\n";
                    echo "<tr><td><b>Confirmation du nouveau password</b></td><td><input name=\"newpassword2\" type=\"password\" size=\"30\" value=\"\"></td></tr>\n";
                    echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
                    echo "<tr><td></td><td><input type=\"submit\" value=\"Enregistrer les modifications\">\n";
                    if (($monaut == "user")&&($action == "updateprofile")){
                        echo "&nbsp;&nbsp;<input type=\"button\" value=\"Annuler\" onClick=\"self.location='admin.php'\">\n";
                    }
                    if (($monaut == "admin")||($monaut == "sadmin")){
                        echo "&nbsp;&nbsp;<input type=\"button\" value=\"Annuler\" onClick=\"self.location='list.php?table=users'\">\n";
                        echo "&nbsp;&nbsp;<input type=\"button\" value=\"Supprimer\" onClick=\"self.location='update.php?action=delete&table=users&id=" . htmlspecialchars($user_id) . "'\"></td></tr>\n";
                    }
                    echo "</table>\n";
                    echo "</form><br /><br />\n";
                    require ("footer.php");
                }
            }
            else{
                echo "<center><br/><b><font color=\"red\">\n";
                echo "La fiche " . $id . " n'a pas été trouvée dans la base.</b></font>\n";
                echo "<br /><br /><b>Veuillez relancer de nouveau votre recherche ou contactez l'administrateur de la base : " . $configemail . "</b></center><br /><br /><br /><br />\n";
                require ("footer.php");
            }
        }
        else{
            require ("header.php");
            //require ("menurech.php");
            echo "<center><br/><b><font color=\"red\">\n";
            echo "La fiche n'a pas été trouvée dans la base.</b></font>\n";
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
