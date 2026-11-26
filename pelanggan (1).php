<?php
require __DIR__ . '/config.php';

$error = '';
$edit = ['id_pelanggan' => '', 'id_jenis' => '', 'nama_pelanggan' => '', 'alamat' => '', 'no_handphone' => ''];
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM pelanggan WHERE id_pelanggan = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $row = $stmt->fetch();
    if ($row) $edit = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        $id = (int)($_POST['id_pelanggan'] ?? 0);
        $idJenis = (int)($_POST['id_jenis'] ?? 0);
        $nama = trim((string)($_POST['nama_pelanggan'] ?? ''));
        $alamat = trim((string)($_POST['alamat'] ?? ''));
        $hp = trim((string)($_POST['no_handphone'] ?? ''));
        $edit = ['id_pelanggan' => $id, 'id_jenis' => $idJenis, 'nama_pelanggan' => $nama, 'alamat' => $alamat, 'no_handphone' => $hp];

        if ($idJenis < 1 || $nama === '' || $alamat === '' || $hp === '') {
            $error = 'Semua kolom wajib diisi.';
        } elseif (mb_strlen($nama) > 100 || mb_strlen($alamat) > 255 || mb_strlen($hp) > 20) {
            $error = 'Nama maksimal 100 karakter, alamat 255 karakter, dan nomor handphone 20 karakter.';
        } else {
            if ($id > 0) {
                $stmt = $pdo->prepare('UPDATE pelanggan SET id_jenis = ?, nama_pelanggan = ?, alamat = ?, no_handphone = ? WHERE id_pelanggan = ?');
                $stmt->execute([$idJenis, $nama, $alamat, $hp, $id]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO pelanggan (id_jenis, nama_pelanggan, alamat, no_handphone) VALUES (?, ?, ?, ?)');
                $stmt->execute([$idJenis, $nama, $alamat, $hp]);
            }
            redirect_to('pelanggan.php?saved=1');
        }
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare('DELETE FROM pelanggan WHERE id_pelanggan = ?');
        $stmt->execute([(int)($_POST['id_pelanggan'] ?? 0)]);
        redirect_to('pelanggan.php?deleted=1');
    }
}
$jenisList = $pdo->query('SELECT id_jenis, jenis FROM jenis_pelanggan ORDER BY jenis')->fetchAll();
$rows = $pdo->query(
    'SELECT p.id_pelanggan, p.nama_pelanggan, p.alamat, p.no_handphone, j.jenis
     FROM pelanggan p INNER JOIN jenis_pelanggan j ON j.id_jenis = p.id_jenis
     ORDER BY p.id_pelanggan DESC'
)->fetchAll();
?>
<!doctype html>
<html lang="id">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Pelanggan</title><link rel="stylesheet" href="style.css"></head>
<body>
<header class="topbar"><div class="container nav"><a class="brand" href="index.php">Web1 <span>Data Pelanggan</span></a><nav><a class="active" href="pelanggan.php">Pelanggan</a><a href="jenis_pelanggan.php">Jenis Pelanggan</a></nav></div></header>
<main class="container">
    <div class="page-heading"><div><p class="eyebrow">DATA TRANSAKSI</p><h1>Pelanggan</h1><p>Data pelanggan terhubung dengan jenis pelanggan.</p></div><a class="button secondary" href="index.php">← Beranda</a></div>
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
    <?php if (isset($_GET['saved'])): ?><div class="alert success">Data pelanggan berhasil disimpan.</div><?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?><div class="alert success">Data pelanggan berhasil dihapus.</div><?php endif; ?>
    <?php if (!$jenisList): ?><div class="alert error">Belum ada jenis pelanggan. Tambahkan jenis pelanggan terlebih dahulu.</div><?php endif; ?>
    <section class="panel">
        <h2><?= $edit['id_pelanggan'] ? 'Edit Pelanggan' : 'Tambah Pelanggan' ?></h2>
        <form method="post" class="form-grid">
            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="id_pelanggan" value="<?= e($edit['id_pelanggan']) ?>">
            <label>Nama pelanggan<input name="nama_pelanggan" maxlength="100" required value="<?= e($edit['nama_pelanggan']) ?>" placeholder="Nama lengkap"></label>
            <label>Jenis pelanggan<select name="id_jenis" required><option value="">-- Pilih jenis --</option><?php foreach ($jenisList as $j): ?><option value="<?= e($j['id_jenis']) ?>" <?= (string)$edit['id_jenis'] === (string)$j['id_jenis'] ? 'selected' : '' ?>><?= e($j['jenis']) ?></option><?php endforeach; ?></select></label>
            <label>Alamat<textarea name="alamat" maxlength="255" required rows="2" placeholder="Alamat pelanggan"><?= e($edit['alamat']) ?></textarea></label>
            <label>No. handphone<input name="no_handphone" maxlength="20" required value="<?= e($edit['no_handphone']) ?>" placeholder="Contoh: 081234567890"></label>
            <div class="form-actions"><button class="button" type="submit" <?= !$jenisList ? 'disabled' : '' ?>>Simpan</button><?php if ($edit['id_pelanggan']): ?><a class="button secondary" href="pelanggan.php">Batal</a><?php endif; ?></div>
        </form>
    </section>
    <section class="panel">
        <div class="table-title"><h2>Daftar Pelanggan</h2><span><?= count($rows) ?> data</span></div>
        <div class="table-wrap"><table><thead><tr><th>ID</th><th>Nama Pelanggan</th><th>Jenis</th><th>Alamat</th><th>No. Handphone</th><th>Aksi</th></tr></thead><tbody>
        <?php if (!$rows): ?><tr><td colspan="6" class="empty">Belum ada data pelanggan.</td></tr><?php endif; ?>
        <?php foreach ($rows as $row): ?><tr><td><?= e($row['id_pelanggan']) ?></td><td><?= e($row['nama_pelanggan']) ?></td><td><span class="pill"><?= e($row['jenis']) ?></span></td><td><?= e($row['alamat']) ?></td><td><?= e($row['no_handphone']) ?></td><td class="actions"><a class="button small secondary" href="pelanggan.php?edit=<?= e($row['id_pelanggan']) ?>">Edit</a><form method="post" onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')"><input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id_pelanggan" value="<?= e($row['id_pelanggan']) ?>"><button class="button small danger" type="submit">Hapus</button></form></td></tr><?php endforeach; ?>
        </tbody></table></div>
    </section>
</main>
</body>
</html>
