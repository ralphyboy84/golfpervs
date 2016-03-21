$(document).ready(function(){
    $('body').on("click", "#acceptfriend", function() {
        notifid = $(this).attr("data-notifid");
        
        ajaxLoader();
            
        $.ajax({
            type: "POST",
            url: "ajax/profile/acceptfriend.php",
            data: { id: notifid}
        })

        .done(function( html ) {
            checkSession(html);
            $("#mainContent").html( html );
        });
    });
    
    $("body").on("click", "#searchpervs", function() {

        $("#searchresultscontainer").show();
        ajaxLoaderDiv("searchresults");
        
        $.ajax({
            type: "POST",
            url: "ajax/profile/searchresults.php",
            data: { 
                "criteria": $("#criteria").val()
            }
        })

        .done(function( html ) {
            checkSession(html);
            $("#searchresults").html( html );
            initialiseDTOrderAsc("searchresulttable");
         });
    });
    
    $("body").on("click", ".addfriendbutton", function() {
        dataid = $(this).attr("data-id");
                                                          
         $.ajax({
            type: "POST",
            url: "ajax/profile/sendpervrequest.php",
            data: { 
                "friendid": $(this).attr("data-id")
            }
        })

        .done(function( html ) {
             checkSession(html);
            $("#cell_"+dataid).html( html );
         });
    });
    
    $("body").on("click", "#editprofilebutton", function() {
         var items = ["forename", "surname", "email", "receivenotifs"];
        
        for (var x in items) {
            $("#"+items[x]).prop("readonly", false);
            $("#"+items[x]).prop("disabled", false);
            $("#"+items[x]).removeClass("disabled");
        }
        
        $("#editbuttondiv").hide();
        $("#savebuttondiv").show();
    });
    
    $("body").on("click", "#updateprofilebutton", function() {
        var str = $( "form" ).serialize();
        ajaxLoader();
        
         $.ajax({
            type: "POST",
            url: "ajax/profile/profile.php?update=1",
            data: str
        })

        .done(function( html ) {
             checkSession(html);
            $("#mainContent").html( html );
         });
    });
});