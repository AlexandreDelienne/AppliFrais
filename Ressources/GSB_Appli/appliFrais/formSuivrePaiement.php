<?php
$repInclude = './include/';
require($repInclude . "_init.inc.php");

// page inaccessible si visiteur non connecté
if (!estVisiteurConnecte()) {
	header("Location: cSeConnecter.php");
}
require($repInclude . "_entete.inc.php");
require($repInclude . "_sommaire.inc.php");


//lorsque l'ont appyue sur mise en paiement cela passe les lignes selectionner en VA
if (isset ($_POST['action']) && $_POST['action']="Mise en paiement")
{
	$req="select id, mois from Visiteur inner join FicheFrais on id=idVisiteur where idEtat='VA'";
	$result=mysql_query($req);
	while($maLigne=mysql_fetch_array($result))
	{
	$id=$maLigne['id'];
	$mois=$maLigne['mois'];
	if(isset($_POST['valid']) && $_POST['valid']=="$id")
		{
		$sql="update FicheFrais set idEtat='RB' where idVisiteur='$id' and mois=$mois";
		mysql_query($sql);
		} 
	}  	
}


//requete permetant de selectionner tous les Fiches de frais validée et en mise en paiement
$req="select id,nom,prenom,mois from Visiteur inner join FicheFrais on id=idVisiteur where idEtat='VA'"; 
$result=mysql_query("$req");
?>
<h1>Mise en paiement</h1>


<form method="post" action="formSuivrePaiement.php" name="MiseEnPaiment">
	<? 
//boucle permetant d'afficher toutes les check box des Fiche de frais validée
while ($maLigne= mysql_fetch_array($result))
{
	//initialisation des varaibles a zero
	$somme=0;$Hf=0;
	$sommeTotale=0;
	$s1=0;$s2=0;$s3=0;$s4=0;
	$etp=0;$km=0;$nui=0;$rep=0;
	$pEtp=0;$pKm=0;$pNui=0;$pRep=0;
	
	//recuperation des donnée nécessaire
	$id=$maLigne['id'];
	$nom=$maLigne['nom'];
	$prenom=$maLigne['prenom'];
	$mois=$maLigne['mois'];

	//recuperation des données de LigneFraisForfait pour calculer le montant Total
	$sqlEtp="select quantite from LigneFraisForfait where idVisiteur='$id' and mois='$mois' and idFraisForfait='ETP'";
	$sqlKm="select quantite from LigneFraisForfait where idVisiteur='$id' and mois='$mois' and idFraisForfait='KM'";
	$sqlNui="select quantite from LigneFraisForfait where idVisiteur='$id' and mois='$mois' and idFraisForfait='NUI'";
	$sqlRep="select quantite from LigneFraisForfait where idVisiteur='$id' and mois='$mois' and idFraisForfait='REP'";

	$etp=mysql_fetch_array(mysql_query($sqlEtp));
	$km=mysql_fetch_array(mysql_query($sqlKm));
	$nui=mysql_fetch_array(mysql_query($sqlNui));
	$rep=mysql_fetch_array(mysql_query($sqlRep));

	//recuperation des prix de chaque FraisForfait pour calculer le montant total
	$sqlEtpP="select montant from FraisForfait where id='ETP'";
	$sqlKmP="select montant from FraisForfait where id='KM'";
	$sqlNuiP="select montant from FraisForfait where id='NUI'";
	$sqlRepP="select montant from FraisForfait where id='REP'";

	$pEtp=mysql_fetch_array(mysql_query($sqlEtpP));
	$pKm=mysql_fetch_array(mysql_query($sqlKmP));
	$pNui=mysql_fetch_array(mysql_query($sqlNuiP));
	$pRep=mysql_fetch_array(mysql_query($sqlRepP));

	//calcul du montant total
	$s1=$etp[0]*$pEtp[0];
	$s2=$km[0]*$pKm[0];
	$s3=$nui[0]*$pNui[0];
	$s4=$rep[0]*$pRep[0];
	
	$somme=$s1+$s2+$s3+$s4;

	//frais hors forfait
	$sqlRow="select count(*) as row from LigneFraisHorsForfait where mois=$mois and idVisiteur='$id';";
	$row=mysql_query($sqlRow);
	if ($row<=1) //si le nombre de ligne fraisHorsForfait est inferieur ou egal a 1 on prend juste la ligne concerner sinon
	{
		$sqlHf="select montant from LigneFraisHorsForfait where mois=$mois and idVisiteur='$id';";
		$Hf=mysql_query($sqlHf);
	}
	else //sinon on prend le montant total de toute les fiches de frais horsforfait
	{
		$sqlHf="select sum(montant) as montant from LigneFraisHorsForfait where mois=$mois and idVisiteur='$id';";
		$sHf=mysql_query($sqlHf);
		$Hf=mysql_fetch_array($sHf);
	}
	$sommeTotale=$somme+$Hf[0];
	?>
		<br><input type="checkbox"  name="valid" value="<?=$id?>" >  <? echo($nom.", ".$prenom.", ".$mois.", montant Total: ".$sommeTotale); ?></input></br> 
		<?
}
?>
<input type="submit" name="action" value="Mise en paiement">
</form>


<?php
require($repInclude . "_pied.inc.html");
require($repInclude . "_fin.inc.php");
?>

