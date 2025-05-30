function initGradientOnModals(scope = document) {
    scope.querySelectorAll(".modal").forEach(modal => {
        modal.addEventListener("shown.bs.modal", event => {
            const img = modal.querySelector("img");
            if (!img) return;

            const colorThief = new ColorThief();

            if (!img.complete) {
                img.addEventListener("load", () => aplicarGradiente(modal, img, colorThief));
            } else {
                aplicarGradiente(modal, img, colorThief);
            }
        });
    });
}

function aplicarGradiente(modal, img, colorThief) {
    try {
        const palette = colorThief.getPalette(img, 2);
        const [color1, color2] = palette;
        const rgb1 = `rgb(${color1[0]}, ${color1[1]}, ${color1[2]})`;
        const rgb2 = `rgb(${color2[0]}, ${color2[1]}, ${color2[2]})`;
        const modalContent = modal.querySelector(".carta-modal");
        if (modalContent) {
            modalContent.style.background = `linear-gradient(to bottom right, ${rgb1}, ${rgb2})`;
        }
    } catch (e) {
        console.error("Erro ao aplicar gradiente:", e);
    }
}
