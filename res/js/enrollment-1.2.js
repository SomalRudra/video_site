window.addEventListener("load", () => {
    // update enrollment auth and show delete button
    function configure() {
        const uid = this.dataset.uid;
        document.getElementById("config_id").value = uid;
        document.getElementById("remove_uid").value = uid;
        const tds = this.parentNode.parentNode.querySelectorAll("td");
        const known = tds[1].innerText;
        const last = tds[3].innerText;
        document.getElementById("configure_for").innerText = `For ${known} ${last}`;
        const auth = this.dataset.auth;
        document.getElementById("config_auth").value = auth;

        overlay.classList.add("visible");
        document.getElementById("configure_modal").classList.remove("hide");
    }
    const configs = document.querySelectorAll(".fa-gear");
    for (const config of configs) {
        config.addEventListener('click', configure);
    }
    document.getElementById('remove_icon').onclick = function() {
        if (confirm("Remove this enrollment?")) {
            document.forms["removeStudent"].submit();        }
    }


    // show upload class list modal
    const overlay = document.getElementById("overlay");
    document.getElementById("upload").addEventListener("click", () => {
        overlay.classList.add("visible");
        document.getElementById("upload_modal").classList.remove("hide");
    });

    // show enroll user modal
    document.getElementById("addUser").addEventListener("click", () => {
        overlay.classList.add("visible");
        document.getElementById("enroll_modal").classList.remove("hide");
        document.getElementById("emailField").focus();
    });

    // hide overlay and any/all modal(s)
    function hide() {
        overlay.classList.remove("visible");
        const modals = document.querySelectorAll(".modal");
        for (const modal of modals) {
            modal.classList.add("hide");
        }
    }
    document.getElementById("close-overlay").onclick = hide;
    document.getElementById("overlay").onclick = function (evt) {
        if (evt.target == this) {
            hide();
        }
    };

    // validate the upload form before submit
    document.getElementById("upload_form").onsubmit = () => {
        const file = document.getElementById("list_file");
        if (!file.value) {
            return false;
        }
    };
});
