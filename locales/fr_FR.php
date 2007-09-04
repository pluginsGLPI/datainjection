<?php
/*
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2005 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

	This file is part of GLPI.

    GLPI is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    GLPI is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with GLPI; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
*/

// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------

$DATAINJECTIONLANG["headings"][1] = "Injection de fichiers";
$DATAINJECTIONLANG["name"][1] = "Injection de fichiers";
$title = $DATAINJECTIONLANG["name"][1] ;

$DATAINJECTIONLANG["config"][1]="Configuration du plugin ".$title;

$DATAINJECTIONLANG["setup"][0] = "";
$DATAINJECTIONLANG["setup"][1] = "Configuration du plugin ".$title."";
$DATAINJECTIONLANG["setup"][2] = "";
$DATAINJECTIONLANG["setup"][3] = "Installer le plugin $title";
$DATAINJECTIONLANG["setup"][4] = "Mettre à jour le plugin $title vers la version";
$DATAINJECTIONLANG["setup"][5] = "Désinstaller le plugin $title";
$DATAINJECTIONLANG["setup"][6] = "Attention, la mise à jour du plugin est irréversible.";
$DATAINJECTIONLANG["setup"][7] = "Attention, la désinstallation du plugin est irréversible.<br> Vous perdrez toutes les données.";
$DATAINJECTIONLANG["setup"][8] = "Colonne";

$DATAINJECTIONLANG["presentation"][1] = "Bienvenue dans l'assistant d'importation de fichier !";
$DATAINJECTIONLANG["presentation"][2] = "Cette assistant va vous permettre en toute simplicité d'importer des fichiers au format XML, CSV, et XLS en fonction de vos droits dans GLPI.";
$DATAINJECTIONLANG["presentation"][3] = "Pour commencer à utiliser l'assistant, cliquez sur Suivant.";

$DATAINJECTIONLANG["step"][1] = "Etape 1 : ";
$DATAINJECTIONLANG["step"][2] = "Etape 2 : ";
$DATAINJECTIONLANG["step"][3] = "Etape 3 : ";
$DATAINJECTIONLANG["step"][4] = "Etape 4 : ";
$DATAINJECTIONLANG["step"][5] = "Etape 5 : ";
$DATAINJECTIONLANG["step"][6] = "Etape 6 : ";
$DATAINJECTIONLANG["step"][7] = "Etape 7 : ";

$DATAINJECTIONLANG["choiceStep"][1] = "Gestion ou utilisation d'un modèle";
$DATAINJECTIONLANG["choiceStep"][2] = "Dans cette première étape, vous devez choisir entre créer votre propre modèle, modifier, supprimer, et utiliser un modèle existant en le choisissant dans la liste déroulante.";
$DATAINJECTIONLANG["choiceStep"][3] = "Créer un nouveau modèle";
$DATAINJECTIONLANG["choiceStep"][4] = "Modifier un modèle existant";
$DATAINJECTIONLANG["choiceStep"][5] = "Supprimer un modèle existant";
$DATAINJECTIONLANG["choiceStep"][6] = "Utiliser un modèle existant";
$DATAINJECTIONLANG["choiceStep"][7] = "Commentaire du modèle";
$DATAINJECTIONLANG["choiceStep"][8] = "Pas de commentaire";

$DATAINJECTIONLANG["modelStep"][1] = "Collecte d'information sur le fichier";
$DATAINJECTIONLANG["modelStep"][2] = "Modification du modèle";
$DATAINJECTIONLANG["modelStep"][3] = "Dans cette étape, vous devez selectionner le type de votre fichier, le type d'objet que vous allez insérer.";
$DATAINJECTIONLANG["modelStep"][4] = "Type de données à insérer :";
$DATAINJECTIONLANG["modelStep"][5] = "Type de fichier :";
$DATAINJECTIONLANG["modelStep"][6] = "Création des lignes :";
$DATAINJECTIONLANG["modelStep"][7] = "Mise à jour des lignes :";
$DATAINJECTIONLANG["modelStep"][8] = "Ajout dans un dropdown :";
$DATAINJECTIONLANG["modelStep"][9] = "Présence d'un entête :";
$DATAINJECTIONLANG["modelStep"][10] = "Délimiteur du fichier :";
$DATAINJECTIONLANG["modelStep"][11] = "Délimiteur non définie";

$DATAINJECTIONLANG["deleteStep"][1] = "Confirmation de la suppression";
$DATAINJECTIONLANG["deleteStep"][2] = "Attention si vous supprimez le model, les mappings et les informations complémentaires seront supprimés également et ne seront plus récupérables.";
$DATAINJECTIONLANG["deleteStep"][3] = "Voulez-vous supprimer";
$DATAINJECTIONLANG["deleteStep"][4] = "définitivement ?";
$DATAINJECTIONLANG["deleteStep"][5] = "Le modèle";
$DATAINJECTIONLANG["deleteStep"][6] = "a été supprimé.";
$DATAINJECTIONLANG["deleteStep"][7] = "Problème lors de la suppression du modèle.";

