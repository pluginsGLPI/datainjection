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

$LANG["datainjection"]["name"][1] = "Importar archivo";
$title = $LANG["datainjection"]["name"][1] ;

$LANG["datainjection"]["config"][1]= "Configuración de " . $title;

$LANG["datainjection"]["setup"][1] = "Configuración de " . $title;
$LANG["datainjection"]["setup"][3] = "Instalar el plugin $title";
$LANG["datainjection"]["setup"][5] = "Desinstalar el plugin $title";
$LANG["datainjection"]["setup"][9] = "Ajuste de permisos";
$LANG["datainjection"]["setup"][10] = "Se requiere PHP 5 o superior para usar este plugin";
$LANG["datainjection"]["setup"][11] = "Instrucciones";

$LANG["datainjection"]["presentation"][1] = "Bienvenido al ayudante de " . $title;
$LANG["datainjection"]["presentation"][2] = "Este plugin le permitirá importar archivos en formato CSV";
$LANG["datainjection"]["presentation"][3] = "Para empezar, pulse el botón Siguiente";

$LANG["datainjection"]["step"][1] = "Paso 1 : ";
$LANG["datainjection"]["step"][2] = "Paso 2 : ";
$LANG["datainjection"]["step"][3] = "Paso 3 : ";
$LANG["datainjection"]["step"][4] = "Paso 4 : ";
$LANG["datainjection"]["step"][5] = "Paso 5 : ";
$LANG["datainjection"]["step"][6] = "Paso 6 : ";

$LANG["datainjection"]["choiceStep"][1] = "Uso o manejo del modelo";
$LANG["datainjection"]["choiceStep"][2] = "Este primer paso permite crear, modificar, borrar o usar un modelo";
$LANG["datainjection"]["choiceStep"][3] = "Crear un nuevo modelo";
$LANG["datainjection"]["choiceStep"][4] = "Modificar un modelo existente";
$LANG["datainjection"]["choiceStep"][5] = "Borrar un modelo existente";
$LANG["datainjection"]["choiceStep"][6] = "Usar un modelo existente";
$LANG["datainjection"]["choiceStep"][7] = "Comentarios del modelo";
$LANG["datainjection"]["choiceStep"][8] = "Sin comentarios";
$LANG["datainjection"]["choiceStep"][9] = "Elección";
$LANG["datainjection"]["choiceStep"][10] = "Dependiendo de los permisos, puede que no tenga acceso a todas las opciones";
$LANG["datainjection"]["choiceStep"][11] = "Debe seleccionar un modelo para usar, actualizar o borrar";

$LANG["datainjection"]["modelStep"][1] = "Reuniendo información sobre el archivo";
$LANG["datainjection"]["modelStep"][2] = "Modificación del modelo";
$LANG["datainjection"]["modelStep"][3] = "Seleccione el tipo de archivo a importar";
$LANG["datainjection"]["modelStep"][4] = "Tipo de datos a importar :";
$LANG["datainjection"]["modelStep"][5] = "Tipo de archivo :";
$LANG["datainjection"]["modelStep"][6] = "Permitir creación de elementos :";
$LANG["datainjection"]["modelStep"][7] = "Permitir actualización de elementos :";
$LANG["datainjection"]["modelStep"][8] = "Permitir creación de desplegables :";
$LANG["datainjection"]["modelStep"][9] = "Incluye cabecera :";
$LANG["datainjection"]["modelStep"][10] = "Delimitador de campos :";
$LANG["datainjection"]["modelStep"][11] = "Sin delimitador";
$LANG["datainjection"]["modelStep"][12] = "Permitir actualización de los campos existentes :";
$LANG["datainjection"]["modelStep"][13] = "Información principal";
$LANG["datainjection"]["modelStep"][14] = "Opciones del ";
$LANG["datainjection"]["modelStep"][15] = "Opciones avanzadas";
$LANG["datainjection"]["modelStep"][16] = "Difusión :";
$LANG["datainjection"]["modelStep"][17] = "Pública";
$LANG["datainjection"]["modelStep"][18] = "Privada";
$LANG["datainjection"]["modelStep"][19] = "Las opciones avanzadas permiten un mayor control sobre el proceso de importación pero sólo los usuarios avanzados deben usarlas.";
$LANG["datainjection"]["modelStep"][20] = "Intentar establecer una conexión de red si es posible";
$LANG["datainjection"]["modelStep"][21] = "Formato de fechas";
$LANG["datainjection"]["modelStep"][22] = "dd-mm-aaaa";
$LANG["datainjection"]["modelStep"][23] = "mm-dd-aaaa";
$LANG["datainjection"]["modelStep"][24] = "aaaa-mm-dd";

