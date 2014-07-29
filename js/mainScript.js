$(document).ready(function() {

    // ************ variables ************\\

    var contentRowNumber = 0;
    var contentRowsToBeDeleted = [];
    //var drawerId = null;

    // ************ variables ************//

    $("#addNewDrawerBtn, .editDrawerDataBtn").click(function() {
        var dId = null;
        if(this.id == "addNewDrawerBtn") {
            console.log("add new drawer " + this.id);
            console.log(" drawer ididid: " + dId);
            //addNewContentRow();
            dId = "noId";
        } else {
            var drawerName = this.name;
            dId = drawerName.substring(7);
        }
        showAddEditWindow(dId);
    });

    //TODO - try to do it with GET option
    function showAddEditWindow(id) {
        $.colorbox({
            iframe:true,
            //open: true,
            //transition: "fade",
            scrolling: true,
            innerWidth:'960',
            innerHeight:'600',
            href:"addOrEditDrawerData.php",
            //data:{drawerID: id, addOrEditData: true},
            //href:"testIframePost.php",
            href:"addOrEditDrawerData.php?drawerID=" + id + "&addOrEditData=true",
//            onComplete:function() {
//                $("#addNewContentBtn").on("click", function() {
//                    //(".addNewContentLine").on("click", function() {
//                    console.log("btn was clicked");
//                    addNewContentRow();
//                });
//            },

            onClosed:function(){
                //Do something on close.
            }
        });
    }

    function handleDrawerData(data){
        $("#drawerName").val(data.info.Name);
        $("#drawerDescription").val(data.info.Description);
        if(data.content.length > 0) {
            $.each(data.content, function(i, value) {
                addNewContentRow(value.ContentID, value.Description, value.Amount, value.Quantity);
                //console.log(value.Description);
            });
        } else if(data.content.length == 0) {
            addNewContentRow();
        }
    }



    $("#addNewContentBtn").click(function() {
        console.log("btn was clicked");
        addNewContentRow();
    });

    function addNewContentRow() {

        console.log("btn was clicked AGAIN");

        var contentId = "noId" + contentRowNumber;
        var contentRow = "";

        contentRow +=  "<div class=\"contentRowStyle\" id=\"contentRow" + contentId + "\">";
        contentRow +=  "<input type=\"text\" id=\"contentDescription" + contentId + "\" name=\"contentDescription" + contentId + "\" size=\"60\" placeholder=\"enter content: stakes\" />";
        contentRow +=  "<input type=\"text\" id=\"contentAmount" + contentId + "\" name=\"contentAmount" + contentId + "\" size=\"30\" placeholder=\"enter amount: 2 pieces\" />";
        contentRow +=  "<input type=\"number\" id=\"contentQuantity" + contentId + "\" name=\"contentQuantity" + contentId + "\" min=\"1\" max=\"20\" placeholder=\"quantity: 4\" />";
        contentRow +=  "<input type=\"date\" id=\"contentDate" + contentId + "\" name=\"contentDate" + contentId + "\" placeholder=\"mm/dd/YYYY\" />";
        contentRow +=  "<input class=\"deleteCurrentRowBtn\" type=\"button\" name=\"" + contentId + "\" value=\"Delete\" />";
        contentRow +=  "</div>";

        if($("#emptyDrawer").length != 0) {
            console.log("empty exists");
            $("#emptyDrawer").replaceWith(contentRow);
        } else {
            console.log("empty DOES NOT exist");
            $("#drawerContent").append(contentRow);
        }
        console.log("over empty check");

        $("#contentRow" + contentId + " .deleteCurrentRowBtn").on("click", deleteContentRow);
        contentRowNumber++;
    }

    $(".deleteCurrentRowBtn").on("click", deleteContentRow);

    function deleteContentRow() {
        console.log(this.name.indexOf("noId"));
        console.log(this.name);
        if(this.name.indexOf("noId") == -1) {
            contentRowsToBeDeleted.push(this.name);
            console.log(this.name);
        }
        console.log($(".contentRowStyle").length + "  length for .contentRowStyle");
        if($(".contentRowStyle").length > 1) {
            $("#contentRow" + this.name).remove();
        } else {
            $("#contentRow" + this.name).replaceWith("<p id='emptyDrawer'>This drawer is still empty. Add some content.</p>");
        }

    }

    $("#cancelDrawerDataBtn").click(cleanupAfterCancel);
    function cleanupAfterCancel() {

        //console.log(contentRowsToBeDeleted);
        //console.log(contentRowsToBeDeleted.length);

        $(".contentRowStyle").remove();
        $("#addEditDrawer").toggleClass("hiddenElement");

        $("#drawerName").val("");
        $("#drawerDescription").val("");

        contentRowNumber = 0;
        contentRowsToBeDeleted = [];
        drawerId = null;

        enableButtons();
    }

    $(".deleteDrawerBtn").click(deleteDrawer);
    function deleteDrawer() {
        console.log(this.name);
    }

    function disableButtons() {
        $("#addNewDrawerBtn, .editDrawerDataBtn").off("click");
        $(".deleteDrawerBtn").off("click");
    }

    function enableButtons() {
        $("#addNewDrawerBtn, .editDrawerDataBtn").click(showAddEditWindow);
        $(".deleteDrawerBtn").click(deleteDrawer);
    }

    $("#saveDrawerData").click(saveDrawerData);
    function saveDrawerData() {
        console.log(drawerId + " drawer id");
        var ajaxRequest = $.ajax({
            type: "POST",
            url: "saveDrawerData.php",
            data: {
                saveData: true,
                drawerID: drawerId,
                deleteContent: contentRowsToBeDeleted
            },
            dataType: "html"
        });
        ajaxRequest.done(function(data) {
            updateDrawerDisplay(data);
        });
    }

    function updateDrawerDisplay(data) {
        console.log(data);
        console.log("  drawer id inside update: " + drawerId);
        if(drawerId != null) {
            $("#drawer" + drawerId).replaceWith(data);
        } else {
            $(".drawers").append(data);
        }
        cleanupAfterCancel();
    }


    $("#saveEditDrawerDataForm").click(function() {
        var hiddentElement = "";
        if(contentRowsToBeDeleted.length > 0) {
            hiddentElement += "<input type='hidden' name='contentToBeDeletedArray' value=" + contentRowsToBeDeleted + " />";
        }
        $("#submitOrCancel").append(hiddentElement);
        $("#addEditDrawerForm").submit();
    });

    $("#cancelAndCloseForm").click(function() {
        parent.$.colorbox.close();
    });


});
