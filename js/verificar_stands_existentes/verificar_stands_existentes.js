document.addEventListener("DOMContentLoaded", function () {
    const partes = ["p3", "p4", "p5", "p6", "p7", "p8", "p9"];
    partes.forEach(parte => {
        const tab = document.getElementById(parte+"-tab");
            tab.addEventListener("shown.bs.tab", function () {
                const divContPartes = document.getElementById(parte);
                if(divContPartes.getAttribute("data-loaded") === "true") return;
                fetch("../../php/verificar_stands_existentes/verificar_stands_existentes.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "parte=" + encodeURIComponent(parte)
                })
                .then(response => response.text())
                .then(html => {
                    divContPartes.innerHTML = html;
                    divContPartes.setAttribute("data-loaded", "true");
                })
                .catch(error => {
                    divContPartes.innerHTML = `<div class="text-danger">Erro ao carregar: ${error.message}</div>`
                })
            })
        });
            document.getElementById("p3-tab").dispatchEvent(new Event("shown.bs.tab"));
    });