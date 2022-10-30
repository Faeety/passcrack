function alert(message, type) {
    const wrapper = document.createElement('div')
    wrapper.innerHTML = [
        `<div class="alert alert-${type} alert-dismissible" role="alert">`,
        `   <div>${message}</div>`,
        '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
        '</div>'
    ].join('')

    $("#div-alert").append(wrapper)
}

function loadTable() {
    if (document.hasFocus()) $("#page-table").load("subpages/table.page.php", function () {
        let idCached = sessionStorage.getItem("resultId");
        let resultCached = sessionStorage.getItem("resultText");

        $(".btn-result").click(function() {
            let result = $(this)[0];
            GetResult(result.id, result);
        });

        if(idCached) {
            let btn = $(`#${idCached}`);
            btn.replaceWith(resultCached);
        }
    });
}

function loadSettings() {
    $("#settings-content").load("subpages/settings.page.php", function () {
        $("#btn-start-cron").click(ExecuteCron);

        $.get("../../../scripts/get_ipblacklist_status.php").done(data => {
            if (data === "true"){
                $("#blacklist-switch").attr("checked", true);
            }else if(data === "false"){

            }
        });

        $('#blacklist-switch').change(function() {
            if(this.checked) {
                $.post("../../../scripts/change_ip_blacklist_value.php", {
                    newval: "true"
                }).done(data => {
                    if(data === "done"){
                        alert("Paramètre changé avec succès!", "success")
                    }
                });
            }else{
                $.post("../../../scripts/change_ip_blacklist_value.php", {
                    newval: "false"
                }).done(data => {
                    if(data === "done"){
                        alert("Paramètre changé avec succès!", "success")
                    }
                });
            }
        });
    });
}

function loadEmailSender() {
    $("#settings-content").load("subpages/email.page.php", function () {
        $("#btn-email-send").click((element) => {
            let env = $("#email-sender").val();
            let dest = $("#email-recipient").val();
            let sujet = $("#email-title").val();
            let contenu = $("#email-content").val();

            $.post("../../../scripts/send_email.php", {
                sender: env,
                recipient: dest,
                title: sujet,
                content: contenu
            }).done(data => {
                if(data === "done"){
                    alert("L'email a été envoyé avec succès", "success")
                    $("#email-sender").val("");
                    $("#email-recipient").val("");
                    $("#email-title").val("");
                    $("#email-content").val("");
                }
            });
        });
    });
}

function Connexion() {
    let pass = $("#admin-password").val();

    $.post("../../../scripts/connexion.php", {
        password: pass
    }).done((data) => {
        if(data === "good password"){
            window.location = "admin.php";
        }else{
            let modal = $("#modalForm");
            let modalTitle = $("#modal-title");
            let modalContent = $("#modal-content");

            modalTitle.text("❌ Une erreure s'est produite");
            modalContent.text("Veuillez vérifier le mot de passe que vous avez entré!");

            const formModal = new bootstrap.Modal(modal, {
                keyboard: false
            });

            formModal.show();
        }
    })
}

function ExecuteCron() {
    $.get("../../../crons/create_instance.php").done((data) => {
        if(data){
            alert("Le cron s'est executé avec succès!", 'success')
        }
    })
}

// Merci Renato Mangini pour les deux fonctions ci-dessous https://developer.chrome.com/blog/how-to-convert-arraybuffer-to-and-from-string/
function ab2str(buf) {
    return String.fromCharCode.apply(null, new Uint8Array(buf));
}

function str2ab(str) {
    let buf = new ArrayBuffer(str.length);
    let bufView = new Uint8Array(buf);
    for (let i = 0, strLen = str.length; i < strLen; i++) {
        bufView[i] = str.charCodeAt(i);
    }
    return buf;
}

function StoreKeys(pvkey, pbkey) {
    localStorage.setItem("pvkey", pvkey);
    localStorage.setItem("pbkey", pbkey);
}

// Grand merci à l'utilisateur Topaco sur StackOverflow https://stackoverflow.com/questions/73891120/encrypt-on-php-and-decrypt-on-javascript
// https://www.w3.org/TR/WebCryptoAPI/
async function GenerateKeys() {
    let key = await window.crypto.subtle.generateKey(
        {
            name: "RSA-OAEP",
            modulusLength: 4096,
            publicExponent: new Uint8Array([0x01, 0x00, 0x01]),
            hash: {name: "SHA-1"}
        },
        true,
        ["encrypt", "decrypt"]
    )

    let pvkey = await window.crypto.subtle.exportKey(
        "pkcs8",
        key.privateKey
    )

    let pbkey = await window.crypto.subtle.exportKey(
        "spki",
        key.publicKey
    )

    let pemPvKey = `-----BEGIN PRIVATE KEY-----\n${window.btoa(ab2str(pvkey))}\n-----END PRIVATE KEY-----`;
    let pemPbKey = `-----BEGIN PUBLIC KEY-----\n${window.btoa(ab2str(pbkey))}\n-----END PUBLIC KEY-----`;

    return [pemPvKey, pemPbKey]
}

function FormatPrivateKey(pemPvKey) {
    return pemPvKey.replace("-----BEGIN PRIVATE KEY-----", "").replace("-----END PRIVATE KEY-----", "").replace(/[\r\n]/gm, "");

}

function GetKeys() {
    const pvkey = localStorage.getItem("pvkey");
    const pbkey = localStorage.getItem("pbkey");

    return [pvkey, pbkey]
}

async function Decrypt(message) {
    const keys = GetKeys();
    let pemPvKey = keys[0];

    const pvkey = await window.crypto.subtle.importKey(
        "pkcs8",
        str2ab(window.atob(FormatPrivateKey(pemPvKey))),
        {
            name: "RSA-OAEP",
            hash: {name: "SHA-1"}
        },
        false,
        ["decrypt"]
    );

    return await window.crypto.subtle.decrypt(
        {
            name: "RSA-OAEP"
        },
        pvkey,
        str2ab(window.atob(message))
    );
}

async function GetResult(id, btn){
    btn = $(btn);
    let cipher = btn.data("hash");
    let pvkey = GetKeys()[0];
    let text = await Decrypt(cipher, pvkey);

    $(btn).replaceWith(ab2str(text));

    sessionStorage.setItem("resultId" , id);
    sessionStorage.setItem("resultText", ab2str(text));
}

$(document).ready(async () => {
    if (!localStorage.getItem("pvkey") || !localStorage.getItem("pbkey")){
        const keys = await GenerateKeys();
        StoreKeys(keys[0], keys[1]);
    }

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

        let percentage = 100 - ( newVal.length * 3 ) - ( uppercaseCount * 2 ) - ( digitsCount * 2 ) - ( specialcharsCount * 4 ) - ( type * 3 );
        if( oldVal.length - newVal.length < 0) $("#form-progress-bar").width(`${percentage}%`);
        else if ( oldVal.length - newVal.length > 0) $("#form-progress-bar").width(`${percentage}%`);

        pass.data("val", pass.val());
    });

    $("#btn-crack").click(() => {
        let pass = $("#input-password").val();
        let type = $(".btn-group-hash").find(".btn-hash-type.active")[0].id;
        let pbkey = GetKeys()[1];

        $.post("../../../scripts/add_password.php", {
            password: pass,
            type: type,
            pbkey: pbkey
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

    $("#admin-btn-settings").click(loadSettings);

    $("#admin-btn-sender").click(loadEmailSender);

    $("#btn-connexion").click(Connexion);

    loadTable();
    loadSettings();
    setInterval(loadTable, 5000);
});