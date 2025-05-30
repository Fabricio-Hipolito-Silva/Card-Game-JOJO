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
        const palette = colorThief.getPalette(img, 3); // Pega 3 cores
        const [color1, color2, color3] = palette;
        const rgb1 = `rgb(${color1[0]}, ${color1[1]}, ${color1[2]})`;
        const rgb2 = `rgb(${color2[0]}, ${color2[1]}, ${color2[2]})`;
        const rgb3 = `rgb(${color3[0]}, ${color3[1]}, ${color3[2]})`;
        window.rgb3 = rgb3;  // agora ela está acessível globalmente   

        const modalContent = modal.querySelector(".carta-modal");
        if (modalContent) {
            modalContent.style.background = `linear-gradient(to bottom right, ${rgb1}, ${rgb2})`;
        }
    } catch (e) {
        console.error("Erro ao aplicar gradiente:", e);
    }
}

function initChartsOnModals(scope = document) {
    scope.querySelectorAll(".modal").forEach((modal) => {
        modal.addEventListener("shown.bs.modal", (event) => {
            // Aguarda até a variável global estar pronta
            const checkRgb3 = setInterval(() => {
                if (window.rgb3) {
                    clearInterval(checkRgb3); // Para o intervalo

                    const canvas = modal.querySelector("canvas");
                    if (canvas && !canvas.dataset.chartLoaded) {
                        const ctx = canvas.getContext("2d");

                        const data = [
                            parseInt(canvas.dataset.poder),
                            parseInt(canvas.dataset.velocidade),
                            parseInt(canvas.dataset.alcance),
                            parseInt(canvas.dataset.persistencia),
                            parseInt(canvas.dataset.precisao),
                            parseInt(canvas.dataset.potencial)
                        ];

                        new Chart(ctx, {
                            type: "radar",
                            data: {
                                labels: [
                                    "Poder Destrutivo",
                                    "Velocidade",
                                    "Alcance",
                                    "Persistência",
                                    "Precisão",
                                    "Potencial"
                                ],
                                datasets: [{
                                    data: data,
                                    backgroundColor: "rgba(167, 67, 233, 0.4)",
                                    borderColor: "#121005",
                                    borderWidth: 2,
                                    pointBackgroundColor: "#121005",
                                    pointRadius: 1,
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    r: {
                                        backgroundColor: 'transparent',
                                        min: 0,
                                        max: 6,
                                        grid: {
                                            circular: true,
                                            color: 'rgba(0,0,0,1)',
                                        },
                                        angleLines: {
                                            color: 'rgba(0,0,0,1)',
                                        },
                                        ticks: {
                                            stepSize: 1,
                                            callback: function (value) {
                                                const letras = {0: "Null",1: "E", 2: "D", 3: "C", 4: "B", 5: "A", 6: "∞"};
                                                return letras[value] || "";
                                            }
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                }
                            }
                        });

                        canvas.dataset.chartLoaded = "true";
                    }
                }
            }, 50); // Verifica a cada 50ms se rgb3 foi carregado
        });
    });
}
