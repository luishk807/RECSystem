// JavaScript Document
var xmlHttpReq = null;
var timerx=null;
function getCurrentDate(task)
{
	var newdat="";
	var current_datex=new Date();
	if(task=='all')
	{
		var month=current_datex.getMonth()+1;
		var day=current_datex.getDate();
		var year=current_datex.getFullYear();
		var hour=current_datex.getHours();
		var minutes=current_datex.getMinutes();
		var ampm="am";
		if(hour>=12)
		{
			ampm="pm";
			hour=hour-12;
		}
		if(hour==0)
			hour=12;
		if(minutes<10)
			minutes="0"+minutes;
		if(hour<10)
			hour="0"+hour;
		if(day<10)
			day="0"+day;
		if(month<10)
			month="0"+month;
		newdate=month+"/"+day+"/"+year+" "+hour+":"+minutes+" "+ampm;
	}
	return newdate;
}
function moveOnMax(field,nextid)
{
	if(field.value.length >= field.maxLength){
    document.getElementById(nextid).focus();
  }
}
function expw_odatec_check(value)
{
	if(value==true)
		document.getElementById("expw_ordatec_div").style.display="block";
	else
		document.getElementById("expw_ordatec_div").style.display="none";
}
function date_diff(date1,date2)
{
	var days=0;
	var difference=0;
	var dif_check=false;
	var date1x=Date.parse(date1);
	var date2x=Date.parse(date2);
	if(date1x>date2x)
		dif_check=true;
	return dif_check;
}
function checkAgentCode()
{
	document.getElementById("ccode_result").style.display="block";
	var ccode=document.getElementById("ccode").value;
	var id=document.getElementById("id").value;
	if(ccode.length>3 && id.length>0)
	{
		getHttpPost();
		xmlHttpReq.onreadystatechange = function() 
		{
			if(xmlHttpReq.readyState == 4)
			   document.getElementById('ccode_result').innerHTML=xmlHttpReq.responseText;
		}
		var url="checkagentcode.php?id="+id+"&ccode="+ccode;
		xmlHttpReq.open('POST',url, true);
		//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
		xmlHttpReq.send(null);
	}
	else
		document.getElementById("ccode_result").style.display="none";
}
function showmorex(value,snameen,sdtype,sof,sdsort,scat,msort,msortx,lastnum,stype)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById('viewholder').innerHTML=xmlHttpReq.responseText;
        }
    }
		var url = "popupdateview.php?v="+value+"&sname="+snameen+"&sdtype="+sdtype+"&sdsort="+sdsort+"&sof="+sof+"&scat="+scat+"&msort="+msort+"&msortx="+msortx+"&plimit="+lastnum+"&stype="+stype;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function modalpop_ajax(value)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
		   document.getElementById("popfloader").style.display="none";
           document.getElementById('pop-up-in').innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "modalpopup.php?id="+value;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function showpenpop_view(xoff,xtype,xdate,contg)
{
	clearInterval(intervalID_pen);
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById(contg).innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "changexview.php?xoff="+xoff+"&xdate="+xdate+"&xtype="+xtype;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function showretpop_view(value,yearx)
{
	clearInterval(intervalID_view);
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4)
           document.getElementById('viewret_holder').innerHTML=xmlHttpReq.responseText;
    }
	var url="popretview.php?office_ret="+value+"&year_ret="+yearx;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function showstatspop_view(date1,date2,sfilter)
{
	clearInterval(intervalID_view);
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById('viewstats_holder').innerHTML=xmlHttpReq.responseText;
        }
    }
		var url = "popstatsview.php?date1="+date1+"&date2="+date2+"&sfilter="+sfilter;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function changeOriShow(value)
{
	var oshow=document.getElementById("oshow").value;
	if(value=="yes")
	{
		document.getElementById("ori_show_div_no").style.display="none";
		document.getElementById("ori_show_m_opt").style.display="block";
		if(oshow=="yes")
		{
			document.getElementById("ori_show_div").style.display="block";
			//document.getElementById("checkoshowdate").value='yes';
		}
		
	}
	else if(value=='no')
	{
		document.getElementById("ori_show_div_no").style.display="block";
		document.getElementById("ori_show_m_opt").style.display="none";
		if(oshow=="yes")
		{
			document.getElementById("ori_show_div").style.display="none";
			//document.getElementById("checkoshowdate").value='no';
		}
	}
	else
	{
		document.getElementById("ori_show_div_no").style.display="none";
		document.getElementById("ori_show_m_opt").style.display="none";	
		if(oshow=="yes")
		{
			document.getElementById("ori_show_div").style.display="none";
			//document.getElementById("checkoshowdate").value='yes';
		}
	}
}
function changeOriComp(value)
{
	if(value=="yes")
	{
		document.getElementById("ori_show_opt").style.display="block";
		document.getElementById("ori_comp_div").style.display="block";
		document.getElementById("ori_comp_div_no").style.display="none";
		document.getElementById("ocomp").value='yes';
	}
	else if(value=='no')
	{
		document.getElementById("ori_show_opt").style.display="none";
		document.getElementById("ori_comp_div").style.display="none";
		document.getElementById("ori_comp_div_no").style.display="block";
		document.getElementById("ocomp").value='no'
	}
	else
	{
		document.getElementById("ori_show_opt").style.display="none";
		document.getElementById("ori_comp_div").style.display="none";
		document.getElementById("ori_comp_div_no").style.display="none";
		document.getElementById("ocomp").value='no'
	}
}
function changeAgentCode(value)
{
	var acode_ready=document.getElementById("acode_ready").value;
	if(value=="yes")
	{
		document.getElementById("ccode_div").style.display="block";
		document.getElementById("ccode_no_div").style.display="none";
		if(acode_ready=='yes')
			document.getElementById("checkimg_div").style.display="block";
		document.getElementById("checkacode_info").value="no";
	}
	else if(value=="no")
	{
		document.getElementById("ccode_div").style.display="none";
		document.getElementById("ccode_no_div").style.display="block";
		if(acode_ready=='yes')
			document.getElementById("checkimg_div").style.display="none";
		document.getElementById("checkacode_info").value="yes";
	}
	else
	{
		document.getElementById("ccode_div").style.display="none";
		document.getElementById("ccode_no_div").style.display="none";
		if(acode_ready=='yes')
			document.getElementById("checkimg_div").style.display="none";
		document.getElementById("checkacode_info").value="no";
	}
}
function showdiv()
{
	//clearInterval(timerx);
	document.getElementById("closebtn").style.display="block";
	document.getElementById("closedivlink").style.display="block";
}
function getHttpPost() 
{
	try{	
		xmlHttpReq=new XMLHttpRequest();// Firefox, Opera 8.0+, Safari
	}catch(e)
	{
		try{
			xmlHttpReq=new ActiveXObject("Msxml2.XMLHTTP");// Internet Explorer
		}catch(e)
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
function showmodalaemail_s(value,id)
{
	var url="";
	getHttpPost();
    xmlHttpReq.onreadystatechange = function()
	{
   		if(xmlHttpReq.readyState == 4)
		{
           document.getElementById("modalform").innerHTML=xmlHttpReq.responseText;
        }
    }
	if(value=="edit")
		url = "showeditemail.php?id="+id;
	else 
		url = "showaddemail.php";
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function check_cbox(formname,cvar)
{
	var userc=false;
	var objCheckBoxes = document.forms[formname].elements[cvar];
	var countCheckBoxes = objCheckBoxes.length;
		// set the check value for all check boxes
	for(var i=0;i<countCheckBoxes; i++)
	{
		if(objCheckBoxes[i].checked==true)
		{
			userc=true;
			break;
		}
	}
	if(!userc)
		return false;
	else
		return true;
}
function msg_checkall(FormName, FieldName, CheckValue)
{
	if(!document.forms[FormName])
		return;
	var objCheckBoxes = document.forms[FormName].elements[FieldName];
	if(!objCheckBoxes)
		return;
	var countCheckBoxes = objCheckBoxes.length;
	if(!countCheckBoxes)
		objCheckBoxes.checked = CheckValue;
	else
		// set the check value for all check boxes
		for(var i = 0; i < countCheckBoxes; i++)
			objCheckBoxes[i].checked = CheckValue;
}
function changeStatSort(value,qu,t,ascdesc)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function()
	{
   		if(xmlHttpReq.readyState == 4)
		{
           document.getElementById("wholestat").innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "popupdate_stat.php?task="+value+"&qu="+qu+"&t="+t+"&ascdesc="+ascdesc;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function changeStatSort_s(task,qu)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function()
	{
   		if(xmlHttpReq.readyState == 4)
		{
           document.getElementById("wholestat").innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "popupdate_stat_s.php?sort="+task+"&"+qu;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function changeRetSort_s(task,qu)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function()
	{
   		if(xmlHttpReq.readyState == 4)
		{
           document.getElementById("wholestat").innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "popupdate_ret_s.php?sort="+task+"&"+qu;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function changexpend(xoff,xdate,xtype,contg)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById(contg).innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "changexview.php?xoff="+xoff+"&xdate="+xdate+"&xtype="+xtype;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function phonefill_preloader(value)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
			document.getElementById("phonefillpreload").style.display="none";
			document.getElementById("phonefilldiv").style.display="block";
           document.getElementById("phonefilldiv").innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "phonefill_cont.php?u="+value;
	alert(url);
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function deletexphone(value)
{
	getHttpPost();
	var qu=document.getElementById("qu").value;
	var dateb=document.getElementById("dateb").value;
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById("phonediv").innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "delphone.php?id="+value+"&qu="+qu+"&dateb="+dateb;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
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
function showdivpop_view(value,snameen,sdtype,sof,sdsort,scat,msort,msortx,stype)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById('viewholder').innerHTML=xmlHttpReq.responseText;
        }
    }
		var url = "popupdateview.php?v="+value+"&sname="+snameen+"&sdtype="+sdtype+"&sdsort="+sdsort+"&sof="+sof+"&scat="+scat+"&msort="+msort+"&msortx="+msortx+"&stype="+stype;
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
function setWOffice(value,targ)
{
	if(value !='na')
		window.location.href='setwoffice.php?id='+value+"&targ="+targ;
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
function showmodalstats(value,task)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById('modalform').innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "showmstats.php?qu="+value+"&t="+task;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function showmodalret_s(value)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById('modalform').innerHTML=xmlHttpReq.responseText;
		   document.getElementById("loadergif").style.display="none";
        }
    }
	var url = "showmret_s.php?"+value;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function showmodalstats_s(value)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById('modalform').innerHTML=xmlHttpReq.responseText;
		   document.getElementById("loadergif").style.display="none";
        }
    }
	var url = "showmstats_s.php?"+value;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function checkField_excel()
{
	document.getElementById("importbtn").src="images/loadingbtn.gif";
	if(!errorcheck("normal","file","Please Choose A File To Upload"))
	{
		document.getElementById("importbtn").src="images/importbtn.png";
		return false;
	}
	else
		return true;
	return false;
}
function showmodalcoffice(targ)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById('modalform').innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "showcoffice.php?targ="+targ;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function showmodalphone(value,oid,task,dateb)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById('modalform').innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "showphone.php?qu="+value+"&oid="+oid+"&t="+task+"&dateb="+dateb;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function showmodalphonefill(value)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById('modalform').innerHTML=xmlHttpReq.responseText;
		  // document.getElementById("loadergif").style.display="none";
		   showdiv();
        }
    }
	var url = "showphonefill.php?u="+value;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function errorcheck(task,vars,message)
{
	var color = "#cee838";
	//var color = "#F00";
	message ="ERROR: "+message;
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
function errorcheck_m(task,vars,message,cont)
{
	var color = "#cee838";
	var variable = document.getElementById(vars).value;
	if(task=="text")
	{
		if(variable.length==0 || isNaN(variable)==false)
		{
			document.getElementById(vars).style.background=color;
			document.getElementById(cont).innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById(cont).innerHTML="";
		}
	}
	if(task=="select")
	{
		if(variable=="0")
		{
			document.getElementById(cont).innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(cont).innerHTML="";
		}
	}
	else if(task=="number")
	{
		if(variable.length==0 || isNaN(variable)==true)
		{
			document.getElementById(vars).style.background=color;
			document.getElementById(cont).innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById(cont).innerHTML="";
		}
	}
	else if(task=="normal")
	{
		if(variable.length==0)
		{
			document.getElementById(vars).style.background=color;
			document.getElementById(cont).innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById(cont).innerHTML="";
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
				document.getElementById(cont).innerHTML=message;
				return false;
			}
			else
			{
				document.getElementById(vars).style.background="";
				document.getElementById(cont).innerHTML="";
			}
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById(cont).innerHTML="";
		}
	}
	else if(task=="emailf")
	{
		var email = document.getElementById(vars).value;
		if(variable.length==0 || ((variable.indexOf(".")<2) && (variable.indexOf("@")<=0)))
		{
			document.getElementById(vars).style.background=color;
			document.getElementById(cont).innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById(cont).innerHTML="";
		}
	}
	else if(task=="selects")
	{
		if(document.getElementById(vars).selectedIndex==0)
		{
			document.getElementById(cont).innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(cont).innerHTML="";
		}
	}
	else if(task=="checksa")
	{
		if(document.getElementById(vars).checked==false)
		{
			document.getElementById(cont).innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(cont).innerHTML="";
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
	    if(value =='11')
		{
			document.getElementById("showdatediv").style.display="none";
			document.getElementById("checkdatediv").value="no";
			document.getElementById("awexpw").style.display="block";
			showexpoption('close','');
		}
		else
		{
			document.getElementById("checkdatediv").value="yes";
			document.getElementById("showdatediv").style.display="block";
			document.getElementById("awexpw").style.display="none";
			showexpoption('close','');
		}
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
function showsourcefield_setting(value,valueb)
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
function addMEmail()
{
	//from from adding a new email in mass email settup
	var memail=document.getElementById("memail").value;
	var moptout=document.getElementById("moptout").value;
	if(errorcheck_m("emailf","memail","Please Type A Valid Email Address",'messagef'))
		window.location.href="memail_opt.php?memail="+memail+"&moptout="+moptout+"&task=addemail";
}
function saveMEmail()
{
	//from from adding a new email in mass email settup
	var id=document.getElementById("id").value;
	var memail=document.getElementById("memail").value;
	var moptout=document.getElementById("moptout").value;
	if(errorcheck_m("emailf","memail","Please Type A Valid Email Address",'messagef'))
		window.location.href="memail_opt.php?id="+id+"&memail="+memail+"&moptout="+moptout+"&task=saveemail";
}
function checkField_fp1()
{
	//create reset password page
	if(!errorcheck("email","femail","Please enter a valid email"))
		return false;
	var femail=document.getElementById("femail").value;
	var uname=document.getElementById("uname").value;
	if(femail.length<1 && uname.length<1)
	{
		document.getElementById("femail").style.background="#b5b5b5";
		document.getElementById("uname").style.background="#b5b5b5";
		document.getElementById("message2").innerHTML="You Must Provide A Mean To Find Your Information.<br/>This could be either an email address or your username";
		return false;
	}
	else
	{
		document.getElementById("femail").style.background="";
		document.getElementById("uname").style.background="";
		document.getElementById("message2").innerHTML="";
	}
	return true;
}
function checkField_fp2()
{
	//reset password page
	if(!errorcheck("normal","fpass","Please your new password"))
		return false;
	if(!errorcheck("normal","rfpass","Please re-type your new password"))
		return false;
	var fpass=document.getElementById("fpass").value;
	var rfpass=document.getElementById("rfpass").value;
	if(fpass != rfpass)
	{
		document.getElementById("fpass").style.background="#b5b5b5";
		document.getElementById("rfpass").style.background="#b5b5b5";
		document.getElementById("message2").innerHTML="Both Password Must Match, Please retry";
		return false;
	}
	else
	{
		document.getElementById("fpass").style.background="";
		document.getElementById("rfpass").style.background="";
		document.getElementById("message2").innerHTML="";
	}
	return true;
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
	if(csource=="2")
	{
		if(!errorcheck("normal","csource_title0","Please provide Title Ad For Craigslist"))
		return false;
		c1 = document.getElementById("csource_title0").value;
		document.getElementById("csource_cont").value=c1;
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
	else if(csource=="11")
	{
		if(!errorcheck("normal","csource_title0","Provide explanation of this Unknown Source"))
		return false;
		c1 = document.getElementById("csource_title0").value;
		document.getElementById("csource_cont").value=c1;
	}
	if(csource=="12")
	{
		if(!errorcheck("normal","csource_title0","Please provide Title Ad For Monster"))
		return false;
		c1 = document.getElementById("csource_title0").value;
		document.getElementById("csource_cont").value=c1;
	}
	if(csource=="13")
	{
		if(!errorcheck("normal","csource_title0","Please provide Title Facebook"))
		return false;
		c1 = document.getElementById("csource_title0").value;
		document.getElementById("csource_cont").value=c1;
	}
	return true;
}
function checkFieldb()
{
	//import page form
	//check if date fields is required to check, if it's a walk-in, ignore it otherwise check it
	var checkdatediv = document.getElementById("checkdatediv").value;
	var checkexpress = document.getElementById("checkexpress").value;
	if(!errorcheck("selects","ocall","Please select either this entry is from a call or not"))
		return false;
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
	var cphoneba=document.getElementById("cphoneba").value;
	var cphonebb=document.getElementById("cphonebb").value;
	var cphonebc=document.getElementById("cphonebc").value;
	if(cphoneba.length>0 && cphonebb.length>0 && cphonebb.length)
		document.getElementById("cphonex").value=cphoneba+"-"+cphonebb+"-"+cphonebc;
	else
		document.getElementById("cphonex").value="";
	var changecdate = document.getElementById("changecdate").value;
	if(changecdate=="yes")
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
	if(checkdatediv=="yes")
	{
		if(!errorcheck("normal","idate","Please enter a date for interview"))
			return false;
		if(!errorcheck("selects","chour","Please select hour for interview"))
			return false;
		if(!errorcheck("selects","cminute","Please select minute for interview"))
			return false;
		else
		{
			var chour = document.getElementById("chour").value;
			var cminute = document.getElementById("cminute").value;
			var campm = document.getElementById("campm").value;
			document.getElementById("ctime").value= chour+":"+cminute+" "+campm;
		}
	}
	if(checkexpress=="yes")
	{
		var expw = document.getElementById("expw").value;
		if(expw=="yes")
		{
			if(!errorcheck("selects","expw_showint","Please select either this person showed up for the interview"))
				return false;
			var expw_showint=document.getElementById("expw_showint").value;
			if(expw_showint=="yes")
			{
				if(!errorcheck("selects","expw_hired","Please select either this person is aproved for orientation or not"))
					return false;
			}
		}
	}
	var email = document.getElementById("email").value;
	if(email.length <1)
	{
		var confirmx = window.confirm("System requires an email adddress to send confirmation for interview, you can still process this information without an email but without an email system will not send the confirmation.\r\n\r\nDo You Want To Proceed? or you would prefer to add an email address instead.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
		if(confirmx==false)
			return false;
	}
	//var ocall=document.getElementById("ocall").value;
	var cphonex=document.getElementById("cphonex").value;
	var check_cphonex=document.getElementById("check_cphonex").value;
	var officeid=document.getElementById("officeid").value;
	var check_family=document.getElementById("check_family").value;
	/*if(check_family=="yes" && check_cphonex=='yes')
	{
		if(ocall=='yes' && cphonex.length <2)
		{
			var confirmx = window.confirm("In Order for the system to adquire the real phone number from the caller you are require to terminate this phone call before creating this new entry otherwise system will not be able to fullfill this task.\r\n\r\nIs The Call Terminated? .\r\n\r\nIf Yes, click Okay, if No then click cancel and please hang up the phone before creating this entry");
			if(confirmx==false)
				return false;
			else
			{
				if(check_cphonex=='yes')
				{
					showmodalphone(officeid);
					//timershow();
					return false;
				}
				else
					return true;
				//return true;
			}
		}
		else
		{
			if(check_cphonex=='yes')
			{
				showmodalphone(officeid);
				//timershow();
				return false;
			}
			else
				return true;
		}
	}
	else*/
		return true;
}
function checkFieldc()
{
	//setting_rec form
	var checkcomentry = document.getElementById("checkcomentry").value;
	if(checkcomentry=='yes')
	{
		if(!errorcheck("selects","comentry","Please select recruiter company for this entry"))
			return false;
	}
	//check if date fields is required to check, if it's a walk-in, ignore it otherwise check it
	var checkdatediv = document.getElementById("checkdatediv").value;
	if(!errorcheck("selects","ocall","Please select either this entry is from a call or not"))
		return false;
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
	var cphoneba=document.getElementById("cphoneba").value;
	var cphonebb=document.getElementById("cphonebb").value;
	var cphonebc=document.getElementById("cphonebc").value;
	if(cphoneba.length>0 && cphonebb.length>0 && cphonebb.length)
		document.getElementById("cphonex").value=cphoneba+"-"+cphonebb+"-"+cphonebc;
	else
		document.getElementById("cphonex").value="";
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
	if(checkdatediv=="yes")
	{
		var idate = document.getElementById("changeidate").value;
		if(idate=="yes")
		{
			if(!errorcheck("normal","idate","Please enter a date for interview"))
				return false;
			else
			{
				var confirmx = window.confirm("WARNING!! You are about to change the Interview Date, doing this, system will restart some of the interview result information such as the absense for this interviewed if the entry was PREVIOUSLY SET AS NO SHOW ONLY.\r\n\r\nAll other information will remain intact.\r\n\r\nDo You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
				if(confirmx==false)
					return false;
			}
		}
		if(!errorcheck("selects","chour","Please select hour for interview"))
			return false;
		if(!errorcheck("selects","cminute","Please select minute for interview"))
			return false;
		else
		{
				var chour = document.getElementById("chour").value;
				var cminute = document.getElementById("cminute").value;
				var campm = document.getElementById("campm").value;
				document.getElementById("ctime").value= chour+":"+cminute+" "+campm;
		}
	}
	var email = document.getElementById("email").value;
	if(email.length <1)
	{
		var confirmx = window.confirm("System requires an email adddress to send confirmation for interview, you can still process this information without an email but without an email system will not send the confirmation.\r\n\r\nDo You Want To Proceed? or you would prefer to add an email address instead.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
	if(confirmx==false)
		return false;
	}
	return true;
}
function checkFieldd()
{
	//setrec.php form
	var statusm=document.getElementById("statusm").value;
	var checkm=true;
	var checkshowans=document.getElementById("intshow").value;
	var warset=document.getElementById("warset").value;//used for warning for interview attendance
	var orset=document.getElementById("orset").value;//used for warning for orientation deletion
	var codate=document.getElementById("codate").value;//current orietnation date;
	var oshow=document.getElementById("oshow").value;
	if(!errorcheck("selects","intshow","Please choose whether candidate came for interview"))
		return false;
	if(checkshowans=="no")
	{
		if(!errorcheck("normal","cintnote","Provide reason of candidate absence"))
		return false;
		if(warset=="yes")
		{
			var confirmx=window.confirm("WARNING!! There are important information already saved for this entry, proceding will delete all other information about this entry and will entry to be restarted again and cannot be retrieve again.\r\n\r\nDo You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
			if(confirmx==false)
				return false;
		}
	}
	else if(checkshowans=="cancel")
	{
		if(!errorcheck("normal","cintnote","Provide reason of candidate Cancelling"))
		return false;
		if(warset=="yes")
		{
			var confirmx=window.confirm("WARNING!! There are important information already saved for this entry, proceding will delete all other information about this entry and will entry to be restarted again and cannot be retrieve again.\r\n\r\nDo You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
			if(confirmx==false)
				return false;
		}
	}
	else
	{
		if(!errorcheck("selects","intagent","Please choose Agent For Interview"))
			return false;
		var checkhired=document.getElementById("checkhired").value;
		if(checkhired=="yes")
		{
			if(!errorcheck("selects","hired","Please Interview Result"))
			return false;
		}
		var checkhirednote=document.getElementById("checkhirednote").value;
		if(checkhirednote=="yes")
		{
			if(!errorcheck("normal","cnote","Please write an reason of the interview result"))
			return false;
		}
		if(checkhired=='yes')
		{
			var hired=document.getElementById("hired").value;
			if(hired=="no")
			{
				if(orset=="yes")
				{
					var confirmx=window.confirm("WARNING!! There are orientation information already saved for this entry, proceding will delete all orientation information about this entry and cannot be retrieve again.\r\n\r\nDo You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
					if(confirmx==false)
						return false;
				}
			}
			var checkoprocess=document.getElementById("checkoprocess").value;
			if(checkoprocess=="yes")
			{
				var checkodate=document.getElementById("checkodate").value;
				var poprocess=true;
				var codate=document.getElementById("codate").value;
				var currentDate=getCurrentDate('all');
				if(checkodate=="yes")
				{
					if(!errorcheck("normal","odate","Please choose an orientation date"))
					return false;
					if(!errorcheck("selects","ohour","Please choose an orientation hour"))
					return false;
					if(!errorcheck("selects","ominute","Please choose an orientation minute"))
					return false;
					var odatex=document.getElementById("odate").value+" "+document.getElementById("ohour").value+":"+document.getElementById("ominute").value+" "+String.toLowerCase(document.getElementById("oampm").value);
					if(date_diff(odatex,codate))
					{
						var confirmxo = window.confirm("WARNING! The New Orientation Date You Entered Is After The Current Orientation Date You Currently Set, Proceding Will Reset All Orientation Process And Delete Any Image Already Set.\r\n\r\nDo You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
						if(confirmxo==false)
							return false;
						else
							poprocess=false;
					}
					//if(statusm !='8' && statusm !='20' && statusm !='9' && statusm !='2')
					//{
						if(date_diff(odatex,currentDate))
							poprocess=false;
						else
							poprocess=true;
					//}
				}
				if(!errorcheck("selects","ooffice","Please choose office for orientation"))
					return false;
				if(poprocess && oshow !='yes')
				{
					if(date_diff(codate,currentDate))
						poprocess=false;
				}
				if(!errorcheck("selects","ooffice","Please choose office for orientation"))
					return false;
				if(poprocess)//if new orientation date is choosen and it's greater than the current date;
				{
				if(statusm=='3' || statusm=='7' ||statusm=='18' || statusm=='19' || statusm=='2' || statusm=='5')
				{
					var ori_showx=document.getElementById("ori_show").value;
					if(!errorcheck("selects","ori_show","Please choose whether or not candidate showed up for orientation"))
						return false;
					if(ori_showx=='no')
					{
						if(!errorcheck("normal","orishownote","Please write an reason of the orientation absent"))
							return false;
					}
					else
					{
						var checkoshowdate=document.getElementById("checkoshowdate").value;
						//var oshowdate=document.getElementById("oshowdate").value;
						//var oshowhour=document.getElementById("oshowhour").value;
						//var oshowminute=document.getElementById("oshowminute").value;
						//var oshowampm=document.getElementById("oshowampm").value;
						if(checkoshowdate=="yes" && oshow=='yes')
						{
							var oshowdate=document.getElementById("oshowdate").value;
							var oshowhour=document.getElementById("oshowhour").value;
							var oshowminute=document.getElementById("oshowminute").value;
							var oshowampm=document.getElementById("oshowampm").value;
							if(!errorcheck("normal","oshowdate","Please choose an orientation attendance date"))
							return false;
							if(!errorcheck("selects","oshowhour","Please choose an orientation attendance hour"))
							return false;
							if(!errorcheck("selects","oshowminute","Please choose an orientation attendance minute"))
							return false;
						}
						var checkocompdate=document.getElementById("checkocompdate").value;
						//alert(checkocompdate);
						if(checkocompdate=="yes")
						{
							if(!errorcheck("selects","ori_comp","Please choose whether or not candidate completed orientation"))
								return false;
							var ori_comp=document.getElementById("ori_comp").value;
							if(ori_comp=="yes")
							{
								if(!errorcheck("normal","ocompdate","Please provide date that orientation was completed"))
								return false;
							}
							else if(ori_comp=="no")
							{
								if(!errorcheck("normal","oricompnote","Please provide reason why orientation was not completed"))
								return false;
							}
						}
						else
						{
							if(statusm=='5')
							{
								if(!errorcheck("selects","ori_comp","Please choose whether or not candidate completed orientation"))
									return false;
								var ori_comp=document.getElementById("ori_comp").value;
								if(ori_comp=="no")
								{
									if(!errorcheck("normal","oricompnote","Please provide reason why orientation was not completed"))
									return false;
								}
							}
						}
						var ocomp=document.getElementById("ocomp").value;
						if((statusm=='7' || statusm=='2' || statusm=='5') && ocomp=='yes')
						{
							if(!errorcheck("selects","acode_show","Please choose whether or not you have candidate agent code"))
								return false;
							var acode_show=document.getElementById("acode_show").value;
							if(acode_show=='yes')
							{
								if(!errorcheck("normal","ccode","Please provide agent code"))
									return false;
								checkAgentCode();
								var ccode_dup=document.getElementById("ccode_dup").value;
								if(ccode_dup=="yes")
								{
									document.getElementById("message2").innerHTML="ERROR: Duplicate Agent Code";
									return false;
								}
								else
								{
									document.getElementById("message2").innerHTML="";
								}
								if(!errorcheck("selects","report_to","Select Who This Agent Reports To"))
									return false;
								if(!errorcheck("selects","trained_by","Select Who Trained This Agent"))
									return false;
							}
							else if(acode_show=='no')
							{
								if(!errorcheck("normal","ccode_info","Please provide reason of why agent code is not avaliable"))
									return false;	
								else
								{
									if(statusm !='2')
									{
										var confirmx=window.confirm("WARNING!! YOU ARE ABOUT TO SET THIS AGENT WITH NO AGENT CODE AVALIABLE MEANING THAT HIS/HER STATUS WILL BE CONSIDERED NOT HIRED.\r\n\r\nYou can come back later after you have his/her agent code or you can just continue with your choice\r\n\r\nDo You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
										if(confirmx==false)
											return false;
									}
								}
							}
							var checkimg=document.getElementById("checkimg").value;
							if(checkimg=="yes")
							{
								if(!errorcheck("normal","imgprof","Please choose an image to upload"))
									return false;
							}
						}
					}
				}
				}
			}
		}
	}
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
	if(!warningpop("status","ustatus",""))
		return false;
	if(!warningpop("type","utype",document.getElementById("utype").value))
		return false;
	var checktype = document.getElementById("checktype").value;
	if(checktype=="yes")
	{
		if(!errorcheck("selects","officeman","Please select the office for this manager or team leader"))
		return false;
	}
	var checkreportt = document.getElementById("checkreportt").value;
	if(checkreportt=="yes")
	{
		if(!errorcheck("selects","reportto","Please select the manager that this team leader reports to"))
		return false;
	}
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
	if(!warningpop("status","ustatus",""))
		return false;
	if(!warningpop("type","utype",document.getElementById("utype").value))
		return false;
	var checktype = document.getElementById("checktype").value;
	if(checktype=="yes")
	{
		if(!errorcheck("selects","officeman","Please select the office for this manager or team leader"))
		return false;
	}
	var checkreportt = document.getElementById("checkreportt").value;
	if(checkreportt=="yes")
	{
		if(!errorcheck("selects","reportto","Please select the manager that this team leader reports to"))
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
/*function allowpassword()
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
}*/
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
function displayintnote(value)
{
	if(value=="no" || value=="cancel")
	{
		document.getElementById("int_show_opt").style.display="none";
		document.getElementById("showintnote").style.display="block";
		//document.getElementById("intshow_div").style.display="none";
		document.getElementById("intmangdiv").style.display="none";
		//document.getElementById("checkintshow").value="no";
	}
	else
	{
		document.getElementById("int_show_opt").style.display="block";
		document.getElementById("showintnote").value="";
		document.getElementById("intmangdiv").style.display="block";
		//document.getElementById("intshow_div").style.display="block";
		document.getElementById("showintnote").style.display="none";
		//document.getElementById("checkintshow").value="yes";
	}
}
function displaynote(value)
{
	if(value=="no" || value=="notint")
	{
		document.getElementById("shownote").style.display="block";
		document.getElementById("checkhirednote").value="yes";
		document.getElementById("oriendiv").style.display="none";
		document.getElementById("checkoprocess").value="no";
	}
	else if(value=="yes")
	{
		document.getElementById("shownote").style.display="none";
		document.getElementById("checkhirednote").value="no";
		document.getElementById("oriendiv").style.display="block";
		document.getElementById("checkoprocess").value="yes";
	}
	else
	{
		document.getElementById("shownote").style.display="none";
		document.getElementById("checkhirednote").value="no";
		document.getElementById("oriendiv").style.display="none";
		document.getElementById("checkoprocess").value="no";
	}
}
function displayhirep(value)
{
	if(value=="3")
	{
		document.getElementById("oriendiv").style.display="block";
		document.getElementById("checkoprocess").value="yes";
	}
	else
	{
		document.getElementById("oriendiv").style.display="none";
		document.getElementById("checkoprocess").value="no";
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
		//document.getElementById("ori_comp").value='yes';
	}
	else
	{
		document.getElementById("ocomp_div").style.display="none";
		document.getElementById("checkocompdate").value="no";
		//document.getElementById("ori_comp").value='no';
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
	else if(value=="gstats")
		window.location.href="viewstats.php";
	else if(value=="reten")
		window.location.href="viewretention.php";
}
function changesorttype()
{
	var v = document.getElementById("showview").value;
	var vx="";
	var sn = document.getElementById("snameen").value;
	//var sorttype = document.getElementById("sorttype").value;
	//var sdsort = document.getElementById("showdatesort").value;
	var sof = document.getElementById("showofficeo").value;
	var scat = document.getElementById("showcato").value;
	var stype = document.getElementById("showtype").value;
	if(v !="followup" && v !="gperf")
		vx=v;
	window.location.href="view.php?v="+vx+"&sname="+sn+"&sof="+sof+"&scat="+scat+"&stype="+stype;
}
function changesorttype_sort(msort,msortx)
{
	var v = document.getElementById("showview").value;
	var vx="";
	var sn = document.getElementById("snameen").value;
	//var sorttype = document.getElementById("sorttype").value;
	//var sdsort = document.getElementById("showdatesort").value;
	var sof = document.getElementById("showofficeo").value;
	var scat = document.getElementById("showcato").value;
	var stype = document.getElementById("showtype").value;
	if(v !="followup" && v !="gperf")
		vx=v;
	window.location.href="view.php?v="+vx+"&sname="+sn+"&sof="+sof+"&scat="+scat+"&stype="+stype+"&msort="+msort+"&msortx="+msortx;
}
function changeviewpend(value)
{
	var snamen = document.getElementById("snamen").value;
	var ascdesc = document.getElementById("ascdesc").value;
	if(value=="all")
		window.location.href="viewpend.php";
	else if(value=="cname" || value=="idate" || value=="office")
		window.location.href="viewpend.php?v="+value+"&sname="+snamen+"&ascdesc="+ascdesc;
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
	//var url = "popupdateuserviewperf.php?taskview="+value;
	var url ="popupdateuserview.php?taskview="+value;
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
function hideexption()
{
	document.getElementById("checkexpress").value="no";
	document.getElementById("expw").selectedIndex=0;
	document.getElementById("expw_gos").checked=false;
	document.getElementById("awexpw_sub").style.display="none";
	document.getElementById("expw_int").selectedIndex=0;
	document.getElementById("expw_showint").selectedIndex=0;
	document.getElementById("awexpw_sub_result").style.display="none";
	document.getElementById("expw_hired").selectedIndex=0;
	document.getElementById("awexpw_sub_reason").style.display="none";
	document.getElementById("expw_reason").selectedIndex=0;
	document.getElementById("awexpw_sub_hired").style.display="none";
	document.getElementById("expw_ordate").checked=false;
	document.getElementById("expw_ooff").selectedIndex=0;
	document.getElementById("expw_ordatec").checked=false;
}
function showexpoption(task,value)
{
	if(task=="close")
	{
		hideexption();
	}
	else if(task=="expoption")
	{
		if(value=="yes")
		{
			document.getElementById("awexpw_sub").style.display="block";
			document.getElementById("expw_int").selectedIndex=0;
			document.getElementById("expw_showint").selectedIndex=0;
			document.getElementById("checkexpress").value="yes";
		}
		else
		{
			if(value=="noy")
			{
				document.getElementById("checkexpress").value="no";
				document.getElementById("expw_gos").checked=false;
				document.getElementById("awexpw_sub").style.display="none";
				document.getElementById("expw_int").selectedIndex=0;
				document.getElementById("expw_showint").selectedIndex=0;
				document.getElementById("awexpw_sub_result").style.display="none";
				document.getElementById("expw_hired").selectedIndex=0;
				document.getElementById("awexpw_sub_reason").style.display="none";
				document.getElementById("expw_reason").selectedIndex=0;
				document.getElementById("awexpw_sub_hired").style.display="none";
				document.getElementById("expw_ordate").checked=false;
				document.getElementById("expw_ooff").selectedIndex=0;
				document.getElementById("expw_ordatec").checked=false;
			}
			else
				hideexption();
		}
	}
	else if(task=="expresult")
	{
		expw_odatec_check(false);
		document.getElementById("expw_hired").selectedIndex=0;
		document.getElementById("awexpw_sub_reason").style.display="none";
		if(value=="yes")
		{
			document.getElementById("awexpw_sub_result").style.display="block";
			document.getElementById("expw_reason").selectedIndex=0;
		}
		else if(value=="no")
		{
			document.getElementById("awexpw_sub_reason").style.display="block";
			document.getElementById("awexpw_sub_result").style.display="none";
			document.getElementById("awexpw_sub_hired").style.display="none";
			document.getElementById("expw_reason").selectedIndex=0;
			document.getElementById("expw_ordate").checked=false;
			document.getElementById("expw_ooff").selectedIndex=0;
			document.getElementById("expw_ordatec").checked=false;
		}
		else if(value=="cancel")
		{
			document.getElementById("awexpw_sub_reason").style.display="block";
			document.getElementById("awexpw_sub_result").style.display="none";
			document.getElementById("awexpw_sub_hired").style.display="none";
			document.getElementById("expw_reason").selectedIndex=0;
			document.getElementById("expw_ordate").checked=false;
			document.getElementById("expw_ooff").selectedIndex=0;
			document.getElementById("expw_ordatec").checked=false;
		}
		else
		{
			document.getElementById("awexpw_sub_result").style.display="none";
			document.getElementById("awexpw_sub_hired").style.display="none";
			document.getElementById("expw_ordate").checked=false;
			document.getElementById("expw_reason").selectedIndex=0;
			document.getElementById("expw_ooff").selectedIndex=0;
			document.getElementById("expw_ordatec").checked=false;	
		}
	}
	else if(task=="expreason")
	{
		expw_odatec_check(false);
		//document.getElementById("expw_reason").selectedIndex=0;
		document.getElementById("expw_ordate").checked=false;
		document.getElementById("expw_ooff").selectedIndex=0;
		document.getElementById("expw_ordatec").checked=false;
		if(value=="yes")
		{
			document.getElementById("awexpw_sub_reason").style.display="none";		
			document.getElementById("awexpw_sub_hired").style.display="block";
		}
		else
		{
			document.getElementById("awexpw_sub_reason").style.display="block";
			document.getElementById("awexpw_sub_hired").style.display="none";
		}
	}
}
function delphone(value)
{
	//alert('here');
	showWarning('delphone',value)
}
/*function allowofficeman(value)
{
	if(value=="5")
	{
		document.getElementById("officemandiv").style.display="block";
		document.getElementById("reporttodiv").style.display="block";
		document.getElementById("checktype").value="yes";
		document.getElementById("checkreportt").value="yes";
	}
	else if(value=="6")
	{
		document.getElementById("officemandiv").style.display="block";
		document.getElementById("reporttodiv").style.display="none";
		document.getElementById("checktype").value="yes";
		document.getElementById("checkreportt").value="no";
	}
	else
	{
		document.getElementById("officemandiv").style.display="none";
		document.getElementById("reporttodiv").style.display="none";
		document.getElementById("checktype").value="no";
		document.getElementById("checkreportt").value="no";
	}
}*/
function warningpop(task,valuex,valuexb)
{
	//show differnet warning pop
	var value= document.getElementById(valuex).value;
	if(task=="status")
	{
		if(value =="2" || value=="3")
		{
			var confirmx = window.confirm("WARNING! You Are About To Block Access For This User!.\r\n\r\nUser Wouldn't be able to access Map System, Task Manager System and Master Recuiter System.\r\n\r\nDo You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
			if(confirmx==false)
				return false;
			else
				return true;
		}
		return true;
	}
	else if(task=="type")
	{
		if(value =="1" || value=="2" || value=="4")
		{
			var confirmx = window.confirm("WARNING! You Are About To Grant Administrator Access To This User!. Doing so user will be able to do task that are normally exclusive for a Super Admin, Admin, and Web Designer!.\r\n\r\nDo You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
			if(confirmx==false)
				return false;
			else
				return true;
		}
		return true;
	}
	else if(task=="type_c")
	{
		if(value =="1" || value=="2" || value=="4")
		{
			var confirmx = window.confirm("WARNING! You Are About To Grant Administrator Access To This User!. Doing so user will be able to do task that are normally exclusive for a Super Admin, Admin, and Web Designer!.\r\n\r\nDo You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
			if(confirmx==false)
				return false;
			else
				return true;
		}
		return true;
	}
	return true;
}
function showWarning(task,value)
{
	if(task !="")
	{
		if(task=="delete")
		{
			var confirmx = window.confirm("YOU ARE ABOUT TO DELETE THIS USER PERMANETLY AND INFORMATION CAN'T BE RETRIEVE, Are You Sure You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
			if(confirmx==true)
				window.location.href='save.php?id='+value+'&task=deleteen&p=comp';
		}
		else if(task=="addnew")
		{
			var confirmx = window.confirm("YOU ARE ABOUT TO ADD THIS USER INTO THE SYSTEM AS A NEW ENTRY, Are You Sure You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
			if(confirmx==true)
				window.location.href='save.php?task=createrec&overwrite=yes';
		}
		else if(task=="updatenew")
		{
			var confirmx = window.confirm("YOU ARE ABOUT TO UPDATE THE CHOOSEN USER WITH THE NEW INFORMATION. THIS ACTION WILL NOT CAUSE ANY OTHER INFORMATION TO BE LOST SUCH AS IMAGES OR AGENT ID UNLESS IF THE EVENT FALLS FOR ANY OF THE FOLLOWING:\r\n\r\n1.IN THE EVENT THAT EXPRESS WALK-IN OPTION IS CHOSEN AND YOUR UPDATE STATES THAT THE ENTRY IS NOT HIRED OR ENTRY HAS NO SHOWN FOR INTERVIEW OR ORIENTATION COMPLETED IS NOT SET ALL INFORMATION REGARDING TO ORIENTATION OR INFORMATION AFTER WILL BE DELETED TO MAINTAIN STABILITY IN THE SYSTEM.\r\n\r\n2.IN THE EVENT THAT THE INTERVIEW DATE IT'S GREATER FROM INTERVIEW DATE OF THE ENTRY YOU CHOOSE TO UPDATE, SYSTEM WILL RESET THIS ENTRY INFORMATION AND DELETE ALL INFORMATION YOU SAVED FOR THE ORIENTATION FOR THIS ENTRY\r\n\r\n3.IN THE EVENT THAT THE ORIENTATION DATE IT'S GREATER FROM ORIENTATION DATE OF THE ENTRY YOU CHOOSE TO UPDATE, SYSTEM WILL RESET THIS ENTRY INFORMATION AFTER THE ORIENTATION DATE AND OFFICE.\r\n\r\nAre You Sure You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
			if(confirmx==true)
				window.location.href='save.php?task=saverec&id='+value;
		}
		else if(task=="delphone")
		{
			var confirmx = window.confirm("YOU ARE ABOUT TO DELETE THE CHOSEN PHONE. THIS ACTION WILL ONLY DELETE THE PHONE FROM THE SYSTEM AND WILL NOT CAUSE ANY OTHER INFORMATION TO BE LOST SUCH AS IMAGES OR AGENT ID, Are You Sure You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
			if(confirmx==true)
				deletexphone(value);
		}
	}
}