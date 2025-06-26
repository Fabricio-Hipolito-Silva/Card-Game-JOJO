function carregarAbas() {
const partes = ["p3", "p4", "p5", "p6", "p7", "p8", "p9"];
partes.forEach(parte => {
    const divContPartes = document.getElementById(parte);
    if (divContPartes.getAttribute("data-loaded") === "true") {
        divContPartes.setAttribute("data-loaded", false);
    }
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
        divContPartes.innerHTML = `<div class="text-danger">Erro ao carregar: ${error.message}</div>`
    })
});
}