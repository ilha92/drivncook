document.addEventListener("DOMContentLoaded", () => {
  const select = document.getElementById("produit_id");
  const prixInput = document.getElementById("prix_unitaire");

  select.addEventListener("change", () => {
    prixInput.value = select.options[select.selectedIndex].dataset.prix;
  });
});
