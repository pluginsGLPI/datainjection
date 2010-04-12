<?php

if (!defined('GLPI_ROOT')) {
	define('GLPI_ROOT', '../../..');
}

include (GLPI_ROOT."/lib/ezpdf/class.ezpdf.php");
include (GLPI_ROOT."/inc/includes.php");

include("../plugin_datainjection.includes.php");

global $LANG;

$pdf= new Cezpdf('a4','portrait');
$width = $pdf->ez['pageWidth'];
$height = $pdf->ez['pageHeight'];
$start_tab = 750;
$pdf->openHere('Fit');

$id_pdf=$pdf->openObject();
$pdf->saveState();
$pdf->ezStartPageNumbers(575,10,10,'left',convDate(date("Y-m-d"))." - {PAGENUM}/{TOTALPAGENUM}");
$pdf->setStrokeColor(0,0,0);
$pdf->setLineStyle(1,'round','round');
$pdf->rectangle(20,20,$width-40,$height-40);
$pdf->addJpegFromFile("../pics/fd_logo.jpg",25,$height-50);

$pdf->selectFont("../fonts/Times-Roman.afm");
$pdf->setFontFamily('Times-Roman.afm',array('b'=>'Times-Bold.afm','i'=>'Times-Italic.afm','bi'=>'Times-BoldItalic.afm'));

$pdf->addText(200,$height-45,14,utf8_decode('<b>' .  $LANG["datainjection"]["result"][18] . ' ' . $_SESSION["plugin_datainjection"]["file_name"].'</b>'));

$pdf->restoreState();
$pdf->closeObject();
$pdf->addObject($id_pdf,'all');

$model = unserialize($_SESSION["plugin_datainjection"]["model"]);
$tab_result = unserialize($_SESSION["plugin_datainjection"]["import"]["tab_result"]);

$tab_result = PluginDatainjectionResult::sort($tab_result);

$i=0;

if(count($tab_result[1])>0)
	{
	$pdf->saveState();
	$pdf->setColor(0.8,0.8,0.8);
	$pdf->filledRectangle(25,($start_tab-25)-(20*$i),40,15);
	$pdf->filledRectangle(70,($start_tab-25)-(20*$i),40,15);
	$pdf->filledRectangle(115,($start_tab-25)-(20*$i),150,15);
	$pdf->filledRectangle(270,($start_tab-25)-(20*$i),120,15);
	$pdf->filledRectangle(395,($start_tab-25)-(20*$i),80,15);
	$pdf->filledRectangle(480,($start_tab-25)-(20*$i),90,15);
	$pdf->restoreState();
	$pdf->addText(32,($start_tab)-(20*$i),9,'<b><i>'.utf8_decode($LANG["datainjection"]["logStep"][4]).' :</i></b>');
	$pdf->addText(32,($start_tab-20)-(20*$i),9,'<b>'.utf8_decode($LANG["joblist"][0]).'</b>');
	$pdf->addText(80,($start_tab-20)-(20*$i),9,'<b>'.utf8_decode($LANG["datainjection"]["result"][14]).'</b>');
	$pdf->addText(145,($start_tab-20)-(20*$i),9,'<b>'.utf8_decode($LANG["datainjection"]["result"][10]).'</b>');
	$pdf->addText(290,($start_tab-20)-(20*$i),9,'<b>'.utf8_decode($LANG["datainjection"]["result"][11]).'</b>');
	$pdf->addText(405,($start_tab-20)-(20*$i),9,'<b>'.utf8_decode($LANG["datainjection"]["result"][12]).'</b>');
	$pdf->addText(485,($start_tab-20)-(20*$i),9,'<b>'.utf8_decode($LANG["datainjection"]["result"][13]).'</b>');

	$i++;

	foreach($tab_result[1] as $key => $value)
		{
		$pdf->saveState();
		
		if($key%2==0)
			$pdf->setColor(0.95,0.95,0.95);
		else
			$pdf->setColor(0.8,0.8,0.8);
	
		$pdf->filledRectangle(25,($start_tab-25)-(20*$i),40,15);
		$pdf->filledRectangle(70,($start_tab-25)-(20*$i),40,15);
		$pdf->filledRectangle(115,($start_tab-25)-(20*$i),150,15);
		$pdf->filledRectangle(270,($start_tab-25)-(20*$i),120,15);
		$pdf->filledRectangle(395,($start_tab-25)-(20*$i),80,15);
		$pdf->filledRectangle(480,($start_tab-25)-(20*$i),90,15);
		
		$pdf->restoreState();
		
		$pdf->addJpegFromFile("../pics/ok.jpg",40,($start_tab-25)-(20*$i)+2,10,10);
		
		$pdf->addText(85,($start_tab-20)-(20*$i),9,utf8_decode($value->getLineID()));
		
		$y=($start_tab-20)-(20*$i)+4;
		$temp=utf8_decode($value->getCheckMessage());
		while($temp = $pdf->addTextWrap(120,$y,140,7,$temp)) {
			$y-=7;
		}
		
		$y=($start_tab-20)-(20*$i)+4;
		$temp=utf8_decode($value->getInjectionMessage());
		while($temp = $pdf->addTextWrap(275,$y,100,7,$temp)) {
			$y-=7;			
		}
		
		$pdf->addText(415,($start_tab-20)-(20*$i),9,utf8_decode(($value->getInjectionType()==INJECTION_ADD?$LANG["datainjection"]["result"][8]:$LANG["datainjection"]["result"][9])));
		$pdf->addText(515,($start_tab-20)-(20*$i),9,utf8_decode($value->getInjectedId()));
		
		$i++;
		
		if(($start_tab-20)-(20*$i)<50)
			{
			/*
			$pdf->ezText("",1000);
			$pdf->ezText("",9);
			*/
			$pdf->ezNewPage();
			$i=0;
			$start_tab = 750;
			}
		}
	}

