function change_color_step(id) {
	
	document.getElementById(id).style.backgroundColor='#e6e6e6';
}

function showSelect()
{	

		if (document.forms['step1'].modele[0].checked)
			document.forms['step1'].load.disabled=true;
		else
			document.forms['step1'].load.disabled=false;
		
}