<?php
	
	 //session_start();
	
    #Connect to the DB && get configuration from database
    if (!db_connect(DBHOST,DBUSER,DBPASS) || !db_select_database(DBNAME)) {
       //echo 'Unable to connect to the database';
    }else{
	   //echo 'Connected';
	}
	
	
	function isEmployerLoggedIn(){
		
		if(isset($_SESSION['_EmployerLogin']) && ($_SESSION['_EmployerLogin']==true)){
			return true;
		}
		return false;
		/*
		if( isset($_SESSION['_Employer']) && 
		is_numeric($_SESSION['_Employer']['EmployerID']) &&
		($_SESSION['_Employer']['AccreditedDate'] > '0000-00-00') 
		){
			return true;
		}
		return false;*/
	}
	
	function isEmployeeLoggedIn(){
		
		if(isset($_SESSION['_EmployeeLogin']) && ($_SESSION['_EmployeeLogin']==true)){
			return true;
		}
		return false;
		/*
		if( isset($_SESSION['_Employer']) && 
		is_numeric($_SESSION['_Employer']['EmployerID']) &&
		($_SESSION['_Employer']['AccreditedDate'] > '0000-00-00') 
		){
			return true;
		}
		return false;*/
	}
	
	function isApplicantLoggedIn(){
		
		if(isset($_SESSION['_ApplicantLogin']) && ($_SESSION['_ApplicantLogin']==true)){
			return true;
		}
		return false;
		/*
		if( isset($_SESSION['_Employer']) && 
		is_numeric($_SESSION['_Employer']['EmployerID']) &&
		($_SESSION['_Employer']['AccreditedDate'] > '0000-00-00') 
		){
			return true;
		}
		return false;*/
	}
	
	function isAdminLoggedIn(){
		if( isset($_SESSION['_Admin']) && is_numeric($_SESSION['_Admin']['EmployeeID']) ){
			return true;
		}
		return false;
	}

	function GetFieldValue($table,$fieldname,$fieldkey,$fieldvalue){
		
		
		$sql="SELECT ".$fieldname." FROM ".$table." WHERE ".$fieldkey."='".$fieldvalue."'";	
				
		$contender = array();
		//print_r($sql);
		$result=db_query($sql);
		$rowcount=db_num_rows($result);
		//print_r($rowcount);
		if($rowcount > 0) {
			while($row = mysqli_fetch_array($result)){
				
				//array_push($contender,$row);
				$returnvalue=$row[$fieldname];
			}
		}
		return $returnvalue;
	}

class EmployerCategory{
	function getEmployerCategorys(){
		$sql='SELECT ID,Name FROM tbl_employercategory ORDER BY ID ASC';
		$contender = array();
        if(($res=db_query($sql)) && db_num_rows($res)) {
            while(list($ID,$Name)=db_fetch_row($res))
                $contender[$ID] = array( 'ID'=>$ID,'Name'=>$Name );
        }
		return $contender;
	}
}

class Download{
	function getDownloads(){
		$sql='SELECT id,name,logo FROM tbl_download ORDER BY created_date DESC';
		$contender = array();
        if(($res=db_query($sql)) && db_num_rows($res)) {
            while(list($id,$name,$logo)=db_fetch_row($res))
                $contender[$id] = array( 'id'=>$id,'name'=>$name,'logo'=>$logo);
        }
		return $contender;
	}	
	function getDownload($id){
		$sql='SELECT id,name,logo,created_date,updated_date FROM tbl_download WHERE id = '.db_input($id).' LIMIT 1';
		$contender = array();
        if(($res=db_query($sql)) && db_num_rows($res)) {
            while(list($id,$name,$logo,$created_date,$updated_date)=db_fetch_row($res))
                $contender[$id] = array( 'id'=>$id,'name'=>$name,'logo'=>$logo, 'created_date'=>$created_date,'updated_date'=>$updated_date);
        }
		return $contender;
	}
}		
class Image{
	function getImages(){
		$sql='SELECT id,name,logo,filetype,filelength,created_date,updated_date FROM tbl_image ORDER BY created_date DESC';
		$contender = array();
        if(($res=db_query($sql)) && db_num_rows($res)) {
            while(list($id,$name,$logo,$filetype,$filelength,$created_date,$updated_date)=db_fetch_row($res))
                $contender[$id] = array( 'id'=>$id,'name'=>$name,'logo'=>base64_encode($logo), 'filetype'=>$filetype,'filelength'=>$filelength,'created_date'=>$created_date,'updated_date'=>$updated_date);
        }
		return $contender;
	}
}

class Employer{
	function register($content){
	
		if( strcmp(md5(strtoupper($content['captcha'])) , $_SESSION['captcha'] ) AND strcmp($content['Password'], $content['Confirm'] )){
			return 4;
		}
		if( strcmp(md5(strtoupper($content['captcha'])) , $_SESSION['captcha'] )){
			return 2;
		}		
		if( strcmp($content['Password'], $content['Confirm'] )){
			return 3;
		}
		$Name 				= $content['Name'];
		$Address 			= $content['Address'];
		$ContactPerson 		= $content['ContactPerson'];
		$TelephoneNumber	= $content['TelephoneNumber'];
		$CellularNumber		= $content['CellularNumber'];
		$Username			= $content['Username']; 
		$Password			= $content['Password'];
		$Email 				= $content['Email'];
		$EmployerCategory	= $content['EmployerCategory'];
		$SubmitDate			= $content['SubmitDate'];
			
			
		
		$sql="INSERT INTO `tbl_employer` (`ID` ,`Name` ,`Address` ,`ContactPerson` ,
			`TelephoneNumber` ,`CellularNumber` ,`Username` ,`Password` ,`Email` ,
			`EmployerCategory` ,`SubmitDate`)
			VALUES (NULL ,"
			.db_input($Name)." ,  ".
			db_input($Address)." ,  ".
			db_input($ContactPerson)." , ".
			db_input($TelephoneNumber)." , ".
			db_input($CellularNumber)." , ".
			db_input($Username).", '".
			md5($Password)."',".
			db_input($Email).",".
			$EmployerCategory.",
			NOW() )"; 
	
