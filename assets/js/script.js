// GRAFIK DASHBOARD
document.addEventListener("DOMContentLoaded", function () {
  const chartCanvas = document.getElementById("produkTerlarisChart");
  if (chartCanvas) {
    const labels = JSON.parse(chartCanvas.getAttribute("data-labels"));
    const dataValues = JSON.parse(chartCanvas.getAttribute("data-values"));

    new Chart(chartCanvas, {
      type: "bar",
      data: {
        labels: labels,
        datasets: [
          {
            label: "Jumlah Terjual",
            backgroundColor: "#4e73df",
            hoverBackgroundColor: "#2e59d9",
            borderColor: "#4e73df",
            data: dataValues,
          },
        ],
      },
      options: {
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
              callback: function (value) {
                if (value % 1 === 0) {
                  return value;
                }
              },
            },
          },
        },
        plugins: {
          legend: {
            display: false,
          },
        },
      },
    });
  }
});
