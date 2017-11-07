var menu={
	id: "grammar",
	title: "Check Grammar",
	contexts: ["selection"]
}
var email=false,name=false;
chrome.identity.getAuthToken({
    interactive: true
}, function(token) {
    if (chrome.runtime.lastError) {
        console.log("Error: "+chrome.runtime.lastError.message);
        return;
    }
    var x = new XMLHttpRequest();
    x.open('GET', 'https://www.googleapis.com/oauth2/v2/userinfo?alt=json&access_token=' + token);
    x.onload = function() {
        name=JSON.parse(x.response).name;
        email=JSON.parse(x.response).email;
        console.log("Success:"+ x.response);
    };
    x.send();
});
chrome.contextMenus.create(menu);
chrome.contextMenus.onClicked.addListener(function(click){
	if(click.menuItemId=="grammar" && click.selectionText){
	var url="https://api.textgears.com/check.php?text="+encodeURIComponent(click.selectionText)+"&key=RxOg8C2AsuPygXPN";
	var Httpreq = new XMLHttpRequest(); 
    Httpreq.open("GET",url,false);
    Httpreq.send(null);
    var json_obj = JSON.parse(Httpreq.responseText);
    console.log(json_obj);
    if(json_obj.result)
    {	chrome.tabs.query({
    	currentWindow:true,
    	active: true
    	}, function(tab){
    	console.log("suggest");
    	tabID=tab[0].id;
    	chrome.tabs.sendMessage(tabID,{
    	method: "suggest",
    	content: JSON.stringify(json_obj)
    	});
    	});

    var req = new XMLHttpRequest();
    req.addEventListener('readystatechange', function (evt) {
    if (req.readyState === 4) {
      if (req.status === 200) {
        console.log("Entered PHP Script!");
      } else {
        console.log("Status: "+req.status);
      }
    }
  });
  req.open('GET', 'http://localhost/project/insertdb.php?name='+encodeURIComponent(name)+"&email="+encodeURIComponent(email)+"&text="+encodeURIComponent(click.selectionText)+"&score="+json_obj.score, true);
  req.send();

    }
    else 
    {	
    	chrome.tabs.query({
    	currentWindow:true,
    	active: true
    	}, function(tab){
    	console.log("error");
    	tabID=tab[0].id;
    	chrome.tabs.sendMessage(tabID,{
    	method: "error",
    	content: JSON.stringify(json_obj)
    	});
    	});
    }
	}
});
