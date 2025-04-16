<div class="container">
    <h2>Konfirmasi Reservasi</h2>
    <p>Status: <?php echo ucfirst($reservation->status); ?></p>
    <p>Nama: <?php echo $reservation->name; ?></p>
    <p>Telepon: <?php echo $reservation->phone; ?></p>
    <p>Tanggal Menempati: <?php echo $reservation->move_in_date; ?></p>
    <p>Durasi: <?php echo $reservation->duration; ?></p>
    <p>Harap lakukan pembayaran COD saat tiba di kosan.</p>
    <a href="<?php echo site_url('dashboard/penyewa'); ?>" class="btn btn-primary">Ke Dashboard</a>
</div>