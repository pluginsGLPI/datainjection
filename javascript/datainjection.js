function show_comments(cpt) {

   sel = document.getElementById('dropdown');
   id  = sel.selectedIndex;

   for (i=0 ; i<cpt ; i++) {
      document.getElementById('comments'+i).style.display = 'none';

      if (i == id) {
         document.getElementById('comments'+i).style.display = 'block';
      }
   }
}


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


function log_popup(nbline) {
   var x = screen.width;
   var y = screen.height;

   height = (nbline * 30) + 210;

   x = (x - 1010)/2;
   y = (y - nbline)/2;

   window.open('../front/plugin_datainjection.popup.log.php', 'Popup', 'resizable=no, location=no, menubar=no, scrollbars=yes, toolbar=no, status=no, width=1010, height='+height+', left='+x+', top='+y+'');
}


function show_log(num) {

   if (document.getElementById('log'+num+'_table').style.display == 'none') {
      document.getElementById('log'+num).src = '../pics/minus.png';
      document.getElementById('log'+num+'_table').style.display = 'block';
   } else {
      document.getElementById('log'+num).src = '../pics/plus.png';
      document.getElementById('log'+num+'_table').style.display = 'none';
   }
}


function show_option() {

   if (document.getElementById('option').style.display == 'none') {
      document.getElementById('option_img').src = '../pics/minus.png';
      document.getElementById('option').style.display = 'block';
   } else {
      document.getElementById('option_img').src = '../pics/plus.png';
      document.getElementById('option').style.display = 'none';
   }
}


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
