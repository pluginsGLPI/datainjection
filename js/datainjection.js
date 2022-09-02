/**
 * -------------------------------------------------------------------------
 * DataInjection plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of DataInjection.
 *
 * DataInjection is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * DataInjection is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DataInjection. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2007-2022 by DataInjection plugin team.
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/pluginsGLPI/datainjection
 * -------------------------------------------------------------------------
 */

/**
 *
 * @param cpt
 * @deprecated No longer used
 */
function show_comments(cpt) {

   sel = document.getElementById('dropdown');
   id  = sel.selectedIndex;

   for (i=0; i<cpt; i++) {
      document.getElementById('comments'+i).style.display = 'none';

      if (i == id) {
         document.getElementById('comments'+i).style.display = 'block';
      }
   }
}

/**
 *
 * @param totalline
 * @deprecated No longer used
 */
function file_popup(totalline) {
   var nbline = document.getElementById('nbline').value;

   if (nbline < 1) {
      nbline = 1;
      document.getElementById('nbline').value=1;
   }

   if (nbline > totalline) {
      nbline = totalline;
   }

   var x = screen.width;
   var y = screen.height;

   height = (nbline * 40) + 100;

   x = (x - 970)/2;
   y = (y - nbline)/2;

   window.open('../front/plugin_datainjection.popup.file.php?nbline='+nbline+'', 'Popup', 'resizable=no, location=no, menubar=no, scrollbars=yes, toolbar=no, status=no, width=970, height='+height+', left='+x+', top='+y+'');
}

/**
 *
 * @param nbline
 * @deprecated No longer used
 */
function log_popup(nbline) {
   var x = screen.width;
   var y = screen.height;

   height = (nbline * 30) + 210;

   x = (x - 1010)/2;
   y = (y - nbline)/2;

   window.open('../front/plugin_datainjection.popup.log.php', 'Popup', 'resizable=no, location=no, menubar=no, scrollbars=yes, toolbar=no, status=no, width=1010, height='+height+', left='+x+', top='+y+'');
}

/**
 *
 * @param num
 */
function show_log(num) {

   if (document.getElementById('log'+num+'_table').style.display == 'none') {
      document.getElementById('log'+num).src = '../pics/minus.png';
      document.getElementById('log'+num+'_table').style.display = 'block';
   } else {
      document.getElementById('log'+num).src = '../pics/plus.png';
      document.getElementById('log'+num+'_table').style.display = 'none';
   }
}

/**
 * @deprecated No longer used
 */
function show_option() {

   if (document.getElementById('option').style.display == 'none') {
      document.getElementById('option_img').src = '../pics/minus.png';
      document.getElementById('option').style.display = 'block';
   } else {
      document.getElementById('option_img').src = '../pics/plus.png';
      document.getElementById('option').style.display = 'none';
   }
}

/**
 *
 * @param id
 * @param type
 * @deprecated No longer used
 */
function go_mapping(id,type) {
   var xhr = getXhr();

   xhr.onreadystatechange = function(){

      if (xhr.readyState == 4 && xhr.status == 200) {
         leselect = xhr.responseText;
         document.getElementById('field'+id).innerHTML = leselect;
      }
   }

   xhr.open("POST","../inc/plugin_datainjection.ajax.tablefieldmapping.php",true);

   xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

   sel = document.getElementById('table'+id);
   idtable = sel.options[sel.selectedIndex].value;

   nom = document.getElementById('name'+id).value;

   if (sel.value==type) {
      document.getElementById("check"+id).disabled=false;
   } else {
      document.getElementById("check"+id).disabled=true;
      document.getElementById("check"+id).checked=false;
   }

   xhr.send("id="+id+"&idMapping="+idtable+"&name="+nom);
}

/**
 *
 * @param id
 * @deprecated No longer used
 */
function go_info(id) {
   var xhr = getXhr();

   xhr.onreadystatechange = function(){

      if (xhr.readyState == 4 && xhr.status == 200) {
         leselect = xhr.responseText;
         document.getElementById('field'+id).innerHTML = leselect;
      }
   }

   xhr.open("POST","../inc/plugin_datainjection.ajax.tablefieldinfo.php",true);

   xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

   sel = document.getElementById('table'+id);
   idtable = sel.options[sel.selectedIndex].value;

   if (idtable != -1) {
      document.getElementById("check"+id).disabled=false;
   } else {
      document.getElementById("check"+id).disabled=true;
      document.getElementById("check"+id).checked=false;
   }

   xhr.send("id="+id+"&idMapping="+idtable);
}
