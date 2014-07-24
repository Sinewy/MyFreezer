$(document).ready(function() {

    $("#addNewDrawerBtn, .editDrawerDataBtn").click(showAddEditWindow);
    function showAddEditWindow() {
        disableButtons();
        if(this.id == "addNewDrawerBtn") {
            console.log("add new drawer " + this.id);
            addNewContentRow();
        } else {
            var drawerName = this.name;
            var drawerId = drawerName.substring(7);
            console.log("edit drawer data for drawer: " + drawerId);

            var ajaxRequest = $.ajax({
                type: "POST",
                url: "getDrawerData.php",
                data: {drawerID: drawerId}
            });
            ajaxRequest.done(function(data) {
                handleDrawerData(data);
            });
        }
        $("#addEditDrawer").toggleClass("hiddenElement");
    }

    function handleDrawerData(data){
        $("#drawerName").val(data.info.Name);
        $("#drawerDescription").val(data.info.Description);
        if(data.content.length > 0) {
            $.each(data.content, function(i, value) {
                addNewContentRow(value.ContewntID, value.Description, value.Amount, value.Quantity);
                console.log(value.Description);
            });
        } else if(data.content.length == 0) {
            addNewContentRow();
        }
    }

    $("#addNewContentBtn").click(function() {
        addNewContentRow();
    });
    var contentRowNumber = 0;
    function addNewContentRow(contentId, description, amount, quantity, date) {

        contentId = typeof contentId !== 'undefined' ?  contentId : "noId" + contentRowNumber;
        description = typeof description !== 'undefined' ?  description : "";
        amount = typeof amount !== 'undefined' ?  amount : "";
        quantity = typeof quantity !== 'undefined' ?  quantity : 1;
        date = typeof date !== 'undefined' ?  date : "";

        var contentRow = "";
        contentRow +=  "<div class=\"contentRowStyle\" id=\"contentRow" + contentId + "\">";
        contentRow +=  "<input type=\"text\" id=\"contentDescription" + contentId + "\" name=\"contentDescription" + contentId + "\" size=\"60\" placeholder=\"enter content: stakes\" value=\"" + description + "\" />";
        contentRow +=  "<input type=\"text\" id=\"contentAmount" + contentId + "\" name=\"contentAmount" + contentId + "\" size=\"30\" placeholder=\"enter amount: 2 pieces\" value=\"" + amount + "\" />";
        contentRow +=  "<input type=\"number\" id=\"contentQuantity" + contentId + "\" name=\"contentQuantity" + contentId + "\" min=\"1\" max=\"20\" placeholder=\"quantity: 4\" value=\"" + quantity + "\" />";
        contentRow +=  "<input type=\"date\" id=\"contentDate" + contentId + "\" name=\"contentDate" + contentId + "\" placeholder=\"mm/dd/YYYY\" value=\"" + date + "\" />";
        if(contentRowNumber != 0) {
            contentRow +=  "<input class=\"deleteCurrentRowBtn\" type=\"button\" name=\"" + contentId + "\" value=\"Delete\" />";
        }
        contentRow +=  "</div>";
        $("#drawerContent").append(contentRow);
        $("#contentRow" + contentId + " .deleteCurrentRowBtn").on("click", function() {
            $("#contentRow" + this.name).remove();
        });
        contentRowNumber++;
    }

    $("#cancelDrawerDataBtn").click(cleanupAfterCancel);
    function cleanupAfterCancel() {
        $(".contentRowStyle").remove();
        contentRowNumber = 0;
        $("#addEditDrawer").toggleClass("hiddenElement");

        $("#drawerName").val("");
        $("#drawerDescription").val("");

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


});
