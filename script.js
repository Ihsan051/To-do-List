const sidebarToggle = document.getElementById("sidebarToggle");
const sidebar = document.getElementById("sidebar");

// Toggle sidebar saat tombol diklik
sidebarToggle.addEventListener("click", function(e) {
  e.stopPropagation(); // Mencegah event bubbling agar tidak langsung menutup sidebar
  sidebar.classList.toggle("active");
});

// Event listener pada dokumen untuk menutup sidebar jika di klik di luar area sidebar
document.addEventListener("click", function(e) {
  // Hanya jalankan auto close pada layar kecil
  if(window.innerWidth <= 768) {
    if(sidebar.classList.contains("active") && 
       !sidebar.contains(e.target) && 
       !sidebarToggle.contains(e.target)
    ) {
      sidebar.classList.remove("active");
    }
  }
});

// Mencegah klik di dalam sidebar agar tidak menutup sidebar
sidebar.addEventListener("click", function(e) {
  e.stopPropagation();
});

// mengambil elemen tambah tugas
const tambahTugas = document.getElementById("tambahTugas");

// event tambah tugas 
tambahTugas.addEventListener('click' , function(){
    document.getElementById("inputTugas").classList.toggle('show')
})

// mengambil elemen Tambah project
const tambahProject = document.getElementById("tambahProject");
const inputProject = document.getElementById("inputProject");

// event tambah project
tambahProject.addEventListener("click", function(){
  inputProject.classList.toggle("tambahProject")
})

document.addEventListener("DOMContentLoaded", function () {
  const editButtons = document.querySelectorAll(".btn-warning"); // Tombol Edit
  const editModal = new bootstrap.Modal(document.getElementById("editModal")); // Modal Bootstrap
  const editForm = document.getElementById("editTaskForm");

  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      // Dapatkan elemen tugas yang diklik
      const card = this.closest(".card-body");
      const title = card.querySelector(".card-title").textContent;
      const description = card.querySelector(".card-subtitle").textContent;
      const dueDate = card.querySelector("p:first-child").textContent;
      const priority = card.querySelector("p:nth-child(2)").textContent;

      // Isi modal dengan data tugas
      document.getElementById("editTitle").value = title;
      document.getElementById("editDescription").value = description;
      document.getElementById("editDueDate").value = convertDateFormat(dueDate);
      document.getElementById("editPriority").value = priority;

      // Tampilkan modal
      editModal.show();
    });
  });

  // Fungsi untuk mengubah format tanggal dari "10 Februari 2025" ke "2025-02-10"
  function convertDateFormat(dateStr) {
    const months = {
      Januari: "01", Februari: "02", Maret: "03", April: "04", Mei: "05",
      Juni: "06", Juli: "07", Agustus: "08", September: "09", Oktober: "10",
      November: "11", Desember: "12"
    };
    
    const parts = dateStr.split(" ");
    if (parts.length === 3) {
      return `${parts[2]}-${months[parts[1]]}-${parts[0].padStart(2, "0")}`;
    }
    return "";
  }

  // Tangani form submit untuk edit tugas (opsional)
  editForm.addEventListener("submit", function (event) {
    event.preventDefault();
    alert("Perubahan tugas telah disimpan!"); // Gantilah dengan kode penyimpanan ke database atau backend
    editModal.hide();
  });
});
