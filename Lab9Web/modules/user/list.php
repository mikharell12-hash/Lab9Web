<?php
/**
 * modules/user/list.php
 * Modul daftar pengguna aplikasi (dibatasi hanya untuk pengguna yang sudah login).
 */
requireLogin('user/list');
$pageTitle = 'Manajemen Data Pengguna';

$daftarUser = $pdo->query("
    SELECT id_user, nama, email, role, created_at
    FROM users
    ORDER BY id_user ASC
")->fetchAll();

$idSedangLogin = (int)(currentUser()['id_user'] ?? 0);
?>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Data Pengguna (<code>modules/user/</code>)</h2>
            <p class="panel-subtitle">Modul ini dilindungi pembatasan akses sesi login (Tantangan Mandiri) dan terhubung ke tabel <code>users</code>.</p>
        </div>
        <a href="<?= e(routeUrl('user/add')); ?>" class="btn btn-primary">+ Tambah Pengguna</a>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pengguna</th>
                    <th>Email</th>
                    <th>Peran (Role)</th>
                    <th>Terdaftar</th>
                    <th style="width: 170px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($daftarUser as $u): ?>
                    <tr>
                        <td>#<?= (int)$u['id_user']; ?></td>
                        <td>
                            <strong><?= e($u['nama']); ?></strong>
                            <?php if ((int)$u['id_user'] === $idSedangLogin): ?>
                                <span class="badge badge-green">Sesi Anda</span>
                            <?php endif; ?>
                        </td>
                        <td><?= e($u['email']); ?></td>
                        <td><span class="badge badge-blue"><?= e($u['role']); ?></span></td>
                        <td><?= e(date('d M Y', strtotime((string)$u['created_at']))); ?></td>
                        <td>
                            <div class="action-group">
                                <a href="<?= e(routeUrl('user/edit', ['id' => (int)$u['id_user']])); ?>" class="btn btn-sm btn-secondary">Ubah</a>
                                <?php if ((int)$u['id_user'] !== $idSedangLogin): ?>
                                    <form method="post" action="<?= e(routeUrl('user/delete')); ?>" class="inline-form js-confirm-delete" data-nama="<?= e($u['nama']); ?>">
                                        <?= csrfField(); ?>
                                        <input type="hidden" name="id_user" value="<?= (int)$u['id_user']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
