$(function(){
	var ajaxData = "";
	// Accordion the schedule
	var jEventAnchor = $(".eventList a");
	var jMySchedList= $("#myScheduleList");
	$("var").hide();
	
	$(".dateList").accordion({ 
		header: ".trackTitle" ,
		autoHeight: false
		});
		
	$("#list_main").accordion({ 
		header: ".slotDate",
		autoHeight: false
		});
	applyStyle();
	jEventAnchor.each(
		function(intEventIndex,objEvent){
			var jEventThis = $(this);
			// when i click a title in the schedule list 
			jEventThis.click(
				function( objEvent ){
					var thisEvent = $(this);
					var thisStartTxt = $(this).find(".startTime").text();
					var thisStart = formatEventTime($(this).find(".startTime").text());
					var thisEnd = formatEventTime($(this).find(".endTime").text());
					var thisEventID = $(this).find("[title=eventID]").text();
					var thisEventCharReqP = $(this).closest("[data-includeChar]").attr("data-includeChar");
					var thisPlayerID = $(this).closest("[data-playerID]").attr("data-playerID");
					//alert(thisPlayerID);
					//SET event vars
					var jMySchedLiDays = jMySchedList.find("li[value]");
					var mySchedDateString = jEventThis.parent().parent().siblings("var").text();
					var mySchedMonDayYear = mySchedDateString.substr(4,2);
					var mySchedMonDayYear = mySchedMonDayYear.concat("/",mySchedDateString.substr(6,2),"/",mySchedDateString.substr(0,4));
					// FIRST add event date if it doesn't exist to schedule and in the right place
					if (jMySchedLiDays.length == 0) {
						jMySchedList.append("<li value=" + mySchedDateString + ">" + mySchedMonDayYear + "<ul></ul></li>");
					}
					else {
						// here's a sorting bit now my issue is that do I have to nest everything in the abstract function
						if (jMySchedList.find("li[value=" + mySchedDateString + "]").length == 0) {
							//Put date in schedule and add event
							var plugLi = true;
							jMySchedLiDays.each(function(intDayindex, objDay){
								//alert($(this).attr("value"));
								if ($(this).attr("value") > mySchedDateString && plugLi) {
									$(this).before("<li value=" + mySchedDateString + ">" + mySchedMonDayYear + "<ul></ul></li>");
									plugLi = false;
								}
								if (jMySchedLiDays.last().attr("value") == $(this).attr("value") && plugLi) {
									$(this).after("<li value=" + mySchedDateString + ">" + mySchedMonDayYear + "<ul></ul></li>");
								}
							});
						//jMySchedList.append("<li value=" + mySchedDateString + ">" + mySchedMonDayYear + "<ul></ul></li>");
						}
					}
					//SECOND add event to mySchedule in the right place
					var jMySchedDayList = jMySchedList.find("li[value=" + mySchedDateString + "]").find("ul");
					// get the loop list for this day
					var jMySchedDayListLi = jMySchedDayList.find("li");
					var thisItemHTML = "<li data-eventID=" + thisEventID + "><button style='font-size:55%'>Delete</button> " + jEventThis.html();
					
					thisItemHTML = thisItemHTML + " <span class='characterName'><button style='font-size:55%'>Update or Add Character</button></span>";
					//if (thisEventCharReqP == 1){}
					
					thisItemHTML = thisItemHTML + "</li>";
					if (jMySchedDayListLi.length == 0) {
						jMySchedDayList.append(thisItemHTML);
					}
					else {
						if (jMySchedDayList.find(".startTime:contains('" + thisStartTxt + "')").length == 0) {
							//Put date in schedule and add event
							var plugLi = true;
							jMySchedDayListLi.each(function(intSchedEventIndex, objSchedEvent){
								var iStartTime = formatEventTime($(this).find(".startTime").text());
								//alert($(this).attr("value"));
								if (iStartTime > thisStart && plugLi) {
									$(this).before(thisItemHTML);
									plugLi = false;
								}
								if (jMySchedDayListLi.last().find(".startTime").text() == $(this).find(".startTime").text() && plugLi) {
									$(this).after(thisItemHTML);
								}
								
							});
						}
						//jMySchedList.append("<li value=" + mySchedDateString + ">" + mySchedMonDayYear + "<ul></ul></li>");
					}
					applyStyle();
					// start with wrong place
					//jMySchedDayList.append("<li>" + jEventThis.html()+ "</li>");
					//THIRD hide conflicting events on this day
					var thisDaysEvents = jEventThis.closest(".dateList").find("li");
					thisDaysEvents.each(function(intEventIndex, objEvent){
						var iThisStart = formatEventTime($(this).find(".startTime").text());
						var iThisEnd = formatEventTime($(this).find(".endTime").text());
						if((iThisEnd > thisStart &&  iThisEnd < thisEnd)||(iThisStart >= thisStart && iThisStart < thisEnd)){
							$(this).hide();
						}
						});
					//TODO:FOURTH add event via ajax to registration
					ajaxData = {
							method:  'addevent',
							eventID:  thisEventID,
							player_id: thisPlayerID
							}
					runAjaxForRegistration(ajaxData);
			//DELETE ACTION
					
					// Prevent default.
					return( false );
				}
				
			);
		}
	);
	$("#myScheduleList").find("button:contains('Delete')").live("click",
		function(objEvent){
			var thisPlayerID = $(this).closest("[data-playerID]").attr("data-playerID");
			//alert(thisPlayerID);
			var thisEventID = $(this).parent().find("[title=eventID]").text();
			var thisStart = formatEventTime($(this).parent().find(".startTime").text());
			var thisEnd = formatEventTime($(this).parent().find(".endTime").text());
			var thisEventDate = $(this).closest("[value]").attr("value");
			if ($(this).closest("[value]").find("li").length == 1){
				$(this).closest("[value]").remove();
			}
			else{
				$(this).parent().remove();
			}
			//alert(thisEventDate);
			$(this).parent().remove();
			// show conflicting events
			var thisDaysEvents = $("var:contains('" + thisEventDate + "')").parent().find("li");
			thisDaysEvents.each(function(intEventIndex, objEvent){
				var iThisStart = formatEventTime($(this).find(".startTime").text());
				var iThisEnd = formatEventTime($(this).find(".endTime").text());
				if((iThisEnd > thisStart &&  iThisEnd < thisEnd)||(iThisStart >= thisStart && iThisStart < thisEnd)){
					$(this).show();
				}
				});
			ajaxData = {
					method:  'deleteevent',
					eventID:  thisEventID,
					player_id: thisPlayerID
					}
			runAjaxForRegistration(ajaxData);
	});

	// character management section this is where I can see an mvc or some framework/methodology being valuable	
	var charactername = $( "#character_name" ),
	characterlevel = $( "#character_level" ),
	classsummary = $( "#class_summary" ),
	allFields = $( [] ).add( charactername ).add( characterlevel ).add( classsummary ),
	tips = $( ".validateTips" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	function checkLength( o, n, min, max ) {
		if ( o.val().length > max || o.val().length < min ) {
			o.addClass( "ui-state-error" );
			updateTips( n + " is required" );
			//updateTips( "Length of " + n + " must be between " +
			//	min + " and " + max + "." );
			return false;
		} else {
			return true;
		}
	}

	function checkRegexp( o, regexp, n ) {
		if ( !( regexp.test( o.val() ) ) ) {
			o.addClass( "ui-state-error" );
			updateTips( n );
			return false;
		} else {
			return true;
		}
	}
	
	$( "#character-form" ).dialog({
		autoOpen: false,
		height: 500,
		width: 500,
		modal: true,
		close: function() {
			allFields.val( "" ).removeClass( "ui-state-error" );
		}
	});
	
	$("#character-form").find("button:contains('Add Character')").live("click",
		function(objEvent){
			//alert("hello");
			var bValid = true;
			allFields.removeClass( "ui-state-error" );
	
			bValid = bValid && checkLength( charactername, "Name", 1, 100 );
			bValid = bValid && checkLength( classsummary, "Class", 1, 100 );
	
			//bValid = bValid && checkRegexp( name, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );
			// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
			//bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com" );
			//bValid = bValid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );
	
			if ( bValid ) {
				
				var ajaxData = {
						method:  'createCharacter',
						character_name: charactername.val() ,
						character_level: characterlevel.val(),
						class_summary: classsummary.val()
						}
				runAjaxForRegistration(ajaxData);
			  	allFields.val( "" ).removeClass( "ui-state-error" );
			}
			return( false );
	});
	$("#characters").find("button:contains('select')").live("click",
		function(objEvent) {
		 var thisCharacterID = $(this).closest("tr").data("characterID");
		 var thisPlayerID =  $(this).closest("tr").data("playerID");
		 var thisCharacterName = $(this).closest("tr").find("td:first-child").html();
		 var thisEventID = $("#character-form").data("eventID");
		 //delete the add character button
		 var displayLI = $('li[data-eventID="'+thisEventID+'"]');
		 displayLI.find("span.characterName").remove();
		 //add the character name to the schedule
		 displayLI.append("<span class='characterName'> - "+thisCharacterName+"  <button style='font-size:55%'>Update or Add Character</button></span>");
		 applyStyle();
		 //update the database
		 ajaxData = {
				method:  'addCharacter',
				eventID:  thisEventID,
				character_id:  thisCharacterID,
				player_id: thisPlayerID
				}
		 runAjaxForRegistration(ajaxData);
		 $( "#character-form" ).dialog("close");		 
	});


	$("#myScheduleList").find("button:contains('Update or Add Character')").live("click",
		function(objEvent){
			var thisEventID = $(this).closest("li").find("[title=eventID]").text();
			$("#character-form").data("eventID",thisEventID);
			//$("#character-form").attr('data-eventID', thisEventID);
			var eventID = $("#character-form").data("eventID");
			$( "#character-form" ).dialog( "open" );
	});


	
});
function applyStyle(){
	$("#myScheduleList").find("button:contains('Delete')").button({
        icons: {
            primary: "ui-icon-close"
        },
        text: false
		});
	$("#myScheduleList").find("button:contains('Update or Add Character')").button({
        icons: {
            primary: "ui-icon-person"
        },
        text: false
		});
	$("#myScheduleList").find("br").remove();
}

function formatEventTime(timeString){
	// datevalue isnot useful
	// because the end time at midnight is older we just want to return 1159
	if(timeString == '12:00 am'){
		return '2400';
	}
	// else we want to return a digit value as a time
	else{
		
	var d = new Date("7/7/2002 " + timeString);
	var hour= d.getHours();
	if(hour < 10){
	hour= "0" + hour;
	}
	var minute= d.getMinutes();
	if(minute< 10){
	minute= "0" + minute;
	}
	return (String(hour) + minute);
	}
}
function runAjaxForRegistration(ajaxData){
	var returnData = "";
     $.ajax({  
        type: "POST",  
        url: "ajax.php",  
        data: ajaxData,  
        success: function(data){  
			var obj = $.parseJSON(data);
			switch(ajaxData.method)
			{
			case 'createCharacter':
			  // display row
			  var characterID = obj.Data;
			  var characterName = ajaxData.character_name;
			  var characterClass = ajaxData.class_summary;
			  var characterLevel = ajaxData.character_level;
			  var insertHTML = '<tr data-characterID="'+characterID+'">';
				insertHTML = insertHTML + '<td>'+characterName+'</td>';
				insertHTML = insertHTML + '<td>'+characterClass+'</td>';
				insertHTML = insertHTML + '<td>'+characterLevel+'</td>';
				insertHTML = insertHTML + "<td><button style='font-size:55%'>select</button></td></tr>";
			  $("#characters tbody").append(insertHTML);
			  break;
			//default:
			//code to be executed if n is different from case 1 and 2
			}
			returnData = obj.Data;
			returnError = obj.Errors;
			returnSuccess = obj.Success;
			//obj returns Data Success and Errors Do this all the time (ty Ben Nadel)
			//alert(obj.Success);
            $('div#notify').hide(function(){$('div.success').fadeIn();});  
		},
		// this runs if an error
	   error: function (xhr, textStatus, errorThrown){
	    // show error
	    alert(errorThrown);
	  }  
    });  
    return returnData;  
    }
	  
