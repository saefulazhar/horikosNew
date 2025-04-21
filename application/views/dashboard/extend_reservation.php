<div class="container">
    <h2><?php echo isset($title) ? $title : 'Perpanjang Sewa'; ?></h2>
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
            <p><strong>Tanggal Menempati:</strong> <?php echo $reservation->move_in_date; ?></p>
            <p><strong>Durasi Awal:</strong> <?php echo $reservation->duration; ?> bulan</p>
            <p><strong>Tanggal Berakhir:</strong> <?php echo date('Y-m-d', strtotime($reservation->move_in_date . " +{$reservation->duration} months")); ?></p>
        </div>
    </div>

    <?php echo form_open('dashboard/extend_reservation/' . $reservation->id); ?>
        <div class="form-group">
            <label>Durasi Perpanjangan (bulan)</label>
            <input type="number" name="extended_duration" class="form-control" value="<?php echo set_value('extended_duration'); ?>" required min="1">
        </div>
        <button type="submit" class="btn btn-primary">Ajukan Perpanjangan</button>
        <a href="<?php echo site_url('dashboard/penyewa'); ?>" class="btn btn-secondary">Kembali</a>
    <?php echo form_close(); ?>
</div>