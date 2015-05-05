$(document).ready(function() {

    $("#saveEditFreezerDataForm").click(function() {
        // validate all necessary fields - if everything ok.. submit
        var freezerId = $("#getFreezerId").val();
        var errorCount = 0;
        if(!hasPresence($("#freezerName").val())) {
            console.log("errors: ");
            $("#fNameError").html("Freezer name should be set.");
            errorCount++;
        } else {
            $("#fNameError").html(" ");
        }

        console.log("errors: " + errorCount);
        // everything is OK - submit
        if(errorCount == 0) {
            console.log("No errors, we can submit.");

            //console.log("freezer name: " + $("#freezerName").val());
            //console.log("freezer descri: " + $("#freezerDescription").val());
            //console.log("freezer location: " + $("#freezerLocation").val());
            //console.log("freezer make: " + $("#freezerMake").val());

            var dataObj = new Object();
            dataObj.saveData = "true";
            dataObj.freezerId = freezerId;
            dataObj.freezerName = $("#freezerName").val();
            dataObj.freezerDescription = $("#freezerDescription").val();
            dataObj.freezerLocation = $("#freezerLocation").val();
            dataObj.freezerMake = $("#freezerMake").val();

            var posting = $.post("saveFreezerData.php", dataObj);
            posting.success(function(data) {
                if(data.indexOf("saveSuccessful") < 0) {
                    // display message what went wrong
                    console.log("save failed  "+data);
                } else {
                    // if saving data to db was successful close form and update current drawer data view
                    console.log("freezerId: " + freezerId);
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
        //console.log("presense: " + hasPresence($("#drawerName").val()));
        //console.log("hax max leng: " + hasMaxLength($("#drawerName").val(), 8));
        //console.log("hax MIN leng: " + hasMinLength($("#drawerName").val(), 3));
        // console.log("in num betw: " + isNumberBetween($("#drawerName").val(), 3 , 25));
        console.log("pressed cancel btn on colorbox");
        parent.setCloseType("cancel");
        parent.$.colorbox.close();
    });

});