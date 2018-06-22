<?php
header('Content-Type: application/json');
//include('util/connect.php');
$conn = mysqli_connect("119.81.153.170", "root", "supervisor","erc", 3306);
$html='';

$sql="select t1.POMasterID,t1.PODate, t1.DueDate,t1.Remarks,t1.RequestedBy,
t2.Qty,t2.Particulars,t2.Price,t2.Qty * t2.Price as Amount
from ".$_REQUEST['table']." t1
 left join ".$_REQUEST['relatedtable']." t2 ON t2.POMasterID=t1.POMasterID
 order by DueDate desc,PODate desc limit 100";



if ($result = mysqli_query($conn, $sql)) {
    
    while($row = mysqli_fetch_array($result)){
		 $html[]=$row;
	}
	mysqli_free_result($result);
}	
else
{
	$html='';
}

	
print json_encode($html);
?>    
               