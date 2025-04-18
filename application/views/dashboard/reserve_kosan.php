<div class="container">
    <h2><?php echo isset($title) ? $title : 'Reservasi Kosan'; ?></h2>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?php echo $kosan->name; ?></h5>
            <p><strong>Harga:</strong> Rp <?php echo number_format($kosan->price, 0, ',', '.'); ?> / bulan</p>
            <p><strong>Kamar Tersedia:</strong> <?php echo $kosan->available_rooms; ?></p>
        </div>
    </div>

    <?php echo form_open('dashboard/reserve_kosan/' . $kosan->id); ?>
        <div class="form-group">
            <label>Tanggal Menempati</label>
            <input type="date" name="move_in_date" class="form-control" value="<?php echo set_value('move_in_date'); ?>" required min="<?php echo date('Y-m-d'); ?>">
        </div>
        <div class="form-group">
            <label>Durasi Sewa (bulan)</label>
            <input type="number" name="duration" class="form-control" value="<?php echo set_value('duration'); ?>" required min="1">
        </div>
        <button type="submit" class="btn btn-primary">Ajukan Reservasi</button>
        <a href="<?php echo site_url('dashboard/penyewa'); ?>" class="btn btn-secondary">Kembali</a>
    <?php echo form_close(); ?>
</div>