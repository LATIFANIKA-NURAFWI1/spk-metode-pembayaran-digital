<?php
require_once 'database.php';

// Handle Save
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nilai'])) {
    foreach ($_POST['nilai'] as $id_alt => $kriterias) {
        foreach ($kriterias as $id_krit => $val) {
            // Check if exists
            $stmt = $pdo->prepare("SELECT id FROM nilai_matriks WHERE id_alternatif=? AND id_kriteria=?");
            $stmt->execute([$id_alt, $id_krit]);
            if ($stmt->rowCount() > 0) {
                // Update
                $upd = $pdo->prepare("UPDATE nilai_matriks SET nilai=? WHERE id_alternatif=? AND id_kriteria=?");
                $upd->execute([$val, $id_alt, $id_krit]);
            } else {
                // Insert
                $ins = $pdo->prepare("INSERT INTO nilai_matriks (id_alternatif, id_kriteria, nilai) VALUES (?, ?, ?)");
                $ins->execute([$id_alt, $id_krit, $val]);
            }
        }
    }
    $success = "Matriks penilaian berhasil disimpan.";
}

$alternatifs = $pdo->query("SELECT * FROM alternatif ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
$kriterias = $pdo->query("SELECT * FROM kriteria ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);

// Ambil nilai existing
$matriks = [];
$stmt = $pdo->query("SELECT * FROM nilai_matriks");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $matriks[$row['id_alternatif']][$row['id_kriteria']] = $row['nilai'];
}

require_once 'admin_header.php';
?>

<?php if(isset($success)): ?>
<div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl mb-8 flex items-center shadow-sm">
    <svg class="w-6 h-6 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    <span class="font-medium"><?= $success ?></span>
</div>
<?php endif; ?>

<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
    <form method="POST">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 text-sm font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="py-4 px-6 min-w-[200px] border-r border-slate-200">Alternatif \ Kriteria</th>
                        <?php foreach($kriterias as $k): ?>
                            <th class="py-4 px-6 text-center" title="<?= $k['nama'] ?>">
                                <?= $k['kode'] ?>
                                <span class="block text-xs font-normal text-slate-400 normal-case mt-1 truncate w-24 mx-auto"><?= $k['nama'] ?></span>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach($alternatifs as $a): ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-4 px-6 font-semibold text-slate-800 border-r border-slate-100 bg-white">
                            <div class="flex items-center">
                                <span class="bg-slate-100 text-slate-500 text-xs px-2 py-1 rounded border border-slate-200 mr-2"><?= $a['kode'] ?></span>
                                <?= $a['nama'] ?>
                            </div>
                        </td>
                        <?php foreach($kriterias as $k): 
                            $val = $matriks[$a['id']][$k['id']] ?? '';
                        ?>
                        <td class="py-3 px-4">
                            <input type="number" step="0.01" name="nilai[<?= $a['id'] ?>][<?= $k['id'] ?>]" value="<?= $val ?>" required class="w-24 mx-auto block text-center border border-slate-300 px-3 py-2 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition font-mono bg-white shadow-sm">
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="p-6 bg-slate-50 border-t border-slate-200 flex justify-end">
            <button type="submit" class="bg-blue-600 text-white font-semibold px-8 py-3 rounded-xl hover:bg-blue-700 shadow-md shadow-blue-200 transition-all transform hover:-translate-y-0.5 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Simpan Matriks Penilaian
            </button>
        </div>
    </form>
</div>

<?php require_once 'admin_footer.php'; ?>
