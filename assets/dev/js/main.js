$(document).ready(() => {
    $("#btn-crack").click(() => {
        let pass = $("#input-password").val();

        $.post("../../../scripts/add_password.php", {
            password: pass
        }).done(data => {
            console.log(data);
        });
    });
});