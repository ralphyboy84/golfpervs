$(document).ready(function(){
    
    ajaxLoader = function () {
        $("#mainContent").html("Please wait loading....<img src='images/ajax-loader.gif' alt='loading' />");   
    }
    
    ajaxLoaderDiv = function (div) {
        $("#"+div).html("Please wait loading....<img src='images/ajax-loader.gif' alt='loading' />");   
    }
    
    checkSession = function(html) {
        if (html == "sessionlost") {
            document.location.href = "index.php";   
        }
    }
    
    pageLoad = function(page, directory) {
        ajaxLoader();
        
        if (directory) {
            page = directory+"/"+page   
        }

        $.ajax({
            type: "POST",
            url: "ajax/"+page+".php",
        })

        .done(function( html ) {
            checkSession(html);
            
            $("#mainContent").html( html );

            switch(page) {
                case "home":
                   // recentRoundsLineChart();
                    break;
                case "signout":
                    document.location.href = "index.php";
                case "stats/roundsummary":
                    initialiseDT("example2");
                    break;
                case "schedule/addevent":
                    initStartEndDatePickers();
                    break;
                case "stats/greens":
                    loadGIRStats("", "", "", "");
                    break;
                case "stats/fairways":
                    loadFairwayStats("", "", "", "");
                    break;
                case "stats/scores":
                    loadScoreStats();
                    break;
                case "stats/putts":
                    loadPuttStats();
                    break;
                case "profile/notifs":
                    initialiseDT("fullnotiftable");
                    break;
                case "profile/pervs":
                    initialiseDT("allyourpervs");
                    break;
                case "rounds/addround":
                    $("#date").datepicker({ dateFormat: 'dd/mm/yyyy', autoclose:true });
                    break;
                case "stats/coursesummary":
                    loadCourseSummary();
                    break;
                case "rounds/editround":
                    initialiseDT("editroundtable");
                    break;
            }
        });  
    };
    
    pageLoad("home", false);
    
    loadJsonNotifs = function () {
        $.getJSON( "ajax/profile/unconfirmednotifs.php", function( json ) {   
            $("#numberofnotifications").html(json.numofnotifs);
            
            $("#notifsdropdown").html("");
            
            if (json.data) {
                $.each(json.data, function(keys, vals) {
                    $("#notifsdropdown").append('<li id="'+vals.id+'" class="notificationList" data-type="'+vals.type+'" data-typeid="'+vals.typeid+'"><a href="#"><i class="fa fa-users text-aqua"></i> '+vals.subject+'</a></li>');
                });
            } else {
                $("#notifsdropdown").append('<li class="notificationList"><a href="#"><i class="fa fa-users text-aqua"></i> No new notifications</a></li>');
            }
        });
    }
    
    loadJsonNotifs();
    
    $('body').on("click", ".notificationList, .notifrow", function() {
        if (this.id || $(this).attr("data-notifid")) {
            
            notifid = "";
            
            if (this.id) {
                idtouse = this.id; 
                notifid = this.id;
            }
            
            if ($(this).attr("data-notifid")) {
                idtouse = $(this).attr("data-notifid");   
            }
            
            switch($(this).attr("data-type")) {
                case "friend":
                    standardNotifLoad(idtouse);
                    break;
                case "friendaccept":
                    standardNotifLoad(idtouse);
                    break;
                case "friendconfirm":
                    standardNotifLoad(idtouse);
                    break;
                case "newround":
                    loadViewRound($(this).attr("data-typeid"), notifid);
                    break;
            }
        }
    });
    
    standardNotifLoad = function(idtouse) {
        ajaxLoader();

        $.ajax({
            type: "POST",
            url: "ajax/profile/viewnotification.php",
            data: { id: idtouse}
        })

        .done(function( html ) {
            checkSession(html);
            
            $("#mainContent").html( html );
            loadJsonNotifs();
        });
    };
    
    $('body').on("click", ".menuli, #notifs", function(){
        pageLoad(this.id, $(this).attr("data-directory"));
        
        if ($( window ).width() < 1500) {
            //$(".sidebar").hide(); 
            //$(".main-sidebar").hide();
            $("body").removeClass("sidebar-open");
        }
    });
    
    initialiseDT = function(tableid) {
        $('#'+tableid).DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "order": [[0, "desc"]]
        });
    }
    
    initialiseDTNoOrder = function(tableid) {
        $('#'+tableid).DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true
        });
    }
    
    initialiseDTOrderAsc = function(tableid) {
        $('#'+tableid).DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "order": [[0, "asc"]],
            "responsive": true
        });
    }
    
    $('body').on("click", ".homeTop", function () {
        pageLoad($(this).attr("data-page"), $(this).attr("data-directory"));
    });
        
    initStartEndDatePickers = function () {
        $("#startDate").datepicker({ dateFormat: 'dd/mm/yyyy', autoclose:true });
        $("#endDate").datepicker({ dateFormat: 'dd/mm/yyyy', autoclose:true });
    }
    
    setInterval(function(){
        loadJsonNotifs();
    }, 60000);    
});