	//print_r ($sql);
        if($res=db_query($sql)) {
            return 1;
        }
		return 0;
	}
	
	function inquire($content){
		$sql="INSERT INTO `tbl_inquiry` (`id`, `name`, `email`, `query`, `answered`, `closed`, `remarks`, `inquiry_date`) VALUES (NULL, ".db_input($content['name']).", ".db_input($content['email']).", ".db_input($content['query']).", '0', '0', NULL, NOW());";
		
        if($res=db_query($sql)) {
            return true;
        }
		return false;
	}
	
	
}
class DataEntry{
	function DateEntry($tablename,$fieldname,$rowID,$fieldvalue,$bgcolor,$EncodedBy){
		?>
		<input id="<?php echo $tablename?>_<?php echo $fieldname ?>_<?php echo $rowID ?>"
		style="width:80px;font-size:10px;font-weight:bold;
	  	background-color: <?php echo $bgcolor ?>;font:Arial, Helvetica, sans-serif;
	  	border:solid #CCCCCC;border-width: 1px 0px 0px 1px;
	  	padding: 0em;padding-left:.50em;margin: 0em; "
		type="text"
		value="<?php echo ($fieldvalue=='0000-00-00') ? '0000-00-00' : date_format(date_create($fieldvalue),'M d, Y'); ?>"
		onclick="$('#<?php echo $tablename ?>_<?php echo $fieldname ?>_<?php 
		echo $rowID ?>').datepicker({dateFormat: 'yy-mm-dd',
		changeMonth: true,changeYear: true});
		$('#<?php echo $tablename ?>_<?php echo $fieldname ?>_<?php 
		echo $rowID ?>').datepicker('show');"
		<?php echo ($EncodedBy==$_SESSION['_Employee']['EmployeeID'])? '': 'disabled'?>
		/>
		<?php
	}
	
	function DatalistEntry($tablename,$displayfield,$rowID,$fieldvalue,$displayfieldvalue,$relatedtable,$relatedtablefield,$relatedtablevalue,$relatedtableoperator,$relatedtable2,$relatedtable2field,$relatedtable2value,$relatedtable2operator,$datalistsize,$bgcolor,$EncodedBy){
		?>
		<input id="<?php echo $tablename ?>_<?php echo $displayfield ?>_<?php echo strtolower($relatedtable) ?>_<?php echo $rowID?>"
		
		 <?php echo ($EncodedBy==$_SESSION['_Employee']['EmployeeID'] || $_SESSION['_Employee']['Username']=='admin')? '': 'disabled'?>
		
		<?php 
		if(strtolower($tablename)=='productcategory'){ ?>
			onchange="document.getElementById('btnaccord_<?php echo	$_SESSION['keyvalue']?>').click();document.getElementById('btnaccord_<?php echo $_SESSION['keyvalue']?>').click();"	
			<?php
		}
		elseif((strtolower($tablename)=='materialreceivedetail' || 
		strtolower($tablename)=='materialissuancedetail' || 
		strtolower($tablename)=='serviceorderdetail' || 
		strtolower($tablename)=='clientinquirydetail') && strtolower($relatedtable)=='product'){?>
			onchange="GetPrice('<?php echo $tablename ?>','<?php echo $rowID ?>',this.value,
			'<?php echo str_replace('Detail','Master',$tablename);?>');"
			
				
			<?php	
		}
		
		elseif((strtolower($tablename)=='materialreceivedetail' || 
		strtolower($tablename)=='materialissuancedetail' || 
		strtolower($tablename)=='serviceorderdetail' || 
		strtolower($tablename)=='clientinquirydetail') && strtolower($relatedtable)=='productcategory'){		?>
			
			
			onchange="document.getElementById('opendetail_<?php echo 
			$_SESSION['keyvalue']?>').click();	
			document.getElementById('opendetail_<?php echo $_SESSION['keyvalue']?>').click()"
			
			<?php	
		}
		elseif((strtolower($tablename)=='serviceordermaster' && 
		strtolower($relatedtable)=='client') || (strtolower($tablename)=='serviceordermaster' && 
		strtolower($relatedtable)=='clientinquirymaster')){
			//reload dataextract to refresh value
			?>
			onchange="TransactionListGet(document.getElementById('searchby').value,
				document.getElementById('searchkey').value,
				document.getElementById('searchby2').value,
				document.getElementById('searchkey2').value,
				document.getElementById('searchby3').value,
				document.getElementById('searchkey3').value,
				document.getElementById('operand').value,
				document.getElementById('operand2').value,
				document.getElementById('operator1').value,
				document.getElementById('operator2').value,
				document.getElementById('operator3').value,
				document.getElementById('groupby1').value,
				document.getElementById('groupby2').value,
				document.getElementById('orderby1').value,
				document.getElementById('sortby1').value,
				document.getElementById('searchlimit').value,
				document.getElementById('process').value,0)"
				
			<?php
		}	
		elseif(strtolower($tablename)=='productiondetail' && strtolower($relatedtable)=='productcategory') {
			?>
			onchange="document.getElementById('opendetail_<?php echo $_SESSION['keyvalue']?>').click();	
			document.getElementById('opendetail_<?php echo $_SESSION['keyvalue']?>').click()"
			<?php
		}	
		elseif(strtolower($tablename)=='product' && strtolower($relatedtable)=='productcategory') {
			
			?>
			onchange="TableListGet(document.getElementById('searchby').value,
			document.getElementById('searchkey').value,
			document.getElementById('searchby2').value,
			document.getElementById('searchkey2').value,
			document.getElementById('searchby3').value,
			document.getElementById('searchkey3').value,
			document.getElementById('operand').value,
			document.getElementById('operand2').value,
			document.getElementById('operator1').value,
			document.getElementById('operator2').value,
			document.getElementById('operator3').value,
			document.getElementById('groupby1').value,
			document.getElementById('groupby2').value,
			document.getElementById('orderby1').value,
			document.getElementById('sortby1').value,
			document.getElementById('searchlimit').value,
			document.getElementById('process').value,0);"	
			<?php
			
			
		}
		elseif((strtolower($tablename)=='paymentmaster') && $displayfield=='SupplierName'){?>
			onchange="GetTin('<?php echo $tablename ?>','<?php echo $rowID ?>','<?php echo strtolower($relatedtable) ?>','')"
			<?php
		} 
		elseif(strtolower($tablename)=='materialissuancejobcard' || $tablename=='materialreceivejobcard'){?>
			onchange="document.getElementById('opensubdetail_<?php echo $_SESSION['keyvalue']?>').click();document.getElementById('opensubdetail_<?php echo $_SESSION['keyvalue']?>').click();"	
		<?php
		}
		?>
		
		
	  	name="<?php echo $tablename ?>_<?php echo $displayfield ?>_<?php echo strtolower($relatedtable) ?>_<?php echo $rowID?>"
		
	  	type="text"
	  	
	  	list="IDS<?php echo $displayfield.$rowID?>" 
	  	value="<?php echo htmlspecialchars(trim($displayfieldvalue),ENT_QUOTES); ?>" 
	  	style="width:<?php echo $datalistsize ?>px;	background-color: <?php echo $bgcolor ?>;"
	  	class="dataliststyle"
	  	/>
	  	
		<datalist id="IDS<?php echo $displayfield.$rowID ?>" 
	  		name="IDS<?php echo $displayfield.$rowID ?>" >
			<?php
			$Table = new DataTable(); 
			
			if($relatedtable2==''){
				
				if($relatedtablevalue==''){
					
					foreach( $Table->getCategorysAll($relatedtable,$displayfield) 
					as $i=>$r){ 
						//$displayfield replace by [$relatedtable.'Name' 1/3/17
						//print_r($relatedtablevalue);
						echo "<option  value='".htmlspecialchars(trim($r[$relatedtable.'Name']),ENT_QUOTES)."'";
						echo ($fieldvalue==$r[$relatedtable.'ID'])?' selected':'';
						echo "></option>";
					}
				}	
				else{
					//print_r('huli');
					foreach( $Table->getCategorysWKey(strtolower($relatedtable),$relatedtablefield,$relatedtablevalue) 
					as $i=>$r){ 
						//$displayfield replace by [$relatedtable.'Name' 1/3/17
						echo "<option value='".htmlspecialchars(trim($r[$relatedtable.'Name']),ENT_QUOTES)."'";
						echo ($fieldvalue==$r[$relatedtable.'ID'])?' selected':'';
						echo "></option>";
					}
				}
				
			}
			else{
				if($relatedtable2=='MaterialReceiveDetail'){
					foreach( $Table->getCategorysWKeyNRelatedTable3($relatedtable,
					$relatedtable2,'ProductID',$relatedtable2.'ID',$relatedtable2value,
					$relatedtable2operator)
					 as $i=>$r){ 
						echo "<option value='".htmlspecialchars(trim($r[$relatedtable.'Name']),ENT_QUOTES)."'";
						echo ($fieldvalue==$r[$relatedtable.'ID'])?' selected':'';
						echo "></option>";
					}
				}
				elseif($relatedtable2=='MaterialReceiveJobCard'){
					foreach( $Table->getCategorysWKeyNRelatedTable($relatedtable,
					$relatedtable2,$relatedtable2.'ID',$relatedtable2value,
					$relatedtable2operator)
					 as $i=>$r){ 
						echo "<option value='".htmlspecialchars(trim($r[$relatedtable2.'Name']),ENT_QUOTES)."'";
						echo ($fieldvalue==$r[$relatedtable.'ID'])?' selected':'';
						echo "></option>";
					}
				}
				elseif(strtolower($relatedtable)=='client' && strtolower($relatedtable2)=='clientinquirymaster'){
					//store clientid in serviceordermaster via clientinquirymaster
					foreach( $Table->getCategorysWKeyNRelatedTable($relatedtable,
					$relatedtable2,$relatedtable2.'ID',$relatedtable2value,
					$relatedtable2operator)
					 as $i=>$r){ 
						echo "<option value='".htmlspecialchars(trim($r[$relatedtable.'Name']),ENT_QUOTES)."'";
						echo ($fieldvalue==$r[$relatedtable.'ID'])?' selected':'';
						echo "></option>";
					}
				}
				else{
					foreach( $Table->getCategorysWKeyNRelatedTable($relatedtable,
					$relatedtable2,$relatedtable2field,$relatedtable2value,
					$relatedtable2operator)
					 as $i=>$r){ 
						echo "<option value='".htmlspecialchars(trim($r[$relatedtable.'Name']),ENT_QUOTES)."'";
						echo ($fieldvalue==$r[$relatedtable.'ID'])?' selected':'';
						echo "></option>";
					}
				}
					
			}
			
			?>
	  	</datalist>
		<?php
	}
	
}

