const data = {
  labels: ["Franchisé (96%)", "Driv'n Cook (4%)"],
  datasets: [
    {
      data: [$partFranchise, $partAdmin],
      backgroundColor: ["#4CAF50", "#FF9800"],
    },
  ],
};

const config = {
  type: "pie",
  data: data,
  options: {
    responsive: true,
  },
};

new Chart(document.getElementById("chartCA"), config);
const ctx = document.getElementById("chartCA").getContext("2d");

new Chart(ctx, {
  type: "pie",
  data: {
    labels: labels,
    datasets: [
      {
        data: data,
        backgroundColor: ["#3498db", "#e74c3c", "#2ecc71", "#f1c40f"],
      },
    ],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: "bottom",
      },
    },
  },
});
