$(document).ready(function() {

    // ************ variables ************\\

    var typeOfColorboxClose = "";

    // ************ variables ************//

    $("#addNewFreezerBtn, .editFreezerDataBtn").click(function() {
        prepareForShowForm(this);
    });

    function prepareForShowForm(element) {
        var dId = null;
        if(element.id == "addNewFreezerBtn") {
            console.log("add new Freezer " + element.id);
            console.log(" drawer ididid: " + dId);
            dId = "noId";
        } else {
            var freezerName = element.name;
            dId = freezerName.substring(7);
        }
        showAddEditFreezerWindow(dId);
    }

    function showAddEditFreezerWindow(id) {
        $.colorbox({
            iframe:true,
            //transition: "fade",
            scrolling: true,
            innerWidth:'960',
            innerHeight:'600',
            href:"modifyFreezer.php?freezerID=" + id + "&addOrEditFreezerData=true",
            onClosed:function(){
                if(typeOfColorboxClose == "submit") {
                    console.log("submitteeedddd");
                    if(id != "noId") {
                        console.log("update freezer display");
                        updateFreezerDisplay(id);
                    } else {
                        addNewFreezerToView();
                        console.log("add new freezer to display");
                    }
                }
                console.log(typeOfColorboxClose);
            }
        });
    }

    function updateFreezerDisplay(id) {
        console.log("update display: drawer id inside update: " + id);
        var posting = $.post("updateFreezerView.php", {freezerId: id});
        posting.success(function(data) {
            console.log("data got back: " + data);
            $("#freezer" + id + " .freezerData").replaceWith($.parseJSON(data).content);
        });
    }

//    TODO - write this function
    function addNewFreezerToView() {
        console.log("add new freezers: freezers id inside add");
        var displayedFreezers = [];
        var missingId = "";

        $("[id^='freezer']").each(function() {
            displayedFreezers.push(this.id.substring(7))
        });
        console.log("on the screen we have: " + displayedFreezers);

        var posting = $.post("getMissingFreezerForDisplay.php", {existingFreezers: displayedFreezers});
        posting.success(function(data) {
            console.log("data from get missing: " + data);
            console.log("data from get missing - json: " + $.parseJSON(data));
            missingId = $.parseJSON(data);

            var postForFreezer = $.post("getDataForMissingFreezer.php", {freezerId: missingId});
            postForFreezer.success(function(data) {
                console.log("data from get missing freeezer data:" + data);
                if($("#noFreezersYet").length) {
                    console.log("no freezers yet exists")
                    $("#noFreezersYet").remove();
                }
                $(".freezers").append(data);

                $("#freezer" + missingId + " .editFreezerDataBtn").click(function() {
                    prepareForShowForm(this);
                });
                $("#freezer" + missingId + " .deleteFreezerBtn").click(deleteFreezer);
            });
        });
    }

    window.setCloseType = function(type) {
        typeOfColorboxClose = type;
    }

    $(".deleteFreezerBtn").click(deleteFreezer);
    function deleteFreezer() {
        var id = this.name.substring(9);
        console.log("delete btn id: " + id);
        var posting = $.post("isFreezerEmpty.php", {freezerId: id});
        posting.success(function(data) {
            if(data.indexOf("notEmpty") < 0) {
                console.log("freezer is empty - delete");
                var postingDelete = $.post("deleteSelectedFreezer.php", {freezerId: id});
                postingDelete.success(function(data) {
                    $("#freezer" + id).remove();
                    if($("[id^='freezer']").length == 0) {
                        var noFreezersYet = "<p id='noFreezersYet'>There are no freezers in your system yet. Please add them.</p>";
                        $(".freezers").append(noFreezersYet);
                    }
                });
            } else {
                console.log("freezer not empty - show dialog");
                $("#deleteFreezerDialog").data("fId", id);
                $("#deleteFreezerDialog").dialog("open");
                console.log("the drawer NOT empty");
            }
//            console.log("data from isEmpty: " + data);
        });
        console.log(this.name);
    }

    $("#deleteFreezerDialog").dialog({
        autoOpen: false,
        width: 400,
        buttons: [
            {
                text: "Yes, delete freezer!",
                click: function() {
                    var fId = $("#deleteFreezerDialog").data("fId");
                    console.log("deleteing drawer with id: " + fId);
                    var posting = $.post("deleteSelectedFreezer.php", {freezerId: fId});
                    posting.success(function(data) {
                        $("#freezer" + fId).remove();
                        if($("[id^='freezer']").length == 0) {
                            var noFreezersYet = "<p id='noFreezersYet'>There are no freezers in your system yet. Please add them.</p>";
                            $(".freezers").append(noFreezersYet);
                        }
                    });
                    $(this).dialog("close");
                }
            },
            {
                text: "Do not delete.",
                click: function() {
                    $( this ).dialog("close");
                }
            }
        ]
    });

// Link to open the dialog
    $( "#dialog-link" ).click(function( event ) {
        $( "#dialog" ).dialog( "open" );
        event.preventDefault();
    });

    // Hover states on the static widgets
    $( "#dialog-link, #icons li" ).hover(
        function() {
            $( this ).addClass( "ui-state-hover" );
        },
        function() {
            $( this ).removeClass( "ui-state-hover" );
        }
    );

});