<div class="container">
    <h2>Perpanjang Sewa</h2>
    <?php echo form_open('dashboard/extend/'.$reservation->id); ?>
        <div class="form-group">
            <label>Durasi Perpanjangan</label>
            <select name="extended_duration" class="form-control" required>
                <option value="1 bulan">1 Bulan</option>
                <option value="3 bulan">3 Bulan</option>
                <option value="6 bulan">6 Bulan</option>
            </select>
        </div>
        <div class="form-group">
            <label>Tanggal Mulai</label>
            <input type="date" name="extension_start_date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajukan</button>
    <?php echo form_close(); ?>
</div>