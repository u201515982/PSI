<?php
/*
Template Name: main
*/
if(!is_user_logged_in()){
	auth_redirect();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

	<style>
		.tabinator {
			background: #fff;
		}
		.tabinator > input {
			display: none;
		}
		.tabinator label {
			display: inline-block;
			padding: 15px;
			color: #ccc;
			margin-bottom: -1px;
			/*white-space: pre-wrap;*/
		}
		.tabinator input:checked + label {
			position: relative;
			color: #000;
			background: #fff;
			border: 1px solid #bbb;
			border-bottom: 1px solid #fff;
			border-radius: 5px 5px 0 0;
		}
		.tabinator input:checked + label:after {
			display: block;
			content: '';
			position: absolute;
			top: 0; right: 0; bottom: 0; left: 0;
		}
		#content1, #content2, #content3 {
			display: none;
			border: 1px solid #bbb;
			padding-top: 10px;
		}
		#tab1:checked ~ #content1,
		#tab2:checked ~ #content2,
		#tab3:checked ~ #content3 {
			display: block;
		}
		#contentA, #contentB, #contentC {
			display: none;
			border: 1px solid #bbb;
			padding-top: 10px;
		}
		#tabA:checked ~ #contentA,
		#tabB:checked ~ #contentB,
		#tabC:checked ~ #contentC {
			display: block;
		}
		#contentM1, #contentM2, #contentM3, #contentM4, #contentM5, #contentM6, #contentM7 {
			display: none;
			border: 1px solid #bbb;
			padding: 10px;
		}
		#tabM1:checked ~ #contentM1,
		#tabM2:checked ~ #contentM2,
		#tabM3:checked ~ #contentM3,
		#tabM4:checked ~ #contentM4,
		#tabM5:checked ~ #contentM5,
		#tabM6:checked ~ #contentM6,
		#tabM7:checked ~ #contentM7 {
			display: block;
		}
		.map-responsive{
			overflow:hidden;
			padding-bottom:56.25%;
			position:relative;
			height:0;
		}
		.map-responsive iframe{
			left:0;
			top:0;
			height:100%;
			width:100%;
			position:absolute;
		}
	</style>
	<style>
		body {
			font-family: 'Roboto', sans-serif;
			background: white;
			margin: 0px;
			padding: 0;
			position: relative;
			min-height: 100%;
		}
		.flex {
			margin: 0;
			display:flex;
			max-width: 500px;
		}
		.flex div {
			background: white;
			margin-right: 20px;
			margin-left: 20px;
			margin-top: 10px;
			margin-bottom: 10px;
		}
		.flex img {
			vertical-align: middle;
			height: 50px;
			width: auto;
		}
		#nav-bar {
			background: lavender;
			height: 50px;
			width: auto;
			line-height: 50px;
			position: relative;
			margin: 0 auto;
		}
		#nav-bar p {
			display: block;
			margin-block-start: 0em;
			margin-block-end: 0em;
			margin-inline-start: 0px;
			margin-inline-end: 0px;
		}
		.logout {
			font-size: 14px;
			text-decoration: none;
			color: #fff;
			transition: .2s background-color;
			background-color: #CBEDEB;
			position: absolute;
			right: 0;
			top: 0;
			bottom: 0;
			padding-left: 20px;
			padding-right: 20px;
		}
		.logout:hover {
			background-color: #15BCBC;
		}
		#wrapper > table{
			width: 100%;
			table-layout: fixed;
		}
		.buscar{
			padding: 4px;
			margin-top: 4px;
			margin-bottom: 4px;
			margin-left: 4px;
			font-size: 1em;
			border-radius: 5px;
			border: 1px solid #A6C8D2;
		}
		.scrolltable{
			height:300px;
			overflow:auto;
		}
		.scrolltable caption {
			caption-side: top;
			background:#4B4B4B;
			color: #fff;
			padding: 4px;
			font-weight: 100;
		}
		.scrolltable table {
			font-size: 13px;
			border-top: 1px solid #ddd;
			width:100%;
			border-collapse:collapse;
			padding:5px;
			text-align: center;
		}
		.scrolltable th {
			padding: 4px 2px;
			background:#E8E8E8;
			color: black;
		}
		.scrolltable td {
			padding:4px 2px;
		}
		.scrolltable th, td {
			border-right: 1px solid #ddd;
			border-bottom: 1px solid #ddd;
		}
		.resoluciones td:hover{
			background: #DEDEDE;
		}
		.resoluciones a{
			text-decoration: none;
		}
	</style>
	<style>
		.carousel-inner img {
			width: 100%;
			height: auto;
		}
		#myImg {
			border-radius: 5px;
			cursor: pointer;
			transition: 0.2s;
		}
		#myImg:hover {
			opacity: 0.8;
		}
		.modal {
			display: none;
			position: fixed;
			z-index: 999999;
			padding: auto;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			overflow: auto;
			background-color: rgb(0,0,0);
			background-color: rgba(0,0,0,0.7);
		}
		.modal-content {
			margin: auto;
			display: block;
			max-height: 80%;
			width: auto;
		}
		#caption {
			margin: auto;
			display: block;
			width: 80%;
			max-width: 700px;
			text-align: center;
			color: #ccc;
			padding: 10px 0;
			height: 150px;
		}
		.modal-content, #caption {  
			-webkit-animation-name: zoom;
			-webkit-animation-duration: 0.6s;
			animation-name: zoom;
			animation-duration: 0.6s;
		}
		@-webkit-keyframes zoom {
			from {-webkit-transform:scale(0)} 
			to {-webkit-transform:scale(1)}
		}
		@keyframes zoom {
			from {transform:scale(0)} 
			to {transform:scale(1)}
		}
		.close {
			position: absolute;
			top: 15px;
			right: 35px;
			color: #f1f1f1;
			font-size: 40px;
			font-weight: bold;
			transition: 0.3s;
		}
		.close:hover,
		.close:focus {
			color: #bbb;
			text-decoration: none;
			cursor: pointer;
		}
		@media only screen and (max-width: 100%){
			.modal-content {
				width: 100%;
			}
		}
		#arrowright:hover, #arrowleft:hover{
			opacity: 0.3;
			background: #676767;
		}
	</style>
