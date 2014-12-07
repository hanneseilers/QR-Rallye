function hideElement(element){
	document.getElementById(element).style.display = "none";
}

function showElement(element){
	hideAllElements();
	document.getElementById(element).style.display = "block";
}

function hideAllElements(){
	// hide content elements
	var vChilds = document.getElementById("content").childNodes;
	for( var n=0; n < vChilds.length; n++ ){
		if( vChilds[n].tagName == "DIV" ){
			vChilds[n].style.display = "none";
		}
	}
}

function hideInfo(){
	// hide info element when no content
	var vInfoElements = document.getElementsByClassName("info")
	for( var n=0; n < vInfoElements.length; n++ ){
		if( vInfoElements[n].innerHTML.length < 5 ){
			vInfoElements[n].style.display = "none";
		} else {
			vInfoElements[n].style.display = "block";
		}
	}
}