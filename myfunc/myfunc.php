<?php 


function p($param)
{
	echo '<pre> . <br>';
	echo '-----------------------<br>';
	print_r($param);
	echo '-----------------------';
	echo '</pre>';
	exit;
}

function v($param)
{
	echo '<pre> . <br>';
	echo '-----------------------<br>';
	var_dump($param);
	echo '-----------------------';
	echo '<pre>';
	exit;
}