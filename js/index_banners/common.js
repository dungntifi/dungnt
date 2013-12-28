if(!Node.prependChild)
{
	Node.prototype.prependChild = function (newChild) 
	{
		if(this.firstChild) 
		{
			this.insertBefore(newChild,this.firstChild);
		} 
		else 
		{
			this.appendChild(newChild);
		}
		return this;
	}
}

//To get the value of a variable in query url string
function getQueryStringParameterByName(name) {
	var href = (arguments[1]) ? arguments[1] : window.location.href ;
	name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
	var regexS = "[\\?&]" + name + "=([^&#]*)";
	var regex = new RegExp(regexS);
	var results = regex.exec(href);
	if (results == null)
		return "";
	else
		return decodeURIComponent(results[1].replace(/\+/g, " "));
}


function stripCDATA(html)
{
	
	html = jQuery.trim( html );
	
	html = html.substr(9, html.length-3); 
	html = html.replace("&lt;![CDATA[&#xD;", "").replace("]]&gt;", "");
	return html.replace("<![CDATA[", "").replace("]]>", "");
	

	//return html.replace("<![CDATA[", "").replace("]]>", "");
}