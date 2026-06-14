<?php
require_once 'database.php';

// Handle CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            $stmt = $pdo->prepare("INSERT INTO alternatif (kode, nama) VALUES (?, ?)");
            $stmt->execute([$_POST['kode'], $_POST['nama']]);
        } elseif ($_POST['action'] == 'edit') {
            $stmt = $pdo->prepare("UPDATE alternatif SET kode=?, nama=? WHERE id=?");
            $stmt->execute([$_POST['kode'], $_POST['nama'], $_POST['id']]);
        } elseif ($_POST['action'] == 'delete') {
            $stmt = $pdo->prepare("DELETE FROM alternatif WHERE id=?");
            $stmt->execute([$_POST['id']]);
        }
        header("Location: admin_alternatif.php");
        exit;
    }
}

$alternatifs = $pdo->query("SELECT * FROM alternatif ORDER BY kode")->fetchAll(PDO::FETCH_ASSOC);

require_once 'admin_header.php';
?>

<div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm mb-8">
    <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center">
        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        Tambah Alternatif Baru
    </h2>
    <form method="POST" class="flex flex-col md:flex-row gap-4">
        <input type="hidden" name="action" value="add">
        <input type="text" name="kode" placeholder="Kode (ex: A1)" required class="border border-slate-300 px-4 py-2.5 rounded-xl w-full md:w-1/4 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition">
        <input type="text" name="nama" placeholder="Nama Alternatif (ex: QRIS)" required class="border border-slate-300 px-4 py-2.5 rounded-xl flex-1 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition">
        <button type="submit" class="bg-blue-600 text-white font-semibold px-8 py-2.5 rounded-xl hover:bg-blue-700 shadow-md shadow-blue-200 transition">Simpan</button>
    </form>
</div>

<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 text-sm font-semibold uppercase tracking-wider">
            <tr>
                <th class="py-4 px-6 w-20">ID</th>
                <th class="py-4 px-6 w-32">Kode</th>
                <th class="py-4 px-6">Nama Alternatif</th>
                <th class="py-4 px-6 w-40 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach($alternatifs as $a): ?>
            <tr class="hover:bg-slate-50 transition">
                <td class="py-4 px-6 text-slate-500">#<?= $a['id'] ?></td>
                <td class="py-4 px-6 font-medium text-slate-800"><span class="bg-slate-100 text-slate-600 px-2 py-1 rounded-md border border-slate-200"><?= htmlspecialchars($a['kode']) ?></span></td>
                <td class="py-4 px-6 font-medium text-slate-900"><?= htmlspecialchars($a['nama']) ?></td>
                <td class="py-4 px-6 flex justify-center gap-2">
                    <button onclick="editAlt(<?= $a['id'] ?>, '<?= htmlspecialchars($a['kode']) ?>', '<?= htmlspecialchars($a['nama']) ?>')" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Edit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </button>
                    <form method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus alternatif ini beserta nilai matriksnya?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $a['id'] ?>">
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
        <h2 class="text-xl font-bold text-slate-900 mb-6">Edit Alternatif</h2>
        <form method="POST" class="space-y-5">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kode Alternatif</label>
                <input type="text" name="kode" id="edit_kode" required class="w-full border border-slate-300 px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Alternatif</label>
                <input type="text" name="nama" id="edit_nama" required class="w-full border border-slate-300 px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition">
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 text-slate-600 font-medium hover:bg-slate-100 rounded-xl transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 font-medium text-white rounded-xl hover:bg-blue-700 shadow-md shadow-blue-200 transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function editAlt(id, kode, nama) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_kode').value = kode;
    document.getElementById('edit_nama').value = nama;
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
