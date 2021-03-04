// language class

function languageManager() {
	this.lang = "tr";
		
	this.load = function(lang) {
		this.lang = lang
		this.url = location.href.substring(0, location.href.lastIndexOf('/'));
		
		document.write("<script language='javascript' src='"+this.url+"/js/langs/"+this.lang+".js'></script>");
	}
	
	this.addIndexes= function() {
		for (var n in arguments[0]) { 
			this[n] = arguments[0][n]; 
		}
	}	
}