$LANG["datainjection"]["deleteStep"][1] = "Confirmar borrado";
$LANG["datainjection"]["deleteStep"][2] = "Atención, si borra el modelo también se borrarán los mapeos y las informaciones.";
$LANG["datainjection"]["deleteStep"][3] = "¿Desea borrar";
$LANG["datainjection"]["deleteStep"][4] = "permanentemente?";
$LANG["datainjection"]["deleteStep"][5] = "El modelo";
$LANG["datainjection"]["deleteStep"][6] = "ha sido borrado.";
$LANG["datainjection"]["deleteStep"][7] = "Ocurrió un problema al borrar el modelo.";

$LANG["datainjection"]["fileStep"][1] = "Seleccione el archivo a enviar.";
$LANG["datainjection"]["fileStep"][2] = "Seleccione en su disco duro el archivo a enviar al servidor.";
$LANG["datainjection"]["fileStep"][3] = "Seleccione un archivo :";
$LANG["datainjection"]["fileStep"][4] = "No se encuentra el archivo";
$LANG["datainjection"]["fileStep"][5] = "El formato del archivo no es correcto";
$LANG["datainjection"]["fileStep"][6] = "Extensión";
$LANG["datainjection"]["fileStep"][7] = "requerida";
$LANG["datainjection"]["fileStep"][8] = "Imposible copiar el archivo";
$LANG["datainjection"]["fileStep"][9] = "Codificación del archivo :";
$LANG["datainjection"]["fileStep"][10] = "Detección automática";
$LANG["datainjection"]["fileStep"][11] = "UTF-8";
$LANG["datainjection"]["fileStep"][12] = "ISO8859-1";

$LANG["datainjection"]["mappingStep"][1] = "Se han encontrado campos a mapear";
$LANG["datainjection"]["mappingStep"][2] = "Cabecera del archivo";
$LANG["datainjection"]["mappingStep"][3] = "Tabla";
$LANG["datainjection"]["mappingStep"][4] = "Columna";
$LANG["datainjection"]["mappingStep"][5] = "Requerido";
$LANG["datainjection"]["mappingStep"][6] = "----- Elija la tabla -----";
$LANG["datainjection"]["mappingStep"][7] = "---- Elija la columna ----";
$LANG["datainjection"]["mappingStep"][8] = "Debe seleccionar al menos un mapeo como requerido";
$LANG["datainjection"]["mappingStep"][9] = "Este paso le permite asociar los campos del archivo con las tablas de la base de datos donde se almacenarán.";
$LANG["datainjection"]["mappingStep"][10] = "La cabecera del archivo le permite mapear los campos con las columnas de la base de datos";
$LANG["datainjection"]["mappingStep"][11] = "Un mapeo requerido corresponde a un campo que debe tener valor obligatoriamente a lo largo de todo el archivo. No podá continuar sin marcar un mapeo como requerido";
$LANG["datainjection"]["mappingStep"][12] = "Número de línea";

$LANG["datainjection"]["infoStep"][1] = "Informaciones complementarias";
$LANG["datainjection"]["infoStep"][2] = "Modificación de las informaciones complementarias";
$LANG["datainjection"]["infoStep"][3] = "Este paso le permite añadir datos que no se incluyen en el archivo. Se le pedirá esa información cuando use el modelo";
$LANG["datainjection"]["infoStep"][4] = "Esta información es común a todos los objetos importados";
$LANG["datainjection"]["infoStep"][5] = "Información obligatoria";

$LANG["datainjection"]["saveStep"][1] = "Guardar el modelo";
$LANG["datainjection"]["saveStep"][2] = "¿Desea guardar el modelo?";
$LANG["datainjection"]["saveStep"][3] = "¿Desea actualizar el modelo?";
$LANG["datainjection"]["saveStep"][4] = "Nombre del modelo:";
$LANG["datainjection"]["saveStep"][5] = "Añadir un comentario:";
$LANG["datainjection"]["saveStep"][6] = "El modelo no ha sido guardado";
$LANG["datainjection"]["saveStep"][7] = "El modelo no ha sido actualizado, pero aún está disponible para su uso.";
$LANG["datainjection"]["saveStep"][8] = "El modelo ha sido guardado";
$LANG["datainjection"]["saveStep"][9] = "El modelo ha sido actualizado";
$LANG["datainjection"]["saveStep"][10] = "¿Desea utilizar el modelo?";
$LANG["datainjection"]["saveStep"][11] = "El número de campos del archivo es incorrecto";
$LANG["datainjection"]["saveStep"][12] = "Al menos un campo es incorrecto";
$LANG["datainjection"]["saveStep"][13] = "Este paso le permite guardar el modelo para poder usarlo con otros archivos";
$LANG["datainjection"]["saveStep"][14] = "Para volver a utilizarlo sólo debe seleccionarlo en la lista del primer paso";
$LANG["datainjection"]["saveStep"][15] = "Puede poner un comentario para añadir información sobre el modelo";
$LANG["datainjection"]["saveStep"][16] = " campo(s) esperados";
$LANG["datainjection"]["saveStep"][17] = " campo(s) encontrados";
$LANG["datainjection"]["saveStep"][18] = " En el archivo : ";
$LANG["datainjection"]["saveStep"][19] = " Del modelo : ";

