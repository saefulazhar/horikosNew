<div class="container">
    <h2><?php echo isset($title) ? $title : 'Dashboard Pemilik'; ?></h2>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <!-- Tombol Tambah Kosan -->
    <a href="<?php echo site_url('dashboard/add_kosan'); ?>" class="btn btn-primary mb-3">Tambah Kosan</a>

    <!-- Daftar Kosan -->
    <h3>Kosan Saya</h3>
    <?php if (empty($kosans)): ?>
        <p>Belum ada kosan yang terdaftar.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Harga</th>
                    <th>Kepribadian</th>
                    <th>Status</th>
                    <th>Kamar Tersedia</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kosans as $kosan): ?>
                    <tr>
                        <td>
                            <?php
                            $primary_photo = null;
                            foreach ($kosan->photos as $photo) {
                                if ($photo->is_primary) {
                                    $primary_photo = $photo;
                                    break;
                                }
                            }
                            if ($primary_photo): ?>
                                <img src="<?php echo base_url('assets/uploads/' . $primary_photo->url); ?>" alt="<?php echo $kosan->name; ?>" style="width: 100px; height: auto;">
                            <?php else: ?>
                                <span>Tidak ada foto</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $kosan->name; ?></td>
                        <td><?php echo $kosan->address; ?></td>
                        <td>Rp <?php echo number_format($kosan->price, 0, ',', '.'); ?></td>
                        <td><?php echo ucfirst($kosan->personality_category); ?></td>
                        <td><?php echo ucfirst($kosan->status); ?></td>
                        <td><?php echo $kosan->available_rooms; ?></td>
                        <td>
                            <a href="<?php echo site_url('dashboard/view_kosan/' . $kosan->id); ?>" class="btn btn-sm btn-info">Detail</a>
                            <a href="<?php echo site_url('dashboard/edit_kosan/' . $kosan->id); ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="<?php echo site_url('dashboard/delete_kosan/' . $kosan->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kosan ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Data Penghuni Kos -->
    <h3>Data Penghuni Kos</h3>
    <?php if (empty($tenants)): ?>
        <p>Tidak ada penghuni kos saat ini.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kosan</th>
                    <th>Nama Penyewa</th>
                    <th>Telepon</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Berakhir</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tenants as $tenant): ?>
                    <tr>
                        <td><?php echo $tenant->kosan_name; ?></td>
                        <td><?php echo $tenant->name; ?></td>
                        <td><?php echo $tenant->phone; ?></td>
                        <td><?php echo $tenant->move_in_date; ?></td>
                        <td><?php echo $tenant->end_date; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Reservasi Masuk -->
    <h3>Reservasi Masuk</h3>
    <?php if (empty($reservations)): ?>
        <p>Tidak ada reservasi baru.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kosan</th>
                    <th>Nama Penyewa</th>
                    <th>Telepon</th>
                    <th>Tanggal Menempati</th>
                    <th>Durasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $r): ?>
                    <tr>
                        <td><?php echo $r->kosan_name; ?></td>
                        <td><?php echo $r->name; ?></td>
                        <td><?php echo $r->phone; ?></td>
                        <td><?php echo $r->move_in_date; ?></td>
                        <td><?php echo $r->duration; ?> bulan</td>
                        <td><?php echo ucfirst($r->status); ?></td>
                        <td>
                            <?php if ($r->status == 'pending'): ?>
                                <a href="<?php echo site_url('dashboard/approve_reservation/' . $r->id); ?>" class="btn btn-sm btn-success">Terima</a>
                                <a href="<?php echo site_url('dashboard/reject_reservation/' . $r->id); ?>" class="btn btn-sm btn-danger">Tolak</a>
                            <?php else: ?>
                                <span>-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Perpanjangan Sewa Masuk -->
    <h3>Perpanjangan Sewa Masuk</h3>
    <?php if (empty($extensions)): ?>
        <p>Tidak ada pengajuan perpanjangan.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kosan</th>
                    <th>Nama Penyewa</th>
                    <th>Durasi Perpanjangan</th>
                    <th>Tanggal Mulai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($extensions as $e): ?>
                    <tr>
                        <td><?php echo $e->kosan_name; ?></td>
                        <td><?php echo $e->penyewa_name; ?></td>
                        <td><?php echo $e->extended_duration; ?> bulan</td>
                        <td><?php echo $e->extension_start_date; ?></td>
                        <td><?php echo ucfirst($e->status); ?></td>
                        <td>
                            <?php if ($e->status == 'pending_extension'): ?>
                                <a href="<?php echo site_url('dashboard/approve_extension/' . $e->id); ?>" class="btn btn-sm btn-success">Terima</a>
                                <a href="<?php echo site_url('dashboard/reject_extension/' . $e->id); ?>" class="btn btn-sm btn-danger">Tolak</a>
                            <?php else: ?>
                                <span>-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>