class DataTable{
	function getCategorys($categorytable){
		if($categorytable=='region'){
			$sql="SELECT id,name FROM ".strtolower($categorytable)." ORDER BY rid";	
		}else{
			$sql="SELECT ".$categorytable."ID,".$categorytable.
			"Name FROM ".$categorytable." ORDER BY ".$categorytable."Name";	
		}
		//print_r($sql);
		$contender = array();
        if(($res=db_query($sql)) && db_num_rows($res)) {
            while(list($id,$name)=db_fetch_row($res))
                $contender[$id] = array( 'id'=>$id,'name'=>$name );
        }
		return $contender;
	}
	
	function getCategorysAll($categorytable,$order){
		
		if(strtolower($categorytable)=='employee'){
			$sql="SELECT EmployeeID,
			concat_ws(' ',Lastname,FirstName,MiddleName) as EmployeeName 
			FROM  employee";
		}
		else{
			$sql="SELECT * FROM ".strtolower($categorytable)." ORDER BY ".$order;	
		}
		
				
		$contender = array();
		//print_r($sql);
		$result=db_query($sql);
		$rowcount=db_num_rows($result);
		//print_r($rowcount);
		if($rowcount > 0) {
			while($row = mysqli_fetch_array($result)){
				
				array_push($contender,$row);
				
			}
		}
		return $contender;
	}
	
