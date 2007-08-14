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
		if(i>nbonglet)
			document.getElementById('step'+i).style.display='none';
		else
			document.getElementById('step'+i).style.display='table-cell';
}