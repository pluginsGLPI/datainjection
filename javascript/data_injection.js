function change_color_step(id) 
{
	document.getElementById(id).style.backgroundColor='#e6e6e6';
}

function showSelect()
{	
	if (document.forms['step1'].modele[0].checked)
		document.forms['step1'].dropdown.disabled=true;
	else
		document.forms['step1'].dropdown.disabled=false;
}

function deleteOnglet(nbonglet)
{
	for(var i=1;i<=5;i++)
		{
		if(i>nbonglet)
			document.getElementById('step'+i).style.display='none';
		else
			document.getElementById('step'+i).style.display='table-cell';
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
			
function go(id)
{
	var xhr = getXhr();

	xhr.onreadystatechange = function(){

		if(xhr.readyState == 4 && xhr.status == 200)
			{
			leselect = xhr.responseText;
			document.getElementById('field'+id).innerHTML = leselect;
			}
		}

	xhr.open("POST","../inc/plugin_data_injection.ajax.tablefield.php",true);

	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	sel = document.getElementById('table'+id);
	idtable = sel.options[sel.selectedIndex].value;
	
	xhr.send("idMapping="+idtable+" & id="+id);
}

function popup()
{
	var nbline = document.getElementById('nbline').value;
	var x = screen.width;
	var y = screen.height;
	
	width = (nbline * 40) + 60;
	
	x = (x - 950)/2;
	y = (y - nbline)/2;
	
	window.open('../inc/plugin_data_injection.popup.php?nbline='+nbline+'', 'Popup', 'resizable=no, location=no, menubar=no, toolbar=no, status=no, width=950, height='+width+', left='+x+', top='+y+'');
	
	document.getElementById('popup').submit();
}