<?php
require_once 'database.php';
require_once 'admin_header.php';

$jml_alternatif = $pdo->query("SELECT COUNT(*) FROM alternatif")->fetchColumn();
$jml_kriteria = $pdo->query("SELECT COUNT(*) FROM kriteria")->fetchColumn();
$jml_matriks = $pdo->query("SELECT COUNT(*) FROM nilai_matriks")->fetchColumn();
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
        <div>
            <p class="text-sm font-medium text-slate-500 mb-1">Total Alternatif</p>
            <h3 class="text-3xl font-extrabold text-slate-900"><?= $jml_alternatif ?></h3>
        </div>
        <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
        <div>
            <p class="text-sm font-medium text-slate-500 mb-1">Total Kriteria</p>
            <h3 class="text-3xl font-extrabold text-slate-900"><?= $jml_kriteria ?></h3>
        </div>
        <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
        <div>
            <p class="text-sm font-medium text-slate-500 mb-1">Data Matriks Tersimpan</p>
            <h3 class="text-3xl font-extrabold text-slate-900"><?= $jml_matriks ?></h3>
        </div>
        <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
        </div>
    </div>
</div>

<div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
    <div class="flex items-start gap-4">
        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-900 mb-2">Selamat Datang di Panel Admin!</h2>
            <p class="text-slate-600 leading-relaxed max-w-3xl">Gunakan menu navigasi di sebelah kiri untuk mengelola data alternatif (seperti pilihan metode pembayaran E-Wallet, QRIS, dsb), mengatur kriteria penilaian berserta bobotnya, dan menginput nilai matriks ke dalam sistem perhitungan TOPSIS.</p>
        </div>
    </div>
</div>

<?php require_once 'admin_footer.php'; ?>
