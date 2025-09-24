<!-- Konten utama berakhir di sini -->
        </div>
    </div>
    <!-- /#page-content-wrapper -->
</div>
<!-- /#wrapper -->

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<!-- Chart.js (akan digunakan di halaman sosiogram) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>


<!-- Custom JS -->
<script src="<?php echo $base_path; ?>/assets/js/script.js"></script>

<script>
// Script untuk toggle sidebar
document.addEventListener("DOMContentLoaded", function(event) {
    const sidebarToggle = document.body.querySelector("#sidebarToggle");
    if (sidebarToggle) {
        sidebarToggle.addEventListener("click", function(event) {
            event.preventDefault();
            document.body.classList.toggle("sb-sidenav-toggled");
            // Anda bisa menyimpan status toggle di localStorage jika ingin
            // localStorage.setItem("sb|sidebar-toggle", document.body.classList.contains("sb-sidenav-toggled"));
        });
    }
});
</script>

</body>
</html>