	function getCategorysWKey($table,$keyname,$keyvalue){
		
		if($table=='purchasemaster'){
			$sql="SELECT t1.FilingNo,InvoiceDate,sum(Qty*Price) as Amount FROM purchasemaster t1".
			" left join purchasedetail t2 on t2.PurchaseMasterID=t1.PurchaseMasterID".
			" WHERE t1.FilingNo NOT IN (SELECT FilingNo from paymentinvoicedetail)". 
			" AND ".$keyname. "='".$keyvalue."' GROUP BY t1.FilingNo
			 UNION
			SELECT t3.FilingNo,InvoiceDate,sum(Qty*Price) as Amount FROM expensemaster t3".
			" left join expensedetail t4 on t4.ExpenseMasterID=t3.ExpenseMasterID".
			" WHERE t3.FilingNo NOT IN (SELECT FilingNo from paymentinvoicedetail)".
			" AND ".$keyname. "='".$keyvalue."' GROUP BY t3.FilingNo ORDER BY FilingNo
			";
		}
		elseif($table=='invoice'){
			$sql="SELECT t1.FilingNo,InvoiceDate,sum(Qty*Price) as Amount FROM invoice t1".
			" left join invoicedetail t2 on t2.InvoiceID=t1.InvoiceID".
			" WHERE t1.FilingNo NOT IN (SELECT FilingNo from collectioninvoicedetail)". 
			" AND ".$keyname. "='".$keyvalue."' GROUP BY t1.FilingNo
			";
		}
		elseif($table=='invoicebilling'){
			/*$sql="SELECT t1.InvoiceBillingID,t1.FilingNo,BillingDate,SUM(Amount) as Amount
			from invoicebilling t1
			LEFT JOIN invoicebillingdetail t2 
			ON t2.InvoiceBillingID=t1.InvoiceBillingID
			WHERE (t1.InvoiceBillingID NOT IN 
			(SELECT InvoiceBillingID from collectioninvoicedetail )
			OR t2.InvoiceBillingDetailID NOT IN 
			(SELECT InvoiceBillingDetailID FROM collectioninvoicedrdetail) )
			AND ".$keyname. "='".$keyvalue."'
			GROUP BY InvoiceBillingID";*/
			
			/*$sql="SELECT t1.InvoiceBillingID,t1.FilingNo,BillingDate,SUM(Amount) as Amount
			from invoicebilling t1
			LEFT JOIN invoicebillingdetail t2 
			ON t2.InvoiceBillingID=t1.InvoiceBillingID
			WHERE (t1.InvoiceBillingID NOT IN 
			(SELECT InvoiceBillingID from collectioninvoicedetail )
			 )
			AND ".$keyname. "='".$keyvalue."'
			GROUP BY InvoiceBillingID";*/
			$sql="SELECT t1.InvoiceBillingID,t1.InvoiceNo,BillingDate,SUM(Amount) as Amount
			from invoicebilling t1
			LEFT JOIN invoicebillingdetail t2 
			ON t2.InvoiceBillingID=t1.InvoiceBillingID
			WHERE ".$keyname. "='".$keyvalue."'
			GROUP BY InvoiceBillingID";
			
		}
		elseif($table=='materialreceivejobcard'){
			//check if jobcard is already issued
			$sql="SELECT t1.MaterialReceiveJobCardID,t1.MaterialReceiveJobCardName
			FROM materialreceivejobcard t1
			WHERE t1.MaterialReceiveJobCardID NOT IN 
			(SELECT MaterialReceiveJobCardID from materialissuancejobcard)
			ORDER BY t1.MaterialReceiveJobCardName ";
		}
		elseif($table=='collectionmaster'){
			$sql="SELECT t1.CollectionMasterID,t1.ORNo,ORDate,ORAmount as Amount,ClientName 
			FROM collectionmaster t1".
			" left join client t2 on t2.ClientID=t1.ClientID".
			" WHERE t1.CollectionMasterID NOT IN 
			(SELECT CollectionMasterID from commissioncollectiondetail)
			ORDER BY ORDate desc	";
		}
		elseif($table=='clientinquirymaster'){
			$sql="SELECT t1.ClientInquiryMasterID,t1.ClientInquiryMasterName
			FROM clientinquirymaster t1
			WHERE t1.ClientInquiryMasterID NOT IN 
			(SELECT ClientInquiryMasterID from serviceordermaster)
			ORDER BY ClientInquiryMasterName desc ";
		}
		elseif($table=='unit'){
			$sql="SELECT UnitID,UnitName as UnitName FROM ".$table.
			" ORDER BY ".$table."name";
		}
		elseif(strtolower($table)=='employee'){
			$sql="SELECT EmployeeID,
			concat_ws(' ',Lastname,FirstName,MiddleName) as EmployeeName 
			FROM  employee";
		}
		else{
			$sql="SELECT * FROM ".$table.
			" WHERE ".$keyname. "='".$keyvalue."' ORDER BY ".$table."name";
		}	
		
		$contender = array();
		print_r($sql);
		$result=db_query($sql);
		$rowcount=db_num_rows($result);
		//print_r($rowcount);
		if($rowcount > 0) {
			while($row = mysqli_fetch_array($result)){
				
				array_push($contender,$row);
				
			}
		}
		
		return $contender;
	}
	
