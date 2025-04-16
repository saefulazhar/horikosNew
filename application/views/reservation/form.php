<div class="container">
    <h2>Reservasi Kosan: <?php echo $kosan->name; ?></h2>
    <?php echo form_open('reservation/create/'.$kosan->id); ?>
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Telepon</label>
            <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Tanggal Menempati</label>
            <input type="date" name="move_in_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Durasi Sewa</label>
            <select name="duration" class="form-control" required>
                <option value="1 bulan">1 Bulan</option>
                <option value="3 bulan">3 Bulan</option>
                <option value="6 bulan">6 Bulan</option>
                <option value="12 bulan">12 Bulan</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Reservasi</button>
    <?php echo form_close(); ?>
</div>