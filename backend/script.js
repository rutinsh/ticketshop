var profileIcon = document.getElementById("profileIcon");
var popupForm = document.getElementById("popupForm");

// Pievieno klikšķa notikumu ikonai
profileIcon.addEventListener("click", function() {
  // Parāda vai paslēpj pop-up formu atkarībā no tā, vai tā ir redzama vai nē
  if (popupForm.style.display === "none") {
    popupForm.style.display = "block";
  } else {
    popupForm.style.display = "none";
  }
});