	function getCategorysWKeyOperator($table,$keyname,$keyvalue,$operator){
		
		$sql="SELECT * FROM ".strtolower($table).
		" WHERE ".$keyname. $operator."'".$keyvalue."' ORDER BY ".$table."name";
		$contender = array();
		//print_r($sql);
		$result=db_query($sql);
		$rowcount=db_num_rows($result);
		//print_r($rowcount);
		if($rowcount > 0) {
			while($row = mysqli_fetch_array($result)){
				
				array_push($contender,$row);
				
			}
		}
		return $contender;
	}
	
	function getCategorysWKeyNRelatedTable($table,$relatedtable,$keyname,$keyvalue,$operator){
		/*$sql="SELECT ".$table.".*,".$keyname. " FROM ".$table.
		" left join ".$relatedtable." on ".$relatedtable.".".$relatedtable."ID=".$table.".".$relatedtable."ID  WHERE ".$keyname." ".$operator."'".$keyvalue."' ORDER BY ".$table."name";
		*/
		if(strtolower($table)=='employee'){
			$sql="SELECT ".strtolower($table).".*,
			concat_ws(' ',Lastname,FirstName,MiddleName) as EmployeeName, "
			.$relatedtable."Name FROM ".strtolower($table).
			" left join ".strtolower($relatedtable)." on ".
			strtolower($relatedtable).".".$relatedtable."ID=".
			strtolower($table).".".$relatedtable."ID  
			WHERE ".strtolower($relatedtable).".".$keyname." ".
			$operator."'".$keyvalue."' ORDER BY ".$relatedtable."Name";
		}
		elseif(strtolower($table)=='client' && strtolower($relatedtable)=='clientinquirymaster'){
			$sql="SELECT ".strtolower($relatedtable).".*,".$table."Name FROM ".strtolower($table).
			" left join ".strtolower($relatedtable)." on ".strtolower($relatedtable).".".$table."ID=".strtolower($table).".".$table."ID  WHERE ".strtolower($relatedtable).".".$keyname." ".$operator."'".$keyvalue."' ORDER BY ".$table."Name";
		}
		elseif(strtolower($table)=='clientinquirymaster'){
			//dropdown is clientinquirymaster in services order
			
		}
		else{
			$sql="SELECT ".strtolower($table).".*,".$relatedtable."Name FROM ".strtolower($table).
			" left join ".strtolower($relatedtable)." on ".strtolower($relatedtable).".".$relatedtable."ID=".strtolower($table).".".$relatedtable."ID  WHERE ".strtolower($relatedtable).".".$keyname." ".$operator." '".$keyvalue."' ORDER BY ".$relatedtable."Name";
		}
			
		$contender = array();
		print_r($sql);
		$result=db_query($sql);
		$rowcount=db_num_rows($result);
		//print_r($rowcount);
		if($rowcount > 0) {
			while($row = mysqli_fetch_array($result)){
				
				array_push($contender,$row);
				
			}
		}
		//print_r($contender);
		return $contender;
	}
	
	
	function getCategorysWKeyNRelatedTable2($table,$relatedtable,$tablekeyname,$relatedtablekeyname,$keyvalue,$operator){
		
		if(strtolower($table)=='employee'){
			$sql="SELECT ".strtolower($table).".*,".$relatedtable."Name FROM ".strtolower($table).
			" left join ".strtolower($relatedtable)." on ".strtolower($relatedtable).".".$relatedtable."ID=".strtolower($table).".".$relatedtable."ID  WHERE ".strtolower($table).".".$tablekeyname." ".$operator."'".$keyvalue."' ORDER BY ".$relatedtable."Name";
		}
		else{
		
			$sql="SELECT ".$table.".*,".$relatedtable."name FROM ".$table.
			" left join ".$relatedtable." USE INDEX (".$table."id) on ".$relatedtable.".".$relatedtablekeyname."=".
			$table.".".$tablekeyname."  WHERE ".$relatedtable.".".$relatedtablekeyname." ".
			$operator."'".$keyvalue."' ORDER BY ".$relatedtablekeyname;
		}	
		$contender = array();
		//print_r($sql);
		$result=db_query($sql);
		$rowcount=db_num_rows($result);
		//print_r($rowcount);
		if($rowcount > 0) {
			while($row = mysqli_fetch_array($result)){
				
				array_push($contender,$row);
				
			}
		}
		return $contender;
	}
	
