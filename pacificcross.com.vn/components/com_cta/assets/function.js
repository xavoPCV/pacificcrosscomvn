// JavaScript Document
function isBlank(str) {
	str = removeExtraSpaces(str," ");
	str = removeLeadingAndTrailingChar(str," ");
	var len = str.length;
	var enter = 0;
	if(len == 0) return true;
	//Check if user only enter
	for (i=0; i<len; i++) {
		if((str.charCodeAt(i) == 13)||(str.charCodeAt(i) == 10)||(str.charCodeAt(i) == 32))
			enter = 0;
		else
			return false;
	}	  
	return true;
}

function checkLength(str, intLength) {
	str = removeExtraSpaces(str," ");
	str = removeLeadingAndTrailingChar(str," ");
	if (str.length<intLength) return true;
	return false;
}

function removeExtraSpaces(string, delimiter) {
	var returnString = "";
	splitstring = string.split(delimiter);
	for(i = 0; i < splitstring.length; i++) {
    	if (splitstring[i]!="") returnString += splitstring[i] + delimiter;
	}
	return returnString;
}

function removeLeadingAndTrailingChar(inputString, removeChar)  {
	var returnString = inputString;
	if (removeChar.length) {
		while(''+returnString.charAt(0)==removeChar) {
			returnString=returnString.substring(1,returnString.length);
		}
		while(''+returnString.charAt(returnString.length-1)==removeChar) {
			returnString=returnString.substring(0,returnString.length-1);
	  	}
	}
	return returnString;
}

