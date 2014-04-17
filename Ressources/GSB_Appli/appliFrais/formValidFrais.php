<?php

$repInclude= './include/';
require($repInclude . "_init.inc.php");

// page inaccessible si visiteur non connecté
if ( ! estVisiteurConnecte() ) {
	header("Location: cSeConnecter.php");
}
require($repInclude . "_entete.inc.php");
require($repInclude . "_sommaire.inc.php");

// execute lors de la selection du visiteur
if(isset($_POST['bouton'])&& $_POST['bouton']="Envoyer")
{
	$numUser=$_POST['lstVisiteur'];
}

//execute lors de la selection du mois
if(isset($_POST['boutton']) && $_POST['boutton']="Envoyer")
{
	if($_POST['lstMois'])
	{
		$numUser=$_POST['numeroUser'];
		$moisSelect=$_POST['lstMois'];
		$_SESSION['mois']=$moisSelect;
		$_SESSION['numUser']=$numUser;
	}
	$req="select * from LigneFraisForfait where idVisiteur='$numUser' and mois='$moisSelect'";
	$resultat=mysql_query($req, $idConnexion);
	while ($MaLigne=mysql_fetch_array($resultat))
	{
		$nom=$MaLigne["idFraisForfait"];
		$qt=$MaLigne["quantite"];
		if ($nom=="ETP")
		{
			$forfait=$qt;
		}
		if ($nom=="KM")
		{
			$km=$qt;
		}
		if ($nom=="NUI")
		{
			$nuit=$qt;
		}
		if ($nom=="REP")
		{
			$repas=$qt;
		}

	} 
	$sql="select * from FicheFrais where idVisiteur='$numUser' and mois=$moisSelect";
	$resultat= mysql_query($sql, $idConnexion);
	$maLigne=mysql_fetch_array($resultat);
	$just=$maLigne['nbJustificatifs'];


}

//modifie frais forfait
if(isset($_POST['action']) && $_POST['action']="Modifier")
{
	$numUser=$_POST['inputNum'];
	$moisSelect=$_POST['inputMois'];
	$forfait=$_POST['etape'];
	$km=$_POST['km'];
	$nuit=$_POST['nuitee'];
	$repas=$_POST['repas'];
	$req1="update LigneFraisForfait set quantite=$nuit where idVisiteur='$numUser' and mois='$moisSelect' and idFraisForfait='NUI'";
	$req2="update LigneFraisForfait set quantite=$repas where idVisiteur='$numUser' and mois='$moisSelect' and idFraisForfait='REP'";
	$req3="update LigneFraisForfait set quantite=$km where idVisiteur='$numUser' and mois='$moisSelect' and idFraisForfait='KM'";
	$req4="update LigneFraisForfait set quantite=$forfait where idVisiteur='$numUser' and mois='$moisSelect' and idFraisForfait='ETP'";
	mysql_query($req1);
	mysql_query($req2);
	mysql_query($req3);
	mysql_query($req4);
}


//supprime Frais hors forfait
if(isset ($_POST['valider']) && $_POST['valider']="Valider")
{
	$mois=$_SESSION['mois'];
	$numUser=$_SESSION['numUser'];
	$reqRadio="select * from LigneFraisHorsForfait where mois='$mois' and idVisiteur='$numUser'";
	$resRadio=mysql_num_rows($reqRadio);
	$id=$_POST['id'];
	// Création de la requête
	$maRequete="delete from LigneFraisHorsForfait where id='$id'";
	$resultat=mysql_query($maRequete);

}


//modifie nb justificatif
if(isset($_POST['inputJust']) && $_POST['inputJust']="Enregistrer")
{
	$numUser=$_POST['Num'];
	$moisSelect=$_POST['Mois'];
	$justifi=$_POST['hcMontant'];
	$sql2="update FicheFrais set nbJustificatifs=$justifi where idVisiteur='$numUser' and mois='$moisSelect'";
	$resultat=mysql_query($sql2, $idConnexion);

}

// passe la fiche de frais en mode Validée
if(isset($_POST['Action']) && $_POST['Action']="Valider")
{
$numUser=$_POST['Num'];
$moisSelect=$_POST['Mois'];
$Sql="update  FicheFrais set idEtat='VA' where idVisiteur='$numUser' and mois=$moisSelect";
mysql_query($Sql);
unset($numUser);
unset($moisSelect);
}
?>
<html>
<head>

<title>Validation des frais de visite</title>

</head>
<body>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />


<div id="contenu">
<form name="formValidFrais" method="post" action="formValidFrais.php">
<h1> Validation des frais par visiteur </h1>
<p><label class="titre">Choisir le visiteur: </label>
<select name="lstVisiteur" class="zone">
<?
$req="select distinct * from Visiteur inner join FicheFrais on idVisiteur=id  where typeVisiteur=1 and idEtat='CL' ";
$resultat = mysql_query($req);
while ($lgVisiteur =mysql_fetch_array($resultat)) {
	$num = $lgVisiteur["id"];
	//      echo $num;
	$nom = $lgVisiteur["nom"];
	$prenom = $lgVisiteur["prenom"];
	//      echo $nom;
	?>
		<option value="<?=$num?>" <?=(($num==$numUser) ?'selected="selected"':'');?>  ><?=$nom.", ".$prenom?></option>
		<?
}
?>
</select>
<input type="submit" name="bouton" value="Envoyer"></p>
<p>

	<?
