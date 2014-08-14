

$(document).ready(function() {

    // ************ variables ************\\

    var typeOfColorboxClose = "";

    // ************ variables ************//

    $("#addNewDrawerBtn, .editDrawerDataBtn").click(function() {
        prepareForShowForm(this);
    });

    function prepareForShowForm(element) {
        var dId = null;
        if(element.id == "addNewDrawerBtn") {
            console.log("add new drawer " + element.id);
            console.log(" drawer ididid: " + dId);
            dId = "noId";
        } else {
            var drawerName = element.name;
            dId = drawerName.substring(7);
        }
        showAddEditWindow(dId);
    }

    function showAddEditWindow(id) {
        $.colorbox({
            iframe:true,
            //transition: "fade",
            scrolling: true,
            innerWidth:'960',
            innerHeight:'600',
            href:"addOrEditDrawerData.php?drawerID=" + id + "&addOrEditData=true",
            onClosed:function(){
                if(typeOfColorboxClose == "submit") {
                    console.log("submitteeedddd");
                    if(id != "noId") {
                        updateDrawerDisplay(id);
                    } else {
                        addNewDrawerToView();
                    }
                }
                console.log(typeOfColorboxClose);
            }
        });
    }

    function updateDrawerDisplay(id) {
        console.log("update display: drawer id inside update: " + id);
            var posting = $.post("updateDrawerView.php", {drawerId: id});
            posting.success(function(data) {
                $("#drawer" + id + " .drawerInfo").replaceWith($.parseJSON(data).drawerInfo);
                $("#drawer" + id + " .content").replaceWith($.parseJSON(data).content);
            });
    }

    function addNewDrawerToView() {
        console.log("add new drawer: drawer id inside add");
        var displayedDrawers = [];
        var missingId = "";

        $("[id^='drawer']").each(function() {
            if(this.id.indexOf("drawerInfo") < 0) {
                //console.log(this.id);
                //console.log(this.id.substring(6));
                displayedDrawers.push(this.id.substring(6))
            }
        });
        console.log("on the cseen we have: " + displayedDrawers);

        var posting = $.post("getMissingDrawerForDisplay.php", {existingDrawers: displayedDrawers});
        posting.success(function(data) {
            console.log("data from get missing: " + data);
            console.log("data from get missing - json: " + $.parseJSON(data));
            missingId = $.parseJSON(data);

            var postForDrawer = $.post("getDataForMissingDrawer.php", {drawerId: missingId});
            postForDrawer.success(function(data) {
                console.log("data from get missing data:" + data);
                $(".drawers").append(data);
                $("#drawer" + missingId + " .editDrawerDataBtn").click(function() {
                    prepareForShowForm(this);
                });
                $("#drawer" + missingId + " .deleteDrawerBtn").click(deleteDrawer);
            });
        });

    }

    window.setCloseType = function(type) {
        typeOfColorboxClose = type;
    }

    $(".deleteDrawerBtn").click(deleteDrawer);
    function deleteDrawer() {
        var id = this.name.substring(9);
        if($("#drawer" + id + " .emptyDrawer").length > 0) {
            console.log("the drawer is empty");
            var posting = $.post("deleteDrawerFromFreezer.php", {drawerId: id});
            posting.success(function(data) {
                $("#drawer" + id).remove();
            });
        } else {
            $("#deleteDrawerDialog").data("dId", id);
            $("#deleteDrawerDialog").dialog("open");
            console.log("the drawer NOT empty");

        }
        //emptyDrawer
        //var posting = $.post("deleteDrawerFromFreezer.php", {drawerId: id});
        //posting.success(function(data) {
        //    $("#drawer" + id).remove();
        //});
        console.log(this.name);
    }

    $("#deleteDrawerDialog").dialog({
        autoOpen: false,
        width: 400,
        buttons: [
            {
                text: "Yes, delete drawer!",
                click: function() {
                    var dId = $("#deleteDrawerDialog").data("dId");
                    console.log("deleteing drawer with id: " + dId);
                    var posting = $.post("deleteDrawerFromFreezer.php", {drawerId: dId});
                    posting.success(function(data) {
                        $("#drawer" + dId).remove();
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