	function getCategorysWKeyNRelatedTable3($table,$relatedtable,$tablekeyname,$relatedtablekeyname,$keyvalue,$operator){
		/*$sql="SELECT ".$table.".*,".$keyname. " FROM ".$table.
		" left join ".$relatedtable." on ".$relatedtable.".".$relatedtable."ID=".$table.".".$relatedtable."ID  WHERE ".$keyname." ".$operator."'".$keyvalue."' ORDER BY ".$table."name";
		*/
		if($table=='materialreceivejobcard'){
			//check if jobcard is already issued
			$sql="SELECT ".strtolower($table).".*,".$tablekeyname." FROM ".strtolower($table).
			" left join ".strtolower($relatedtable)." on ".
			strtolower($relatedtable).".".$relatedtablekeyname."=".strtolower($table).".".$relatedtablekeyname.
			"  WHERE ".strtolower($relatedtable).".".$tablekeyname." ".$operator."'".$keyvalue.
			" ' AND ".strtolower($table)."ID NOT IN".
			" (SELECT ".$table."ID FROM materialissuancejobcard)".
			" ORDER BY ".$relatedtablekeyname;
			
			/*$sql="SELECT t1.MaterialReceiveJobCardID,t1.MaterialReceiveJobCardName
			FROM materialreceivejobcard t1
			WHERE t1.MaterialReceiveJobCardID NOT IN 
			(SELECT MaterialReceiveJobCardID from materialissuancejobcard)
			ORDER BY t1.MaterialReceiveJobCardName ";*/
		}
		
		else{
			$sql="SELECT ".strtolower($table).".*,".$tablekeyname." FROM ".strtolower($table).
			" left join ".strtolower($relatedtable)." on ".
			strtolower($relatedtable).".".$relatedtablekeyname."=".strtolower($table).".".$relatedtablekeyname.
			"  WHERE ".strtolower($relatedtable).".".$tablekeyname." ".$operator."'".$keyvalue.
			"' ORDER BY ".$relatedtablekeyname;
		}
		
		
		
		$contender = array();
		//print_r($sql);
		$result=db_query($sql);
		$rowcount=db_num_rows($result);
		//print_r($rowcount);
		if($rowcount > 0) {
			while($row = mysqli_fetch_array($result)){
				
				array_push($contender,$row);
				
			}
		}
		return $contender;
	}
	
	
	function Save($posteddata,$table,$keyname,$keyvalue){
		include('../util/connect.php'); 
		//$conn = mysqli_connect("127.0.0.1", "root", "supervisor","phivolcs", 3306);
		//$conn = mysqli_connect("localhost","automeu1","baby20","automeu1_evb");

		date_default_timezone_set('Asia/Manila');
		//$posteddata=array_map('strtoupper', $posteddata);
		
		if($table=='employer'){
			
		}
		/*if($table=='invoicemaster' || $table=='invoicedetail' ||
		   $table=='purchasemaster' || $table=='purchasedetail'	 ||
		   $table=='project' || $table=='attendance' || 
		   $table=='expensemaster' || $table=='expensedetail' ||
		   $table=='collectionmaster' || $table=='collectiondetail' ||
		   $table=='collectionchequedetail' || $table=='collectioninvoicedetail' ||
		   $table=='employee' || $table=='paymentmaster' ||
		   $table=='cashadvance' || $table=='PaySlip' ||
		   $table=='invoicebilling' || $table=='client' || 
		   $table=='clientservice' || $table=='clientinquirymaster' ||
		   $table=='siteinspectionmaster' || $table=='materialrequestmaster' ||
		   $table=='materialrequestdetail'
		   
		){*/
			if(isset($posteddata['btnSave'])){
				unset($posteddata['btnSave']);
				
			}
			unset($posteddata['process']);
			
		//}	
			
		$posteddataaudit=$posteddata;
		if(isset($posteddata['btnSubmit'])){
			
			unset($posteddataaudit['btnSubmit']);
			if($keyvalue ==0){
			//submit button and keyvalue 0 means resubmit, 
			//reset to zero since new job vacancy record
				//$posteddata['InvoiceID']=0;
			}
			
		}
		
		//keyvalue > 0 means edit mode  )
		if ($keyvalue > 0) {
			$max = sizeof($posteddata);
			$i=1;
			$sql = "UPDATE " .$table.  " SET ";
			foreach(array_keys($posteddata) as $fieldname){
			   if( is_string($fieldname) ){
			   		//sanitize comma 
			   		$sql.= $fieldname."='".str_replace(',','',$posteddata[$fieldname]).($i<$max?"',":"'");
			   		
			   }
			   $i++;
			} 
			//$sql .= " WHERE ".$keyname. "='".strtoupper($keyvalue)."'";
			$sql .= " WHERE ".$keyname. "='".$keyvalue."'";
		
		//print_r($sql);
		}
		else{
			/*$posteddata=array_map('strtoupper', $posteddata);
			$posteddata['Email']=strtolower($posteddata['Email']);
			$posteddata['Password']=strtolower($posteddata['Password']);
			$posteddata['SecurityQuestionAnswer']=strtolower($posteddata['SecurityQuestionAnswer']);*/
			$sql = "INSERT INTO " .$table.  " (" . implode(",", array_keys($posteddata) ) . 
			") VALUES ('" . implode("','", $posteddata)."')";
		}
		
		//print_r('sql'.$sql);
		
		$newsql=str_replace("''","''",$sql);
		
		//print_r('newsql'.$newsql);
		
		if ($res = mysqli_query($conn, $newsql)) {
		 //if($res=db_query($newsql,true)) {
		 	$_SESSION['NewID']=mysqli_insert_id($conn);
		 	//print_r('Success');
		 	if(($table=='employer') && $keyvalue==0){
				$_SESSION['_Employer']['EmployerID']=$_SESSION['NewID'];
			}	
		 	//mysqli_free_result($res);	
		 	if($table=='invoicemaster' 
		 	|| $table=='purchasemaster' 
		 	|| $table=='paymentmaster' 
		 	|| $table=='collectionmaster' 
		 	|| $table=='invoicebilling'
		 	|| $table=='client'
		 	|| $table=='clientinquirymaster'
		 	|| $table=='materialrequestmaster'
		 	|| $table=='materialissuancemaster'
		 	|| $table=='materialreceivemaster'
		 	|| $table=='purchaserequestmaster'
		 	|| $table=='employee'
		 	|| $table=='supplier'
		 	){
				return '0_'.$_SESSION['NewID'];
			}else{
				return 0;	
			}
            
        }else{
        	//print_r('Failed');
        	
			return mysqli_error($conn);
		}
		
		 
	}

	function Get($table,$keyname,$keyvalue){
		if($table=='xemployee'){
			if ($_SESSION['Employee']['Email']=="joeben_c@yahoo.com"){
				if($keyvalue==''){
					//empty employeename mean add mode
					$sql = "select * from employee where EmployeeName ='' limit 1";
				}
				else{
					$sql = "select * from ".$table. " where ".$keyname. 
					" LIKE '".$keyvalue."%' limit 1";
				}
				
			}
			else{
				$sql = "select *	from ".$table. " where Email='".$_SESSION['_Admin']['Email']."'";
			}	
		}
		/*elseif($table=='usermodule'){
			$sql="SELECT ".$table.".*,tablemodules.TableName from ".$table. 
			" LEFT JOIN tablemodules on tablemodules.TableID=".$table.
			".TableID WHERE " .$table.".".$keyname. "='".$keyvalue. 
			"' AND EmployeeID = '".$_SESSION['_Admin']['EmployeeID'].
			"' AND Permitx='1'";
								
		}*/
		else{
			$sql="SELECT * FROM ".$table." where ".$keyname. "='".$keyvalue."'";	
		}
		
		//print_r($sql);
		$contender = array();
		if(($result=db_query($sql)) && db_num_rows($result)) {
			$contender=mysqli_fetch_array($result);
			}
		return $contender;	
	}
	