</head>
<body>
	<script>
		$(document).ready(function () {
			document.getElementById("tbs").insertAdjacentHTML("beforeend", httpGet("http://www.psi.gob.pe/ws/ws-anexos.php"));
			document.getElementById("correos").insertAdjacentHTML("beforeend", httpGet("http://www.psi.gob.pe/ws/ws-correos.php"));
			document.getElementById("celulares").insertAdjacentHTML("beforeend", httpGet("http://www.psi.gob.pe/ws/ws-celulares.php"));
		});
		function httpGet(theUrl){
			var xmlHttp = new XMLHttpRequest();
			xmlHttp.open( "GET", theUrl, false );
			xmlHttp.send( null );
			return xmlHttp.responseText;
		}
		function searchAnexos() {
			var input, filter;
			input = document.getElementById("myInput");
			filter = input.value.toUpperCase();
			searchInd(filter, "de");
			searchInd(filter, "dir");
			searchInd(filter, "oep");
			searchInd(filter, "os");
			searchInd(filter, "oaf");
			searchInd(filter, "oaj");
			searchInd(filter, "dgr");
			searchInd(filter, "otr");
			searchInd(filter, "opps");
			searchInd(filter, "ocat");
			searchInd(filter, "oci");
			searchInd(filter, "sa");
			searchInd(filter, "mp");
			searchInd(filter, "rec");
			searchInd(filter, "rrhh");
			searchInd(filter, "uis");
			searchInd(filter, "pat");
			searchInd(filter, "sectec");
			searchInd(filter, "alm");
			searchInd(filter, "sergen");
			searchInd(filter, "seg");
		}
		function searchInd(filter, id){
			var found, table, tr, td, i, j, k, cap, count;
			table = document.getElementById(id);
			tr = table.getElementsByTagName("tr");
			cap = table.getElementsByTagName("caption");
			count = 0;
			for (i = 1; i < tr.length; i++) {
				td = tr[i].getElementsByTagName("td");
				for (j = 0; j < cap.length; j++) {
					if(cap[j].innerHTML.toUpperCase().indexOf(filter) > -1){
						found = true;
						count = count + 1;
						break;
					}
				}
				for (k = 0; k < td.length; k++) {
					if(found) break;
					if(td[k].innerHTML.toUpperCase().indexOf(filter) > -1){
						found = true;
						count = count + 1;
						break;
					}
				}
				if (found) {
					tr[i].style.display = "";
					found = false;
				} else {
					tr[i].style.display = "none";
				}
			}
			if(count != 0){
				table.style.display = "";
			}else{
				table.style.display = "none";
			}
		}
		function searchCorreo(){
			var input, filter, found, table, tr, td, i, j;
			input = document.getElementById("busqCorreo");
			filter = input.value.toUpperCase();
			table = document.getElementById("tablecorreos");
			tr = table.getElementsByTagName("tr");
			for(i = 1; i < tr.length; i++){
				td = tr[i].getElementsByTagName("td");
				for(j = 0; j < td.length; j++){
					if(td[j].innerHTML.toUpperCase().indexOf(filter) > -1){
						found = true;
					}
				}
				if(found){
					tr[i].style.display = "";
					found = false;
				}else{
					tr[i].style.display = "none";
				}
			}
		}
		function searchCelulares(){
			var input, filter, found, table, tr, td, i, j;
			input = document.getElementById("busqCelulares");
			filter = input.value.toUpperCase();
			table = document.getElementById("tablecelulares");
			tr = table.getElementsByTagName("tr");
			for (i = 1; i < tr.length; i++) {
				td = tr[i].getElementsByTagName("td");
				for (j = 0; j < td.length; j++) {
					if (td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
						found = true;
					}
				}
				if (found) {
					tr[i].style.display = "";
					found = false;
				} else {
					tr[i].style.display = "none";
				}
			}
		}
	</script>
	<div style="position: relative;margin: 0 auto;">
		<div id="nav-bar">
			<p style="font-size: 16px; padding-left:10px; ">NAMELASTNAME</p>
			<div >
				<a class="logout" style="position: absolute;right: 0;top: 0;bottom: 0;" href="#">LOGOUT</a>
			</div>
		</div>
		<div class="flex">
			<div>
				<a target="_blank" href="https://mail.google.com/mail/u/0/#inbox"><img src="http://www.psi.gob.pe/intranet/res/gmail.png" alt="gmail"></a>
			</div>
			<div>
				<a target="_blank" href="http://mail.psi.gob.pe"><img src="http://www.psi.gob.pe/intranet/res/zimbra.png" alt="zimbra" ></a>
			</div>
		</div>
		<div id="wrapper">
			<table>
				<tr >
					<td style="width: 450px;" valign="top">
						<div class = "tabinator">
							<input type = "radio" id = "tab1" name = "tabs" checked>
							<label for = "tab1">Anexos</label>
							<input type = "radio" id = "tab2" name = "tabs">
							<label for = "tab2">Correos</label>
							<input type = "radio" id = "tab3" name = "tabs">
							<label for = "tab3">Celulares</label>

							<div id = "content1">
								<input class="buscar" id="myInput" onkeyup="searchAnexos()" type="text" placeholder="Buscar..." />
								<div id="tbs" class="scrolltable"></div>
							</div>
							<div id = "content2">
								<input class="buscar" id="busqCorreo" onkeyup="searchCorreo()" type="text" placeholder="Buscar..." />
								<div id="correos" class="scrolltable"></div>
							</div>
							<div id = "content3">
								<input class="buscar" id="busqCelulares" type="text" onkeyup="searchCelulares()" placeholder="Buscar..." />
								<div id="celulares" class="scrolltable"></div>
							</div>
						</div>
					</td>

					<td valign="top" align="center" >
						<div id="demo" class="carousel slide" data-ride="carousel" style="max-width: 600px;">

							<ul id="indicators" class="carousel-indicators">

							</ul>

							<div id="slideshow" class="carousel-inner">

							</div>

							<div>
								<a id="arrowleft" class="carousel-control-prev" href="#demo" data-slide="prev">
									<span class="carousel-control-prev-icon"></span>
								</a>
								<a id="arrowright" class="carousel-control-next" href="#demo" data-slide="next">
									<span class="carousel-control-next-icon"></span>
								</a>
							</div>
						</div>
					</td>

					<td style="width: 400px;" valign="top">
						<div class = "tabinator">
							<input type = "radio" id = "tabA" name = "tabs2" checked>
							<label for = "tabA">Resoluciones<br>Directoriales</label>
							<input type = "radio" id = "tabB" name = "tabs2">
							<label for = "tabB">Resoluciones<br>Administrativas</label>
							<input type = "radio" id = "tabC" name = "tabs2">
							<label for = "tabC">Directivas</label>

							<div id = "contentA">
								<div class="scrolltable">
									<table class="resoluciones">
										<tr><td><a href="http://www.psi.gob.pe/docs/resoluciones/2009/rd 2009.pdf" target="_blank"><div style="height:100%;width:100%">R.D. 2009</div></a></td></tr>
										<tr><td><a href="http://www.psi.gob.pe/docs/resoluciones/2010/rd 2010.pdf" target="_blank"><div style="height:100%;width:100%">R.D. 2010</div></a></td></tr>
										<tr><td><a href="http://www.psi.gob.pe/docs/resoluciones/2011/rd 2011.pdf" target="_blank"><div style="height:100%;width:100%">R.D. 2011</div></a></td></tr>
										<tr><td><a href="http://www.psi.gob.pe/docs/resoluciones/2012/rd 2012.pdf" target="_blank"><div style="height:100%;width:100%">R.D. 2012</div></a></td></tr>
										<tr><td><a href="http://www.psi.gob.pe/docs/resoluciones/2013/rd 2013.pdf" target="_blank"><div style="height:100%;width:100%">R.D. 2013</div></a></td></tr>
										<tr><td><a href="http://www.psi.gob.pe/docs/resoluciones/2014/rd 2014.pdf" target="_blank"><div style="height:100%;width:100%">R.D. 2014</div></a></td></tr>
										<tr><td><a href="http://www.psi.gob.pe/docs/resoluciones/2015/rd 2015.pdf" target="_blank"><div style="height:100%;width:100%">R.D. 2015</div></a></td></tr>
										<tr><td><a href="http://www.psi.gob.pe/docs/resoluciones/2016/rd 2016.pdf" target="_blank"><div style="height:100%;width:100%">R.D. 2016</div></a></td></tr>
										<tr><td><a href="http://www.psi.gob.pe/docs/resoluciones/2017/rd 2017.pdf" target="_blank"><div style="height:100%;width:100%">R.D. 2017</div></a></td></tr>
										<tr><td><a href="http://www.psi.gob.pe/docs/resoluciones/2018/rd 2018.pdf" target="_blank"><div style="height:100%;width:100%">R.D. 2018</div></a></td></tr>
										<tr><td><a href="http://www.psi.gob.pe/docs/resoluciones/2019/rd 2019.pdf" target="_blank"><div style="height:100%;width:100%">R.D. 2019</div></a></td></tr>
									</table>
								</div>
							</div>
							<div id = "contentB">
								<div class="scrolltable">
									<table class="resoluciones">
										<tr><td><a href="http://www.psi.gob.pe/docs/resolucionesadm/lister.php?year=2018" target="_blank"><div style="height:100%;width:100%">R.A. 2018</div></a></td></tr>
										<tr><td><a href="http://www.psi.gob.pe/docs/resolucionesadm/lister.php?year=2019" target="_blank"><div style="height:100%;width:100%">R.A. 2019</div></a></td></tr>
									</table>
								</div>
							</div>
							<div id = "contentC">

							</div>
						</div>
					</td>
				</tr>
			</table>

			<div class = "tabinator" style="height: 400px; width: 500px;">
				<input type = "radio" id = "tabM1" name = "tabsM" checked>
				<label for = "tabM1" style="padding: 5px;">Huancayo</label>
				<input type = "radio" id = "tabM2" name = "tabsM">
				<label for = "tabM2" style="padding: 5px;">Arequipa</label>
				<input type = "radio" id = "tabM3" name = "tabsM">
				<label for = "tabM3" style="padding: 5px;">Trujillo</label>
				<input type = "radio" id = "tabM4" name = "tabsM">
				<label for = "tabM4" style="padding: 5px;">Chiclayo</label>
				<input type = "radio" id = "tabM5" name = "tabsM">
				<label for = "tabM5" style="padding: 5px;">Cusco</label>
				<input type = "radio" id = "tabM6" name = "tabsM">
				<label for = "tabM6" style="padding: 5px;">Piura</label>
				<input type = "radio" id = "tabM7" name = "tabsM">
				<label for = "tabM7" style="padding: 5px;">Casma</label>

				<div id = "contentM1">
					<div class="map-responsive">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d975.4432011930492!2d-75.2055809707963!3d-12.059147099466172!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x910e963590d0a9d9%3A0x36729d11c5a161d!2sFrancisco%20Solano%20107%2C%20Huancayo%2012001!5e0!3m2!1sen!2spe!4v1568827982893!5m2!1sen!2spe" frameborder="0" style="border:0" allowfullscreen></iframe>
					</div>
				</div>
				<div id = "contentM2">
					<div class="map-responsive">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3827.5028339393734!2d-71.54769659100644!3d-16.399269432646154!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91424b28d47f62df%3A0x1d2928ac2c5882bd!2sPrograma%20Subsectorial%20de%20Irrigaciones%20(PSI)!5e0!3m2!1sen!2spe!4v1568834742862!5m2!1sen!2spe" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
					</div>
				</div>
				<div id = "contentM3">
					<div class="map-responsive">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3950.0235897630596!2d-79.03141398484296!3d-8.099076294169276!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91ad3d8de82d2e9b%3A0xf0f8b59120536d22!2sPrograma%20Subsectorial%20de%20Irrigaciones!5e0!3m2!1sen!2spe!4v1568834881788!5m2!1sen!2spe" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
					</div>
				</div>
				<div id = "contentM4">
					<div class="map-responsive">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d990.4712665905143!2d-79.83997974197489!3d-6.783839129291723!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x904cef853cb77aa9%3A0xb87bf3ed966be69d!2sMinisterio%20de%20Agricultura%20y%20Riego%20PSI!5e0!3m2!1sen!2spe!4v1568834440313!5m2!1sen!2spe" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
					</div>
				</div>
				<div id = "contentM5">
					<div class="map-responsive">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d484.8885777995198!2d-71.9502359937065!3d-13.528924925255078!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x916e7f564a128edf%3A0x27eb43b106a4356f!2sPsi!5e0!3m2!1sen!2spe!4v1568834303433!5m2!1sen!2spe" frameborder="0" style="border:0" allowfullscreen></iframe>
					</div>
				</div>
				<div id = "contentM6">
					<div class="map-responsive">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3973.498280465817!2d-80.65424678485657!3d-5.184057696238262!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x904a1a939d4c4e15%3A0x1bfea492a12261b3!2sChira%20Piura%20Special%20Project!5e0!3m2!1sen!2spe!4v1568829146933!5m2!1sen!2spe" frameborder="0" style="border:0" allowfullscreen></iframe>
					</div>
				</div>
				<div id = "contentM7">
					<div class="map-responsive">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d983.8616728554829!2d-78.30350537079744!3d-9.469818999575986!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91abb89ac87d4d7d%3A0x73ed888288c07922!2sPSI-%20CASMA!5e0!3m2!1sen!2spe!4v1568834224049!5m2!1sen!2spe" frameborder="0" style="border:0" allowfullscreen></iframe>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="myModal" class="modal">
		<span class="close">&times;</span>
		<img class="modal-content" id="img01">
	</div>

	<script>
		function utilNameId(str){
			arr = str.split(".");
			return arr[0];
		}
		function httpGet(theUrl){
			var xmlHttp = new XMLHttpRequest();
			xmlHttp.open( "GET", theUrl, false );
			xmlHttp.send( null );
			return xmlHttp.responseText;
		}
		comunicados = httpGet("http://www.psi.gob.pe/intranet/res/comunicados/lister.php");
		var comsArr = comunicados.split(',');
		var slides = "";
		var inds = "";
		for(var i=0;i<comsArr.length;i++){
			var strsld = "";
			var strind = "";
			if(i==0){
				strsld = " active";
				strind = " class=\"active\"";
			}
			inds=inds+"<li data-target=\"#demo\" data-slide-to=\""+i+"\""+strind+" style=\"background-color: rgba(0, 88, 87, 0.27)\"></li>";
			slides=slides+"<div class=\"carousel-item"+strsld+"\"><img id=\"" + utilNameId(comsArr[i]) + "\" onclick=\"picExpand(this)\" src=\"http://www.psi.gob.pe/intranet/res/comunicados/"+comsArr[i]+"\" ></div>";
		}
		document.getElementById("indicators").insertAdjacentHTML("beforeend", inds);
		document.getElementById("slideshow").insertAdjacentHTML("beforeend", slides);

		function picExpand(item){
			var modal = document.getElementById("myModal");
			var modalImg = document.getElementById("img01");
			modal.style.display = "block";
			modalImg.src = document.getElementById($(item).attr("id")).src;
			var span = document.getElementsByClassName("close")[0];
			span.onclick = function() { 
				modal.style.display = "none";
			}
		}
	</script>
</body>
</html>