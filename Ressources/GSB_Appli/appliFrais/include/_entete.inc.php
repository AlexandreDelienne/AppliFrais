<?php

if (estVisiteurConnecte() ) {
          $idUser = obtenirIdUserConnecte() ;
          $lgUser = obtenirDetailVisiteur($idConnexion, $idUser);
          $nom = $lgUser['nom'];
          $prenom = $lgUser['prenom'];
          $type = $lgUser['typeVisiteur'];

if ($type==1)
{
?>
 <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
   <head>
     <title>Intranet du Laboratoire Galaxy-Swiss Bourdin</title>
     <meta http-equiv="content-type" content="text/html; charset=utf-8" />
     <link href="./styles/stylesVisiteur.css" rel="stylesheet" type="text/css" />
     <link rel="shortcut icon" type="image/x-icon" href="./images/favicon.ico" />
   </head>
   <body>
     <div id="page">
       <div id="entete">
         <img src="./images/logo.jpg" id="logoGSB" alt="Laboratoire Galaxy-Swiss Bourdin" title="Laboratoire Galaxy-Swiss Bourdin" />
         <h1>Suivi du remboursement des frais</h1>
       </div>
</html>
<?
}

else
{
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
   <head>
     <title>Intranet du Laboratoire Galaxy-Swiss Bourdin</title>
     <meta http-equiv="content-type" content="text/html; charset=utf-8" />
     <link href="./styles/stylesComptable.css" rel="stylesheet" type="text/css" />
     <link rel="shortcut icon" type="image/x-icon" href="./images/favicon.ico" />
   </head>
   <body>
     <div id="page">
       <div id="entete">
         <img src="./images/logo.jpg" id="logoGSB" alt="Laboratoire Galaxy-Swiss Bourdin" title="Laboratoire Galaxy-Swiss Bourdin" />
         <h1>Suivi du remboursement des frais</h1>
       </div>
</html>

<?
}
}
else
{
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
   <head>
     <title>Intranet du Laboratoire Galaxy-Swiss Bourdin</title>
     <meta http-equiv="content-type" content="text/html; charset=utf-8" />
     <link href="./styles/stylesVisiteur.css" rel="stylesheet" type="text/css" />
     <link rel="shortcut icon" type="image/x-icon" href="./images/favicon.ico" />
   </head>
   <body>
     <div id="page">
       <div id="entete">
         <img src="./images/logo.jpg" id="logoGSB" alt="Laboratoire Galaxy-Swiss Bourdin" title="Laboratoire Galaxy-Swiss Bourdin" />
         <h1>Suivi du remboursement des frais</h1>
       </div>
</html>
<?
}
?>
