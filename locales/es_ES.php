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

$LANG["data_injection"]["name"][1] = "Importar archivo";
$title = $LANG["data_injection"]["name"][1] ;

$LANG["data_injection"]["config"][1]= "Configuración de " . $title;

$LANG["data_injection"]["setup"][1] = "Configuración de " . $title;
$LANG["data_injection"]["setup"][3] = "Instalar el plugin $title";
$LANG["data_injection"]["setup"][5] = "Desinstalar el plugin $title";
$LANG["data_injection"]["setup"][9] = "Ajuste de permisos";
$LANG["data_injection"]["setup"][10] = "Se requiere PHP 5 o superior para usar este plugin";
$LANG["data_injection"]["setup"][11] = "Instrucciones";

$LANG["data_injection"]["presentation"][1] = "Bienvenido al ayudante de " . $title;
$LANG["data_injection"]["presentation"][2] = "Este plugin le permitirá importar archivos en formato CSV";
$LANG["data_injection"]["presentation"][3] = "Para empezar, pulse el botón Siguiente";

$LANG["data_injection"]["step"][1] = "Paso 1 : ";
$LANG["data_injection"]["step"][2] = "Paso 2 : ";
$LANG["data_injection"]["step"][3] = "Paso 3 : ";
$LANG["data_injection"]["step"][4] = "Paso 4 : ";
$LANG["data_injection"]["step"][5] = "Paso 5 : ";
$LANG["data_injection"]["step"][6] = "Paso 6 : ";

$LANG["data_injection"]["choiceStep"][1] = "Uso o manejo del modelo";
$LANG["data_injection"]["choiceStep"][2] = "Este primer paso permite crear, modificar, borrar o usar un modelo";
$LANG["data_injection"]["choiceStep"][3] = "Crear un nuevo modelo";
$LANG["data_injection"]["choiceStep"][4] = "Modificar un modelo existente";
$LANG["data_injection"]["choiceStep"][5] = "Borrar un modelo existente";
$LANG["data_injection"]["choiceStep"][6] = "Usar un modelo existente";
$LANG["data_injection"]["choiceStep"][7] = "Comentarios del modelo";
$LANG["data_injection"]["choiceStep"][8] = "Sin comentarios";
$LANG["data_injection"]["choiceStep"][9] = "Elección";
$LANG["data_injection"]["choiceStep"][10] = "Dependiendo de los permisos, puede que no tenga acceso a todas las opciones";
$LANG["data_injection"]["choiceStep"][11] = "Debe seleccionar un modelo para usar, actualizar o borrar";

$LANG["data_injection"]["modelStep"][1] = "Reuniendo información sobre el archivo";
$LANG["data_injection"]["modelStep"][2] = "Modificación del modelo";
$LANG["data_injection"]["modelStep"][3] = "Seleccione el tipo de archivo a importar";
$LANG["data_injection"]["modelStep"][4] = "Tipo de datos a importar :";
$LANG["data_injection"]["modelStep"][5] = "Tipo de archivo :";
$LANG["data_injection"]["modelStep"][6] = "Permitir creación de elementos :";
$LANG["data_injection"]["modelStep"][7] = "Permitir actualización de elementos :";
$LANG["data_injection"]["modelStep"][8] = "Permitir creación de desplegables :";
$LANG["data_injection"]["modelStep"][9] = "Incluye cabecera :";
$LANG["data_injection"]["modelStep"][10] = "Delimitador de campos :";
$LANG["data_injection"]["modelStep"][11] = "Sin delimitador";
$LANG["data_injection"]["modelStep"][12] = "Permitir actualización de los campos existentes :";
$LANG["data_injection"]["modelStep"][13] = "Información principal";
$LANG["data_injection"]["modelStep"][14] = "Opciones del ";
$LANG["data_injection"]["modelStep"][15] = "Opciones avanzadas";
$LANG["data_injection"]["modelStep"][16] = "Difusión :";
$LANG["data_injection"]["modelStep"][17] = "Pública";
$LANG["data_injection"]["modelStep"][18] = "Privada";
$LANG["data_injection"]["modelStep"][19] = "Las opciones avanzadas permiten un mayor control sobre el proceso de importación pero sólo los usuarios avanzados deben usarlas.";
$LANG["data_injection"]["modelStep"][20] = "Intentar establecer una conexión de red si es posible";
$LANG["data_injection"]["modelStep"][21] = "Formato de fechas";
$LANG["data_injection"]["modelStep"][22] = "dd-mm-aaaa";
$LANG["data_injection"]["modelStep"][23] = "mm-dd-aaaa";
$LANG["data_injection"]["modelStep"][24] = "aaaa-mm-dd";

