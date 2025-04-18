<div class="container">
    <h2><?php echo isset($title) ? $title : 'Dashboard Penyewa'; ?></h2>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <!-- Form Pencarian -->
    <h3>Cari Kosan</h3>
    <?php echo form_open('dashboard/penyewa', ['method' => 'get']); ?>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Lokasi</label>
                    <input type="text" name="location" class="form-control" value="<?php echo $this->input->get('location'); ?>">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Kepribadian</label>
                    <select name="personality" class="form-control">
                        <option value="">Semua</option>
                        <option value="introvert" <?php echo $this->input->get('personality') == 'introvert' ? 'selected' : ''; ?>>Introvert</option>
                        <option value="extrovert" <?php echo $this->input->get('personality') == 'extrovert' ? 'selected' : ''; ?>>Extrovert</option>
                        <option value="neutral" <?php echo $this->input->get('personality') == 'neutral' ? 'selected' : ''; ?>>Neutral</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Harga Min (Rp)</label>
                    <input type="number" name="price_min" class="form-control" value="<?php echo $this->input->get('price_min'); ?>">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Harga Max (Rp)</label>
                    <input type="number" name="price_max" class="form-control" value="<?php echo $this->input->get('price_max'); ?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Fasilitas</label>
            <div class="row">
                <?php foreach ($facilities as $facility): ?>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input type="checkbox" name="facilities[]" value="<?php echo $facility->id; ?>" class="form-check-input" <?php echo in_array($facility->id, (array)$this->input->get('facilities')) ? 'checked' : ''; ?>>
                            <label class="form-check-label"><?php echo $facility->name; ?></label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Cari</button>
        <a href="<?php echo site_url('dashboard/penyewa'); ?>" class="btn btn-secondary">Reset</a>
    <?php echo form_close(); ?>

    <!-- Daftar Kosan -->
    <h3>Daftar Kosan Tersedia</h3>
    <?php if (empty($kosans)): ?>
        <p>Tidak ada kosan yang tersedia.</p>
    <?php else: ?>
        <div class="row">
            <?php foreach ($kosans as $kosan): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <?php
                            $primary_photo = null;
                            foreach ($kosan->photos as $photo) {
                                if ($photo->is_primary) {
                                    $primary_photo = $photo;
                                    break;
                                }
                            }
                            if ($primary_photo): ?>
                                <img src="<?php echo base_url('assets/uploads/' . $primary_photo->url); ?>" alt="<?php echo $kosan->name; ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div class="text-center">Tidak ada foto</div>
                            <?php endif; ?>
                            <h5 class="card-title mt-2"><?php echo $kosan->name; ?></h5>
                            <p class="card-text"><strong>Alamat:</strong> <?php echo $kosan->address; ?></p>
                            <p class="card-text"><strong>Harga:</strong> Rp <?php echo number_format($kosan->price, 0, ',', '.'); ?> / bulan</p>
                            <p class="card-text"><strong>Kepribadian:</strong> <?php echo ucfirst($kosan->personality_category); ?></p>
                            <p class="card-text"><strong>Kamar Tersedia:</strong> <?php echo $kosan->available_rooms; ?></p>
                            <a href="<?php echo site_url('dashboard/view_kosan_owner/' . $kosan->id); ?>" class="btn btn-primary">Detail</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Riwayat Reservasi -->
    <h3>Riwayat Reservasi</h3>
    <?php if (empty($reservations)): ?>
        <p>Belum ada reservasi.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kosan</th>
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
                        <td><?php echo $r->move_in_date; ?></td>
                        <td><?php echo $r->duration; ?> bulan</td>
                        <td><?php echo ucfirst($r->status); ?></td>
                        <td>
                            <?php
                            $current_date = date('Y-m-d');
                            $end_date = date('Y-m-d', strtotime($r->move_in_date . " +{$r->duration} months"));
                            $extension = $this->db->where('reservation_id', $r->id)
                                                  ->where('status', 'pending_extension')
                                                  ->get('extensions')
                                                  ->row();
                            if ($r->status == 'approved' && $current_date <= $end_date && !$extension): ?>
                                <a href="<?php echo site_url('dashboard/extend_reservation/' . $r->id); ?>" class="btn btn-sm btn-warning">Perpanjang</a>
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