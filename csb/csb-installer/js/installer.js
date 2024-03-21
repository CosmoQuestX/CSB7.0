<!-- DB Connection Tester -->
$(document).ready(function(){
    $("#db-tester").click(function() {
        const data = {
            "db_servername": $("[name='db_servername']").val(),
            "db_username": $("[name='db_username']").val(),
            "db_password": $("[name='db_password']").val(), // Is this secure? Do we care at this point?
            "db_name": $("[name='db_name']").val(),
            "db_port": $("[name='db_port']").val()
        }

        /*
         The response will always be with a 200 status.
         It will look like this for success: { result: true }
         And like this for failures:
         {
             result: false,
             code: <code>,
             message: <error message>
         }
         */

        postData("db-tester.php", data).then( response => {
            if (response.result)
            {
                $("#db-test-status").html(getTimestamp() + "DB Test: Looks good! 👍")
                    .attr("class", "alert alert-success col-12") //Style the message
                    .css({
                        "margin-top": "1rem",
                        "display": "block",
                        "width": "auto",
                        "height": "auto"
                    }) //Bootstrap alerts seem to be overridden to be hidden by something, gotta restore them
            }
            else
            {
                $("#db-test-status").html(getTimestamp() + "DB Test Error: " + response.message)
                    .attr("class", "alert alert-danger col-12")  //Style the message
                    .css({
                        "margin-top": "1rem",
                        "display": "block",
                        "width": "auto",
                        "height": "auto"
                    }) //Bootstrap alerts seem to be overridden to be hidden by something, gotta restore them
            }
        }).catch( err => {
            console.debug(err);
            $("#db-test-status").html(getTimestamp() + "DB Test Error: An unexpected error occurred!")
                .attr("class", "alert alert-danger col-12")  //Style the message
                .css({
                    "margin-top": "1rem",
                    "display": "block",
                    "width": "auto",
                    "height": "auto"
                }) //Bootstrap alerts seem to be overridden to be hidden by something, gotta restore them
        });
    });

    $("#example-email").click(function() {
        const
            rescueEmail = $("[name='rescue_email']"),
            data = {
                "email_host": $("[name='email_host']").val(),
                "email_port": $("[name='email_port']").val(),
                "email_encryption": $("[name='email_encryption']:checked").val(),
                "email_username": $("[name='email_username']").val(),
                "email_password": $("[name='email_password']").val(), // Is this secure? Do we care at this point?
                "email_from": $("[name='email_from']").val(),
                "email_name": $("[name='email_name']").val(),
                "rescue_email": rescueEmail.val() // TODO : If rescue_email is not filled, change to that tab [Directories] and show error
            }

        // Check if rescue_email matches email pattern
        if (!RegExp(rescueEmail[0].pattern).test(data['rescue_email'])) {
            const directoriesTab = $('a.nav-link[href="#directories"]');
            directoriesTab.click(); // Switch to Directories tab
            rescueEmail.focus(); // Show Rescue Email error
            rescueEmail.focus();
            return;
        }

        /*
         The response will always be with a 200 status.
         It will look like this for success: { result: true }
         And like this for failures:
         {
             result: false,
             code: <code>,
             message: <error message>
         }
         */

        postData("example-email.php", data).then( response => {
            if (response.result)
            {
                $("#email-test-status").html(getTimestamp() + "Test Email: Looks good! 👍")
                    .attr("class", "alert alert-success col-12") //Style the message
                    .css({
                        "margin-top": "1rem",
                        "display": "block",
                        "width": "auto",
                        "height": "auto"
                    }) //Bootstrap alerts seem to be overridden to be hidden by something, gotta restore them
            }
            else
            {
                console.debug(response.debug);
                $("#email-test-status").html(getTimestamp() + response.message)
                    .attr("class", "alert alert-danger col-12")  //Style the message
                    .css({
                        "margin-top": "1rem",
                        "display": "block",
                        "width": "auto",
                        "height": "auto"
                    }) //Bootstrap alerts seem to be overridden to be hidden by something, gotta restore them
            }
        }).catch( err => {
            console.debug(err);
            $("#email-test-status").html(getTimestamp() + "Test Email: An unexpected error occurred!")
                .attr("class", "alert alert-danger col-12")  //Style the message
                .css({
                    "margin-top": "1rem",
                    "display": "block",
                    "width": "auto",
                    "height": "auto"
                }) //Bootstrap alerts seem to be overridden to be hidden by something, gotta restore them
        });
    });
});
