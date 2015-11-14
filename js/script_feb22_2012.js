// JavaScript Document
var xmlHttpReq = null;
function getHttpPost() 
{
	try{			
		xmlHttpReq=new XMLHttpRequest();// Firefox, Opera 8.0+, Safari
	}catch (e)
	{		
		try{			
			xmlHttpReq=new ActiveXObject("Msxml2.XMLHTTP"); // Internet Explorer
		}catch (e)
		{		    
			try{				
				xmlHttpReq=new ActiveXObject("Microsoft.XMLHTTP");	
			}catch (e)
			{				
				alert("No AJAX!?");				
				return false;			
			}		
		}	
	}
}
function showdivpop()
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById('popdiv').innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "popupdate.php";
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function showdivpop_view(value,snameen,sdtype,sof,sdsort,scat)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById('viewholder').innerHTML=xmlHttpReq.responseText;
        }
    }
		var url = "popupdateview.php?v="+value+"&sname="+snameen+"&sdtype="+sdtype+"&sdsort="+sdsort+"&sof="+sof+"&scat="+scat;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function showdivpoppend_view(value)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById('viewholder').innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "popupdateviewpend.php?v="+value;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function showfollow_result(id,checkp,fdate,fhour,fminute,fampm,fnote,ccome,checkcome)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
			document.getElementById("contmodal").style.display="none";
           //document.getElementById('messagef').innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = 'savefollow.php?id='+id+'&checkprocess='+checkp+'&fdate='+fdate+'&fhour='+fhour+'&fminute='+fminute+'&fampm='+fampm+'&fnote='+fnote+"&ccome="+ccome+"&checkcome="+checkcome;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function showtext_result(cphone,mmessage)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
			document.getElementById("messagef").innerHTML=xmlHttpReq.responseText;
			document.getElementById("contmodal").style.display="none";
        }
    }
	var url = "sendText.php?phone="+cphone+"&mmessage="+mmessage;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function showmodalform(value)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById('modalform').innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "followup.php?id="+value;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function showmodalform_text(value)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById('modalform').innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "sendtextform.php?id="+value;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function errorcheck(task,vars,message)
{
	var color = "#cee838";
	var variable = document.getElementById(vars).value;
	if(task=="text")
	{
		if(variable.length==0 || isNaN(variable)==false)
		{
			document.getElementById(vars).style.background=color;
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById("message2").innerHTML="";
		}
	}
	if(task=="select")
	{
		if(variable=="0")
		{
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById("message2").innerHTML="";
		}
	}
	else if(task=="number")
	{
		if(variable.length==0 || isNaN(variable)==true)
		{
			document.getElementById(vars).style.background=color;
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById("message2").innerHTML="";
		}	
	}
	else if(task=="normal")
	{
		if(variable.length==0)
		{
			document.getElementById(vars).style.background=color;
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById("message2").innerHTML="";
		}
	}
	else if(task=="email")
	{
		var email = document.getElementById(vars).value;
		if(email.length !=0)
		{
			if(variable.length==0 || ((variable.indexOf(".")<2) && (variable.indexOf("@")<=0)))
			{
				document.getElementById(vars).style.background=color;
				document.getElementById("message2").innerHTML=message;
				return false;
			}
			else
			{
				document.getElementById(vars).style.background="";
				document.getElementById("message2").innerHTML="";
			}
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById("message2").innerHTML="";
		}
	}
	else if(task=="emailf")
	{
		var email = document.getElementById(vars).value;
		if(variable.length==0 || ((variable.indexOf(".")<2) && (variable.indexOf("@")<=0)))
		{
			document.getElementById(vars).style.background=color;
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById("message2").innerHTML="";
		}
	}
	else if(task=="selects")
	{
		if(document.getElementById(vars).selectedIndex==0)
		{
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById("message2").innerHTML="";
		}
	}
	else if(task=="checksa")
	{
		if(document.getElementById(vars).checked==false)
		{
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById("message2").innerHTML="";
		}
	}
	return true;
}
function preload(images) {
    if (document.images) {
        var i = 0;
        var imageArray = new Array();
        imageArray = images.split(',');
        var imageObj = new Image();
        for(i=0; i<=imageArray.length-1; i++) {
            //document.write('<img src="' + imageArray[i] + '" />');// Write to page (uncomment to check images)
            imageObj.src=images[i];
        }
    }
}
function showofficeinfo(value)
{
	if(value !="na")
	{
		getHttpPost();
		xmlHttpReq.onreadystatechange = function() 
		{
			if(xmlHttpReq.readyState == 4) 
			{
			   document.getElementById('officeinfo').innerHTML=xmlHttpReq.responseText;
			}
		}
		var url = "showofficeinfo.php?id="+value;
		xmlHttpReq.open('POST',url, true);
		//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
		xmlHttpReq.send(null);
	}
}
function showsourcefield(value,valueb)
{
		getHttpPost();
		xmlHttpReq.onreadystatechange = function() 
		{
			if(xmlHttpReq.readyState == 4) 
			{
			   document.getElementById('sourceentry').innerHTML=xmlHttpReq.responseText;
			}
		}
		var url = "showsourceentry.php?id="+value+"&r="+valueb;
		xmlHttpReq.open('POST',url, true);
		//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
		xmlHttpReq.send(null);
}
function checkField()
{
	//login page
	if(!errorcheck("normal","uname","Please enter username"))
		return false;
	if(!errorcheck("normal","upass","Please enter password"))
		return false;
	return true;
}
function checkentryfield(csource)
{
	//check the fields from source
	var c1="";
	var c2="";
	if(csource=="1")
	{
		if(!errorcheck("normal","csource_title0","Please provide Newspaper Name"))
		return false;
		if(!errorcheck("normal","csource_title1","Please provide Ad Title"))
		return false;
		c1 = document.getElementById("csource_title0").value;
		c2 = document.getElementById("csource_title1").value;
		document.getElementById("csource_cont").value=c1+" || "+ c2;
	}
	else if(csource=="3")
	{
		if(!errorcheck("normal","csource_title0","Please provide Site Name"))
		return false;
		if(!errorcheck("normal","csource_title1","Please provide Title of Ad"))
		return false;
		c1 = document.getElementById("csource_title0").value;
		c2 = document.getElementById("csource_title1").value;
		document.getElementById("csource_cont").value=c1+" || "+ c2;
	}
	else if(csource=="4")
	{
		if(!errorcheck("normal","csource_title0","Please provide College Name"))
		return false;
		c1 = document.getElementById("csource_title0").value;
		document.getElementById("csource_cont").value=c1;
	}
	else if(csource=="5")
	{
		if(!errorcheck("normal","csource_title0","Please provide Name of Fair"))
		return false;
		c1 = document.getElementById("csource_title0").value;
		document.getElementById("csource_cont").value=c1;
	}
	else if(csource=="7")
	{
		if(!errorcheck("normal","csource_title0","Name of Agency"))
		return false;
		c1 = document.getElementById("csource_title0").value;
		document.getElementById("csource_cont").value=c1;
	}
	else if(csource=="8")
	{
		if(!errorcheck("normal","csource_title0","Name of Source"))
		return false;
		c1 = document.getElementById("csource_title0").value;
		document.getElementById("csource_cont").value=c1;
	}
	else if(csource=="9")
	{
		if(!errorcheck("normal","csource_title0","Please Provide the Location and Description of the Flyer"))
		return false;
		c1 = document.getElementById("csource_title0").value;
		document.getElementById("csource_cont").value=c1;
	}
	else if(csource=="10")
	{
		if(!errorcheck("normal","csource_title0","Provide explanation of this Unknown Source"))
		return false;
		c1 = document.getElementById("csource_title0").value;
		document.getElementById("csource_cont").value=c1;
	}
	return true;
}
function checkFieldb()
{
	//import page form
	if(!errorcheck("normal","cname","Please enter caller name"))
		return false;
	if(!errorcheck("number","cphonea","Please enter the caller phone area code"))
		return false;
	if(!errorcheck("number","cphoneb","Please enter the caller's complete phone"))
		return false;
	if(!errorcheck("number","cphonec","Please enter the caller's complete phone"))
		return false;
	var cphonea=document.getElementById("cphonea").value;
	var cphoneb=document.getElementById("cphoneb").value;
	var cphonec=document.getElementById("cphonec").value;
	document.getElementById("cphone").value=cphonea+"-"+cphoneb+"-"+cphonec;
	if(!errorcheck("normal","cdate","Please enter a date called"))
		return false;
	if(!errorcheck("selects","coffice","Please select the office"))
		return false;
	if(!errorcheck("selects","csource","Please select the source"))
		return false;
	var csource = document.getElementById("csource").value;
	if(!checkentryfield(csource))
		return false;
	if(!errorcheck("normal","idate","Please enter a date for interview"))
		return false;
	if(!errorcheck("selects","chour","Please select hour for interview"))
		return false;
	if(!errorcheck("selects","cminute","Please select minute for interview"))
		return false;
	if(!errorcheck("selects","campm","Please select a type of time"))
		return false;
	else
	{
		var chour = document.getElementById("chour").value;
		var cminute = document.getElementById("cminute").value;
		var campm = document.getElementById("campm").value;
		document.getElementById("ctime").value= chour+":"+cminute+" "+campm;
		var email = document.getElementById("email").value;
		if(email.length <1)
		{
		var confirmx = window.confirm("System requires an email adddress to send confirmation for interview, you can still process this information without an email but without an email system will not send the confirmation.\r\n\r\nDo You Want To Proceed? or you would prefer to add an email address instead.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
		if(confirmx==false)
			return false;
		}
	}
	return true;
}
function checkFieldc()
{
	//setting_rec form
	if(!errorcheck("normal","cname","Please enter caller name"))
		return false;
	if(!errorcheck("number","cphonea","Please enter the caller phone area code"))
		return false;
	if(!errorcheck("number","cphoneb","Please enter the caller's complete phone"))
		return false;
	if(!errorcheck("number","cphonec","Please enter the caller's complete phone"))
		return false;
	var cphonea=document.getElementById("cphonea").value;
	var cphoneb=document.getElementById("cphoneb").value;
	var cphonec=document.getElementById("cphonec").value;
	document.getElementById("cphone").value=cphonea+"-"+cphoneb+"-"+cphonec;
	var cdate = document.getElementById("changecdate").value;
	if(cdate=="yes")
	{
		if(!errorcheck("normal","cdate","Please enter a date called"))
			return false;
	}
	if(!errorcheck("selects","coffice","Please select the office"))
		return false;
	if(!errorcheck("selects","csource","Please select the source"))
		return false;
	var csource = document.getElementById("csource").value;
	if(!checkentryfield(csource))
		return false;
	var idate = document.getElementById("changeidate").value;
	if(idate=="yes")
	{
		if(!errorcheck("normal","idate","Please enter a date for interview"))
			return false;
	}
	if(!errorcheck("selects","chour","Please select hour for interview"))
		return false;
	if(!errorcheck("selects","cminute","Please select minute for interview"))
		return false;
	if(!errorcheck("selects","campm","Please select a type of time"))
		return false;
	else
	{
			var chour = document.getElementById("chour").value;
			var cminute = document.getElementById("cminute").value;
			var campm = document.getElementById("campm").value;
			document.getElementById("ctime").value= chour+":"+cminute+" "+campm;
			var email = document.getElementById("email").value;
			if(email.length <1)
			{
			var confirmx = window.confirm("System requires an email adddress to send confirmation for interview, you can still process this information without an email but without an email system will not send the confirmation.\r\n\r\nDo You Want To Proceed? or you would prefer to add an email address instead.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
			if(confirmx==false)
				return false;
			}
	}
}
function checkFieldd()
{
	//setrec.php form
	var statusm=document.getElementById("statusm").value;
	var checkm=true;
	var checkshow = document.getElementById("checkshow").value;
	var checkintshow = document.getElementById("checkintshow").value;
	if(checkshow=="yes")
	{
		if(!errorcheck("selects","intshow","Please choose whether candidate came for interview"))
		return false;
		var checkshowans = document.getElementById("intshow").value;
		if(checkshowans=="no")
		{
			if(!errorcheck("normal","cintnote","Provide reason of candidate absence"))
			return false;
		}
		else
		{
			if(checkintshow=="yes")
			{
				if(!errorcheck("normal","intdate","Please Choose The Date"))
				return false;
				if(!errorcheck("selects","inthour","Please Choose The Hour"))
				return false;
				if(!errorcheck("selects","intminute","Please Choose The Minute"))
				return false;
			}
			if(!errorcheck("selects","intagent","Please choose Agent For Interview"))
			return false;
		}
	}
	else
	{
		if(checkintshow=="yes")
		{
			if(!errorcheck("normal","intdate","Please Choose The Date"))
			return false;
			if(!errorcheck("selects","inthour","Please Choose The Hour"))
			return false;
			if(!errorcheck("selects","intminute","Please Choose The Minute"))
			return false;
		}
		if(!errorcheck("selects","intagent","Please choose Agent For Interview"))
			return false;
	}
	var checkhired = document.getElementById("checkhired").value;
	if(checkhired=="yes")
	{
		if(!errorcheck("selects","hired","Please Interview Result"))
		return false;
	}
	var checkhirednote = document.getElementById("checkhirednote").value;
	if(checkhirednote=="yes")
	{
		if(!errorcheck("normal","cnote","Please write an reason of the interview result"))
		return false;
	}
	var checkhiredp= document.getElementById("checkhiredp").value;
	if(checkhiredp=="yes")
	{
		if(!errorcheck("selects","hiredp","Please choose a hired procedure"))
		return false;
	}
	else if(checkhiredp=="no")
	{
		if(!errorcheck("normal","cnote","Please provide the reason"))
		return false;
	}
	var checkoprocess = document.getElementById("checkoprocess").value;
	var checkobprocess = document.getElementById("checkobprocess").value;
	if(checkoprocess=="yes")
	{
		var checkodate = document.getElementById("checkodate").value;
		if(checkodate=="yes")
		{
			if(!errorcheck("normal","odate","Please choose an orientation date"))
			return false;
			if(!errorcheck("selects","ohour","Please choose an orientation hour"))
			return false;
			if(!errorcheck("selects","ominute","Please choose an orientation minute"))
			return false;
			if(!errorcheck("selects","oampm","Please choose an orientation time type"))
			return false;
		}
		var checkoshowdate = document.getElementById("checkoshowdate").value;
		var oshowdate = document.getElementById("oshowdate").value;
		var oshowhour = document.getElementById("oshowhour").value;
		var oshowminute = document.getElementById("oshowminute").value;
		var oshowampm = document.getElementById("oshowampm").value;
		if((checkoshowdate=="yes" ) || (oshowdate.length>0 || oshowhour !="na" || oshowminute !="na" || oshowampm !="na"))
		{
			if(!errorcheck("normal","oshowdate","Please choose an orientation attendance date"))
			return false;
			if(!errorcheck("selects","oshowhour","Please choose an orientation attendance hour"))
			return false;
			if(!errorcheck("selects","oshowminute","Please choose an orientation attendance minute"))
			return false;
			if(!errorcheck("selects","oshowampm","Please choose an orientation time attendance type"))
			return false;
		}
		var checkocompdate = document.getElementById("checkocompdate").value;
		if(checkocompdate=="yes")
		{
			if(!errorcheck("normal","ocompdate","Please choose an orientation completition date"))
			return false;
		}
		if(!errorcheck("selects","ooffice","Please choose office for orientation"))
			return false;
		var checkimg = document.getElementById("checkimg").value;
		if(checkimg=="yes")
		{
			if(!errorcheck("normal","imgprof","Please choose an image to upload"))
				return false;
		}
	}
	else if(checkobprocess=="yes")
	{
		var checkobdate = document.getElementById("checkobdate").value;
		if(checkobdate=="yes")
		{
			if(!errorcheck("normal","obdate","Please choose an observation date"))
			return false;
		}
		if(!errorcheck("selects","oboffice","Please choose office for observation"))
			return false;
		var checkobshowdate = document.getElementById("checkobshowdate").value;
		var obshowdate = document.getElementById("obshowdate").value;
		var obshowhour = document.getElementById("obshowhour").value;
		var obshowminute = document.getElementById("obshowminute").value;
		var obshowampm = document.getElementById("obshowampm").value;
		if((checkobshowdate=="yes" ) || (obshowdate.length>0 || obshowhour !="na" || obshowminute !="na" || obshowampm !="na"))
		{
			if(!errorcheck("normal","obshowdate","Please choose an observation attendance date"))
			return false;
			if(!errorcheck("selects","obshowhour","Please choose an observation  attendance hour"))
			return false;
			if(!errorcheck("selects","obshowminute","Please choose an observation  attendance minute"))
			return false;
			if(!errorcheck("selects","obshowampm","Please choose an observation  time attendance type"))
			return false;
		}
		var checkobcompdate = document.getElementById("checkobcompdate").value;
		if(checkobcompdate=="yes")
		{
			if(!errorcheck("normal","obcompdate","Please choose an observation completition date"))
			return false;
		}
		if(!errorcheck("selects","obagent","Please choose agent for observation"))
			return false;
	}
	/*if(statusm !="")
	{
		var hiredp=document.getElementById("hiredp").selectedIndex;
		if(statusm != hiredp)
		if(statusm=="3")
		{
			if(hiredp !=1)
				checkm=false;
		}
		else if(statusm=="4")
		{
			if(hiredp !=2)
				checkm=false;
		}
	}*/
	/*if(checkm==true)
	{
	if(statusm=="3")
	{
		var checkodate = document.getElementById("checkodate").value;
		if(checkodate=="yes")
		{
			if(!errorcheck("normal","odate","Please choose an orientation date"))
			return false;
			if(!errorcheck("selects","ohour","Please choose an orientation hour"))
			return false;
			if(!errorcheck("selects","ominute","Please choose an orientation minute"))
			return false;
			if(!errorcheck("selects","oampm","Please choose an orientation time type"))
			return false;
		}
		if(!errorcheck("selects","ooffice","Please choose office for orientation"))
			return false;
		var checkimg = document.getElementById("checkimg").value;
		if(checkimg=="yes")
		{
			if(!errorcheck("normal","imgprof","Please choose an image to upload"))
				return false;
		}
	}
	else if(statusm=="4")
	{
		var checkobdate = document.getElementById("checkobdate").value;
		if(checkobdate=="yes")
		{
			if(!errorcheck("normal","obdate","Please choose an observation date"))
			return false;
		}
		if(!errorcheck("selects","oboffice","Please choose office for observation"))
			return false;
		if(!errorcheck("selects","obagent","Please choose agent for observation"))
			return false;
	}
	}*/
	return true;
}
function checkFielde()
{
	//user setting page
	var checkin = document.getElementById("changepass").value;
	if(!errorcheck("normal","uname","Please Write A Username"))
		return false;
	if(checkin=="yes")
	{
		if(!errorcheck("normal","newpass","Please write the new password"))
			return false;
		if(!errorcheck("normal","renewpass","Please re-type the password"))
			return false;
		var newpass = document.getElementById("newpass").value;
		var renewpass = document.getElementById("renewpass").value;
		if(newpass != renewpass)
		{
			document.getElementById("renewpass").style.background="#cee838";
			document.getElementById("message2").innerHTML="Both Password Must Match";
			return false;
		}
		else
		{
			document.getElementById("renewpass").style.background="";
			document.getElementById("message2").innerHTML="";
		}
	}
	else
	{
		document.getElementById("message2").innerHTML="";
		document.getElementById("newpass").style.background="";
		document.getElementById("renewpass").style.background="";
	}
	if(!errorcheck("normal","realname","Please provide a valid name"))
		return false;
	if(!errorcheck("number","cphonea","Please enter the user phone area code"))
		return false;
	if(!errorcheck("number","cphoneb","Please enter the user's complete phone"))
		return false;
	if(!errorcheck("number","cphonec","Please enter the user's complete phone"))
		return false;
	var cphonea=document.getElementById("cphonea").value;
	var cphoneb=document.getElementById("cphoneb").value;
	var cphonec=document.getElementById("cphonec").value;
	document.getElementById("cphone").value=cphonea+"-"+cphoneb+"-"+cphonec;
	if(!errorcheck("emailf","uemail","Please provide a valid email"))
		return false;
	if(!errorcheck("normal","utitle","Please provide a title"))
		return false;
	return true;
}
function checkFieldf()
{
	//create user form
	if(!errorcheck("normal","uname","Please Write A Username"))
		return false;
	if(!errorcheck("normal","newpass","Please Type a Password"))
		return false;
	if(!errorcheck("normal","renewpass","Please Re-Type The Password"))
		return false;
	var newpass = document.getElementById("newpass").value;
	var renewpass = document.getElementById("renewpass").value;
	if(newpass != renewpass)
	{
		document.getElementById("renewpass").style.background="#cee838";
		document.getElementById("message2").innerHTML="Both Password Must Match";
		return false;
	}
	else
	{
		document.getElementById("renewpass").style.background="";
		document.getElementById("message2").innerHTML="";
	}
	if(!errorcheck("emailf","uemail","Please provide a valid email"))
		return false;
	if(!errorcheck("normal","realname","Please provide a valid name"))
		return false;
	if(!errorcheck("number","cphonea","Please enter the user phone area code"))
		return false;
	if(!errorcheck("number","cphoneb","Please enter the user's complete phone"))
		return false;
	if(!errorcheck("number","cphonec","Please enter the user's complete phone"))
		return false;
	var cphonea=document.getElementById("cphonea").value;
	var cphoneb=document.getElementById("cphoneb").value;
	var cphonec=document.getElementById("cphonec").value;
	document.getElementById("cphone").value=cphonea+"-"+cphoneb+"-"+cphonec;
	if(!errorcheck("normal","utitle","Please provide a title"))
		return false;
		var utype = document.getElementById("utype").value;
	if(utype=="1")
	{
		var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO CREATE A SUPER ADMIN. DOING THIS WILL ALLOW EXCLUSIVE ACCESS THAN REGULAR ADMIN. ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
		if(confirmx==false)
			return false;
	}
	return true;
}
function checkFieldg()
{
	//setting page
	var checkin = document.getElementById("changepass").value;
	if(!errorcheck("normal","uname","Please Write A Username"))
		return false;
	if(checkin=="yes")
	{
		if(!errorcheck("normal","newpass","Please write the new password"))
			return false;
		if(!errorcheck("normal","renewpass","Please re-type the password"))
			return false;
		var newpass = document.getElementById("newpass").value;
		var renewpass = document.getElementById("renewpass").value;
		if(newpass != renewpass)
		{
			document.getElementById("renewpass").style.background="#cee838";
			document.getElementById("message2").innerHTML="Both Password Must Match";
			return false;
		}
		else
		{
			document.getElementById("renewpass").style.background="";
			document.getElementById("message2").innerHTML="";
		}
	}
	else
	{
		document.getElementById("message2").innerHTML="";
		document.getElementById("newpass").style.background="";
		document.getElementById("renewpass").style.background="";
	}
	if(!errorcheck("normal","realname","Please provide a valid name"))
		return false;
	if(!errorcheck("number","cphonea","Please enter your phone area code"))
		return false;
	if(!errorcheck("number","cphoneb","Please enter your complete phone"))
		return false;
	if(!errorcheck("number","cphonec","Please enter your complete phone"))
		return false;
	var cphonea=document.getElementById("cphonea").value;
	var cphoneb=document.getElementById("cphoneb").value;
	var cphonec=document.getElementById("cphonec").value;
	document.getElementById("cphone").value=cphonea+"-"+cphoneb+"-"+cphonec;
	if(!errorcheck("emailf","uemail","Please provide a valid email"))
		return false;
	if(!errorcheck("normal","utitle","Please provide a title"))
		return false;
	return true;
}
function checkFieldh()
{
	//search entry from view.php
	if(!errorcheck("normal","sname","Please enter a name to search"))
		return false;
	return true;
}
function checkFieldi()
{
	//office edit page
	if(!errorcheck("normal","oname","Please Write A Office Name"))
		return false;
	if(!errorcheck("normal","ocontact","Please Write A Contact Name"))
		return false;
	if(!errorcheck("emailf","oemail","Please provide a valid email"))
		return false;
	if(!errorcheck("normal","ophone","Please provide a valid Phone"))
		return false;
	if(!errorcheck("normal","odays","Please provide days avaliable for meeting at this office"))
		return false;
	if(!errorcheck("normal","ohours","Please provide hours avaliable for meeting at this office"))
		return false;
	if(!errorcheck("normal","oaddress","Please provide a valid address"))
		return false;
	if(!errorcheck("normal","ocity","Please provide a valid city"))
		return false;
	if(!errorcheck("normal","ostate","Please provide a valid state"))
		return false;
	if(!errorcheck("normal","ocountry","Please provide a valid country"))
		return false;
	if(!errorcheck("normal","ozip","Please provide a valid Zip/Postal Code"))
		return false;
	if(!errorcheck("normal","odriving","Please provide a direction instructions by driving"))
		return false;
	if(!errorcheck("normal","owalking","Please provide a direction instructions by walking"))
		return false;
	return true;
}
function checkFieldj()
{
	//edit follow up page
	var id=document.getElementById("id").value;
	var varname = document.getElementById("varname").value;
	var checkp = document.getElementById("checkprocess").value;
	if(!errorcheck("selects","followup","Please Choose A Follow Up Procedure"))
		return false;
	if(checkp=="2" || checkp=="3")
	{
		var fmonth = document.getElementById("fmonth").value;
		var fday = document.getElementById("fday").value;
		var fyear = document.getElementById("fyear").value;
		document.getElementById("fdate").value=fyear+"-"+fmonth+"-"+fday;
		if(!errorcheck("selects","fhour","Please Choose A "+varname+" Hour"))
			return false;
		if(!errorcheck("selects","fminute","Please Choose A "+varname+" Minute"))
			return false;
		if(!errorcheck("selects","fampm","Please Choose A "+varname+" Time Type"))
			return false;
		if(checkp=="3")
		{
			if(!errorcheck("selects","ccome","Is this person coming back?"))
			return false;
		}
		if(!errorcheck("normal","fnote","Please Provide A "+varname+" Note"))
			return false;
	}
	var fdate = document.getElementById("fdate").value;
	var fhour = document.getElementById("fhour").value;
	var fminute = document.getElementById("fminute").value;
	var fampm = document.getElementById("fampm").value;
	var fnote = document.getElementById("fnote").value;
//window.location.href='savefollow.php?id='+id+'&checkprocess='+checkp+'&fdate='+fdate+'&fhour='+fhour+'&fminute='+fminute+'&fampm='+fampm+'&fnote='+fnote;
	if(checkp=="3")
	{
		var ccome = document.getElementById("ccome").value;
		var checkcome = document.getElementById("checkcome").value;
		showfollow_result(id,checkp,fdate,fhour,fminute,fampm,fnote,ccome,checkcome);
	}
	else
		showfollow_result(id,checkp,fdate,fhour,fminute,fampm,fnote,"","");
	 window.location.reload(true);
}
function checkFieldk()
{
	if(!errorcheck("normal","date1","Please Complete Date Range"))
		return false;
	if(!errorcheck("normal","date2","Please Complete Date Range"))
		return false;
	return true;
}
function checkFieldl()
{
	//form to send text message
	if(!errorcheck("number","cphonea","Please enter person's phone area code"))
		return false;
	if(!errorcheck("number","cphoneb","Please enter person's complete phone"))
		return false;
	if(!errorcheck("number","cphonec","Please enter person's complete phone"))
		return false;
	var cphonea=document.getElementById("cphonea").value;
	var cphoneb=document.getElementById("cphoneb").value;
	var cphonec=document.getElementById("cphonec").value;
	document.getElementById("cphone").value=cphonea+"-"+cphoneb+"-"+cphonec;
	if(!errorcheck("normal","mmessage","Please Provide Text for Message"))
		return false;
	if(!errorcheck("normal","cphone","Person don't have a phone to send text message"))
		return false;
	var cphone = document.getElementById("cphone").value;
	var mmessage = document.getElementById("mmessage").value;
	if(cphone.length !=0 && mmessage.length !=0)
		showtext_result(cphone,mmessage);
}
function switchfollow(value)
{
	if(value=="1")
	{
		document.getElementById("foldiv").style.display="none";
		document.getElementById("checkprocess").value="1";
		document.getElementById("fnote").innerHTML="";
		document.getElementById("checkcome").value="no";
		document.getElementById("showcome").style.display="none";
	}
	else if(value=="2")
	{
		document.getElementById("foldiv").style.display="block";
		document.getElementById("varname").value="Follow Up";
		document.getElementById("varname_date").innerHTML="Follow Up Date";
		document.getElementById("varname_note").innerHTML="Follow Up Note";
		document.getElementById("checkprocess").value="2";
		document.getElementById("fnote").innerHTML=document.getElementById("followupnote").value;
		document.getElementById("checkcome").value="no";
		document.getElementById("showcome").style.display="none";
	}
	else if(value=="3")
	{
		document.getElementById("varname").value="Completed";
		document.getElementById("foldiv").style.display="block";
		document.getElementById("varname_date").innerHTML="Completed Date";
		document.getElementById("varname_note").innerHTML="Completed Note";
		document.getElementById("checkprocess").value="3";
		document.getElementById("fnote").innerHTML="";
		document.getElementById("checkcome").value="yes";
		document.getElementById("showcome").style.display="block";
	}
	else
	{
		document.getElementById("foldiv").style.display="none";
		document.getElementById("checkprocess").value="1";
		document.getElementById("fnote").innerHTML="";
		document.getElementById("checkcome").value="no";
		document.getElementById("showcome").style.display="none";
	}
}
function allowchangecdate(checkin,divin,changein)
{
	var checkx = document.getElementById(checkin).checked;
	if(checkx==true)
	{
		 document.getElementById(divin).style.display="block";
		 document.getElementById(changein).value="yes";
	}
	else
	{
		document.getElementById(divin).style.display="none";
	    document.getElementById(changein).value="none";
	}
}
function allowpassword()
{
	var checking = document.getElementById("checkchange").checked;
	if(checking==true)
	{
		document.getElementById("allowpassworddiv").style.display="block";
		document.getElementById("changepass").value="yes";
	}
	else
	{
		document.getElementById("changepass").value="no";
		document.getElementById("newpass").value="";
		document.getElementById("renewpass").value="";
		document.getElementById("allowpassworddiv").style.display="none";
	}
}
function changeadatediv()
{
	var checkin = document.getElementById("changeadate").checked;
	if(checkin==true)
	{
		document.getElementById("allowadate").style.display="block";
		document.getElementById("changeadates").value="yes";
	}
	else
	{
		document.getElementById("allowadate").style.display="none";
		document.getElementById("changeadates").value="no";
	}
}
function deleteadmin(value)
{
	var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO DELETE THIS ADMIN, ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
	if(confirmx==true)
		window.location.href='save.php?task=delete&id='+value;
}
function deleteentry(value)
{
	var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO DELETE THIS ENTRY, ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
	if(confirmx==true)
		window.location.href='save.php?task=deleteen&id='+value;
}
function displaynote(value)
{
	if(value=="no")
	{
		document.getElementById("shownote").style.display="block";
		document.getElementById("checkhirednote").value="yes";
		document.getElementById("showhirepdiv").style.display="none";
		document.getElementById("obdiv").style.display="none";
		document.getElementById("oriendiv").style.display="none";
		document.getElementById("checkhiredp").value="no";
		document.getElementById("checkoprocess").value="no";
	}
	else if(value=="yes")
	{
		document.getElementById("shownote").style.display="none";
		document.getElementById("checkhirednote").value="no";
		document.getElementById("showhirepdiv").style.display="block";
		document.getElementById("checkhiredp").value="yes";
	}
	else
	{
		document.getElementById("shownote").style.display="none";
		document.getElementById("checkhirednote").value="no";
		document.getElementById("showhirepdiv").style.display="none";
		document.getElementById("obdiv").style.display="none";
		document.getElementById("oriendiv").style.display="none";
		document.getElementById("checkhiredp").value="no";
		document.getElementById("checkoprocess").value="no";
	}
}
function displayintnote(value)
{
	if(value=="no")
	{
		document.getElementById("showintnote").style.display="block";
		//document.getElementById("intshow_div").style.display="none";
		document.getElementById("checkintshow").value="no";
	}
	else
	{
		document.getElementById("showintnote").value="";
		//document.getElementById("intshow_div").style.display="block";
		document.getElementById("showintnote").style.display="none";
		document.getElementById("checkintshow").value="yes";
	}
}
function displayhirep(value)
{
	if(value=="3")
	{
		document.getElementById("oriendiv").style.display="block";
		document.getElementById("obdiv").style.display="none";
		document.getElementById("checkoprocess").value="yes";
		document.getElementById("checkobprocess").value="no";
	}
	else if(value=="4")
	{
		document.getElementById("oriendiv").style.display="none";
		document.getElementById("obdiv").style.display="block";
		document.getElementById("checkoprocess").value="no";
		document.getElementById("checkobprocess").value="yes";
	}
	else
	{
		document.getElementById("oriendiv").style.display="none";
		document.getElementById("obdiv").style.display="none";
		document.getElementById("checkoprocess").value="no";
		document.getElementById("checkobprocess").value="no";
	}	
}
function changeodatef()
{
	var value = document.getElementById("checkodate_check").checked;
	if(value==true)
	{
		document.getElementById("odate_div").style.display="block";
		document.getElementById("checkodate").value="yes";
	}
	else
	{
		document.getElementById("odate_div").style.display="none";
		document.getElementById("checkodate").value="no";
	}
}
function changeimgf()
{
	var value = document.getElementById("checkimg_check").checked;
	if(value==true)
	{
		document.getElementById("checkimg_div").style.display="block";
		document.getElementById("checkimg").value="yes";
	}
	else
	{
		document.getElementById("checkimg_div").style.display="none";
		document.getElementById("checkimg").value="no";
	}
}
function changeobdatef()
{
	var value = document.getElementById("checkobdate_check").checked;
	if(value==true)
	{
		document.getElementById("obdate_div").style.display="block";
		document.getElementById("checkobdate").value="yes";
	}
	else
	{
		document.getElementById("obdate_div").style.display="none";
		document.getElementById("checkobdate").value="no";
	}
}
function changeocompdatef()
{
	var value = document.getElementById("checkocompdate_check").checked;
	if(value==true)
	{
		document.getElementById("ocomp_div").style.display="block";
		document.getElementById("checkocompdate").value="yes";
	}
	else
	{
		document.getElementById("ocomp_div").style.display="none";
		document.getElementById("checkocompdate").value="no";
	}
}
function changeobcompdatef()
{
	var value = document.getElementById("checkobcompdate_check").checked;
	if(value==true)
	{
		document.getElementById("obcomp_div").style.display="block";
		document.getElementById("checkobcompdate").value="yes";
	}
	else
	{
		document.getElementById("obcomp_div").style.display="none";
		document.getElementById("checkobcompdate").value="no";
	}
}
function changeoshowdatef()
{
	var value = document.getElementById("checkoshowdate_check").checked;
	if(value==true)
	{
		document.getElementById("oshowdate_div").style.display="block";
		document.getElementById("checkoshowdate").value="yes";
	}
	else
	{
		document.getElementById("oshowdate_div").style.display="none";
		document.getElementById("checkoshowdate").value="no";
	}
}
function changeshowdatef()
{
	var value = document.getElementById("checkintdate_check").checked;
	if(value==true)
	{
		document.getElementById("intshow_div").style.display="block";
		document.getElementById("checkintshow").value="yes";
	}
	else
	{
		document.getElementById("intshow_div").style.display="none";
		document.getElementById("checkintshow").value="no";
	}
}
function changeobshowdatef()
{
	var value = document.getElementById("checkobshowdate_check").checked;
	if(value==true)
	{
		document.getElementById("obshowdate_div").style.display="block";
		document.getElementById("checkobshowdate").value="yes";
	}
	else
	{
		document.getElementById("obshowdate_div").style.display="none";
		document.getElementById("checkobshowdate").value="no";
	}
}
function changeuserview(value)
{
	if(value=="newuser")
		window.location.href='create.php?p=view';
	else if(value=="sortinter" || value=="sortob")
		changeuserviewsperf(value);
	else
		changeuserviews(value);
}
function changeview(value)
{
	var snameen = document.getElementById("snameen").value;
	if(value=="cname" || value=="cdate" || value=="status" || value=="office" )
		window.location.href="view.php?v="+value+"&sname="+snameen;
	else if(value=="all")
		window.location.href="view.php";
	else if(value=="followup")
		window.location.href="viewpend.php";
	else if(value=="gperf")
		window.location.href="viewstatus.php";
}
function changesorttype()
{
	var v = document.getElementById("showview").value;
	var vx="";
	var sn = document.getElementById("snameen").value;
	var sorttype = document.getElementById("sorttype").value;
	var sdsort = document.getElementById("showdatesort").value;
	var sof = document.getElementById("showofficeo").value;
	var scat = document.getElementById("showcato").value;
	if(v !="followup" && v !="gperf")
		vx = v;
	window.location.href="view.php?v="+vx+"&sname="+sn+"&sdtype="+sorttype+"&sdsort="+sdsort+"&sof="+sof+"&scat="+scat;
}
function changeviewpend(value)
{
	var snameen = document.getElementById("snameen").value;
	if(value=="all" || value=="cname" || value=="cdate" || value=="office")
		window.location.href="viewpend.php?v="+value+"&sname="+snameen;
}
function changeuserviews(value)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById('usercont').innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "popupdateuserview.php?taskview="+value;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function changeuserviewsperf(value)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById('usercont').innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "popupdateuserviewperf.php?taskview="+value;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function deletetask(tasks,value)
{
	if(tasks=="users")
	{
		var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO DELETE THIS USER. ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
		if(confirmx==true)
		window.location.href='save.php?task=delete&id='+value;	
	}
	else if(tasks =="entry")
	{
		var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO DELETE THIS ENTRY. ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
		if(confirmx==true)
		window.location.href="save.php?task=deleteen&id="+value;	
	}
	else if(tasks =="office")
	{
		var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO DELETE THIS OFFICE. ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
		if(confirmx==true)
		window.location.href="saveoffice.php?task=delete&id="+value;	
	}
}