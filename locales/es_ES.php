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

$DATAINJECTIONLANG["name"][1] = "Importar archivo";
$title = $DATAINJECTIONLANG["name"][1] ;

$DATAINJECTIONLANG["config"][1]= "Configuración de " . $title;

$DATAINJECTIONLANG["setup"][1] = "Configuración de " . $title;
$DATAINJECTIONLANG["setup"][3] = "Instalar el plugin $title";
$DATAINJECTIONLANG["setup"][5] = "Desinstalar el plugin $title";
$DATAINJECTIONLANG["setup"][9] = "Ajuste de permisos";
$DATAINJECTIONLANG["setup"][10] = "Se requiere PHP 5 o superior para usar este plugin";
$DATAINJECTIONLANG["setup"][11] = "Instrucciones";

$DATAINJECTIONLANG["presentation"][1] = "Bienvenido al ayudante de " . $title;
$DATAINJECTIONLANG["presentation"][2] = "Este plugin le permitirá importar archivos en formato CSV";
$DATAINJECTIONLANG["presentation"][3] = "Para empezar, pulse el botón Siguiente";

$DATAINJECTIONLANG["step"][1] = "Paso 1 : ";
$DATAINJECTIONLANG["step"][2] = "Paso 2 : ";
$DATAINJECTIONLANG["step"][3] = "Paso 3 : ";
$DATAINJECTIONLANG["step"][4] = "Paso 4 : ";
$DATAINJECTIONLANG["step"][5] = "Paso 5 : ";
$DATAINJECTIONLANG["step"][6] = "Paso 6 : ";

$DATAINJECTIONLANG["choiceStep"][1] = "Uso o manejo del modelo";
$DATAINJECTIONLANG["choiceStep"][2] = "Este primer paso permite crear, modificar, borrar o usar un modelo";
$DATAINJECTIONLANG["choiceStep"][3] = "Crear un nuevo modelo";
$DATAINJECTIONLANG["choiceStep"][4] = "Modificar un modelo existente";
$DATAINJECTIONLANG["choiceStep"][5] = "Borrar un modelo existente";
$DATAINJECTIONLANG["choiceStep"][6] = "Usar un modelo existente";
$DATAINJECTIONLANG["choiceStep"][7] = "Comentarios del modelo";
$DATAINJECTIONLANG["choiceStep"][8] = "Sin comentarios";
$DATAINJECTIONLANG["choiceStep"][9] = "Elección";
$DATAINJECTIONLANG["choiceStep"][10] = "Dependiendo de los permisos, puede que no tenga acceso a todas las opciones";
$DATAINJECTIONLANG["choiceStep"][11] = "Debe seleccionar un modelo para usar, actualizar o borrar";

$DATAINJECTIONLANG["modelStep"][1] = "Reuniendo información sobre el archivo";
$DATAINJECTIONLANG["modelStep"][2] = "Modificación del modelo";
$DATAINJECTIONLANG["modelStep"][3] = "Seleccione el tipo de archivo a importar";
$DATAINJECTIONLANG["modelStep"][4] = "Tipo de datos a importar :";
$DATAINJECTIONLANG["modelStep"][5] = "Tipo de archivo :";
$DATAINJECTIONLANG["modelStep"][6] = "Permitir creación de elementos :";
$DATAINJECTIONLANG["modelStep"][7] = "Permitir actualización de elementos :";
$DATAINJECTIONLANG["modelStep"][8] = "Permitir creación de desplegables :";
$DATAINJECTIONLANG["modelStep"][9] = "Incluye cabecera :";
$DATAINJECTIONLANG["modelStep"][10] = "Delimitador de campos :";
$DATAINJECTIONLANG["modelStep"][11] = "Sin delimitador";
$DATAINJECTIONLANG["modelStep"][12] = "Permitir actualización de los campos existentes :";
$DATAINJECTIONLANG["modelStep"][13] = "Información principal";
$DATAINJECTIONLANG["modelStep"][14] = "Opciones del ";
$DATAINJECTIONLANG["modelStep"][15] = "Opciones avanzadas";
$DATAINJECTIONLANG["modelStep"][16] = "Difusión :";
$DATAINJECTIONLANG["modelStep"][17] = "Pública";
$DATAINJECTIONLANG["modelStep"][18] = "Privada";
$DATAINJECTIONLANG["modelStep"][19] = "Las opciones avanzadas permiten un mayor control sobre el proceso de importación pero sólo los usuarios avanzados deben usarlas.";
$DATAINJECTIONLANG["modelStep"][20] = "Intentar establecer una conexión de red si es posible";
$DATAINJECTIONLANG["modelStep"][21] = "Formato de fechas";
$DATAINJECTIONLANG["modelStep"][22] = "dd-mm-aaaa";
$DATAINJECTIONLANG["modelStep"][23] = "mm-dd-aaaa";
$DATAINJECTIONLANG["modelStep"][24] = "aaaa-mm-dd";

