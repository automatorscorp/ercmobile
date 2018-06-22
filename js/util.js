

function ShowLoading(targetdiv){
            document.getElementById(targetdiv).innerHTML = "<b>Please wait while server is preparing data...</b>"
			}



function soundalert(){
	var snd = new Audio("sounds/notify.wav"); // buffers automatically when created
    snd.play('sounds/notify.wav');
	//playSound('sounds/notify.wav');	
}


function ProcessMasterEntry(key,operation,process,prevprocid) {
	
try{
	
	//alert(process);
	if ( process=='TeeTimePlayer' 
	
	) {	 
		jvurl='TableEntry.php';
	}
	else{
		jvurl=process+'Entry.php';
	}		
	
	
	if ( process=='Client'){
		//for client lookup
		divid='myModalMasterContent2';
		modalid='#myModalMaster2';
	}
	else if(process=='Supplier'){
		//for suuplier lookup
		divid='myModalMasterContent2';
		modalid='#myModalMaster2';
	}
	else{
		divid='myModalMasterContent';
		modalid='#myModalMaster';
	}
	
	
	url=jvurl+'?'+process+'ID='+key+"&operation="+operation+"&process="+process+
	"&PrevProcID="+prevprocid;
	
	
	//alert(url);
		
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
    		document.getElementById(divid).innerHTML=xmlhttp.responseText;
    	}
 	}
 
	xmlhttp.open("GET",url,true);
	xmlhttp.send();
	$(modalid).modal('show'); 

	
}
catch(err){
		
			  txt="There was an error on this page.\n\n";
			  txt+="Error description: " + err.message + "\n\n";
			  txt+="Click OK to continue.\n\n";
			  alert(txt);
	  	}
	
}




