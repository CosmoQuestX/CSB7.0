$(function () {
    $("form[name='register']").validate({
        rules: {
            name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                complexity: true,
                minlength: 8
            },
            confirm: {
                required: true,
                equalTo: "#password1"
            }
        },
        messages: {
            password: {
                complexity: "Must include one lowercase, one uppercase, and one digit."
            }
        },
        errorElement: 'p',
        errorClass: 'help-block text-danger',
       submitHandler: function (form) {
            form.submit();
        }
    });
});

$.validator.addMethod("complexity", function (value) {
    return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) // consists of one of these characters
        && /[A-Z]/.test(value) // has an uppercase
        && /[a-z]/.test(value) // has a lowercase letter
        && /\d/.test(value) // has a digit
});