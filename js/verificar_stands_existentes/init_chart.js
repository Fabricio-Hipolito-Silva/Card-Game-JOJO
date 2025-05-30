function initChartsOnModals(scope = document) {
    scope.querySelectorAll(".modal").forEach((modal) => {
        modal.addEventListener("shown.bs.modal", (event) => {
            const checkRgb3 = setInterval(() => {
                if (window.rgb3) {
                    clearInterval(checkRgb3);

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
                                    backgroundColor: window.rgb3,
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
                                            color:'rgba(0,0,0,1)',
                                            stepSize: 1,
                                            callback: function (value) {
                                                const letras = {0: "Null",1: "E", 2: "D", 3: "C", 4: "B", 5: "A", 6: "∞"};
                                                return letras[value] || "";
                                            }
                                        },
                                        pointLabels:{
                                            color:'rgba(0,0,0,1)'
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