if($i!=0)
	$i+=2;
	
if(count($tab_result[0])>0)
	{
	$pdf->saveState();
	$pdf->setColor(0.8,0.8,0.8);
	$pdf->filledRectangle(25,($start_tab-25)-(20*$i),40,15);
	$pdf->filledRectangle(70,($start_tab-25)-(20*$i),40,15);
	$pdf->filledRectangle(115,($start_tab-25)-(20*$i),150,15);
	$pdf->filledRectangle(270,($start_tab-25)-(20*$i),120,15);
	$pdf->filledRectangle(395,($start_tab-25)-(20*$i),80,15);
	$pdf->filledRectangle(480,($start_tab-25)-(20*$i),90,15);
	$pdf->restoreState();
	$pdf->addText(32,($start_tab)-(20*$i),9,'<b><i>'.utf8_decode($LANG["datainjection"]["logStep"][5]).' :</i></b>');
	$pdf->addText(32,($start_tab-20)-(20*$i),9,'<b>'.utf8_decode($LANG["joblist"][0]).'</b>');
	$pdf->addText(80,($start_tab-20)-(20*$i),9,'<b>'.utf8_decode($LANG["datainjection"]["result"][14]).'</b>');
	$pdf->addText(145,($start_tab-20)-(20*$i),9,'<b>'.utf8_decode($LANG["datainjection"]["result"][10]).'</b>');
	$pdf->addText(290,($start_tab-20)-(20*$i),9,'<b>'.utf8_decode($LANG["datainjection"]["result"][11]).'</b>');
	$pdf->addText(405,($start_tab-20)-(20*$i),9,'<b>'.utf8_decode($LANG["datainjection"]["result"][12]).'</b>');
	$pdf->addText(485,($start_tab-20)-(20*$i),9,'<b>'.utf8_decode($LANG["datainjection"]["result"][13]).'</b>');

	$i++;

	foreach($tab_result[0] as $key => $value)
		{
		$pdf->saveState();
		
		if($key%2==0)
			$pdf->setColor(0.95,0.95,0.95);
		else
			$pdf->setColor(0.8,0.8,0.8);
	
		$pdf->filledRectangle(25,($start_tab-25)-(20*$i),40,15);
		$pdf->filledRectangle(70,($start_tab-25)-(20*$i),40,15);
		$pdf->filledRectangle(115,($start_tab-25)-(20*$i),150,15);
		$pdf->filledRectangle(270,($start_tab-25)-(20*$i),120,15);
		$pdf->filledRectangle(395,($start_tab-25)-(20*$i),80,15);
		$pdf->filledRectangle(480,($start_tab-25)-(20*$i),90,15);
		
		$pdf->restoreState();
		
		// Global status
		if ($value->getCheckStatus() != CHECK_OK || $value->getInjectionStatus()==NOT_IMPORTED) {
			$pdf->addJpegFromFile("../pics/notok.jpg",40,($start_tab-25)-(20*$i)+2,10,10);
		} else {
			$pdf->addJpegFromFile("../pics/danger.jpg",40,($start_tab-25)-(20*$i)+2,10,10);
		}
		
		$pdf->addText(85,($start_tab-20)-(20*$i),9,utf8_decode($value->getLineID()));
		
		$x=120;
		$length=140;
		
		// Check status
		if ($value->getCheckStatus() != CHECK_OK) {
			$pdf->addJpegFromFile("../pics/notok.jpg",120,($start_tab-25)-(20*$i)+2,10,10);
			$x=135;
			$length=125;
		}
		
		$y=($start_tab-20)-(20*$i)+4;
		$temp=utf8_decode($value->getCheckMessage());
		while($temp = $pdf->addTextWrap($x,$y,$length,7,$temp)) {
			$y-=7;			
		}
		
		$x=275;
		$length=100;
		
		// Injection status
		if($value->getInjectionStatus() == NOT_IMPORTED) {
			$pdf->addJpegFromFile("../pics/notok.jpg",275,($start_tab-25)-(20*$i)+2,10,10);
		} else {
			$pdf->addJpegFromFile("../pics/danger.jpg",275,($start_tab-25)-(20*$i)+2,10,10);
		}
		$x=290;
		$length=85;
		
		$y=($start_tab-20)-(20*$i)+4;
		$temp=utf8_decode($value->getInjectionMessage());
		while($temp = $pdf->addTextWrap($x,$y,$length,7,$temp)) {
			$y-=7;
		}
		
		
		$pdf->addText(415,($start_tab-20)-(20*$i),9,utf8_decode(($value->getInjectionType()==INJECTION_ADD?$LANG["datainjection"]["result"][8]:$LANG["datainjection"]["result"][9])));
		if ($value->getInjectedId()>0) {
			$pdf->addText(515,($start_tab-20)-(20*$i),9,utf8_decode($value->getInjectedId()));
		}
		
		$i++;
		
		if(($start_tab-20)-(20*$i)<50)
			{
			/*
			$pdf->ezText("",1000);
			$pdf->ezText("",9);
			*/
			$pdf->ezNewPage();
			$i=0;
			$start_tab = 750;
			}
		}
	}		

$pdf->ezStream();
?>
