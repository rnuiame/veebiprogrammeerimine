let modal;
let modalImg;
let captionText;
let closeBtn;
let photoDir = "../../vp_pic_uploads/";
let modalId;

window.onload = function(){
	modal = document.getElementById("myModal");
	modalImg = document.getElementById("modalImg");
	captionText = document.getElementById("caption");
	closeBtn = document.getElementsByClassName("close")[0];
	let allThumbs = document.getElementById("gallery").getElementsByTagName("img");
	let thumbCount = allThumbs.length;
	for(let i = 0; i < thumbCount; i ++){
		allThumbs[i].addEventListener("click", openModal);
	}
	closeBtn.addEventListener("click", closeModal);
	modalImg.addEventListener("click", closeModal);
}

function openModal(e){
	modal.style.display = "block";
	modalImg.src = photoDir + e.target.dataset.fn;
	modalId = e.target.dataset.id;
	captionText = innerHTML = e.target.alt;
	document.getElementById("storeRating").addEventListener("click", storeRating);
}

function storeRating(){
	let rating = 0;
	for(let i = 1; i < 6; i ++){
		if(document.getElementById("rate"+i))
	}
}

function closeModal(){
	modal.style.display = "none";
}