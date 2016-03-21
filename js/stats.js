$(document).ready(function(){
    $("body").on("click", "#selectCourseForCourseSummaryButton", function() {
        startDate = $("#startDate").val();
        endDate = $("#endDate").val();
        course = $("#course_select").val();
        
        ajaxLoader();
         $.ajax({
            type: "POST",
            url: "ajax/stats/coursesummary.php",
            data: { 
                "startdate": startDate,
                "enddate": endDate,
                "course": course
            }
        })

        .done(function( html ) {
             checkSession(html);
            $("#mainContent").html( html );
             loadCourseSummary();
         });
    });
    
    $("body").on("click", "#viewStatsForAreaButton", function() {
        
        page = $(this).attr("data-page");
        startDate = $("#startDate").val();
        endDate = $("#endDate").val();
        course = $("#course").val();
        competition = $("#competition").val();
        friendid = $("#allyourpervs_select").val();
        
        ajaxLoader();
        
        $.ajax({
            type: "POST",
            url: "ajax/stats/"+$(this).attr("data-page")+".php",
            data: { 
                "startdate": startDate,
                "enddate": endDate,
                "course": course,
                "competition": competition,
                "friendid": friendid
            }
        })

        .done(function( html ) {
            checkSession(html);
            $("#mainContent").html( html );
            switch(page) {
                case "greens":
                    loadGIRStats(startDate, endDate, course, competition);
                    break;
                case "fairways":
                    loadFairwayStats(startDate, endDate, course, competition);
                    break;
                case "scores":
                    loadScoreStats(startDate, endDate, course, competition);
                    break;
                case "puttstats":
                    loadPuttStats(startDate, endDate, course, competition);
                    break;
            }
         });
    });
    
    $('body').on("click", "#example2 tr", function() {
        roundid = this.id;
        loadViewRound(roundid, false);
    });
    
    loadViewRound = function(roundid, notifid) {
        if (roundid) {
            ajaxLoader();
            
            $.ajax({
                type: "POST",
                url: "ajax/stats/viewround.php",
                data: { id: roundid, notifid: notifid}
            })

            .done(function( html ) {
                checkSession(html);
                $("#mainContent").html( html );
                puttBreakDownBarChart(roundid);  
                fairwaysHitChart(roundid);
                greensHitChart(roundid);
                puttLengthHoled(roundid);
                
                if (notifid) {
                    loadJsonNotifs();   
                }
            });
        }  
    };
    
    loadGIRStats = function (startDate, endDate, course, competition) {
        initialiseDTOrderAsc("greencoursetable");
        initialiseDTOrderAsc("greencompetitiontable");
        initialiseDTOrderAsc("greenyeartable");
        initStartEndDatePickers();  
        greensHitOverallChart(startDate, endDate, course, competition);
    };
    
    loadFairwayStats = function (startDate, endDate, course, competition) {
        initialiseDTOrderAsc("fairwaycoursetable");
        initialiseDTOrderAsc("fairwaycompetitiontable");
        initialiseDTOrderAsc("fairwayyeartable");
        initStartEndDatePickers();  
        fairwaysHitOverallChart(startDate, endDate, course, competition);
    };
    
    loadScoreStats = function () {
        initialiseDTOrderAsc("scorecoursetable");
        initialiseDTOrderAsc("scorecompetitiontable");
        initialiseDTOrderAsc("scoreyeartable");
        initStartEndDatePickers();  
        //fairwaysHitOverallChart(startDate, endDate, course, competition);
    };
    loadPuttStats = function () {
        initialiseDTOrderAsc("puttcoursetable");
        initialiseDTOrderAsc("puttcompetitiontable");
        initialiseDTOrderAsc("puttyeartable");
        initStartEndDatePickers();  
        //fairwaysHitOverallChart(startDate, endDate, course, competition);
    };
    loadCourseSummary = function () {
        initStartEndDatePickers();  
    };
});