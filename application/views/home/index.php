<div class="container">
    <h2>Cari Kosan</h2>
    <?php echo form_open('home/index'); ?>
        <div class="form-group">
            <label>Lokasi</label>
            <input type="text" name="location" class="form-control">
        </div>
        <div class="form-group">
            <label>Kepribadian</label>
            <select name="personality" class="form-control">
                <option value="">Pilih</option>
                <option value="introvert">Introvert</option>
                <option value="extrovert">Extrovert</option>
                <option value="neutral">Netral</option>
            </select>
        </div>
        <div class="form-group">
            <label>Harga</label>
            <input type="number" name="price_min" placeholder="Min" class="form-control">
            <input type="number" name="price_max" placeholder="Max" class="form-control">
        </div>
        <div class="form-group">
            <label>Fasilitas</label>
            <?php foreach ($facilities as $facility): ?>
                <div>
                    <input type="checkbox" name="facilities[]" value="<?php echo $facility->id; ?>">
                    <?php echo $facility->name; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="btn btn-primary">Cari</button>
    <?php echo form_close(); ?>

    <h3>Hasil Pencarian</h3>
    <div class="row">
        <?php foreach ($kosans as $kosan): ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5><?php echo $kosan->name; ?></h5>
                        <p><?php echo $kosan->address; ?></p>
                        <p>Rp <?php echo number_format($kosan->price, 0, ',', '.'); ?></p>
                        <p><?php echo ucfirst($kosan->personality_category); ?></p>
                        <a href="<?php echo site_url('kosan/'.$kosan->id); ?>" class="btn btn-info">Detail</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>