$DATAINJECTIONLANG["deleteStep"][1] = "Confirmar borrado";
$DATAINJECTIONLANG["deleteStep"][2] = "Atención, si borra el modelo también se borrarán los mapeos y las informaciones.";
$DATAINJECTIONLANG["deleteStep"][3] = "¿Desea borrar";
$DATAINJECTIONLANG["deleteStep"][4] = "permanentemente?";
$DATAINJECTIONLANG["deleteStep"][5] = "El modelo";
$DATAINJECTIONLANG["deleteStep"][6] = "ha sido borrado.";
$DATAINJECTIONLANG["deleteStep"][7] = "Ocurrió un problema al borrar el modelo.";

$DATAINJECTIONLANG["fileStep"][1] = "Seleccione el archivo a enviar.";
$DATAINJECTIONLANG["fileStep"][2] = "Seleccione en su disco duro el archivo a enviar al servidor.";
$DATAINJECTIONLANG["fileStep"][3] = "Seleccione un archivo :";
$DATAINJECTIONLANG["fileStep"][4] = "No se encuentra el archivo";
$DATAINJECTIONLANG["fileStep"][5] = "El formato del archivo no es correcto";
$DATAINJECTIONLANG["fileStep"][6] = "Extensión";
$DATAINJECTIONLANG["fileStep"][7] = "requerida";
$DATAINJECTIONLANG["fileStep"][8] = "Imposible copiar el archivo";
$DATAINJECTIONLANG["fileStep"][9] = "Codificación del archivo :";
$DATAINJECTIONLANG["fileStep"][10] = "Detección automática";
$DATAINJECTIONLANG["fileStep"][11] = "UTF-8";
$DATAINJECTIONLANG["fileStep"][12] = "ISO8859-1";

$DATAINJECTIONLANG["mappingStep"][1] = "Se han encontrado campos a mapear";
$DATAINJECTIONLANG["mappingStep"][2] = "Cabecera del archivo";
$DATAINJECTIONLANG["mappingStep"][3] = "Tabla";
$DATAINJECTIONLANG["mappingStep"][4] = "Columna";
$DATAINJECTIONLANG["mappingStep"][5] = "Requerido";
$DATAINJECTIONLANG["mappingStep"][6] = "----- Elija la tabla -----";
$DATAINJECTIONLANG["mappingStep"][7] = "---- Elija la columna ----";
$DATAINJECTIONLANG["mappingStep"][8] = "Debe seleccionar al menos un mapeo como requerido";
$DATAINJECTIONLANG["mappingStep"][9] = "Este paso le permite asociar los campos del archivo con las tablas de la base de datos donde se almacenarán.";
$DATAINJECTIONLANG["mappingStep"][10] = "La cabecera del archivo le permite mapear los campos con las columnas de la base de datos";
$DATAINJECTIONLANG["mappingStep"][11] = "Un mapeo requerido corresponde a un campo que debe tener valor obligatoriamente a lo largo de todo el archivo. No podá continuar sin marcar un mapeo como requerido";
$DATAINJECTIONLANG["mappingStep"][12] = "Número de línea";

$DATAINJECTIONLANG["infoStep"][1] = "Informaciones complementarias";
$DATAINJECTIONLANG["infoStep"][2] = "Modificación de las informaciones complementarias";
$DATAINJECTIONLANG["infoStep"][3] = "Este paso le permite añadir datos que no se incluyen en el archivo. Se le pedirá esa información cuando use el modelo";
$DATAINJECTIONLANG["infoStep"][4] = "Esta información es común a todos los objetos importados";
$DATAINJECTIONLANG["infoStep"][5] = "Información obligatoria";

$DATAINJECTIONLANG["saveStep"][1] = "Guardar el modelo";
$DATAINJECTIONLANG["saveStep"][2] = "¿Desea guardar el modelo?";
$DATAINJECTIONLANG["saveStep"][3] = "¿Desea actualizar el modelo?";
$DATAINJECTIONLANG["saveStep"][4] = "Nombre del modelo:";
$DATAINJECTIONLANG["saveStep"][5] = "Añadir un comentario:";
$DATAINJECTIONLANG["saveStep"][6] = "El modelo no ha sido guardado";
$DATAINJECTIONLANG["saveStep"][7] = "El modelo no ha sido actualizado, pero aún está disponible para su uso.";
$DATAINJECTIONLANG["saveStep"][8] = "El modelo ha sido guardado";
$DATAINJECTIONLANG["saveStep"][9] = "El modelo ha sido actualizado";
$DATAINJECTIONLANG["saveStep"][10] = "¿Desea utilizar el modelo?";
$DATAINJECTIONLANG["saveStep"][11] = "El número de campos del archivo es incorrecto";
$DATAINJECTIONLANG["saveStep"][12] = "Al menos un campo es incorrecto";
$DATAINJECTIONLANG["saveStep"][13] = "Este paso le permite guardar el modelo para poder usarlo con otros archivos";
$DATAINJECTIONLANG["saveStep"][14] = "Para volver a utilizarlo sólo debe seleccionarlo en la lista del primer paso";
$DATAINJECTIONLANG["saveStep"][15] = "Puede poner un comentario para añadir información sobre el modelo";
$DATAINJECTIONLANG["saveStep"][16] = " campo(s) esperados";
$DATAINJECTIONLANG["saveStep"][17] = " campo(s) encontrados";
$DATAINJECTIONLANG["saveStep"][18] = " En el archivo : ";
$DATAINJECTIONLANG["saveStep"][19] = " Del modelo : ";

