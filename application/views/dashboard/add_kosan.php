<div class="container">
    <h2><?php echo isset($title) ? $title : 'Tambah Kosan'; ?></h2>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

    <?php echo form_open_multipart('dashboard/add_kosan'); ?>
        <div class="form-group">
            <label>Nama Kosan</label>
            <input type="text" name="name" class="form-control" value="<?php echo set_value('name'); ?>" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="address" class="form-control" required><?php echo set_value('address'); ?></textarea>
        </div>
        <div class="form-group">
            <label>Link Google Maps (Opsional)</label>
            <input type="url" name="google_maps_link" class="form-control" value="<?php echo set_value('google_maps_link'); ?>">
        </div>
        <div class="form-group">
            <label>Tipe Kosan</label>
            <select name="type" class="form-control" required>
                <option value="putra" <?php echo set_select('type', 'putra'); ?>>Putra</option>
                <option value="putri" <?php echo set_select('type', 'putri'); ?>>Putri</option>
                <option value="campur" <?php echo set_select('type', 'campur'); ?>>Campur</option>
            </select>
        </div>
        <div class="form-group">
            <label>Kategori Kepribadian</label>
            <select name="personality_category" class="form-control" required>
                <option value="introvert" <?php echo set_select('personality_category', 'introvert'); ?>>Introvert</option>
                <option value="extrovert" <?php echo set_select('personality_category', 'extrovert'); ?>>Extrovert</option>
                <option value="neutral" <?php echo set_select('personality_category', 'neutral'); ?>>Neutral</option>
            </select>
        </div>
        <div class="form-group">
            <label>Deskripsi Kepribadian (Opsional)</label>
            <textarea name="personality_description" class="form-control"><?php echo set_value('personality_description'); ?></textarea>
        </div>
        <div class="form-group">
            <label>Harga (Rp/Bulan)</label>
            <input type="number" name="price" class="form-control" value="<?php echo set_value('price'); ?>" required>
        </div>
        <div class="form-group">
            <label>Jumlah Kamar Tersedia</label>
            <input type="number" name="available_rooms" class="form-control" value="<?php echo set_value('available_rooms'); ?>" required>
        </div>
        <div class="form-group">
            <label>Deskripsi Umum</label>
            <textarea name="description" class="form-control" required><?php echo set_value('description'); ?></textarea>
        </div>
        <div class="form-group">
            <label>Fasilitas</label>
            <?php foreach ($facilities as $facility): ?>
                <div class="form-check">
                    <input type="checkbox" name="facilities[]" value="<?php echo $facility->id; ?>" class="form-check-input" <?php echo set_checkbox('facilities', $facility->id); ?>>
                    <label class="form-check-label"><?php echo $facility->name; ?></label>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="form-group">
            <label>Upload Foto (jpg/png, maks 2MB)</label>
            <input type="file" name="photos[]" class="form-control" multiple accept="image/jpeg,image/png">
        </div>
        <button type="submit" class="btn btn-primary">Tambah Kosan</button>
        <a href="<?php echo site_url('dashboard/owner'); ?>" class="btn btn-secondary">Kembali</a>
    <?php echo form_close(); ?>
</div>