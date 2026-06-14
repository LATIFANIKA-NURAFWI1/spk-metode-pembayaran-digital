<?php
require_once 'database.php';

// Handle CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            $stmt = $pdo->prepare("INSERT INTO kriteria (kode, nama, tipe, bobot) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_POST['kode'], $_POST['nama'], $_POST['tipe'], $_POST['bobot']]);
        } elseif ($_POST['action'] == 'edit') {
            $stmt = $pdo->prepare("UPDATE kriteria SET kode=?, nama=?, tipe=?, bobot=? WHERE id=?");
            $stmt->execute([$_POST['kode'], $_POST['nama'], $_POST['tipe'], $_POST['bobot'], $_POST['id']]);
        } elseif ($_POST['action'] == 'delete') {
            $stmt = $pdo->prepare("DELETE FROM kriteria WHERE id=?");
            $stmt->execute([$_POST['id']]);
        }
        header("Location: admin_kriteria.php");
        exit;
    }
}

$kriterias = $pdo->query("SELECT * FROM kriteria ORDER BY kode")->fetchAll(PDO::FETCH_ASSOC);

require_once 'admin_header.php';
?>

<div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm mb-8">
    <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center">
        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        Tambah Kriteria Baru
    </h2>
    <form method="POST" class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <input type="hidden" name="action" value="add">
        <div class="md:col-span-2">
            <input type="text" name="kode" placeholder="Kode (C1)" required class="w-full border border-slate-300 px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-blue-600 outline-none">
        </div>
        <div class="md:col-span-4">
            <input type="text" name="nama" placeholder="Nama Kriteria" required class="w-full border border-slate-300 px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-blue-600 outline-none">
        </div>
        <div class="md:col-span-2">
            <select name="tipe" class="w-full border border-slate-300 px-4 py-2.5 rounded-xl bg-white focus:ring-2 focus:ring-blue-600 outline-none">
                <option value="Benefit">Benefit</option>
                <option value="Cost">Cost</option>
            </select>
        </div>
        <div class="md:col-span-2">
            <input type="number" step="0.01" name="bobot" placeholder="Bobot (ex: 0.25)" required class="w-full border border-slate-300 px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-blue-600 outline-none">
        </div>
        <div class="md:col-span-2">
            <button type="submit" class="w-full bg-blue-600 text-white font-semibold px-4 py-2.5 rounded-xl hover:bg-blue-700 shadow-md transition">Simpan</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 text-sm font-semibold uppercase tracking-wider">
            <tr>
                <th class="py-4 px-6 w-20">ID</th>
                <th class="py-4 px-6 w-24">Kode</th>
                <th class="py-4 px-6">Nama Kriteria</th>
                <th class="py-4 px-6 w-32">Tipe</th>
                <th class="py-4 px-6 w-28">Bobot</th>
                <th class="py-4 px-6 w-32 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach($kriterias as $k): ?>
            <tr class="hover:bg-slate-50 transition">
                <td class="py-4 px-6 text-slate-500">#<?= $k['id'] ?></td>
                <td class="py-4 px-6 font-medium text-slate-800"><span class="bg-slate-100 px-2 py-1 rounded-md border border-slate-200"><?= htmlspecialchars($k['kode']) ?></span></td>
                <td class="py-4 px-6 font-medium text-slate-900"><?= htmlspecialchars($k['nama']) ?></td>
                <td class="py-4 px-6">
                    <?php if($k['tipe'] == 'Benefit'): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">Benefit</span>
                    <?php else: ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">Cost</span>
                    <?php endif; ?>
                </td>
                <td class="py-4 px-6 font-mono font-bold text-blue-600"><?= $k['bobot'] ?></td>
                <td class="py-4 px-6 flex justify-center gap-2">
                    <button onclick="editKrit(<?= $k['id'] ?>, '<?= $k['kode'] ?>', '<?= $k['nama'] ?>', '<?= $k['tipe'] ?>', <?= $k['bobot'] ?>)" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Edit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </button>
                    <form method="POST" class="inline" onsubmit="return confirm('Hapus kriteria ini?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $k['id'] ?>">
                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Edit -->
<div id="editModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-50 transition-opacity">
    <div class="bg-white p-8 rounded-3xl w-full max-w-md shadow-2xl transform transition-transform scale-95" id="modalContent">
        <h2 class="text-xl font-bold text-slate-900 mb-6">Edit Kriteria</h2>
        <form method="POST" class="space-y-4">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kode</label>
                <input type="text" name="kode" id="edit_kode" required class="w-full border border-slate-300 px-4 py-2.5 rounded-xl outline-none focus:ring-2 focus:ring-blue-600">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Kriteria</label>
                <input type="text" name="nama" id="edit_nama" required class="w-full border border-slate-300 px-4 py-2.5 rounded-xl outline-none focus:ring-2 focus:ring-blue-600">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Tipe Kriteria</label>
                <select name="tipe" id="edit_tipe" class="w-full border border-slate-300 px-4 py-2.5 rounded-xl bg-white outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="Benefit">Benefit</option>
                    <option value="Cost">Cost</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Bobot (Desimal)</label>
                <input type="number" step="0.01" name="bobot" id="edit_bobot" required class="w-full border border-slate-300 px-4 py-2.5 rounded-xl outline-none focus:ring-2 focus:ring-blue-600">
            </div>
            <div class="flex justify-end gap-3 pt-5 border-t border-slate-100 mt-2">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 text-slate-600 font-medium hover:bg-slate-100 rounded-xl transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 font-medium text-white rounded-xl hover:bg-blue-700 shadow-md shadow-blue-200 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function editKrit(id, kode, nama, tipe, bobot) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_kode').value = kode;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_tipe').value = tipe;
    document.getElementById('edit_bobot').value = bobot;
    const modal = document.getElementById('editModal');
    const content = document.getElementById('modalContent');
    modal.style.display = 'flex';
    setTimeout(() => {
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    }, 10);
}
function closeModal() {
    const modal = document.getElementById('editModal');
    const content = document.getElementById('modalContent');
    content.classList.remove('scale-100');
    content.classList.add('scale-95');
    setTimeout(() => {
        modal.style.display = 'none';
    }, 200);
}
</script>

<?php require_once 'admin_footer.php'; ?>
