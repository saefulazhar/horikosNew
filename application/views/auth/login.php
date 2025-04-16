<div class="container">
    <h2>Login</h2>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <?php echo form_open('auth/login'); ?>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <a href="<?php echo site_url('auth/register'); ?>" class="btn btn-link">Belum punya akun? Daftar</a>
    <?php echo form_close(); ?>
</div>