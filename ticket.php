<?php 
/*
Template Name: Ticket
*/
if(!is_user_logged_in()){
	auth_redirect();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Solicitud de Asistencia Informática</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script lang="javascript" src="http://www.psi.gob.pe/intranet/res/xlsx.full.min.js"></script>
	<script lang="javascript" src="http://www.psi.gob.pe/intranet/res/FileSaver.js"></script>
	<style>
		body {
			font-family:sans-serif;
		}
		#donate {
			/*margin:4px;*/
			float:left;
		}
		#donate label {
			float:left;
			/*width:50px;*/
			margin:4px;
			background-color:#EFEFEF;
			border-radius:4px;
			border:1px solid #D0D0D0;
			overflow:auto;
			box-shadow: 1px 1px 4px #EBEBEB;
		}
		#donate label span {
			text-align:center;
			font-size: 16px;
			padding:5px 15px;
			display:block;
		}
		#donate label input {
			position:absolute;
			top:-20px;
		}
		#donate input:checked + span {
			background-color:#404040;
			color:#F7F7F7;
		}
		#donate .flrbtn {
			background-color:#FFFFFF;
			color:#333;
		}

		.form-style-2{
			max-width: 500px;
			padding: 20px 12px 10px 20px;
			font: 15px Arial, Helvetica, sans-serif;
		}
		.form-style-2-heading{
			font-weight: bold;
			font-style: italic;
			border-bottom: 2px solid #ddd;
			margin-bottom: 20px;
			font-size: 15px;
			padding-bottom: 3px;
		}
		.form-style-2 label{
			display: block;
			margin: 0px 0px 15px 0px;
		}
		.form-style-2 label > span{
			/*width: 125px;*/
			font-weight: normal;
			float: left;
			padding-top: 8px;
			padding-right: 5px;
		}
		.form-style-2 input.input-field, .form-style-2 .select-field{
			width: 48%;
		}
		.form-style-2 input.input-field, 
		.form-style-2 .tel-number-field, 
		.form-style-2 .textarea-field, 
		.form-style-2 .select-field{
			box-sizing: border-box;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			border: 1px solid #C2C2C2;
			box-shadow: 1px 1px 4px #EBEBEB;
			-moz-box-shadow: 1px 1px 4px #EBEBEB;
			-webkit-box-shadow: 1px 1px 4px #EBEBEB;
			border-radius: 3px;
			-webkit-border-radius: 3px;
			-moz-border-radius: 3px;
			padding: 7px;
			outline: none;
		}
		.form-style-2 .input-field:focus, 
		.form-style-2 .tel-number-field:focus, 
		.form-style-2 .textarea-field:focus,  
		.form-style-2 .select-field:focus{
			border: 1px solid #00A2DE;
		}
		.form-style-2 .textarea-field{
			height:100px;
			width: 55%;
		}
		.form-style-2 input[type=submit],
		.form-style-2 input[type=button]{
			border: none;
			padding: 8px 15px 8px 15px;
			background: #00A2DE;
			color: #fff;
			box-shadow: 1px 1px 4px #DADADA;
			-moz-box-shadow: 1px 1px 4px #DADADA;
			-webkit-box-shadow: 1px 1px 4px #DADADA;
			border-radius: 3px;
			-webkit-border-radius: 3px;
			-moz-border-radius: 3px;
		}
		.form-style-2 input[type=submit]:hover,
		.form-style-2 input[type=button]:hover{
			background: #0187B8;
		}
		#mytickets{
			margin:10px;
			max-width: 430px;
			font-family: monospace;
		}
		#mytickets .tk{
			display:inline-block;
			padding-top: 0px;
			padding-bottom: 0px;
			padding-left: 8px;
			padding-right: 8px;
			font-size: 13px;
			border-top-left-radius: 5px;
			border-top-right-radius: 5px;
			background-color: #D8D8D8;
		}
		#mytickets .tk-info{
			color: white;
			font-size: 16px;
			/*margin-bottom:15px;*/
			margin-bottom:0px;
			border-top-right-radius: 10px;
			border-bottom-left-radius: 10px;
			border-bottom-right-radius: 10px;
			padding: 5px;
			background-color: #162B4D;
			transition:all .2s ease;
		}
		#mytickets table{
			width: 100%;
		}
		#mytickets td{
			padding: 0px;
		}
		#gen-report{
			border: none;
			padding: 8px 15px 8px 15px;
			margin: 10px 20px;
			background: #00A2DE;
			color: #fff;
			box-shadow: 1px 1px 4px #DADADA;
			-moz-box-shadow: 1px 1px 4px #DADADA;
			-webkit-box-shadow: 1px 1px 4px #DADADA;
			border-radius: 3px;
			-webkit-border-radius: 3px;
			-moz-border-radius: 3px;
			font: 15px Arial, Helvetica, sans-serif;
		}
		#gen-report:hover{
			background: #0187B8;
		}
		.btn_small{
			border: none;
			padding: 6px 12px 6px 12px;
			margin: 10px;
			background: #D8D8D8;
			color: black;
			box-shadow: 1px 1px 4px #DADADA;
			-moz-box-shadow: 1px 1px 4px #DADADA;
			-webkit-box-shadow: 1px 1px 4px #DADADA;
			border-radius: 3px;
			-webkit-border-radius: 3px;
			-moz-border-radius: 3px;
			float: right;
		}
		.btn_small:hover{
			background: #BFBFBF;
		}
		.fadein{
			-webkit-animation: fadein 0.3s;
			margin-bottom: 15px;
		}
		@keyframes fadein {
			from { opacity: 0; }
			to   { opacity: 1; }
		}
		@-webkit-keyframes fadein {
			from { opacity: 0; }
			to   { opacity: 1; }
		}
		.wrapper {
			position: relative;
			overflow: hidden;
			width: 100%;
			height: 0px;
			transition: 0.2s;
			margin: 0;
		}
		.wrapout{
			transition: 0.2s;
			height: 50px;
		}
		.slider {
			position: absolute;
			right: 0;
			bottom: 50px;
			height: 50px;
			transition: 0.2s;
			border-bottom-left-radius: 5px;
			border-bottom-right-radius: 5px;
		}
		.slideout {
			transition: 0.2s;
			bottom: 0;
		}

	</style>
