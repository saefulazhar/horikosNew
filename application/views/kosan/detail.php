<div class="container">
    <h2><?php echo $kosan->name; ?></h2>
    <p><?php echo $kosan->address; ?></p>
    <a href="<?php echo $kosan->google_maps_link; ?>" target="_blank">Lihat di Google Maps</a>
    <p>Rp <?php echo number_format($kosan->price, 0, ',', '.'); ?> / bulan</p>
    <p>Kepribadian: <?php echo ucfirst($kosan->personality_category); ?></p>
    <p><?php echo $kosan->description; ?></p>

    <h3>Foto</h3>
    <div class="row">
        <?php foreach ($photos as $photo): ?>
            <div class="col-md-3">
                <img src="<?php echo base_url('assets/uploads/'.$photo->url); ?>" class="img-fluid">
            </div>
        <?php endforeach; ?>
    </div>

    <h3>Ulasan</h3>
    <?php foreach ($reviews as $review): ?>
        <div class="card mb-2">
            <div class="card-body">
                <p>Rating: <?php echo $review->rating; ?>/5</p>
                <p><?php echo $review->comment; ?></p>
                <p>Kesesuaian: <?php echo $review->personality_match; ?></p>
            </div>
        </div>
    <?php endforeach; ?>

    <a href="<?php echo site_url('reservation/create/'.$kosan->id); ?>" class="btn btn-primary">Reservasi Sekarang</a>
</div>