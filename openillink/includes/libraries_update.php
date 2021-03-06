<?php
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
// Libraries table : creation and update of records
// 

require_once ("config.php");
require_once ("authcookie.php");
require_once ("connexion.php");
require_once ("toolkit.php");

if (!empty($_COOKIE['illinkid'])){
  $action2="";
  $action="";
  $id = ((!empty($_POST['id'])) && isValidInput($_POST['id'],11,'i',false))?$_POST['id']:NULL;
  $ip = $_SERVER['REMOTE_ADDR'];
  $validActionSet = array('new', 'update', 'delete', 'deleteok');
  $action=((!empty($_POST['action'])) && isValidInput($_POST['action'],10,'s',false,$validActionSet))?$_POST['action']:'';
  $action2=((!empty($_GET['action'])) && isValidInput($_GET['action'],10,'s',false,$validActionSet))?$_GET['action']:NULL;
  if (!empty($action2))
    $action = $action2;
    if (($monaut == "admin")||($monaut == "sadmin")){
      $mes="";
      $date=date("Y-m-d H:i:s");
      $code = ((!empty($_POST['code'])) && isValidInput($_POST['code'],50,'s',false))? trim($_POST['code']):'';
      $name1 = ((!empty($_POST['name1'])) && isValidInput($_POST['name1'],50,'s',false))? trim($_POST['name1']):'';
      $name2 = ((!empty($_POST['name2'])) && isValidInput($_POST['name2'],50,'s',false))? trim($_POST['name2']):'';
      $name3 = ((!empty($_POST['name3'])) && isValidInput($_POST['name3'],50,'s',false))? trim($_POST['name3']):'';
      $name4 = ((!empty($_POST['name4'])) && isValidInput($_POST['name4'],50,'s',false))? trim($_POST['name4']):'';
      $name5 = ((!empty($_POST['name5'])) && isValidInput($_POST['name5'],50,'s',false))? trim($_POST['name5']):'';
      $default = ((!empty($_POST['default'])) && isValidInput($_POST['default'],1,'s',false))? trim($_POST['default']):'';
      if ($default != "1")
        $default = 0;
      else
        $default = 1;
      $hasSharedOrders = ((!empty($_POST['hasSharedOrders'])) && isValidInput($_POST['hasSharedOrders'],1,'s',false))? trim($_POST['hasSharedOrders']):'';
      if ($hasSharedOrders != "1")
        $hasSharedOrders = 0;
      else
        $hasSharedOrders = 1;
	 $signature = ((!empty($_POST['signature'])) && isValidInput($_POST['signature'],65535,'s',false))? $_POST['signature']:'';
	 str_replace("\\r\\n", "\\n", $signature);
	 str_replace("\\n", "\\r\\n", $signature);
      if (($action == "update")||($action == "new")){
        // Tester si le code est unique
        $reqcode = "SELECT * FROM libraries WHERE code = ?";
        $resultcode = dbquery($reqcode, array($code), 's');
        $nbcode = iimysqli_num_rows($resultcode);
        $enregcode = iimysqli_result_fetch_array($resultcode);
        $idcode = $enregcode['id'];
        if (($nbcode == 1)&&($action == "new"))
          $mes = $mes . "<br/>le code '" . htmlspecialchars($code) . "' existe déjà dans la base, veuillez choisir un autre";
        if (($nbcode == 1)&&($action != "new")&&($idcode != $id))
          $mes = $mes . "<br/>le code '" . htmlspecialchars($code) . "' est déjà attribué à une autre bibliothèque, veuillez choisir un autre";
        if ($name1 == "")
          $mes = $mes . "<br/>le nom1 est obligatoire";
        if ($code == "")
          $mes = $mes . "<br/>le code est obligatoire";

        if ($mes != ""){
          require ("headeradmin.php");
          echo "<center><br/><b><font color=\"red\">\n";
          echo $mes."</b></font>\n";
          echo "<br /><br /><a href=\"javascript:history.back();\"><b>retour au formulaire</a></b></center><br /><br /><br /><br />\n";
          require ("footer.php");
        }
        else {
          // Début de l'édition
          if ($action == "update") {
            if ($id != "") {
              require ("headeradmin.php");
              $reqid = "SELECT * FROM libraries WHERE id = ?";
              $myhtmltitle = $configname[$lang] . " : édition de la fiche bibliothèque ".htmlspecialchars($id);
              $resultid = dbquery($reqid, array($id), 'i');

              $nb = iimysqli_num_rows($resultid);
              if ($nb == 1) {
                $enregid = iimysqli_result_fetch_array($resultid);
                $query = "UPDATE libraries SET libraries.name1=?, libraries.name2=?, libraries.name3=?, libraries.name4=?, libraries.name5=?, libraries.default=?, libraries.code=?, libraries.has_shared_ordres=?, libraries.signature=? WHERE libraries.id=?";
                $params = array($name1, $name2, $name3, $name4, $name5, $default, $code, $hasSharedOrders, $signature, $id);
                $resultupdate = dbquery($query, $params, 'sssssisisi') or die("Error : ".mysqli_error());
                echo "<center><br/><b><font color=\"green\">\n";
                echo "La modification de la fiche ".htmlspecialchars($id)." a été enregistrée avec succès</b></font>\n";
                echo "<br/><br/><br/><a href=\"list.php?table=libraries\">Retour à la liste de bibliothèques</a></center>\n";
                require ("footer.php");
              }
              else {
                echo "<center><br/><b><font color=\"red\">\n";
                echo "La modification n'a pas été enregistrée car l'identifiant de la fiche ".htmlspecialchars($id)." n'a pas été trouvée dans la base.</b></font>\n";
                echo "<br /><br /><b>Veuillez relancer de nouveau votre recherche ou contactez l'administrateur de la base : " . $configemail . "</b></center><br /><br /><br /><br />\n";
                require ("footer.php");
              }
            }
          else {
            require ("headeradmin.php");
            //require ("menurech.php");
            echo "<center><br/><b><font color=\"red\">\n";
            echo "La modification n'a pas été enregistrée car il manque l'identifiant de la fiche</b></font>\n";
            echo "<br /><br /><b>Veuillez relancer de nouveau votre recherche</b></center><br /><br /><br /><br />\n";
            require ("footer.php");
          }
        }
        // Fin de l'édition
        // Début de la création
        if ($action == "new") {
          require ("headeradmin.php");
          $myhtmltitle = $configname[$lang] . " : nouvelle bibliothèque";
          $query ="INSERT INTO `libraries` (`id`, `name1`, `name2`, `name3`, `name4`, `name5`, `code`, `default`,`has_shared_ordres`, `signature`) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?)";
          $params = array($name1, $name2, $name3, $name4, $name5, $code, $default, $hasSharedOrders, $signature);
          $id = dbquery($query, $params, 'ssssssisi') or die("Error : ".mysqli_error());
          echo "<center><br/><b><font color=\"green\">\n";
          echo "La nouvelle fiche ".htmlspecialchars($id)." a été enregistrée avec succès</b></font>\n";
          echo "<br/><br/><br/><a href=\"list.php?table=libraries\">Retour à la liste de bibliothèques</a></center>\n";
          echo "</center>\n";
          echo "\n";
          require ("footer.php");
		}
      }
    }
    // Fin de la création
    // Début de la suppresion
    if ($action == "delete") {
      $id=((!empty($_GET['id'])) && isValidInput($_GET['id'],11,'i',false))?$_GET['id']:"";
      $myhtmltitle = $configname[$lang] . " : confirmation pour la suppresion d'une bibliothèque";
      require ("headeradmin.php");
      echo "<center><br/><br/><br/><b><font color=\"red\">\n";
      echo "Voulez-vous vraiement supprimer la fiche " . htmlspecialchars($id) . "?</b></font>\n";
      echo "<form action=\"update.php\" method=\"POST\" enctype=\"x-www-form-encoded\" name=\"fiche\" id=\"fiche\">\n";
      echo "<input name=\"table\" type=\"hidden\" value=\"libraries\">\n";
      echo "<input name=\"id\" type=\"hidden\" value=\"".htmlspecialchars($id)."\">\n";
      echo "<input name=\"action\" type=\"hidden\" value=\"deleteok\">\n";
      echo "<br /><br />\n";
      echo "<input type=\"submit\" value=\"Confirmer la suppression de la fiche " . htmlspecialchars($id) . " en cliquant ici\">\n";
      echo "</form>\n";
      echo "<br/><br/><br/><a href=\"list.php?table=libraries\">Retour à la liste des bibliothèques</a></center>\n";
      echo "</center>\n";
      echo "\n";
      require ("footer.php");
    }
    if ($action == "deleteok") {
      $myhtmltitle = $configname[$lang] . " : supprimer une bibliothèque";
      require ("headeradmin.php");
      $query = "DELETE FROM libraries WHERE libraries.id = ?";
      $result = dbquery($query, array($id), 'i') or die("Error : ".mysqli_error());
      echo "<center><br/><b><font color=\"green\">\n";
      echo "La fiche " . htmlspecialchars($id) . " a été supprimée avec succès</b></font>\n";
      echo "<br/><br/><br/><a href=\"list.php?table=libraries\">Retour à la liste des bibliothèques</a></center>\n";
      echo "</center>\n";
      echo "\n";
      require ("footer.php");
    }
    // Fin de la saisie
  }
  else {
    require ("header.php");
    echo "<center><br/><b><font color=\"red\">\n";
    echo "Vos droits sont insuffisants pour consulter cette page</b></font></center><br /><br /><br /><br />\n";
     require ("footer.php");
  }
}
else {
  require ("header.php");
  require ("codefail.php");
  require ("footer.php");
}
?>