	function GetOneRecord($table){
		
		$sql="SELECT * FROM ".$table." limit 1";	
				
		//print_r($sql);
		$contender = array();
		if(($result=db_query($sql)) && db_num_rows($result)) {
			$contender=mysqli_fetch_array($result);
			}
		return $contender;	
	}
	
	function Delete($table,$keyname,$keyvalue){
		//customized for collectioninvoicedrdetail
		if($table=='collectioninvoicedrdetail'){
			$sql2="INSERT INTO collectioninvoicenotdrdetail select 0, t1.*
			 FROM collectioninvoicedrdetail t1 where collectioninvoicedrdetailid='".$keyvalue."'";
			if($result2=db_query($sql2)) {
				
			}	
		}
		elseif($table=='collectioninvoicedetail'){
			$sql2="DELETE FROM collectioninvoicedrdetail 
			WHERE collectioninvoicedetailid='".$keyvalue."'";
			if($result2=db_query($sql2)) {
				
			}	
		}
		elseif($table=='collectioninvoicenotdrdetail'){
			$sql2="INSERT INTO collectioninvoicedrdetail select 0,
			CollectionInvoiceDetailID,CollectionMasterID, 
			InvoiceBillingID,InvoiceBillingDetailID,0,'0000-00-00'
			FROM collectioninvoicenotdrdetail t1 where collectioninvoicenotdrdetailid='".$keyvalue."'";
			if($result2=db_query($sql2)) {
				
			}
		}
		$sql="DELETE FROM ".$table." where ".$keyname. "='".$keyvalue."'";
		//print_r($sql);
		
		if($result=db_query($sql)) {
			//print_r('0');
			
			
			return 0;
		}
		return 1;	
	}
	
	function DeleteMasterDetail($mastertable,$detailtable,$detailtable2,$keyname,$keyvalue){
		
		if($detailtable2!=''){
			$sql2="DELETE FROM ".strtolower($detailtable2)." where ".$keyname. "='".$keyvalue."'";
			if($result2=db_query($sql2)) {
			//print_r('0');
				
			}
		}
		
		$sql="DELETE FROM ".strtolower($detailtable)." where ".$keyname. "='".$keyvalue."'";
		
		//print_r($sql);
		
		if($result=db_query($sql)) {
			//print_r('0');
				$sql="DELETE FROM ".strtolower($mastertable)." where ".$keyname. "='".$keyvalue."'";
				if($result=db_query($sql)) {
					
					return 0;
				}
			}
		return 1;	
	}
	
	function DeleteCascade($mastertable,$detailtable,$detailtable2,$keyname,$keyvalue){
		
		if($detailtable2!=''){
			$sql2="select * FROM ".strtolower($detailtable2)." 
			where ".$keyname. "='".$keyvalue."' limit 1";
			if($result2=db_query($sql2)) {
				if(db_num_rows($result2) > 0){
					return 1;
				}
				
			}
		}
		
		
		
		//print_r($sql);
		if($detailtable!=''){
			$sql="select * FROM ".strtolower($detailtable)." where ".$keyname. "='".$keyvalue."' limit 1";
			if($result=db_query($sql)) {
				if(db_num_rows($result) > 0){
					return 1;		
				}
				else{
					$sql="DELETE FROM ".strtolower($mastertable)." where ".$keyname. "='".$keyvalue."'";
					if($result=db_query($sql)) {
						return 0;
					}
				}
			}
		}
		else{
			$sql="DELETE FROM ".strtolower($mastertable)." where ".$keyname. "='".$keyvalue."'";
			if($result=db_query($sql)) {
				return 0;
			}
		}
		
	}
	
}

class Login{
	
	function Admin($content){
		
		$sql="SELECT EmployeeID,Email,Password,EmployeeName FROM employee 
		WHERE `Email` = ".db_input($content['Email'])." 
		AND `Password` = '".md5($content['Password'])."'";
		//print_r($sql);
		$contender = array();
		//print_r('login_not_ok');
		//print_r(md5($content['Password']));
	        if(($res=db_query($sql)) && db_num_rows($res)) {
	            while(list($EmployeeID,$Email,$Password,$EmployeeName)=db_fetch_row($res)){
	            	$_SESSION['_Admin'] = array();
	            	$_SESSION['_Admin']['EmployeeID'] =  $EmployeeID;
	            	$_SESSION['_Admin']['Email'] =  $Email;
	            	$_SESSION['_Admin']['EmployeeName'] =  $EmployeeName;
	    
        		//db_query($sql);
        		//print_r('login_ok');
	                return true;
	            }
	        }
		return false;
	}

	function ApplicantLogin($content){
		
		$sql="SELECT ApplicantID,Email,Password,FirstName FROM applicant 
		WHERE `Email` = ".db_input($content['Email']);
		
		//" AND `Password` = '".md5($content['Password'])."'";
		//print_r($sql);
		$contender = array();
		//print_r(md5($content['Password']));
		$_SESSION['_ApplicantLogin']=false;
		$result=0;
        if(($res=db_query($sql)) && db_num_rows($res)) {
            while(list($ApplicantID,$Email,$Password,$FirstName)=db_fetch_row($res)){
            	$_SESSION['_Applicant'] = array();
            	$_SESSION['_Applicant']['ApplicantID'] =  $ApplicantID;
            	$_SESSION['_Applicant']['Email'] =  $Email;
            	$_SESSION['_Applicant']['FirstName'] =  $FirstName;
            	if ($Password==md5($content['Password'])){
            		$_SESSION['_ApplicantLogin']=true;
					$result=0;
				}
				else{
					$_SESSION['_ApplicantLogin']=false;	
					$result=APPLICANT_INVALID_Password;
				}
                
            }
	    }
        else{
			$result=APPLICANT_NOT_REGISTERED;
		}
		return $result;
	}
	
