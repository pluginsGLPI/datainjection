<?php
/*
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

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

$LANG["datainjection"]["name"][1] = "Injection de fichiers";
$title = $LANG["datainjection"]["name"][1] ;

$LANG["datainjection"]["config"][1]="Configuration du plugin ".$title;

$LANG["datainjection"]["setup"][1] = "Configuration du plugin ".$title."";
$LANG["datainjection"]["setup"][3] = "Installer le plugin $title";
$LANG["datainjection"]["setup"][4] = "Mettre à jour le plugin $title";
$LANG["datainjection"]["setup"][5] = "Désinstaller le plugin $title";
$LANG["datainjection"]["setup"][9] = "Gestion des droits";
$LANG["datainjection"]["setup"][10] = "Ce plugin est compatible avec PHP 5 ou supérieur";
$LANG["datainjection"]["setup"][11] = "Mode d'emploi";


$LANG["datainjection"]["presentation"][1] = "Bienvenue dans l'assistant d'importation de fichiers";
$LANG["datainjection"]["presentation"][2] = "Cet assistant permet d'importer des fichiers au format CSV.";
$LANG["datainjection"]["presentation"][3] = "Pour commencer, cliquez sur Suivant.";

$LANG["datainjection"]["step"][1] = "Etape 1 : ";
$LANG["datainjection"]["step"][2] = "Etape 2 : ";
$LANG["datainjection"]["step"][3] = "Etape 3 : ";
$LANG["datainjection"]["step"][4] = "Etape 4 : ";
$LANG["datainjection"]["step"][5] = "Etape 5 : ";
$LANG["datainjection"]["step"][6] = "Etape 6 : ";

$LANG["datainjection"]["choiceStep"][1] = "Gestion ou utilisation d'un modèle";
$LANG["datainjection"]["choiceStep"][2] = "Cette première étape vous permet de créer, modifier, supprimer, ou utiliser un modèle.";
$LANG["datainjection"]["choiceStep"][3] = "Créer un nouveau modèle";
$LANG["datainjection"]["choiceStep"][4] = "Modifier un modèle existant";
$LANG["datainjection"]["choiceStep"][5] = "Supprimer un modèle existant";
$LANG["datainjection"]["choiceStep"][6] = "Utiliser un modèle existant";
$LANG["datainjection"]["choiceStep"][7] = "Commentaire du modèle";
$LANG["datainjection"]["choiceStep"][8] = "Pas de commentaires";
$LANG["datainjection"]["choiceStep"][9] = "Faites votre sélection";
$LANG["datainjection"]["choiceStep"][10] = "En fonction de vos droits, vous ne pourrez peut être pas avoir accès à tous les choix.";
$LANG["datainjection"]["choiceStep"][11] = "Pour la modification, la suppression et l'utilisation, vous devez selectionner un modèle dans le menu déroulant.";

$LANG["datainjection"]["modelStep"][1] = "Informations sur le type fichier";
$LANG["datainjection"]["modelStep"][2] = "Modification du modèle";
$LANG["datainjection"]["modelStep"][3] = "Les options dépendent du type de fichier sélectionné.";
$LANG["datainjection"]["modelStep"][4] = "Type de données à insérer :";
$LANG["datainjection"]["modelStep"][5] = "Type de fichier :";
$LANG["datainjection"]["modelStep"][6] = "Création des lignes :";
$LANG["datainjection"]["modelStep"][7] = "Mise à jour des lignes :";
$LANG["datainjection"]["modelStep"][8] = "Ajouter des intitulés :";
$LANG["datainjection"]["modelStep"][9] = "Présence d'un en-tête :";
$LANG["datainjection"]["modelStep"][10] = "Délimiteur du fichier :";
$LANG["datainjection"]["modelStep"][11] = "Délimiteur non défini";
$LANG["datainjection"]["modelStep"][12] = "Mise à jour des champs existants :";
$LANG["datainjection"]["modelStep"][13] = "Informations principales";
$LANG["datainjection"]["modelStep"][14] = "Options de fichier ";
$LANG["datainjection"]["modelStep"][15] = "Option avancées";
$LANG["datainjection"]["modelStep"][16] = "Diffusion :";
$LANG["datainjection"]["modelStep"][17] = "Public";
$LANG["datainjection"]["modelStep"][18] = "Privée";
$LANG["datainjection"]["modelStep"][19] = "Les options avancées permettent un contrôle plus fin de l'import. Elles ne doivent être modifiées que par des utilisateurs expérimentés.";
$LANG["datainjection"]["modelStep"][20] = "Essayer de réaliser les connexions réseau";
$LANG["datainjection"]["modelStep"][21] = "Format des dates";
$LANG["datainjection"]["modelStep"][22] = "jj-mm-aaaa";
$LANG["datainjection"]["modelStep"][23] = "mm-jj-aaaa";
$LANG["datainjection"]["modelStep"][24] = "aaaa-mm-jj";
$LANG["datainjection"]["modelStep"][25] = "1 234.56";
$LANG["datainjection"]["modelStep"][26] = "1 234,56";
$LANG["datainjection"]["modelStep"][27] = "1,234.56";
$LANG["datainjection"]["modelStep"][28] = "Format des nombres flottants";

$LANG["datainjection"]["deleteStep"][1] = "Confirmation de la suppression";
$LANG["datainjection"]["deleteStep"][2] = "Attention si vous supprimez le modèle, les mappings et les informations complémentaires seront supprimés également.";
$LANG["datainjection"]["deleteStep"][3] = "Voulez-vous supprimer";
$LANG["datainjection"]["deleteStep"][4] = "définitivement ?";
$LANG["datainjection"]["deleteStep"][5] = "Le modèle";
$LANG["datainjection"]["deleteStep"][6] = "a été supprimé.";
$LANG["datainjection"]["deleteStep"][7] = "Problème lors de la suppression du modèle.";

$LANG["datainjection"]["fileStep"][1] = "Sélection du fichier à télécharger";
$LANG["datainjection"]["fileStep"][2] = "Sélectionnez le fichier sur votre disque dur afin qu'il soit envoyé sur le serveur.";
$LANG["datainjection"]["fileStep"][3] = "Choix du fichier :";
$LANG["datainjection"]["fileStep"][4] = "Le fichier est introuvable";
$LANG["datainjection"]["fileStep"][5] = "Le fichier n'a pas le bon format";
$LANG["datainjection"]["fileStep"][6] = "Extension";
$LANG["datainjection"]["fileStep"][7] = "requise";
$LANG["datainjection"]["fileStep"][8] = "Impossible de copier le fichier dans";
$LANG["datainjection"]["fileStep"][9] = "Encodage du fichier :";
$LANG["datainjection"]["fileStep"][10] = "Détection automatique";
$LANG["datainjection"]["fileStep"][11] = "UTF-8";
$LANG["datainjection"]["fileStep"][12] = "ISO8859-1";

$LANG["datainjection"]["mappingStep"][1] = "colonnes trouvées";
$LANG["datainjection"]["mappingStep"][2] = "En-tête du fichier";
$LANG["datainjection"]["mappingStep"][3] = "Tables";
$LANG["datainjection"]["mappingStep"][4] = "Champs";
$LANG["datainjection"]["mappingStep"][5] = "Champs de liaison";
$LANG["datainjection"]["mappingStep"][6] = "-------Choisir une table-------";
$LANG["datainjection"]["mappingStep"][7] = "-------Choisir un champ-------";
$LANG["datainjection"]["mappingStep"][8] = "Au moins un champ de liaison doit être défini";
$LANG["datainjection"]["mappingStep"][9] = "Dans cette étape vous allez mettre en relation les champs du fichier et de la base de données.";
$LANG["datainjection"]["mappingStep"][10] = "La colonne en-tête correspond aux en-têtes du fichier";
$LANG["datainjection"]["mappingStep"][11] = "Au moins un champs de liaison doit être renseigné : il permet de rechercher si les données à insérer existent déjà en base";
$LANG["datainjection"]["mappingStep"][12] = "Nombre de lignes";

$LANG["datainjection"]["infoStep"][1] = "Informations complémentaires";
$LANG["datainjection"]["infoStep"][2] = "Modification des informations complémentaires";
$LANG["datainjection"]["infoStep"][3] = "Vous pouvez définir des données qui devront être saisies manuellement au moment de l'import.";
$LANG["datainjection"]["infoStep"][4] = "Celles-ci seront communes à tous les objets importés.";
$LANG["datainjection"]["infoStep"][5] = "Information obligatoire";

$LANG["datainjection"]["saveStep"][1] = "Enregistrement du modèle";
$LANG["datainjection"]["saveStep"][2] = "Voulez-vous enregistrer le modèle ?";
$LANG["datainjection"]["saveStep"][3] = "Voulez-vous mettre le modèle à jour ?";
$LANG["datainjection"]["saveStep"][4] = "Entrez le nom du modèle :";
$LANG["datainjection"]["saveStep"][5] = "Ajoutez un commentaire :";
$LANG["datainjection"]["saveStep"][6] = "Votre modèle n'a pas été enregistré mais est quand même prêt à l'emploi.";
$LANG["datainjection"]["saveStep"][7] = "Votre modèle n'a pas été mis à jour mais est quand même prêt à l'emploi.";
$LANG["datainjection"]["saveStep"][8] = "Votre modèle a été enregistré et est prêt à l'emploi.";
$LANG["datainjection"]["saveStep"][9] = "Votre modèle a été mis à jour et est prêt à l'emploi.";
$LANG["datainjection"]["saveStep"][10] = "Voulez-vous utiliser le modèle maintenant ?";
$LANG["datainjection"]["saveStep"][11] = "Le nombre de colonnes du fichier n'est pas correct : ";
$LANG["datainjection"]["saveStep"][12] = "Au moins une colonne est incorrecte : ";
$LANG["datainjection"]["saveStep"][13] = "Enregistrer le modèle afin qu'il puisse être utilisé ultérieurement.";
$LANG["datainjection"]["saveStep"][14] = "Il vous suffira de le sélectionner dans le menu déroulant de la première étape lors de la demande du choix.";
$LANG["datainjection"]["saveStep"][15] = "Vous pouvez écrire un commentaire pour ajouter des informations sur la nature du modèle.";
$LANG["datainjection"]["saveStep"][16] = " colonne(s) attendue(s)";
$LANG["datainjection"]["saveStep"][17] = " colonne(s) trouvée(s)";
$LANG["datainjection"]["saveStep"][18] = " Dans le fichier : ";
$LANG["datainjection"]["saveStep"][19] = " Dans le modèle : ";


$LANG["datainjection"]["fillInfoStep"][1] = "Attention ! Vous êtes sur le point d'importer des données dans GLPI. Etes-vous sûr de vouloir importer ?";
$LANG["datainjection"]["fillInfoStep"][2] = "Remplissez les champs pour que les informations soient insérées dans GLPI lors de l'importation.";
$LANG["datainjection"]["fillInfoStep"][3] = "* Champ obligatoire";
$LANG["datainjection"]["fillInfoStep"][4] = "Un champ obligatoire n'est pas remplis";

$LANG["datainjection"]["importStep"][1] = "Importation du fichier";
$LANG["datainjection"]["importStep"][2] = "L'importation du fichier peut prendre plusieurs minutes en fonction de votre configurations. Veuillez patientez et suivre la barre de progression pour voir où en est l'importation.";
$LANG["datainjection"]["importStep"][3] = "Importation terminée";

$LANG["datainjection"]["logStep"][1] = "Résultat de l'importation";
$LANG["datainjection"]["logStep"][2] = "Le bouton 'voir le rapport' vous permet de vérifier que l'importation c'est déroulée sans problèmes.";
$LANG["datainjection"]["logStep"][3] = "L'importation a réussi";
$LANG["datainjection"]["logStep"][4] = "Tableau des imports qui ont réussis";
$LANG["datainjection"]["logStep"][5] = "Tableau des imports qui ont échoués ou partiellement réussis";
$LANG["datainjection"]["logStep"][6] = "Le bouton 'exporter le rapport en PDF' vous permet d'enregistrer le rapport sur votre disque dur afin de garder une trace.";
$LANG["datainjection"]["logStep"][7] = "Le bouton 'générer le fichier importer' vous permet d'enregistrer sur le disque dur le fichier que vous venez d'importer avec les lignes qui ont échouées.";
$LANG["datainjection"]["logStep"][8] = "L'importation a rencontré des erreurs";

$LANG["datainjection"]["button"][1] = "< Précédent";
$LANG["datainjection"]["button"][2] = "Suivant >";
$LANG["datainjection"]["button"][3] = "Voir le fichier";
$LANG["datainjection"]["button"][4] = "Voir le rapport";
$LANG["datainjection"]["button"][5] = "Générer CSV des erreurs";
$LANG["datainjection"]["button"][6] = "Terminé";
$LANG["datainjection"]["button"][7] = "Exporter le rapport en PDF";
$LANG["datainjection"]["button"][8] = "Fermer";

$LANG["datainjection"]["result"][1] = "Mauvais type";
$LANG["datainjection"]["result"][2] = "Les données à insérer sont correctes";
$LANG["datainjection"]["result"][3] = "Les données existent déjà en base";
$LANG["datainjection"]["result"][4] = "Au moins un champs obligatoire n'est pas remplis";
$LANG["datainjection"]["result"][5] = "Pas les droits pour importer les données";
$LANG["datainjection"]["result"][6] = "Pas les droits pour mettre à jour les données";
$LANG["datainjection"]["result"][7] = "L'import s'est bien passé";
$LANG["datainjection"]["result"][8] = "Ajout";
$LANG["datainjection"]["result"][9] = "Mise à jour";
$LANG["datainjection"]["result"][10] = "Vérification des données";
$LANG["datainjection"]["result"][11] = "Import des données";
$LANG["datainjection"]["result"][12] = "Type d'injection";
$LANG["datainjection"]["result"][13] = "Identifiant de l'objet";
$LANG["datainjection"]["result"][14] = "Ligne";
$LANG["datainjection"]["result"][15] = "Donnée introuvable";
$LANG["datainjection"]["result"][16] = "Donnée déjà utilisée";
$LANG["datainjection"]["result"][17] = "Pas de données à insérer";
$LANG["datainjection"]["result"][18] = "Rapport d'injection du fichier";
$LANG["datainjection"]["result"][19] = "Plus d'une valeur trouvée";
$LANG["datainjection"]["result"][20] = "L'objet est déjà lié";
$LANG["datainjection"]["result"][21] = "Import impossible";

$LANG["datainjection"]["profiles"][1] = "Gestion des modèles";
$LANG["datainjection"]["profiles"][4] = "Listes des profils déjà configurés";

$LANG["datainjection"]["mappings"][1] = "Nombre de ports";
$LANG["datainjection"]["mappings"][2] = "Port réseau";
$LANG["datainjection"]["mappings"][3] = "Connecté à : Nom actif";
$LANG["datainjection"]["mappings"][4] = "Connecté à : Numéro de port";
$LANG["datainjection"]["mappings"][5] = "Ordinateur";
$LANG["datainjection"]["mappings"][6] = "Connecté à : adresse MAC du port";
$LANG["datainjection"]["mappings"][7] = "Critère d'unicité d'un port";


$LANG["datainjection"]["history"][1] = "depuis un fichier CSV";
$LANG["datainjection"]["logevent"][1] = "injection d'un fichier CSV.";

$LANG["datainjection"]["entity"][0] = "Entité parente";

$LANG["datainjection"]["associate"][0] = "Association à un objet";

?>