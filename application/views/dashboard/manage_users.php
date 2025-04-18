<div class="container">
    <h2><?php echo isset($title) ? $title : 'Manajemen Pengguna'; ?></h2>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <h3>Daftar Pengguna</h3>
    <?php if (empty($users)): ?>
        <p>Tidak ada pengguna yang terdaftar.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user->name; ?></td>
                        <td><?php echo $user->email; ?></td>
                        <td><?php echo $user->phone; ?></td>
                        <td><?php echo ucfirst($user->role); ?></td>
                        <td>
                            <a href="<?php echo site_url('dashboard/edit_user/' . $user->id); ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="<?php echo site_url('dashboard/delete_user/' . $user->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <a href="<?php echo site_url('dashboard/admin'); ?>" class="btn btn-secondary">Kembali ke Dashboard</a>
</div>