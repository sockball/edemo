<?php 


function p($param)
{
	echo '<pre>';
	print_r($param);
	echo '</pre>';
	exit;
}

function v($param)
{
	echo '<pre>';
	var_dump($param);
	echo '<pre>';
	exit;
}