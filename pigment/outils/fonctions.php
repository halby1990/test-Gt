<?php
//===============================
// la fonction connecter() permet de choisir une
// base de données et de s'y connecter.

function connexion()
	{
	require_once("connect.php");

	//$connexion = mysqli_connect(SERVEUR,LOGIN,PASSE,BASE,PORT) or die("Error " . mysqli_error($connexion)); pour connexion distante

	$connexion = mysqli_connect(SERVEUR,LOGIN,PASSE,BASE) or die("Error " . mysqli_error($connexion)); //connexion locale
	return $connexion;
	}
//==================faire une fonction======================
function afficher_actus($requete, $connexion)//on envoi des parametre à la fonction
	{
		           //ON FABRIQUE UN TABLEAU QUI RECAP LES ACTUS
    $liste_actus="<table class=\"tab_actus\">\n";
    $liste_actus.="<tr>\n";
                            $liste_actus.="<th>zone</td>\n";
                            $liste_actus.="<th>titre</td>\n";
                            $liste_actus.="<th>Date - fin</td>\n";
                            $liste_actus.="<th>Actions</th>\n";
    $liste_actus.="</tr>\n";

    //3eme temps : on execute la requete
    $resultat=mysqli_query($connexion, $requete);

    //4me temps: on exploite le résultat
    $i=1;
    while($ligne=mysqli_fetch_object($resultat)) //temps qu'il voit des lignes ($ligne), il récupère sous forme d'objets
        {
        $liste_actus.="<tr id=\"tr".$i."\">\n";
        $liste_actus.="<td>".$ligne->zone_actu."</td>\n";
        $liste_actus.="<td>\n";
            $liste_actus.="<a href=\"#popup".$i."\">".$ligne->titre_actu_h1."<br />".$ligne->titre_actu_h2."</a>\n";
            $liste_actus.="<div id=\"popup".$i."\" class=\"popup\">".$ligne->text_actu."<span>\n<a href=\"#tr".$i."\">x</a>\n</span>\n</div>\n";
        $liste_actus.="</td>\n";
        $liste_actus.="<td>".$ligne->date_limite_actu."</td>\n";
        $liste_actus.="<td>\n";
        $liste_actus.="<a class=\"mod\" href=\"admin.php?action=modifier_actu&id_actu=".$ligne->id_actu."\">modifier <span  title=\"modifier\" class=\"dashicons dashicons-admin-tools\"></span></a>\n";
        $liste_actus.="<a class=\"supp\" href=\"admin.php?action=supprimer_actu&id_actu=".$ligne->id_actu."\">supprimer <span title=\"supprimer\" class=\"dashicons dashicons-no\"></span></a>\n";
        $liste_actus.="</td>\n";
        $liste_actus.="</tr>\n";
        $i++;//$i++ = $i+1
        }
    $liste_actus.="</table>\n";
	return $liste_actus; //toujours un return de ce qui à été calculé.
    }

    //*******************comptes*************************


    function afficher_comptes($requete, $connexion)//on envoi des parametre à la fonction
	{
		           //ON FABRIQUE UN TABLEAU QUI RECAP LES ACTUS
    $liste_actus="<table class=\"tab_actus\">\n";
    $liste_actus.="<tr>\n";
                            $liste_actus.="<th>Identifiant</td>\n";
                            $liste_actus.="<th>Nom et prénom</td>\n";
                            $liste_actus.="<th>Login</td>\n";
                            $liste_actus.="<th>Modifier/supprimer</th>\n";
    $liste_actus.="</tr>\n";

    //3eme temps : on execute la requete
    $resultat=mysqli_query($connexion, $requete);

    //4me temps: on exploite le résultat
    $i=1;
    while($ligne=mysqli_fetch_object($resultat)) //temps qu'il voit des lignes ($ligne), il récupère sous forme d'objets
        {
        $liste_actus.="<tr id=\"tr".$i."\">\n";
        $liste_actus.="<td>".$ligne->id_compte."</td>\n";
        $liste_actus.="<td>".$ligne->nom_compte. " " . $ligne->prenom_compte. "</td>\n";
        $liste_actus.="<td>".$ligne->login_compte."</td>\n";
        $liste_actus.="<td>\n";       
        $liste_actus.="<a class=\"mod\" href=\"admin.php?action=modifier_comptes&id_compte=".$ligne->id_compte."\">modifier <span  title=\"modifier\" class=\"dashicons dashicons-admin-tools\"></span></a>\n";
        $liste_actus.="<a class=\"supp\" href=\"admin.php?action=supprimer_compte&id_compte=".$ligne->id_compte."\">supprimer <span title=\"supprimer\" class=\"dashicons dashicons-no\"></span></a>\n";       
        $liste_actus.="</td>\n";
        $liste_actus.="</tr>\n";
        $i++;//$i++ = $i+1
        }
    $liste_actus.="</table>\n";
	return $liste_actus; //toujours un return de ce qui à été calculé.
	}
?>





