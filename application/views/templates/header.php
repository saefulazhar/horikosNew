<!DOCTYPE html>
<html>
<head>
    <title>Kosan App</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="<?php echo site_url(); ?>">Kosan</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo site_url('home'); ?>">Home</a>
                </li>
                <?php if ($this->session->userdata('user_id')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('dashboard/'.($this->session->userdata('role') == 'penyewa' ? 'penyewa' : ($this->session->userdata('role') == 'pemilik' ? 'owner' : 'admin'))); ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('auth/logout'); ?>">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('auth/login'); ?>">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo site_url('auth/register'); ?>">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">