<div class="container">
    <h2><?php echo isset($title) ? $title : 'Detail Kosan'; ?> - <?php echo $kosan->name; ?></h2>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?php echo $kosan->name; ?></h5>
            <p><strong>Alamat:</strong> <?php echo $kosan->address; ?></p>
            <p><strong>Harga:</strong> Rp <?php echo number_format($kosan->price, 0, ',', '.'); ?> / bulan</p>
            <p><strong>Tipe:</strong> <?php echo ucfirst($kosan->type); ?></p>
            <p><strong>Kepribadian:</strong> <?php echo ucfirst($kosan->personality_category); ?></p>
            <p><strong>Deskripsi Kepribadian:</strong> <?php echo $kosan->personality_description ?: 'Tidak ada'; ?></p>
            <p><strong>Kamar Tersedia:</strong> <?php echo $kosan->available_rooms; ?></p>
            <p><strong>Deskripsi:</strong> <?php echo $kosan->description; ?></p>
            <p><strong>Google Maps:</strong> 
                <?php if ($kosan->google_maps_link): ?>
                    <a href="<?php echo $kosan->google_maps_link; ?>" target="_blank">Lihat di Maps</a>
                <?php else: ?>
                    Tidak ada
                <?php endif; ?>
            </p>
            <p><strong>Status:</strong> <?php echo ucfirst($kosan->status); ?></p>
            <p><strong>Fasilitas:</strong></p>
            <ul>
                <?php if (!empty($facilities)): ?>
                    <?php foreach ($facilities as $facility): ?>
                        <li><?php echo $facility->name; ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Tidak ada fasilitas</li>
                <?php endif; ?>
            </ul>
            <p><strong>Foto:</strong></p>
            <?php if (!empty($photos)): ?>
                <div id="carouselKosan" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($photos as $index => $photo): ?>
                            <div class="carousel-item <?php echo $index == 0 ? 'active' : ''; ?>">
                                <img src="<?php echo base_url('assets/uploads/' . $photo->url); ?>" class="d-block w-50" alt="<?php echo $kosan->name; ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <a class="carousel-control-prev" href="#carouselKosan" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselKosan" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            <?php else: ?>
                <p>Tidak ada foto</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-3">
        <a href="<?php echo site_url('dashboard/edit_kosan/' . $kosan->id); ?>" class="btn btn-warning">Edit</a>
        <a href="<?php echo site_url('dashboard/delete_kosan/' . $kosan->id); ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kosan ini?')">Hapus</a>
        <a href="<?php echo site_url('dashboard/owner'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>