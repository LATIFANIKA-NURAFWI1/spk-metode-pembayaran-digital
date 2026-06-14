<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - SPK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800 flex h-screen overflow-hidden antialiased">

<!-- Sidebar -->
<aside class="w-64 bg-slate-900 text-slate-300 flex flex-col shadow-2xl z-20">
    <div class="p-6 border-b border-slate-800 flex items-center gap-3">
        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-lg">S</div>
        <div>
            <h2 class="text-lg font-bold text-white tracking-wide">Admin SPK</h2>
            <p class="text-slate-400 text-xs font-medium uppercase tracking-wider mt-0.5">Panel Kendali</p>
        </div>
    </div>
    
    <div class="px-4 py-6">
        <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">Menu Utama</p>
        <nav class="space-y-1.5">
            <a href="admin_dashboard.php" class="flex items-center px-4 py-3 rounded-xl transition-all <?= $current_page == 'admin_dashboard.php' ? 'bg-blue-600 text-white shadow-md shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="font-medium text-sm">Dashboard</span>
            </a>
            <a href="admin_alternatif.php" class="flex items-center px-4 py-3 rounded-xl transition-all <?= $current_page == 'admin_alternatif.php' ? 'bg-blue-600 text-white shadow-md shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <span class="font-medium text-sm">Data Alternatif</span>
            </a>
            <a href="admin_kriteria.php" class="flex items-center px-4 py-3 rounded-xl transition-all <?= $current_page == 'admin_kriteria.php' ? 'bg-blue-600 text-white shadow-md shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span class="font-medium text-sm">Data Kriteria</span>
            </a>
            <a href="admin_matriks.php" class="flex items-center px-4 py-3 rounded-xl transition-all <?= $current_page == 'admin_matriks.php' ? 'bg-blue-600 text-white shadow-md shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                <span class="font-medium text-sm">Matriks Penilaian</span>
            </a>
        </nav>
    </div>
    
    <div class="mt-auto p-4 border-t border-slate-800">
        <a href="index.php" target="_blank" class="flex items-center justify-center w-full bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white px-4 py-2.5 rounded-xl text-sm font-medium transition mb-2">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            Lihat Guest Web
        </a>
        <a href="logout.php" class="flex items-center justify-center w-full text-red-400 hover:text-red-300 hover:bg-red-900/20 px-4 py-2.5 rounded-xl text-sm font-medium transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            Logout
        </a>
    </div>
</aside>

<!-- Main Content -->
<div class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50">
    <header class="bg-white px-8 py-5 shadow-sm border-b border-slate-200 flex justify-between items-center z-10">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                <?php 
                if($current_page == 'admin_dashboard.php') echo 'Dashboard Overview';
                elseif($current_page == 'admin_alternatif.php') echo 'Manajemen Alternatif';
                elseif($current_page == 'admin_kriteria.php') echo 'Manajemen Kriteria';
                elseif($current_page == 'admin_matriks.php') echo 'Matriks Keputusan TOPSIS';
                ?>
            </h1>
            <p class="text-sm text-slate-500 mt-0.5">Sistem Pendukung Keputusan UMKM</p>
        </div>
        <div class="flex items-center gap-4 border-l border-slate-200 pl-6">
            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold border border-slate-200">
                A
            </div>
            <div class="hidden md:block text-right">
                <p class="text-sm font-semibold text-slate-900">Administrator</p>
                <p class="text-xs text-slate-500">Super Admin</p>
            </div>
        </div>
    </header>
    <main class="flex-1 overflow-y-auto p-8 relative">
