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
            timer: 2000,
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

    function confirmDownloadPDF(a) {
        event.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Download',
            text: "Yakin ingin mendownload pdf ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Download!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = a.href;
            }
        });
        return false;
    }

    function confirmSimpanInline(button) {
        event.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Merubah Murid',
            text: "Yakin ingin merubah murid ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Update!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                button.submit();
            }
        });
        return false;
    }

    function confirmExportCsv(a) {
        event.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Download Sample CSV',
            text: "Yakin ingin mendownload sample csv ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#FBC02D',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Download!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = a.href;
            }
        });
        return false;
    }

    function confirmDeletePhotoTeacher(form) {
        event.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Hapus Foto Profile',
            text: "Yakin ingin menghapus foto profile ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Download!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }

    function confirmMuridSatuan(form) {
        event.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Menambahkan Murid',
            text: "Yakin ingin menambahkan murid ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Tambahkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }

    function confirmTanggalLulus(form) {
        event.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Tanggal Kelulusan',
            text: "Yakin ingin menerapkan tanggal ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Terapkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }

    function confirmTempatKelulusan(form) {
        event.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Tempat Kelulusan',
            text: "Yakin ingin menerapkan tempat kelulusan ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Terapkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }

    function confirmNamaKepsek(form) {
        event.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Nama Kepala Sekolah',
            text: "Yakin ingin menerapkan nama kepala sekolah ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Terapkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }

    function confirmNipKepsek(form) {
        event.preventDefault();
        Swal.fire({
            title: 'Konfirmasi NIP Kepala Sekolah',
            text: "Yakin ingin menerapkan nip kepala sekolah ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Terapkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }

    function confirmDownloadCertificate(a) {
        event.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Download Certificate',
            text: "Yakin ingin mendownload certificate ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Download!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = a.href
            }
        });
        return false;
    }

    {{--  Alert untuk Logout/logo khusus  --}}
    function confirmLogout(form) {
        event.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Keluar Website',
            text: 'Yakin ingin keluar dari website ini?',
            icon: 'warning',
            // gunakan iconHtml supaya logo muncul langsung dari SweetAlert (tanpa imageUrl)
            iconHtml: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="64" height="64" aria-hidden="true">
                <circle cx="12" cy="12" r="12" fill="#ef4444"/>
                <path d="M10 8v3H2v2h8v3l5-4-5-4z" fill="#ffffff"/>
            </svg>`,
            showCancelButton: true,
            confirmButtonColor: '#ef4444', // warna tombol konfirmasi (merah)
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Keluar!',
            cancelButtonText: 'Batal',
            customClass: {
                icon: 'swal2-icon-logout' // opsional bila mau styling tambahan via CSS
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    {{--  Alert untuk update data guru  --}}
    document.addEventListener('DOMContentLoaded', function() {
        const saveButtonTeacher = document.getElementById('saveButtonTeacher');
        if (!saveButtonTeacher) return;

        saveButtonTeacher.addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Apakah Anda yakin ingin menyimpan perubahan data guru ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563EB',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    saveButtonTeacher.closest('form').submit();
                }
            });
        });
    });

    {{--  Alert untuk update data admin  --}}
    document.addEventListener('DOMContentLoaded', function() {
        const saveButtonAdmin = document.getElementById('saveButtonAdmin');
        if (!saveButtonAdmin) return;

        saveButtonAdmin.addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Apakah Anda yakin ingin menyimpan perubahan data admin ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563EB',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (event.isConfirmed) {
                    saveButtonAdmin.closest('form').submit();
                }
            });
        });
    });

    {{--  CSV Alert  --}}
    document.addEventListener('DOMContentLoaded', () => {
        const csvInput = document.getElementById('csvFileInput');
        const uploadLabel = document.getElementById('csvUploadLabel');
        const csvForm = document.getElementById('csvForm');

        // Ketika tombol upload diklik
        uploadLabel.addEventListener('click', (e) => {
            e.preventDefault();

            Swal.fire({
                title: 'Konfirmasi Upload Data Murid',
                text: "Yakin ingin mengupload data murid?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Pilih File!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    csvInput.click();
                }
            });
        });

        // Setelah user memilih file
        csvInput.addEventListener('change', function() {
            if (!this.files.length) return;

            Swal.fire({
                title: 'Konfirmasi Kirim File',
                text: `Upload file "${this.files[0].name}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Upload!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    csvForm.submit();
                } else {
                    this.value = '';
                }
            });
        });
    });

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
