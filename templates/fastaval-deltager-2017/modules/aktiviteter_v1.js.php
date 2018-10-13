<?php ?>
function flipDiv(name,div){
     var obj = document.getElementById(name);
     if (obj.checked){
          var obj = document.getElementById(div);
          obj.style.display = "block";
     }else{
          var obj = document.getElementById(div);
          obj.style.display = "none";
     }
}

function chainDisable(obj,bool,others){
	if (obj.checked==bool){
		for (var i=0;i<others.length;i++){
			var o=document.getElementById(others[i]);
			o.disabled=true;
		}
	}else {
		for (var i=0;i<others.length;i++){
			var o=document.getElementById(others[i]);
			o.disabled=false;
		}
	}
}
function chainDisableNow(obj,bool,others){
	obj = jQuery('#'+obj);
	if (obj.is(":checked")){
		for (var i=0;i<others.length;i++){
			var o=document.getElementById(others[i]);
			o.disabled=true;
		}
	}else {
		for (var i=0;i<others.length;i++){
			var o=document.getElementById(others[i]);
			o.disabled=false;
		}
	}
}

function previousClick()
{
     var obj = document.getElementById("direction");
     obj.value="-1";
     obj = document.getElementById("main_form");
     obj.submit();
}

/**
 * 
 *  Event table
 *
 **/

if (!Array.prototype.contains){
    Array.prototype.contains = function(obj){
    var len = this.length;
    for (var i = 0; i < len; i++){
      if(this[i]===obj){return true;}
    }
    return false;
  };
}	

function popup2(ur,framename,width,height)
{
     var centerWidth=(screen.width/2)-(width/2);
     var centerHeight=(screen.height/2)-(height/2);
     //var mywindow = window.open(url,framename, 'location=no,scrollbars=yes,status=no,height='+height+',width='+width+',top='+centerHeight+',left='+centerWidth);
     var mywindow = window.open(ur,framename,'location=no,scrollbars=yes,status=no,height='+height+',width='+width+',top='+centerHeight+',left='+centerWidth);
     if(mywindow.opener==null) 
          mywindow.opener = self;
}
function popup(url){
     popup2(url,"popdenop",820,400);
}


function nextPriority(e,name,start,end,day)
{
	var radio = document.getElementsByName(name);
	var radiolength = radio.length;
	var radiovalue = -1;
	for (var i=0;i<radiolength;i++)
	{
		if (radio[i].checked)
		{
			radiovalue = radio[i].value;
			radio[i].checked = false;
		}
	}
	// subtract
	

	var remainers = new Array(0,1,2,3,4,5);
	var foes = new Array();

	var max_prio = 5;
	if (event_has_gms[name]*1==0){
	    max_prio = 3;
        remainers = new Array(0,1,2,3);
	}
	
	for (var d=1;d<=31;d++)
	{
        for (var i=0;i<26*2;i++)
        {
     		if (timetable[d][i])
     		{
				if (timetable[d][i].contains(name))
				{
          			for (var j=0;j<timetable[d][i].length;j++)
          			{
          				if (!foes.contains(timetable[d][i][j]))
          					foes[foes.length]=timetable[d][i][j];
          			}
				}
     		}
        }
	}
	
	for (var i=0;i<foes.length;i++)
		remainers = subtractEvent(foes[i],remainers);
	    
	radiovalue++;
	while (!remainers.contains(radiovalue))
	{
		radiovalue++;
		if (radiovalue>max_prio){
    		radiovalue=0;
		}
	}
	
	
	for (var i=0;i<radiolength;i++)
	{
		if (radio[i].value==radiovalue){
			radio[i].checked = true;
		}
	}
	fixDisplay(name,radiovalue);
     var event = e || window.event;
     return false;
}
function fixDisplay(name,radiovalue)
{
    
	jQuery('#'+name).removeClass('priority0 priority1 priority2 priority3 priority4 priority5');
	jQuery('#'+name).addClass('priority'+radiovalue);
	jQuery("#"+name+"_caption").html(caption[radiovalue]);
	
    if (chains[name])
    {
        for (var i=0;i<chains[name].length;i++)
        {
            fixDisplay(chains[name][i],radiovalue);
        }
    }
}

function resetDay(day){
     for (var i=0;i<=26*2;i++){
          if (timetable[day][i])
          for (var j=0;j<timetable[day][i].length;j++){
               var event = timetable[day][i][j];
               setEventValue(event,0);
               fixDisplay(event,0);
          }
     }
}

var timetable=new Array();
for(var i=0;i<=31;i++)
	timetable[i] = new Array();

var event_has_gms = new Array();

var chains=new Array();
function addEvent(eventid , from , to , day , has_gm)
{
    event_has_gms[eventid] = has_gm;
    
	for (var i=from;i<to;i++)
	{
		if (timetable[day][i])
		{
			count = timetable[day][i].length;
			timetable[day][i][count] = eventid;
		}
		else
		{
			timetable[day][i] = new Array();
			timetable[day][i][0] = eventid;
		}
	}
}

function attachEvent(eventid,multiblok,from,to,day){
     if (chains[eventid])
          chains[eventid][chains[eventid].length] = eventid+"_m"+multiblok;
     else{
          chains[eventid] = new Array();
          chains[eventid][0] = eventid+"_m"+multiblok;
     }
     
	for (var i=from;i<to;i++){
		if (timetable[day][i]){
			timetable[day][i][timetable[day][i].length] = eventid;
		}else{
			timetable[day][i] = new Array();
			timetable[day][i][0] = eventid;
		}
	}
}
function getEventValue(name){
	var radio = document.getElementsByName(name);
	var radiolength=radio.length;
	var radiovalue = -1;
	for (var i=0;i<radiolength;i++){
		if (radio[i].checked){
			radiovalue = radio[i].value;
		}
	}
	return radiovalue;
}
function setEventValue(name,value){
	var radio = document.getElementsByName(name);
	var radiolength=radio.length;
	for (var i=0;i<radiolength;i++){
		if (radio[i].value==value){
			radio[i].checked = true;
		}
	}
}
function subtractEvent(name,remainers){
	var radiovalue = getEventValue(name);
	if ((radiovalue==0)||(radiovalue==4)||(radiovalue==3))radiovalue=-1;
	var nr=new Array();
	for (var i=0;i<remainers.length;i++){
		if (remainers[i]!=radiovalue)nr[nr.length] = remainers[i];
	}
	return nr;
}
