window.onload = function(){
	//document.getElementById("pic").innetHTML = "<p>Siia tuleb pilt!</p> \n";
	setRandPic();
}

function setRandPic(){
	//ajax, loome veebipäringu, määrame, mis juhtub, kui see edukalt tehtud saab ja saadud vastust kasutmane lehel javascripti abil sisu muutmiseks.
	let request = new XMLHttpRequest();
	request.onreadystatechange = function(){
		if(this.readState == 4 && this.status == 200){
			//järgmisena on asjad, mida javascript peab tulemusega tegema
			document.getElementById("pic").innetHTML = this.responseText;
		}
	}
	//siin määrate veebiaadressi ja parameetrid
	request.open("GET", "setrandompic.php", true);
	request.send();
	//ajax lõppeb
}