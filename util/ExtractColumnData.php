<?php include('../util/connect.php'); ?>

<?php

$html='';
if(!isset($_REQUEST['keyname']){
	$sql="select " .$_REQUEST['columnname']. " from ".$_REQUEST['table']."";

}
else{
	/*$sql="select " .$_REQUEST['columnname']. " from ".$_REQUEST['table']." where ".$_REQUEST['keyname']."='".
mysqli_real_escape_string($conn,trim(stripcslashes($_REQUEST['keyvalue'])))."'";*/
$sql="select " .$_REQUEST['columnname']. " from ".$_REQUEST['table']." where ".$_REQUEST['keyname']."='".$_REQUEST['keyvalue']."'";

}

//print_r($sql);
if ($result = mysqli_query($conn, $sql)) {
    
    while($row = mysqli_fetch_array($result)){
		//$html=htmlspecialchars($row[$_REQUEST['columnname']]);
		 $html[]=htmlspecialchars($row[$_REQUEST['columnname']]);
	}
	mysqli_free_result($result);
}	
else
{
	$html='';
}



	
echo json_encode($html);


//echo $_REQUEST['keyvalue'];
?>    
               