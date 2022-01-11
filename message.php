<?php

// get the name from cookie
$name = "";
if (isset($_COOKIE["name"])) {
    $name = $_COOKIE["name"];
}

print "<?xml version=\"1.0\" encoding=\"utf-8\"?>";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Message Page</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <script language="javascript" type="text/javascript">
        //<![CDATA[
        var loadTimer = null;
        var request;
        var datasize;
        var lastMsgID;
		
		

        function load() {
			
            var username = document.getElementById("username");
			
            if (username.value == "") {
                loadTimer = setTimeout("load()", 100);
                return;
            }
			
			//alert("message.php_load()");
            loadTimer = null;
            datasize = 0;
            lastMsgID = 0;

            var node = document.getElementById("chatroom");
            node.style.setProperty("visibility", "visible", null);

            getUpdate();
        }
		

        function unload() {
			//alert("message.php_unload()");
            var username = document.getElementById("username");
            if (username.value != "") {
                //request = new ActiveXObject("Microsoft.XMLHTTP");
                request =new XMLHttpRequest();
                request.open("POST", "logout.php", true);
                request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                request.send(null);
                username.value = "";
				
				// the above is for closing the browser
            }
            if (loadTimer != null) {
                loadTimer = null;
                clearTimeout("load()", 100);
            }
        }

        function getUpdate() {
			//alert("woow_getUpdate");
            //request = new ActiveXObject("Microsoft.XMLHTTP");
            request =new XMLHttpRequest();
            request.onreadystatechange = stateChange;
            request.open("POST", "server.php", true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send("datasize=" + datasize);
		}

        function stateChange() {
			//alert("woow_stateChange");
            if (request.readyState == 4 && request.status == 200 && request.responseText) { // 200 means no error, readyState means sth about handshake, meaning of things in this line can be found in internet
                //alert("inside_stateChange");
				var xmlDoc;
                try {
                    xmlDoc =new XMLHttpRequest();
                    xmlDoc.loadXML(request.responseText);
                } catch (e) {
                    var parser = new DOMParser();
                    xmlDoc = parser.parseFromString(request.responseText, "text/xml");
                }
				
				
                if(datasize != request.responseText.length)
				{updateChat(xmlDoc);} 
			
				//updateChat(xmlDoc); //this is for, should use the above?
				datasize = request.responseText.length; // ?did I do it correct?
				
                getUpdate();
            }
        }

        function updateChat(xmlDoc) {

            //point to the message nodes
            var messages = xmlDoc.getElementsByTagName("message");
			
			//var nameStr = messages[0].getAttribute("name");
			//alert(nameStr);
            // create a string for the messages
            /* Add your code here */
			//alert("woow_updateChat");
			//alert(messages.length);
			
			for(var i =0; i<messages.length; i++)
			{			

				var nameStr = messages[i].getAttribute("name");
				var contentStr = messages[i].firstChild.nodeValue;
				
				//var color = messages[i].getAttribute("color"); //perhaps this is not neccessary?

				
				
				//alert(nameStr);
				showMessage(nameStr, contentStr, messages[i].getAttribute("color")); // by self: not sure i or 1
				//the current situation is that the above line cause the problem: no output on the upper frame
				
				
				
				//alert(i);
				//	alert(messages.length);
					
				
				
				//var msg = messages.item(i);
				//showMessage(msg.getAttribute("name"), msg.textContent, msg.getAttribute("color"));
				
			} 
			lastMsgID = messages.length;
			
			
			var newHeight = (lastMsgID + 1)*20 + 35 // 35 is reserved space for title
			//the above is only for when it exceed 340 px
			
		if(newHeight<340) {newHeight = 340;}
		
		var svg_doc = document.getElementById("svg_doc");
		svg_doc.setAttribute("height", newHeight);
		
		var chatroom_bg = svg_doc.getElementById("chatroom_bg");
		chatroom_bg.setAttribute("height", newHeight);
		
		window.scrollTo(0, newHeight);

        }

		
        function showMessage(nameStr, contentStr, color){ 
               //alert("woow_showMessage");
                var node = document.getElementById("chattext");
                // Create the name text span
                var nameNode = document.createElementNS("http://www.w3.org/2000/svg", "tspan");

                // Set the attributes and create the text
                nameNode.setAttribute("x", 100);
                nameNode.setAttribute("dy", 20);
				nameNode.setAttribute("fill", color);
                nameNode.appendChild(document.createTextNode(nameStr));

                // Add the name to the text node
                node.appendChild(nameNode);

                // Create the score text span
        //        var contentNode = document.createElementNS("http://www.w3.org/2000/svg", "tspan");

		//		            // Replace all message that content hyperlinks with html links
        //    contentStr = contentStr.replace(/((http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?)/g,
        //        '<a target="blank" xlink:href="$1">$1</a>');
				
				//console.log(typeof contentStr);
				//console.log(contentStr);
				
        //        // Set the attributes and create the text
        //        contentNode.setAttribute("x", 200);
		//		//contentNode.setAttribute("dy", 20);
		//		contentNode.setAttribute("fill", color);
		//		//var p = document.createTextNode(contentStr);
		//		//p.innerHTML = contenStr;
		
		//		contentNode.appendChild(document.createTextNode(contentStr));
		// the above is the old one		
		//		contentNode.innerHTML = contentStr;	
				
                // Add the name to the text node
        //        node.appendChild(contentNode);
				
				
				// Create the score text span
                var contentNode = document.createElementNS("http://www.w3.org/2000/svg", "tspan");
				
				
				
				//https://stackoverflow.com/questions/38408663/hyperlink-in-tspan-svg-not-shown
				//https://stackoverflow.com/questions/38409915/how-to-add-hyperlinks-in-text-in-svg-with-javascript
				//the above two website is very useful for me
				var old_content_length = contentStr.length;
				// Replace all message that content hyperlinks with html links
            contentStr = contentStr.replace(/((http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?)/g,
                '<a target="blank" xlink:href="$1">$1</a>');
				var new_content_length = contentStr.length;
				
				if(old_content_length == new_content_length)
				{
					contentNode.setAttribute("x", 200);
					contentNode.setAttribute("fill", color);
					contentNode.innerHTML = contentStr;
					node.appendChild(contentNode);
				}
				else
				{
					contentNode.setAttribute("x", 200);
					contentNode.setAttribute("fill", color);
					contentNode.setAttribute("text-decoration", "underline" );
					contentNode.innerHTML = contentStr;
					node.appendChild(contentNode);	
					
					
				}
		
		
        }
		
		
		

        //]]>
        </script>
    </head>

    <body style="text-align: left" onload="load()" onunload="unload()">
	
	
    <svg width="800px" height="340px" id="svg_doc"
     xmlns="http://www.w3.org/2000/svg"
     xmlns:xhtml="http://www.w3.org/1999/xhtml"
     xmlns:xlink="http://www.w3.org/1999/xlink"
     xmlns:a="http://www.adobe.com/svg10-extensions" a:timeline="independent"
     >

        <g id="chatroom" style="visibility:hidden">    
				
        <rect id="chatroom_bg" width="520" height="340" style="fill:white;stroke:red;stroke-width:2"/>
        <text x="260" y="40" style="fill:red;font-size:30px;font-weight:bold;text-anchor:middle">Chat Window</text> 
        <text id="chattext" y="45" style="font-size: 20px;font-weight:bold"/> <!-- insert t span to it ???-->
      </g>
  </svg>
  
         <form action="">
            <input type="hidden" value="<?php print $name; ?>" id="username" />
        </form>

    </body>
</html>
