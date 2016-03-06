$(document).ready(function(){
    //on click event for viewing a course
	$("body").on("click", "#viewCourseButton", function() {
        courseSelect = $("#course_select").val();
        ajaxLoaderDiv("viewCourseInfo");
        
		$.ajax({
			type: "POST",
			url: "ajax/courses/viewCourseInfo.php",
			data: { course: courseSelect }
		})
		
		.done(function( html ) {
            checkSession(html);
			$("#viewCourseInfo").html( html );
		});
	});
    
    //on click event for viewing a course
	$("body").on("click", "#viewCourseButtonForRating", function() {
        ajaxLoaderDiv("viewCourseInfoForRating");
        
		$.ajax({
			type: "POST",
			url: "ajax/courses/viewCourseInfoForRating.php",
			data: { course: $("#course_select").val(), }
		})
		
		.done(function( html ) {
            checkSession(html);
			$("#viewCourseInfoForRating").html( html );
		});
	});
	
	//on click event for adding a course rating
	$("body").on("click", "#addCourseRatingButton", function() {
		if (confirm("Are you sure you want to add this Course Rating?")) {
			$.ajax({
				type: "POST",
				url: "ajax/courses/dbRateCourse.php",
				data: { courseRating: $("#courseRating_select").val(), 
						review: $("#courseReview").val(), 
						courseid: $("#courseid").val(),
						alreadyRanked: $("#alreadyRanked").val(),
						alreadyReviewed: $("#alreadyReviewed").val(),
				}
			})
			
			.done(function( html ) {
                checkSession(html);
				$("#viewCourseInfoForRating").html( html );
			});
		}
	});
    
    //on click event for adding a course basic info
	$("body").on("click", "#addCourseButton", function() {
		var returnFlag=0;
		
		//check to see if we have any values for the add course input
		if (document.getElementById("courseName").value == "") {
			$("#courseName").focus();
			$("#hiddenCourseNameDiv").show();
			$("#hiddenCourseNameDiv").addClass("has-error");
			$("#courseNameForm").addClass("has-error");
			returnFlag=1;
		} else { //if we don;t then hide the warning div (may have been activated earlier)
			$("#hiddenCourseNameDiv").hide();
			$("#courseNameForm").removeClass("has-error");
			$("#hiddenCourseNameDiv").removeClass("has-error");
		}
		
		//check to see if we have any values for the course location input
		if (document.getElementById("courseLocation").value == "") {
			$("#hiddenCourseLocationDiv").show();
			$("#courseLocationForm").addClass("has-error");
			
			if (document.getElementById("courseName").value != "") {
				$("#courseLocation").focus();
			}
			returnFlag=1;
		} else {
			$("#hiddenCourseLocationDiv").hide();
			$("#courseLocationForm").removeClass("has-error");
		}

		//check to see if we have any values for the course location input
		if (document.getElementById("courseTee").value == "") {
			$("#hiddenCourseTeeDiv").show();
			$("#courseTeeForm").addClass("has-error");
			
			if (document.getElementById("courseName").value != "" && document.getElementById("courseLocation").value != "") {
				$("#courseTee").focus();
			}

			returnFlag=1;
		} else {
			$("#hiddenCourseTeeDiv").hide();
			$("#courseTeeForm").removeClass("has-error");
		}
		
		//if the return flag has been set then exit out
		if (returnFlag == 1) {
			return;
		} else { //if not then we have values so proceed!!!
			$.ajax({
				type: "POST",
				url: "ajax/courses/addholes.php",
				data: { courseName: $("#courseName").val(), 
						courseLocation: $("#courseLocation").val(), 
						courseTee: $("#courseTee").val(), 
						courseSSS: $("#courseSSS").val(),
						courseWebsite: $("#courseWebsite").val(),
						coursePhoneNo: $("#coursePhoneNo").val(),
					}
			})
			
			.done(function( html ) {
                checkSession(html);
				$("#addCourseDiv").html( html );
			});
		}
	});
    
    //on click event for adding a course
	$("body").on("click", "#addFullCourseButton", function() {
	
		var notAllValuesSet = 0;
		for (x=1; x<=18; x++) {
			if (!$("#yards"+x).val()) {
				$("#divyards"+x).addClass("has-error");
				notAllValuesSet = 1;
			}
			
			if (!$("#si"+x).val()) {
				$("#divsi"+x).addClass("has-error");
				notAllValuesSet = 1;
			}
		}
		
		if (!notAllValuesSet) {
			var dataString = $('#holesForm').serialize();
			
			$.ajax({
				type: "POST",
				url: "ajax/courses/dbaddcourse.php",
				data: dataString,
			})
			
			.done(function( html ) {
                checkSession(html);
				$("#addCourseDiv").html( html );
			});
		}
	});
	
	$("body").on("change", ".siInput, .yardsInput", function () {
		if ($(this).val()) {
			$("#div"+$(this).attr("id")).removeClass("has-error");
		}
	});
	
	
	$("body").on("keydown", ".siInput, .yardsInput", function(event) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ( $.inArray(event.keyCode,[46,8,9,27,13,190]) !== -1 ||
             // Allow: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) || 
             // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault(); 
            }   
        }
    });
	
	$("body").on("change", ".siInput", function() {
		if ($(this).val() > 18) {
			$(this).val(18);
		}
	});
    
    //select a course to add a new tee set to
	$("body").on("click", "#selectCourseButton", function () {
		var returnFlag=0;

		//check to see if we have any values for the course location input
		if (document.getElementById("newTeeCourseTee").value == "") {
			$("#newTeeHiddenCourseTeeDiv").show();
			$("#newTeeCourseTeeForm").addClass("has-error");
			returnFlag=1;
		} else {
			$("#newTeeHiddenCourseTeeDiv").hide();
			$("#newTeeCourseTeeForm").removeClass("has-error");
		}
		
		//if the return flag has been set then exit out
		if (returnFlag == 1) {
			return;
		} else { //if not then we have values so proceed!!!
			$.ajax({
				type: "POST",
				url: "ajax/courses/addholes.php",
				data: { courseName: $("#course_select").val(),  
						courseTee: $("#newTeeCourseTee").val(), 
						courseSSS: $("#newTeeCourseSSS").val(),
					}
			})
			
			.done(function( html ) {
                checkSession(html);
				$("#addCourseDivNewTee").html( html );
			});
		}
	});
    
    //on click event for selecting a course to view the tee set for
	$("body").on("click", "#selectCourseForEditButton", function() {
		ajaxLoaderDiv("editCourseTeeSelect");
		$.ajax({
				type: "POST",
            url: "ajax/courses/editTee.php",
            data: { courseName: $("#course_select").val()}
        })

        .done(function( html ) {
            checkSession(html);
            $("#editCourseTeeSelect").html( html );
        });
	});
    
    //on click event for editing a course
	$("body").on("click", "#selectFullCourseForEditButton", function() {
        courseSelect = $("#course_select").val();
        teeSelect = $("#tee_select").val();
        
		ajaxLoaderDiv("editCourseDiv");
		
        $.ajax({
            type: "POST",
            url: "ajax/courses/editHoles.php",
            data: { courseId: courseSelect,
                    teeId: teeSelect
            }
        })

        .done(function( html ) {
            checkSession(html);
            $("#editCourseDiv").html( html );
        });
	});
    
    //on click event for editing a course and saving to the database
	$("body").on("click", "#editFullCourseButton", function() {
		var notAllValuesSet = 0;
		for (x=1; x<=18; x++) {
			if (!$("#yards"+x).val()) {
				$("#divyards"+x).addClass("has-error");
				notAllValuesSet = 1;
			}
			
			if (!$("#si"+x).val()) {
				$("#divsi"+x).addClass("has-error");
				notAllValuesSet = 1;
			}
		}
		
		if (!notAllValuesSet) {
			var dataString = $('#holesForm').serialize();
			
			$.ajax({
				type: "POST",
				url: "ajax/courses/dbeditcourse.php",
				data: dataString,
			})
			
			.done(function( html ) {
                checkSession(html);
				$("#editCourseDiv").html( html );
			});
		}
	});
});