$DATAINJECTIONLANG["fileStep"][1] = "Sélection du fichier à uploader";
$DATAINJECTIONLANG["fileStep"][2] = "Sélectionnez le fichier sur votre disque dur afin qu'il soit uploader sur le serveur.";
$DATAINJECTIONLANG["fileStep"][3] = "Choix du fichier :";
$DATAINJECTIONLANG["fileStep"][4] = "Le fichier est introuvable";
$DATAINJECTIONLANG["fileStep"][5] = "Le fichier n'a pas le bon format";
$DATAINJECTIONLANG["fileStep"][6] = "Extension";
$DATAINJECTIONLANG["fileStep"][7] = "requise";
$DATAINJECTIONLANG["fileStep"][8] = "Impossible de copier le fichier dans";

$DATAINJECTIONLANG["mappingStep"][1] = "colonnes à mapper ont été trouvées";
$DATAINJECTIONLANG["mappingStep"][2] = "Entête du fichier";
$DATAINJECTIONLANG["mappingStep"][3] = "Tables";
$DATAINJECTIONLANG["mappingStep"][4] = "Champs";
$DATAINJECTIONLANG["mappingStep"][5] = "Obligatoire";
$DATAINJECTIONLANG["mappingStep"][6] = "-------Choisir une table-------";
$DATAINJECTIONLANG["mappingStep"][7] = "-------Choisir un champ-------";
$DATAINJECTIONLANG["mappingStep"][8] = "Au moins un mapping doit être obligatoire";
$DATAINJECTIONLANG["mappingStep"][9] = "Dans cette étapes vous allez réaliser vos mappings avec le fichier.";
$DATAINJECTIONLANG["mappingStep"][10] = "La colonne entête correspond aux entetes de votre fichier";
$DATAINJECTIONLANG["mappingStep"][11] = "La notion obligatoire correspond à un champ qui devra absolument être connue dans le fichier, vous devez avoir au moins un mapping obligatoire avant de continuer l'assistant";

$DATAINJECTIONLANG["infoStep"][1] = "Informations complémentaires";
$DATAINJECTIONLANG["infoStep"][2] = "Modification des informations complémentaires";
$DATAINJECTIONLANG["infoStep"][3] = "Dans cette étape vous pouvez ajouter des informations qui n'étaient pas présente dans le fichier. Vous devrez ensuite lors de l'utilisation du modèle rentrer les informations à la main.";

$DATAINJECTIONLANG["saveStep"][1] = "Enregistrement du modèle";
$DATAINJECTIONLANG["saveStep"][2] = "Voulez-vous enregistrer le modèle ?";
$DATAINJECTIONLANG["saveStep"][3] = "Voulez-vous mettre le modèle à jour ?";
$DATAINJECTIONLANG["saveStep"][4] = "Entrez le nom du modèle :";
$DATAINJECTIONLANG["saveStep"][5] = "Ajoutez un commentaire :";
$DATAINJECTIONLANG["saveStep"][6] = "Votre modèle n'a pas été enregistré mais est quand même prêt à l'emploi.";
$DATAINJECTIONLANG["saveStep"][7] = "Votre modèle n'a pas été mis à jour mais est quand même prêt à l'emploi.";
$DATAINJECTIONLANG["saveStep"][8] = "Votre modèle a été enregistré et est prêt à l'emploi.";
$DATAINJECTIONLANG["saveStep"][9] = "Votre modèle a été mis à jour et est prêt à l'emploi.";
$DATAINJECTIONLANG["saveStep"][10] = "Voulez-vous utiliser le modèle maintenant ?";

$DATAINJECTIONLANG["fillInfoStep"][1] = "Attention ! Vous êtes sur le point d'importer des données dans GLPI. Etes-vous sûre de vouloir importer ?";
$DATAINJECTIONLANG["fillInfoStep"][2] = "Remplissez les champs pour que les informations soient inséré dans GLPI lors de l'importation.";

$DATAINJECTIONLANG["importStep"][1] = "Importation du fichier";
$DATAINJECTIONLANG["importStep"][2] = "L'importation du fichier peut prendre plusieurs minutes en fonction de votre configurations. Veuillez patientez et suivre la barre de progression pour voir où en est l'importation.";

$DATAINJECTIONLANG["button"][1] = "< Précédent";
$DATAINJECTIONLANG["button"][2] = "Suivant >";
$DATAINJECTIONLANG["button"][3] = "Voir le fichier";

?>
