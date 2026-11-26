<?php
require __DIR__ . '/config.php';

$error = '';
$edit = ['id_jenis' => '', 'jenis' => ''];
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT id_jenis, jenis FROM jenis_pelanggan WHERE id_jenis = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $row = $stmt->fetch();
    if ($row) $edit = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        $id = (int)($_POST['id_jenis'] ?? 0);
        $jenis = trim((string)($_POST['jenis'] ?? ''));
        if ($jenis === '') {
            $error = 'Nama jenis pelanggan wajib diisi.';
            $edit = ['id_jenis' => $id, 'jenis' => $jenis];
        } elseif (mb_strlen($jenis) > 50) {
            $error = 'Nama jenis maksimal 50 karakter.';
            $edit = ['id_jenis' => $id, 'jenis' => $jenis];
        } else {
            if ($id > 0) {
                $stmt = $pdo->prepare('UPDATE jenis_pelanggan SET jenis = ? WHERE id_jenis = ?');
                $stmt->execute([$jenis, $id]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO jenis_pelanggan (jenis) VALUES (?)');
                $stmt->execute([$jenis]);
            }
            redirect_to('jenis_pelanggan.php?saved=1');
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id_jenis'] ?? 0);
        try {
            $stmt = $pdo->prepare('DELETE FROM jenis_pelanggan WHERE id_jenis = ?');
            $stmt->execute([$id]);
            redirect_to('jenis_pelanggan.php?deleted=1');
        } catch (PDOException $e) {
            $error = 'Jenis ini masih digunakan oleh pelanggan. Ubah jenis pelanggan terkait terlebih dahulu.';
        }
    }
}
$rows = $pdo->query(
    'SELECT j.id_jenis, j.jenis, COUNT(p.id_pelanggan) AS jumlah_pelanggan
     FROM jenis_pelanggan j
     LEFT JOIN pelanggan p ON p.id_jenis = j.id_jenis
     GROUP BY j.id_jenis, j.jenis
     ORDER BY j.id_jenis'
)->fetchAll();
?>
<!doctype html>
<html lang="id">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Jenis Pelanggan</title><link rel="stylesheet" href="style.css"></head>
<body>
<header class="topbar"><div class="container nav"><a class="brand" href="index.php">Web1 <span>Data Pelanggan</span></a><nav><a href="pelanggan.php">Pelanggan</a><a class="active" href="jenis_pelanggan.php">Jenis Pelanggan</a></nav></div></header>
<main class="container">
    <div class="page-heading"><div><p class="eyebrow">MASTER DATA</p><h1>Jenis Pelanggan</h1><p>Tambah, ubah, tampilkan, atau hapus jenis pelanggan.</p></div><a class="button secondary" href="index.php">← Beranda</a></div>
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
    <?php if (isset($_GET['saved'])): ?><div class="alert success">Data jenis pelanggan berhasil disimpan.</div><?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?><div class="alert success">Data jenis pelanggan berhasil dihapus.</div><?php endif; ?>
    <section class="panel">
        <h2><?= $edit['id_jenis'] ? 'Edit Jenis Pelanggan' : 'Tambah Jenis Pelanggan' ?></h2>
        <form method="post" class="form-inline">
            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="id_jenis" value="<?= e($edit['id_jenis']) ?>">
            <label for="jenis">Jenis (contoh: Member / Biasa)</label>
            <input id="jenis" name="jenis" maxlength="50" required value="<?= e($edit['jenis']) ?>" placeholder="Masukkan jenis pelanggan">
            <button class="button" type="submit">Simpan</button>
            <?php if ($edit['id_jenis']): ?><a class="button secondary" href="jenis_pelanggan.php">Batal</a><?php endif; ?>
        </form>
    </section>
    <section class="panel">
        <div class="table-title"><h2>Daftar Jenis Pelanggan</h2><span><?= count($rows) ?> data</span></div>
        <div class="table-wrap"><table><thead><tr><th>ID</th><th>Jenis</th><th>Jumlah Pelanggan</th><th>Aksi</th></tr></thead><tbody>
        <?php if (!$rows): ?><tr><td colspan="4" class="empty">Belum ada data jenis pelanggan.</td></tr><?php endif; ?>
        <?php foreach ($rows as $row): ?><tr><td><?= e($row['id_jenis']) ?></td><td><?= e($row['jenis']) ?></td><td><?= e($row['jumlah_pelanggan']) ?></td><td class="actions"><a class="button small secondary" href="jenis_pelanggan.php?edit=<?= e($row['id_jenis']) ?>">Edit</a><form method="post" onsubmit="return confirm('Yakin ingin menghapus jenis ini?')"><input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id_jenis" value="<?= e($row['id_jenis']) ?>"><button class="button small danger" type="submit">Hapus</button></form></td></tr><?php endforeach; ?>
        </tbody></table></div>
    </section>
</main>
</body>
</html>
