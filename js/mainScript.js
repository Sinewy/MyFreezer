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

    function showAddEditWindow(id) {
        $.colorbox({
            iframe:true,
            //transition: "fade",
            scrolling: true,
            innerWidth:'960',
            innerHeight:'600',
            href:"addOrEditDrawerData.php?drawerID=" + id + "&addOrEditData=true",
//            onComplete:function() {
//                $("#addNewContentBtn").on("click", function() {
//                    //(".addNewContentLine").on("click", function() {
//                    console.log("btn was clicked");
//                    addNewContentRow();
//                });
//            },
            onClosed:function(m){
                console.log("closing colorbox id: " + id);
                console.log("closing message: " + m.arg1);
                $.each(m, function(index, value) {
                    console.log(index + " :index, values are: " + value);
                    console.log(index + " :index, values are: " + value);
                    if(index == "cache"){
                        $.each(value, function(i, v) {
                            console.log(1 + " :index, values are: " + v);
                        });
                    }
                });
//                for(var key in message) {
//                    console.log(key + " the key and the value: " + message[key]);
//                }
                if(id != "noId") {
                    updateDrawerDisplay(id);
                } else {
                    addNewDrawerToView();
                }
            }
        });
    }

    $("#addNewContentBtn").click(function() {
        //console.log("btn was clicked");
        addNewContentRow();
    });

    function addNewContentRow() {

        //console.log("btn was clicked AGAIN");

        var contentId = "noId" + contentRowNumber;
        var contentRow = "";

        contentRow +=  "<div class=\"contentRowStyle\" id=\"contentRow" + contentId + "\">";
        contentRow +=  "<input type=\"text\" id=\"contentDescription" + contentId + "\" name=\"contentDescription" + contentId + "\" size=\"60\" placeholder=\"enter content: stakes\" />";
        contentRow +=  "<input type=\"text\" id=\"contentAmount" + contentId + "\" name=\"contentAmount" + contentId + "\" size=\"30\" placeholder=\"enter amount: 2 pieces\" />";
        contentRow +=  "<input type=\"number\" id=\"contentQuantity" + contentId + "\" name=\"contentQuantity" + contentId + "\" min=\"1\" max=\"99\" value='1' />";
        contentRow +=  "<input type=\"date\" id=\"contentDate" + contentId + "\" name=\"contentDate" + contentId + "\" placeholder=\"mm/dd/YYYY\" />";
        contentRow +=  "<input class=\"deleteCurrentRowBtn\" type=\"button\" name=\"" + contentId + "\" value=\"Delete\" />";
        contentRow +=  "</div>";
        contentRow +=  "<div class='formError' id='fError" + contentId + "' ></div>";

        if($("#emptyDrawer").length != 0) {
            //console.log("empty exists");
            $("#emptyDrawer").replaceWith(contentRow);
        } else {
            //console.log("empty DOES NOT exist");
            $("#drawerContent").append(contentRow);
        }
        //console.log("over empty check");

        $("#contentRow" + contentId + " .deleteCurrentRowBtn").on("click", deleteContentRow);
        contentRowNumber++;
    }

    $(".deleteCurrentRowBtn").on("click", deleteContentRow);

    function deleteContentRow() {
        if(this.name.indexOf("noId") == -1) {
            contentRowsToBeDeleted.push(this.name);
        }
        if($(".contentRowStyle").length > 1) {
            $("#contentRow" + this.name).remove();
            $("#fError" + this.name).remove();
        } else {
            $("#contentRow" + this.name).replaceWith("<p id='emptyDrawer'>This drawer is still empty. Add some content.</p>");
        }
    }

    $(".deleteDrawerBtn").click(deleteDrawer);
    function deleteDrawer() {
        console.log(this.name);
    }

    function updateDrawerDisplay(id) {
        //console.log("  drawer id inside update: " + id);
        var posting = $.post("updateDrawerView.php", {drawerId: id});
        posting.success(function(data) {
            $("#drawer" + id + " .drawerInfo").replaceWith($.parseJSON(data).drawerInfo);
            $("#drawer" + id + " .content").replaceWith($.parseJSON(data).content);
        });
    }

    function addNewDrawerToView() {
        //console.log("  drawer id inside update: " + id);
        var displayedDrawers = [];

        $("[id^='drawer']").each(function() {
            if(this.id.indexOf("drawerInfo") < 0) {
                console.log(this.id);
                console.log(this.id.substring(6));
                displayedDrawers.push(this.id.substring(6))
            }
        });

//        var posting = $.post("updateDrawerView.php", {drawerId: id});
//        posting.success(function(data) {
//            $("#drawer" + id + " .drawerInfo").replaceWith($.parseJSON(data).drawerInfo);
//            $("#drawer" + id + " .content").replaceWith($.parseJSON(data).content);
//        });
    }

    $("#saveEditDrawerDataForm").click(function() {
        // validate all necessary fields - if everything ok.. submit
        var drawerId = $("#getDrawerId").val();
        var errorCount = 0;
        if(!hasPresence($("#drawerName").val())) {
            $("#dNameError").html("Drawer name should be set.");
            errorCount++;
        } else {
            $("#dNameError").html(" ");
        }

        $("[id^='contentRow']").each(function() {
            var id = this.id.substring(10);
            var errors = "";
            if(!hasPresence($("#contentDescription" + id).val())) {
                errors += "Description should not be blank. Please enter content description. <br />";
                errorCount++;
            }
            if(!isNumberBetween($("#contentQuantity" + id).val(), 1, 99)) {
                errors += "Quantity should be a number between 1 and 99.";
                errorCount++;
            }
            if(errors != "") {
                $("#fError" + id).html(errors);
            } else {
                $("#fError" + id).html(" ");
            }
        });

        // everything is OK - submit
        if(errorCount == 0) {
            console.log("No errors, we can submit.");
            var contentRowData = [];
            $("[id^='contentRow']").each(function() {
                var id = this.id.substring(10);
                var rowObj = new Object();
                rowObj.id = id;
                rowObj.description = $("#contentDescription" + id).val();
                rowObj.amount = $("#contentAmount" + id).val();
                rowObj.qty = $("#contentQuantity" + id).val();
                rowObj.date = $("#contentDate" + id).val();
                contentRowData.push(rowObj);
            });
            var dataObj = new Object();
            dataObj.saveData = "true";
            dataObj.drawerId = drawerId;
            dataObj.drawerName = $("#drawerName").val();
            dataObj.drawerDescription = $("#drawerDescription").val();
            dataObj.contentData = contentRowData;
            dataObj.contentDelete = contentRowsToBeDeleted;

            var posting = $.post("saveDrawerData.php", dataObj);
            posting.success(function(data) {
                if(data.indexOf("saveSuccessful") < 0) {
                    // display message what went wrong
                    console.log("save failed");
                } else {
                    // if saving data to db was successful close form and update current drawer data view
                    console.log("drawerId: " + drawerId);
                    console.log("save succeeded");
                    //updateDrawerDisplay(drawerId);
                    parent.$.colorbox.close();
                }
                console.log("done data: " + data.indexOf("saveSuccessful"));
                console.log("done data: " + data);
            });
        }
    });

    $("#cancelAndCloseForm").click(function() {
        console.log("presense: " + hasPresence($("#drawerName").val()));
        console.log("hax max leng: " + hasMaxLength($("#drawerName").val(), 8));
        console.log("hax MIN leng: " + hasMinLength($("#drawerName").val(), 3));
        console.log("in num betw: " + isNumberBetween($("#drawerName").val(), 3 , 25));
        //window.parent.calledFromIframe("Canceled");
        //parent.jQuery.colorbox.close("noooooooooooooooooooooooooo");
        parent.jQuery.colorbox.close("noooooooooooooooooooooooooo");
    });

    function calledFromIframe(typeOfClose) {
        console.log(typeOfClose);
    }

//*************************** Validation Functions ***************************\\

    function hasPresence(value) {
        return value.trim().length != 0;
    }

    function hasMaxLength(value, max) {
        return value.trim().length <= max;
    }

    function hasMinLength(value, min) {
        return value.trim().length >= min;
    }

    function isNumberBetween(value, min, max) {
        var intValue = parseInt(value.trim());
        if($.isNumeric(value)) {
            return intValue >= min && intValue <= max;
        }
        return false;
    }

//*************************** -  END Validations - ***************************//


});

//************************ Probably unused functions ***************************\\

function disableButtons() {
    $("#addNewDrawerBtn, .editDrawerDataBtn").off("click");
    $(".deleteDrawerBtn").off("click");
}

function enableButtons() {
    $("#addNewDrawerBtn, .editDrawerDataBtn").click(showAddEditWindow);
    $(".deleteDrawerBtn").click(deleteDrawer);
}

//$("#saveDrawerData").click(saveDrawerData);
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