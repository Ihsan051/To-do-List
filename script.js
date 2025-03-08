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

// mengambil elemen Tambah kategori
const tambahKategori = document.getElementById("tambahKategori");
const inputKategori = document.getElementById("inputKategori");

// event tambah kategori
tambahKategori.addEventListener("click", function(){
  inputKategori.classList.toggle("inputKategori")
})