$DATAINJECTIONLANG["fillInfoStep"][1] = "Atención, está a punto de importar datos al inventario. ¿Está seguro?";
$DATAINJECTIONLANG["fillInfoStep"][2] = "Rellene los datos que deben ser insertados durante la importación";
$DATAINJECTIONLANG["fillInfoStep"][3] = "* Campo obligatorio";
$DATAINJECTIONLANG["fillInfoStep"][4] = "Un campo obligatorio no está relleno";

$DATAINJECTIONLANG["importStep"][1] = "Importación del archivo";
$DATAINJECTIONLANG["importStep"][2] = "La importación del archivo puede durar varios minutos dependiendo de su configuración. Por favor, espere.";
$DATAINJECTIONLANG["importStep"][3] = "Importación finalizada";

$DATAINJECTIONLANG["logStep"][1] = "Resultados de la importación";
$DATAINJECTIONLANG["logStep"][2] = "Puede ver el informe de la importación pulsando el botón 'Ver el log'";
$DATAINJECTIONLANG["logStep"][3] = "Importación correta";
$DATAINJECTIONLANG["logStep"][4] = "Lista de inserciones correctas";
$DATAINJECTIONLANG["logStep"][5] = "Lista de inserciones incorrectas";
$DATAINJECTIONLANG["logStep"][6] = "El botón 'Exportar informe en PDF' le permite guardar un informe de la importación en su disco duro";
$DATAINJECTIONLANG["logStep"][7] = "El botón 'Exportar el log' le permite generar un archivo CSV con las líneas que fallaron en el proceso de importación";
$DATAINJECTIONLANG["logStep"][8] = "Hubo errores en la importación";

$DATAINJECTIONLANG["button"][1] = "< Anterior";
$DATAINJECTIONLANG["button"][2] = "Siguiente >";
$DATAINJECTIONLANG["button"][3] = "Ver";
$DATAINJECTIONLANG["button"][4] = "Ver el log";
$DATAINJECTIONLANG["button"][5] = "Exportar el log";
$DATAINJECTIONLANG["button"][6] = "Finalizar";
$DATAINJECTIONLANG["button"][7] = "Exportar informe en PDF";
$DATAINJECTIONLANG["button"][8] = "Cerrar";

$DATAINJECTIONLANG["result"][1] = "Un valor no es del tipo correcto";
$DATAINJECTIONLANG["result"][2] = "Datos correctos";
$DATAINJECTIONLANG["result"][3] = "Los datos están aún en la base de datos";
$DATAINJECTIONLANG["result"][4] = "Al menos un campo obligatorio no está presente";
$DATAINJECTIONLANG["result"][5] = "No tiene permiso para importar datos";
$DATAINJECTIONLANG["result"][6] = "No tiene permiso para actualizar datos";
$DATAINJECTIONLANG["result"][7] = "Importación correcta";
$DATAINJECTIONLANG["result"][8] = "Añadir";
$DATAINJECTIONLANG["result"][9] = "Actualizar";
$DATAINJECTIONLANG["result"][10] = "Comprobación";
$DATAINJECTIONLANG["result"][11] = "Importación";
$DATAINJECTIONLANG["result"][12] = "Tipo";
$DATAINJECTIONLANG["result"][13] = "ID de objeto";
$DATAINJECTIONLANG["result"][14] = "Línea";
$DATAINJECTIONLANG["result"][15] = "Data not found";
$DATAINJECTIONLANG["result"][16] = "Data already used";
$DATAINJECTIONLANG["result"][17] = "No data to insert";
$DATAINJECTIONLANG["result"][18] = "Injection summary for file";

$DATAINJECTIONLANG["profiles"][1] = "Crear modelo";
$DATAINJECTIONLANG["profiles"][3] = "Usar modelo";
$DATAINJECTIONLANG["profiles"][4] = "Lista de perfiles ya configurados";

$DATAINJECTIONLANG["mappings"][1] = "Número de puertos";
$DATAINJECTIONLANG["mappings"][2] = "Puerto de red";
$DATAINJECTIONLANG["mappings"][3] = "Connected to : device name";
$DATAINJECTIONLANG["mappings"][4] = "Connected to : port number";

$DATAINJECTIONLANG["history"][1] = "de archivo CSV";
$DATAINJECTIONLANG["logevent"][1] = "importación de un archivo CSV.";

?>
