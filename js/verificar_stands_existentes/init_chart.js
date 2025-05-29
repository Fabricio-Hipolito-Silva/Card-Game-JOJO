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
                                min: 0,
                                max: 6,
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
        });
    });
}
