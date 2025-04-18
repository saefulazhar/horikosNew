<div class="container">
    <h2><?php echo isset($title) ? $title : 'Edit Kosan'; ?></h2>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

    <?php echo form_open_multipart('dashboard/edit_kosan/' . $kosan->id); ?>
        <div class="form-group">
            <label>Nama Kosan</label>
            <input type="text" name="name" class="form-control" value="<?php echo set_value('name', $kosan->name); ?>" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="address" class="form-control" required><?php echo set_value('address', $kosan->address); ?></textarea>
        </div>
        <div class="form-group">
            <label>Link Google Maps (Opsional)</label>
            <input type="url" name="google_maps_link" class="form-control" value="<?php echo set_value('google_maps_link', $kosan->google_maps_link); ?>">
        </div>
        <div class="form-group">
            <label>Tipe Kosan</label>
            <select name="type" class="form-control" required>
                <option value="putra" <?php echo set_select('type', 'putra', $kosan->type == 'putra'); ?>>Putra</option>
                <option value="putri" <?php echo set_select('type', 'putri', $kosan->type == 'putri'); ?>>Putri</option>
                <option value="campur" <?php echo set_select('type', 'campur', $kosan->type == 'campur'); ?>>Campur</option>
            </select>
        </div>
        <div class="form-group">
            <label>Kategori Kepribadian</label>
            <select name="personality_category" class="form-control" required>
                <option value="introvert" <?php echo set_select('personality_category', 'introvert', $kosan->personality_category == 'introvert'); ?>>Introvert</option>
                <option value="extrovert" <?php echo set_select('personality_category', 'extrovert', $kosan->personality_category == 'extrovert'); ?>>Extrovert</option>
                <option value="neutral" <?php echo set_select('personality_category', 'neutral', $kosan->personality_category == 'neutral'); ?>>Neutral</option>
            </select>
        </div>
        <div class="form-group">
            <label>Deskripsi Kepribadian (Opsional)</label>
            <textarea name="personality_description" class="form-control"><?php echo set_value('personality_description', $kosan->personality_description); ?></textarea>
        </div>
        <div class="form-group">
            <label>Harga per Bulan (Rp)</label>
            <input type="number" name="price" class="form-control" value="<?php echo set_value('price', $kosan->price); ?>" required>
        </div>
        <div class="form-group">
            <label>Jumlah Kamar Tersedia</label>
            <input type="number" name="available_rooms" class="form-control" value="<?php echo set_value('available_rooms', $kosan->available_rooms); ?>" required>
        </div>
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control" required><?php echo set_value('description', $kosan->description); ?></textarea>
        </div>
        <div class="form-group">
            <label>Fasilitas</label>
            <?php foreach ($facilities as $facility): ?>
                <div class="form-check">
                    <input type="checkbox" name="facilities[]" value="<?php echo $facility->id; ?>" class="form-check-input" <?php echo in_array($facility->id, $kosan_facilities) ? 'checked' : ''; ?>>
                    <label class="form-check-label"><?php echo $facility->name; ?></label>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="form-group">
            <label>Foto Saat Ini</label>
            <div class="row">
                <?php foreach ($photos as $photo): ?>
                    <div class="col-md-3">
                        <img src="<?php echo base_url('assets/uploads/' . $photo->url); ?>" alt="Foto Kosan" class="img-thumbnail" style="width: 100%;">
                        <div class="form-check">
                            <input type="checkbox" name="delete_photos[]" value="<?php echo $photo->id; ?>" class="form-check-input">
                            <label class="form-check-label">Hapus foto ini</label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="form-group">
            <label>Tambah Foto Baru (jpg/png, max 2MB)</label>
            <input type="file" name="photos[]" class="form-control" multiple accept="image/jpeg,image/png">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?php echo site_url('dashboard/owner'); ?>" class="btn btn-secondary">Kembali</a>
    <?php echo form_close(); ?>
</div>