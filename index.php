<?php
require_once 'database.php';
$stmt = $pdo->query("SELECT * FROM kriteria ORDER BY id");
$kriterias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Pembayaran Digital UMKM - TOPSIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-text {
            background: linear-gradient(90deg, #2563EB, #8B5CF6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

<nav class="bg-white/80 backdrop-blur-md shadow-sm border-b border-slate-200 sticky top-0 z-50 transition-all">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center group cursor-pointer">
                <div class="w-8 h-8 mr-3 transform group-hover:scale-110 transition duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="100%" height="100%" fill="none" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        <path d="M3 9h18"></path>
                        <path d="M8 9v3"></path>
                        <path d="M16 9v3"></path>
                        <path d="M12 9v3"></path>
                    </svg>
                </div>
                <span class="font-bold text-xl text-slate-900 group-hover:text-blue-600 transition">UMKM SmartPay</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="#kalkulator" class="text-sm font-medium text-slate-600 hover:text-slate-900">Simulasi</a>
                <a href="login.php" class="text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-5 py-2.5 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all">Admin Panel</a>
            </div>
        </div>
    </div>
</nav>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Hero Section -->
    <div class="text-center max-w-3xl mx-auto mb-16">
        <div class="inline-flex items-center justify-center p-2 bg-blue-50 rounded-full mb-4 ring-1 ring-blue-100">
            <span class="px-3 py-1 bg-blue-600 text-white text-xs font-bold rounded-full mr-2">BARU</span>
            <span class="text-sm text-blue-800 pr-2 font-medium">Algoritma TOPSIS Terintegrasi</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-6 leading-tight">
            Pilih Metode Pembayaran <br/><span class="gradient-text">Terbaik untuk Bisnismu</span>
        </h1>
        <p class="text-lg text-slate-600 mb-8 leading-relaxed">
            Sistem Pendukung Keputusan (SPK) cerdas ini membantu pelaku UMKM menganalisis dan menemukan platform pembayaran digital paling ideal berdasarkan efisiensi biaya, kemudahan, dan keamanan.
        </p>
        
        <div class="flex justify-center gap-6">
            <div class="flex items-center text-slate-600 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100">
                <svg class="w-5 h-5 mr-2 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="M9 12l2 2 4-4"></path></svg>
                <span class="text-sm font-medium">Aman & Terpercaya</span>
            </div>
            <div class="flex items-center text-slate-600 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100">
                <svg class="w-5 h-5 mr-2 text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4"></path><path d="M4 6v12a2 2 0 0 0 2 2h14v-4"></path><path d="M18 12a2 2 0 0 0-2 2c0 1.1.9 2 2 2h4v-4h-4z"></path></svg>
                <span class="text-sm font-medium">Beragam Pilihan E-Wallet</span>
            </div>
        </div>
    </div>

    <!-- Main App Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8" id="kalkulator">
        
        <!-- Sidebar Kalkulator -->
        <div class="lg:col-span-4">
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 sticky top-24">
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-slate-900 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-3 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        Simulasi Bobot
                    </h2>
                    <p class="text-sm text-slate-500 mt-2">Sesuaikan prioritas bisnis Anda dengan menggeser slider kriteria di bawah ini.</p>
                </div>
                
                <form id="weightForm" class="space-y-5">
                    <?php foreach($kriterias as $k): ?>
                    <div class="group">
                        <label class="block text-sm font-semibold text-slate-700 group-hover:text-blue-600 transition mb-2">
                            <?= $k['nama'] ?> <span class="text-xs text-slate-400 font-normal ml-1">(<?= $k['kode'] ?>)</span>
                        </label>
                        <input type="number" id="input_<?= $k['id'] ?>" data-id="<?= $k['id'] ?>" step="0.01" value="<?= $k['bobot'] ?>" class="w-full border border-slate-300 px-4 py-3 rounded-xl focus:ring-2 focus:ring-blue-600 outline-none transition font-mono font-medium text-slate-700 bg-slate-50 focus:bg-white weight-input">
                    </div>
                    <?php endforeach; ?>
                    
                    <div class="pt-6 border-t border-slate-100 mt-6">
                        <button type="button" id="btnReset" class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-slate-50 text-slate-700 font-semibold rounded-xl border border-slate-200 hover:bg-slate-100 hover:text-slate-900 transition-all focus:ring-4 focus:ring-slate-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Reset ke Nilai Default
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Visualisasi -->
        <div class="lg:col-span-8 space-y-8">
            <!-- Chart Card -->
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-3 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                            Visualisasi Nilai Preferensi
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">Grafik perbandingan nilai kedekatan relatif (V) untuk setiap metode pembayaran.</p>
                    </div>
                </div>
                <div class="relative h-96 w-full">
                    <canvas id="topsisChart"></canvas>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-xl font-bold text-slate-900">Rekomendasi Peringkat Akhir</h2>
                    <p class="text-sm text-slate-500 mt-1">Alternatif dengan peringkat 1 adalah rekomendasi terbaik saat ini.</p>
                </div>
                <div class="p-0">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-sm text-slate-500 font-semibold uppercase tracking-wider">
                                <th class="py-4 px-8 border-b border-slate-200 w-24">Rank</th>
                                <th class="py-4 px-8 border-b border-slate-200">Metode Pembayaran</th>
                                <th class="py-4 px-8 border-b border-slate-200 text-right w-48">Nilai Preferensi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody" class="text-slate-700 divide-y divide-slate-100">
                            <!-- Diisi oleh JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="border-t border-slate-200 bg-white mt-20 py-8">
    <div class="max-w-7xl mx-auto px-4 text-center text-sm text-slate-500">
        &copy; <?= date('Y') ?> Sistem Pendukung Keputusan UMKM. Powered by TOPSIS Algorithm.
    </div>
</footer>

<script>
    let myChart = null;
    const defaultWeights = {};
    <?php foreach($kriterias as $k): ?>
        defaultWeights[<?= $k['id'] ?>] = <?= $k['bobot'] ?>;
    <?php endforeach; ?>

    function getWeights() {
        const weights = {};
        document.querySelectorAll('.weight-input').forEach(input => {
            weights[input.dataset.id] = parseFloat(input.value) || 0;
        });
        return weights;
    }

    function fetchTopsis(weights = null) {
        let payload = {};
        if (weights) {
            payload = { weights: weights };
        }

        fetch('api_topsis.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            updateUI(data);
        });
    }

    function updateUI(data) {
        // Update Table
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';
        data.forEach(row => {
            let tr = document.createElement('tr');
            tr.className = "hover:bg-slate-50/80 transition duration-150 group";
            
            let isWinner = row.rank === 1;
            let rankBadge = isWinner 
                ? `<span class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full font-bold shadow-md shadow-blue-200">1</span>` 
                : `<span class="inline-flex items-center justify-center w-8 h-8 bg-slate-100 text-slate-500 rounded-full font-semibold group-hover:bg-slate-200">${row.rank}</span>`;
            
            let highlightClass = isWinner ? "font-bold text-slate-900" : "font-medium text-slate-700";
            
            tr.innerHTML = `
                <td class="py-5 px-8">${rankBadge}</td>
                <td class="py-5 px-8 ${highlightClass}">
                    <div class="flex items-center">
                        ${isWinner ? '<span class="mr-2 text-amber-400"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg></span>' : ''}
                        ${row.nama} 
                        <span class="text-xs text-slate-400 ml-2 border border-slate-200 px-2 py-0.5 rounded-full">${row.kode}</span>
                    </div>
                </td>
                <td class="py-5 px-8 text-right font-mono text-base ${isWinner ? 'text-blue-600 font-bold' : 'text-slate-600 font-medium'}">${row.nilai.toFixed(4)}</td>
            `;
            tbody.appendChild(tr);
        });

        // Update Chart
        const labels = data.map(d => d.nama);
        const values = data.map(d => d.nilai);

        if (myChart) {
            myChart.data.labels = labels;
            myChart.data.datasets[0].data = values;
            
            // Dynamic color: winner gets blue, others get slate
            let bgColors = data.map(d => d.rank === 1 ? 'rgba(37, 99, 235, 0.9)' : 'rgba(148, 163, 184, 0.5)');
            let borderColors = data.map(d => d.rank === 1 ? 'rgba(37, 99, 235, 1)' : 'rgba(148, 163, 184, 0.8)');
            
            myChart.data.datasets[0].backgroundColor = bgColors;
            myChart.data.datasets[0].borderColor = borderColors;
            
            myChart.update();
        } else {
            const ctx = document.getElementById('topsisChart').getContext('2d');
            
            let bgColors = data.map(d => d.rank === 1 ? 'rgba(37, 99, 235, 0.9)' : 'rgba(148, 163, 184, 0.5)');
            let borderColors = data.map(d => d.rank === 1 ? 'rgba(37, 99, 235, 1)' : 'rgba(148, 163, 184, 0.8)');

            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Nilai Preferensi (V)',
                        data: values,
                        backgroundColor: bgColors,
                        borderColor: borderColors,
                        borderWidth: 1,
                        borderRadius: 8,
                        barPercentage: 0.5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 800,
                        easing: 'easeOutQuart'
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 1.1,
                            grid: { 
                                color: '#f1f5f9',
                                drawBorder: false
                            },
                            ticks: {
                                font: { family: "'Inter', sans-serif" },
                                color: '#64748b'
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { family: "'Inter', sans-serif", weight: '500' },
                                color: '#475569'
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            titleFont: { family: "'Inter', sans-serif", size: 14 },
                            bodyFont: { family: "'Inter', sans-serif", size: 14, weight: 'bold' },
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return ' Nilai: ' + context.parsed.y.toFixed(4);
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // Event Listeners for inputs
    document.querySelectorAll('.weight-input').forEach(input => {
        input.addEventListener('input', function() {
            fetchTopsis(getWeights());
        });
    });

    document.getElementById('btnReset').addEventListener('click', function() {
        document.querySelectorAll('.weight-input').forEach(input => {
            input.value = defaultWeights[input.dataset.id];
        });
        fetchTopsis(null);
    });

    // Initial fetch
    fetchTopsis(null);
</script>

</body>
</html>