$LANG["data_injection"]["deleteStep"][1] = "Confirmar borrado";
$LANG["data_injection"]["deleteStep"][2] = "Atención, si borra el modelo también se borrarán los mapeos y las informaciones.";
$LANG["data_injection"]["deleteStep"][3] = "¿Desea borrar";
$LANG["data_injection"]["deleteStep"][4] = "permanentemente?";
$LANG["data_injection"]["deleteStep"][5] = "El modelo";
$LANG["data_injection"]["deleteStep"][6] = "ha sido borrado.";
$LANG["data_injection"]["deleteStep"][7] = "Ocurrió un problema al borrar el modelo.";

$LANG["data_injection"]["fileStep"][1] = "Seleccione el archivo a enviar.";
$LANG["data_injection"]["fileStep"][2] = "Seleccione en su disco duro el archivo a enviar al servidor.";
$LANG["data_injection"]["fileStep"][3] = "Seleccione un archivo :";
$LANG["data_injection"]["fileStep"][4] = "No se encuentra el archivo";
$LANG["data_injection"]["fileStep"][5] = "El formato del archivo no es correcto";
$LANG["data_injection"]["fileStep"][6] = "Extensión";
$LANG["data_injection"]["fileStep"][7] = "requerida";
$LANG["data_injection"]["fileStep"][8] = "Imposible copiar el archivo";
$LANG["data_injection"]["fileStep"][9] = "Codificación del archivo :";
$LANG["data_injection"]["fileStep"][10] = "Detección automática";
$LANG["data_injection"]["fileStep"][11] = "UTF-8";
$LANG["data_injection"]["fileStep"][12] = "ISO8859-1";

$LANG["data_injection"]["mappingStep"][1] = "Se han encontrado campos a mapear";
$LANG["data_injection"]["mappingStep"][2] = "Cabecera del archivo";
$LANG["data_injection"]["mappingStep"][3] = "Tabla";
$LANG["data_injection"]["mappingStep"][4] = "Columna";
$LANG["data_injection"]["mappingStep"][5] = "Requerido";
$LANG["data_injection"]["mappingStep"][6] = "----- Elija la tabla -----";
$LANG["data_injection"]["mappingStep"][7] = "---- Elija la columna ----";
$LANG["data_injection"]["mappingStep"][8] = "Debe seleccionar al menos un mapeo como requerido";
$LANG["data_injection"]["mappingStep"][9] = "Este paso le permite asociar los campos del archivo con las tablas de la base de datos donde se almacenarán.";
$LANG["data_injection"]["mappingStep"][10] = "La cabecera del archivo le permite mapear los campos con las columnas de la base de datos";
$LANG["data_injection"]["mappingStep"][11] = "Un mapeo requerido corresponde a un campo que debe tener valor obligatoriamente a lo largo de todo el archivo. No podá continuar sin marcar un mapeo como requerido";
$LANG["data_injection"]["mappingStep"][12] = "Número de línea";

$LANG["data_injection"]["infoStep"][1] = "Informaciones complementarias";
$LANG["data_injection"]["infoStep"][2] = "Modificación de las informaciones complementarias";
$LANG["data_injection"]["infoStep"][3] = "Este paso le permite añadir datos que no se incluyen en el archivo. Se le pedirá esa información cuando use el modelo";
$LANG["data_injection"]["infoStep"][4] = "Esta información es común a todos los objetos importados";
$LANG["data_injection"]["infoStep"][5] = "Información obligatoria";

$LANG["data_injection"]["saveStep"][1] = "Guardar el modelo";
$LANG["data_injection"]["saveStep"][2] = "¿Desea guardar el modelo?";
$LANG["data_injection"]["saveStep"][3] = "¿Desea actualizar el modelo?";
$LANG["data_injection"]["saveStep"][4] = "Nombre del modelo:";
$LANG["data_injection"]["saveStep"][5] = "Añadir un comentario:";
$LANG["data_injection"]["saveStep"][6] = "El modelo no ha sido guardado";
$LANG["data_injection"]["saveStep"][7] = "El modelo no ha sido actualizado, pero aún está disponible para su uso.";
$LANG["data_injection"]["saveStep"][8] = "El modelo ha sido guardado";
$LANG["data_injection"]["saveStep"][9] = "El modelo ha sido actualizado";
$LANG["data_injection"]["saveStep"][10] = "¿Desea utilizar el modelo?";
$LANG["data_injection"]["saveStep"][11] = "El número de campos del archivo es incorrecto";
$LANG["data_injection"]["saveStep"][12] = "Al menos un campo es incorrecto";
$LANG["data_injection"]["saveStep"][13] = "Este paso le permite guardar el modelo para poder usarlo con otros archivos";
$LANG["data_injection"]["saveStep"][14] = "Para volver a utilizarlo sólo debe seleccionarlo en la lista del primer paso";
$LANG["data_injection"]["saveStep"][15] = "Puede poner un comentario para añadir información sobre el modelo";
$LANG["data_injection"]["saveStep"][16] = " campo(s) esperados";
$LANG["data_injection"]["saveStep"][17] = " campo(s) encontrados";
$LANG["data_injection"]["saveStep"][18] = " En el archivo : ";
$LANG["data_injection"]["saveStep"][19] = " Del modelo : ";

