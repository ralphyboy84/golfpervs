$(document).ready(function(){
    $("body").on("click", "#editRoundButtonTable", function() {
        roundid = $(this).attr("data-id");
        
        editRound(roundid, 1);
    });
    
    $("body").on("click", "#deleteRoundButtonTable", function() {
        roundid = $(this).attr("data-id");
        
        if (!confirm("Are you sure you want to delete this round?")) {
            return;
        }
        
        ajaxLoader();
        
        $.ajax({
            type: "POST",
            url: "ajax/rounds/deleteRound.php",
            data: {
                roundid: roundid
            }
        })

        .done(function( html ) {
            checkSession(html);
            $("#mainContent").html( html );
        });
    });    
    
    $("body").on("click", "#editRoundButton", function() {
        roundid = $("#roundid").val();
        
        if ($("#edit").val()) {
            edit = 1;
            update = 0;
        } else {
            edit = 0;
            update = 1;
        }
        
        editRound(roundid, edit, update);
    });
    
    $("body").on("click", "#completeRoundButton", function() {
        roundid = $(this).attr("data-roundid");
        ajaxLoader();
        
        $.ajax({
            type: "POST",
            url: "ajax/rounds/finishround.php",
            data: {
                roundid: roundid
            }
        })

        .done(function( html ) {
            checkSession(html);
            
            if(html) {
               $.ajax({
                    type: "POST",
                    url: "ajax/rounds/finishroundshow.php",
                    data: {
                        roundid: html
                    }
                })   
               
               .done(function( html2 ) {
                    checkSession(html);
                    $("#mainContent").html( html2 );	
                });
               
               $.ajax({
                    type: "POST",
                    url: "ajax/rounds/sendnotif.php",
                    data: {
                        roundid: html
                    }
                });
            }
        }); 
    });
    
    editRound = function(roundid, edit, update) {
        ajaxLoader();
        
        $.ajax({
            type: "POST",
            url: "ajax/rounds/addhole.php",
            data: {
                roundid: roundid,
                update: update,
                edit: edit
            }
        })

        .done(function( html ) {
            checkSession(html);
            $("#mainContent").html( html );	
            initialiseSwipeForScoreAdd();
        });
    };
    
    $("body").on("click", "#newRoundButton", function() {
        roundid = $("#roundid").val();
        ajaxLoader();
        
        $.ajax({
            type: "POST",
            url: "ajax/rounds/addround.php",
            data: {
                roundid: roundid,
                newround: 1
            }
        })

        .done(function( html ) {
            checkSession(html);
            $("#mainContent").html( html );	
            //initialiseSwipeForScoreAdd();
        });
    });
    
    $("body").on("click", "#continueRoundButton", function() {
        roundid = $("#roundid").val();
        hole = $("#hole").val();
        ajaxLoader();

        $.ajax({
            type: "POST",
            url: "ajax/rounds/addhole.php",
            data: {
                roundid: roundid,
                hole: hole,
                continue: 1
            }
        })

        .done(function( html ) {
            checkSession(html);
            $("#mainContent").html( html );	
            
            if (hole != 19) {
                initialiseSwipeForScoreAdd();
            }
        });
    });
                             
    $("body").on("click", "#addRoundButton", function() {
        var mandatories = ["add_round_course_select", "add_round_tee_select", "date"];
        var errorFlag = "";
        
        for (var x in mandatories) {
            if (!$("#"+mandatories[x]).val()) {
                $("#"+mandatories[x]+"div").addClass("has-error");
                errorFlag = true;
            } else {
                $("#"+mandatories[x]+"div").removeClass("has-error");
            }
        }
        
        if (!errorFlag) {
            $.ajax({
                type: "POST",
                url: "ajax/rounds/addhole.php",
                data: $("#addRound").serialize()
            })

            .done(function( html ) {
                checkSession(html);
                $("#mainContent").html( html );	
                initialiseSwipeForScoreAdd();
            });
        } else {
            $("#showErrorsDiv").show();   
        }
    });
    
    $("body").on("change", "#add_round_course_select", function() {
        $.getJSON( "ajax/rounds/gettees.php?course="+this.value, function( json ) {
             if (json) {
                $("#add_round_tee_select").html("<option value=''>Select tee....</option>");
                $.each(json, function(keys, vals) {
                    $("#add_round_tee_select").append("<option value='"+vals.tee+"'>"+vals.teelabel+"</option>");
                });
             }
        });         
    });
    
    $('body').on("click", "#viewAddedRoundButton", function() {
        roundid = $(this).attr("data-roundid");
        loadViewRound(roundid, false);
    });
    
    $("body").on("click", "#addHoleButton", function() {
        
        if ($( window ).width() <= 768) {
            $("#score").val(getSwipeVal("position"));
            $("#putts").val(getSwipeVal("position2"));
            $("#puttholedlength").val(getSwipeVal("position3"));
        }
        
        holeNumber = $("#hole").val();
        formData = $("#holeInput").serialize();
        
        if ($("#green").val() != 2) {
            if (!$("#upndown").val() && !$("#sandsave").val() && $("#gmissed").val() != 5) {
                alert("You have missed the green but not said what happened next!");
                return;
            }
        }

        ajaxLoader();
        
        $.ajax({
            type: "POST",
            url: "ajax/rounds/addhole.php",
            data: formData
        })

        .done(function( html ) {
            checkSession(html);
            $("#mainContent").html( html );	
            if (holeNumber != 18) {
                initialiseSwipeForScoreAdd();
            }
        });     
    });
    
    getSwipeVal = function(elToGet) {
        var el = document.getElementById(elToGet).getElementsByTagName("li");

        for (var i=0; i<el.length; i++) {
            if (el[i].className == "on") {
                score = el[i].id;
            }
        }

        return score;
    };
    
    $("body").on("change", "#score_select", function() {
        $("#score").val($("#score_select").val());
    });
    
    changeMe = function (someVal) {
        if (someVal.className == "inActiveF") {
            vals = document.getElementsByClassName("activeF");

            for (var i=0; i < vals.length; i++) {
                document.getElementById(vals[i].id).className = "inActiveF";
            }

            document.getElementById(someVal.id).className = "activeF";

            if (someVal.id == "fHit") {
                $("#fairway").val(2);
                $("#fmissed").val("");
            } else {
                $("#fairway").val(1);
                
                if (someVal.id == "fr") {
                    $("#fmissed").val(2);
                } else if (someVal.id == "fl") {
                    $("#fmissed").val(1);
                }
            }
        } 

        if (someVal.className == "inActiveG") {
            vals = document.getElementsByClassName("activeG");

            for (var z=0; z < vals.length; z++) {
                document.getElementById(vals[z].id).className = "inActiveG";
            }

            document.getElementById(someVal.id).className = "activeG";

            if (someVal.id != 'gHit' && someVal.id != 'na') {
                document.getElementById("shortGameDiv").style.display = "block";
                document.getElementById("bunkerDiv").style.display = "block";
            } else {
                document.getElementById("shortGameDiv").style.display = "none";
                document.getElementById("bunkerDiv").style.display = "none";
            }

            if (someVal.id == "gHit") {
                $("#green").val(2);
                $("#gmissed").val("");
                $("#upndown").val("");
                $("#sandsave").val("");
            } else {
                $("#green").val(1);
                
                if (someVal.id == "gr") {
                    $("#gmissed").val(2);
                } else if (someVal.id == "gl") {
                    $("#gmissed").val(1);
                } else if (someVal.id == "glo") {
                    $("#gmissed").val(3);
                } else if (someVal.id == "gs") {
                    $("#gmissed").val(4);
                } else if (someVal.id == "na") {
                    $("#gmissed").val(5);
                }
            }
        }

        if (someVal.className == "inActiveS") {
            vals = document.getElementsByClassName("activeS");

            for (var q=0; q < vals.length; q++) {
                document.getElementById(vals[q].id).className = "inActiveS";
            }

            document.getElementById(someVal.id).className = "activeS";
            
            if (someVal.id == "udn") {
                $("#upndown").val(1);
                $("#sandsave").val("");
            } else if (someVal.id == "udy") {
                $("#upndown").val(2);
                $("#sandsave").val("");
            } else if (someVal.id == "ssn") {
                $("#upndown").val("");
                $("#sandsave").val(1);
            } else if (someVal.id == "ssy") {
                $("#upndown").val("");
                $("#sandsave").val(2);
            }
        }
    };
    
    initialiseSwipeForScoreAdd = function() 
    {
        //if (document.getElementById("dontshowswipe").value == 0) {
            var elem = document.getElementById('mySwipeScore');
            var scoreBullets = document.getElementById('position');
            window.mySwipe = Swipe(elem, {
                startSlide: document.getElementById("defaultScoreForSwipe").value,
                // auto: 3000,
                // continuous: true,
                // disableScroll: true,
                // stopPropagation: true,
                // callback: function(index, element) {},
                // transitionEnd: function(index, element) {}
                callback: function(pos) {
                    var bullets = scoreBullets.getElementsByTagName('li');
                    var i = bullets.length;
                    while (i--) {
                        bullets[i].className = ' ';
                    }
                    bullets[pos].className = 'on';
                }
            });

            elem = document.getElementById('mySwipePutts');
            var puttBullets = document.getElementById('position2');
            window.mySwipe = Swipe(elem, {
                startSlide: document.getElementById("defaultPuttsForSwipe").value,
                // auto: 3000,
                // continuous: true,
                // disableScroll: true,
                // stopPropagation: true,
                // callback: function(index, element) {},
                // transitionEnd: function(index, element) {}
                callback: function(pos) {      
                    var bullets = puttBullets.getElementsByTagName('li');
                    var i = bullets.length;
                    while (i--) {
                        bullets[i].className = ' ';
                    }
                    bullets[pos].className = 'on';
                }
            });

            elem = document.getElementById('mySwipeLength');
            var lengthBullets = document.getElementById('position3');
            window.mySwipe = Swipe(elem, {
                startSlide: document.getElementById("defaultLengthForSwipe").value,
                // auto: 3000,
                // continuous: true,
                // disableScroll: true,
                // stopPropagation: true,
                // callback: function(index, element) {},
                // transitionEnd: function(index, element) {}
                callback: function(pos) {      
                    var bullets = lengthBullets.getElementsByTagName('li');
                    var i = bullets.length;
                    while (i--) {
                        bullets[i].className = ' ';
                    }
                    bullets[pos].className = 'on';
                }
            });
        //}
    };
});