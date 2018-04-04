<?php
require_once("../outils/fonctions.php");// on se relie à la librairie fonctions.php

$fichier_zone_droite="intro.html"; //pour transporter les fichiers, il faut les enfermer dans une variable
$connexion=connexion();//on établie la connexion à la base de donnée BDD


// temps 1: on établit (ouverture) la connexion
if(isset($_GET['action'])){ //toujours verifier avec le if(isset)
switch($_GET['action']){

    case "comptes":

    $fichier_zone_droite="form_comptes.html";
    $intitule_bouton="CREER";
    $action_form="comptes";
    if(isset($_POST['submit'])) //c-a-d si on appuie sur submit
         {
            if(empty($_POST['login_compte']))
            {
                $avertissement="<p class=\"pas_ok\">Créer un identifiant!</p>";
            } elseif(empty($_POST['nom_compte']))
            {
                $avertissement="<p class=\"pas_ok\">Veuillez mettre un nom!</p>";
            }
            elseif(empty($_POST['pass_compte1']) || empty($_POST['pass_compte2']))
            {
                $avertissement="<p class=\"pas_ok\">Veuillez mettre un password</p>";
            } 
            elseif(!empty($_POST['pass_compte1']) && !empty($_POST['pass_compte2']) && $_POST['pass_compte1']!=$_POST['pass_compte2'])
            {
                $avertissement="<p class=\"pas_ok\">Veuillez mettre un password identique!</p>";
            }                      
            else{
                //temps 2:on fait la requete d'insertion
                $requete="INSERT INTO comptes   
                        SET nom_compte='".addslashes($_POST['nom_compte'])."',
                            prenom_compte='".addslashes($_POST['prenom_compte'])."',
                            login_compte='".addslashes($_POST['login_compte'])."',
                            pass_compte=PASSWORD('".addslashes($_POST['pass_compte1'])."')";
                //temps 3: on execute la requete
                $resultat=mysqli_query($connexion, $requete);
                
                //on vide les champs du formulaire après validation
                foreach($_POST as $nom_champ=>$valeur)
                {
                    $_POST[$nom_champ]="";
                }


                $avertissement="<p class=\"ok\">Bravo!</p>";
            }
         }
    $requete2="SELECT * FROM comptes ORDER BY nom_compte";
    $affichage=afficher_comptes($requete2,$connexion);
     
    break;    
    //*****************modifier compte************************
case "modifier_comptes":
    $fichier_zone_droite="form_comptes.html";
    $intitule_bouton="MODIFIER";
    if(isset($_POST['submit']))
            //on détecte l'appuie sur le bouton modifier
            {
                if(empty($_POST['login_compte']))
                {
                    $avertissement="<p class=\"pas_ok\">Créer un identifiant!</p>";
                } elseif(empty($_POST['nom_compte']))
                {
                    $avertissement="<p class=\"pas_ok\">Veuillez mettre un nom!</p>";
                }
                elseif(!empty($_POST['pass_compte1']) && !empty($_POST['pass_compte2']) && $_POST['pass_compte1']!=$_POST['pass_compte2'])
                {
                    $avertissement="<p class=\"pas_ok\">Veuillez mettre un password identique!</p>";
                } 
                else
                {
                if(!empty($_POST['pass_compte1']))
                    {
                    $requete="UPDATE comptes
                        SET nom_compte='".$_POST['nom_compte']."', 
                            prenom_compte='".$_POST['prenom_compte']."',
                            login_compte='".addslashes($_POST['login_compte'])."',
                            pass_compte=PASSWORD('".addslashes($_POST['pass_compte1'])."')
                        WHERE id_compte='".$_GET['id_compte']."'";                        
                    }
                else{
                    $requete="UPDATE comptes
                    SET nom_compte='".$_POST['nom_compte']."', 
                        prenom_compte='".$_POST['prenom_compte']."',
                        login_compte='".addslashes($_POST['login_compte'])."' 
                    WHERE id_compte='".$_GET['id_compte']."'";    
                    }
                    //on fait la requête de mise à jour

                 $resultat=mysqli_query($connexion, $requete);
                 //on redirige vers la gae de création
                 header("Location:admin.php?action=comptes");            
                }
            }
    if(isset($_GET['id_compte'])) //si on récup la valeur du id_actu dans l'url (méthode GET)
        {
            $action_form="modifier_comptes&id_compte=".$_GET['id_compte']."";
            $requete="SELECT * FROM comptes WHERE id_compte='".$_GET['id_compte']."'";
            $resultat=mysqli_query($connexion, $requete);// toujours le mettre
            // on ne fait pas de boucle car on s'attend à un seul résultat (une seule linge)
            $ligne=mysqli_fetch_object($resultat);
            //on recharge les champs du formulaire
            $_POST['id_compte']=stripslashes($ligne->id_compte);
            $_POST['nom_compte']=stripslashes($ligne->nom_compte);
            $_POST['prenom_compte']=stripslashes($ligne->prenom_compte);//stripslashes() enlève les apostrophes
            $_POST['login_compte']=stripslashes($ligne->login_compte);         
        }
    //2eme temps :la requete pour selectionner
    $requete2="SELECT * FROM comptes ORDER BY id_compte";
    $affichage=afficher_comptes($requete2,$connexion);
            
    break;
//****************************************Supprimer comptes*********
case "supprimer_compte":
$fichier_zone_droite="form_comptes.html";
$intitule_bouton="CREER";
$action_form="comptes";

if(isset($_GET['id_compte']))
    {
        $avertissement="<div id=\"bt_confirm\">\n";
        $avertissement.="<h3>Voulez-vous supprimer cette actu N°".$_GET['id_compte']."?</h3>\n";
        $avertissement.="<a class=\"bt_confirm\" href=\"admin.php?action=supprimer_compte&id_compte=".$_GET['id_compte']."&delete=oui\">OUI</a>";
        $avertissement.="&nbsp;&nbsp";
        $avertissement.="<a class=\"bt_confirm\" href=\"admin.php?action=comptes\">NON</a>\n";
        $avertissement.="</div>\n";

        if(isset($_GET['delete']) && $_GET['delete']=="oui")
        {
            $requete="DELETE FROM comptes WHERE id_compte='".$_GET['id_compte']."'";
            $resultat=mysqli_query($connexion, $requete);

            $avertissement="<div id=\"bt_confirm\">\n";
            $avertissement.="<h3>Compte supprimer</h3>\n";
            $avertissement.="</div>\n";
        }
    }
//2eme temps :la requete pour selectionner
$requete2="SELECT * FROM comptes ORDER BY id_compte";
$affichage=afficher_comptes($requete2,$connexion);

break;
//*************************************************actus************
    case "actus":

    $fichier_zone_droite="form_actus.html";
    $intitule_bouton="CREER";
    $action_form="actus";
    if(isset($_POST['submit'])) //c-a-d si on appuie sur submit
         {
            if(empty($_POST['zone_actu']))
            {
                $avertissement="<p class=\"pas_ok\">Mets une zone!</p>";
            } elseif(empty($_POST['titre_actu_h1']) && empty($_POST['titre_actu_h2']))
            {
                $avertissement="<p class=\"pas_ok\">Veuillez mettre un titre!</p>";
            }
            else{
                //temps 2:on fait la requete d'insertion
                $requete="INSERT INTO actus   
                        SET zone_actu='".$_POST['zone_actu']."', 
                            date_limite_actu='".$_POST['date_limite_actu']."',
                            titre_actu_h1='".addslashes($_POST['titre_actu_h1'])."',
                            titre_actu_h2='".addslashes($_POST['titre_actu_h2'])."',
                            text_actu='".addslashes($_POST['text_actu'])."'";

                //temps 3: on execute la requete
                $resultat=mysqli_query($connexion, $requete);
                
                //on vide les champs du formulaire après validation
                foreach($_POST as $nom_champ=>$valeur)
                {
                    $_POST[$nom_champ]="";
                }


                $avertissement="<p class=\"ok\">Bravo!</p>";
            }
         }
    //2eme temps :la requete pour selectionner
    $requete2="SELECT * FROM actus ORDER BY zone_actu";
    $affichage=afficher_actus($requete2,$connexion);
    break;
    
    case "modifier_actu":
    $fichier_zone_droite="form_actus.html";
    $intitule_bouton="MODIFIER";
    if(isset($_POST['submit']))
            //on détecte l'appuie sur le bouton modifier
            {
                    if(empty($_POST['zone_actu']))
                {
                    $avertissement="<p class=\"pas_ok\">Mets une zone!</p>";
                } elseif(empty($_POST['titre_actu_h1']) && empty($_POST['titre_actu_h2']))
                {
                    $avertissement="<p class=\"pas_ok\">Veuillez mettre un titre!</p>";
                } else {
                    //on fait la requête de mise à jour
                    $requete="UPDATE actus
                        SET zone_actu='".$_POST['zone_actu']."', 
                            date_limite_actu='".$_POST['date_limite_actu']."',
                            titre_actu_h1='".addslashes($_POST['titre_actu_h1'])."',
                            titre_actu_h2='".addslashes($_POST['titre_actu_h2'])."',
                            text_actu='".addslashes($_POST['text_actu'])."' 
                        WHERE id_actu='".$_GET['id_actu']."'";
                        $resultat=mysqli_query($connexion, $requete);
                }
            }
    if(isset($_GET['id_actu'])) //si on récup la valeur du id_actu dans l'url (méthode GET)
        {
            $action_form="modifier_actu&id_actu=".$_GET['id_actu']."";
            $requete="SELECT * FROM actus WHERE id_actu='".$_GET['id_actu']."'";
            $resultat=mysqli_query($connexion, $requete);// toujours le mettre
            // on ne fait pas de boucle car on s'attend à un seul résultat (une seule linge)
            $ligne=mysqli_fetch_object($resultat);
            //on recharge les champs du formulaire
            $_POST['zone_actu']=$ligne->zone_actu;
            $_POST['date_limite_actu']=$ligne->date_limite_actu;
            $_POST['titre_actu_h1']=stripslashes($ligne->titre_actu_h1);//stripslashes() enlève les apostrophes
            $_POST['titre_actu_h2']=stripslashes($ligne->titre_actu_h2);
            $_POST['text_actu']=stripslashes($ligne->text_actu);
        }
    //2eme temps :la requete pour selectionner
    $requete2="SELECT * FROM actus ORDER BY zone_actu";
    $adffichage=afficher_actus($requete2,$connexion);
            
    break;

    case "supprimer_actu":
    $fichier_zone_droite="form_actus.html";
    $intitule_bouton="CREER";
    $action_form="actus";

    if(isset($_GET['id_actu']))
        {
            $avertissement="<div id=\"bt_confirm\">\n";
            $avertissement.="<h3>Voulez-vous supprimer cette actu N°".$_GET['id_actu']."?</h3>\n";
            $avertissement.="<a class=\"bt_confirm\" href=\"admin.php?action=supprimer_actu&id_actu=".$_GET['id_actu']."&delete=oui\">OUI</a>";
            $avertissement.="&nbsp;&nbsp";
            $avertissement.="<a class=\"bt_confirm\" href=\"admin.php?action=actus\">NON</a>\n";
            $avertissement.="</div>\n";

            if(isset($_GET['delete']) && $_GET['delete']=="oui")
            {
                $requete="DELETE FROM actus WHERE id_actu='".$_GET['id_actu']."'";
                $resultat=mysqli_query($connexion, $requete);

                $avertissement="<div id=\"bt_confirm\">\n";
                $avertissement.="<h3>Actu supprimer</h3>\n";
                $avertissement.="</div>\n";
            }
        }
    //2eme temps :la requete pour selectionner
    $requete2="SELECT * FROM actus ORDER BY zone_actu";
    $affichage=afficher_actus($requete2,$connexion);

    break;

    default:

    $fichier_zone_droite="intro.html";

    break;
    }

}
mysqli_close($connexion);
include("admin.html");
?>