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

$DATAINJECTIONLANG["presentation"][1] = "Bienvenue dans l'assistant d'aide à l'importation de fichier";
$DATAINJECTIONLANG["presentation"][2] = "L'assistant n'est pas encore fonctionnel.";

$DATAINJECTIONLANG["step1"][1] = "Etape 1 : Sélection ou création d'un modèle";
$DATAINJECTIONLANG["step1"][2] = "Créer un nouveau modèle";
$DATAINJECTIONLANG["step1"][3] = "Modifier un modèle existant";
$DATAINJECTIONLANG["step1"][4] = "Supprimer un modèle existant";
$DATAINJECTIONLANG["step1"][5] = "Utiliser un modèle existant";

$DATAINJECTIONLANG["step2"][1] = "Etape 2 : Collecte d'information sur le fichier";
$DATAINJECTIONLANG["step2"][2] = "Type de fichier";
$DATAINJECTIONLANG["step2"][3] = "Délimiteur du fichier";
$DATAINJECTIONLANG["step2"][4] = "Création des lignes";
$DATAINJECTIONLANG["step2"][5] = "Mise à jour des lignes";
$DATAINJECTIONLANG["step2"][6] = "Présence d'un entête";
$DATAINJECTIONLANG["step2"][7] = "Type de données à insérer";
$DATAINJECTIONLANG["step2"][8] = "Délimiteur non définie";

$DATAINJECTIONLANG["step3"][1] = "Etape 2 : Modification du modèle";

$DATAINJECTIONLANG["step4"][1] = "Etape 2 : Confirmation de la suppression";
$DATAINJECTIONLANG["step4"][2] = "Voulez-vous supprimer";
$DATAINJECTIONLANG["step4"][3] = "définitivement ?";
$DATAINJECTIONLANG["step4"][4] = "Le modèle";
$DATAINJECTIONLANG["step4"][5] = "a été supprimé.";
$DATAINJECTIONLANG["step4"][6] = "Problème lors de la suppression du modèle.";

$DATAINJECTIONLANG["step5"][1] = "Etape 2 : Sélection du fichier à uploader";
$DATAINJECTIONLANG["step5"][2] = "Choix du fichier :";
$DATAINJECTIONLANG["step5"][3] = "Le fichier est introuvable";
$DATAINJECTIONLANG["step5"][4] = "Le fichier n'a pas le bon format";
$DATAINJECTIONLANG["step5"][5] = "Extension";
$DATAINJECTIONLANG["step5"][6] = "requise";
$DATAINJECTIONLANG["step5"][7] = "Impossible de copier le fichier dans";

$DATAINJECTIONLANG["step6"][1] = "Etape 3 : Sélection du fichier à uploader";

$DATAINJECTIONLANG["step7"][1] = "Etape 3 : Modification des mappings";

$DATAINJECTIONLANG["step9"][1] = "Etape 4 :";
$DATAINJECTIONLANG["step9"][2] = "colonnes à mapper ont été trouvées";
$DATAINJECTIONLANG["step9"][3] = "-------Choisir une table-------";
$DATAINJECTIONLANG["step9"][4] = "-------Choisir un champ-------";
$DATAINJECTIONLANG["step9"][5] = "Au moins un mapping doit être obligatoire";
$DATAINJECTIONLANG["step9"][6] = "Entête du fichier";
$DATAINJECTIONLANG["step9"][7] = "Tables";
$DATAINJECTIONLANG["step9"][8] = "Champs";
$DATAINJECTIONLANG["step9"][9] = "Obligatoire";

$DATAINJECTIONLANG["step10"][1] = "Etape 4 : Modification des informations complémentaires";

$DATAINJECTIONLANG["step12"][1] = "Etape 5 : Informations complémentaires";

$DATAINJECTIONLANG["step13"][1] = "Etape 5 : Enregistrement du modèle";
$DATAINJECTIONLANG["step13"][2] = "Voulez-vous mettre le modèle à jour ?";
$DATAINJECTIONLANG["step13"][3] = "Votre modèle n'a pas été mis à jour mais est quand même prêt à l'emploi.";
$DATAINJECTIONLANG["step13"][4] = "Votre modèle a été mis à jour et est prêt à l'emploi.";

$DATAINJECTIONLANG["step15"][1] = "Etape 6 : Enregistrement du modèle";
$DATAINJECTIONLANG["step15"][2] = "Voulez-vous enregistrer le modèle ?";
$DATAINJECTIONLANG["step15"][3] = "Entrez le nom du modèle :";
$DATAINJECTIONLANG["step15"][4] = "Ajoutez un commentaire :";
$DATAINJECTIONLANG["step15"][5] = "Votre modèle n'a pas été enregistré mais est quand même prêt à l'emploi.";
$DATAINJECTIONLANG["step15"][6] = "Votre modèle a été enregistré et est prêt à l'emploi.";

$DATAINJECTIONLANG["button"][1] = "< Précédent";
$DATAINJECTIONLANG["button"][2] = "Suivant >";
$DATAINJECTIONLANG["button"][3] = "Voir le fichier";

?>
