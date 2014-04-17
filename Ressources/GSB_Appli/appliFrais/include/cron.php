<?
mysql_connect("localhost", "root", "passf101")or die ($message="Connection fail");
mysql_select_db("gsb_frais")or die ($message="base de donnée introuvable");

$req="update FicheFrais set idEtat='CL', dateModif=current_date where mois<date_format(current_date, '%Y%m') and idEtat='CR'";
$exec=mysql_query($req);

if (mysql_affected_rows()==0)
{
$message="les fiches été déjà clos";
}
else
{
$message="les fiches ont été close";
}
system("logger $message");

?>
