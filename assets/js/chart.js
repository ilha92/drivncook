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