function ValidEmail(EmailAddr) {
    var reg1 = /(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/;
    var reg2 = /^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/;
    var SpecChar="!#$%^&*()'+{}[]\|:;?/><,~`" + "\"";
    var frmValue = new String(EmailAddr);
    var len = frmValue.length;
    if( len < 1 ) { return false; }
    for (var i=0;i<len;i++){
        temp=frmValue.substring(i,i+1)
        if (SpecChar.indexOf(temp)!=-1){
            return false;
        }
    }
    if(!reg1.test(frmValue) && reg2.test(frmValue)){
        return true;
    }
    return false;
}

function sel_check_value(form, sel_name) {
	
	var radio_value = 0;
	for (i = 0; i < form.elements.length; i++){
		if (form.elements[i].name == sel_name) {
			var selectedIndex = form.elements[i].selectedIndex;
			if (selectedIndex!='-1') {
				radio_value = form.elements[i].options[selectedIndex].value;
				break;
			}
		}//if
	}//for
	
	return radio_value;
	
}//function

function radio_check(form, sel_name) {
	
	var bln_check = false;
	
	for (i = 0; i < form.elements.length; i++){
		if(form.elements[i].name == sel_name) {
			if (form.elements[i].checked) {
				bln_check = true;
				break;
			}
		}
	}
	
	return bln_check;
	
}//end function

function radio_val(form, sel_name) {
	
	var bln_check = '';
	
	for (i = 0; i < form.elements.length; i++){
		if(form.elements[i].name == sel_name) {
			if (form.elements[i].checked) {
				bln_check = form.elements[i].value;
			}//if
		}
	}
	
	return bln_check;
	
}//end function

function checkbox_check(form, sel_name) {
	
	var bln_check = false;
	
	for (i = 0; i < form.elements.length; i++){
		if(form.elements[i].name == sel_name) {
			if (form.elements[i].checked) {
				bln_check = true;
				break;
			}
		}
	}
	
	return bln_check;
}

function checkbox_active(form, sel_name, val){
	
	val = ', '+val.toString()+',';
	
	for (i = 0; i < form.elements.length; i++){
		if(form.elements[i].name == sel_name) {
			if (val.search(', '+form.elements[i].value+',')!=-1) {
				form.elements[i].checked = true;
			}
		}
	}
	
}

function check_all(form, field, value) {

	for (i = 0; i < form.elements.length; i++){
		if(form.elements[i].name == field) form.elements[i].checked = value;
	}//for
	
	var detect_field = document.getElementsByName('check_clear_all');
	if (!detect_field) detect_field = document.getElementById('check_clear_all');
	if (detect_field) form.elements['check_clear_all'].checked = value;
	
}

function radio_active(form, sel_name, val){
	//var f = eval("document.form1."+sel_name);
	for (i = 0; i < form.elements.length; i++){
		if(form.elements[i].name == sel_name) {
			if (form.elements[i].value == val) {
				form.elements[i].checked = true;
				break;
			}
		}
	}
}

function select_multi_active(form, sel_name, val){
	//var f = eval("document.form1."+sel_name);

	val = ', ' + val.toString() + ',';
	
	for (i = 0; i < form.elements.length; i++){
		if (form.elements[i].name == sel_name) {
			for (var j=0; j<form.elements[i].length; j++){
				if (val.search(', ' + form.elements[i].options[j].value + ',') != -1) {
					form.elements[i].options[j].selected = true;
				}//if
			}		
		}
	}
}//function



function textbox_check(form, sel_name) {
	
	var bln_check = false;
	for (i = 0; i < form.elements.length; i++){
		
		if(form.elements[i].name == sel_name) {
			if (form.elements[i].value!='') {
				bln_check = form.elements[i].value;
			}
			break;
		}//if
	}
	
	return bln_check;
}

function textbox_set(form, sel_name, val) {
	
	for (i = 0; i < form.elements.length; i++){
		if(form.elements[i].name == sel_name) {
			form.elements[i].value = val;
			break;
		}//if
	}
}

function uncheck_checkbox(form, sel_name) {
	
	for (i = 0; i < form.elements.length; i++){
		if(form.elements[i].name == sel_name) {
			form.elements[i].checked = false;
		}//if
	}
}


function check_select_multi(form, sel_name){
	for (i = 0; i < form.elements.length; i++){
		if (form.elements[i].name == sel_name) {
			for (var j=0; j<form.elements[i].length; j++){
				if (form.elements[i].options[j].selected == true) {
					return true;
					break;
				}	
			}		
		}//if
	}//for
}//function


<!-- Dynamic Version by: Nannette Thacker -->
<!-- http://www.shiningstar.net -->
<!-- Original by :  Ronnie T. Moore -->
<!-- Web Site:  The JavaScript Source -->
<!-- Use one function for multiple text areas on a page -->
<!-- Limit the number of characters per textarea -->
<!-- Begin
function textCounter(field, maxlimit, span_show) {
	
	var show_val = 0;
	
	if (field.value.length) {
		if (field.value.length < maxlimit) {
			//show_val = maxlimit - field.value.length;
			show_val = field.value.length;
		} else {
			field.value = field.value.substring(0, maxlimit);
			show_val = maxlimit;
		}
	}//if
	 
	 document.getElementById(span_show).innerHTML = show_val;
	 
}//func


function create_request_string(theForm)
{
	var reqStr = "";
	
	for(i=0; i < theForm.elements.length; i++)
	{
		isFormObject = false;
		
		switch (theForm.elements[i].tagName)
		{
		case "INPUT":
		
			switch (theForm.elements[i].type)
			{
			case "text":
			case "hidden":
				reqStr += theForm.elements[i].name + "=" + encodeURIComponent(theForm.elements[i].value);
				isFormObject = true;
			break;
			case "checkbox":
				if (theForm.elements[i].checked)
				{
					reqStr += theForm.elements[i].name + "=" + theForm.elements[i].value;
					isFormObject = true;
				}else{
					//reqStr += theForm.elements[i].name + "=";
					isFormObject = false;
				}
				
			break;
		
			case "radio":
				if (theForm.elements[i].checked)
				{
					reqStr += theForm.elements[i].name + "=" + theForm.elements[i].value;
					isFormObject = true;
				}
			}
		break;
		
		case "TEXTAREA":
		
			reqStr += theForm.elements[i].name + "=" + encodeURIComponent(theForm.elements[i].value);
			isFormObject = true;
		break;
		
		case "SELECT":
			var sel = theForm.elements[i];
			reqStr += sel.name + "=" + sel.options[sel.selectedIndex].value;
			isFormObject = true;
			break;
		}//switch
		
		if ((isFormObject) && ((i+1)!= theForm.elements.length))
		{
			reqStr += "&";
		}
	
	}//for
	
	return reqStr;
}//func

// JavaScript Document
function select_active(form, sel_name, val){
	//var f = eval("document.form1."+sel_name);

	for (i = 0; i < form.elements.length; i++){
		if (form.elements[i].name == sel_name) {
			for (var j=0; j<form.elements[i].length; j++){
				if (form.elements[i].options[j].value == val) {
					form.elements[i].selectedIndex = j;
					break;
				}
			}		
		}
	}
	
}

function detectFlashPlayer(){
	if(typeof navigator.plugins!="undefined"&&typeof navigator.plugins["Shockwave Flash"]!="undefined"){
		return true
	}
	if(typeof window.ActiveXObject!="undefined"){
		try{
			new ActiveXObject("ShockwaveFlash.ShockwaveFlash");
			return true
		}catch(c){}
	}
	return false;
}