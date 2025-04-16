<div class="container">
    <h2>Registrasi</h2>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
    <?php echo form_open('auth/register'); ?>
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" value="<?php echo set_value('name'); ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>" required>
        </div>
        <div class="form-group">
            <label>Telepon</label>
            <input type="text" name="phone" class="form-control" value="<?php echo set_value('phone'); ?>" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Peran</label>
            <select name="role" class="form-control" required>
                <option value="penyewa">Penyewa</option>
                <option value="pemilik">Pemilik Kosan</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Daftar</button>
        <a href="<?php echo site_url('login'); ?>" class="btn btn-link">Sudah punya akun? Login</a>
    <?php echo form_close(); ?>
</div>