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
$DATAINJECTIONLANG["setup"][9] = "Gestion des droits";

$DATAINJECTIONLANG["presentation"][1] = "Bienvenue dans l'assistant d'importation de fichiers";
$DATAINJECTIONLANG["presentation"][2] = "Cet assistant permet d'importer des fichiers au format CSV.";
$DATAINJECTIONLANG["presentation"][3] = "Pour commencer, cliquez sur Suivant.";

$DATAINJECTIONLANG["step"][1] = "Etape 1 : ";
$DATAINJECTIONLANG["step"][2] = "Etape 2 : ";
$DATAINJECTIONLANG["step"][3] = "Etape 3 : ";
$DATAINJECTIONLANG["step"][4] = "Etape 4 : ";
$DATAINJECTIONLANG["step"][5] = "Etape 5 : ";
$DATAINJECTIONLANG["step"][6] = "Etape 6 : ";
$DATAINJECTIONLANG["step"][7] = "Etape 7 : ";

$DATAINJECTIONLANG["choiceStep"][1] = "Gestion ou utilisation d'un modèle";
$DATAINJECTIONLANG["choiceStep"][2] = "Cette première étape vous permet de créer, modifier, supprimer, ou utiliser un modèle.";
$DATAINJECTIONLANG["choiceStep"][3] = "Créer un nouveau modèle";
$DATAINJECTIONLANG["choiceStep"][4] = "Modifier un modèle existant";
$DATAINJECTIONLANG["choiceStep"][5] = "Supprimer un modèle existant";
$DATAINJECTIONLANG["choiceStep"][6] = "Utiliser un modèle existant";
$DATAINJECTIONLANG["choiceStep"][7] = "Commentaire du modèle";
$DATAINJECTIONLANG["choiceStep"][8] = "Pas de commentaires";
$DATAINJECTIONLANG["choiceStep"][9] = "Faites votre sélection";
$DATAINJECTIONLANG["choiceStep"][10] = "En fonction de vos droits, vous ne pourrez peut être pas avoir accès à tous les choix.";
$DATAINJECTIONLANG["choiceStep"][11] = "Pour la modification, la suppression et l'utilisation, vous devez selectionner un modèle dans le menu déroulant.";
$DATAINJECTIONLANG["choiceStep"][12] = "Exporter un modèle existant";

$DATAINJECTIONLANG["modelStep"][1] = "Informations sur le type fichier";
$DATAINJECTIONLANG["modelStep"][2] = "Modification du modèle";
$DATAINJECTIONLANG["modelStep"][3] = "Les options dépendent du type de fichier sélectionné.";
$DATAINJECTIONLANG["modelStep"][4] = "Type de données à insérer :";
$DATAINJECTIONLANG["modelStep"][5] = "Type de fichier :";
$DATAINJECTIONLANG["modelStep"][6] = "Création des lignes :";
$DATAINJECTIONLANG["modelStep"][7] = "Mise à jour des lignes :";
$DATAINJECTIONLANG["modelStep"][8] = "Ajouter des intitulés :";
$DATAINJECTIONLANG["modelStep"][9] = "Présence d'un entête :";
$DATAINJECTIONLANG["modelStep"][10] = "Délimiteur du fichier :";
$DATAINJECTIONLANG["modelStep"][11] = "Délimiteur non défini";
$DATAINJECTIONLANG["modelStep"][12] = "Mise à jour des champs existants :";
$DATAINJECTIONLANG["modelStep"][13] = "Informations principales";
$DATAINJECTIONLANG["modelStep"][14] = "Options de fichier ";
$DATAINJECTIONLANG["modelStep"][15] = "Option avancées";
$DATAINJECTIONLANG["modelStep"][16] = "Diffusion :";
$DATAINJECTIONLANG["modelStep"][17] = "Public";
$DATAINJECTIONLANG["modelStep"][18] = "Privée";
$DATAINJECTIONLANG["modelStep"][19] = "Les options avancées permettent un contrôle plus fin de l'import. Elles ne doivent être modifiées que par des utilisateurs expérimentés.";

