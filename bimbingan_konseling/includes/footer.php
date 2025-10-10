<?php
// File: includes/footer.php (Unified Footer)
?>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <!-- Konten Admin Berakhir Di Sini -->
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->
<?php else: // Pengguna adalah 'siswa' atau role lain ?>
        <!-- Konten Siswa Berakhir Di Sini -->
    </div> <!-- End of .container -->
<?php endif; ?>


<!-- Pustaka JavaScript Pihak Ketiga -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<!-- Custom JS dengan Cache Busting -->
<script src="<?php echo BASE_URL; ?>/assets/js/script.js?v=<?php echo time(); ?>"></script>

</body>
</html>