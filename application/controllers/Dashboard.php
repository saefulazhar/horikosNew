<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Kosan_model');
        $this->load->model('Reservation_model');
        $this->load->model('User_model');
        $this->load->library('upload');
        if (!$this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu');
            redirect('login');
        }
    }

    public function penyewa() {
        if ($this->session->userdata('role') != 'penyewa') {
            show_404();
        }
        $user_id = $this->session->userdata('user_id');
        $data['title'] = 'Dashboard Penyewa';

        // Ambil data kosan yang aktif
        $filters = [];
        if ($this->input->get('location')) {
            $filters['location'] = $this->input->get('location');
        }
        if ($this->input->get('personality')) {
            $filters['personality'] = $this->input->get('personality');
        }
        if ($this->input->get('price_min')) {
            $filters['price_min'] = $this->input->get('price_min');
        }
        if ($this->input->get('price_max')) {
            $filters['price_max'] = $this->input->get('price_max');
        }
        if ($this->input->get('facilities')) {
            $filters['facilities'] = $this->input->get('facilities');
        }
        $data['kosans'] = $this->Kosan_model->search_kosans($filters);
        foreach ($data['kosans'] as $kosan) {
            $kosan->photos = $this->Kosan_model->get_photos($kosan->id);
        }

        // Ambil fasilitas untuk form pencarian
        $data['facilities'] = $this->Kosan_model->get_facilities();

        // Ambil riwayat reservasi penyewa
        $data['reservations'] = $this->Reservation_model->get_user_reservations($user_id);

        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/penyewa', $data);
        $this->load->view('templates/footer');
    }
    public function reserve_kosan($id) {
        if ($this->session->userdata('role') != 'penyewa') {
            show_404();
        }
        $data['kosan'] = $this->Kosan_model->get_kosan($id);
        if (!$data['kosan']) {
            $this->session->set_flashdata('error', 'Kosan tidak ditemukan atau tidak tersedia.');
            redirect('dashboard/penyewa');
        }
        $data['title'] = 'Reservasi Kosan - ' . $data['kosan']->name;

        $this->form_validation->set_rules('move_in_date', 'Tanggal Menempati', 'required');
        $this->form_validation->set_rules('duration', 'Durasi Sewa', 'required|integer|greater_than[0]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('dashboard/reserve_kosan', $data);
            $this->load->view('templates/footer');
        } else {
            $user_id = $this->session->userdata('user_id');
            $reservation_data = [
                'kosan_id' => $id,
                'user_id' => $user_id,
                'move_in_date' => $this->input->post('move_in_date'),
                'duration' => $this->input->post('duration'),
                'status' => 'pending'
            ];
            $inserted = $this->Reservation_model->save_reservation($reservation_data);
            if ($inserted) {
                $this->session->set_flashdata('success', 'Reservasi berhasil diajukan. Menunggu konfirmasi pemilik.');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengajukan reservasi.');
            }
            redirect('dashboard/penyewa');
        }
    }

    public function owner() {
        if ($this->session->userdata('role') != 'pemilik') {
            show_404();
        }
        $user_id = $this->session->userdata('user_id');
        $data['title'] = 'Dashboard Pemilik';
        $kosans = $this->Kosan_model->get_owner_kosans($user_id);
        foreach ($kosans as $kosan) {
            $kosan->photos = $this->Kosan_model->get_photos($kosan->id);
        }
        $data['kosans'] = $kosans;
        $data['reservations'] = $this->Reservation_model->get_owner_reservations($user_id);
        $data['extensions'] = $this->Reservation_model->get_owner_extensions($user_id);
        $data['tenants'] = $this->Reservation_model->get_active_tenants($user_id);
        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/owner', $data);
        $this->load->view('templates/footer');
    }

    public function add_kosan() {
        if ($this->session->userdata('role') != 'pemilik') {
            show_404();
        }
        $data['title'] = 'Tambah Kosan';
        $data['facilities'] = $this->Kosan_model->get_facilities();

        $this->form_validation->set_rules('name', 'Nama Kosan', 'required');
        $this->form_validation->set_rules('address', 'Alamat', 'required');
        $this->form_validation->set_rules('type', 'Tipe Kosan', 'required|in_list[putra,putri,campur]');
        $this->form_validation->set_rules('personality_category', 'Kategori Kepribadian', 'required|in_list[introvert,extrovert,neutral]');
        $this->form_validation->set_rules('price', 'Harga', 'required|numeric');
        $this->form_validation->set_rules('available_rooms', 'Kamar Tersedia', 'required|integer');
        $this->form_validation->set_rules('description', 'Deskripsi', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('dashboard/add_kosan', $data);
            $this->load->view('templates/footer');
        } else {
            $user_id = $this->session->userdata('user_id');
            $kosan_data = [
                'name' => $this->input->post('name'),
                'address' => $this->input->post('address'),
                'google_maps_link' => $this->input->post('google_maps_link'),
                'type' => $this->input->post('type'),
                'personality_category' => $this->input->post('personality_category'),
                'personality_description' => $this->input->post('personality_description'),
                'price' => $this->input->post('price'),
                'available_rooms' => $this->input->post('available_rooms'),
                'description' => $this->input->post('description'),
                'owner_id' => $user_id,
                'status' => 'inactive'
            ];

            $config['upload_path'] = './assets/uploads/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 2048;
            $this->upload->initialize($config);

            $photos = [];
            if (!empty($_FILES['photos']['name'][0])) {
                $files = $_FILES['photos'];
                $count = count($files['name']);
                for ($i = 0; $i < $count; $i++) {
                    $_FILES['photo']['name'] = $files['name'][$i];
                    $_FILES['photo']['type'] = $files['type'][$i];
                    $_FILES['photo']['tmp_name'] = $files['tmp_name'][$i];
                    $_FILES['photo']['error'] = $files['error'][$i];
                    $_FILES['photo']['size'] = $files['size'][$i];

                    if ($this->upload->do_upload('photo')) {
                        $upload_data = $this->upload->data();
                        $photos[] = [
                            'url' => $upload_data['file_name'],
                            'is_primary' => ($i == 0) ? TRUE : FALSE
                        ];
                    } else {
                        $this->session->set_flashdata('error', $this->upload->display_errors());
                        $this->load->view('templates/header', $data);
                        $this->load->view('dashboard/add_kosan', $data);
                        $this->load->view('templates/footer');
                        return;
                    }
                }
            } else {
                $this->session->set_flashdata('error', 'Minimal satu foto harus diunggah.');
                $this->load->view('templates/header', $data);
                $this->load->view('dashboard/add_kosan', $data);
                $this->load->view('templates/footer');
                return;
            }

            $kosan_id = $this->Kosan_model->save_kosan($kosan_data);
            if ($kosan_id) {
                if (!empty($facilities = $this->input->post('facilities'))) {
                    $this->Kosan_model->save_kosan_facilities($kosan_id, $facilities);
                }
                if (!empty($photos)) {
                    $this->Kosan_model->save_photos($kosan_id, $photos);
                }
                $this->session->set_flashdata('success', 'Kosan berhasil ditambahkan. Menunggu verifikasi admin.');
                redirect('dashboard/owner');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan kosan.');
                $this->load->view('templates/header', $data);
                $this->load->view('dashboard/add_kosan', $data);
                $this->load->view('templates/footer');
            }
        }
    }

    public function edit_kosan($id) {
        if ($this->session->userdata('role') != 'pemilik') {
            show_404();
        }
        $user_id = $this->session->userdata('user_id');
        $data['kosan'] = $this->Kosan_model->get_kosan_by_id($id);
        if (!$data['kosan'] || $data['kosan']->owner_id != $user_id) {
            $this->session->set_flashdata('error', 'Kosan tidak ditemukan atau bukan milik Anda.');
            redirect('dashboard/owner');
        }
        $data['title'] = 'Edit Kosan';
        $data['facilities'] = $this->Kosan_model->get_facilities();
        $data['kosan_facilities'] = array_column($this->Kosan_model->get_kosan_facilities($id), 'id');
        $data['photos'] = $this->Kosan_model->get_photos($id);

        $this->form_validation->set_rules('name', 'Nama Kosan', 'required');
        $this->form_validation->set_rules('address', 'Alamat', 'required');
        $this->form_validation->set_rules('type', 'Tipe Kosan', 'required|in_list[putra,putri,campur]');
        $this->form_validation->set_rules('personality_category', 'Kategori Kepribadian', 'required|in_list[introvert,extrovert,neutral]');
        $this->form_validation->set_rules('price', 'Harga', 'required|numeric');
        $this->form_validation->set_rules('available_rooms', 'Kamar Tersedia', 'required|integer');
        $this->form_validation->set_rules('description', 'Deskripsi', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('dashboard/edit_kosan', $data);
            $this->load->view('templates/footer');
        } else {
            $kosan_data = [
                'name' => $this->input->post('name'),
                'address' => $this->input->post('address'),
                'google_maps_link' => $this->input->post('google_maps_link'),
                'type' => $this->input->post('type'),
                'personality_category' => $this->input->post('personality_category'),
                'personality_description' => $this->input->post('personality_description'),
                'price' => $this->input->post('price'),
                'available_rooms' => $this->input->post('available_rooms'),
                'description' => $this->input->post('description'),
                'status' => 'inactive' // Set ke inactive untuk verifikasi ulang
            ];

            $config['upload_path'] = './assets/uploads/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 2048;
            $this->upload->initialize($config);

            $photos = [];
            if (!empty($_FILES['photos']['name'][0])) {
                $files = $_FILES['photos'];
                $count = count($files['name']);
                for ($i = 0; $i < $count; $i++) {
                    $_FILES['photo']['name'] = $files['name'][$i];
                    $_FILES['photo']['type'] = $files['type'][$i];
                    $_FILES['photo']['tmp_name'] = $files['tmp_name'][$i];
                    $_FILES['photo']['error'] = $files['error'][$i];
                    $_FILES['photo']['size'] = $files['size'][$i];

                    if ($this->upload->do_upload('photo')) {
                        $upload_data = $this->upload->data();
                        $photos[] = [
                            'url' => $upload_data['file_name'],
                            'is_primary' => ($i == 0 && empty($data['photos'])) ? TRUE : FALSE
                        ];
                    } else {
                        $this->session->set_flashdata('error', $this->upload->display_errors());
                        $this->load->view('templates/header', $data);
                        $this->load->view('dashboard/edit_kosan', $data);
                        $this->load->view('templates/footer');
                        return;
                    }
                }
            }

            // Hapus foto yang dipilih
            if ($delete_photos = $this->input->post('delete_photos')) {
                foreach ($delete_photos as $photo_id) {
                    $photo = $this->db->get_where('photos', ['id' => $photo_id, 'kosan_id' => $id])->row();
                    if ($photo) {
                        unlink(FCPATH . 'assets/uploads/' . $photo->url);
                        $this->db->delete('photos', ['id' => $photo_id]);
                    }
                }
            }

            // Pastikan ada minimal satu foto
            $existing_photos = $this->Kosan_model->get_photos($id);
            if (empty($photos) && empty($existing_photos)) {
                $this->session->set_flashdata('error', 'Minimal satu foto harus tersedia.');
                $this->load->view('templates/header', $data);
                $this->load->view('dashboard/edit_kosan', $data);
                $this->load->view('templates/footer');
                return;
            }

            $updated = $this->Kosan_model->update_kosan($id, $kosan_data);
            if ($updated) {
                // Update fasilitas
                $this->db->delete('kosan_facilities', ['kosan_id' => $id]);
                if (!empty($facilities = $this->input->post('facilities'))) {
                    $this->Kosan_model->save_kosan_facilities($id, $facilities);
                }
                // Simpan foto baru
                if (!empty($photos)) {
                    $this->Kosan_model->save_photos($id, $photos);
                }
                $this->session->set_flashdata('success', 'Kosan berhasil diperbarui. Menunggu verifikasi admin.');
                redirect('dashboard/owner');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui kosan.');
                $this->load->view('templates/header', $data);
                $this->load->view('dashboard/edit_kosan', $data);
                $this->load->view('templates/footer');
            }
        }
    }

    public function delete_kosan($id) {
        if ($this->session->userdata('role') != 'pemilik') {
            show_404();
        }
        $user_id = $this->session->userdata('user_id');
        $kosan = $this->Kosan_model->get_kosan_by_id($id);
        if (!$kosan || $kosan->owner_id != $user_id) {
            $this->session->set_flashdata('error', 'Kosan tidak ditemukan atau bukan milik Anda.');
            redirect('dashboard/owner');
        }
        $deleted = $this->Kosan_model->delete_kosan($id);
        if ($deleted) {
            $this->session->set_flashdata('success', 'Kosan berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kosan.');
        }
        redirect('dashboard/owner');
    }

    public function view_kosan_owner($id) {
        $user_id = $this->session->userdata('user_id');
        $data['kosan'] = $this->Kosan_model->get_kosan_by_id($id);
        if (!$data['kosan']) {
            $this->session->set_flashdata('error', 'Kosan tidak ditemukan.');
            redirect($this->session->userdata('role') == 'pemilik' ? 'dashboard/owner' : 'dashboard/penyewa');
        }

        if ($this->session->userdata('role') == 'pemilik' && $data['kosan']->owner_id != $user_id) {
            $this->session->set_flashdata('error', 'Kosan bukan milik Anda.');
            redirect('dashboard/owner');
        }

        $data['title'] = 'Detail Kosan';
        $data['photos'] = $this->Kosan_model->get_photos($id);
        $data['facilities'] = $this->Kosan_model->get_kosan_facilities($id);
        if ($this->session->userdata('role') == 'admin') {
            $data['owner'] = $this->User_model->get_user($data['kosan']->owner_id);
            $this->load->view('templates/header', $data);
            $this->load->view('dashboard/view_kosan', $data);
        } else if ($this->session->userdata('role') == 'pemilik') {
            $this->load->view('templates/header', $data);
            $this->load->view('dashboard/owner_view_kosan', $data);
        } else {
            $this->load->view('templates/header', $data);
            $this->load->view('dashboard/penyewa_view_kosan', $data);
        }
        $this->load->view('templates/footer');
    }

    public function approve_reservation($id) {
        if ($this->session->userdata('role') != 'pemilik') {
            show_404();
        }
        $user_id = $this->session->userdata('user_id');
        $reservation = $this->db->select('r.*, u.email, k.name as kosan_name')
                                ->from('reservations r')
                                ->join('users u', 'r.user_id = u.id')
                                ->join('kosans k', 'r.kosan_id = k.id')
                                ->where('r.id', $id)
                                ->get()
                                ->row();
        $updated = $this->Reservation_model->update_reservation_status($id, 'approved', $user_id);
        if ($updated) {
            $this->send_notification_email($reservation->email, 'Reservasi Diterima', 'Reservasi Anda untuk "' . $reservation->kosan_name . '" telah diterima.');
            $this->session->set_flashdata('success', 'Reservasi diterima');
        } else {
            $this->session->set_flashdata('error', 'Gagal menerima reservasi');
        }
        redirect('dashboard/owner');
    }

    public function reject_reservation($id) {
        if ($this->session->userdata('role') != 'pemilik') {
            show_404();
        }
        $user_id = $this->session->userdata('user_id');
        $reservation = $this->db->select('r.*, u.email, k.name as kosan_name')
                                ->from('reservations r')
                                ->join('users u', 'r.user_id = u.id')
                                ->join('kosans k', 'r.kosan_id = k.id')
                                ->where('r.id', $id)
                                ->get()
                                ->row();
        $updated = $this->Reservation_model->update_reservation_status($id, 'rejected', $user_id);
        if ($updated) {
            $this->send_notification_email($reservation->email, 'Reservasi Ditolak', 'Reservasi Anda untuk "' . $reservation->kosan_name . '" telah ditolak.');
            $this->session->set_flashdata('success', 'Reservasi ditolak');
        } else {
            $this->session->set_flashdata('error', 'Gagal menolak reservasi');
        }
        redirect('dashboard/owner');
    }

    public function approve_extension($id) {
        if ($this->session->userdata('role') != 'pemilik') {
            show_404();
        }
        $user_id = $this->session->userdata('user_id');
        $extension = $this->db->select('e.*, u.email, k.name as kosan_name')
                              ->from('extensions e')
                              ->join('reservations r', 'e.reservation_id = r.id')
                              ->join('users u', 'r.user_id = u.id')
                              ->join('kosans k', 'r.kosan_id = k.id')
                              ->where('e.id', $id)
                              ->get()
                              ->row();
        $updated = $this->Reservation_model->update_extension_status($id, 'approved_extension', $user_id);
        if ($updated) {
            $this->send_notification_email($extension->email, 'Perpanjangan Diterima', 'Pengajuan perpanjangan sewa untuk "' . $extension->kosan_name . '" telah diterima.');
            $this->session->set_flashdata('success', 'Perpanjangan diterima');
        } else {
            $this->session->set_flashdata('error', 'Gagal menerima perpanjangan');
        }
        redirect('dashboard/owner');
    }

    public function reject_extension($id) {
        if ($this->session->userdata('role') != 'pemilik') {
            show_404();
        }
        $user_id = $this->session->userdata('user_id');
        $extension = $this->db->select('e.*, u.email, k.name as kosan_name')
                              ->from('extensions e')
                              ->join('reservations r', 'e.reservation_id = r.id')
                              ->join('users u', 'r.user_id = u.id')
                              ->join('kosans k', 'r.kosan_id = k.id')
                              ->where('e.id', $id)
                              ->get()
                              ->row();
        $updated = $this->Reservation_model->update_extension_status($id, 'rejected_extension', $user_id);
        if ($updated) {
            $this->send_notification_email($extension->email, 'Perpanjangan Ditolak', 'Pengajuan perpanjangan sewa untuk "' . $extension->kosan_name . '" telah ditolak.');
            $this->session->set_flashdata('success', 'Perpanjangan ditolak');
        } else {
            $this->session->set_flashdata('error', 'Gagal menolak perpanjangan');
        }
        redirect('dashboard/owner');
    }

    public function admin() {
        if ($this->session->userdata('role') != 'admin') {
            show_404();
        }
        $data['title'] = 'Dashboard Admin';
        // Ambil semua kosan dengan filter opsional
        $status = $this->input->get('status');
        $data['kosans'] = $this->Kosan_model->get_all_kosans($status);
        // Ambil statistik
        $data['stats'] = [
            'total_kosans' => $this->Kosan_model->count_kosans(),
            'active_kosans' => $this->Kosan_model->count_kosans('active'),
            'inactive_kosans' => $this->Kosan_model->count_kosans('inactive'),
            'total_users' => $this->User_model->count_users(),
            'pemilik_count' => $this->User_model->count_users('pemilik'),
            'penyewa_count' => $this->User_model->count_users('penyewa')
        ];
        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/admin', $data);
        $this->load->view('templates/footer');
    }

    public function view_kosan($id) {
        if ($this->session->userdata('role') != 'admin') {
            show_404();
        }
        $data['title'] = 'Detail Kosan';
        $data['kosan'] = $this->Kosan_model->get_kosan_by_id($id);
        if (!$data['kosan']) {
            $this->session->set_flashdata('error', 'Kosan tidak ditemukan');
            redirect('dashboard/admin');
        }
        $data['photos'] = $this->Kosan_model->get_photos($id);
        $data['facilities'] = $this->Kosan_model->get_kosan_facilities($id);
        $data['owner'] = $this->User_model->get_user($data['kosan']->owner_id);
        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/view_kosan', $data);
        $this->load->view('templates/footer');
    }

    public function verify_kosan($id) {
        if ($this->session->userdata('role') != 'admin') {
            show_404();
        }
        $kosan = $this->Kosan_model->get_kosan_by_id($id);
        $updated = $this->Kosan_model->update_kosan_status($id, 'active');
        if ($updated) {
            // Kirim email ke pemilik
            $owner = $this->User_model->get_user($kosan->owner_id);
            $this->send_notification_email($owner->email, 'Kosan Diverifikasi', 'Kosan Anda "' . $kosan->name . '" telah diverifikasi dan aktif.');
            $this->session->set_flashdata('success', 'Kosan berhasil diverifikasi');
        } else {
            $this->session->set_flashdata('error', 'Gagal memverifikasi kosan');
        }
        redirect('dashboard/admin');
    }

    public function reject_kosan($id) {
        if ($this->session->userdata('role') != 'admin') {
            show_404();
        }
        $kosan = $this->Kosan_model->get_kosan_by_id($id);
        $updated = $this->Kosan_model->update_kosan_status($id, 'rejected');
        if ($updated) {
            $owner = $this->User_model->get_user($kosan->owner_id);
            $this->send_notification_email($owner->email, 'Kosan Ditolak', 'Kosan Anda "' . $kosan->name . '" ditolak. Silakan hubungi admin untuk detail.');
            $this->session->set_flashdata('success', 'Kosan ditolak');
        } else {
            $this->session->set_flashdata('error', 'Gagal menolak kosan');
        }
        redirect('dashboard/admin');
    }

    private function send_notification_email($to, $subject, $message) {
        $this->load->library('email');
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 587,
            'smtp_user' => 'kingidrismantap@gmail.com',
            'smtp_pass' => 'your_app_password',
            'mailtype' => 'text',
            'charset' => 'utf-8'
        ];
        $this->email->initialize($config);
        $this->email->from('kingidrismantap@gmail.com', 'Kosan App');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        if (!$this->email->send()) {
            log_message('error', 'Email failed: ' . $this->email->print_debugger());
        }
    }

    public function manage_users() {
        if ($this->session->userdata('role') != 'admin') {
            show_404();
        }
        $data['title'] = 'Manajemen Pengguna';
        $data['users'] = $this->User_model->get_all_users();
        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/manage_users', $data);
        $this->load->view('templates/footer');
    }
    
    public function edit_user($id) {
        if ($this->session->userdata('role') != 'admin') {
            show_404();
        }
        $data['title'] = 'Edit Pengguna';
        $data['user'] = $this->User_model->get_user($id);
        if (!$data['user']) {
            $this->session->set_flashdata('error', 'Pengguna tidak ditemukan');
            redirect('dashboard/manage_users');
        }
    
        $this->form_validation->set_rules('name', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Telepon', 'required');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[penyewa,pemilik,admin]');
    
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('dashboard/edit_user', $data);
            $this->load->view('templates/footer');
        } else {
            $user_data = [
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'role' => $this->input->post('role')
            ];
            if ($this->input->post('password')) {
                $user_data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            }
            $updated = $this->User_model->update_user($id, $user_data);
            if ($updated) {
                $this->session->set_flashdata('success', 'Pengguna berhasil diperbarui');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui pengguna');
            }
            redirect('dashboard/manage_users');
        }
    }
    
    public function delete_user($id) {
        if ($this->session->userdata('role') != 'admin') {
            show_404();
        }
        $deleted = $this->User_model->delete_user($id);
        if ($deleted) {
            $this->session->set_flashdata('success', 'Pengguna berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus pengguna');
        }
        redirect('dashboard/manage_users');
    }
    public function extend_reservation($reservation_id) {
        if ($this->session->userdata('role') != 'penyewa') {
            show_404();
        }
        $user_id = $this->session->userdata('user_id');
        $reservation = $this->Reservation_model->get_reservation_by_id($reservation_id);
        if (!$reservation || $reservation->user_id != $user_id || $reservation->status != 'approved') {
            $this->session->set_flashdata('error', 'Reservasi tidak ditemukan atau tidak dapat diperpanjang.');
            redirect('dashboard/penyewa');
        }
    
        $current_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime($reservation->move_in_date . " +{$reservation->duration} months"));
        $existing_extension = $this->db->where('reservation_id', $reservation_id)
                                       ->where('status', 'pending_extension')
                                       ->get('extensions')
                                       ->row();
        if ($current_date > $end_date || $existing_extension) {
            $this->session->set_flashdata('error', 'Reservasi sudah berakhir atau sudah ada pengajuan perpanjangan.');
            redirect('dashboard/penyewa');
        }
    
        $data['title'] = 'Perpanjang Sewa';
        $data['reservation'] = $reservation;
        $data['kosan'] = $this->Kosan_model->get_kosan($reservation->kosan_id);
    
        $this->form_validation->set_rules('extended_duration', 'Durasi Perpanjangan', 'required|integer|greater_than[0]');
    
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('dashboard/extend_reservation', $data);
            $this->load->view('templates/footer');
        } else {
            $extension_data = [
                'reservation_id' => $reservation_id,
                'user_id' => $user_id, // Tambahkan user_id di sini
                'kosan_id' => $reservation->kosan_id,
                
                'extended_duration' => $this->input->post('extended_duration'),
                'extension_start_date' => date('Y-m-d', strtotime($end_date . " +1 day")),
                'status' => 'pending_extension'
            ];
            $inserted = $this->Reservation_model->save_extension($extension_data);
            if ($inserted) {
                $this->session->set_flashdata('success', 'Pengajuan perpanjangan berhasil diajukan. Menunggu konfirmasi pemilik.');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengajukan perpanjangan.');
            }
            redirect('dashboard/penyewa');
        }
    }
}