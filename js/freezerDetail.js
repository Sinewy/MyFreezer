

$(document).ready(function() {

    adjustMainSectionSize();

    // ************ variables ************\\

    var typeOfColorboxClose = "";

    // ************ variables ************//

    $("#addNewDrawerBtn, .editDrawerBtn").click(function() {
        prepareForShowForm(this);
    });

    function prepareForShowForm(element) {
        var dId = null;
        if(element.id == "addNewDrawerBtn") {
            console.log("add new drawer " + element.id);
            console.log(" drawer ididid: " + dId);
            dId = "noId";
        } else {
            console.log("else - edit");
            var drawerName = element.id;
            dId = drawerName.substring(10);
            console.log("drawer id: "  + dId);
        }
        showAddEditWindow(dId);
    }

    function showAddEditWindow(id) {
        $.colorbox({
            iframe:true,
            //transition: "fade",
            scrolling: true,
            overlayClose: false,
            escKey: false,
            closeButton: false,
            innerWidth:'960',
            innerHeight:'840',
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
                $("#drawer" + id).replaceWith($.parseJSON(data).drawer);

                $("#drawer" + id + " .editDrawerBtn").click(function() {
                    prepareForShowForm(this);
                });
                $("#drawer" + id + " .deleteDrawerBtn").click(deleteDrawer);
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
                if($("#noDrawersYet").length) {
                    console.log("no drawer yet exists")
                    $("#noDrawersYet").remove();
                }
                $(".drawers").append(data);
                console.log("adding edit click to missing id: " + missingId);
                $("#drawer" + missingId + " .editDrawerBtn").click(function() {
                    prepareForShowForm(this);
                });
                $("#drawer" + missingId + " .deleteDrawerBtn").click(deleteDrawer);
            });
        });

    }

    window.setCloseType = function(type) {
        typeOfColorboxClose = type;
    };

    $(".deleteDrawerBtn").click(deleteDrawer);
    function deleteDrawer() {
        var id = this.id.substring(12);

        console.log("delete id: " + id);

        if($("#drawer" + id + " .emptyDrawer").length > 0) {
            console.log("the drawer is empty");
            var posting = $.post("deleteDrawerFromFreezer.php", {drawerId: id});
            posting.success(function(data) {
                $("#drawer" + id).remove();
                if($("[id^='drawer']").length == 0) {
                    var noDrawerYet = "<p id='noDrawersYet'>There are no drawers in this freezer yet. Please add them.</p>";
                    $(".drawers").append(noDrawerYet);
                }
            });
        } else {
            $("#deleteDrawerDialog").data("dId", id);
            $("#deleteDrawerDialog").dialog("open");
            console.log("the drawer NOT empty");

        }
        //console.log(this.name);
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
                        if($("[id^='drawer']").length == 0) {
                            var noDrawerYet = "<p id='noDrawersYet'>There are no drawers in this freezer yet. Please add them.</p>";
                            $(".drawers").append(noDrawerYet);
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


    function adjustMainSectionSize() {
        console.log("Adjusting height");
        var footerHeight = $("footer").height();
        var headerHeight = $("header").height() + $("nav").height();
        var spacerHeight = 27;
        var height = $(window).height() - footerHeight - headerHeight - spacerHeight;
        var mySection = $(".sectionContainerFreezerDetail");
        if(mySection.height() < height) {
            mySection.height(height);
        }
    }

    $(window).resize(function() {
        $(".sectionContainerFreezerDetail").height('auto');
        adjustMainSectionSize();
    });

});