	function EmployerLogin($content){
		
		$sql="SELECT t1.EmployerID,Email,Password FROM employer t1 
		WHERE `Email` = ".db_input($content['Email']);
		
		//" AND `Password` = '".md5($content['Password'])."'";
		//print_r($sql);
		$contender = array();
		//print_r(md5($content['Password']));
		$_SESSION['_EmployerLogin']=false;
		$result=1;
        if(($res=db_query($sql)) && db_num_rows($res)) {
            while(list($EmployerID,$Email,$Password)=db_fetch_row($res)){
            	$_SESSION['_Employer'] = array();
            	$_SESSION['_Employer']['EmployerID'] =  $EmployerID;
            	$_SESSION['_Employer']['Email'] =  $Email;
            	
            	if ($Password==md5($content['EmployerPassword'])){
            		$_SESSION['_EmployerLogin']=true;
					$result=0;
				}
				else{
					$_SESSION['_EmployerLogin']=false;	
					$result=EMPLOYER_INVALID_Password;
				}
                
            }
	     	        
	    }
        
		return $result;
	}

	function EmployeeLogin($content){
		
		$sql="SELECT t1.EmployeeID,Email,Username,Password,
		concat_ws(' ',Lastname,Firstname) as EmployeeName,
		Designation,t1.DepartmentID,DepartmentName,SectionID 
		FROM employee t1 
		LEFT JOIN department t2 ON t2.DepartmentID=t1.DepartmentID
		WHERE `Username` = ".db_input($content['Username']).
		" AND `Password` = '".md5($content['Password'])."'";
		//print_r($sql);
		$contender = array();
		//print_r(md5($content['Password']));
		$_SESSION['_EmployeeLogin']=false;
		$result=1;
        if(($res=db_query($sql)) && db_num_rows($res)) {
            while(list($EmployeeID,$Email,$Username,$Password,$EmployeeName,
            $Designation,$DepartmentID,$DepartmentName,$SectionID)=db_fetch_row($res)){
            	$_SESSION['_Employee'] = array();
            	$_SESSION['_Employee']['Username'] =  $Username;
            	$_SESSION['_Employee']['EmployeeID'] =  $EmployeeID;
            	$_SESSION['_Employee']['EmployeeName'] =  $EmployeeName;
            	$_SESSION['_Employee']['Email'] =  $Email;
            	$_SESSION['_Employee']['DepartmentID'] =  $DepartmentID;
            	$_SESSION['_Employee']['DepartmentName'] =  $DepartmentName;
            	$_SESSION['_Employee']['SectionID'] =  $SectionID;
            	$_SESSION['_Employee']['Designation']=$Designation;
            	
            	if ($Password==md5($content['Password'])){
            		$_SESSION['_EmployeeLogin']=true;
					$result=0;
				}
				else{
					$_SESSION['_EmployeeLogin']=false;	
					$result=EMPLOYEE_INVALID_Password;
				}
                
            }
	     	        
	    }
        
		return $result;
	}

	
	function EmployerPWReset($content){
		
		$sql="SELECT t1.EmployerID,Email,AccreditedDate,
		SecurityQuestionID,SecurityQuestionAnswer
		FROM employer t1 
		left join employeraccredited t2 on t2.EmployerID=t1.EmployerID
		WHERE `Email` = ".db_input($content['Email']);
		
		//$contender = array();
		
		$result=1;
        if(($res=db_query($sql)) && db_num_rows($res)) {
            while(list($EmployerID,$Email,$AccreditedDate,$SecurityQuestionID,$SecurityQuestionAnswer)=db_fetch_row($res)){
            	
            if($AccreditedDate!='0000-00-00'){
            		
            	if ( ($SecurityQuestionID==$content['SecurityQuestionID']) && 
            	($SecurityQuestionAnswer==$content['SecurityQuestionAnswer'])){
            		$Employer = new DataTable();
            		$posteddata=array();
            		$posteddata['EmployerID']=$EmployerID;
            		$posteddata['Password']=md5('DOHEJOBS');
            		$result=$Employer->Save($posteddata,'employer','EmployerID',$EmployerID);
            		//$result=0;
				}
				else if ($SecurityQuestionID!=$content['SecurityQuestionID']){
					$result=INVALID_SECURITYID;
				}
				else if ($SecurityQuestionAnswer!=$content['SecurityQuestionAnswer']){
					$result=INVALID_SECURITYANSWER;
				}
                
            }
	     	else{
				
				$result=EMPLOYER_NOT_ACCREDITED;
			}        
	    	}
        }
		return $result;
	}

	function ApplicantPWReset($content){
		
		$sql="SELECT t1.ApplicantID,Email,
		SecurityQuestionID,SecurityQuestionAnswer
		FROM applicant t1 
		WHERE `Email` = ".db_input($content['Email']);
		
		//$contender = array();
		
		$result=1;
        if(($res=db_query($sql)) && db_num_rows($res)) {
            while(list($ApplicantID,$Email,$SecurityQuestionID,$SecurityQuestionAnswer)=db_fetch_row($res)){
            	
                        		
	    	if ( ($SecurityQuestionID==$content['SecurityQuestionID']) && 
	    	($SecurityQuestionAnswer==$content['SecurityQuestionAnswer'])){
	    		$Applicant = new DataTable();
	    		$posteddata=array();
	    		$posteddata['ApplicantID']=$ApplicantID;
	    		$posteddata['Password']=md5('DOHEJOBS');
	    		$result=$Applicant->Save($posteddata,'applicant','ApplicantID',$ApplicantID);
	    		//$result=0;
			}
			else if ($SecurityQuestionID!=$content['SecurityQuestionID']){
				$result=INVALID_SECURITYID;
			}
			else if ($SecurityQuestionAnswer!=$content['SecurityQuestionAnswer']){
				$result=INVALID_SECURITYANSWER;
			}
                
               
	    	}
        }
		return $result;
	}
}

?>