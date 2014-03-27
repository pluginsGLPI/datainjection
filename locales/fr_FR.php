<?php
/*
 * @version $Id: HEADER 14684 2011-06-11 06:32:40Z remi $
 LICENSE

 This file is part of the datainjection plugin.

 Datainjection plugin is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Datainjection plugin is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with datainjection. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 @package   datainjection
 @author    the datainjection plugin team
 @copyright Copyright (c) 2010-2013 Datainjection plugin team
 @license   GPLv2+
            http://www.gnu.org/licenses/gpl.txt
 @link      https://forge.indepnet.net/projects/datainjection
 @link      http://www.glpi-project.org/
 @since     2009
 ---------------------------------------------------------------------- */
 
$LANG['datainjection']['name'][1] = "Injection de fichiers";

$LANG['datainjection']['choiceStep'][6] = "Utiliser un modèle existant";

$LANG['datainjection']['model'][4]  = "Type de données à insérer";
$LANG['datainjection']['model'][5]  = "Type de fichier";
$LANG['datainjection']['model'][6]  = "Création des lignes";
$LANG['datainjection']['model'][7]  = "Mise à jour des lignes";
$LANG['datainjection']['model'][8]  = "Ajouter des intitulés";
$LANG['datainjection']['model'][9]  = "Présence d'un en-tête";
$LANG['datainjection']['model'][10] = "Délimiteur du fichier";
$LANG['datainjection']['model'][12] = "Mise à jour des champs existants";
$LANG['datainjection']['model'][15] = "Options avancées";
$LANG['datainjection']['model'][18] = "Privé";
$LANG['datainjection']['model'][20] = "Essayer de réaliser les connexions réseau";
$LANG['datainjection']['model'][21] = "Format des dates";
$LANG['datainjection']['model'][22] = "jj-mm-aaaa";
$LANG['datainjection']['model'][23] = "mm-jj-aaaa";
$LANG['datainjection']['model'][24] = "aaaa-mm-jj";
$LANG['datainjection']['model'][25] = "1 234.56";
$LANG['datainjection']['model'][26] = "1 234,56";
$LANG['datainjection']['model'][27] = "1,234.56";
$LANG['datainjection']['model'][28] = "Format des nombres flottants";
$LANG['datainjection']['model'][29] = "Options spécifiques au format de fichier";
$LANG['datainjection']['model'][31] = "Votre modèle doit permettre l'import et/ou la mise à jour de données";
$LANG['datainjection']['model'][32] = "Le fichier est correct.".
                     "<br>Vous pouvez à présent faire les correspondances avec les champs de GLPI";
$LANG['datainjection']['model'][33] = "Aucun modèle n'est disponible actuellement";
$LANG['datainjection']['model'][34] = "Vous pouvez en créer un en cliquant sur le bouton";
$LANG['datainjection']['model'][35] = "Modèle en cours de création";
$LANG['datainjection']['model'][36] = "Modèle utilisable";
$LANG['datainjection']['model'][37] = "Validation";
$LANG['datainjection']['model'][38] = "Valider le modèle";
$LANG['datainjection']['model'][39] = "Liste des modèles";
$LANG['datainjection']['model'][40] = "Téléchargement fichier example";

$LANG['datainjection']['fileStep'][3]  = "Choix du fichier";
$LANG['datainjection']['fileStep'][4]  = "Le fichier est introuvable";
$LANG['datainjection']['fileStep'][5]  = "Le fichier n'a pas le bon format";
$LANG['datainjection']['fileStep'][6]  = "Extension";
$LANG['datainjection']['fileStep'][7]  = "requise";
$LANG['datainjection']['fileStep'][8]  = "Impossible de copier le fichier dans";
$LANG['datainjection']['fileStep'][9]  = "Encodage du fichier";
$LANG['datainjection']['fileStep'][10] = "Détection automatique";
$LANG['datainjection']['fileStep'][11] = "UTF-8";
$LANG['datainjection']['fileStep'][12] = "ISO8859-1";
$LANG['datainjection']['fileStep'][13] = "Charger le fichier";

$LANG['datainjection']['mapping'][2]  = "En-tête du fichier";
$LANG['datainjection']['mapping'][3]  = "Tables";
$LANG['datainjection']['mapping'][4]  = "Champs";
$LANG['datainjection']['mapping'][5]  = "Champs de liaison";
$LANG['datainjection']['mapping'][6]  = "-------Choisir une table-------";
$LANG['datainjection']['mapping'][7]  = "-------Choisir un champ-------";
$LANG["datainjection"]["mapping"][8] = "Connecté à : Nom actif";
$LANG["datainjection"]["mapping"][9] = "Connecté à : Numéro de port";
$LANG["datainjection"]["mapping"][10] = "Connecté à : adresse MAC du port";
$LANG['datainjection']['mapping'][11] = "Au moins un champs de liaison doit être renseigné :<br> il permet de rechercher si les données à insérer existent déjà en base";
$LANG['datainjection']['mapping'][13] = "Attention : Les données existantes seront écrasées !";

$LANG['datainjection']['info'][1] = "Informations complémentaires";
$LANG['datainjection']['info'][3] = "Vous pouvez à présent définir des données qui devront être saisies manuellement au moment de l'import.";
$LANG['datainjection']['info'][5] = "Information obligatoire";

