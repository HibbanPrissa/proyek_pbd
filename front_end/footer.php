    </main>
</div>

<!-- Konfirmasi hapus -->
<div class="modal" id="modal-hapus">
    <div class="modal-kotak">
        <h2>Hapus data ini?</h2>
        <p>Tindakan ini tidak dapat dibatalkan.</p>
        <div class="modal-aksi">
            <button type="button" class="btn-sekunder" id="hapus-batal">Batal</button>
            <button type="button" class="btn-bahaya" id="hapus-ok">Hapus</button>
        </div>
    </div>
</div>

<script>
(function () {
    var modal = document.getElementById('modal-hapus');
    var target = null;

    function tutup() { modal.classList.remove('buka'); target = null; }

    document.querySelectorAll('form.form-hapus').forEach(function (f) {
        f.addEventListener('submit', function (ev) {
            ev.preventDefault();
            target = f;
            modal.classList.add('buka');
        });
    });

    document.getElementById('hapus-batal').addEventListener('click', tutup);
    document.getElementById('hapus-ok').addEventListener('click', function () {
        if (target) { target.submit(); }
    });
    modal.addEventListener('click', function (ev) { if (ev.target === modal) { tutup(); } });
    document.addEventListener('keydown', function (ev) { if (ev.key === 'Escape') { tutup(); } });
})();
</script>
</body>
</html>
