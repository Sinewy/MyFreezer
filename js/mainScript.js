$(document).ready(function() {

    // ************ variables ************\\

    var contentRowNumber = 0;
    var contentRowsToBeDeleted = [];
    //var drawerId = null;

    // ************ variables ************//

//    $("#addNewDrawerBtn, .editDrawerDataBtn").click(showAddEditWindow);
//    function showAddEditWindow() {
//        disableButtons();
//
//        console.log(drawerId + " drawer id - show edit windiw");
//
//        if(this.id == "addNewDrawerBtn") {
//            console.log("add new drawer " + this.id);
//            console.log(" drawer ididid: " + drawerId);
//            addNewContentRow();
//        } else {
//            var drawerName = this.name;
//            drawerId = drawerName.substring(7);
//            console.log("edit drawer data for drawer: " + drawerId);
//
//            var ajaxRequest = $.ajax({
//                type: "POST",
//                url: "getDrawerData.php",
//                data: {drawerID: drawerId}
//            });
//            ajaxRequest.done(function(data) {
//                handleDrawerData(data);
//            });
//        }
//        $("#addEditDrawer").toggleClass("hiddenElement");
//    }

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

    function showAddEditWindow(id) {
        $.colorbox({
            open: true,
            //iframe:true,
            scrolling: false,
            innerWidth:'800',
            innerHeight:'600',
            href:"testIframePost.php",
            data:{drawerID: id, itemType: "POSTIC madafakaaa...", addOrEditData: true},
            //href:"testIframePost.php?LinkID=" + 21635984 + "&itemType=" + "DzureTIPEK",
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
        addNewContentRow();
    });

    function addNewContentRow() {
//    function addNewContentRow(contentId, description, amount, quantity, date) {

//        contentId = typeof contentId !== 'undefined' ?  contentId : "noId" + contentRowNumber;
//        description = typeof description !== 'undefined' ?  description : "";
//        amount = typeof amount !== 'undefined' ?  amount : "";
//        quantity = typeof quantity !== 'undefined' ?  quantity : 1;
//        date = typeof date !== 'undefined' ?  date : "";

        var contentId = "noId" + contentRowNumber;
        var contentRow = "";

//        contentRow +=  "<div class=\"contentRowStyle\" id=\"contentRow" + contentId + "\">";
//        contentRow +=  "<input type=\"text\" id=\"contentDescription" + contentId + "\" name=\"contentDescription" + contentId + "\" size=\"60\" placeholder=\"enter content: stakes\" value=\"" + description + "\" />";
//        contentRow +=  "<input type=\"text\" id=\"contentAmount" + contentId + "\" name=\"contentAmount" + contentId + "\" size=\"30\" placeholder=\"enter amount: 2 pieces\" value=\"" + amount + "\" />";
//        contentRow +=  "<input type=\"number\" id=\"contentQuantity" + contentId + "\" name=\"contentQuantity" + contentId + "\" min=\"1\" max=\"20\" placeholder=\"quantity: 4\" value=\"" + quantity + "\" />";
//        contentRow +=  "<input type=\"date\" id=\"contentDate" + contentId + "\" name=\"contentDate" + contentId + "\" placeholder=\"mm/dd/YYYY\" value=\"" + date + "\" />";
//        contentRow +=  "<input class=\"deleteCurrentRowBtn\" type=\"button\" name=\"" + contentId + "\" value=\"Delete\" />";
//        contentRow +=  "</div>";

        contentRow +=  "<div class=\"contentRowStyle\" id=\"contentRow" + contentId + "\">";
        contentRow +=  "<input type=\"text\" id=\"contentDescription" + contentId + "\" name=\"contentDescription" + contentId + "\" size=\"60\" placeholder=\"enter content: stakes\" />";
        contentRow +=  "<input type=\"text\" id=\"contentAmount" + contentId + "\" name=\"contentAmount" + contentId + "\" size=\"30\" placeholder=\"enter amount: 2 pieces\" />";
        contentRow +=  "<input type=\"number\" id=\"contentQuantity" + contentId + "\" name=\"contentQuantity" + contentId + "\" min=\"1\" max=\"20\" placeholder=\"quantity: 4\" />";
        contentRow +=  "<input type=\"date\" id=\"contentDate" + contentId + "\" name=\"contentDate" + contentId + "\" placeholder=\"mm/dd/YYYY\" />";
        contentRow +=  "<input class=\"deleteCurrentRowBtn\" type=\"button\" name=\"" + contentId + "\" value=\"Delete\" />";
        contentRow +=  "</div>";

        if($("#emptyDrawer").length != 0) {
            $("#emptyDrawer").replaceWith(contentRow);
        } else {
            $("#drawerContent").append(contentRow);
        }

        $("#contentRow" + contentId + " .deleteCurrentRowBtn").on("click", deleteContentRow);

//        $("#contentRow" + contentId + " .deleteCurrentRowBtn").on("click", function() {
//            console.log(this.name.indexOf("noId"));
//            if(this.name.indexOf("noId") == -1) {
//                contentRowsToBeDeleted.push(contentId);
//            }
//            $("#contentRow" + this.name).remove();
//        });

        contentRowNumber++;
    }

    $(".deleteCurrentRowBtn").on("click", deleteContentRow);

//    $(".deleteCurrentRowBtn").on("click", function() {
//        console.log(this.name.indexOf("noId"));
//        if(this.name.indexOf("noId") == -1) {
//            contentRowsToBeDeleted.push(this.name);
//        }
//        $("#contentRow" + this.name).remove();
//    });

    function deleteContentRow() {
        console.log(this.name.indexOf("noId"));
        console.log(this.name);
        if(this.name.indexOf("noId") == -1) {
            contentRowsToBeDeleted.push(this.name);
            console.log(this.name);
        }
        $("#contentRow" + this.name).remove();
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
        //TODO - add hidden input to provide which contentIDs to delete from db
        $("#addEditDrawerForm").submit();
    });


});
