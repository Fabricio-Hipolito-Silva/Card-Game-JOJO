document.addEventListener("DOMContentLoaded", function () {
    function ativar_editar() {
        document.querySelectorAll('.tab-pane').forEach(function(pane) {
            pane.classList.add('modo-edicao');
        });

    }

    function desativar_editar() {
        document.querySelectorAll('.tab-pane').forEach(function(pane) {
            pane.classList.remove('modo-edicao');
        });
        
    }
function carregarAbas() {
    const partes = ["p3", "p4", "p5", "p6", "p7", "p8", "p9"];
    partes.forEach(parte => {
        const divContPartes = document.getElementById(parte);
        divContPartes.innerHTML = "";
        divContPartes.removeAttribute("data-loaded");
        fetch("../../php/verificar_stands_existentes/alterar_stands_existentes.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "parte=" + encodeURIComponent(parte)
        })
        .then(response => response.text())
        .then(html => {
            divContPartes.innerHTML = html;
            divContPartes.setAttribute("data-loaded", "true");
            initChartsOnModals(divContPartes);
            initGradientOnModals(divContPartes);
        })
        .catch(error => {
            divContPartes.innerHTML = `<div class="text-danger">Erro ao carregar: ${error.message}</div>`;
        });
    });
}

function VoltarAbas() {
    const partes = ["p3", "p4", "p5", "p6", "p7", "p8", "p9"];
    partes.forEach(parte => {
        const divContPartes = document.getElementById(parte);

        // Limpa o conteúdo e remove o atributo
        divContPartes.innerHTML = "";
        divContPartes.removeAttribute("data-loaded");

        // Faz a mesma requisição usada no carregamento inicial
        fetch("../../php/verificar_stands_existentes/verificar_stands_existentes.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "parte=" + encodeURIComponent(parte)
        })
        .then(response => response.text())
        .then(html => {
            divContPartes.innerHTML = html;
            divContPartes.setAttribute("data-loaded", "true");
            initChartsOnModals(divContPartes);
            initGradientOnModals(divContPartes);
        })
        .catch(error => {
            divContPartes.innerHTML = `<div class="text-danger">Erro ao carregar: ${error.message}</div>`;
        });
    });
}
    
    let modo_edicao = false;
    const editar_btn = document.getElementById("editar");
    editar_btn.addEventListener('click', function () {
        modo_edicao = !modo_edicao;
        if (modo_edicao) {
            ativar_editar();
            carregarAbas();
            editar_btn.innerHTML = "<i class='fa-solid fa-pen-to-square'></i> Parar de Editar";
        } else {
            desativar_editar();
            VoltarAbas();
            editar_btn.innerHTML = "<i class='fa-solid fa-pen-to-square'></i> Editar Cartas";
        }
    });
});