$LANG["datainjection"]["fillInfoStep"][1] = "Atención, está a punto de importar datos al inventario. ¿Está seguro?";
$LANG["datainjection"]["fillInfoStep"][2] = "Rellene los datos que deben ser insertados durante la importación";
$LANG["datainjection"]["fillInfoStep"][3] = "* Campo obligatorio";
$LANG["datainjection"]["fillInfoStep"][4] = "Un campo obligatorio no está relleno";

$LANG["datainjection"]["importStep"][1] = "Importación del archivo";
$LANG["datainjection"]["importStep"][2] = "La importación del archivo puede durar varios minutos dependiendo de su configuración. Por favor, espere.";
$LANG["datainjection"]["importStep"][3] = "Importación finalizada";

$LANG["datainjection"]["logStep"][1] = "Resultados de la importación";
$LANG["datainjection"]["logStep"][2] = "Puede ver el informe de la importación pulsando el botón 'Ver el log'";
$LANG["datainjection"]["logStep"][3] = "Importación correta";
$LANG["datainjection"]["logStep"][4] = "Lista de inserciones correctas";
$LANG["datainjection"]["logStep"][5] = "Lista de inserciones incorrectas";
$LANG["datainjection"]["logStep"][6] = "El botón 'Exportar informe en PDF' le permite guardar un informe de la importación en su disco duro";
$LANG["datainjection"]["logStep"][7] = "El botón 'Exportar el log' le permite generar un archivo CSV con las líneas que fallaron en el proceso de importación";
$LANG["datainjection"]["logStep"][8] = "Hubo errores en la importación";

$LANG["datainjection"]["button"][1] = "< Anterior";
$LANG["datainjection"]["button"][2] = "Siguiente >";
$LANG["datainjection"]["button"][3] = "Ver";
$LANG["datainjection"]["button"][4] = "Ver el log";
$LANG["datainjection"]["button"][5] = "Exportar el log";
$LANG["datainjection"]["button"][6] = "Finalizar";
$LANG["datainjection"]["button"][7] = "Exportar informe en PDF";
$LANG["datainjection"]["button"][8] = "Cerrar";

$LANG["datainjection"]["result"][1] = "Un valor no es del tipo correcto";
$LANG["datainjection"]["result"][2] = "Datos correctos";
$LANG["datainjection"]["result"][3] = "Los datos están aún en la base de datos";
$LANG["datainjection"]["result"][4] = "Al menos un campo obligatorio no está presente";
$LANG["datainjection"]["result"][5] = "No tiene permiso para importar datos";
$LANG["datainjection"]["result"][6] = "No tiene permiso para actualizar datos";
$LANG["datainjection"]["result"][7] = "Importación correcta";
$LANG["datainjection"]["result"][8] = "Añadir";
$LANG["datainjection"]["result"][9] = "Actualizar";
$LANG["datainjection"]["result"][10] = "Comprobación";
$LANG["datainjection"]["result"][11] = "Importación";
$LANG["datainjection"]["result"][12] = "Tipo";
$LANG["datainjection"]["result"][13] = "ID de objeto";
$LANG["datainjection"]["result"][14] = "Línea";
$LANG["datainjection"]["result"][15] = "Data not found";
$LANG["datainjection"]["result"][16] = "Data already used";
$LANG["datainjection"]["result"][17] = "No data to insert";
$LANG["datainjection"]["result"][18] = "Injection summary for file";

$LANG["datainjection"]["profiles"][1] = "Crear modelo";
$LANG["datainjection"]["profiles"][3] = "Usar modelo";
$LANG["datainjection"]["profiles"][4] = "Lista de perfiles ya configurados";

$LANG["datainjection"]["mappings"][1] = "Número de puertos";
$LANG["datainjection"]["mappings"][2] = "Puerto de red";
$LANG["datainjection"]["mappings"][3] = "Connected to : device name";
$LANG["datainjection"]["mappings"][4] = "Connected to : port number";
$LANG["datainjection"]["mappings"][5] = "Computer";

$LANG["datainjection"]["history"][1] = "de archivo CSV";
$LANG["datainjection"]["logevent"][1] = "importación de un archivo CSV.";

$LANG["datainjection"]["entity"][0] = "Parent entity";
?>
