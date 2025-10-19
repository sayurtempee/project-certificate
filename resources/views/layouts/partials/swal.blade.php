{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ====== FLASH MESSAGES ======
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: @json(session('success')),
            showConfirmButton: false
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: @json(session('error'))
        });
    @endif

    @if (session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: @json(session('warning'))
        });
    @endif

    @if (session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Info',
            text: @json(session('info'))
        });
    @endif

    @if (session('status'))
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: @json(session('status'))
        });
    @endif

    @if (session('message'))
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: '{{ session('message') }}',
            timer: 2500,
            showConfirmButton: false
        })
    @endif

    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validasi gagal',
            text: @json($errors->first())
        });
    @endif

    function confirmDelete(form) {
        // Cegah submit default
        event.preventDefault();

        Swal.fire({
            title: 'Konfirmasi',
            text: "Yakin mau hapus guru ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // submit form jika user klik "Ya"
            }
        });

        return false; // pastikan form tidak submit default
    }

    function confirmDeleteHistory(form) {
        event.preventDefault(); // cegah submit default

        Swal.fire({
            title: 'Konfirmasi Hapus Semua',
            text: "Yakin ingin menghapus semua activity logs? Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // submit form jika dikonfirmasi
            }
        });

        return false;
    }

    function confirmdownloadPdfHistory(form) {
        event.preventDefault(); // cegah submit default

        Swal.fire({
            title: 'Download PDF',
            text: "Yakin ingin mendownload activity logs dalam format PDF?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, download!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // submit form jika dikonfirmasi
            }
        });

        return false;
    }

    function confirmUpdate(form) {
        event.preventDefault(); // cegah submit default

        Swal.fire({
            title: 'Konfirmasi Update',
            text: "Yakin ingin mengupdate data guru ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, update!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // submit form jika dikonfirmasi
            }
        });

        return false;
    }

    function confirmCreate(form) {
        event.preventDefault(); // cegah submit default

        Swal.fire({
            title: 'Konfirmasi Create',
            text: "Yakin ingin menambahkan data guru ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, tambah!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // submit form jika dikonfirmasi
            }
        });

        return false;
    }

    // ====== GLOBAL CONFIRM HELPER ======
    // Pakai di tombol/anchor dengan attribute data-confirm, contoh:
    // <button data-confirm="Hapus data ini?" data-confirm-yes="submit:#deleteForm1">Hapus</button>
    document.addEventListener('click', function(e) {
        const el = e.target.closest('[data-confirm]');
        if (!el) return;

        e.preventDefault();
        const message = el.getAttribute('data-confirm') || 'Yakin melanjutkan?';
        const yesAction = el.getAttribute('data-confirm-yes'); // contoh "submit:#formId" atau "href:/link"
        const yesText = el.getAttribute('data-confirm-yes-text') || 'Ya';
        const noText = el.getAttribute('data-confirm-no-text') || 'Batal';

        Swal.fire({
            icon: 'question',
            title: 'Konfirmasi',
            text: message,
            showCancelButton: true,
            confirmButtonText: yesText,
            cancelButtonText: noText,
        }).then((res) => {
            if (!res.isConfirmed || !yesAction) return;

            // Aksi "submit:#formId"
            if (yesAction.startsWith('submit:')) {
                const sel = yesAction.replace('submit:', '').trim();
                const form = document.querySelector(sel);
                if (form) form.submit();
                return;
            }
            // Aksi "href:/some/url"
            if (yesAction.startsWith('href:')) {
                const url = yesAction.replace('href:', '').trim();
                window.location.href = url;
            }
        });
    });

    // ====== TOAST MIXIN (opsional) ======
    window.Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
    // Contoh pakai di halaman:
    // Toast.fire({icon: 'success', title: 'Data tersimpan'});
</script>
