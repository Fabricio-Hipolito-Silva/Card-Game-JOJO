function initChartsOnModals(scope = document) {
    scope.querySelectorAll(".modal").forEach((modal) => {
        modal.addEventListener("shown.bs.modal", (event) => {
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
                                    backgroundColor: 'transparent',  // Fundo do radar
                                    min: 0,
                                    max: 6,
                                    grid: {
                                        circular: true,
                                        color: 'rgba(0,0,0,1)',// linhas do grid, se quiser ajustar
                                    },
                                    angleLines: {
                                        color: 'rgba(0,0,0,1)',
                                    },
                                    pointLabels:{
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
            console.log(window.rgb3);

                canvas.dataset.chartLoaded = "true";
            }
        });
    });
}