$LANG["data_injection"]["fillInfoStep"][1] = "Atención, está a punto de importar datos al inventario. ¿Está seguro?";
$LANG["data_injection"]["fillInfoStep"][2] = "Rellene los datos que deben ser insertados durante la importación";
$LANG["data_injection"]["fillInfoStep"][3] = "* Campo obligatorio";
$LANG["data_injection"]["fillInfoStep"][4] = "Un campo obligatorio no está relleno";

$LANG["data_injection"]["importStep"][1] = "Importación del archivo";
$LANG["data_injection"]["importStep"][2] = "La importación del archivo puede durar varios minutos dependiendo de su configuración. Por favor, espere.";
$LANG["data_injection"]["importStep"][3] = "Importación finalizada";

$LANG["data_injection"]["logStep"][1] = "Resultados de la importación";
$LANG["data_injection"]["logStep"][2] = "Puede ver el informe de la importación pulsando el botón 'Ver el log'";
$LANG["data_injection"]["logStep"][3] = "Importación correta";
$LANG["data_injection"]["logStep"][4] = "Lista de inserciones correctas";
$LANG["data_injection"]["logStep"][5] = "Lista de inserciones incorrectas";
$LANG["data_injection"]["logStep"][6] = "El botón 'Exportar informe en PDF' le permite guardar un informe de la importación en su disco duro";
$LANG["data_injection"]["logStep"][7] = "El botón 'Exportar el log' le permite generar un archivo CSV con las líneas que fallaron en el proceso de importación";
$LANG["data_injection"]["logStep"][8] = "Hubo errores en la importación";

$LANG["data_injection"]["button"][1] = "< Anterior";
$LANG["data_injection"]["button"][2] = "Siguiente >";
$LANG["data_injection"]["button"][3] = "Ver";
$LANG["data_injection"]["button"][4] = "Ver el log";
$LANG["data_injection"]["button"][5] = "Exportar el log";
$LANG["data_injection"]["button"][6] = "Finalizar";
$LANG["data_injection"]["button"][7] = "Exportar informe en PDF";
$LANG["data_injection"]["button"][8] = "Cerrar";

$LANG["data_injection"]["result"][1] = "Un valor no es del tipo correcto";
$LANG["data_injection"]["result"][2] = "Datos correctos";
$LANG["data_injection"]["result"][3] = "Los datos están aún en la base de datos";
$LANG["data_injection"]["result"][4] = "Al menos un campo obligatorio no está presente";
$LANG["data_injection"]["result"][5] = "No tiene permiso para importar datos";
$LANG["data_injection"]["result"][6] = "No tiene permiso para actualizar datos";
$LANG["data_injection"]["result"][7] = "Importación correcta";
$LANG["data_injection"]["result"][8] = "Añadir";
$LANG["data_injection"]["result"][9] = "Actualizar";
$LANG["data_injection"]["result"][10] = "Comprobación";
$LANG["data_injection"]["result"][11] = "Importación";
$LANG["data_injection"]["result"][12] = "Tipo";
$LANG["data_injection"]["result"][13] = "ID de objeto";
$LANG["data_injection"]["result"][14] = "Línea";
$LANG["data_injection"]["result"][15] = "Data not found";
$LANG["data_injection"]["result"][16] = "Data already used";
$LANG["data_injection"]["result"][17] = "No data to insert";
$LANG["data_injection"]["result"][18] = "Injection summary for file";

$LANG["data_injection"]["profiles"][1] = "Crear modelo";
$LANG["data_injection"]["profiles"][3] = "Usar modelo";
$LANG["data_injection"]["profiles"][4] = "Lista de perfiles ya configurados";

$LANG["data_injection"]["mappings"][1] = "Número de puertos";
$LANG["data_injection"]["mappings"][2] = "Puerto de red";
$LANG["data_injection"]["mappings"][3] = "Connected to : device name";
$LANG["data_injection"]["mappings"][4] = "Connected to : port number";
$LANG["data_injection"]["mappings"][5] = "Computer";

$LANG["data_injection"]["history"][1] = "de archivo CSV";
$LANG["data_injection"]["logevent"][1] = "importación de un archivo CSV.";

$LANG["data_injection"]["entity"][0] = "Parent entity";
?>
