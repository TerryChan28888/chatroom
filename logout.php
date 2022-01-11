<?php 

require_once('xmlHandler.php');

if (!isset($_COOKIE["name"])) {
    header("Location: error.html");
    exit;
}

// create the chatroom xml file handler
$xmlh = new xmlHandler("chatroom.xml");
if (!$xmlh->fileExist()) {
    header("Location: error.html");
    exit;
}
 
 setcookie("name", "");
// $xmlh->openFile();
// $user = $xmlh->getElement("user");
// $childNodeList = $user->childNodes;
// while($childNodeList->count() > 0) //, if doesn't work, try this
//	 while(sizeOf($childNodeList) > 0)
// {
//	 $child = $childNodeList->item(0);
//	 $xmlh->removeElement($user, $child);
// }
 
//  $message = $xmlh->getElement("message");
//  $childNodeList = $message->childNodes;
//  while($childNodeList->count() > 0) // if doesn't work, try this
 //while(sizeOf($childNodeList) > 0)
// {
//	 $child = $childNodeList->item(0);
//	 $xmlh->removeElement($message, $child);
// }

 
 
// $xmlh->saveFile();
 //echo"<script> alert('I did this!!') </script>";
 echo"<script>window.parent.location.reload()</script>";

?>
