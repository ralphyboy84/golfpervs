$(document).ready(function(){
	//on click event for editing an event, this launches the edit event screen
	$("body").on("click", "#editEventButton", function() {
        eventid = $("#eventlist_select").val();
        
        ajaxLoader();
		$.ajax({
			type: "GET",
			url: "ajax/schedule/editevent2.php?eventid="+eventid,
		})
		
		.done(function( html ) {
            checkSession(html);
			$("#mainContent").html( html );
			initStartEndDatePickers();
		});
	});
    
    //onclick event for viewing a calendar day
	$("body").on("click", ".monthviewcellwrapper", function() {
		date = $(this).data("date");
		
        ajaxLoader();
		$.ajax({
			type: "GET",
			url: "ajax/schedule/loadcalendar.php?param=day&date="+date,
		})
		
		.done(function( html ) {
            checkSession(html);
			$("#mainContent").html( html );
			$("#selectCalendarDayFromDayView").datepicker({ dateFormat: 'dd/mm/yy' });
		});
	});
	
	//change event when selecting a different day in view day mode
	$("body").on("change", "#selectCalendarDayFromDayView", function() {
		date = $(this).val();
		
        ajaxLoader();
		$.ajax({
			type: "GET",
			url: "ajax/schedule/loadcalendar.php?param=day&date="+date,
		})
		
		.done(function( html ) {
            checkSession(html);
			$("#mainContent").html( html );
			$("#selectCalendarDayFromDayView").datepicker({ dateFormat: 'dd/mm/yy' });
		});
	});
	
	//on click event for returning to month view from day view
	$("body").on("click", "#return_button", function() {
		date = $(this).data("date");
		
        ajaxLoader();
		$.ajax({
			type: "GET",
			url: "ajax/schedule/loadcalendar.php?param=month&date="+date,
		})
		
		.done(function( html ) {
            checkSession(html);
			$("#mainContent").html( html );
		});		
	});
	
	//on click event for returning to month view from day view
	$("body").on("click", "#next_button, #prev_button", function() {
		date = $(this).data("date");
		
        ajaxLoader();
		$.ajax({
			type: "GET",
			url: "ajax/schedule/loadmonth.php?viewdate="+date,
		})
		
		.done(function( html ) {
            checkSession(html);
			$("#mainContent").html( html );
		});
	});
	
	//onchange event for going to a new month
	$("body").on("change", "#month_select", function() {
		year = $(this).data("year");
		month = $(this).val();
		
        ajaxLoader();
		$.ajax({
			type: "GET",
			url: "ajax/schedule/loadmonth.php?viewdate="+year+month+"01",
		})
		
		.done(function( html ) {
            checkSession(html);
			$("#mainContent").html( html );
		});
	});
	
	//on click event for returning to month view from day view
	$("body").on("click", "#next_day_button, #prev_day_button", function() {
		date = $(this).data("date");
		
        ajaxLoader();
		$.ajax({
			type: "GET",
			url: "ajax/schedule/loadday.php?viewdate="+date,
		})
		
		.done(function( html ) {
            checkSession(html);
			$("#mainContent").html( html );
			$("#selectCalendarDayFromDayView").datepicker({ dateFormat: 'dd/mm/yy' });
		});
	});
    
    $("body").on("click", "#deleteEventButton", function() {
		if (confirm("Are you sure you want to delete this event?")) {
            eventList = $("#eventlist_select").val();
            ajaxLoader();
			$.ajax({
				type: "POST",
				url: "ajax/schedule/dbdeleteevent.php?eventid="+eventList,
				data: { 
					eventid: $("#eventlist_select").val()
				}
			})
			
			.done(function( html ) {
                checkSession(html);
				$("#mainContent").html( html );
			});
		}
	});
    
    //onclick event for showing the end date when editing an event
	$("body").on("click", "#updateShowEndDate", function() {
		
		if ($(this).is(":checked")) {
			$("#endEventDateHiddenRow").show();
		} else {
			$("#endEventDateHiddenRow").hide();
			$("#updateEventEndDate").val("");
		}
	});
	
	//event for when upating an event
	$("body").on("click", "#updateEventButton", function() {
		var returnFlag=0;
		
		//check to see if we have any values for the add start date input
		if (document.getElementById("startDate").value === "") {
			$("#updateStartDate").focus();
			$("#hiddenStartDateDiv").show();
			$("#updateStartDateForm").addClass("has-error");
			returnFlag=1;
		} else { //if we don;t then hide the warning div (may have been activated earlier)
			$("#hiddenStartDateDiv").hide();
			$("#updateStartDateForm").removeClass("has-error");
		}
		
		if ($("#updateShowEndDate").is(":checked")) {
			//check to see if we have any values for the end date input
			if (document.getElementById("endDate").value === "") {
				$("#hiddenEndDateDiv").show();
				$("#endEventDateHiddenRow").addClass("has-error");
				returnFlag=1;
			} else { //if we don;t then hide the warning div (may have been activated earlier)
				$("#hiddenEndDateDiv").hide();
				$("#endEventDateHiddenRow").removeClass("has-error");
			}
		}
		
		//check to see if we have any values for the event Name input
		if (document.getElementById("eventName").value === "") {
			$("#hiddenEventNameDiv").show();
			$("#updateEventNameForm").addClass("has-error");
			
			if (document.getElementById("inputUsername").value !== "") {
				$("#eventName").focus();
			}
			returnFlag=1;
		} else {
			$("#hiddenEventNameDiv").hide();
			$("#updateEventNameForm").removeClass("has-error");
		}

		//if the return flag has been set then exit out
		if (returnFlag == 1) {
			return;
		} else { //if not then we have values so proceed!!!
            startDate = $("#startDate").val();
            endDate = $("#endDate").val();
            eventName = $("#eventName").val();
            description = $("#eventDescription").val();
            course = $("#course_select").val();
            eventid_hidden = $("#eventid_hidden").val();
            
            ajaxLoader();
			$.ajax({
				type: "POST",
				url: "ajax/schedule/dbeditevent.php",
				data: { startDate: startDate, endDate: endDate, eventName: eventName, description: description, course: course, eventid_hidden: eventid_hidden }
			})
			
			
			.done(function( html ) {
                checkSession(html);
				$("#mainContent").html( html );
			});
		}
	});
    
    //onclick event for showing the end date when adding an event
	$("body").on("click", "#showEndDate", function() {
		
		if ($(this).is(":checked")) {
			$("#endEventDateHiddenRow").show();
		} else {
			$("#endEventDateHiddenRow").hide();
			$("#addEventEndDate").val("");
		}
	});
	
	//event for when adding an event
    $("body").on("click", "#addEventButton", function() {
		var returnFlag=0;
		
		//check to see if we have any values for the add start date input
		if (document.getElementById("startDate").value === "") {
			$("#addEventStartDate").focus();
			$("#hiddenStartDateDiv").show();
			$("#startDateForm").addClass("has-error");
			returnFlag=1;
		} else { //if we don;t then hide the warning div (may have been activated earlier)
			$("#hiddenStartDateDiv").hide();
			$("#startDateForm").removeClass("has-error");
		}
		
				
		if ($("#showEndDate").is(":checked")) {
			//check to see if we have any values for the end date input
			if (document.getElementById("endDate").value === "") {
				$("#hiddenEndDateDiv").show();
				$("#endEventDateHiddenRow").addClass("has-error");
				returnFlag=1;
			} else { //if we don;t then hide the warning div (may have been activated earlier)
				$("#hiddenEndDateDiv").hide();
				$("#endEventDateHiddenRow").removeClass("has-error");
			}
		}
		
		//check to see if we have any values for the event Name input
		if (document.getElementById("eventName").value === "") {
			$("#hiddenEventNameDiv").show();
			$("#eventNameForm").addClass("has-error");
			
			if (document.getElementById("inputUsername").value !== "") {
				$("#eventName").focus();
			}
			returnFlag=1;
		} else {
			$("#hiddenEventNameDiv").hide();
			$("#eventNameForm").removeClass("has-error");
		}

		//if the return flag has been set then exit out
		if (returnFlag == 1) {
			return;
		} else { //if not then we have values so proceed!!!
            startDate = $("#startDate").val();
            endDate = $("#endDate").val();
            eventName = $("#eventName").val();
            description = $("#eventDescription").val();
            course = $("#course_select").val();
            
            ajaxLoader();
			$.ajax({
				type: "POST",
				url: "ajax/schedule/dbaddevent.php",
				data: { startDate: startDate, endDate: endDate, eventName: eventName, description: description, course: course }
			})
			
			.done(function( html ) {
                checkSession(html);
				$("#mainContent").html( html );
				
				//null form vars
				$("#startDate").val("");
				$("#endDate").val("");
				$("#eventName").val("");
				$("#eventDescription").val("");
				$("#course_select").val("");
				
			});
		}
	});
	
	//if the multiple day checkbox is clicked...
	$("#multipleDayEventCheck").click(function(){
		$("#endEventDateHiddenRow").show();
	});
});