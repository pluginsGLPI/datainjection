function change_color_step(id) 
{
	document.getElementById(id).style.backgroundColor='#e6e6e6';
}	

function show_Select(cpt)
{	
			var xhr = getXhr();
		
			xhr.onreadystatechange = function(){
		
				if(xhr.readyState == 4 && xhr.status == 200)
					{
					leselect = xhr.responseText;
					document.getElementById('choice4_div').innerHTML = leselect;
					}
			}


	if (document.getElementById('create').checked)
		{
		document.getElementById('dropdown').disabled=true;
		document.getElementById('dropdown').style.backgroundColor='#e6e6e6';
		document.getElementById('comment_select').style.display='none';
		}
	else
		{
		document.getElementById('dropdown').disabled=false;
		document.getElementById('dropdown').style.backgroundColor='white';
		document.getElementById('comment_select').style.display='block';
		show_comments(cpt);

		xhr.open("POST","../inc/plugin_data_injection.ajax.selectmodel.php",true);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		
		if (document.getElementById('choice4').checked)
			xhr.send("action=read");
		else
		{
			if (document.getElementById('choice3').checked || document.getElementById('choice2').checked)
				xhr.send("action=write");
		}
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
id = sel.selectedIndex;

for(i=0;i<cpt;i++)
	{
	document.getElementById('comments'+i).style.display = 'none';
	
	if(i==id)
		document.getElementById('comments'+i).style.display = 'block';
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
		
function go_mapping(id,type)
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

   nom = document.getElementById('name'+id).value;
	
	if (sel.value==type)
		document.getElementById("check"+id).disabled=false;
	else
		{
		document.getElementById("check"+id).disabled=true;
		document.getElementById("check"+id).checked=false;
		}
		
	xhr.send("id="+id+"&idMapping="+idtable+"&name="+nom);
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

function show_backend(choice)
{	
	var xhr = getXhr();

	xhr.onreadystatechange = function(){

		if(xhr.readyState == 4 && xhr.status == 200)
			{
			leselect = xhr.responseText;
			document.getElementById('option_backend').innerHTML = leselect;
			}
		}
	
	xhr.open("POST","../inc/plugin_data_injection.ajax.backend.php",true);

	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	if(choice==0)
		{
		sel = document.getElementById('dropdown_type');
		id = sel.options[sel.selectedIndex].value;
		}
	else
		id = choice;
		
	xhr.send("id="+id);
}

function getBackend()
{
	id = document.getElementById('dropdown_type').value;
	
	return id;
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
	
	window.open('../front/plugin_data_injection.popup.file.php?nbline='+nbline+'', 'Popup', 'resizable=no, location=no, menubar=no, scrollbars=yes, toolbar=no, status=no, width=970, height='+height+', left='+x+', top='+y+'');
}

function log_popup(nbline)
{
	var x = screen.width;
	var y = screen.height;
	
	height = (nbline * 30) + 210;
	
	x = (x - 1010)/2;
	y = (y - nbline)/2;
	
	window.open('../front/plugin_data_injection.popup.log.php', 'Popup', 'resizable=no, location=no, menubar=no, scrollbars=yes, toolbar=no, status=no, width=1010, height='+height+', left='+x+', top='+y+'');
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

function show_log(num)
{	
	if(document.getElementById('log'+num+'_table').style.display == 'none')
		{
		document.getElementById('log'+num).src = '../pics/minus.png';
		document.getElementById('log'+num+'_table').style.display = 'block';
		}
	else
		{
		document.getElementById('log'+num).src = '../pics/plus.png';
		document.getElementById('log'+num+'_table').style.display = 'none';
		}
}

function show_option()
{	
	if(document.getElementById('option').style.display == 'none')
		{
		document.getElementById('option_img').src = '../pics/minus.png';
		document.getElementById('option').style.display = 'block';
		}
	else
		{
		document.getElementById('option_img').src = '../pics/plus.png';
		document.getElementById('option').style.display = 'none';
		}
}