$LANG['datainjection']['saveStep'][11] = "Le nombre de colonnes du fichier n'est pas correct : ";
$LANG['datainjection']['saveStep'][12] = "Au moins une colonne est incorrecte : ";
$LANG['datainjection']['saveStep'][16] = " colonne(s) attendue(s)";
$LANG['datainjection']['saveStep'][17] = " colonne(s) trouvée(s)";
$LANG['datainjection']['saveStep'][18] = " Dans le fichier ";
$LANG['datainjection']['saveStep'][19] = " Dans le modèle ";

$LANG['datainjection']['fillInfoStep'][1] = "Attention ! Vous allez importer des données dans GLPI.Etes-vous sûr de vouloir continuer ?";
$LANG['datainjection']['fillInfoStep'][3] = "* Champ obligatoire";
$LANG['datainjection']['fillInfoStep'][4] = "Un champ obligatoire n'est pas remplis";

$LANG['datainjection']['importStep'][1] = "Importation du fichier";
$LANG['datainjection']['importStep'][3] = "Importation terminée";

$LANG['datainjection']['log'][1]  = "Résultat de l'importation";
$LANG['datainjection']['log'][3]  = "L'importation a réussi";
$LANG['datainjection']['log'][4]  = "Tableau des imports qui ont réussis";
$LANG['datainjection']['log'][5]  = "Tableau des imports qui ont échoués ou partiellement réussis";
$LANG['datainjection']['log'][8]  = "L'importation a rencontré des erreurs";
$LANG['datainjection']['log'][9]  = "Vérification des données";
$LANG['datainjection']['log'][10] = "Import des données";
$LANG['datainjection']['log'][11] = "Type d'injection";
$LANG['datainjection']['log'][12] = "Identifiant de l'objet";
$LANG['datainjection']['log'][13] = "Ligne";

$LANG['datainjection']['button'][3] = "Voir un aperçu du fichier";
$LANG['datainjection']['button'][4] = "Voir le rapport";
$LANG['datainjection']['button'][5] = "Générer CSV des erreurs";
$LANG['datainjection']['button'][6] = "Terminer";
$LANG['datainjection']['button'][7] = "Exporter le rapport en PDF";
$LANG['datainjection']['button'][8] = "Fermer";

$LANG['datainjection']['result'][2]  = "Les données à insérer sont correctes";
$LANG['datainjection']['result'][4]  = "Au moins un champs obligatoire n'est pas remplis";
$LANG['datainjection']['result'][6]  = "Indéterminé";
$LANG['datainjection']['result'][7]  = "L'import s'est bien passé";
$LANG['datainjection']['result'][8]  = "Ajout";
$LANG['datainjection']['result'][9]  = "Mise à jour";
$LANG['datainjection']['result'][10] = "Succès";
$LANG['datainjection']['result'][11] = "Echec";
$LANG['datainjection']['result'][12] = "Avertissement";
$LANG['datainjection']['result'][17] = "Pas de données à insérer";
$LANG['datainjection']['result'][18] = "Rapport d'injection du fichier";
$LANG['datainjection']['result'][21] = "Import impossible";
$LANG['datainjection']['result'][22] = "Mauvais type";
$LANG['datainjection']['result'][23] = "Au moins un champs obligatoire est manquant";

$LANG['datainjection']['result'][30] = "Les données existent déjà en base";
$LANG['datainjection']['result'][31] = "Pas les droits pour importer les données";
$LANG['datainjection']['result'][32] = "Pas les droits pour mettre à jour les données";
$LANG['datainjection']['result'][33] = "Donnée introuvable";
$LANG['datainjection']['result'][34] = "Donnée déjà utilisée";
$LANG['datainjection']['result'][35] = "Plus d'une valeur trouvée";
$LANG['datainjection']['result'][36] = "L'objet est déjà lié";
$LANG['datainjection']['result'][37] = "Taille maximum du champs dépassée";
$LANG['datainjection']['result'][39] = "Import refusé par le dictionnaire";

$LANG['datainjection']['profiles'][1] = "Gestion des modèles";

$LANG['datainjection']['mappings'][1] = "Nombre de ports";
$LANG['datainjection']['mappings'][7] = "Critère d'unicité d'un port";

$LANG['datainjection']['history'][1] = "depuis un fichier CSV";

$LANG['datainjection']['model'][0] = "Modèle";

$LANG['datainjection']['tabs'][0] = "Correspondances";
$LANG['datainjection']['tabs'][1] = "Infos complémentaires";
$LANG['datainjection']['tabs'][2] = "Valeurs fixées";
$LANG['datainjection']['tabs'][3] = "Fichier à injecter";

$LANG['datainjection']['import'][0] = "Procéder à l'import";
$LANG['datainjection']['import'][1] = "Etat d'avancement de l'import";

$LANG['datainjection']['port'][1] = "Liaison réseau";
$LANG['datainjection']['entity'][1] = "Informations entité";

$LANG['datainjection']['install'][1] = "doit exister et accessible en écriture pour l'utilisateur du serveur Web";

?>