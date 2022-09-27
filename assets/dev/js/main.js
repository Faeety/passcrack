function loadTable() {
    if (document.hasFocus()) $("#page-table").load("subpages/table.page.php");
}

$(document).ready(() => {
    let pass = $("#input-password");

    pass.on("focusin", () => {
        pass.data("val", pass.val());
    })

    pass.on("input", (e) => {
        let oldVal = pass.data("val");
        let newVal = pass.val();
        let type = $(".btn-group-hash").find(".btn-hash-type.active")[0].id;

        let uppercaseCount = (newVal.match(/[A-Z]/g) || []).length;
        let digitsCount = (newVal.match(/[0-9]/g) || []).length;
        let specialcharsCount = (newVal.match(/[^a-zA-Z\d\s:]/g) || []).length;

        console.log(specialcharsCount);

        let percentage = 100 - ( newVal.length * 3 ) - ( uppercaseCount * 2 ) - ( digitsCount * 2 ) - ( specialcharsCount * 4 ) - ( type * 3 );
        if( oldVal.length - newVal.length < 0) $("#form-progress-bar").width(`${percentage}%`);
        else if ( oldVal.length - newVal.length > 0) $("#form-progress-bar").width(`${percentage}%`);

        pass.data("val", pass.val());
    });

    $("#btn-crack").click(() => {
        let pass = $("#input-password").val();
        let type = $(".btn-group-hash").find(".btn-hash-type.active")[0].id;

        $.post("../../../scripts/add_password.php", {
            password: pass,
            type: type
        }).done(data => {
            let modal = $("#modalForm");
            let modalTitle = $("#modal-title");
            let modalContent = $("#modal-content");

            if (data === "no password" || data === "no type"){
                modalTitle.text("❌ Une erreure s'est produite");
                modalContent.text("Veuillez vérifier vos entrées. Avez-vous bien sélectionné un type et saisis un mot de passe ?");
            }else if (data === "already cracking") {
                modalTitle.text("❌ Une erreure s'est produite");
                modalContent.text("Vous avez déjà un mot de passe en tentative de crack.");
            }else if (data === "too long") {
                modalTitle.text("❌ Une erreure s'est produite");
                modalContent.text("Votre mot de passe demande trop de ressources. Cependant, cela ne garantit pas sa complexité.");
            }else {
                modalTitle.text("✔ Succès");
                modalContent.text(`Votre mot de passe va tenté d'être cracké. #${data}`);
            }

            const formModal = new bootstrap.Modal(modal, {
                keyboard: false
            });

            formModal.show();
        });
    });

    $(".btn-group-hash").find(".btn-hash-type").click((element) => {
        let btn = $(element.target);
        $(".btn-group-hash").find(".btn-hash-type").removeClass("active");
        btn.addClass("active");
    });

    loadTable()
    setInterval(loadTable, 5000);
});