if (isset($numUser))
{
	?>


		<label for="lstMois">Mois : </label>
		<select id="lstMois" name="lstMois" title="Sélectionnez le mois souhaité pour la fiche de frais">
		<?php
		// on propose tous les mois pour lesquels le visiteur a une fiche de frais
		$req =obtenirReqMoisFicheFrais($numUser);
	echo $req;
	$idJeuMois = mysql_query($req, $idConnexion);
	$lgMois = mysql_fetch_assoc($idJeuMois);
	while ( is_array($lgMois) ) {
		$mois = $lgMois["mois"];
		$noMois = intval(substr($mois, 4, 2));
		$annee = intval(substr($mois, 0, 4));
		?>
			<option value="<?php echo $mois; ?>"<?php if ($moisSelect == $mois) { ?> selected="selected"<?php } ?>><?php echo obtenirLibelleMois($noMois) . " " . $annee; ?></option>
			<?php
			$lgMois = mysql_fetch_assoc($idJeuMois);
	}
	mysql_free_result($idJeuMois);
	?>
		</select>
		<input type="hidden" name="numeroUser" value="<?=$numUser?>"/>
		<input type="submit" name="boutton" value="Envoyer"></p>

		<?
		if (isset($moisSelect))
		{
			?>





				<div style="clear:left;"><h2>Frais au forfait </h2></div>
				<? 
				$numUser=$_POST['numeroUser'];
			$moisSelect=$_POST['lstMois'];
			$req="select * from LigneFraisForfait where idVisiteur='$numUser' and mois='$moisSelect'";
			while ($MaLigne=mysql_fetch_array($resultat))
			{
				$nom=$MaLigne["idFraisForfait"];
				$qt=$MaLigne["quantite"];
				if ($nom=="ETP")
				{
					$forfait=$qt;
				}
				if ($nom=="KM")
				{
					$km=$qt;
				}
				if ($nom=="NUI")
				{
					$nuit=$qt;
				}
				if ($nom=="REP")
				{
					$repas=$qt;
				}

			}
			$sql="select * from FicheFrais where idVisiteur='$numUser' and mois=$moisSelect";
			$resultat= mysql_query($sql, $idConnexion);
			$maLigne=mysql_fetch_array($resultat);
			$just=$maLigne['nbJustificatifs'];
			?>
				<table style="color:black;" border="1">
				<tr><th>Repas midi</th><th>Nuitee </th><th>Etape</th><th>Km </th><th>Modification</th></tr>
				<tr align="center"><td width="80" ><input type="text" size="3" name="repas" value="<?=$repas?>"/></td>
				<td width="80"><input type="text" size="3" name="nuitee" value="<?=$nuit?>"/></td>
				<td width="80"> <input type="text" size="3" name="etape" value="<?=$forfait?>"/></td>
				<td width="80"> <input type="text" size="3" name="km" value="<?=$km?>"/></td>
				<td width="80"> <input type="submit" name="action" value="Modifier"</td>
				<input type="hidden" name="inputNum" value="<?=$numUser?>"/>
				<input type="hidden" name="inputMois" value="<?=$moisSelect?>"/>
				</tr>
				</table>


					<p class="titre" /><div style="clear:left;"><h2>Hors Forfait</h2></div>
					<table style="color:black;" border="1">   
					<tr><th>Date</th><th>Libellé </th><th>Montant</th><th>Modifier</th><th>Supprimer</th></tr>
			



				<?
				$nb=0;
				$req="select * from LigneFraisHorsForfait where idVisiteur='$numUser' and mois='$moisSelect'";
			$resultat=mysql_query($req, $idConnexion);
			while ($MaLigne=mysql_fetch_array($resultat))
			{
				$date=$MaLigne["date"];
				$libelle=$MaLigne["libelle"];
				$montant=$MaLigne["montant"];
				$id=$MaLigne["id"];
			

				?>

					<tr align="center"><td width="100" ><input type="text" size="7" name="hfDate1" value="<?=$date?>"/></td>
					<td width="220"><input type="text" size="20" name="hfLib1" value="<?=$libelle?>"/></td> 
					<td width="90"> <input type="text" size="5" name="hfMont1" value="<?=$montant?>"/></td>
					<input type="hidden" name="id" value="<?=$id?>"/>
					<td width="80"><INPUT type= "Submit" name="Modif<?$nb?>" value="Modifier"></td>
					<td width="80"><INPUT type= "Submit" name="Supp<?$nb?>" value="Suprimmer"></td>
					</tr>
					
<?
					$nb++;
			}
?>
					</table>
					<p class="titre"><div style="clear:left;"><h2>Hors Classification</h2></div></p>
					<div class="titre">Nb Justificatifs</div><input type="text" class="zone" size="4" name="hcMontant" value="<?=$just?>"/>
					<input type="hidden" name="Num" value="<?=$numUser?>"/>
					<input type="hidden" name="Mois" value="<?=$moisSelect?>"/>

					<input type="submit" name="inputJust" value="Enregistrer">		
					<p class="titre" /><label class="titre">&nbsp;</label><input class="zone"type="reset" /><input class="zone" name="Action" value="Valider" type="submit" />
					</form>
					</div>

					<?php
		}
		else
		{
		}
}
else
{
}
require($repInclude . "_pied.inc.html");
require($repInclude . "_fin.inc.php");
?>

</div>
</body>
</html>
