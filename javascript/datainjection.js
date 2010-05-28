
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
	
	window.open('../front/plugin_datainjection.popup.file.php?nbline='+nbline+'', 'Popup', 'resizable=no, location=no, menubar=no, scrollbars=yes, toolbar=no, status=no, width=970, height='+height+', left='+x+', top='+y+'');
}

function log_popup(nbline)
{
	var x = screen.width;
	var y = screen.height;
	
	height = (nbline * 30) + 210;
	
	x = (x - 1010)/2;
	y = (y - nbline)/2;
	
	window.open('../front/plugin_datainjection.popup.log.php', 'Popup', 'resizable=no, location=no, menubar=no, scrollbars=yes, toolbar=no, status=no, width=1010, height='+height+', left='+x+', top='+y+'');
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
