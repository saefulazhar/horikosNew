<div class="container">
    <h2><?php echo isset($title) ? $title : 'Edit Pengguna'; ?></h2>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

    <?php echo form_open('dashboard/edit_user/' . $user->id); ?>
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" value="<?php echo set_value('name', $user->name); ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo set_value('email', $user->email); ?>" required>
        </div>
        <div class="form-group">
            <label>Telepon</label>
            <input type="text" name="phone" class="form-control" value="<?php echo set_value('phone', $user->phone); ?>" required>
        </div>
        <div class="form-group">
            <label>Role</label>
            <select name="role" class="form-control" required>
                <option value="penyewa" <?php echo set_select('role', 'penyewa', $user->role == 'penyewa'); ?>>Penyewa</option>
                <option value="pemilik" <?php echo set_select('role', 'pemilik', $user->role == 'pemilik'); ?>>Pemilik</option>
                <option value="admin" <?php echo set_select('role', 'admin', $user->role == 'admin'); ?>>Admin</option>
            </select>
        </div>
        <div class="form-group">
            <label>Password Baru (Kosongkan jika tidak ingin mengubah)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?php echo site_url('dashboard/manage_users'); ?>" class="btn btn-secondary">Kembali</a>
    <?php echo form_close(); ?>
</div>