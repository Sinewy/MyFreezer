$(document).ready(function() {

    // ************ variables ************\\

    var contentRowNumber = 0;
    var contentRowsToBeDeleted = [];

    // ************ variables ************//

     $("#addNewContentBtn").click(function() {
        //console.log("btn was clicked");
        addNewContentRow();
    });

    function addNewContentRow() {

        console.log("btn was clicked AGAIN");

        var contentId = "noId" + contentRowNumber;
        var contentRow = "";

        contentRow +=  "<div class=\"contentRowStyle\" id=\"contentRow" + contentId + "\">";
        contentRow +=  "<input type=\"text\" id=\"contentDescription" + contentId + "\" name=\"contentDescription" + contentId + "\" size=\"40\" placeholder=\"enter content: stakes\" />";
        contentRow +=  "<input type=\"text\" id=\"contentAmount" + contentId + "\" name=\"contentAmount" + contentId + "\" size=\"25\" placeholder=\"enter amount: 2 pieces\" />";
        contentRow +=  "<input type=\"number\" id=\"contentQuantity" + contentId + "\" name=\"contentQuantity" + contentId + "\" min=\"1\" max=\"99\" value='1' />";
        contentRow +=  "<input type=\"date\" id=\"contentDate" + contentId + "\" name=\"contentDate" + contentId + "\" placeholder=\"mm/dd/YYYY\" />";
        contentRow +=  "<input class=\"deleteCurrentRowBtn button\" type=\"button\" name=\"" + contentId + "\" value=\"Delete\" />";
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
            $("#contentRow" + this.name).replaceWith("<p id='emptyDrawer' class='emptyDrawer'>This drawer is still empty. Add some content.</p>");
        }
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
                    parent.setCloseType("submit");
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
        parent.setCloseType("cancel");
        parent.$.colorbox.close();
    });

});