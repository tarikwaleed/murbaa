<!DOCTYPE html>
<html lang="ar">
	<head>
		<meta charset="UTF-8">
		<link rel="icon" href="<?php echo URL."public/IMG/".session::get("LOGO");?>">
		<meta name="description" content="<?php echo session::get("DESC_INFO");?>">
		<meta name="keywords" content="<?php echo session::get("DESC_INFO");?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title><?php echo session::get("TITLE")?></title>
		
		<!-- Css Styles -->
		<link rel="stylesheet" href="<?php echo URL ?>public/CSS/bootstrap.min.css" type="text/css">
		<link rel="stylesheet" href="<?php echo URL ?>public/CSS/font-awesome.min.css" type="text/css">
		<link rel="stylesheet" href="<?php echo URL ?>public/CSS/jquery-ui.min.css" type="text/css">
		<link rel="stylesheet" href="<?php echo URL ?>public/CSS/slicknav.min.css" type="text/css">
		<link rel="stylesheet" href="<?php echo URL ?>public/CSS/style.css" type="text/css" >
		<link rel="stylesheet" href="<?php echo URL ?>public/CSS/loader.css" type="text/css" >
		
		<?php
			if(isset($this->CSS))
			{
				foreach($this->CSS as $v)
				{
					echo '<link rel="stylesheet" href="'.URL.$v.'">';
				}
			}
		?>
		
	</head>
	<body onload="loader()" style="margin:0;">
	<div id="loader"></div>
	
	<vue-element-loading :active="show" is-full-screen />