$DATAINJECTIONLANG["deleteStep"][1] = "Confirmation de la suppression";
$DATAINJECTIONLANG["deleteStep"][2] = "Attention si vous supprimez le modèle, les mappings et les informations complémentaires seront supprimés également.";
$DATAINJECTIONLANG["deleteStep"][3] = "Voulez-vous supprimer";
$DATAINJECTIONLANG["deleteStep"][4] = "définitivement ?";
$DATAINJECTIONLANG["deleteStep"][5] = "Le modèle";
$DATAINJECTIONLANG["deleteStep"][6] = "a été supprimé.";
$DATAINJECTIONLANG["deleteStep"][7] = "Problème lors de la suppression du modèle.";

$DATAINJECTIONLANG["fileStep"][1] = "Sélection du fichier à uploader";
$DATAINJECTIONLANG["fileStep"][2] = "Sélectionnez le fichier sur votre disque dur afin qu'il soit uploadé sur le serveur.";
$DATAINJECTIONLANG["fileStep"][3] = "Choix du fichier :";
$DATAINJECTIONLANG["fileStep"][4] = "Le fichier est introuvable";
$DATAINJECTIONLANG["fileStep"][5] = "Le fichier n'a pas le bon format";
$DATAINJECTIONLANG["fileStep"][6] = "Extension";
$DATAINJECTIONLANG["fileStep"][7] = "requise";
$DATAINJECTIONLANG["fileStep"][8] = "Impossible de copier le fichier dans";
$DATAINJECTIONLANG["fileStep"][9] = "Encodage du fichier :";
$DATAINJECTIONLANG["fileStep"][10] = "Détection automatique";
$DATAINJECTIONLANG["fileStep"][11] = "UTF-8";
$DATAINJECTIONLANG["fileStep"][12] = "ISO8859-1";

$DATAINJECTIONLANG["mappingStep"][1] = "colonnes trouvées";
$DATAINJECTIONLANG["mappingStep"][2] = "Entête du fichier";
$DATAINJECTIONLANG["mappingStep"][3] = "Tables";
$DATAINJECTIONLANG["mappingStep"][4] = "Champs";
$DATAINJECTIONLANG["mappingStep"][5] = "Champs de liaison";
$DATAINJECTIONLANG["mappingStep"][6] = "-------Choisir une table-------";
$DATAINJECTIONLANG["mappingStep"][7] = "-------Choisir un champ-------";
$DATAINJECTIONLANG["mappingStep"][8] = "Au moins un mapping doit être obligatoire";
$DATAINJECTIONLANG["mappingStep"][9] = "Dans cette étape vous allez mettre en relation les champs du fichier et de la base de données.";
$DATAINJECTIONLANG["mappingStep"][10] = "La colonne entête correspond aux entêtes du fichier";
$DATAINJECTIONLANG["mappingStep"][11] = "Au moins un champs de liaison doit être renseigné : il permet de rechercher si les données à insérer existent déjà en base";

$DATAINJECTIONLANG["infoStep"][1] = "Informations complémentaires";
$DATAINJECTIONLANG["infoStep"][2] = "Modification des informations complémentaires";
$DATAINJECTIONLANG["infoStep"][3] = "Vous pouvez définir des données qui devront être saisies manuellement au moment de l'import.";
$DATAINJECTIONLANG["infoStep"][4] = "Celles-ci seront communes à tous les objets importés.";
$DATAINJECTIONLANG["infoStep"][5] = "Information obligatoire";

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
$DATAINJECTIONLANG["saveStep"][11] = "Le nombre de colonnes du fichier n'est pas correct.";
$DATAINJECTIONLANG["saveStep"][12] = "Au moins une colonne est incorrecte";
$DATAINJECTIONLANG["saveStep"][13] = "Enregistrer le modèle afin qu'il puisse être utilisé ultérieurement.";
$DATAINJECTIONLANG["saveStep"][14] = "Il vous suffira de le sélectionner dans le menu déroulant de la première étape lors de la demande du choix.";
$DATAINJECTIONLANG["saveStep"][15] = "Vous pouvez écrire un commentaire pour ajouter des informations sur la nature du modèle.";

