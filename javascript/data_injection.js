function change_color_step(id) 
{
	document.getElementById(id).style.backgroundColor='#e6e6e6';
}	

function showSelect(form,cpt)
{	
	if (document.getElementById('checkbox').checked)
		{
		form.dropdown.disabled=true;
		form.dropdown.style.backgroundColor='#e6e6e6';
		hide_comments(cpt);
		}
	else
		{
		form.dropdown.disabled=false;
		form.dropdown.style.backgroundColor='white';
		show_comments(cpt);
		}
}

function deleteOnglet(nbonglet)
{	
	for(var i=1;i<=6;i++)
		{
		if(i>nbonglet)
			document.getElementById('step'+i).style.display='none';
		else
			{
			if(navigator.appName == "Netscape")
				document.getElementById('step'+i).style.display='table-cell';
			else
				document.getElementById('step'+i).style.display='block';
			}
		}
}

function show_comments(cpt)
{
sel = document.getElementById('dropdown');
id = sel.options[sel.selectedIndex].value;

for(i=0;i<cpt;i++)
	{
	id2 = sel.options[i].value;
	
	document.getElementById('comments'+id2).style.display = 'none';
	
	if(id2==id)
		document.getElementById('comments'+id).style.display = 'block';
	}
}

function hide_comments(cpt)
{
sel = document.getElementById('dropdown');
id = sel.options[sel.selectedIndex].value;

for(i=0;i<cpt;i++)
	{
	id2 = sel.options[i].value;
	document.getElementById('comments'+id2).style.display = 'none';
	}
}

function getXhr()
{	
	var xhr = null; 
	if(window.XMLHttpRequest)
		xhr = new XMLHttpRequest(); 
	else if(window.ActiveXObject){
		try {
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
			}
		catch (e) 
			{
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		}
		else { 
			alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest..."); 
			xhr = false; 
			} 
	return xhr;
}
		
function go_mapping(id)
{	
	var xhr = getXhr();

	xhr.onreadystatechange = function(){

		if(xhr.readyState == 4 && xhr.status == 200)
			{
			leselect = xhr.responseText;
			document.getElementById('field'+id).innerHTML = leselect;
			}
		}

	xhr.open("POST","../inc/plugin_data_injection.ajax.tablefieldmapping.php",true);

	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	sel = document.getElementById('table'+id);
	idtable = sel.options[sel.selectedIndex].value;
	
	if(idtable!=-1)
		document.getElementById("check"+id).disabled=false;
	else
		{
		document.getElementById("check"+id).disabled=true;
		document.getElementById("check"+id).checked=false;
		}
		
	xhr.send("id="+id+"&idMapping="+idtable);
}

function go_info(id)
{	
	var xhr = getXhr();

	xhr.onreadystatechange = function(){

		if(xhr.readyState == 4 && xhr.status == 200)
			{
			leselect = xhr.responseText;
			document.getElementById('field'+id).innerHTML = leselect;
			}
		}

	xhr.open("POST","../inc/plugin_data_injection.ajax.tablefieldinfo.php",true);

	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	sel = document.getElementById('table'+id);
	idtable = sel.options[sel.selectedIndex].value;
	
	if(idtable!=-1)
		document.getElementById("check"+id).disabled=false;
	else
		{
		document.getElementById("check"+id).disabled=true;
		document.getElementById("check"+id).checked=false;
		}
		
	xhr.send("id="+id+"&idMapping="+idtable);
}

function addelete_info(id)
{	
	sel = document.getElementById('table'+id);
	idtable = sel.options[sel.selectedIndex].value;
	
	add = document.getElementById('add'+id).value;
	
	if(add==0)
		{
		document.getElementById('add'+id).value = 1;
		
		next = id+1;
		
		var xhr = getXhr();
		
		xhr.onreadystatechange = function(){
	
			if(xhr.readyState == 4 && xhr.status == 200)
				{
				leselect = xhr.responseText;
				document.getElementById('select'+next).innerHTML = leselect;
				}
			}
	
		xhr.open("POST","../inc/plugin_data_injection.ajax.selectfield.php",true);
	
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		
		xhr.send("id="+next);
		}
	if(add==1 && idtable==-1)
		{
			document.getElementById('tab'+id).style.display = 'none';
		}
}

function file_popup(totalline)
{
	var nbline = document.getElementById('nbline').value;
	
	if(nbline<1)
		{
		nbline=1;
		document.getElementById('nbline').value = 1;
		}
	
	if(nbline > totalline)
		nbline = totalline;
	
	var x = screen.width;
	var y = screen.height;
	
	height = (nbline * 40) + 100;
	
	x = (x - 970)/2;
	y = (y - nbline)/2;
	
	window.open('../inc/plugin_data_injection.popup.file.php?nbline='+nbline+'', 'Popup', 'resizable=no, location=no, menubar=no, scrollbars=yes, toolbar=no, status=no, width=970, height='+height+', left='+x+', top='+y+'');
	
	document.getElementById('popup').submit();
}

function log_popup(nbline)
{
	var x = screen.width;
	var y = screen.height;
	
	height = (nbline * 40) + 100;
	
	x = (x - 970)/2;
	y = (y - nbline)/2;
	
	window.open('../inc/plugin_data_injection.popup.log.php', 'Popup', 'resizable=no, location=no, menubar=no, scrollbars=yes, toolbar=no, status=no, width=970, height='+height+', left='+x+', top='+y+'');
}

function verif_delimiter()
{	
	if(document.getElementById('delimiter').value == "")
		{
		document.getElementById('delimiter_error').style.visibility='visible';
		return false;
		}
	else
		return true;
}

function verif_mandatory(cpt)
{	
	var ok=0;
	
	for(var i=0;i<cpt;i++)
		if(document.getElementById("check"+i).checked == true)
			ok=1;
			
	if(ok)
		return true;
	else
		{
		document.getElementById('mandatory_error').style.visibility='visible';
		return false;
		}
}

function change_progress(pourcentage)
{
	document.getElementById('importStep_progress').style.width = pourcentage;
	document.getElementById('importStep_pourcentage').innerHTML = pourcentage;
}

function new_import()
{	
	var xhr = getXhr();
	
	alert('horrible');
	
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200)
			{
			leselect = xhr.responseText;
			document.getElementById('new_import').innerHTML = leselect;
			}
		}

	xhr.open("POST","../inc/plugin_data_injection.ajax.progress.php",true);

	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		
	xhr.send(null);
}