</head>
<body>
	<script src="https://www.gstatic.com/firebasejs/6.6.0/firebase-app.js"></script>
	<script src="https://www.gstatic.com/firebasejs/6.6.0/firebase-firestore.js"></script>
	<script>
		var wpuser = JSON.parse(httpGet("http://www.psi.gob.pe/intranet/index.php/query/"));
		var userisadmin = false;
		var meta_data;
		var firebaseConfig = {
			apiKey: "AIzaSyBtY0kX9eXgQfr2OS1d5qZ8rT_9VeRbZzo",
			authDomain: "sistemaspsi.firebaseapp.com",
			databaseURL: "https://sistemaspsi.firebaseio.com",
			projectId: "sistemaspsi",
			storageBucket: "sistemaspsi.appspot.com",
			messagingSenderId: "885044214981",
			appId: "1:885044214981:web:a6005da9bd5f9ea8f884ac"
		};
		firebase.initializeApp(firebaseConfig);
		var db = firebase.firestore();

		db.collection("users").get().then(function(querySnapshot) {
			querySnapshot.forEach(function(doc) {
				if(doc.id == wpuser["alias"]) userisadmin = true;
			});
			if(userisadmin){
				document.getElementById("areausuario").style.display = "block";
				document.getElementById("gen-report").style.display = "";
			}
		})
		.catch(function(error) {
			console.log("Error getting documents: ", error);
		});
		db.collection("root").doc("tk.0").onSnapshot(function(doc) {
			fetchmytickets();
		});
		function solicitar(){
			document.getElementById("submit").disabled = true;
			var tkid = "tk."+Date.now();
			var usuariopsi;
			var rightnow = new Date();
			var numpiso;
			var radiofloor = document.querySelector("input[name=toggle]:checked");
			numpiso = radiofloor.value;
			if(userisadmin){
				usuariopsi = document.getElementById("usuario").value;
			}else{
				usuariopsi = wpuser["name"];
			}
			db.collection("ticket").doc(tkid).set({
				claimed_by: null,
				created_by: wpuser["alias"],
				description: document.getElementById("descripcion").value,
				floor: numpiso,
				area: document.getElementById("area").value,
				post_mortem: null,
				state: "pending",
				time_begin: rightnow,
				time_claimed: null,
				time_end: null,
				type: document.getElementById("tipo").value,
				user: usuariopsi
			})
			.then(function() {
				console.log("Document written with ID: ", tkid);
				location.reload(true);
				/////
				if(!userisadmin){
					db.collection("root").doc(wpuser["alias"]).set({
						tk: tkid
					})
					.catch(function(error) {
						console.error("Error adding document: ", error);
					});
				}
				/////
			})
			.catch(function(error) {
				console.error("Error adding document: ", error);
			});
		}

		function addZero(n){
			if(n<10){ n="0"+n; }
			return n;
		}
		
		function fetchmytickets(){
			meta_data = [];
			clearBox("mytickets");
			if(userisadmin){
				db.collection("ticket").where("state", "==", "pending")
				.get()
				.then(function(querySnapshot) {
					querySnapshot.forEach(function(doc) {
						var allofmine = ticketForm(doc, 1);
						document.getElementById("mytickets").insertAdjacentHTML('beforeend',allofmine);
					});
				})
				.catch(function(error) {
					console.log("Error getting documents: ", error);
				});

				db.collection("ticket").where("state", "==", "claimed")
				.get()
				.then(function(querySnapshot) {
					querySnapshot.forEach(function(doc) {
						var allofmine = ticketForm(doc, 2);
						document.getElementById("mytickets").insertAdjacentHTML('beforeend',allofmine);
					});
				})
				.catch(function(error) {
					console.log("Error getting documents: ", error);
				});
			}else{
				allofmine="";

			}
		}

		function fullDate(doc,thetime){
			if(doc.data()[thetime]==null) return 0;
			date = new Date(doc.data()[thetime]["seconds"] * 1000),
			datevalues = [
			date.getFullYear(),
			date.getMonth()+1,
			date.getDate(),
			date.getHours(),
			date.getMinutes(),
			date.getSeconds(),
			];
			mytime=addZero(datevalues[3])+":"+addZero(datevalues[4])+":"+addZero(datevalues[5])+" "+addZero(datevalues[2])+"/"+addZero(datevalues[1])+"/"+addZero(datevalues[0]);
			return mytime;
		}

		function ticketForm(doc, estado){
			var prm;
			var border_color = "";
			if(estado==1){
				prm=true;
			}else{
				prm=doc.data()['claimed_by']==wpuser['alias']?true:false;
				if(prm) border_color = "style=\"border: 2px solid #0FD4A8\"";
			}
			meta_data[doc.id] = prm;

			date = new Date(doc.data()["time_begin"]["seconds"] * 1000),
			datevalues = [
			date.getFullYear(),
			date.getMonth()+1,
			date.getDate(),
			date.getHours(),
			date.getMinutes(),
			date.getSeconds(),
			];
			timebegin=addZero(datevalues[2])+"/"+addZero(datevalues[1])+" "+addZero(datevalues[3])+":"+addZero(datevalues[4]);
			var auxid = doc.id.split('.');
			var util_id = auxid[0]+auxid[1];
			var auxdesc = doc.data()['description'];
			var desctxt = "";
			if(!auxdesc=="" || !auxdesc==null){
				desctxt = "<tr style=\"border-top: 1px ridge gray;\"><td colspan=2>"+auxdesc+"</td></tr>";
			}
			var btn_second = "";
			if(estado == 1 && doc.data()['created_by'] == wpuser['alias']){
				btn_second = "<button class=\"btn_small\" onclick=\"cancelar('"+doc.id+"')\">cancelar</button>";
			}else if(estado == 2){
				btn_second = "<button class=\"btn_small\" onclick=\"liberar('"+doc.id+"')\">liberar</button>";
			}
			var allofmine = "<div class=\"fadein\" onclick=\"tkselected(this)\" id=\""+
			util_id+
			"\"><div class=\"tk\">"+
			doc.id+
			"</div><div class=\"tk-info\" "+border_color+"><table><tr><td style=\"font-style: italic;\">"+
			doc.data()['user']+
			"</td><td></td></tr><tr><td style=\"font-weight: 900;\">"+
			"Piso "+doc.data()['floor']+
			"</td><td style=\"text-align: right; color:"+
			(estado==1?"#00D277;\">Pendiente":"#6AF0FF;\">En curso")+
			"</td></tr><tr><td>"+
			doc.data()['type']+
			"</td><td style=\"text-align: right;\">"+
			timebegin+
			"</td></tr>"+
			desctxt+
			(estado==1?"":("<tr><td colspan=2 align='center' style='color:#6AF0FF'>Siendo atendido por "+doc.data()['claimed_by']+"</td></tr>"))+"</table>"+
			"</div><div class=\"wrapper\"><div class=\"slider\"><button class=\"btn_small\" onclick=\""+(estado==1?"atender":"terminar")+"('"+doc.id+"')\">"+(estado==1?"ATENDER":"TERMINAR")+"</button>"+btn_second+"</div></div></div>";
			return allofmine;
		}

		function validate(){
			var v1, v2;
			v1 = true;
			if(userisadmin){
				v1 = emptyfield("usuario");
				if(!v1){
					document.getElementById("usuario").style.borderColor = "red";
				}
			}
			var radiofloor = document.querySelector("input[name=toggle]:checked");
			if(radiofloor != null){
				v2 = true;
			}else{
				v2 = false;
				missingFloor(true);
			}
			if(v1 && v2) solicitar();
		}

		function redbox(){
			document.getElementById("usuario").style.borderColor = "#C2C2C2";
		}

		function missingFloor(state){
			var color;
			if(state){
				color = "red";
			}else{
				color = "#D0D0D0";
			}
			document.getElementById("lblpiso1").style.borderColor = color;
			document.getElementById("lblpiso2").style.borderColor = color;
			document.getElementById("lblpiso3").style.borderColor = color;
			document.getElementById("lblpiso4").style.borderColor = color;
			document.getElementById("lblpiso5").style.borderColor = color;
			document.getElementById("lblpiso6").style.borderColor = color;
			document.getElementById("lblpiso7").style.borderColor = color;
			document.getElementById("lblpiso8").style.borderColor = color;
			document.getElementById("lblpiso9").style.borderColor = color;
			document.getElementById("lblpiso10").style.borderColor = color;
		}

		function emptyfield(input){
			if (document.getElementById(input).value.trim().length == 0){
				return false;
			}
			return true;
		}

		function filter(radio){
			missingFloor(false);
			while(document.getElementById('area').options.length)
				document.getElementById('area').options.remove(0);
			switch(radio.value){
				case "1":{
					document.getElementById('area').options.add(new Option("MESA DE PARTES","MESA DE PARTES",true,true), 0);
					document.getElementById('area').options.add(new Option("RECEPCION","RECEPCION",false,false), 1);
					document.getElementById('area').options.add(new Option("SALA DE REUNIONES","SALA DE REUNIONES",false,false), 2);
					break;
				}
				case "2":{
					document.getElementById('area').options.add(new Option("DIR","DIR",true,true), 0);
					document.getElementById('area').options.add(new Option("OS","OS",false,false), 1);
					document.getElementById('area').options.add(new Option("OEP","OEP",false,false), 2);
					break;
				}
				case "3":{
					document.getElementById('area').options.add(new Option("DIR","DIR",true,true), 0);
					document.getElementById('area').options.add(new Option("OS","OS",false,false), 1);
					document.getElementById('area').options.add(new Option("OEP","OEP",false,false), 2);

					break;
				}
				case "4":{
					document.getElementById('area').options.add(new Option("DIR","DIR",true,true), 0);
					document.getElementById('area').options.add(new Option("OS","OS",false,false), 1);
					document.getElementById('area').options.add(new Option("OEP","OEP",false,false), 2);
					break;
				}
				case "5":{
					document.getElementById('area').options.add(new Option("DIR","DIR",true,true), 0);
					document.getElementById('area').options.add(new Option("OS","OS",false,false), 1);
					document.getElementById('area').options.add(new Option("OEP","OEP",false,false), 2);
					break;
				}
				case "6":{
					document.getElementById('area').options.add(new Option("DGR","DGR",true,true), 0);
					document.getElementById('area').options.add(new Option("OTR","OTR",false,false), 1);
					document.getElementById('area').options.add(new Option("OCAT","OCAT",false,false), 2);
					document.getElementById('area').options.add(new Option("OPPS","OPPS",false,false), 3);
					break;
				}
				case "7":{
					document.getElementById('area').options.add(new Option("OAJ","OAJ",true,true), 0);
					document.getElementById('area').options.add(new Option("OCI","OCI",false,false), 1);
					document.getElementById('area').options.add(new Option("SECRETARIA TECNICA","SECRETARIA TECNICA",false,false), 2);
					break;
				}
				case "8":{
					document.getElementById('area').options.add(new Option("LOGISTICA","LOGISTICA",true,true), 0);
					document.getElementById('area').options.add(new Option("FISCALIZACION","FISCALIZACION",false,false), 1);
					document.getElementById('area').options.add(new Option("EJECUCION CONTRACTUAL","EJECUCION CONTRACTUAL",false,false), 2);
					document.getElementById('area').options.add(new Option("PATRIMONIO","PATRIMONIO",false,false), 3);
					document.getElementById('area').options.add(new Option("ALMACEN","ALMACEN",false,false), 4);
					document.getElementById('area').options.add(new Option("SERVICIOS GENERALES","SERVICIOS GENERALES",false,false), 5);
					document.getElementById('area').options.add(new Option("UIS","UIS",false,false), 6);
					break;
				}
				case "9":{
					document.getElementById('area').options.add(new Option("OAF","OAF",true,true), 0);
					document.getElementById('area').options.add(new Option("TESORERIA","TESORERIA",false,false), 1);
					document.getElementById('area').options.add(new Option("CONTABILIDAD","CONTABILIDAD",false,false), 2);
					document.getElementById('area').options.add(new Option("RRHH","RRHH",false,false), 3);
					break;
				}
				case "10":{
					document.getElementById('area').options.add(new Option("DIRECCION EJECUTIVA","DIRECCION EJECUTIVA",true,true), 0);
					document.getElementById('area').options.add(new Option("SALA DE REUNIONES","SALA DE REUNIONES",false,false), 1);
					break;
				}
			}
		}

		function httpGet(theUrl){
			var xmlHttp = new XMLHttpRequest();
			xmlHttp.open( "GET", theUrl, false );
			xmlHttp.send( null );
			return xmlHttp.responseText;
		}

		function generarReporte(){
			var wb = XLSX.utils.book_new();
			wb.Props = {
				Title: "Reporte",
				Subject: "Atenciones",
				Author: "PSI",
				CreatedDate: new Date(2017,12,19)
			};
			wb.SheetNames.push("hoja 1");
			var ws_data = [];
			var fila = 0;
			ws_data[fila] = ["id", "time_begin", "time_claimed", "time_end", "created_by", "claimed_by", "user", "area", "floor", "type", "description", "state", "post_mortem"];
			fila++;
			db.collection("ticket").get()
			.then(function(querySnapshot) {
				querySnapshot.forEach(function(doc) {
					var tkrow = [];
					tkrow[0] = doc.id;
					tkrow[1] = fullDate(doc,"time_begin");
					tkrow[2] = fullDate(doc,"time_claimed");
					tkrow[3] = fullDate(doc,"time_end");
					tkrow[4] = doc.data()['created_by'];
					tkrow[5] = doc.data()['claimed_by'];
					tkrow[6] = doc.data()['user'];
					tkrow[7] = doc.data()['area'];
					tkrow[8] = doc.data()['floor'];
					tkrow[9] = doc.data()['type'];
					tkrow[10] = doc.data()['description'];
					tkrow[11] = doc.data()['state'];
					tkrow[12] = doc.data()['post_mortem'];
					ws_data[fila] = tkrow;
					fila++;
				});
				var ws = XLSX.utils.aoa_to_sheet(ws_data);
				wb.Sheets["hoja 1"] = ws;
				var wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});

				saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'reporte.xlsx');
			})
			.catch(function(error) {
				console.log("Error getting documents: ", error);
			});
		}

		function s2ab(s) {
			var buf = new ArrayBuffer(s.length);
			var view = new Uint8Array(buf);
			for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
				return buf;
		}
		function validateGenReport() {
			var txt;
			var person = prompt("Ingrese su nombre de usuario");
			if (person == null || person == "") {
				txt = "User cancelled the prompt.";
			} else {
				if(wpuser['alias'] == person) generarReporte();
			}
		}
		function clearBox(elementID)
		{
			document.getElementById(elementID).innerHTML = "";
		}

		function tkselected(item) {
			var auxid = $(item).attr("id").split('tk');
			var util_id = "tk."+auxid[1];
			if(meta_data[util_id]){
				var thisid = $(item).attr("id");
				var qryid1 = '#'+thisid+' .slider';
				var qryid2 = '#'+thisid+' .wrapper';
				document.querySelector(qryid1).classList.toggle('slideout');
				document.querySelector(qryid2).classList.toggle('wrapout');
			}else{
				return;
			}
		}

		function atender(tkid){
			var rightnow = new Date();
			db.collection("ticket").doc(tkid).update({
				state: "claimed",
				claimed_by: wpuser['alias'],
				time_claimed: rightnow
			})
			.then(function(){
				updateRoot();
			})
			.catch(function(error) {
				console.error("Error updating document: ", error);
			});
		}

		function cancelar(tkid){
			var rightnow = new Date();
			db.collection("ticket").doc(tkid).update({
				state: "cancelled",
				time_end: rightnow
			})
			.then(function(){
				updateRoot();
			})
			.catch(function(error) {
				console.error("Error updating document: ", error);
			});
		}

		function terminar(tkid){
			var rightnow = new Date();
			db.collection("ticket").doc(tkid).update({
				state: "closed",
				time_end: rightnow
			})
			.then(function(){
				updateRoot();
			})
			.catch(function(error) {
				console.error("Error updating document: ", error);
			});
		}

		function liberar(tkid){
			var rightnow = new Date();
			db.collection("ticket").doc(tkid).update({
				state: "pending",
				claimed_by: null,
				time_claimed: null
			})
			.then(function(){
				updateRoot();
			})
			.catch(function(error) {
				console.error("Error updating document: ", error);
			});
		}

		function updateRoot(){
			var d = new Date();
			var tmp = d.getTime();
			db.collection("root").doc("tk.0").update({
				last: tmp
			});
		}

	</script>
	
	<div class="form-style-2">
		<div class="form-style-2-heading">Solicitud de Asistencia Informática</div>
		<form action="" method="post">
			<div id=areausuario style = "display:none;">
				<label for="field1"><span style="width: 125px;">Usuario</span><input id="usuario" type="text" class="input-field" name="field1" onclick="redbox()" /></label>
			</div>
			<label for="field2"><span style="width: 125px;">Piso</span>
				<div id="donate">
					<label id="lblpiso1" class="flrbtn"><input type="radio" name="toggle" onClick="filter(this)" id="piso" value="1"><span>1</span></label>
					<label id="lblpiso2" class="flrbtn"><input type="radio" name="toggle" onClick="filter(this)" id="piso" value="2"><span>2</span></label>
					<label id="lblpiso3" class="flrbtn"><input type="radio" name="toggle" onClick="filter(this)" id="piso" value="3"><span>3</span></label>
					<label id="lblpiso4" class="flrbtn"><input type="radio" name="toggle" onClick="filter(this)" id="piso" value="4"><span>4</span></label>
					<label id="lblpiso5" class="flrbtn"><input type="radio" name="toggle" onClick="filter(this)" id="piso" value="5"><span>5</span></label>
					<br>
					<label id="lblpiso6" class="flrbtn"><input type="radio" name="toggle" onClick="filter(this)" id="piso" value="6"><span>6</span></label>
					<label id="lblpiso7" class="flrbtn"><input type="radio" name="toggle" onClick="filter(this)" id="piso" value="7"><span>7</span></label>
					<label id="lblpiso8" class="flrbtn"><input type="radio" name="toggle" onClick="filter(this)" id="piso" value="8"><span>8</span></label>
					<label id="lblpiso9" class="flrbtn"><input type="radio" name="toggle" onClick="filter(this)" id="piso" value="9"><span>9</span></label>
					<label id="lblpiso10" class="flrbtn"><input type="radio" name="toggle" onClick="filter(this)" id="piso" value="10"><span>10</span></label>
				</div>
			</label>
			<br><br><br><br><br>
			<label for="field4"><span style="width: 125px;">Área</span>
				<select name="field3" class="select-field" id="area">
					<option value="default">&lt;area&gt;</option>
				</select></label>
				<label for="field4"><span style="width: 125px;">Tipo de atención</span><select name="field4" class="select-field" id="tipo">
					<option value="Soporte">Soporte</option>
					<option value="Impresora">Impresora</option>
					<option value="SIAF">SIAF</option>
					<option value="SAPS">SAPS</option>
					<option value="SISGED">SISGED</option>
					<option value="Web">Web</option>
					<option value="Transparencia">Transparencia</option>
				</select></label>
				<label for="field5"><span style="width: 125px;">Descripción</span><textarea id="descripcion" name="field5" class="textarea-field"></textarea></label>

				<input id="submit" type="button" value="Solicitar" onClick="validate()"/>
			</form>
		</div>

		<button id="gen-report" style="display: none;" onclick="validateGenReport()">Generar Reporte</button>

		<div id="mytickets">
			
		</div>
		<div style="height:50px"></div>
</body>
</html>