$DATAINJECTIONLANG["fillInfoStep"][1] = "Attention ! Vous êtes sur le point d'importer des données dans GLPI. Etes-vous sûr de vouloir importer ?";
$DATAINJECTIONLANG["fillInfoStep"][2] = "Remplissez les champs pour que les informations soient insérées dans GLPI lors de l'importation.";
$DATAINJECTIONLANG["fillInfoStep"][3] = "* Champ obligatoire";
$DATAINJECTIONLANG["fillInfoStep"][4] = "Un champ obligatoire n'est pas remplis";

$DATAINJECTIONLANG["importStep"][1] = "Importation du fichier";
$DATAINJECTIONLANG["importStep"][2] = "L'importation du fichier peut prendre plusieurs minutes en fonction de votre configurations. Veuillez patientez et suivre la barre de progression pour voir où en est l'importation.";
$DATAINJECTIONLANG["importStep"][3] = "Importation terminée";

$DATAINJECTIONLANG["logStep"][1] = "Résultat de l'importation";
$DATAINJECTIONLANG["logStep"][2] = "Le bouton 'voir le rapport' vous permet de vérifier que l'importation c'est déroulée sans problèmes.";
$DATAINJECTIONLANG["logStep"][3] = "L'importation a réussi";
$DATAINJECTIONLANG["logStep"][4] = "Tableau des imports qui ont réussis";
$DATAINJECTIONLANG["logStep"][5] = "Tableau des imports qui ont échoués ou partiellement réussis";
$DATAINJECTIONLANG["logStep"][6] = "Le bouton 'exporter le rapport en PDF' vous permet d'enregistrer le rapport sur votre disque dur afin de garder une trace.";
$DATAINJECTIONLANG["logStep"][7] = "Le bouton 'générer le fichier importer' vous permet d'enregistrer sur le disque dur le fichier que vous venez d'importer avec les lignes qui ont échouées.";
$DATAINJECTIONLANG["logStep"][8] = "L'importation a rencontré des erreurs";

$DATAINJECTIONLANG["button"][1] = "< Précédent";
$DATAINJECTIONLANG["button"][2] = "Suivant >";
$DATAINJECTIONLANG["button"][3] = "Voir le fichier";
$DATAINJECTIONLANG["button"][4] = "Voir le rapport";
$DATAINJECTIONLANG["button"][5] = "Générer le fichier importer";
$DATAINJECTIONLANG["button"][6] = "Terminé";
$DATAINJECTIONLANG["button"][7] = "Exporter le rapport en PDF";

$DATAINJECTIONLANG["result"][1] = "Mauvais type";
$DATAINJECTIONLANG["result"][2] = "Les données à insérer sont correctes";
$DATAINJECTIONLANG["result"][3] = "Les données existent déjà en base";
$DATAINJECTIONLANG["result"][4] = "Au moins un champs obligatoire n'est pas remplis";
$DATAINJECTIONLANG["result"][5] = "Pas les droits pour importer les données";
$DATAINJECTIONLANG["result"][6] = "Pas les droits pour mettre à jour les données";
$DATAINJECTIONLANG["result"][7] = "L'import s'est bien passé";
$DATAINJECTIONLANG["result"][8] = "Ajout";
$DATAINJECTIONLANG["result"][9] = "Mise à jour";
$DATAINJECTIONLANG["result"][10] = "Vérification des données";
$DATAINJECTIONLANG["result"][11] = "Import des données";
$DATAINJECTIONLANG["result"][12] = "Type d'injection";
$DATAINJECTIONLANG["result"][13] = "Identifiant de l'objet";
$DATAINJECTIONLANG["result"][14] = "Ligne";

$DATAINJECTIONLANG["profiles"][1] = "Créer un modèle";
$DATAINJECTIONLANG["profiles"][2] = "Supprimer un modèle";
$DATAINJECTIONLANG["profiles"][3] = "Utiliser un modèle";
$DATAINJECTIONLANG["profile"][4] = "Listes des profils déjà configurés";

$DATAINJECTIONLANG["mappings"][1] = "Nombre de ports";
$DATAINJECTIONLANG["mappings"][2] = "Port réseau";

$DATAINJECTIONLANG["history"][1] = "depuis un fichier CSV";
$DATAINJECTIONLANG["logevent"][1] = "injection d'un fichier CSV.";

?>