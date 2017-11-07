
chrome.runtime.onMessage.addListener(function(request, sender, sendResponse){
	console.log("Entered Content Script");
    if (request.method == "suggest"){	
    		json_obj=JSON.parse(request.content);
    		console.log(json_obj);
			for(var i=0;;i++)
    		{
    		if(!json_obj.errors[i])
    		{
    			var num=i-1;
    			break;
    		}
    		}
    		var script = document.createElement('script');
			script.type = 'text/javascript';
			script.src = 'chrome-extension://bhcpcmgjmcfcnhedmndnmpimcdgamnbm/remove.js';    	
			document.getElementsByTagName('head')[0].appendChild(script);
    		var body=document.querySelector("body");
    		var first=body.firstChild;
    		if(document.querySelector(".display")==null)
    		{var ele=document.createElement("div");
    		console.log(ele);
    		ele.setAttribute("class","display");
    		body.insertBefore(ele,first);}
    		else
    			ele=document.querySelector(".display");
    		if(json_obj.score==100)
    		{
    		ele.setAttribute("class","display right");
    		ele.innerHTML="<img id=\"close\" src=\"chrome-extension://bhcpcmgjmcfcnhedmndnmpimcdgamnbm/close.png\" onclick=\"remove(event)\"/><b>Grammar Nazi, you're perfectly right!</b>";
    		}
    		else{
    		ele.setAttribute("class","display wrong");
    		ele.innerHTML="<img id=\"close\" src=\"chrome-extension://bhcpcmgjmcfcnhedmndnmpimcdgamnbm/close.png\" onclick=\"remove(event)\"/><b>Hmm. Make the text error-free by writing<br><b>";
    		for(var i=0;i<=num;i++)
    		{
    		 ele.innerHTML+=("<br><b>"+json_obj.errors[i].bad+"</b> as ");
    		 for(var j=0;;j++)
    		 {
    		 	if(json_obj.errors[i].better[j]){
    		 		if(j==0)
    		 		ele.innerHTML+="\""+json_obj.errors[i].better[j]+"\"";
    		 		else
    		 		ele.innerHTML+=" or \""+json_obj.errors[i].better[j]+"\"";
    		 	}
    		 	else 
    		 		break;
    		 }

    		}
    	}
    }
});