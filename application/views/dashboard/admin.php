<div class="container">
    <h2><?php echo isset($title) ? $title : 'Dashboard Admin'; ?></h2>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <!-- Statistik -->
    <h3>Statistik</h3>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Kosan</h5>
                    <p class="card-text"><?php echo $stats['total_kosans']; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Kosan Aktif</h5>
                    <p class="card-text"><?php echo $stats['active_kosans']; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Kosan Inactive</h5>
                    <p class="card-text"><?php echo $stats['inactive_kosans']; ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Pengguna</h5>
                    <p class="card-text"><?php echo $stats['total_users']; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pemilik</h5>
                    <p class="card-text"><?php echo $stats['pemilik_count']; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Penyewa</h5>
                    <p class="card-text"><?php echo $stats['penyewa_count']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Kosan -->
    <h3 class="mt-4">Manajemen Kosan</h3>
    <form method="get" action="<?php echo site_url('dashboard/admin'); ?>" class="mb-3">
        <div class="form-group">
            <label>Filter Status</label>
            <select name="status" class="form-control" onchange="this.form.submit()">
                <option value="">Semua</option>
                <option value="active" <?php echo $this->input->get('status') == 'active' ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo $this->input->get('status') == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                <option value="rejected" <?php echo $this->input->get('status') == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
            </select>
        </div>
    </form>
    <a href="<?php echo site_url('dashboard/manage_users'); ?>" class="btn btn-primary mb-3">Manajemen Pengguna</a>
    <!-- Daftar Kosan -->
    <?php if (empty($kosans)): ?>
        <p>Tidak ada kosan yang ditemukan.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Harga</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th>Pemilik</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kosans as $kosan): ?>
                    <tr>
                        <td><?php echo $kosan->name; ?></td>
                        <td><?php echo $kosan->address; ?></td>
                        <td>Rp <?php echo number_format($kosan->price, 0, ',', '.'); ?></td>
                        <td><?php echo ucfirst($kosan->type); ?></td>
                        <td><?php echo ucfirst($kosan->status); ?></td>
                        <td>
                            <?php
                            $owner = $this->db->get_where('users', ['id' => $kosan->owner_id])->row();
                            echo $owner ? $owner->name : 'Unknown';
                            ?>
                        </td>
                        <td>
                            <a href="<?php echo site_url('dashboard/view_kosan/' . $kosan->id); ?>" class="btn btn-sm btn-info">Detail</a>
                            <?php if ($kosan->status == 'inactive'): ?>
                                <a href="<?php echo site_url('dashboard/verify_kosan/' . $kosan->id); ?>" class="btn btn-sm btn-success">Verifikasi</a>
                                <a href="<?php echo site_url('dashboard/reject_kosan/' . $kosan->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menolak kosan ini?')">Tolak</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>