document.addEventListener("DOMContentLoaded", function () {
  const ctx = document.getElementById("chartCA").getContext("2d");

  const data = {
    labels: chartLabels, // venant du PHP
    datasets: [
      {
        label: "Chiffre d'affaires",
        data: chartData,
        backgroundColor: [
          "#4CAF50",
          "#2196F3",
          "#FFC107",
          "#E91E63",
          "#9C27B0",
        ],
      },
    ],
  };

  const total = data.datasets[0].data.reduce((a, b) => a + b, 0);

  new Chart(ctx, {
    type: "pie",
    data: data,
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: "bottom",
        },
        datalabels: {
          color: "#fff",
          font: {
            weight: "bold",
            size: 16,
          },
          formatter: (value) => {
            let percent = ((value / total) * 100).toFixed(1);
            return percent + " %";
          },
        },
      },
    },
    plugins: [ChartDataLabels],
  });
});
// Export PDF
function exportPDF() {
  const { jsPDF } = window.jspdf;

  html2canvas(document.getElementById("chartCA")).then((canvas) => {
    const pdf = new jsPDF();
    const imgData = canvas.toDataURL("image/png");

    const width = pdf.internal.pageSize.getWidth();
    const height = (canvas.height * width) / canvas.width;

    pdf.text("Rapport des ventes - Driv'n Cook", 10, 15);
    pdf.addImage(imgData, "PNG", 10, 25, width - 20, height);
    pdf.save("rapport_ventes.pdf");
  });
}
