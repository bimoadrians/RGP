<?php
namespace App\Controllers\Admin;

date_default_timezone_set("Asia/Jakarta");

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use App\Models\Detail_Pengguna_Model;
use App\Models\PenggunaModel;
use App\Models\EmailDelegasiModel;
use App\Models\BagianModel;
use App\Models\JabatanModel;
use App\Models\PersetujuanModel;
use App\Models\NegaraModel;
use App\Models\KotaModel;
use App\Models\PoolModel;
use App\Models\VendorModel;
use App\Models\PemberhentianModel;
use App\Models\MessModel;
use App\Models\HotelModel;
use App\Models\DetailHotelModel;
use App\Models\MobilModel;
use App\Models\PengemudiModel;
use App\Models\TujuanModel;
use App\Models\JenisBBMModel;
use App\Models\JenisKendaraanModel;
use App\Models\JenisSopirModel;
use App\Models\TransModel;
use App\Models\TiketModel;
use App\Models\AkomodasiModel;
use App\Models\TransportasiModel;
use App\Models\ETiketModel;
use App\Models\EAkomodasiModel;
use App\Models\ETransportasiModel;

class Master extends BaseController
{
    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $session = \Config\Services::session();

        $this->m_detail_pengguna = new Detail_Pengguna_Model();
        $this->m_pengguna = new PenggunaModel();
        $this->m_email_delegasi = new EmailDelegasiModel();
        $this->m_bagian = new BagianModel();
        $this->m_jabatan = new JabatanModel();
        $this->m_persetujuan = new PersetujuanModel();
        $this->m_negara = new NegaraModel();
        $this->m_kota = new KotaModel();
        $this->m_pool = new PoolModel();
        $this->m_vendor = new VendorModel();
        $this->m_pemberhentian = new PemberhentianModel();
        $this->m_mess = new MessModel();
        $this->m_hotel = new HotelModel();
        $this->m_detail_hotel = new DetailHotelModel();
        $this->m_mobil = new MobilModel();
        $this->m_pengemudi = new PengemudiModel();
        $this->m_tujuan = new TujuanModel();
        $this->m_jenis_bbm = new JenisBBMModel();
        $this->m_jenis_kendaraan = new JenisKendaraanModel();
        $this->m_jenis_sopir = new JenisSopirModel();
        $this->m_trans = new TransModel();
        $this->m_tiket = new TiketModel();
        $this->m_akomodasi = new AkomodasiModel();
        $this->m_transportasi = new TransportasiModel();
        $this->m_e_tiket = new ETiketModel();
        $this->m_e_akomodasi = new EAkomodasiModel();
        $this->m_e_transportasi = new ETransportasiModel();

        helper('global_fungsi_helper');
        helper('url');
    }

    public function dept()
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('id_bagian')) {
            $bagian_id = $this->m_bagian->bagian_id($this->request->getVar('id_bagian'));
            if($bagian_id['id_bagian']) {//memastikan bahwa ada data
                $aksi = $this->m_bagian->delete_bagian($this->request->getVar('id_bagian'));
                if($aksi == true) {
                    $this->m_bagian->query('ALTER TABLE bagian AUTO_INCREMENT 1');
                    $this->m_jabatan->query('ALTER TABLE jabatan AUTO_INCREMENT 1');
                    session()->setFlashdata('success', "Bagian berhasil dihapus");
                } else {
                    session()->setFlashdata('warning', ['Bagian gagal dihapus']);
                }
            }
            return redirect()->to("dept");
        }

        if($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('id_persetujuan')) {
            $persetujuan_id = $this->m_persetujuan->persetujuan_id($this->request->getVar('id_persetujuan'));
            if($persetujuan_id['id_persetujuan']) {//memastikan bahwa ada data
                $aksi = $this->m_persetujuan->delete_persetujuan($this->request->getVar('id_persetujuan'));
                if($aksi == true) {
                    $this->m_persetujuan->query('ALTER TABLE persetujuan AUTO_INCREMENT 1');
                    session()->setFlashdata('success', "Persetujuan berhasil dihapus");
                } else {
                    session()->setFlashdata('warning', ['Persetujuan gagal dihapus']);
                }
            }
            return redirect()->to("dept");
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            if(!empty($this->request->getVar('bagian'))) {
                $aturan = [
                    'bagian' => [
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Nama Bagian harus diisi'
                        ]
                    ],
                ];
                if(!$this->validate($aturan)) {
                    session()->setFlashdata('warning', $this->validation->getErrors());
                } else {
                    $cek_bagian = $this->m_bagian->where('nama_bagian', $this->request->getVar('bagian'))->select('id_bagian')->first();
                    if ($cek_bagian) {
                        session()->setFlashdata('warning', ['Tidak dapat menambah data karena nama Bagian telah ada di dalam database']);
                        return redirect()->to('dept');
                    } else {
                        $record = [
                            'nama_bagian' => ucwords($this->request->getVar('bagian')),
                            'tgl_input' => date('Ymd'),
                        ];
    
                        $aksi = $this->m_bagian->insert($record);
    
                        if($aksi != false) { //dibagian aksi tidak false atau ada isinya
                            $page_id = $aksi;
                            session()->setFlashdata('success', 'Bagian berhasil ditambahkan');
                            return redirect()->to('dept');
                        } else {
                            session()->setFlashdata('warning', ['Bagian gagal ditambahkan']);
                            return redirect()->to('dept');
                        }
                    }
                }
            } else {
                $aturan = [
                    'jabatan_atasan' => [
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Jabatan Atasan harus diisi'
                        ]
                    ],
                    'jabatan_bawahan' => [
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Jabatan Bawahan harus diisi'
                        ]
                    ],
                ];
                if(!$this->validate($aturan)) {
                    session()->setFlashdata('warning', $this->validation->getErrors());
                } else {
                    $id_atasan = $this->m_jabatan->where('nama_jabatan', $this->request->getVar('jabatan_atasan'))->select('id_jabatan')->first();
                    $id_bawahan = $this->m_jabatan->where('nama_jabatan', $this->request->getVar('jabatan_bawahan'))->select('id_jabatan')->first();

                    $record = [
                        'id_atasan' => $id_atasan,
                        'id_bawahan' => $id_bawahan,
                        'tgl_input' => date('Ymd'),
                    ];

                    $aksi = $this->m_persetujuan->insert($record);

                    if($aksi != false) { //dibagian aksi tidak false atau ada isinya
                        $page_id = $aksi;
                        session()->setFlashdata('success', 'Persetujuan berhasil ditambahkan');
                        return redirect()->to('dept');
                    } else {
                        session()->setFlashdata('warning', ['Persetujuan gagal ditambahkan']);
                        return redirect()->to('dept');
                    }
                }
            }
        }

        $bagian = $this->m_bagian->select('id_bagian, nama_bagian')->orderBy('nama_bagian', 'asc')->findAll();
        $persetujuan = $this->m_persetujuan->select('id_persetujuan')->findAll();
        $jabatan = $this->m_jabatan->select('id_jabatan, nama_jabatan')->findAll();

        $jabatan_atasan = $this->m_persetujuan->join('jabatan', 'jabatan.id_jabatan = persetujuan.id_atasan')->select('id_persetujuan, nama_jabatan as jabatan_atasan')->orderBy('id_atasan', 'asc')->findAll();
        $jabatan_bawahan = $this->m_persetujuan->join('jabatan', 'jabatan.id_jabatan = persetujuan.id_bawahan')->select('id_persetujuan, nama_jabatan as jabatan_bawahan')->orderBy('id_atasan', 'asc')->findAll();

        $data = [
            'bagian' => $bagian,
            'jabatan_atasan' => $jabatan_atasan,
            'jabatan_bawahan' => $jabatan_bawahan,
            'jabatan' => $jabatan,
            'persetujuan' => $persetujuan,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_dept', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function edit_dept($id_bagian)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            // $cek_bagian = $this->m_bagian->where('nama_bagian', $this->request->getVar('bagian'))->select('id_bagian')->first();
            // if ($cek_bagian) {
            //     session()->setFlashdata('warning', ['Tidak dapat melakukan edit karena nama Bagian telah ada di dalam database']);
            //     return redirect()->to('dept');
            // } else {
                
            // }
            $record = [
                'id_bagian' => $id_bagian,
                'nama_bagian' => ucwords($this->request->getVar('bagian')),
                'tgl_input' => date('Ymd'),
            ];
            $this->m_bagian->save($record);

            session()->setFlashdata('success', 'Data Bagian berhasil di edit');
            return redirect()->to('dept');
        }

        $bagian = $this->m_bagian->where('id_bagian', $id_bagian)->select('id_bagian, nama_bagian')->findAll();

        $data = [
            'bagian' => $bagian,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_edit_dept', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function edit_persetujuan($id_persetujuan)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view

            $id_atasan = $this->m_jabatan->where('nama_jabatan', $this->request->getVar('jabatan_atasan'))->select('id_jabatan')->first();
            $id_bawahan = $this->m_jabatan->where('nama_jabatan', $this->request->getVar('jabatan_bawahan'))->select('id_jabatan')->first();

            $record = [
                'id_atasan' => $id_atasan,
                'id_bawahan' => $id_bawahan,
                'id_persetujuan' => $id_persetujuan,
                'tgl_input' => date('Ymd'),
            ];
            $this->m_persetujuan->save($record);

            session()->setFlashdata('success', 'Data Persetujuan berhasil di edit');
            return redirect()->to('dept');
        }

        $persetujuan = $this->m_persetujuan->where('id_persetujuan', $id_persetujuan)->select('id_persetujuan')->findAll();
        $jabatan_atasan = $this->m_persetujuan->where('id_persetujuan', $id_persetujuan)->join('jabatan', 'jabatan.id_jabatan = persetujuan.id_atasan')->select('id_persetujuan, nama_jabatan as jabatan_atasan')->orderBy('id_atasan', 'asc')->findAll();
        $jabatan_bawahan = $this->m_persetujuan->where('id_persetujuan', $id_persetujuan)->join('jabatan', 'jabatan.id_jabatan = persetujuan.id_bawahan')->select('id_persetujuan, nama_jabatan as jabatan_bawahan')->orderBy('id_atasan', 'asc')->findAll();
        $jabatan = $this->m_jabatan->select('id_jabatan, nama_jabatan')->findAll();

        $data = [
            'jabatan_atasan' => $jabatan_atasan,
            'jabatan_bawahan' => $jabatan_bawahan,
            'jabatan' => $jabatan,
            'persetujuan' => $persetujuan,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_edit_persetujuan', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function jabt($id_bagian)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('id_jabatan')) {
            $jabatan_id = $this->m_jabatan->jabatan_id($this->request->getVar('id_jabatan'));
            if($jabatan_id['id_jabatan']) {//memastikan bahwa ada data
                $aksi = $this->m_jabatan->delete_jabatan($this->request->getVar('id_jabatan'));
                if($aksi == true) {
                    $this->m_jabatan->query('ALTER TABLE jabatan AUTO_INCREMENT 1');
                    session()->setFlashdata('success', "Jabatan berhasil dihapus");
                } else {
                    session()->setFlashdata('warning', ['Jabatan gagal dihapus']);
                }
            }
            return redirect()->to('jabt/'.$id_bagian);
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            $aturan = [
                'jabatan' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama Jabatan harus diisi'
                    ]
                ],
            ];
            if(!$this->validate($aturan)) {
                session()->setFlashdata('warning', $this->validation->getErrors());
            } else {
                $cek_jabatan = $this->m_jabatan->where('nama_jabatan', $this->request->getVar('jabatan'))->select('id_jabatan')->first();
                if ($cek_jabatan) {
                    session()->setFlashdata('warning', ['Tidak dapat menambah data karena nama Jabatan telah ada di dalam database']);
                    return redirect()->to('jabt/'.$id_bagian);
                } else {
                    $record = [
                        'id_bagian' => $id_bagian,
                        'nama_jabatan' => ucwords($this->request->getVar('jabatan')),
                        'bts_eval' => $this->request->getVar('bts_eval'),
                        'tgl_input' => date('Ymd'),
                    ];
                    $aksi = $this->m_jabatan->insert($record);
    
                    if($aksi != false) { //dibagian aksi tidak false atau ada isinya
                        $page_id = $aksi;
                        session()->setFlashdata('success', 'Jabatan berhasil ditambahkan');
                        return redirect()->to('jabt/'.$id_bagian);
                    } else {
                        session()->setFlashdata('warning', ['Jabatan gagal ditambahkan']);
                        return redirect()->to('jabt/'.$id_bagian);
                    }
                }
            }
        }

        $jabatan = $this->m_jabatan->where('id_bagian', $id_bagian)->select('id_jabatan, nama_jabatan, bts_eval')->orderBy('nama_jabatan', 'asc')->findAll();

        $data = [
            'jabatan' => $jabatan,
            'id_bagian' => $id_bagian,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_jabt', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function edit_jabt($id_bagian, $id_jabatan)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            $record = [
                'id_jabatan' => $id_jabatan,
                'nama_jabatan' => ucwords($this->request->getVar('jabatan')),
                'bts_eval' => $this->request->getVar('bts_eval'),
                'tgl_input' => date('Ymd'),
            ];
            $this->m_jabatan->save($record);

            session()->setFlashdata('success', 'Data Jabatan berhasil di edit');
            return redirect()->to('jabt/'.$id_bagian);
        }

        $jabatan = $this->m_jabatan->where('id_jabatan', $id_jabatan)->select('id_jabatan, nama_jabatan, bts_eval')->findAll();

        $data = [
            'jabatan' => $jabatan,
            'id_bagian' => $id_bagian,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_edit_jabt', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function negara()
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('id_negara')) {
            $negara_id = $this->m_negara->negara_id($this->request->getVar('id_negara'));
            if($negara_id['id_negara']) {//memastikan bahwa ada data
                $aksi = $this->m_negara->delete_negara($this->request->getVar('id_negara'));
                if($aksi == true) {
                    $this->m_negara->query('ALTER TABLE negara AUTO_INCREMENT 1');
                    $this->m_kota->query('ALTER TABLE kota AUTO_INCREMENT 1');
                    session()->setFlashdata('success', "Negara berhasil dihapus");
                } else {
                    session()->setFlashdata('warning', ['Negara gagal dihapus']);
                }
            }
            return redirect()->to("negara");
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            $aturan = [
                'negara' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama Negara harus diisi'
                    ]
                ],
            ];
            if(!$this->validate($aturan)) {
                session()->setFlashdata('warning', $this->validation->getErrors());
            } else {
                $cek_negara = $this->m_negara->where('nama_negara', $this->request->getVar('negara'))->select('id_negara')->first();
                if ($cek_negara) {
                    session()->setFlashdata('warning', ['Tidak dapat menambah data karena nama Negara telah ada di dalam database']);
                    return redirect()->to('negara');
                } else {
                    $record = [
                        'nama_negara' => ucwords($this->request->getVar('negara')),
                        'tgl_input' => date('Ymd'),
                    ];
                    $aksi = $this->m_negara->insert($record);

                    if($aksi != false) { //dibagian aksi tidak false atau ada isinya
                        $page_id = $aksi;
                        session()->setFlashdata('success', 'Negara berhasil ditambahkan');
                        return redirect()->to('negara');
                    } else {
                        session()->setFlashdata('warning', ['Negara gagal ditambahkan']);
                        return redirect()->to('negara');
                    }
                }
            }
        }

        $negara = $this->m_negara->select('id_negara, nama_negara')->orderBy('nama_negara', 'asc')->findAll();

        $data = [
            'negara' => $negara,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_negara', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function edit_negara($id_negara)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            $record = [
                'id_negara' => $id_negara,
                'nama_negara' => ucwords($this->request->getVar('negara')),
                'tgl_input' => date('Ymd'),
            ];
            $this->m_negara->save($record);

            session()->setFlashdata('success', 'Data Negara berhasil di edit');
            return redirect()->to('negara');
        }

        $negara = $this->m_negara->where('id_negara', $id_negara)->select('id_negara, nama_negara')->findAll();

        $data = [
            'negara' => $negara,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_edit_negara', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function kota($id_negara)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('id_kota')) {
            $kota_id = $this->m_kota->kota_id($this->request->getVar('id_kota'));
            if($kota_id['id_kota']) {//memastikan bahwa ada data
                $aksi = $this->m_kota->delete_kota($this->request->getVar('id_kota'));
                if($aksi == true) {
                    $this->m_kota->query('ALTER TABLE kota AUTO_INCREMENT 1');
                    session()->setFlashdata('success', "Kota berhasil dihapus");
                } else {
                    session()->setFlashdata('warning', ['Kota gagal dihapus']);
                }
            }
            return redirect()->to('kota/'.$id_negara);
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            $aturan = [
                'nama_kota' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama Kota harus diisi'
                    ]
                ],
            ];
            if(!$this->validate($aturan)) {
                session()->setFlashdata('warning', $this->validation->getErrors());
            } else {
                $cek_kota = $this->m_kota->where('nama_kota', $this->request->getVar('nama_kota'))->select('id_kota')->first();
                if ($cek_kota) {
                    session()->setFlashdata('warning', ['Tidak dapat menambah data karena nama Kota telah ada di dalam database']);
                    return redirect()->to('kota/'.$id_negara);
                } else {
                    $id_pool = $this->m_pool->where('nama_pool', $this->request->getVar('nama_pool'))->select('id_pool')->first();

                    $record = [
                        'id_negara' => $id_negara,
                        'nama_kota' => ucwords($this->request->getVar('nama_kota')),
                        'id_pool' => $id_pool,
                        'tgl_input' => date('Ymd'),
                    ];
                    $aksi = $this->m_kota->insert($record);

                    if($aksi != false) { //dibagian aksi tidak false atau ada isinya
                        $page_id = $aksi;
                        session()->setFlashdata('success', 'Kota berhasil ditambahkan');
                        return redirect()->to('kota/'.$id_negara);
                    } else {
                        session()->setFlashdata('warning', ['Kota gagal ditambahkan']);
                        return redirect()->to('kota/'.$id_negara);
                    }
                }
            }
        }

        $kota = $this->m_kota->where('id_negara', $id_negara)->select('id_kota, id_pool, nama_kota')->orderBy('nama_kota', 'asc')->findAll();
        $pool = $this->m_pool->select('nama_pool')->orderBy('nama_pool', 'asc')->findAll();
        $pool_kota = $this->m_kota->join('pool', 'pool.id_pool = kota.id_pool', 'left')->select('id_kota, kota.id_pool, nama_pool')->findAll();

        $data = [
            'kota' => $kota,
            'id_negara' => $id_negara,
            'pool' => $pool,
            'pool_kota' => $pool_kota,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_kota', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function edit_kota($id_negara, $id_kota)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            
            $id_pool = $this->m_pool->where('nama_pool', $this->request->getVar('nama_pool'))->select('id_pool')->first();

            $record = [
                'id_kota' => $id_kota,
                'nama_kota' => ucwords($this->request->getVar('nama_kota')),
                'id_pool' => $id_pool,
                'tgl_input' => date('Ymd'),
            ];
            $this->m_kota->save($record);

            session()->setFlashdata('success', 'Data Kota berhasil di edit');
            return redirect()->to('kota/'.$id_negara);
        }

        $kota = $this->m_kota->where('id_kota', $id_kota)->select('id_kota, id_pool, nama_kota')->findAll();
        $pool = $this->m_pool->select('nama_pool')->findAll();
        $pool_kota = $this->m_kota->join('pool', 'pool.id_pool = kota.id_pool', 'left')->select('id_kota, kota.id_pool, nama_pool')->findAll();

        $data = [
            'kota' => $kota,
            'id_negara' => $id_negara,
            'pool' => $pool,
            'pool_kota' => $pool_kota,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_edit_kota', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function vendor()
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('id_vendor')) {
            $vendor_id = $this->m_vendor->vendor_id($this->request->getVar('id_vendor'));
            if($vendor_id['id_vendor']) {//memastikan bahwa ada data
                $aksi = $this->m_vendor->delete_vendor($this->request->getVar('id_vendor'));
                if($aksi == true) {
                    $this->m_vendor->query('ALTER TABLE vendor AUTO_INCREMENT 1');
                    session()->setFlashdata('success', "Vendor berhasil dihapus");
                } else {
                    session()->setFlashdata('warning', ['Vendor gagal dihapus']);
                }
            }
            return redirect()->to('vendo');
        }

        if($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('id_pemberhentian')) {
            $pemberhentian_id = $this->m_pemberhentian->pemberhentian_id($this->request->getVar('id_pemberhentian'));
            if($pemberhentian_id['id_pemberhentian']) {//memastikan bahwa ada data
                $aksi = $this->m_pemberhentian->delete_pemberhentian($this->request->getVar('id_pemberhentian'));
                if($aksi == true) {
                    $this->m_pemberhentian->query('ALTER TABLE pemberhentian AUTO_INCREMENT 1');
                    session()->setFlashdata('success', "Pemberhentian berhasil dihapus");
                } else {
                    session()->setFlashdata('warning', ['Pemberhentian gagal dihapus']);
                }
            }
            return redirect()->to('vendo');
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            if(!empty($this->request->getVar('nama_vendor'))) {
                $aturan = [
                    'nama_vendor' => [
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Nama Vendor harus diisi'
                        ]
                    ],
                ];
                if(!$this->validate($aturan)) {
                    session()->setFlashdata('warning', $this->validation->getErrors());
                } else {
                    $cek_vendor = $this->m_vendor->where('nama_vendor', $this->request->getVar('nama_vendor'))->select('id_vendor')->first();
                    if ($cek_vendor) {
                        session()->setFlashdata('warning', ['Tidak dapat menambah data karena nama Vendor telah ada di dalam database']);
                        return redirect()->to('vendo');
                    } else {
                        $record = [
                            'jenis_vendor' => ucwords($this->request->getVar('jenis_vendor')),
                            'nama_vendor' => ucwords($this->request->getVar('nama_vendor')),
                            'tgl_input' => date('Ymd'),
                        ];
                        $aksi = $this->m_vendor->insert($record);
    
                        if($aksi != false) { //dibagian aksi tidak false atau ada isinya
                            $page_id = $aksi;
                            session()->setFlashdata('success', 'Vendor berhasil ditambahkan');
                            return redirect()->to('vendo');
                        } else {
                            session()->setFlashdata('warning', ['Vendor gagal ditambahkan']);
                            return redirect()->to('vendo');
                        }
                    }
                }
            } else if(!empty($this->request->getVar('nama_pemberhentian'))) {
                $aturan = [
                    'nama_pemberhentian' => [
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Nama Pemberhentian harus diisi'
                        ]
                    ],
                ];
                if(!$this->validate($aturan)) {
                    session()->setFlashdata('warning', $this->validation->getErrors());
                } else {
                    $cek_pemberhentian = $this->m_pemberhentian->where('nama_pemberhentian', $this->request->getVar('nama_pemberhentian'))->select('id_pemberhentian')->first();
                    if ($cek_pemberhentian) {
                        session()->setFlashdata('warning', ['Tidak dapat menambah data karena nama Pemberhentian telah ada di dalam database']);
                        return redirect()->to('vendo');
                    } else {
                        $record = [
                            'jenis_pemberhentian' => ucwords($this->request->getVar('jenis_pemberhentian')),
                            'nama_pemberhentian' => ucwords($this->request->getVar('nama_pemberhentian')),
                            'nama_kota' => ucwords($this->request->getVar('nama_kota')),
                            'tgl_input' => date('Ymd'),
                        ];
                        $aksi = $this->m_pemberhentian->insert($record);

                        if($aksi != false) { //dibagian aksi tidak false atau ada isinya
                            $page_id = $aksi;
                            session()->setFlashdata('success', 'Pemberhentian berhasil ditambahkan');
                            return redirect()->to('vendo');
                        } else {
                            session()->setFlashdata('warning', ['Pemberhentian gagal ditambahkan']);
                            return redirect()->to('vendo');
                        }
                    }
                }
            }
        }

        $vendor = $this->m_vendor->select('id_vendor, jenis_vendor, nama_vendor')->orderBy('jenis_vendor', 'asc')->orderBy('nama_vendor', 'asc')->findAll();
        $pemberhentian = $this->m_pemberhentian->select('id_pemberhentian, jenis_pemberhentian, nama_pemberhentian, nama_kota')->orderBy('jenis_pemberhentian', 'asc')->orderBy('nama_pemberhentian', 'asc')->findAll();
        $kota = $this->m_kota->select('nama_kota')->orderBy('nama_kota', 'asc')->findAll();

        $data = [
            'kota' => $kota,
            'pemberhentian' => $pemberhentian,
            'vendor' => $vendor,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_vendor', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function edit_vendor($id_vendor)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view

            $record = [
                'id_vendor' => $id_vendor,
                'jenis_vendor' => ucwords($this->request->getVar('jenis_vendor')),
                'nama_vendor' => ucwords($this->request->getVar('nama_vendor')),
                'tgl_input' => date('Ymd'),
            ];
            $aksi = $this->m_vendor->save($record);

            session()->setFlashdata('success', 'Data Vendor berhasil di edit');
            return redirect()->to('vendo');
        }

        $vendor = $this->m_vendor->where('id_vendor', $id_vendor)->select('id_vendor, jenis_vendor, nama_vendor')->findAll();

        $data = [
            'vendor' => $vendor,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_edit_vendor', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function edit_pemberhentian($id_pemberhentian)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view

            $record = [
                'id_pemberhentian' => $id_pemberhentian,
                'jenis_pemberhentian' => ucwords($this->request->getVar('jenis_pemberhentian')),
                'nama_pemberhentian' => ucwords($this->request->getVar('nama_pemberhentian')),
                'nama_kota' => ucwords($this->request->getVar('nama_kota')),
                'tgl_input' => date('Ymd'),
            ];
            $aksi = $this->m_pemberhentian->save($record);

            session()->setFlashdata('success', 'Data Pemberhentian berhasil di edit');
            return redirect()->to('vendo');
        }

        $pemberhentian = $this->m_pemberhentian->where('id_pemberhentian', $id_pemberhentian)->select('id_pemberhentian, jenis_pemberhentian, nama_pemberhentian, nama_kota')->findAll();
        $kota = $this->m_kota->select('nama_kota')->orderBy('nama_kota', 'asc')->findAll();

        $data = [
            'kota' => $kota,
            'pemberhentian' => $pemberhentian,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_edit_pemberhentian', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function hotel_user()
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 0) {

        } else if ($admin_gs == 1) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('dept');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        $mess = $this->m_mess->join('hotel', 'hotel.id_hotel = mess_kx_jkt.id_hotel', 'left')->join('akomodasi', 'akomodasi.id_hotel = mess_kx_jkt.id_hotel', 'left')->where('nama_kamar', 'mess')->where('status_mess', 1)->select('id_akomodasi, jumlah_kamar, tanggal_jam_keluar, kapasitas_kamar, terpakai')->findAll();
        
        $sum = 0;
        foreach ($mess as $m => $mes) {
            $jam_keluar = (strtotime($mes['tanggal_jam_keluar']));
            
            $sum += $mes['jumlah_kamar'];

            if ($mes['terpakai'] == 0) {
                
            } else {
                if($time == $jam_keluar || $time > $jam_keluar){
                    $terpakai = [
                        'id_mess' => 8,
                        'terpakai' => $mes['terpakai'] - $sum,
                        'edited_at' => $timestamp,
                    ];

                    $akomodasi = [
                        'id_akomodasi' => $mes['id_akomodasi'],
                        'status_mess' => 0,
                    ];
                    $this->m_akomodasi->save($akomodasi);
                }
            }
        }

        if (empty($terpakai)) {
            
        } else {
            $this->m_mess->save($terpakai);
        }

        $eval_tiket_auto = $this->m_tiket->where('tanggal_jam_tiket <', $timestamp)->where('kirim_eval', 0)->where('batal_tiket', 0)->where('status_tiket', 1)->select('id_trans, id_tiket, tanggal_jam_tiket')->findAll();
        
        foreach ($eval_tiket_auto as $eta => $evtik) {
            $tiga_hari = (strtotime($evtik['tanggal_jam_tiket']));

            if ($time - $tiga_hari >= 259200) {
                $id_detail_pengguna = $this->m_trans->where('id_trans', $evtik['id_trans'])->select('id_detail_pengguna')->findAll();

                foreach ($id_detail_pengguna as $idp => $detail_peng) {
                    $record = [
                        'id_trans' => $evtik['id_trans'],
                        'id_tiket' => $evtik['id_tiket'],
                        'id_detail_pengguna' => $detail_peng['id_detail_pengguna'],
                        'a1_nilai' => 3,
                        'b1_nilai' => 3,
                        'c1_nilai' => 3,
                        'd1_nilai' => 3,
                        'komentar' => null,
                        'status' => 1,
                        'tgl_input' => date('Ymd'),
                    ];
        
                    $tiket = [
                        'id_tiket' => $evtik['id_tiket'],
                        'kirim_eval' => 1,
                        'edited_at' => $timestamp,
                    ];
        
                    $this->m_e_tiket->insert($record);
                    $this->m_tiket->save($tiket);
                }
            }
        }

        $eval_akomodasi_auto = $this->m_akomodasi->where('tanggal_jam_keluar <', $timestamp)->where('kirim_eval', 0)->where('batal_akomodasi', 0)->where('status_akomodasi', 1)->select('id_trans, id_akomodasi, tanggal_jam_keluar')->findAll();
        
        foreach ($eval_akomodasi_auto as $eaa => $evak) {
            $tiga_hari = (strtotime($evak['tanggal_jam_keluar']));

            if ($time - $tiga_hari >= 259200) {
                $id_detail_pengguna = $this->m_trans->where('id_trans', $evak['id_trans'])->select('id_detail_pengguna')->findAll();

                foreach ($id_detail_pengguna as $idp => $detail_peng) {
                    $record = [
                        'id_trans' => $evak['id_trans'],
                        'id_akomodasi' => $evak['id_akomodasi'],
                        'id_detail_pengguna' => $detail_peng['id_detail_pengguna'],
                        'a1_nilai' => 3,
                        'b1_nilai' => 3,
                        'c1_nilai' => 3,
                        'd1_nilai' => 3,
                        'e1_nilai' => 3,
                        'f1_nilai' => 3,
                        'g1_nilai' => 3,
                        'a2_nilai' => 3,
                        'b2_nilai' => 3,
                        'c2_nilai' => 3,
                        'd2_nilai' => 3,
                        'komentar' => null,
                        'status' => 1,
                        'tgl_input' => date('Ymd'),
                    ];
        
                    $akomodasi = [
                        'id_akomodasi' => $evak['id_akomodasi'],
                        'kirim_eval' => 1,
                        'edited_at' => $timestamp,
                    ];
        
                    $this->m_e_akomodasi->insert($record);
                    $this->m_akomodasi->save($akomodasi);
                }
            }
        }

        $eval_transportasi_auto = $this->m_transportasi->where('tanggal_mobil <', $timestamp)->where('kirim_eval', 0)->where('batal_transportasi', 0)->where('status_mobil', 1)->select('id_trans, id_transportasi, tanggal_mobil, jam_siap')->findAll();
        
        foreach ($eval_transportasi_auto as $eta => $evtr) {
            $gab_tiga_hari = tanggal_waktu($evtr['tanggal_mobil'], $evtr['jam_siap']);
            $tiga_hari = strtotime($gab_tiga_hari);

            if ($time - $tiga_hari >= 259200) {
                $id_detail_pengguna = $this->m_trans->where('trans.id_trans', $evtr['id_trans'])->join('transportasi', 'transportasi.id_trans = trans.id_trans', 'left')->select('trans.id_detail_pengguna, id_pengemudi')->findAll();

                foreach ($id_detail_pengguna as $idp => $detail_peng) {
                    $record = [
                        'id_trans' => $evtr['id_trans'],
                        'id_transportasi' => $evtr['id_transportasi'],
                        'id_detail_pengguna' => $detail_peng['id_detail_pengguna'],
                        'id_pengemudi' => $detail_peng['id_pengemudi'],
                        'a1_nilai' => 3,
                        'b1_nilai' => 3,
                        'c1_nilai' => 3,
                        'd1_nilai' => 3,
                        'a2_nilai' => 3,
                        'b2_nilai' => 3,
                        'c2_nilai' => 3,
                        'd2_nilai' => 3,
                        'e2_nilai' => 3,
                        'f2_nilai' => null,
                        '3_nilai' => 3,
                        '4_nilai' => 3,
                        'a5_nilai' => 3,
                        'b5_nilai' => 3,
                        'komentar' => null,
                        'status' => 1,
                        'tgl_input' => date('Ymd'),
                    ];
        
                    $transportasi = [
                        'id_transportasi' => $evtr['id_transportasi'],
                        'kirim_eval' => 1,
                        'edited_at' => $timestamp,
                    ];
        
                    $this->m_e_transportasi->insert($record);
                    $this->m_transportasi->save($transportasi);
                }
            }
        }

        $hotel = $this->m_hotel->join('kota', 'kota.id_kota = hotel.id_kota', 'left')->select('id_hotel, nama_hotel, nama_kota, alamat_hotel, telp_hotel, email_hotel, bintang_hotel')->orderBy('nama_hotel', 'asc')->findAll();
        $kota = $this->m_kota->select('nama_kota')->orderBy('nama_kota', 'asc')->findAll();

        $data = [
            'hotel' => $hotel,
            'kota' => $kota,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_user', $data);
        echo view('master/v_hotel_user', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function detail_hotel_user($id_hotel)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 0) {

        } else if ($admin_gs == 1) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('dept');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        $mess = $this->m_mess->join('hotel', 'hotel.id_hotel = mess_kx_jkt.id_hotel', 'left')->join('akomodasi', 'akomodasi.id_hotel = mess_kx_jkt.id_hotel', 'left')->where('nama_kamar', 'mess')->where('status_mess', 1)->select('id_akomodasi, jumlah_kamar, tanggal_jam_keluar, kapasitas_kamar, terpakai')->findAll();
        
        $sum = 0;
        foreach ($mess as $m => $mes) {
            $jam_keluar = (strtotime($mes['tanggal_jam_keluar']));
            
            $sum += $mes['jumlah_kamar'];

            if ($mes['terpakai'] == 0) {
                
            } else {
                if($time == $jam_keluar || $time > $jam_keluar){
                    $terpakai = [
                        'id_mess' => 8,
                        'terpakai' => $mes['terpakai'] - $sum,
                        'edited_at' => $timestamp,
                    ];

                    $akomodasi = [
                        'id_akomodasi' => $mes['id_akomodasi'],
                        'status_mess' => 0,
                    ];
                    $this->m_akomodasi->save($akomodasi);
                }
            }
        }

        if (empty($terpakai)) {
            
        } else {
            $this->m_mess->save($terpakai);
        }

        $eval_tiket_auto = $this->m_tiket->where('tanggal_jam_tiket <', $timestamp)->where('kirim_eval', 0)->where('batal_tiket', 0)->where('status_tiket', 1)->select('id_trans, id_tiket, tanggal_jam_tiket')->findAll();
        
        foreach ($eval_tiket_auto as $eta => $evtik) {
            $tiga_hari = (strtotime($evtik['tanggal_jam_tiket']));

            if ($time - $tiga_hari >= 259200) {
                $id_detail_pengguna = $this->m_trans->where('id_trans', $evtik['id_trans'])->select('id_detail_pengguna')->findAll();

                foreach ($id_detail_pengguna as $idp => $detail_peng) {
                    $record = [
                        'id_trans' => $evtik['id_trans'],
                        'id_tiket' => $evtik['id_tiket'],
                        'id_detail_pengguna' => $detail_peng['id_detail_pengguna'],
                        'a1_nilai' => 3,
                        'b1_nilai' => 3,
                        'c1_nilai' => 3,
                        'd1_nilai' => 3,
                        'komentar' => null,
                        'status' => 1,
                        'tgl_input' => date('Ymd'),
                    ];
        
                    $tiket = [
                        'id_tiket' => $evtik['id_tiket'],
                        'kirim_eval' => 1,
                        'edited_at' => $timestamp,
                    ];
        
                    $this->m_e_tiket->insert($record);
                    $this->m_tiket->save($tiket);
                }
            }
        }

        $eval_akomodasi_auto = $this->m_akomodasi->where('tanggal_jam_keluar <', $timestamp)->where('kirim_eval', 0)->where('batal_akomodasi', 0)->where('status_akomodasi', 1)->select('id_trans, id_akomodasi, tanggal_jam_keluar')->findAll();
        
        foreach ($eval_akomodasi_auto as $eaa => $evak) {
            $tiga_hari = (strtotime($evak['tanggal_jam_keluar']));

            if ($time - $tiga_hari >= 259200) {
                $id_detail_pengguna = $this->m_trans->where('id_trans', $evak['id_trans'])->select('id_detail_pengguna')->findAll();

                foreach ($id_detail_pengguna as $idp => $detail_peng) {
                    $record = [
                        'id_trans' => $evak['id_trans'],
                        'id_akomodasi' => $evak['id_akomodasi'],
                        'id_detail_pengguna' => $detail_peng['id_detail_pengguna'],
                        'a1_nilai' => 3,
                        'b1_nilai' => 3,
                        'c1_nilai' => 3,
                        'd1_nilai' => 3,
                        'e1_nilai' => 3,
                        'f1_nilai' => 3,
                        'g1_nilai' => 3,
                        'a2_nilai' => 3,
                        'b2_nilai' => 3,
                        'c2_nilai' => 3,
                        'd2_nilai' => 3,
                        'komentar' => null,
                        'status' => 1,
                        'tgl_input' => date('Ymd'),
                    ];
        
                    $akomodasi = [
                        'id_akomodasi' => $evak['id_akomodasi'],
                        'kirim_eval' => 1,
                        'edited_at' => $timestamp,
                    ];
        
                    $this->m_e_akomodasi->insert($record);
                    $this->m_akomodasi->save($akomodasi);
                }
            }
        }

        $eval_transportasi_auto = $this->m_transportasi->where('tanggal_mobil <', $timestamp)->where('kirim_eval', 0)->where('batal_transportasi', 0)->where('status_mobil', 1)->select('id_trans, id_transportasi, tanggal_mobil, jam_siap')->findAll();
        
        foreach ($eval_transportasi_auto as $eta => $evtr) {
            $gab_tiga_hari = tanggal_waktu($evtr['tanggal_mobil'], $evtr['jam_siap']);
            $tiga_hari = strtotime($gab_tiga_hari);

            if ($time - $tiga_hari >= 259200) {
                $id_detail_pengguna = $this->m_trans->where('trans.id_trans', $evtr['id_trans'])->join('transportasi', 'transportasi.id_trans = trans.id_trans', 'left')->select('trans.id_detail_pengguna, id_pengemudi')->findAll();

                foreach ($id_detail_pengguna as $idp => $detail_peng) {
                    $record = [
                        'id_trans' => $evtr['id_trans'],
                        'id_transportasi' => $evtr['id_transportasi'],
                        'id_detail_pengguna' => $detail_peng['id_detail_pengguna'],
                        'id_pengemudi' => $detail_peng['id_pengemudi'],
                        'a1_nilai' => 3,
                        'b1_nilai' => 3,
                        'c1_nilai' => 3,
                        'd1_nilai' => 3,
                        'a2_nilai' => 3,
                        'b2_nilai' => 3,
                        'c2_nilai' => 3,
                        'd2_nilai' => 3,
                        'e2_nilai' => 3,
                        'f2_nilai' => null,
                        '3_nilai' => 3,
                        '4_nilai' => 3,
                        'a5_nilai' => 3,
                        'b5_nilai' => 3,
                        'komentar' => null,
                        'status' => 1,
                        'tgl_input' => date('Ymd'),
                    ];
        
                    $transportasi = [
                        'id_transportasi' => $evtr['id_transportasi'],
                        'kirim_eval' => 1,
                        'edited_at' => $timestamp,
                    ];
        
                    $this->m_e_transportasi->insert($record);
                    $this->m_transportasi->save($transportasi);
                }
            }
        }

        $detail_hotel = $this->m_detail_hotel->where('id_hotel', $id_hotel)->select('id_detail_hotel, id_hotel, jenis_kamar, price_kamar, tgl_valid')->orderBy('jenis_kamar', 'asc')->findAll();

        $data = [
            'id_hotel' => $id_hotel,
            'detail_hotel' => $detail_hotel,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_user', $data);
        echo view('master/v_detail_hotel_user', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function hotel()
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('id_hotel')) {
            $hotel_id = $this->m_hotel->hotel_id($this->request->getVar('id_hotel'));
            if($hotel_id['id_hotel']) {//memastikan bahwa ada data
                $aksi = $this->m_hotel->delete_hotel($this->request->getVar('id_hotel'));
                if($aksi == true) {
                    $this->m_hotel->query('ALTER TABLE hotel AUTO_INCREMENT 1');
                    $this->m_detail_hotel->query('ALTER TABLE detail_hotel AUTO_INCREMENT 1');
                    session()->setFlashdata('success', "Hotel berhasil dihapus");
                } else {
                    session()->setFlashdata('warning', ['Hotel gagal dihapus']);
                }
            }
            return redirect()->to('hotel');
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            $aturan = [
                'nama_hotel' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama Hotel harus diisi'
                    ]
                ],
            ];
            if(!$this->validate($aturan)) {
                session()->setFlashdata('warning', $this->validation->getErrors());
            } else {
                $cek_hotel = $this->m_hotel->where('nama_hotel', $this->request->getVar('nama_hotel'))->select('id_hotel')->first();
                if ($cek_hotel) {
                    session()->setFlashdata('warning', ['Tidak dapat menambah data karena nama Hotel telah ada di dalam database']);
                    return redirect()->to('hotel');
                } else {
                    $id_kota = $this->m_kota->where('nama_kota', $this->request->getVar('nama_kota'))->select('id_kota')->first();

                    $alamat_hotel = $this->request->getVar('alamat_hotel');
                    if (empty($alamat_hotel)) {
                        $alamat_hotel = "-";
                    } else {
                        $alamat_hotel = ucwords($this->request->getVar('alamat_hotel'));
                    }
                    $record = [
                        'id_kota' => $id_kota,
                        'nama_hotel' => ucwords($this->request->getVar('nama_hotel')),
                        'alamat_hotel' => $alamat_hotel,
                        'telp_hotel' => ucwords($this->request->getVar('telp_hotel')),
                        'email_hotel' => ucwords($this->request->getVar('email_hotel')),
                        'bintang_hotel' => $this->request->getVar('bintang_hotel'),
                        'tgl_input' => date('Ymd'),
                    ];
                    $aksi = $this->m_hotel->insert($record);

                    if($aksi != false) { //dibagian aksi tidak false atau ada isinya
                        $page_id = $aksi;
                        session()->setFlashdata('success', 'Hotel berhasil ditambahkan');
                        return redirect()->to('hotel');
                    } else {
                        session()->setFlashdata('warning', ['Hotel gagal ditambahkan']);
                        return redirect()->to('hotel');
                    }
                }
            }
        }

        $hotel = $this->m_hotel->join('kota', 'kota.id_kota = hotel.id_kota', 'left')->select('id_hotel, nama_hotel, nama_kota, alamat_hotel, telp_hotel, email_hotel, bintang_hotel')->orderBy('nama_hotel', 'asc')->findAll();
        $kota = $this->m_kota->select('nama_kota')->orderBy('nama_kota', 'asc')->findAll();

        // $data_id = $this->m_hotel->join('detail_hotel', 'detail_hotel.id_hotel = hotel.id_hotel', 'right')->select('id_detail_hotel, detail_hotel.id_hotel, nama_hotel')->orderBy('id_detail_hotel')->findAll();

        $data = [
            'hotel' => $hotel,
            'kota' => $kota,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_hotel', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function edit_hotel($id_hotel)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view

            $id_kota = $this->m_kota->where('nama_kota', $this->request->getVar('nama_kota'))->select('id_kota')->first();

            $alamat_hotel = $this->request->getVar('alamat_hotel');
            if (empty($alamat_hotel)) {
                $alamat_hotel = "-";
            } else {
                $alamat_hotel = ucwords($this->request->getVar('alamat_hotel'));
            }

            $telp_hotel = $this->request->getVar('telp_hotel');
            // if (is_numeric($this->request->getVar('telp_hotel'))) {
            //     $telp_hotel = $this->request->getVar('telp_hotel');
            // } else {
            //     session()->setFlashdata('warning', ['No telp harus berupa angka']);
            //     return redirect()->to('edit_hotel/'.$id_hotel);
            // }

            $record = [
                'id_hotel' => $id_hotel,
                'id_kota' => $id_kota,
                'nama_hotel' => ucwords($this->request->getVar('nama_hotel')),
                'alamat_hotel' => $alamat_hotel,
                'telp_hotel' => ucwords($telp_hotel),
                'email_hotel' => ucwords($this->request->getVar('email_hotel')),
                'bintang_hotel' => $this->request->getVar('bintang_hotel'),
                'tgl_input' => date('Ymd'),
            ];
            $aksi = $this->m_hotel->save($record);

            session()->setFlashdata('success', 'Data Hotel berhasil di edit');
            return redirect()->to('hotel');
        }

        $hotel = $this->m_hotel->where('id_hotel', $id_hotel)->join('kota', 'kota.id_kota = hotel.id_kota', 'left')->select('id_hotel, nama_hotel, nama_kota, alamat_hotel, telp_hotel, email_hotel, bintang_hotel')->orderBy('nama_hotel', 'asc')->findAll();
        $kota = $this->m_kota->select('nama_kota')->orderBy('nama_kota', 'asc')->findAll();

        $data = [
            'hotel' => $hotel,
            'kota' => $kota,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_edit_hotel', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function detail_hotel($id_hotel)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('id_detail_hotel')) {
            $detail_hotel_id = $this->m_detail_hotel->detail_hotel_id($this->request->getVar('id_detail_hotel'));
            if($detail_hotel_id['id_detail_hotel']) {//memastikan bahwa ada data
                $aksi = $this->m_detail_hotel->delete_detail_hotel($this->request->getVar('id_detail_hotel'));
                if($aksi == true) {
                    $this->m_detail_hotel->query('ALTER TABLE detail_hotel AUTO_INCREMENT 1');
                    session()->setFlashdata('success', "Detail Hotel berhasil dihapus");
                } else {
                    session()->setFlashdata('warning', ['Detail Hotel gagal dihapus']);
                }
            }
            return redirect()->to('detail_hotel/'.$id_hotel);
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            $aturan = [
                'jenis_kamar' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Jenis Kamar harus diisi'
                    ]
                ],
            ];
            if(!$this->validate($aturan)) {
                session()->setFlashdata('warning', $this->validation->getErrors());
            } else {
                $biaya = $this->request->getVar('price_kamar');
                $comma = ',';
                $number = preg_replace('/[^0-9\\-]+/','', $biaya);
                if( strpos($biaya, $comma) !== false ) {
                    $string = $number/100;
                } else {
                    $string = $number;
                }

                $tgl = $this->request->getVar('tgl_valid');
                $tanggal = \DateTime::createFromFormat('Y-m-d', $tgl)->format('Ymd');

                $record = [
                    'id_hotel' => $id_hotel,
                    'jenis_kamar' => ucwords($this->request->getVar('jenis_kamar')),
                    'price_kamar' => $string,
                    'tgl_valid' => $tanggal,
                    'tgl_input' => date('Ymd'),
                ];
                $aksi = $this->m_detail_hotel->insert($record);

                if($aksi != false) { //dibagian aksi tidak false atau ada isinya
                    $page_id = $aksi;
                    session()->setFlashdata('success', 'Detail Hotel berhasil ditambahkan');
                    return redirect()->to('detail_hotel/'.$id_hotel);
                } else {
                    session()->setFlashdata('warning', ['Detail Hotel gagal ditambahkan']);
                    return redirect()->to('detail_hotel/'.$id_hotel);
                }
            }
        }

        $detail_hotel = $this->m_detail_hotel->where('id_hotel', $id_hotel)->select('id_detail_hotel, id_hotel, jenis_kamar, price_kamar, tgl_valid')->orderBy('jenis_kamar', 'asc')->findAll();

        $data = [
            'id_hotel' => $id_hotel,
            'detail_hotel' => $detail_hotel,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_detail_hotel', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function edit_detail_hotel($id_hotel, $id_detail_hotel)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view

            $biaya = $this->request->getVar('price_kamar');
            $comma = ',';
            $number = preg_replace('/[^0-9\\-]+/','', $biaya);
            if( strpos($biaya, $comma) !== false ) {
                $string = $number/100;
            } else {
                $string = $number;
            }

            $tgl = $this->request->getVar('tgl_valid');
            $tanggal = \DateTime::createFromFormat('Y-m-d', $tgl)->format('Ymd');

            $record = [
                'id_detail_hotel' => $id_detail_hotel,
                'id_hotel' => $id_hotel,
                'jenis_kamar' => ucwords($this->request->getVar('jenis_kamar')),
                'price_kamar' => $string,
                'tgl_valid' => $tanggal,
                'tgl_input' => date('Ymd'),
            ];
            $aksi = $this->m_detail_hotel->save($record);

            session()->setFlashdata('success', 'Data Detail Hotel berhasil di edit');
            return redirect()->to('detail_hotel/'.$id_hotel);
        }

        $detail_hotel = $this->m_detail_hotel->where('id_detail_hotel', $id_detail_hotel)->select('id_detail_hotel, id_hotel, jenis_kamar, price_kamar, tgl_valid')->orderBy('jenis_kamar', 'asc')->findAll();

        $data = [
            'detail_hotel' => $detail_hotel,
            'id_hotel' => $id_hotel,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_edit_detail_hotel', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function mobil()
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('id_mobil')) {
            $mobil_id = $this->m_mobil->mobil_id($this->request->getVar('id_mobil'));
            if($mobil_id['id_mobil']) {//memastikan bahwa ada data
                $aksi = $this->m_mobil->delete_mobil($this->request->getVar('id_mobil'));
                if($aksi == true) {
                    $this->m_mobil->query('ALTER TABLE mobil AUTO_INCREMENT 1');
                    session()->setFlashdata('success', "Mobil berhasil dihapus");
                } else {
                    session()->setFlashdata('warning', ['Mobil gagal dihapus']);
                }
            }
            return redirect()->to('mobil');
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            $aturan = [
                'nama_mobil' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama Mobil harus diisi'
                    ]
                ],
            ];
            if(!$this->validate($aturan)) {
                session()->setFlashdata('warning', $this->validation->getErrors());
            } else {
                $cek_mobil = $this->m_mobil->where('nama_mobil', $this->request->getVar('nama_mobil'))->select('id_mobil')->first();
                if ($cek_mobil) {
                    session()->setFlashdata('warning', ['Tidak dapat menambah data karena nama Mobil telah ada di dalam database']);
                    return redirect()->to('mobil');
                } else {
                    $id_pool = $this->m_pool->where('nama_pool', $this->request->getVar('nama_pool'))->select('id_pool')->first();

                    $non_mobil = $this->request->getVar('non_mobil');
                    if (empty($non_mobil)) {
                        $non_mobil = "0";
                    } else {
                        $non_mobil = "1";
                    }

                    $id_jenis_bbm = $this->m_jenis_bbm->where('jenis_bbm', $this->request->getVar('jenis_bbm'))->select('id_jenis_bbm')->first();
                    $id_jenis_kendaraan = $this->m_jenis_kendaraan->where('jenis_kendaraan', $this->request->getVar('jenis_kendaraan'))->select('id_jenis_kendaraan')->first();

                    $tanggal_stnk = $this->request->getVar('tgl_stnk');
                    $tgl_stnk = \DateTime::createFromFormat('Y-m-d', $tanggal_stnk)->format('Ymd');

                    $tanggal_keur = $this->request->getVar('tgl_keur');
                    $tgl_keur = \DateTime::createFromFormat('Y-m-d', $tanggal_keur)->format('Ymd');

                    $record = [
                        'id_pool' => $id_pool,
                        'nama_mobil' => ucwords($this->request->getVar('nama_mobil')),
                        'non_mobil' => $non_mobil,
                        'nopol' => strtoupper($this->request->getVar('nopol')),
                        'id_jenis_bbm' => $id_jenis_bbm,
                        'id_jenis_kendaraan' => $id_jenis_kendaraan,
                        'tgl_stnk' => $tgl_stnk,
                        'tgl_keur' => $tgl_keur,
                        'km_mesin' => $this->request->getVar('km_mesin'),
                        'km_awal_mesin' => $this->request->getVar('km_awal_mesin'),
                        'km_oli' => $this->request->getVar('km_oli'),
                        'km_awal_oli' => $this->request->getVar('km_awal_oli'),
                        'km_bbm' => $this->request->getVar('km_bbm'),
                        'km_awal_bbm' => $this->request->getVar('km_awal_bbm'),
                        'km_udara' => $this->request->getVar('km_udara'),
                        'km_awal_udara' => $this->request->getVar('km_awal_udara'),
                        'tgl_input' => date('Ymd'),
                    ];
                    $aksi = $this->m_mobil->insert($record);

                    if($aksi != false) { //dibagian aksi tidak false atau ada isinya
                        $page_id = $aksi;
                        session()->setFlashdata('success', 'Mobil berhasil ditambahkan');
                        return redirect()->to('mobil');
                    } else {
                        session()->setFlashdata('warning', ['Mobil gagal ditambahkan']);
                        return redirect()->to('mobil');
                    }
                }
            }
        }

        $mobil = $this->m_mobil->select('id_mobil, id_pool, nama_mobil, nopol, id_jenis_bbm, id_jenis_kendaraan')->orderBy('nama_mobil', 'asc')->findAll();

        $pool = $this->m_pool->select('id_pool, nama_pool')->orderBy('id_pool', 'asc')->findAll();
        $jenis_bbm = $this->m_jenis_bbm->select('id_jenis_bbm, jenis_bbm')->orderBy('id_jenis_bbm', 'asc')->findAll();
        $jenis_kendaraan = $this->m_jenis_kendaraan->select('id_jenis_kendaraan, jenis_kendaraan')->orderBy('id_jenis_kendaraan', 'asc')->findAll();

        $jenis_bbm_mobil = $this->m_mobil->join('jenis_bbm', 'jenis_bbm.id_jenis_bbm = mobil.id_jenis_bbm', 'left')->select('mobil.id_jenis_bbm, id_mobil, jenis_bbm')->findAll();
        $jenis_kendaraan_mobil = $this->m_mobil->join('jenis_kendaraan', 'jenis_kendaraan.id_jenis_kendaraan = mobil.id_jenis_kendaraan', 'left')->select('mobil.id_jenis_kendaraan, id_mobil, jenis_kendaraan')->findAll();
        $pool_mobil = $this->m_mobil->join('pool', 'pool.id_pool = mobil.id_pool', 'left')->select('id_mobil, mobil.id_pool, nama_pool')->findAll();

        $data = [
            'mobil' => $mobil,
            'jenis_bbm' => $jenis_bbm,
            'jenis_kendaraan' => $jenis_kendaraan,
            'pool' => $pool,
            'jenis_bbm_mobil' => $jenis_bbm_mobil,
            'jenis_kendaraan_mobil' => $jenis_kendaraan_mobil,
            'pool_mobil' => $pool_mobil,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_mobil', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function edit_mobil($id_mobil)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view

            $id_pool = $this->m_pool->where('nama_pool', $this->request->getVar('nama_pool'))->select('id_pool')->first();

            $non_mobil = $this->request->getVar('non_mobil');
            if (empty($non_mobil)) {
                $non_mobil = "0";
            } else {
                $non_mobil = "1";
            }

            $id_jenis_bbm = $this->m_jenis_bbm->where('jenis_bbm', $this->request->getVar('jenis_bbm'))->select('id_jenis_bbm')->first();
            $id_jenis_kendaraan = $this->m_jenis_kendaraan->where('jenis_kendaraan', $this->request->getVar('jenis_kendaraan'))->select('id_jenis_kendaraan')->first();

            $tanggal_stnk = $this->request->getVar('tgl_stnk');
            $tgl_stnk = \DateTime::createFromFormat('Y-m-d', $tanggal_stnk)->format('Ymd');

            $tanggal_keur = $this->request->getVar('tgl_keur');
            $tgl_keur = \DateTime::createFromFormat('Y-m-d', $tanggal_keur)->format('Ymd');

            $record = [
                'id_mobil' => $id_mobil,
                'id_pool' => $id_pool,
                'nama_mobil' => ucwords($this->request->getVar('nama_mobil')),
                'non_mobil' => $non_mobil,
                'nopol' => strtoupper($this->request->getVar('nopol')),
                'id_jenis_bbm' => $id_jenis_bbm,
                'id_jenis_kendaraan' => $id_jenis_kendaraan,
                'tgl_stnk' => $tgl_stnk,
                'tgl_keur' => $tgl_keur,
                'km_mesin' => $this->request->getVar('km_mesin'),
                'km_awal_mesin' => $this->request->getVar('km_awal_mesin'),
                'km_oli' => $this->request->getVar('km_oli'),
                'km_awal_oli' => $this->request->getVar('km_awal_oli'),
                'km_bbm' => $this->request->getVar('km_bbm'),
                'km_awal_bbm' => $this->request->getVar('km_awal_bbm'),
                'km_udara' => $this->request->getVar('km_udara'),
                'km_awal_udara' => $this->request->getVar('km_awal_udara'),
                'tgl_input' => date('Ymd'),
            ];
            $aksi = $this->m_mobil->save($record);

            session()->setFlashdata('success', 'Data Mobil berhasil di edit');
            return redirect()->to('mobil');
        }

        $mobil = $this->m_mobil->where('id_mobil', $id_mobil)->orderBy('nama_mobil', 'asc')->findAll();
        
        $pool = $this->m_pool->select('id_pool, nama_pool')->orderBy('id_pool', 'asc')->findAll();
        $jenis_bbm = $this->m_jenis_bbm->select('id_jenis_bbm, jenis_bbm')->orderBy('id_jenis_bbm', 'asc')->findAll();
        $jenis_kendaraan = $this->m_jenis_kendaraan->select('id_jenis_kendaraan, jenis_kendaraan')->orderBy('id_jenis_kendaraan', 'asc')->findAll();

        $jenis_bbm_mobil = $this->m_mobil->join('jenis_bbm', 'jenis_bbm.id_jenis_bbm = mobil.id_jenis_bbm', 'left')->select('mobil.id_jenis_bbm, id_mobil, jenis_bbm')->findAll();
        $jenis_kendaraan_mobil = $this->m_mobil->join('jenis_kendaraan', 'jenis_kendaraan.id_jenis_kendaraan = mobil.id_jenis_kendaraan', 'left')->select('mobil.id_jenis_kendaraan, id_mobil, jenis_kendaraan')->findAll();
        $pool_mobil = $this->m_mobil->join('pool', 'pool.id_pool = mobil.id_pool', 'left')->select('id_mobil, mobil.id_pool, nama_pool')->findAll();

        $data = [
            'mobil' => $mobil,
            'jenis_bbm' => $jenis_bbm,
            'jenis_kendaraan' => $jenis_kendaraan,
            'pool' => $pool,
            'jenis_bbm_mobil' => $jenis_bbm_mobil,
            'jenis_kendaraan_mobil' => $jenis_kendaraan_mobil,
            'pool_mobil' => $pool_mobil,
            'id_mobil' => $id_mobil,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_edit_mobil', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function pengemudi()
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('id_pengemudi')) {
            $pengemudi_id = $this->m_pengemudi->pengemudi_id($this->request->getVar('id_pengemudi'));
            if($pengemudi_id['id_pengemudi']) {//memastikan bahwa ada data
                $aksi = $this->m_pengemudi->delete_pengemudi($this->request->getVar('id_pengemudi'));
                if($aksi == true) {
                    $this->m_pengemudi->query('ALTER TABLE pengemudi AUTO_INCREMENT 1');
                    session()->setFlashdata('success', "Pengemudi berhasil dihapus");
                } else {
                    session()->setFlashdata('warning', ['Pengemudi gagal dihapus']);
                }
            }
            return redirect()->to('pengemudi');
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            $aturan = [
                'email' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Silahkan beri tanda "-" jika tidak ada informasi Email'
                    ]
                ],
            ];
            if(!$this->validate($aturan)) {
                session()->setFlashdata('warning', $this->validation->getErrors());
            } else {
                $cek_pengemudi = $this->m_pengemudi->where('nama_pengemudi', $this->request->getVar('nama_pengemudi'))->select('id_pengemudi')->first();
                if ($cek_pengemudi) {
                    session()->setFlashdata('warning', ['Tidak dapat menambah data karena nama Pengemudi telah ada di dalam database']);
                    return redirect()->to('pengemudi');
                } else {
                    $id_pool = $this->m_pool->where('nama_pool', $this->request->getVar('nama_pool'))->select('id_pool')->first();
                    $id_mobil = $this->m_mobil->where('nama_mobil', $this->request->getVar('nama_mobil'))->select('id_mobil')->first();
    
                    $record = [
                        'id_pool' => $id_pool,
                        'id_mobil' => $id_mobil,
                        'nama_pengemudi' => ucwords($this->request->getVar('nama_pengemudi')),
                        'jenis_sopir' => ucwords($this->request->getVar('jenis_sopir')),
                        'nomor_hp' => $this->request->getVar('nomor_hp'),
                        'email' => $this->request->getVar('email'),
                        'tgl_input' => date('Ymd'),
                    ];
                    $aksi = $this->m_pengemudi->insert($record);
    
                    if($aksi != false) { //dibagian aksi tidak false atau ada isinya
                        $page_id = $aksi;
                        session()->setFlashdata('success', 'Pengemudi berhasil ditambahkan');
                        return redirect()->to('pengemudi');
                    } else {
                        session()->setFlashdata('warning', ['Pengemudi gagal ditambahkan']);
                        return redirect()->to('pengemudi');
                    }
                }
            }
        }

        $pengemudi = $this->m_pengemudi->select('id_pengemudi, id_pool, id_mobil, nama_pengemudi, jenis_sopir, nomor_hp, email')->orderBy('nama_pengemudi', 'asc')->findAll();
        
        $jenis_sopir = $this->m_jenis_sopir->select('id_jenis_sopir, jenis_sopir')->orderBy('id_jenis_sopir', 'asc')->findAll();
        $pool = $this->m_pool->select('id_pool, nama_pool')->orderBy('id_pool', 'asc')->findAll();
        $mobil = $this->m_mobil->select('id_mobil, nama_mobil')->orderBy('nama_mobil', 'asc')->findAll();

        $pool_pengemudi = $this->m_pengemudi->join('pool', 'pool.id_pool = pengemudi.id_pool', 'left')->select('id_pengemudi, pengemudi.id_pool, nama_pool')->findAll();
        $mobil_pengemudi = $this->m_pengemudi->join('mobil', 'mobil.id_mobil = pengemudi.id_mobil', 'left')->select('id_pengemudi, pengemudi.id_mobil, nama_mobil')->findAll();

        $data = [
            'pengemudi' => $pengemudi,

            'jenis_sopir' => $jenis_sopir,
            'pool' => $pool,
            'mobil' => $mobil,

            'pool_pengemudi' => $pool_pengemudi,
            'mobil_pengemudi' => $mobil_pengemudi,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_pengemudi', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function edit_pengemudi($id_pengemudi)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view

            $id_pool = $this->m_pool->where('nama_pool', $this->request->getVar('nama_pool'))->select('id_pool')->first();
            $id_mobil = $this->m_mobil->where('nama_mobil', $this->request->getVar('nama_mobil'))->select('id_mobil')->first();

            $record = [
                'id_pengemudi' => $id_pengemudi,
                'id_pool' => $id_pool,
                'id_mobil' => $id_mobil,
                'nama_pengemudi' => ucwords($this->request->getVar('nama_pengemudi')),
                'jenis_sopir' => ucwords($this->request->getVar('jenis_sopir')),
                'nomor_hp' => $this->request->getVar('nomor_hp'),
                'email' => $this->request->getVar('email'),
                'tgl_input' => date('Ymd'),
            ];
            $aksi = $this->m_pengemudi->save($record);

            session()->setFlashdata('success', 'Data Pengemudi berhasil di edit');
            return redirect()->to('pengemudi');
        }

        $pengemudi = $this->m_pengemudi->where('id_pengemudi', $id_pengemudi)->select('id_pengemudi, id_pool, id_mobil, nama_pengemudi, jenis_sopir, nomor_hp, email')->orderBy('nama_pengemudi', 'asc')->findAll();
        
        $jenis_sopir = $this->m_jenis_sopir->select('id_jenis_sopir, jenis_sopir')->orderBy('id_jenis_sopir', 'asc')->findAll();
        $pool = $this->m_pool->select('id_pool, nama_pool')->orderBy('id_pool', 'asc')->findAll();
        $mobil = $this->m_mobil->select('id_mobil, nama_mobil')->orderBy('nama_mobil', 'asc')->findAll();

        $pool_pengemudi = $this->m_pengemudi->join('pool', 'pool.id_pool = pengemudi.id_pool', 'left')->select('id_pengemudi, pengemudi.id_pool, nama_pool')->findAll();
        $mobil_pengemudi = $this->m_pengemudi->join('mobil', 'mobil.id_mobil = pengemudi.id_mobil', 'left')->select('id_pengemudi, pengemudi.id_mobil, nama_mobil')->findAll();
        $jenis_sopir_pengemudi = $this->m_pengemudi->join('jenis_sopir', 'jenis_sopir.jenis_sopir = pengemudi.jenis_sopir', 'left')->select('id_pengemudi, pengemudi.jenis_sopir')->findAll();

        $data = [
            'pengemudi' => $pengemudi,

            'jenis_sopir' => $jenis_sopir,
            'pool' => $pool,
            'mobil' => $mobil,

            'jenis_sopir_pengemudi' => $jenis_sopir_pengemudi,
            'pool_pengemudi' => $pool_pengemudi,
            'mobil_pengemudi' => $mobil_pengemudi,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_edit_pengemudi', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function pengguna()
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');
        $timestamp = date('Y-m-d H:i:s');
        $time = strtotime($timestamp);

        $now = date('2024-04-30 17:00:00');
        $time_now = (strtotime($now));

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('id_pengguna')) {
            $pengguna_id = $this->m_pengguna->pengguna_id($this->request->getVar('id_pengguna'));
            if($pengguna_id['id_pengguna']) {//memastikan bahwa ada data
                $aksi = $this->m_pengguna->delete_pengguna($this->request->getVar('id_pengguna'));
                if($aksi == true) {
                    $this->m_pengguna->query('ALTER TABLE pengguna AUTO_INCREMENT 1');
                    $this->m_detail_pengguna->query('ALTER TABLE detail_pengguna AUTO_INCREMENT 1');
                    session()->setFlashdata('success', "Pengguna berhasil dihapus");
                } else {
                    session()->setFlashdata('warning', ['Pengguna gagal dihapus']);
                }
            }
            return redirect()->to('pengguna');
        }

        if($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('id_email_delegasi')) {
            $email_delegasi_id = $this->m_email_delegasi->email_delegasi_id($this->request->getVar('id_email_delegasi'));
            if($email_delegasi_id['id_email_delegasi']) {//memastikan bahwa ada data
                $aksi = $this->m_email_delegasi->delete_email_delegasi($this->request->getVar('id_email_delegasi'));
                if($aksi == true) {
                    $this->m_email_delegasi->query('ALTER TABLE email_delegasi AUTO_INCREMENT 1');
                    session()->setFlashdata('success', "Email Delegasi berhasil dihapus");
                } else {
                    session()->setFlashdata('warning', ['Email Delegasi gagal dihapus']);
                }
            }
            return redirect()->to('pengguna');
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            if(!empty($this->request->getVar('nik_pengguna'))) {
                $aturan = [
                    'email_pengguna' => [
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Silahkan beri tanda "-" jika tidak ada informasi Email'
                        ]
                    ],
                ];
                if(!$this->validate($aturan)) {
                    session()->setFlashdata('warning', $this->validation->getErrors());
                } else {
                    $jenis_kelamin = $this->request->getVar('jenis_kelamin');

                    if ($jenis_kelamin == 'Laki-laki') {
                        $jenis_kelamin = "l";
                    } else if ($jenis_kelamin == 'Perempuan') {
                        $jenis_kelamin = "p";
                    }

                    $record = [
                        'nama_pengguna' => ucwords($this->request->getVar('nama_pengguna')),
                        'nik_pengguna' => $this->request->getVar('nik_pengguna'),
                        'email_pengguna' => $this->request->getVar('email_pengguna'),
                        'jenis_kelamin' => $jenis_kelamin,
                        'no_hp_pengguna' => $this->request->getVar('no_hp_pengguna'),
                        'alamat_rumah' => $this->request->getVar('alamat_rumah'),
                        'tgl_input' => date('Ymd'),
                    ];
                    $aksi = $this->m_pengguna->insert($record);

                    if($aksi != false) { //dibagian aksi tidak false atau ada isinya
                        $page_id = $aksi;
                        session()->setFlashdata('success', 'Pengguna berhasil ditambahkan');
                        return redirect()->to('pengguna');
                    } else {
                        session()->setFlashdata('warning', ['Pengguna gagal ditambahkan']);
                        return redirect()->to('pengguna');
                    }
                }
            } else if (!empty($this->request->getVar('username'))) {
                $aturan = [
                    'username' => [
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Username harus diisi'
                        ]
                    ],
                ];
                if(!$this->validate($aturan)) {
                    session()->setFlashdata('warning', $this->validation->getErrors());
                } else {
                    if (strtotime($this->request->getVar('tanggal_jam_mulai')) == strtotime($this->request->getVar('tanggal_jam_akhir'))) {
                        session()->setFlashdata('warning', ['Tanggal jam mulai dan tanggal jam selesai tidak boleh sama']);
                        return redirect()->to('pengguna');
                    }

                    $personil_delegasi = $this->request->getVar('personil_delegasi');

                    $nama_pengguna = substr($personil_delegasi, 0, strpos($personil_delegasi, " - "));
                    $nik_pengguna = substr($personil_delegasi, strpos($personil_delegasi, ' - ') + 3);

                    $id_detail_pengguna = $this->m_detail_pengguna->where('nik_pengguna', $nik_pengguna)->join('pengguna', 'pengguna.id_pengguna = detail_pengguna.id_pengguna', 'left')->select('pengguna.id_pengguna, id_detail_pengguna, email_pengguna')->orderBy('username', 'asc')->findAll();

                    $record = [
                        'id_pengguna' => $id_detail_pengguna[0]['id_pengguna'],
                        'id_detail_pengguna' => $id_detail_pengguna[0]['id_detail_pengguna'],
                        'id_pool' => session()->get('pool_pengguna'),
                        'username' => $this->request->getVar('username'),
                        'email_pengguna' => $id_detail_pengguna[0]['email_pengguna'],
                        'tanggal_jam_mulai' => strtotime($this->request->getVar('tanggal_jam_mulai')),
                        'tanggal_jam_akhir' => strtotime($this->request->getVar('tanggal_jam_akhir')),
                        'edited_by' => session()->get('login_by'),
                        'edited_at' => $timestamp,
                    ];
                    $aksi = $this->m_email_delegasi->insert($record);

                    if($aksi != false) { //dibagian aksi tidak false atau ada isinya
                        $page_id = $aksi;
                        session()->setFlashdata('success', 'Email Delegasi berhasil ditambahkan');
                        return redirect()->to('pengguna');
                    } else {
                        session()->setFlashdata('warning', ['Email Delegasi gagal ditambahkan']);
                        return redirect()->to('pengguna');
                    }
                }
            }
        }

        $id_bagian = session()->get('id_bagian');

        $pengguna = $this->m_pengguna->select('id_pengguna, nama_pengguna, nik_pengguna, email_pengguna, jenis_kelamin, no_hp_pengguna, alamat_rumah')->orderBy('nik_pengguna', 'asc')->findAll();

        $email_delegasi = $this->m_email_delegasi->where('id_pool', session()->get('pool_pengguna'))->where('tanggal_jam_mulai <', $time)->where('tanggal_jam_akhir >', $time)->select('id_email_delegasi, username, email_pengguna, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        $add_delegasi = $this->m_detail_pengguna->where('id_bagian', $id_bagian)->join('pengguna', 'pengguna.id_pengguna = detail_pengguna.id_pengguna', 'left')->select('username')->orderBy('username', 'asc')->findAll();

        $nik_nama = $this->m_detail_pengguna->where('id_bagian', $id_bagian)->join('pengguna', 'pengguna.id_pengguna = detail_pengguna.id_pengguna', 'left')->select('nik_pengguna, nama_pengguna')->groupBy('nik_pengguna')->orderBy('username', 'asc')->findAll();

        $data = [
            'pengguna' => $pengguna,
            'email_delegasi' => $email_delegasi,
            'add_delegasi' => $add_delegasi,
            'nik_nama' => $nik_nama,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_pengguna', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function edit_pengguna($id_pengguna)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            
            $jenis_kelamin = $this->request->getVar('jenis_kelamin');

            if ($jenis_kelamin == 'Laki-laki') {
                $jenis_kelamin = "l";
            } else if ($jenis_kelamin == 'Perempuan') {
                $jenis_kelamin = "p";
            }

            $record = [
                'id_pengguna' => $id_pengguna,
                'nama_pengguna' => ucwords($this->request->getVar('nama_pengguna')),
                'nik_pengguna' => $this->request->getVar('nik_pengguna'),
                'email_pengguna' => $this->request->getVar('email_pengguna'),
                'jenis_kelamin' => $jenis_kelamin,
                'no_hp_pengguna' => $this->request->getVar('no_hp_pengguna'),
                'alamat_rumah' => $this->request->getVar('alamat_rumah'),
                'tgl_input' => date('Ymd'),
            ];
            $aksi = $this->m_pengguna->save($record);

            session()->setFlashdata('success', 'Data Pengguna berhasil di edit');
            return redirect()->to('pengguna');
        }

        $pengguna = $this->m_pengguna->where('id_pengguna', $id_pengguna)->select('id_pengguna, nama_pengguna, nik_pengguna, email_pengguna, jenis_kelamin, no_hp_pengguna, alamat_rumah')->orderBy('nik_pengguna', 'asc')->findAll();

        $data = [
            'pengguna' => $pengguna,
            'id_pengguna' => $id_pengguna,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_edit_pengguna', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function edit_email_delegasi($id_email_delegasi)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');
        $timestamp = date('Y-m-d H:i:s');
        $time = strtotime($timestamp);

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view

            if (strtotime($this->request->getVar('tanggal_jam_mulai')) == strtotime($this->request->getVar('tanggal_jam_akhir'))) {
                session()->setFlashdata('warning', ['Tanggal jam mulai dan tanggal jam selesai tidak boleh sama']);
                return redirect()->to('pengguna');
            }

            $personil_delegasi = $this->request->getVar('personil_delegasi');

            $nama_pengguna = substr($personil_delegasi, 0, strpos($personil_delegasi, " - "));
            $nik_pengguna = substr($personil_delegasi, strpos($personil_delegasi, ' - ') + 3);

            $id_detail_pengguna = $this->m_detail_pengguna->where('nik_pengguna', $nik_pengguna)->join('pengguna', 'pengguna.id_pengguna = detail_pengguna.id_pengguna', 'left')->select('pengguna.id_pengguna, id_detail_pengguna, email_pengguna')->orderBy('username', 'asc')->findAll();

            $record = [
                'id_email_delegasi' => $id_email_delegasi,
                'id_pengguna' => $id_detail_pengguna[0]['id_pengguna'],
                'id_detail_pengguna' => $id_detail_pengguna[0]['id_detail_pengguna'],
                'username' => $this->request->getVar('username'),
                'email_pengguna' => $id_detail_pengguna[0]['email_pengguna'],
                'tanggal_jam_mulai' => strtotime($this->request->getVar('tanggal_jam_mulai')),
                'tanggal_jam_akhir' => strtotime($this->request->getVar('tanggal_jam_akhir')),
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];
            $aksi = $this->m_email_delegasi->save($record);

            session()->setFlashdata('success', 'Data Email Delegasi berhasil di edit');
            return redirect()->to('pengguna');
        }

        $id_bagian = session()->get('id_bagian');

        $email_delegasi = $this->m_email_delegasi->where('id_email_delegasi', $id_email_delegasi)->join('detail_pengguna', 'detail_pengguna.id_detail_pengguna = email_delegasi.id_detail_pengguna', 'left')->join('pengguna', 'pengguna.id_pengguna = email_delegasi.id_pengguna', 'left')->select('id_email_delegasi, email_delegasi.id_detail_pengguna, email_delegasi.username, nama_pengguna, nik_pengguna, email_delegasi.email_pengguna, tanggal_jam_mulai, tanggal_jam_akhir')->findAll();

        $add_delegasi = $this->m_detail_pengguna->where('id_bagian', $id_bagian)->join('pengguna', 'pengguna.id_pengguna = detail_pengguna.id_pengguna', 'left')->select('username')->orderBy('username', 'asc')->findAll();

        $nik_nama = $this->m_detail_pengguna->where('id_bagian', $id_bagian)->join('pengguna', 'pengguna.id_pengguna = detail_pengguna.id_pengguna', 'left')->select('nik_pengguna, nama_pengguna')->groupBy('nik_pengguna')->orderBy('username', 'asc')->findAll();

        $data = [
            'email_delegasi' => $email_delegasi,
            'add_delegasi' => $add_delegasi,
            'id_email_delegasi' => $id_email_delegasi,
            'nik_nama' => $nik_nama,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_edit_email_delegasi', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function detail_pengguna($id_pengguna)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('id_detail_pengguna')) {
            $detail_pengguna_id = $this->m_detail_pengguna->detail_pengguna_id($this->request->getVar('id_detail_pengguna'));
            if($detail_pengguna_id['id_detail_pengguna']) {//memastikan bahwa ada data
                $aksi = $this->m_detail_pengguna->delete_detail_pengguna($this->request->getVar('id_detail_pengguna'));
                if($aksi == true) {
                    $this->m_detail_pengguna->query('ALTER TABLE detail_pengguna AUTO_INCREMENT 1');
                    session()->setFlashdata('success', "Detail Pengguna berhasil dihapus");
                } else {
                    session()->setFlashdata('warning', ['Detail Pengguna gagal dihapus']);
                }
            }
            return redirect()->to('detail_pengguna/'.$id_pengguna);
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            $aturan = [
                'username' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Username tidak boleh kosong'
                    ]
                ],
            ];
            if(!$this->validate($aturan)) {
                session()->setFlashdata('warning', $this->validation->getErrors());
            } else {
                $cek_detail_pengguna = $this->m_detail_pengguna->where('username', $this->request->getVar('username'))->select('id_detail_pengguna')->first();
                if ($cek_detail_pengguna) {
                    session()->setFlashdata('warning', ['Tidak dapat menambah data karena Username telah ada di dalam database']);
                    return redirect()->to('detail_pengguna/'.$id_pengguna);
                } else {
                    $id_pool = $this->m_pool->where('nama_pool', $this->request->getVar('nama_pool'))->select('id_pool')->first();
                    $id_bagian = $this->m_bagian->where('nama_bagian', $this->request->getVar('nama_bagian'))->select('id_bagian')->first();
                    $id_jabatan = $this->m_jabatan->where('nama_jabatan', $this->request->getVar('nama_jabatan'))->select('id_jabatan')->first();
    
                    $admin_gs = $this->request->getVar('admin_gs');
    
                    if ($admin_gs == 'User') {
                        $admin_gs = "0";
                    } else if ($admin_gs == 'Admin GS') {
                        $admin_gs = "1";
                    } else if ($admin_gs == 'Petugas Pool') {
                        $admin_gs = "2";
                    }
    
                    $record = [
                        'id_pengguna' => $id_pengguna,
                        'id_pool' => $id_pool['id_pool'],
                        'id_bagian' => $id_bagian['id_bagian'],
                        'id_jabatan' => $id_jabatan['id_jabatan'],
                        'username' => $this->request->getVar('username'),
                        'pass' => '28b662d883b6d76fd96e4ddc5e9ba780',
                        'admin_gs' => $admin_gs,
                        'tgl_input' => date('Ymd'),
                    ];
                    $aksi = $this->m_detail_pengguna->insert($record);
    
                    if($aksi != false) { //dibagian aksi tidak false atau ada isinya
                        $page_id = $aksi;
                        session()->setFlashdata('success', 'Detail Pengguna berhasil ditambahkan');
                        return redirect()->to('detail_pengguna/'.$id_pengguna);
                    } else {
                        session()->setFlashdata('warning', ['Detail Pengguna gagal ditambahkan']);
                        return redirect()->to('detail_pengguna/'.$id_pengguna);
                    }
                }
            }
        }

        $detail_pengguna = $this->m_detail_pengguna->where('id_pengguna', $id_pengguna)->select('id_detail_pengguna, id_pengguna, id_pool, id_bagian, id_jabatan, username, admin_gs')->orderBy('id_detail_pengguna', 'asc')->findAll();

        $pool = $this->m_pool->select('id_pool, nama_pool')->orderBy('id_pool', 'asc')->findAll();
        $bagian = $this->m_bagian->select('id_bagian, nama_bagian')->orderBy('nama_bagian', 'asc')->findAll();
        $jabatan = $this->m_jabatan->select('id_jabatan, nama_jabatan')->orderBy('nama_jabatan', 'asc')->findAll();

        $pool_detail_pengguna = $this->m_detail_pengguna->join('pool', 'pool.id_pool = detail_pengguna.id_pool', 'left')->select('id_detail_pengguna, detail_pengguna.id_pool, nama_pool')->findAll();
        $bagian_detail_pengguna = $this->m_detail_pengguna->join('bagian', 'bagian.id_bagian = detail_pengguna.id_bagian', 'left')->select('id_detail_pengguna, detail_pengguna.id_bagian, nama_bagian')->findAll();
        $jabatan_detail_pengguna = $this->m_detail_pengguna->join('jabatan', 'jabatan.id_jabatan = detail_pengguna.id_jabatan', 'left')->select('id_detail_pengguna, detail_pengguna.id_jabatan, nama_jabatan')->findAll();

        $data = [
            'id_pengguna' => $id_pengguna,
            'detail_pengguna' => $detail_pengguna,

            'pool' => $pool,
            'bagian' => $bagian,
            'jabatan' => $jabatan,

            'pool_detail_pengguna' => $pool_detail_pengguna,
            'bagian_detail_pengguna' => $bagian_detail_pengguna,
            'jabatan_detail_pengguna' => $jabatan_detail_pengguna,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_detail_pengguna', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function edit_detail_pengguna($id_pengguna, $id_detail_pengguna)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            
            $id_pool = $this->m_pool->where('nama_pool', $this->request->getVar('nama_pool'))->select('id_pool')->first();
                $id_bagian = $this->m_bagian->where('nama_bagian', $this->request->getVar('nama_bagian'))->select('id_bagian')->first();
                $id_jabatan = $this->m_jabatan->where('nama_jabatan', $this->request->getVar('nama_jabatan'))->select('id_jabatan')->first();

                $admin_gs = $this->request->getVar('admin_gs');

                if ($admin_gs == 'User') {
                    $admin_gs = "0";
                } else if ($admin_gs == 'Admin GS') {
                    $admin_gs = "1";
                } else if ($admin_gs == 'Petugas Pool') {
                    $admin_gs = "2";
                }

                $record = [
                    'id_detail_pengguna' => $id_detail_pengguna,
                    'id_pengguna' => $id_pengguna,
                    'id_pool' => $id_pool['id_pool'],
                    'id_bagian' => $id_bagian['id_bagian'],
                    'id_jabatan' => $id_jabatan['id_jabatan'],
                    'username' => $this->request->getVar('username'),
                    'pass' => '28b662d883b6d76fd96e4ddc5e9ba780',
                    'admin_gs' => $admin_gs,
                    'tgl_input' => date('Ymd'),
                ];
                $aksi = $this->m_detail_pengguna->save($record);

            session()->setFlashdata('success', 'Data Detail Pengguna berhasil di edit');
            return redirect()->to('detail_pengguna/'.$id_pengguna);
        }

        $detail_pengguna = $this->m_detail_pengguna->where('id_detail_pengguna', $id_detail_pengguna)->select('id_detail_pengguna, id_pengguna, id_pool, id_bagian, id_jabatan, username, admin_gs')->orderBy('id_detail_pengguna', 'asc')->findAll();

        $pool = $this->m_pool->select('id_pool, nama_pool')->orderBy('id_pool', 'asc')->findAll();
        $bagian = $this->m_bagian->select('id_bagian, nama_bagian')->orderBy('nama_bagian', 'asc')->findAll();
        $jabatan = $this->m_jabatan->select('id_jabatan, nama_jabatan')->orderBy('nama_jabatan', 'asc')->findAll();

        $pool_detail_pengguna = $this->m_detail_pengguna->join('pool', 'pool.id_pool = detail_pengguna.id_pool', 'left')->select('id_detail_pengguna, detail_pengguna.id_pool, nama_pool')->findAll();
        $bagian_detail_pengguna = $this->m_detail_pengguna->join('bagian', 'bagian.id_bagian = detail_pengguna.id_bagian', 'left')->select('id_detail_pengguna, detail_pengguna.id_bagian, nama_bagian')->findAll();
        $jabatan_detail_pengguna = $this->m_detail_pengguna->join('jabatan', 'jabatan.id_jabatan = detail_pengguna.id_jabatan', 'left')->select('id_detail_pengguna, detail_pengguna.id_jabatan, nama_jabatan')->findAll();

        $data = [
            'id_detail_pengguna' => $id_detail_pengguna,
            'id_pengguna' => $id_pengguna,
            'detail_pengguna' => $detail_pengguna,

            'pool' => $pool,
            'bagian' => $bagian,
            'jabatan' => $jabatan,

            'pool_detail_pengguna' => $pool_detail_pengguna,
            'bagian_detail_pengguna' => $bagian_detail_pengguna,
            'jabatan_detail_pengguna' => $jabatan_detail_pengguna,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_edit_detail_pengguna', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function pool()
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('id_pool')) {
            $pool_id = $this->m_pool->pool_id($this->request->getVar('id_pool'));
            if($pool_id['id_pool']) {//memastikan bahwa ada data
                $aksi = $this->m_pool->delete_pool($this->request->getVar('id_pool'));
                if($aksi == true) {
                    $this->m_pool->query('ALTER TABLE pool AUTO_INCREMENT 1');
                    session()->setFlashdata('success', "Pool berhasil dihapus");
                } else {
                    session()->setFlashdata('warning', ['Pool gagal dihapus']);
                }
            }
            return redirect()->to('pool');
        }
    
        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            $aturan = [
                'email_pool' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Silahkan beri tanda "-" jika tidak ada informasi Email'
                    ]
                ],
            ];
            if(!$this->validate($aturan)) {
                session()->setFlashdata('warning', $this->validation->getErrors());
            } else {
                $cek_pool = $this->m_pool->where('nama_pool', $this->request->getVar('nama_pool'))->select('id_pool')->first();
                if ($cek_pool) {
                    session()->setFlashdata('warning', ['Tidak dapat menambah data karena Pool telah ada di dalam database']);
                    return redirect()->to('pool');
                } else {
                    $record = [
                        'nama_pool' => ucwords($this->request->getVar('nama_pool')),
                        'no_hp_pool' => $this->request->getVar('no_hp_pool'),
                        'email_pool' => $this->request->getVar('email_pool'),
                        'tgl_input' => date('Ymd'),
                    ];
                    $aksi = $this->m_pool->insert($record);
        
                    if($aksi != false) { //dibagian aksi tidak false atau ada isinya
                        $page_id = $aksi;
                        session()->setFlashdata('success', 'Pool berhasil ditambahkan');
                    } else {
                        session()->setFlashdata('warning', ['Pool gagal ditambahkan']);
                    }
                }
            }
        }
    
        $pool = $this->m_pool->select('id_pool, nama_pool, no_hp_pool, email_pool')->orderBy('id_pool', 'asc')->findAll();
    
        $data = [
            'pool' => $pool,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_pool', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function edit_pool($id_pool)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view

            $record = [
                'id_pool' => $id_pool,
                'nama_pool' => ucwords($this->request->getVar('nama_pool')),
                'no_hp_pool' => $this->request->getVar('no_hp_pool'),
                'email_pool' => $this->request->getVar('email_pool'),
                'tgl_input' => date('Ymd'),
            ];
            $aksi = $this->m_pool->save($record);

            session()->setFlashdata('success', 'Data pool berhasil di edit');
            return redirect()->to('pool');
        }

        $pool = $this->m_pool->where('id_pool', $id_pool)->orderBy('nama_pool', 'asc')->findAll();

        $data = [
            'id_pool' => $id_pool,
            'pool' => $pool,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_edit_pool', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function tujuan()
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('id_tujuan')) {
            $tujuan_id = $this->m_tujuan->tujuan_id($this->request->getVar('id_tujuan'));
            if($tujuan_id['id_tujuan']) {//memastikan bahwa ada data
                $aksi = $this->m_tujuan->delete_tujuan($this->request->getVar('id_tujuan'));
                if($aksi == true) {
                    $this->m_tujuan->query('ALTER TABLE tujuan AUTO_INCREMENT 1');
                    session()->setFlashdata('success', "Tujuan berhasil dihapus");
                } else {
                    session()->setFlashdata('warning', ['Tujuan gagal dihapus']);
                }
            }
            return redirect()->to('tujuan');
        }
    
        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            $aturan = [
                'nama_tujuan' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Tujuan tidak boleh kosong'
                    ]
                ],
            ];
            if(!$this->validate($aturan)) {
                session()->setFlashdata('warning', $this->validation->getErrors());
            } else {
                $cek_pool = $this->m_pool->where('nama_pool', $this->request->getVar('nama_pool'))->select('id_pool')->first();
                if ($cek_pool) {
                    session()->setFlashdata('warning', ['Tidak dapat menambah data karena Pool telah ada di dalam database']);
                    return redirect()->to('pool');
                } else {
                    $record = [
                        'nama_tujuan' => ucwords($this->request->getVar('nama_tujuan')),
                        'tgl_input' => date('Ymd'),
                    ];
                    $aksi = $this->m_tujuan->insert($record);
        
                    if($aksi != false) { //dibagian aksi tidak false atau ada isinya
                        $page_id = $aksi;
                        session()->setFlashdata('success', 'Tujuan berhasil ditambahkan');
                    } else {
                        session()->setFlashdata('warning', ['Tujuan gagal ditambahkan']);
                    }
                }
            }
        }
    
        $tujuan = $this->m_tujuan->select('id_tujuan, nama_tujuan')->orderBy('nama_tujuan', 'asc')->findAll();
    
        $data = [
            'tujuan' => $tujuan,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_tujuan', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function edit_tujuan($id_tujuan)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view

            $record = [
                'id_tujuan' => $id_tujuan,
                'nama_tujuan' => ucwords($this->request->getVar('nama_tujuan')),
                'tgl_input' => date('Ymd'),
            ];
            $aksi = $this->m_tujuan->save($record);

            session()->setFlashdata('success', 'Data tujuan berhasil di edit');
            return redirect()->to('tujuan');
        }

        $tujuan = $this->m_tujuan->where('id_tujuan', $id_tujuan)->orderBy('nama_tujuan', 'asc')->findAll();

        $data = [
            'id_tujuan' => $id_tujuan,
            'tujuan' => $tujuan,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_edit_tujuan', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function jam_kend()
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_jam_kend', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function jam_driv()
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_jam_driv', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function warning()
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 1) {

        } else if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 2) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('pasjalangs');
        }

        $timestamp = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $dtime = date('H:i:s');
        $time = (strtotime($timestamp));//+ 86400 detik buat nambah 1 hari

        $cek_email_delegasi = $this->m_email_delegasi->where('email_pengguna', session()->get('login_email'))->where('username', session()->get('username'))->select('id_pengguna, username, tanggal_jam_mulai, tanggal_jam_akhir')->orderBy('tanggal_jam_akhir', 'desc')->findAll();

        if (empty($cek_email_delegasi)){
            
        } else {
            if ($time > $cek_email_delegasi[0]['tanggal_jam_mulai']) {
                if ($time < $cek_email_delegasi[0]['tanggal_jam_akhir']) {
                
                } else {
                    session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                    return redirect()->to('logout');
                }
            } else {
                session()->setFlashdata('warning', ['Sesi email delegasi Anda telah berakhir']);
                return redirect()->to('logout');
            }
        }

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_admin', $data);
        echo view('master/v_warning', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function pasjalangs()
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');

        if ($admin_gs == 0) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('trans');
        } else if ($admin_gs == 1) {
            session()->setFlashdata('warning', ['Anda tidak memiliki akses ke alamat ini']);
            return redirect()->to('dept');
        } else if ($admin_gs == 2) {
            
        }

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_master_pasjalan', $data);
        echo view('master/v_pasjalangs', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function dashboard($id_transaksi)
    {
        $nik = session()->get('akun_nik');
        $niknm = session()->get('niknm');
        $role = session()->get('akun_role');
        $strorg = session()->get('strorg');
        $submit_pjum = $this->m_id->where('id_transaksi', $id_transaksi)->select('submit_pjum, kirim_pjum')->first();
        $submit_pb = $this->m_id->where('id_transaksi', $id_transaksi)->select('submit_pb, kirim_pb')->first();
        $kota = $this->m_id->where('id_transaksi', $id_transaksi)->select('kota')->first();

        $login = $this->m_id->where('id_transaksi', $id_transaksi)->select('login_by')->first();

        if ($role == 'admin' && $login['login_by'] != $niknm || $role == 'user' && $login['login_by'] != $niknm) {
            session()->setFlashdata('warning', ['Id transaksi sedang diedit, harap menunggu beberapa saat lagi']);
            return redirect()->to("transaksi");
        } elseif ($role == 'treasury' || $role == 'gs') {

        }

        if($role == 'admin') {
            $dataPost = $this->m_id->getPostId($id_transaksi, substr($strorg, 0, 4));
        } elseif($role == 'user') {
            $dataPost = $this->m_id->getId($id_transaksi, $nik);
        } elseif($role == 'treasury') {
            $dataPost = $this->m_id->getTreasuryDashboard($id_transaksi);
        } elseif($role == 'gs') {
            $dataPost = $this->m_id->getGSDashboard($id_transaksi);
        }
        if(empty($dataPost)) {
            return redirect()-> to("transaksi");
        }
        $data = $dataPost;

        if ($role == 'gs' && $submit_pjum['submit_pjum'] < 2 && $submit_pb['submit_pb'] < 2) {
            return redirect()-> to("transaksi");
        }

        $personil = $this->m_personil->getDataAllId($id_transaksi);
        $negara = $this->m_negara_tujuan->getDataAllId($id_transaksi);

        if(empty($personil)) {
            session()->setFlashdata('warning', ['Silahkan lengkapi data perjalanan dinas luar negeri']);
            return redirect()-> to("tambahpersonil/".$id_transaksi);
        }

        if(empty($negara)) {
            session()->setFlashdata('warning', ['Silahkan lengkapi data perjalanan dinas luar negeri']);
            return redirect()-> to("tambahnegara/".$id_transaksi);
        }

        $id_transaksi = [
            'id_transaksi' => $id_transaksi,
        ];
        session()->set($id_transaksi);

        $id_transaksi = session()->get('id_transaksi');

        if($role == 'user') {
            $hasil = $this->m_id->listId($id_transaksi, $nik);
        } else {
            $hasil = $this->m_id->listIdTransaksi($id_transaksi);
        }

        $cek = $this->m_kategori->cek($id_transaksi);

        if($role == 'treasury' || $role == 'gs') {
            if(empty($cek)) {
                return redirect()-> to("transaksi");
            }
        }

        $cekdatapb = $this->m_kategori->cekpb($id_transaksi, 'PB');

        if($role == 'treasury' && $submit_pjum['submit_pjum'] == 0 && empty($cekdatapb)) {
            return redirect()-> to("transaksi");
        }

        $nopb = $this->m_pb->where('id_transaksi', $id_transaksi)->select('created_by')->findAll();

        session()->set('url_transaksi', current_url());

        $data = [
            'header' => "Dashboard",
            'id_transaksi' => $this->m_id->getDataAll(),
            'hasil' => $hasil,
            'neg' => $this->m_negara_tujuan->getDataAllId($id_transaksi),
            'submit_pjum' => $submit_pjum['submit_pjum'],
            'submit_pb' => $submit_pb['submit_pb'],
            'kirim_pjum' => $submit_pjum['kirim_pjum'],
            'kirim_pb' => $submit_pb['kirim_pb'],
            'role' => $role,
            'kota1' => $this->m_id->kota($id_transaksi),
            'solo' => $kota['kota'],
            'role' => $role,
            'id' => $id_transaksi,
            'cek' => $cek,
            'cekpjum' => $this->m_kategori->alldataId($id_transaksi, 'PJUM'),
            'cekpb' => $this->m_kategori->alldataId($id_transaksi, 'PB'),
            'biayapb' => $cekdatapb,
            'nopb' => $nopb,
        ];
        echo view('ui/v_header', $data);
        echo view('admin/v_dashboard', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get());
    }

    public function biayaxls()
    {
        return $this->response->download('./formatExcel/Format Biaya Perjalanan Dinas Luar Negeri.xls', null);
    }

    public function biayaxlsx()
    {
        return $this->response->download('./formatExcel/Format Biaya Perjalanan Dinas Luar Negeri.xlsx', null);
    }

    public function supportxls()
    {
        return $this->response->download('./formatExcel/Format Biaya Support Perjalanan Dinas Luar Negeri.xls', null);
    }

    public function support($id_transaksi)
    {
        $nik = session()->get('akun_nik');
        $role = session()->get('akun_role');
        $kota = $this->m_id->where('id_transaksi', $id_transaksi)->select('kota')->first();
        if($role != 'gs') {
            return redirect()-> to("transaksi");
        }
        if($kota['kota'] == 'Surakarta') {
            return redirect()-> to("transaksi");
        }

        $strorg = session()->get('strorg');
        if($role == 'gs') {
            $dataPost = $this->m_id->getGSDashboard($id_transaksi);
        } else {
            return redirect()-> to("transaksi");
        }

        if(empty($dataPost)) {
            return redirect()-> to("transaksi");
        }
        $data = $dataPost;

        if($role == 'gs') {
            $id = $this->m_id->getGS($id_transaksi);
        }

        $submit = $this->m_id->where('id_transaksi', $id_transaksi)->select('submit_pjum, submit_pb')->first();

        if ($role == 'gs' && $submit['submit_pjum'] == 4 && $submit['submit_pb'] > 2) {

        } else {
            return redirect()-> to("transaksi");
        }

        if ($role == 'gs') {
            if($this->request->getMethod() == 'post') {
                $data = [
                    'id_transaksi' => $id_transaksi,
                    'submit_pb' => 2,
                ];
                $this->m_id->save($data);
                session()->setFlashdata('success', 'Silahkan Revisi Data PB');
                return redirect()->to('dashboard/'.$id_transaksi);
            }
        }

        $sumBiaya = $this->m_biaya->totalsupport($id_transaksi);

        $submit_pjum = $this->m_id->where('id_transaksi', $id_transaksi)->select('submit_pjum')->first();
        $submit_pb = $this->m_id->where('id_transaksi', $id_transaksi)->select('submit_pb')->first();

        $cek = $this->m_kategori->cek($id_transaksi);

        if($role == 'treasury' || $role == 'gs') {
            if(empty($cek)) {
                return redirect()-> to("transaksi");
            }
        }

        $nopb = $this->m_pb->where('id_transaksi', $id_transaksi)->select('created_by')->findAll();

        session()->set('url_transaksi', current_url());

        $data = [
            'header' => "Biaya Support",
            'id_transaksi' => $this->m_id->getDataAll(),
            'id' => $id,
            'neg' => $this->m_negara_tujuan->getDataAllId($id_transaksi),
            'dataPost' => $dataPost,
            'submit' => $submit['submit_pb'],
            'kota' => $this->m_id->kota($id_transaksi),
            'solo' => $kota['kota'],
            'kategori' => $this->m_kategori->alldatasupport($id_transaksi),
            'biaya' => $this->m_biaya->alldatasupport($id_transaksi),
            'total' => $sumBiaya,
            'role' => $role,
            'submit_pjum' => $submit_pjum['submit_pjum'],
            'submit_pb' => $submit_pb['submit_pb'],
            'cek' => $cek,
            'cekpjum' => $this->m_kategori->alldataId($id_transaksi, 'PJUM'),
            'cekpb' => $this->m_kategori->alldataId($id_transaksi, 'PB'),
            'nopb' => $nopb,
        ];

        echo view('admin/v_support', $data);
        // print_r(session()->get());
    }

    public function gsselesaisupport($jenis_biaya, $id_transaksi)
    {
        $role = session()->get('akun_role');
        if($role != 'gs') {
            return redirect()-> to("transaksi");
        }
        $ceksup = $this->m_kategori->cekpb($id_transaksi, 'Support');

        if(empty($ceksup)) {
            session()->setFlashdata('warning', ['Tambahkan biaya support terlebih dahulu untuk melakukan submit data']);
            return redirect()->to('support/'.$id_transaksi);
        } else {
            $data = [
                'id_transaksi' => $id_transaksi,
                'submit_pb' => 4,
            ];
            $this->m_id->save($data);
            session()->setFlashdata('success', 'Biaya Support berhasil disubmit');
            return redirect()->to('support/'.$id_transaksi);
        }
    }

    public function editbiayasupport($id_biaya, $id_kategori, $id_transaksi, $jenis_biaya)
    {
        $nik = session()->get('akun_nik');
        $niknm = session()->get('niknm');
        $role = session()->get('akun_role');
        $strorg = session()->get('strorg');

        $submit_pjum = $this->m_id->where('id_transaksi', $id_transaksi)->select('submit_pjum, kirim_pjum')->first();
        $submit_pb = $this->m_id->where('id_transaksi', $id_transaksi)->select('submit_pb, kirim_pb')->first();

        if($role == 'admin') {
            $dataPost = $this->m_id->getPostId($id_transaksi, substr($strorg, 0, 4));
        } elseif($role == 'user') {
            $dataPost = $this->m_id->getId($id_transaksi, $nik);
        } elseif($role == 'treasury' && $submit_pb['submit_pb'] == 0) {
            $dataPost = $this->m_id->getTreasuryDash($id_transaksi);
        } elseif($role == 'treasury' && $submit_pb['submit_pb'] == 1) {
            $dataPost = $this->m_id->getTreasuryDashboard($id_transaksi);
        } elseif($role == 'gs') {
            $dataPost = $this->m_id->getGSDashboard($id_transaksi);
        }

        if(empty($dataPost)) {
            return redirect()-> to("transaksi");
        }

        $data = $dataPost;

        if($role == 'treasury') {
            $id = $this->m_id->getTreasury($id_transaksi);
        } elseif($role == 'gs') {
            $id = $this->m_id->getGS($id_transaksi);
        } else {
            $id = $this->m_id->getPostId($id_transaksi, substr($strorg, 0, 4));
        }

        $ses = [
            'id_transaksi' => $id_transaksi,
            'jenis_biaya' => $jenis_biaya,
        ];

        session()->set($ses);

        if ($role == 'treasury' && $submit_pjum['submit_pjum'] == 0 && $submit_pb['submit_pb'] == 0) {
            return redirect()-> to("transaksi");
        } elseif ($role == 'gs' && $submit_pb['submit_pb'] != 3) {
            return redirect()-> to("transaksi");
        }

        $valas = $this->m_biaya->valassupport($id_transaksi);
        $kode_valas = $this->m_biaya->kode_valassupport($id_transaksi);

        $kota = $this->m_id->where('id_transaksi', $id_transaksi)->select('kota')->first();
        $cek = $this->m_kategori->cek($id_transaksi);

        if($role == 'treasury' || $role == 'gs') {
            if(empty($cek)) {
                return redirect()-> to("transaksi");
            }
        }

        if($this->request->getMethod() == 'post') {
            $nik = session()->get('akun_nik');
            $timestamp = date('Y-m-d H:i:s');
            $biaya = $this->request->getVar('biaya');
            $comma = ',';
            $number = preg_replace('/[^0-9\\-]+/', '', $biaya);
            if(strpos($biaya, $comma) !== false) {
                $string = $number / 100;
            } else {
                $string = $number;
            }

            $data = [
                'id_biaya' => $id_biaya,
                'biaya' => $string,
            ];
            $this->m_biaya->save($data);

            session()->setFlashdata('success', 'Biaya Support berhasil diubah');
            return redirect()->to('support/'.$id_transaksi);
        }

        $nopb = $this->m_pb->where('id_transaksi', $id_transaksi)->select('created_by')->findAll();

        session()->set('url_transaksi', current_url());

        $data = [
            'header' => "Edit Biaya Support",
            'id' => $id,
            'dataPost' => $dataPost,
            'kategori' => $this->m_kategori->alldatasupport($id_transaksi),
            'biaya' => $this->m_biaya->alldatasupportedit($id_transaksi, $id_biaya),
            'role' => $role,
            'kota' => $this->m_id->kota($id_transaksi),
            'solo' => $kota['kota'],
            'submit' => $submit_pb['submit_pb'],
            'submit_pjum' => $submit_pjum['submit_pjum'],
            'submit_pb' => $submit_pb['submit_pb'],
            'cek' => $cek,
            'cekpjum' => $this->m_kategori->alldataId($id_transaksi, 'PJUM'),
            'cekpb' => $this->m_kategori->alldataId($id_transaksi, 'PB'),
            'index' => count((array)$valas),
            'kode_valas' => $kode_valas,
            'neg' => $this->m_negara_tujuan->getDataAllId($id_transaksi),
            'nopb' => $nopb,
        ];
        echo view('proses/support/v_biayasupport', $data);
        // print_r(session()->get());
        // echo Currencies::getSymbol('THB');
    }

    public function importsupport($id_transaksi)
    {
        $file = $this->request->getFile('file_excel_support');
        $ext = $file->getClientExtension();

        if($ext == 'xls') {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        $spreadsheet = $render->load($file);
        $sheet = $spreadsheet->getActiveSheet()->toArray();
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        $a = 1;
        $b = 1;
        $c = 1;
        $d = 1;

        for($r = 7; $r < $highestRow; $r++) {
            if(!empty($sheet[$r][4])) {
                $tanggal = $sheet[$r][1];
                if (empty($tanggal)) {
                    $tanggal = null;
                }
                $kategori = $sheet[$r][2];
                if (empty($kategori)) {
                    $kategori = null;
                }
                $jumlah_personil = $sheet[$r][3];
                if (empty($jumlah_personil)) {
                    $jumlah_personil = null;
                }

                $cekdata = $this->m_kategori->ceksupport($id_transaksi, $r + 1);

                $data = [];
                if(empty($cekdata)) {
                    $data = [
                        'baris' => $r + 1,
                        'id_transaksi' => $id_transaksi,
                        'jenis_biaya' => 'Support',
                        'tanggal' => $tanggal,
                        'kategori' => $kategori,
                        'jumlah_personil' => $jumlah_personil,
                    ];
                    $this->m_kategori->insert($data);
                } elseif(empty($cekdata['baris'])) {
                    continue;
                } elseif($id_transaksi == $cekdata['id_transaksi'] && $r + 1 == $cekdata['baris']) {
                    $resultKategori = $this->m_kategori->where('id_transaksi', $id_transaksi)->where('baris', $r + 1)->where('jenis_biaya', 'Support')->select('id_kategori as id_kategori')->first();
                    $data = [
                        'id_kategori' => $resultKategori['id_kategori'],
                        'jenis_biaya' => 'Support',
                        'tanggal' => $tanggal,
                        'kategori' => $kategori,
                        'jumlah_personil' => $jumlah_personil,
                    ];
                    $this->m_kategori->distinct($data['id_kategori']);
                    $this->m_kategori->save($data);
                }

                $data = [];
                $kode_valas = $sheet[6][4];
                $kode_valas = strtoupper($kode_valas);
                $biaya = preg_replace("/[^0-9\.]/", "", $sheet[$r][4]);
                $id_valas = $this->m_valas->where('kode_valas', $kode_valas)->select('id_valas as id_valas')->first();
                $simbol = $this->m_valas->where('kode_valas', $kode_valas)->select('simbol as simbol')->first();

                if (empty($biaya)) {
                    $biaya = 0;
                }
                if (empty($kode_valas)) {
                    $kode_valas = null;
                    $id_valas = null;
                    $simbol = null;
                }

                $resultKategori = $this->m_kategori->where('id_transaksi', $id_transaksi)->where('baris', $r + 1)->where('jenis_biaya', 'Support')->select('id_kategori as id_kategori')->first();

                $cekvaluta = $this->m_biaya->ceksupport($id_transaksi, $r + 1);
                if(empty($cekvaluta)) {
                    $data = [
                        'id_kategori' => $resultKategori['id_kategori'],
                        'baris' => $r + 1,
                        'kolom' => 5,
                        'id_transaksi' => $id_transaksi,
                        'jenis_biaya' => 'Support',
                        'id_valas' => $id_valas,
                        'kode_valas' => $kode_valas,
                        'simbol' => $simbol,
                        'biaya' => $biaya,
                        'kategori' => $kategori,
                        'tanggal' => $tanggal,
                    ];
                    $this->m_biaya->insert($data);
                } elseif(empty($cekvaluta['baris'])) {

                } elseif($id_transaksi == $cekvaluta['id_transaksi'] && $r + 1 == $cekvaluta['baris']) {
                    $resultBiaya = $this->m_biaya->where('id_transaksi', $id_transaksi)->where('baris', $r + 1)->where('jenis_biaya', 'Support')->select('id_biaya as id_biaya')->first();
                    $data = [
                        'id_biaya' => $resultBiaya['id_biaya'],
                        'id_kategori' => $resultKategori['id_kategori'],
                        'jenis_biaya' => 'Support',
                        'id_valas' => $id_valas,
                        'kode_valas' => $kode_valas,
                        'simbol' => $simbol,
                        'biaya' => $biaya,
                        'kategori' => $kategori,
                        'tanggal' => $tanggal,
                    ];
                    $this->m_biaya->distinct($data['id_biaya']);
                    $this->m_biaya->save($data);
                }
            } else {
                $cekidkategori = $this->m_kategori->where('id_transaksi', $id_transaksi)->where('jenis_biaya', 'Support')->select('id_kategori as id_kategori')->findAll();
                foreach ($cekidkategori as $key => $value) {
                    $data_id = [
                        'id_kategori' => $value['id_kategori'],
                    ];
                    $this->m_kategori->deleteKategori($data_id['id_kategori']);
                }
            }
        }
        session()->setFlashdata('success', 'Biaya Support berhasil diimport');
        return redirect()->to('support/'.$id_transaksi);
    }

    public function importbiaya($id_transaksi)
    {
        $file = $this->request->getFile('file_excel_all');
        $ext = $file->getClientExtension();

        if($ext == 'xls') {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        $spreadsheet = $render->load($file);
        $sheet = $spreadsheet->getActiveSheet()->toArray();
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        $a = 1;
        $b = 1;
        $c = 1;
        $d = 1;

        for($kk = 9; $kk < $highestColumnIndex; ++$kk) {
            $nopjum[$kk] = $sheet[1][$kk];
            $nopb[$kk] = $sheet[2][$kk];
            $ko_val[$kk] = $sheet[6][$kk];

            $array = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB');

            if(empty($nopjum[$kk]) && empty($nopb[$kk])) {
                $alpha = $array[$kk];
                session()->setFlashdata('warning', ['Silahkan isi salah satu antara No PJUM atau No PB pada kolom '.$alpha.' baris ke 2 atau 3, lalu upload ulang file excel']);
                return redirect()->to('dashboard/'.$id_transaksi);
            }

            if(!empty($nopjum[$kk]) && !empty($nopb[$kk])) {
                $alpha = $array[$kk];
                session()->setFlashdata('warning', ['Silahkan isi salah satu antara No PJUM atau No PB pada kolom '.$alpha.' baris ke 2 atau 3, lalu upload ulang file excel']);
                return redirect()->to('dashboard/'.$id_transaksi);
            }

            if (empty($ko_val[$kk])) {
                $alpha = $array[$kk];
                session()->setFlashdata('warning', ['Silahkan isi Valas pada kolom '.$alpha.' baris ke 7, lalu upload ulang file excel']);
                return redirect()->to('dashboard/'.$id_transaksi);
            }

            if (!empty($nopjum[$kk])) {
                $jenis_biaya[$kk] = 'PJUM';
            } elseif (!empty($nopb[$kk])) {
                $jenis_biaya[$kk] = 'PB';
            }
        }

        for($r = 7; $r < $highestRow; $r++) {
            $tanggal[$r] = $sheet[$r][1];
            $kategori[$r] = $sheet[$r][2];
            $status[$r] = $sheet[$r][3];
            $ref[$r] = $sheet[$r][4];
            $note[$r] = $sheet[$r][5];
            $negara_tujuan[$r] = $sheet[$r][6];
            $negara_trading[$r] = $sheet[$r][7];
            $jumlah_personil[$r] = $sheet[$r][8];

            if(empty($tanggal[$r])) {
                $bar = $r + 1;
                session()->setFlashdata('warning', ['Silahkan isi tanggal pada kolom B baris ke '.$bar.', lalu upload ulang file excel']);
                return redirect()-> to('dashboard/'.$id_transaksi);
            }

            if(empty($kategori[$r])) {
                $bar = $r + 1;
                session()->setFlashdata('warning', ['Silahkan isi kategori pada kolom C baris ke '.$bar.', lalu upload ulang file excel']);
                return redirect()-> to('dashboard/'.$id_transaksi);
            }

            if(empty($negara_tujuan[$r]) && empty($negara_trading[$r])) {
                $bar = $r + 1;
                session()->setFlashdata('warning', ['Silahkan isi antara negara tujuan atau negara transit pada kolom G atau kolom H baris ke '.$bar.', lalu upload ulang file excel']);
                return redirect()-> to('dashboard/'.$id_transaksi);
            }

            if(empty($jumlah_personil[$r])) {
                $bar = $r + 1;
                session()->setFlashdata('warning', ['Silahkan isi jumlah personil pada kolom I baris ke '.$bar.', lalu upload ulang file excel']);
                return redirect()-> to('dashboard/'.$id_transaksi);
            }

            for($k = 9; $k < $highestColumnIndex; ++$k) {
                if(!empty($sheet[$r][$k])) {
                    $kode_valas = $sheet[6][$k];
                    $kode_valas = strtoupper($kode_valas);
                    $id_valas = $this->m_valas->where('kode_valas', $kode_valas)->select('id_valas as id_valas')->first();
                    $simbol = $this->m_valas->where('kode_valas', $kode_valas)->select('simbol as simbol')->first();

                    $biaya = preg_replace("/[^0-9\.]/", "", $sheet[$r][$k]);

                    if(empty($id_valas['id_valas'])) {
                        $alpha = $array[$k];
                        session()->setFlashdata('warning', ['Silahkan mengisikan valas pada kolom '.$alpha.' sesuai dengan list pada sheet Master, lalu upload ulang file excel']);
                        return redirect()-> to('dashboard/'.$id_transaksi);
                    }

                    $pum = preg_replace("/[^0-9\.]/", "", $sheet[3][$k]);
                    if (empty($pum)) {
                        $pum = 0;
                    }
                    $uang_kembali = preg_replace("/[^0-9\.]/", "", $sheet[4][$k]);
                    if (empty($uang_kembali)) {
                        $uang_kembali = 0;
                    }

                    $array = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB');

                    $cekdata = $this->m_kategori->cekdata($id_transaksi, $r + 1, $jenis_biaya[$k]);
                    $cekdatapjum = $this->m_kategori->cekdata($id_transaksi, $r + 1, 'PJUM');
                    $cekdatapb = $this->m_kategori->cekdata($id_transaksi, $r + 1, 'PB');
                    $cekdata1 = $this->m_kategori->cekdata1($id_transaksi, $r + 1);

                    $cekvaluta = $this->m_biaya->cekvaluta($id_transaksi, $r + 1, $k + 1, $jenis_biaya[$k]);
                    $cekvalutapjum = $this->m_biaya->cekvaluta($id_transaksi, $r + 1, $k + 1, 'PJUM');
                    $cekvalutapb = $this->m_biaya->cekvaluta($id_transaksi, $r + 1, $k + 1, 'PB');
                    $cekvaluta1 = $this->m_biaya->cekvaluta1($id_transaksi, $r + 1, $k + 1);

                    $ceknomorpjum = $this->m_pjum->ceknomor($id_transaksi, $k + 1);
                    $ceknomorpb = $this->m_pb->ceknomor($id_transaksi, $k + 1);

                    $nik = session()->get('akun_nik');
                    $role = session()->get('akun_role');
                    $timestamp = date('Y-m-d H:i:s');

                    $submit_pjum = $this->m_id->where('id_transaksi', $id_transaksi)->select('submit_pjum, kirim_pjum')->first();
                    $submit_pb = $this->m_id->where('id_transaksi', $id_transaksi)->select('submit_pb, kirim_pb')->first();

                    if($jenis_biaya[$k] == 'PJUM') {
                        // echo '(PJUM - ';
                        if($role == 'admin' || $role == 'user') {

                            if(empty($cekdatapb['jenis_biaya'])) {
                                // echo 'PB KOSONG - ';
                            } else {
                                if ($submit_pjum['kirim_pjum'] == 1 && $cekdatapb['created_by'] != '05080' && $cekdatapb['tanggal'] == $tanggal[$r] && $cekdatapb['kategori'] == $kategori[$r] && $cekdatapb['status'] == $status[$r] && $cekdatapb['ref'] == $ref[$r] && $cekdatapb['note'] == $note[$r] && $cekdatapb['negara_tujuan'] == $negara_tujuan[$r] && $cekdatapb['negara_trading'] == $negara_trading[$r] && $cekdatapb['jumlah_personil'] == $jumlah_personil[$r]) {

                                } else {
                                    $bar = $r + 1;
                                    session()->setFlashdata('warning', ['Pada baris ke '.$bar.' telah ada data yang diupload oleh Treasury, silahkan export file kembali dan tambahkan data pada baris yang kosong lalu upload ulang file excel']);
                                    return redirect()-> to('dashboard/'.$id_transaksi);
                                }
                            }

                            if(empty($ceknomorpjum)) {
                                $nomor_pjum = [
                                    'id_transaksi' => $id_transaksi,
                                    'kolom' => $k + 1,
                                    'nomor' => $nopjum[$k],
                                    'created_by' => $nik,
                                ];
                                $this->m_pjum->insert($nomor_pjum);
                            } elseif($id_transaksi == $ceknomorpjum['id_transaksi'] && $k + 1 == $ceknomorpjum['kolom']) {
                                $ceknompjum = $this->m_pjum->ceknopjum($id_transaksi, $k + 1);
                                foreach ($ceknompjum as $key => $value) {
                                    $val_pjum = $this->m_pjum->where('id_pjum', $value['id_pjum'])->select('id_valas')->first();

                                    if($id_valas['id_valas'] != $val_pjum['id_valas']) {
                                        $tanggal_pjum_edit = [
                                            'id_pjum' => $value['id_pjum'],
                                            'tanggal' => null,
                                        ];
                                        $this->m_pjum->save($tanggal_pjum_edit);
                                        $this->m_kurs->deletekurspjum($tanggal_pjum_edit['id_pjum']);
                                        $this->m_kurs->query('ALTER TABLE kurs AUTO_INCREMENT 1');
                                    }

                                    $nomor_pjum_edit = [
                                        'id_pjum' => $value['id_pjum'],
                                        'nomor' => $nopjum[$k],
                                        'id_valas' => $id_valas['id_valas'],
                                        'kode_valas' => $kode_valas,
                                        'edited_at' => $timestamp,
                                        'edited_by' => $nik,
                                    ];
                                    $this->m_pjum->save($nomor_pjum_edit);
                                }
                            }

                            $cekpum = $this->m_pum->cekpum($id_transaksi, $k + 1);
                            if(empty($cekpum)) {
                                $ceknopjum = $this->m_pjum->ceknomorpjum($id_transaksi, $nopjum[$k]);
                                foreach ($ceknopjum as $key => $value) {
                                    $data_pum = [
                                        'id_pjum' => $value['id_pjum'],
                                        'kolom' => $k + 1,
                                        'pum' => $pum,
                                        'uang_kembali' => $uang_kembali,
                                        'id_transaksi' => $id_transaksi,
                                        'id_valas' => $id_valas['id_valas'],
                                        'kode_valas' => $kode_valas,
                                        'simbol' => $simbol['simbol'],
                                        'created_by' => $nik,
                                    ];
                                    $this->m_pum->insert($data_pum);
                                }
                            } elseif($id_transaksi == $cekpum['id_transaksi'] && $k + 1 == $cekpum['kolom']) {
                                $resultPum = $this->m_pum->where('id_transaksi', $id_transaksi)->where('kolom', $k + 1)->select('id_pum as id_pum')->first();
                                $kirim = $this->m_id->where('id_transaksi', $id_transaksi)->select('kirim_pjum')->first();
                                $ceknopjum = $this->m_pjum->ceknomorpjum($id_transaksi, $nopjum[$k]);
                                foreach ($ceknopjum as $key => $value) {
                                    if ($kirim['kirim_pjum'] == 0) {
                                        $data_pum_edit = [
                                            'id_pum' => $resultPum['id_pum'],
                                            'id_pjum' => $value['id_pjum'],
                                            'pum' => $pum,
                                            'uang_kembali' => $uang_kembali,
                                            'id_valas' => $id_valas['id_valas'],
                                            'kode_valas' => $kode_valas,
                                            'simbol' => $simbol['simbol'],
                                        ];
                                        $this->m_pum->distinct($data_pum_edit['id_pum']);
                                        $this->m_pum->save($data_pum_edit);
                                    } else {
                                        $data_pum_edit = [
                                            'id_pum' => $resultPum['id_pum'],
                                            'id_pjum' => $value['id_pjum'],
                                            'pum' => $pum,
                                            'uang_kembali' => $uang_kembali,
                                            'id_valas' => $id_valas['id_valas'],
                                            'kode_valas' => $kode_valas,
                                            'simbol' => $simbol['simbol'],
                                            'edited_at' => $timestamp,
                                            'edited_by' => $nik,
                                        ];
                                        $this->m_pum->distinct($data_pum_edit['id_pum']);
                                        $this->m_pum->save($data_pum_edit);
                                    }
                                }
                            }

                            if(empty($cekdata1)) {
                                // echo 'KATEGORI KOSONG - ';
                                $ceknopjum = $this->m_pjum->ceknomorpjum($id_transaksi, $nopjum[$k]);
                                foreach ($ceknopjum as $key => $value) {
                                    $data_kategori_pjum = [
                                        'baris' => $r + 1,
                                        'id_transaksi' => $id_transaksi,
                                        'id_pjum' => $value['id_pjum'],
                                        'id_pb' => null,
                                        'jenis_biaya' => 'PJUM',
                                        'kategori' => $kategori[$r],
                                        'tanggal' => $tanggal[$r],
                                        'note' => $note[$r],
                                        'ref' => $ref[$r],
                                        'jumlah_personil' => $jumlah_personil[$r],
                                        'negara_tujuan' => $negara_tujuan[$r],
                                        'negara_trading' => $negara_trading[$r],
                                        'created_by' => $nik,
                                    ];
                                    $this->m_kategori->insert($data_kategori_pjum);
                                }
                            } else {
                                // echo 'KATEGORI ISI - ';
                                if(empty($cekdatapb['jenis_biaya'])) {
                                    // echo 'PB KOSONG - ';
                                } else {
                                    // echo 'PB ISI - ';
                                    if($id_transaksi == $cekdatapb['id_transaksi'] && $r + 1 == $cekdatapb['baris'] && 'PB' == $cekdatapb['jenis_biaya']) {
                                        $resultKategori = $this->m_kategori->where('id_transaksi', $id_transaksi)->where('baris', $r + 1)->where('jenis_biaya', 'PB')->select('id_kategori as id_kategori')->first();
                                        $kirim = $this->m_id->where('id_transaksi', $id_transaksi)->select('kirim_pjum')->first();
                                        $ceknopjum = $this->m_pjum->ceknomorpjum($id_transaksi, $nopjum[$k]);
                                        foreach ($ceknopjum as $key => $value) {
                                            if ($kirim['kirim_pjum'] == 0) {
                                                $data_kategori_pjum_edit = [
                                                    'id_kategori' => $resultKategori['id_kategori'],
                                                    'baris' => $r + 1,
                                                    'id_pjum' => $value['id_pjum'],
                                                    'id_pb' => null,
                                                    'jenis_biaya' => 'PJUM',
                                                    'kategori' => $kategori[$r],
                                                    'tanggal' => $tanggal[$r],
                                                    'note' => $note[$r],
                                                    'ref' => $ref[$r],
                                                    'jumlah_personil' => $jumlah_personil[$r],
                                                    'negara_tujuan' => $negara_tujuan[$r],
                                                    'negara_trading' => $negara_trading[$r],
                                                ];
                                                $this->m_kategori->distinct($data_kategori_pjum_edit['id_kategori']);
                                                $this->m_kategori->save($data_kategori_pjum_edit);
                                            } else {
                                                $data_kategori_pjum_edit = [
                                                    'id_kategori' => $resultKategori['id_kategori'],
                                                    'baris' => $r + 1,
                                                    'id_pjum' => $value['id_pjum'],
                                                    'id_pb' => null,
                                                    'jenis_biaya' => 'PJUM',
                                                    'kategori' => $kategori[$r],
                                                    'tanggal' => $tanggal[$r],
                                                    'note' => $note[$r],
                                                    'ref' => $ref[$r],
                                                    'jumlah_personil' => $jumlah_personil[$r],
                                                    'negara_tujuan' => $negara_tujuan[$r],
                                                    'negara_trading' => $negara_trading[$r],
                                                    'edited_at' => $timestamp,
                                                    'edited_by' => $nik,
                                                ];
                                                $this->m_kategori->distinct($data_kategori_pjum_edit['id_kategori']);
                                                $this->m_kategori->save($data_kategori_pjum_edit);
                                            }
                                        }
                                    }
                                }

                                if(empty($cekdatapjum['jenis_biaya'])) {
                                    // echo 'PJUM KOSONG) ';
                                } else {
                                    // echo 'PJUM ISI) ';
                                    if($id_transaksi == $cekdatapjum['id_transaksi'] && $r + 1 == $cekdatapjum['baris'] && 'PJUM' == $cekdatapjum['jenis_biaya']) {
                                        $resultKategori = $this->m_kategori->where('id_transaksi', $id_transaksi)->where('baris', $r + 1)->where('jenis_biaya', 'PJUM')->select('id_kategori as id_kategori')->first();
                                        $kirim = $this->m_id->where('id_transaksi', $id_transaksi)->select('kirim_pjum')->first();
                                        $ceknopjum = $this->m_pjum->ceknomorpjum($id_transaksi, $nopjum[$k]);
                                        foreach ($ceknopjum as $key => $value) {
                                            if ($kirim['kirim_pjum'] == 0) {
                                                $data_kategori_pjum_edit = [
                                                    'id_kategori' => $resultKategori['id_kategori'],
                                                    'baris' => $r + 1,
                                                    'id_pjum' => $value['id_pjum'],
                                                    'id_pb' => null,
                                                    'jenis_biaya' => 'PJUM',
                                                    'kategori' => $kategori[$r],
                                                    'tanggal' => $tanggal[$r],
                                                    'note' => $note[$r],
                                                    'ref' => $ref[$r],
                                                    'jumlah_personil' => $jumlah_personil[$r],
                                                    'negara_tujuan' => $negara_tujuan[$r],
                                                    'negara_trading' => $negara_trading[$r],
                                                ];
                                                $this->m_kategori->distinct($data_kategori_pjum_edit['id_kategori']);
                                                $this->m_kategori->save($data_kategori_pjum_edit);
                                            } else {
                                                $data_kategori_pjum_edit = [
                                                    'id_kategori' => $resultKategori['id_kategori'],
                                                    'baris' => $r + 1,
                                                    'id_pjum' => $value['id_pjum'],
                                                    'id_pb' => null,
                                                    'jenis_biaya' => 'PJUM',
                                                    'kategori' => $kategori[$r],
                                                    'tanggal' => $tanggal[$r],
                                                    'note' => $note[$r],
                                                    'ref' => $ref[$r],
                                                    'jumlah_personil' => $jumlah_personil[$r],
                                                    'negara_tujuan' => $negara_tujuan[$r],
                                                    'negara_trading' => $negara_trading[$r],
                                                    'edited_at' => $timestamp,
                                                    'edited_by' => $nik,
                                                ];
                                                $this->m_kategori->distinct($data_kategori_pjum_edit['id_kategori']);
                                                $this->m_kategori->save($data_kategori_pjum_edit);
                                            }
                                        }
                                    }
                                }
                            }

                            if(empty($cekvaluta1)) {
                                // echo 'BIAYA KOSONG - ';
                                $ceknopjum = $this->m_pjum->ceknomorpjum($id_transaksi, $nopjum[$k]);
                                $resultKategori = $this->m_kategori->where('id_transaksi', $id_transaksi)->where('baris', $r + 1)->where('jenis_biaya', 'PJUM')->select('id_kategori as id_kategori')->first();
                                $resultPum = $this->m_pum->where('id_transaksi', $id_transaksi)->where('kolom', $k + 1)->select('id_pum as id_pum')->first();
                                foreach ($ceknopjum as $key => $value) {
                                    $data_biaya_pjum = [
                                        'id_kategori' => $resultKategori['id_kategori'],
                                        'id_pum' => $resultPum['id_pum'],
                                        'id_pjum' => $value['id_pjum'],
                                        'id_pb' => null,
                                        'baris' => $r + 1,
                                        'kolom' => $k + 1,
                                        'kategori' => $kategori[$r],
                                        'id_transaksi' => $id_transaksi,
                                        'id_valas' => $id_valas['id_valas'],
                                        'kode_valas' => $kode_valas,
                                        'simbol' => $simbol['simbol'],
                                        'jenis_biaya' => 'PJUM',
                                        'biaya' => $biaya,
                                        'tanggal' => $tanggal[$r],
                                        'created_by' => $nik,
                                    ];
                                    $this->m_biaya->insert($data_biaya_pjum);
                                }
                            } else {
                                // echo 'BIAYA ISI - ';
                                if(empty($cekvalutapb['jenis_biaya'])) {
                                    // echo 'PB KOSONG - ';
                                } else {
                                    // echo 'PB ISI - ';
                                    if ($submit_pjum['kirim_pjum'] == 1 && $cekvalutapb['created_by'] == '05080' && $cekvalutapb['biaya'] == $biaya) {

                                    } else {
                                        $bar = $r + 1;
                                        session()->setFlashdata('warning', ['Tidak dapat melakukan edit biaya pada baris ke '.$bar.', karena merupakan data yang diupload oleh Treasury']);
                                        return redirect()-> to('dashboard/'.$id_transaksi);
                                    }

                                    if ($submit_pjum['kirim_pjum'] == 1 && $cekvalutapb['created_by'] == '05080' && $cekvalutapb['id_valas'] == $id_valas['id_valas']) {

                                    } else {
                                        $alpha = $array[$k];
                                        session()->setFlashdata('warning', ['Tidak dapat melakukan edit valas pada kolom '.$alpha.', karena merupakan data yang diupload oleh Treasury']);
                                        return redirect()-> to('dashboard/'.$id_transaksi);
                                    }

                                    if($id_transaksi == $cekvalutapb['id_transaksi'] && $r + 1 == $cekvalutapb['baris'] && $k + 1 == $cekvalutapb['kolom'] && 'PB' == $cekvalutapb['jenis_biaya']) {
                                        $resultBiaya = $this->m_biaya->where('id_transaksi', $id_transaksi)->where('baris', $r + 1)->where('kolom', $k + 1)->where('jenis_biaya', 'PB')->select('id_biaya as id_biaya')->first();
                                        $resultPum = $this->m_pum->where('id_transaksi', $id_transaksi)->where('kolom', $k + 1)->select('id_pum as id_pum')->first();
                                        $kirim = $this->m_id->where('id_transaksi', $id_transaksi)->select('kirim_pjum')->first();
                                        $ceknopjum = $this->m_pjum->ceknomorpjum($id_transaksi, $nopjum[$k]);
                                        foreach ($ceknopjum as $key => $value) {
                                            if ($kirim['kirim_pjum'] == 0) {
                                                $data_biaya_pjum_edit = [
                                                    'id_biaya' => $resultBiaya['id_biaya'],
                                                    'id_pum' => $resultPum['id_pum'],
                                                    'id_pjum' => $value['id_pjum'],
                                                    'id_pb' => null,
                                                    'kategori' => $kategori[$r],
                                                    'id_valas' => $id_valas['id_valas'],
                                                    'kode_valas' => $kode_valas,
                                                    'simbol' => $simbol['simbol'],
                                                    'jenis_biaya' => 'PJUM',
                                                    'biaya' => $biaya,
                                                    'tanggal' => $tanggal[$r],
                                                ];
                                                $this->m_biaya->distinct($data_biaya_pjum_edit['id_biaya']);
                                                $this->m_biaya->save($data_biaya_pjum_edit);
                                            } else {
                                                $data_biaya_pjum_edit = [
                                                    'id_biaya' => $resultBiaya['id_biaya'],
                                                    'id_pum' => $resultPum['id_pum'],
                                                    'id_pjum' => $value['id_pjum'],
                                                    'id_pb' => null,
                                                    'kategori' => $kategori[$r],
                                                    'id_valas' => $id_valas['id_valas'],
                                                    'kode_valas' => $kode_valas,
                                                    'simbol' => $simbol['simbol'],
                                                    'jenis_biaya' => 'PJUM',
                                                    'biaya' => $biaya,
                                                    'tanggal' => $tanggal[$r],
                                                    'edited_at' => $timestamp,
                                                    'edited_by' => $nik,
                                                ];
                                                $this->m_biaya->distinct($data_biaya_pjum_edit['id_biaya']);
                                                $this->m_biaya->save($data_biaya_pjum_edit);
                                            }
                                        }
                                    }
                                }

                                if(empty($cekvalutapjum['jenis_biaya'])) {
                                    // echo 'PJUM KOSONG) ';
                                } else {
                                    // echo 'PJUM ISI) ';
                                    if($id_transaksi == $cekvalutapjum['id_transaksi'] && $r + 1 == $cekvalutapjum['baris'] && $k + 1 == $cekvalutapjum['kolom'] && 'PJUM' == $cekvalutapjum['jenis_biaya']) {
                                        $resultBiaya = $this->m_biaya->where('id_transaksi', $id_transaksi)->where('baris', $r + 1)->where('kolom', $k + 1)->where('jenis_biaya', 'PJUM')->select('id_biaya as id_biaya')->first();
                                        $resultPum = $this->m_pum->where('id_transaksi', $id_transaksi)->where('kolom', $k + 1)->select('id_pum as id_pum')->first();
                                        $kirim = $this->m_id->where('id_transaksi', $id_transaksi)->select('kirim_pjum')->first();
                                        $ceknopjum = $this->m_pjum->ceknomorpjum($id_transaksi, $nopjum[$k]);
                                        foreach ($ceknopjum as $key => $value) {
                                            if ($kirim['kirim_pjum'] == 0) {
                                                $data_biaya_pjum_edit = [
                                                    'id_biaya' => $resultBiaya['id_biaya'],
                                                    'id_pum' => $resultPum['id_pum'],
                                                    'id_pjum' => $value['id_pjum'],
                                                    'id_pb' => null,
                                                    'kategori' => $kategori[$r],
                                                    'id_valas' => $id_valas['id_valas'],
                                                    'kode_valas' => $kode_valas,
                                                    'simbol' => $simbol['simbol'],
                                                    'jenis_biaya' => 'PJUM',
                                                    'biaya' => $biaya,
                                                    'tanggal' => $tanggal[$r],
                                                ];
                                                $this->m_biaya->distinct($data_biaya_pjum_edit['id_biaya']);
                                                $this->m_biaya->save($data_biaya_pjum_edit);
                                            } else {
                                                $data_biaya_pjum_edit = [
                                                    'id_biaya' => $resultBiaya['id_biaya'],
                                                    'id_pum' => $resultPum['id_pum'],
                                                    'id_pjum' => $value['id_pjum'],
                                                    'id_pb' => null,
                                                    'kategori' => $kategori[$r],
                                                    'id_valas' => $id_valas['id_valas'],
                                                    'kode_valas' => $kode_valas,
                                                    'simbol' => $simbol['simbol'],
                                                    'jenis_biaya' => 'PJUM',
                                                    'biaya' => $biaya,
                                                    'tanggal' => $tanggal[$r],
                                                    'edited_at' => $timestamp,
                                                    'edited_by' => $nik,
                                                ];
                                                $this->m_biaya->distinct($data_biaya_pjum_edit['id_biaya']);
                                                $this->m_biaya->save($data_biaya_pjum_edit);
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            $cekpjum = $this->m_kategori->cekdata2($id_transaksi, 'PJUM');

                            if(empty($ceknomorpjum)) {
                                if($submit_pjum['kirim_pjum'] == 1 && empty($cekpjum)) {

                                } elseif($submit_pjum['kirim_pjum'] == 1 && !empty($nopjum[$k]) && $cekpjum['created_by'] != '05080' && $cekpjum['tanggal'] == $tanggal[$r] && $cekpjum['kategori'] == $kategori[$r] && $cekpjum['status'] == $status[$r] && $cekpjum['ref'] == $ref[$r] && $cekpjum['note'] == $note[$r] && $cekpjum['negara_tujuan'] == $negara_tujuan[$r] && $cekpjum['negara_trading'] == $negara_trading[$r] && $cekpjum['jumlah_personil'] == $jumlah_personil[$r]) {

                                } else {
                                    session()->setFlashdata('warning', ['Tidak dapat menambahkan data PJUM']);
                                    return redirect()-> to('dashboard/'.$id_transaksi);
                                }
                            } elseif($id_transaksi == $ceknomorpjum['id_transaksi'] && $k + 1 == $ceknomorpjum['kolom'] && $nopjum[$k] == $ceknomorpjum['nomor']) {
                                if($submit_pjum['kirim_pjum'] == 1 && empty($cekpjum)) {

                                } elseif($submit_pjum['kirim_pjum'] == 1 && !empty($nopjum[$k]) && $cekpjum['created_by'] != '05080' && $cekpjum['tanggal'] == $tanggal[$r] && $cekpjum['kategori'] == $kategori[$r] && $cekpjum['status'] == $status[$r] && $cekpjum['ref'] == $ref[$r] && $cekpjum['note'] == $note[$r] && $cekpjum['negara_tujuan'] == $negara_tujuan[$r] && $cekpjum['negara_trading'] == $negara_trading[$r] && $cekpjum['jumlah_personil'] == $jumlah_personil[$r]) {

                                } else {
                                    session()->setFlashdata('warning', ['Tidak dapat menambahkan data PJUM']);
                                    return redirect()-> to('dashboard/'.$id_transaksi);
                                }
                            }

                            $ceknompjum = $this->m_pjum->ceknopjum($id_transaksi, $k + 1);
                            foreach ($ceknompjum as $key => $value) {
                                $val_pjum = $this->m_pjum->where('id_pjum', $value['id_pjum'])->select('created_by, nomor')->first();

                                if($submit_pjum['kirim_pjum'] == 1 && $val_pjum['created_by'] != '05080' && $val_pjum['nomor'] == $nopjum[$k]) {

                                } elseif($submit_pjum['kirim_pjum'] == 1 && $val_pjum['created_by'] != '05080' && $val_pjum['nomor'] != $nopjum[$k]) {
                                    $alpha = $array[$k];
                                    session()->setFlashdata('warning', ['Tidak dapat melakukan edit no PJUM pada kolom '.$alpha.', karena merupakan data yang diupload oleh User Bagian']);
                                    return redirect()-> to('dashboard/'.$id_transaksi);
                                }
                            }

                            $cekpum = $this->m_pum->cekpum($id_transaksi, $k + 1);
                            if(empty($cekpum)) {

                            } else {
                                if ($submit_pjum['kirim_pjum'] == 1 && $cekpum['created_by'] != '05080' && $cekpum['uang_kembali'] == $uang_kembali && $cekpum['pum'] == $pum) {

                                } else {
                                    $alpha = $array[$k];
                                    session()->setFlashdata('warning', ['Tidak dapat melakukan edit data PJUM pada kolom '.$alpha.', karena merupakan data yang diupload oleh User Bagian']);
                                    return redirect()-> to('dashboard/'.$id_transaksi);
                                }
                            }

                            if(empty($cekdatapjum['jenis_biaya'])) {
                                // echo 'PJUM KOSONG - ';
                            } else {
                                if ($submit_pjum['kirim_pjum'] == 1 && $cekdatapjum['created_by'] != '05080' && $cekdatapjum['tanggal'] == $tanggal[$r] && $cekdatapjum['kategori'] == $kategori[$r] && $cekdatapjum['status'] == $status[$r] && $cekdatapjum['ref'] == $ref[$r] && $cekdatapjum['note'] == $note[$r] && $cekdatapjum['negara_tujuan'] == $negara_tujuan[$r] && $cekdatapjum['negara_trading'] == $negara_trading[$r] && $cekdatapjum['jumlah_personil'] == $jumlah_personil[$r]) {

                                } else {
                                    $bar = $r + 1;
                                    session()->setFlashdata('warning', ['Pada baris ke '.$bar.' telah ada data yang diupload oleh User Bagian, silahkan export file kembali dan tambahkan data pada baris yang kosong lalu upload ulang file excel']);
                                    return redirect()-> to('dashboard/'.$id_transaksi);
                                }
                            }

                            if(empty($cekvalutapjum['jenis_biaya'])) {
                                // echo 'PJUM KOSONG - ';
                            } else {
                                // echo 'PJUM ISI - ';
                                if ($submit_pjum['kirim_pjum'] == 1 && $cekvalutapjum['created_by'] != '05080' && $cekvalutapjum['biaya'] == $biaya) {

                                } else {
                                    $bar = $r + 1;
                                    session()->setFlashdata('warning', ['Tidak dapat melakukan edit biaya pada baris ke '.$bar.', karena merupakan data yang diupload oleh User Bagian']);
                                    return redirect()-> to('dashboard/'.$id_transaksi);
                                }

                                if ($submit_pjum['kirim_pjum'] == 1 && $cekvalutapjum['created_by'] != '05080' && $cekvalutapjum['id_valas'] == $id_valas['id_valas']) {

                                } else {
                                    $alpha = $array[$k];
                                    session()->setFlashdata('warning', ['Tidak dapat melakukan edit valas pada kolom '.$alpha.', karena merupakan data yang diupload oleh User Bagian']);
                                    return redirect()-> to('dashboard/'.$id_transaksi);
                                }
                            }
                        }
                    } else {
                        // Biaya PB
                        // echo '(PB - ';
                        $bar = $r + 1;
                        if(empty($status[$r])) {
                            session()->setFlashdata('warning', ['Silahkan isi apakah Dibelikan GS atau Beli Sendiri pada kolom D baris ke '.$bar.', lalu upload ulang file excel']);
                            return redirect()-> to('dashboard/'.$id_transaksi);
                        }

                        if($kategori[$r] != 'Tiket Pesawat' && $kategori[$r] != 'Bagasi Pesawat' && $kategori[$r] != 'Porter Pesawat' && $kategori[$r] != 'Hotel' && $kategori[$r] != 'Makan dan Minum' && $kategori[$r] != 'Transportasi' && $kategori[$r] != 'Laundry' && $kategori[$r] != 'Lain-lain' && $kategori[$r] != 'Tukar Uang Keluar' && $kategori[$r] != 'Tukar Uang Masuk' && $kategori[$r] != 'Kembalian') {
                            session()->setFlashdata('warning', ['Silahkan memilih kategori pada kolom C baris ke '.$bar.' sesuai dengan list pada sheet Master, lalu upload ulang file excel']);
                            return redirect()-> to('dashboard/'.$id_transaksi);
                        } elseif($status[$r] != 'Dibelikan GS' && $status[$r] != 'Beli Sendiri') {
                            session()->setFlashdata('warning', ['Silahkan isi status apakah Dibelikan GS atau Beli Sendiri pada kolom D baris ke '.$bar.', lalu upload ulang file excel']);
                            return redirect()-> to('dashboard/'.$id_transaksi);
                        } else {

                        }

                        if($role == 'admin' || $role == 'user') {
                            if(empty($cekdatapb['jenis_biaya'])) {
                                // 'PB KOSONG) ';
                            } else {
                                // 'PB ISI) ';
                                if ($submit_pjum['kirim_pjum'] == 1 && $cekdatapb['created_by'] == '05080' && $cekdatapb['tanggal'] == $tanggal[$r] && $cekdatapb['kategori'] == $kategori[$r] && $cekdatapb['status'] == $status[$r] && $cekdatapb['ref'] == $ref[$r] && $cekdatapb['note'] == $note[$r] && $cekdatapb['negara_tujuan'] == $negara_tujuan[$r] && $cekdatapb['negara_trading'] == $negara_trading[$r] && $cekdatapb['jumlah_personil'] == $jumlah_personil[$r]) {

                                } elseif ($submit_pjum['kirim_pjum'] == 0) {

                                } else {
                                    $bar = $r + 1;
                                    session()->setFlashdata('warning', ['Tidak dapat melakukan edit data pada baris ke '.$bar.', karena merupakan data yang diupload oleh Treasury']);
                                    return redirect()-> to('dashboard/'.$id_transaksi);
                                }
                            }

                            $cekpb = $this->m_kategori->cekdata2($id_transaksi, 'PB');

                            if(empty($ceknomorpb)) {
                                if($submit_pjum['kirim_pjum'] == 1 && empty($cekpb)) {

                                } elseif($submit_pjum['kirim_pjum'] == 1 && $cekpb['created_by'] == '05080') {
                                    session()->setFlashdata('warning', ['Silahkan hubungi Treasury untuk menambahkan data PB']);
                                    return redirect()-> to('dashboard/'.$id_transaksi);
                                } else {
                                    $nomor_pb = [
                                        'id_transaksi' => $id_transaksi,
                                        'kolom' => $k + 1,
                                        'nomor' => $nopb[$k],
                                        'created_by' => $nik,
                                    ];
                                    $this->m_pb->insert($nomor_pb);
                                }
                            } elseif($id_transaksi == $ceknomorpb['id_transaksi'] && $k + 1 == $ceknomorpb['kolom']) {
                                $ceknompb = $this->m_pb->ceknopb($id_transaksi, $k + 1);
                                foreach ($ceknompb as $key => $value) {
                                    $val_pb = $this->m_pb->where('id_pb', $value['id_pb'])->select('id_valas, created_by, nomor')->first();

                                    if($id_valas['id_valas'] != $val_pb['id_valas']) {
                                        $tanggal_pb_edit = [
                                            'id_pb' => $value['id_pb'],
                                            'tanggal' => null,
                                        ];
                                        $this->m_pb->save($tanggal_pb_edit);
                                        $this->m_kurs->deletekurspb($tanggal_pb_edit['id_pb']);
                                        $this->m_kurs->query('ALTER TABLE kurs AUTO_INCREMENT 1');
                                    }


                                    if($submit_pjum['kirim_pjum'] == 1 && $val_pb['created_by'] == '05080' && $val_pb['nomor'] == $nopb[$k]) {

                                    } elseif($submit_pjum['kirim_pjum'] == 1 && $val_pb['created_by'] == '05080' && $val_pb['nomor'] != $nopb[$k]) {
                                        $alpha = $array[$k];
                                        session()->setFlashdata('warning', ['Tidak dapat melakukan edit no PB pada kolom '.$alpha.', karena merupakan data yang diupload oleh Treasury']);
                                        return redirect()-> to('dashboard/'.$id_transaksi);
                                    } else {
                                        $nomor_pb_edit = [
                                            'id_pb' => $value['id_pb'],
                                            'nomor' => $nopb[$k],
                                            'id_valas' => $id_valas['id_valas'],
                                            'kode_valas' => $kode_valas,
                                            'edited_at' => $timestamp,
                                            'edited_by' => $nik,
                                        ];
                                        $this->m_pb->save($nomor_pb_edit);
                                    }
                                }
                            }

                            if(empty($cekdata1)) {
                                // echo 'KATEGORI KOSONG - ';
                                $ceknopb = $this->m_pb->ceknomorpb($id_transaksi, $nopb[$k]);
                                foreach ($ceknopb as $key => $value) {
                                    $data_kategori_pb = [
                                        'baris' => $r + 1,
                                        'id_transaksi' => $id_transaksi,
                                        'id_pjum' => null,
                                        'id_pb' => $value['id_pb'],
                                        'jenis_biaya' => 'PB',
                                        'kategori' => $kategori[$r],
                                        'status' => $status[$r],
                                        'tanggal' => $tanggal[$r],
                                        'note' => $note[$r],
                                        'ref' => $ref[$r],
                                        'jumlah_personil' => $jumlah_personil[$r],
                                        'negara_tujuan' => $negara_tujuan[$r],
                                        'negara_trading' => $negara_trading[$r],
                                        'created_by' => $nik,
                                    ];
                                    $this->m_kategori->insert($data_kategori_pb);
                                }
                            } else {
                                // echo 'KATEGORI ISI - ';
                                if(empty($cekdatapjum['jenis_biaya'])) {
                                    // echo 'PJUM KOSONG - ';
                                } else {
                                    // echo 'PJUM ISI - ';
                                    if($id_transaksi == $cekdatapjum['id_transaksi'] && $r + 1 == $cekdatapjum['baris'] && 'PJUM' == $cekdatapjum['jenis_biaya']) {
                                        $resultKategori = $this->m_kategori->where('id_transaksi', $id_transaksi)->where('baris', $r + 1)->where('jenis_biaya', 'PJUM')->select('id_kategori as id_kategori')->first();
                                        $kirim = $this->m_id->where('id_transaksi', $id_transaksi)->select('kirim_pb')->first();
                                        $ceknopb = $this->m_pb->ceknomorpb($id_transaksi, $nopb[$k]);
                                        foreach ($ceknopb as $key => $value) {
                                            $val_pb = $this->m_pb->where('id_pb', $value['id_pb'])->select('created_by')->first();
                                            if($val_pb['created_by'] == '05080') {
                                                continue;
                                            } else {
                                                if ($kirim['kirim_pb'] == 0) {
                                                    $data_kategori_pb_edit = [
                                                        'id_kategori' => $resultKategori['id_kategori'],
                                                        'baris' => $r + 1,
                                                        'id_pjum' => null,
                                                        'id_pb' => $value['id_pb'],
                                                        'jenis_biaya' => 'PB',
                                                        'kategori' => $kategori[$r],
                                                        'status' => $status[$r],
                                                        'tanggal' => $tanggal[$r],
                                                        'note' => $note[$r],
                                                        'ref' => $ref[$r],
                                                        'jumlah_personil' => $jumlah_personil[$r],
                                                        'negara_tujuan' => $negara_tujuan[$r],
                                                        'negara_trading' => $negara_trading[$r],
                                                    ];
                                                    $this->m_kategori->distinct($data_kategori_pb_edit['id_kategori']);
                                                    $this->m_kategori->save($data_kategori_pb_edit);
                                                } else {
                                                    $data_kategori_pb_edit = [
                                                        'id_kategori' => $resultKategori['id_kategori'],
                                                        'baris' => $r + 1,
                                                        'id_pjum' => null,
                                                        'id_pb' => $value['id_pb'],
                                                        'jenis_biaya' => 'PB',
                                                        'kategori' => $kategori[$r],
                                                        'status' => $status[$r],
                                                        'tanggal' => $tanggal[$r],
                                                        'note' => $note[$r],
                                                        'ref' => $ref[$r],
                                                        'jumlah_personil' => $jumlah_personil[$r],
                                                        'negara_tujuan' => $negara_tujuan[$r],
                                                        'negara_trading' => $negara_trading[$r],
                                                        'edited_at' => $timestamp,
                                                        'edited_by' => $nik,
                                                    ];
                                                    $this->m_kategori->distinct($data_kategori_pb_edit['id_kategori']);
                                                    $this->m_kategori->save($data_kategori_pb_edit);
                                                }
                                            }
                                        }
                                    }
                                }

                                if(empty($cekdatapb['jenis_biaya'])) {
                                    // 'PB KOSONG) ';
                                } else {
                                    // 'PB ISI) ';
                                    if($id_transaksi == $cekdatapb['id_transaksi'] && $r + 1 == $cekdatapb['baris'] && 'PB' == $cekdatapb['jenis_biaya']) {
                                        $resultKategori = $this->m_kategori->where('id_transaksi', $id_transaksi)->where('baris', $r + 1)->where('jenis_biaya', 'PB')->select('id_kategori as id_kategori')->first();
                                        $kirim = $this->m_id->where('id_transaksi', $id_transaksi)->select('kirim_pb')->first();
                                        $ceknopb = $this->m_pb->ceknomorpb($id_transaksi, $nopb[$k]);
                                        foreach ($ceknopb as $key => $value) {
                                            $val_pb = $this->m_pb->where('id_pb', $value['id_pb'])->select('created_by')->first();
                                            if($val_pb['created_by'] == '05080') {
                                                continue;
                                            } else {
                                                if ($kirim['kirim_pb'] == 0) {
                                                    $data_kategori_pb_edit = [
                                                        'id_kategori' => $resultKategori['id_kategori'],
                                                        'baris' => $r + 1,
                                                        'id_pjum' => null,
                                                        'id_pb' => $value['id_pb'],
                                                        'jenis_biaya' => 'PB',
                                                        'kategori' => $kategori[$r],
                                                        'status' => $status[$r],
                                                        'tanggal' => $tanggal[$r],
                                                        'note' => $note[$r],
                                                        'ref' => $ref[$r],
                                                        'jumlah_personil' => $jumlah_personil[$r],
                                                        'negara_tujuan' => $negara_tujuan[$r],
                                                        'negara_trading' => $negara_trading[$r],
                                                    ];
                                                    $this->m_kategori->distinct($data_kategori_pb_edit['id_kategori']);
                                                    $this->m_kategori->save($data_kategori_pb_edit);
                                                } else {
                                                    $data_kategori_pb_edit = [
                                                        'id_kategori' => $resultKategori['id_kategori'],
                                                        'baris' => $r + 1,
                                                        'id_pjum' => null,
                                                        'id_pb' => $value['id_pb'],
                                                        'jenis_biaya' => 'PB',
                                                        'kategori' => $kategori[$r],
                                                        'status' => $status[$r],
                                                        'tanggal' => $tanggal[$r],
                                                        'note' => $note[$r],
                                                        'ref' => $ref[$r],
                                                        'jumlah_personil' => $jumlah_personil[$r],
                                                        'negara_tujuan' => $negara_tujuan[$r],
                                                        'negara_trading' => $negara_trading[$r],
                                                        'edited_at' => $timestamp,
                                                        'edited_by' => $nik,
                                                    ];
                                                    $this->m_kategori->distinct($data_kategori_pb_edit['id_kategori']);
                                                    $this->m_kategori->save($data_kategori_pb_edit);
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            if(empty($cekvaluta1)) {
                                // echo 'BIAYA KOSONG - ';
                                $ceknopb = $this->m_pb->ceknomorpb($id_transaksi, $nopb[$k]);
                                $resultKategori = $this->m_kategori->where('id_transaksi', $id_transaksi)->where('baris', $r + 1)->where('jenis_biaya', 'PB')->select('id_kategori as id_kategori')->first();
                                foreach ($ceknopb as $key => $value) {
                                    $data_biaya_pb = [
                                        'id_kategori' => $resultKategori['id_kategori'],
                                        'id_pum' => null,
                                        'id_pjum' => null,
                                        'id_pb' => $value['id_pb'],
                                        'baris' => $r + 1,
                                        'kolom' => $k + 1,
                                        'kategori' => $kategori[$r],
                                        'id_transaksi' => $id_transaksi,
                                        'id_valas' => $id_valas['id_valas'],
                                        'kode_valas' => $kode_valas,
                                        'simbol' => $simbol['simbol'],
                                        'jenis_biaya' => 'PB',
                                        'biaya' => $biaya,
                                        'tanggal' => $tanggal[$r],
                                        'created_by' => $nik,
                                    ];
                                    $this->m_biaya->insert($data_biaya_pb);
                                }
                            } else {
                                // echo 'BIAYA ISI - ';
                                if(empty($cekvalutapjum['jenis_biaya'])) {
                                    // echo 'PJUM KOSONG - ';
                                } else {
                                    // echo 'PJUM ISI - ';
                                    if($id_transaksi == $cekvalutapjum['id_transaksi'] && $r + 1 == $cekvalutapjum['baris'] && $k + 1 == $cekvalutapjum['kolom'] && 'PJUM' == $cekvalutapjum['jenis_biaya']) {
                                        $resultBiaya = $this->m_biaya->where('id_transaksi', $id_transaksi)->where('baris', $r + 1)->where('kolom', $k + 1)->where('jenis_biaya', 'PJUM')->select('id_biaya as id_biaya')->first();
                                        $kirim = $this->m_id->where('id_transaksi', $id_transaksi)->select('kirim_pb')->first();
                                        $ceknopb = $this->m_pb->ceknomorpb($id_transaksi, $nopb[$k]);
                                        foreach ($ceknopb as $key => $value) {
                                            $val_pb = $this->m_pb->where('id_pb', $value['id_pb'])->select('created_by')->first();
                                            if($val_pb['created_by'] == '05080') {
                                                continue;
                                            } else {
                                                if ($kirim['kirim_pb'] == 0) {
                                                    $data_biaya_pb_edit = [
                                                        'id_biaya' => $resultBiaya['id_biaya'],
                                                        'id_pum' => null,
                                                        'id_pjum' => null,
                                                        'id_pb' => $value['id_pb'],
                                                        'kategori' => $kategori[$r],
                                                        'id_valas' => $id_valas['id_valas'],
                                                        'kode_valas' => $kode_valas,
                                                        'simbol' => $simbol['simbol'],
                                                        'jenis_biaya' => 'PB',
                                                        'biaya' => $biaya,
                                                        'tanggal' => $tanggal[$r],
                                                    ];
                                                    $this->m_biaya->distinct($data_biaya_pb_edit['id_biaya']);
                                                    $this->m_biaya->save($data_biaya_pb_edit);
                                                } else {
                                                    $data_biaya_pb_edit = [
                                                        'id_biaya' => $resultBiaya['id_biaya'],
                                                        'id_pum' => null,
                                                        'id_pjum' => null,
                                                        'id_pb' => $value['id_pb'],
                                                        'kategori' => $kategori[$r],
                                                        'id_valas' => $id_valas['id_valas'],
                                                        'kode_valas' => $kode_valas,
                                                        'simbol' => $simbol['simbol'],
                                                        'jenis_biaya' => 'PB',
                                                        'biaya' => $biaya,
                                                        'tanggal' => $tanggal[$r],
                                                        'edited_at' => $timestamp,
                                                        'edited_by' => $nik,
                                                    ];
                                                    $this->m_biaya->distinct($data_biaya_pb_edit['id_biaya']);
                                                    $this->m_biaya->save($data_biaya_pb_edit);
                                                }
                                            }
                                        }
                                    }
                                }

                                if(empty($cekvalutapb['jenis_biaya'])) {
                                    // echo 'PB KOSONG) ';
                                } else {
                                    // echo 'PB ISI) ';
                                    if ($submit_pjum['kirim_pjum'] == 1 && $cekvalutapb['created_by'] == '05080' && $cekvalutapb['biaya'] == $biaya) {

                                    } elseif ($submit_pjum['kirim_pjum'] == 0) {

                                    } else {
                                        $bar = $r + 1;
                                        session()->setFlashdata('warning', ['Tidak dapat melakukan edit biaya pada baris ke '.$bar.', karena merupakan data yang diupload oleh Treasury']);
                                        return redirect()-> to('dashboard/'.$id_transaksi);
                                    }

                                    if ($submit_pjum['kirim_pjum'] == 1 && $cekvalutapb['created_by'] == '05080' && $cekvalutapb['id_valas'] == $id_valas['id_valas']) {

                                    } elseif ($submit_pjum['kirim_pjum'] == 0) {

                                    } else {
                                        $alpha = $array[$k];
                                        session()->setFlashdata('warning', ['Tidak dapat melakukan edit valas pada kolom '.$alpha.', karena merupakan data yang diupload oleh Treasury']);
                                        return redirect()-> to('dashboard/'.$id_transaksi);
                                    }

                                    if($id_transaksi == $cekvalutapb['id_transaksi'] && $r + 1 == $cekvalutapb['baris'] && $k + 1 == $cekvalutapb['kolom'] && 'PB' == $cekvalutapb['jenis_biaya']) {
                                        $resultBiaya = $this->m_biaya->where('id_transaksi', $id_transaksi)->where('baris', $r + 1)->where('kolom', $k + 1)->where('jenis_biaya', 'PB')->select('id_biaya as id_biaya')->first();
                                        $kirim = $this->m_id->where('id_transaksi', $id_transaksi)->select('kirim_pb')->first();
                                        $ceknopb = $this->m_pb->ceknomorpb($id_transaksi, $nopb[$k]);
                                        foreach ($ceknopb as $key => $value) {
                                            $val_pb = $this->m_pb->where('id_pb', $value['id_pb'])->select('created_by')->first();
                                            if($val_pb['created_by'] == '05080') {
                                                continue;
                                            } else {
                                                if ($kirim['kirim_pb'] == 0) {
                                                    $data_biaya_pb_edit = [
                                                        'id_biaya' => $resultBiaya['id_biaya'],
                                                        'id_pum' => null,
                                                        'id_pjum' => null,
                                                        'id_pb' => $value['id_pb'],
                                                        'kategori' => $kategori[$r],
                                                        'id_valas' => $id_valas['id_valas'],
                                                        'kode_valas' => $kode_valas,
                                                        'simbol' => $simbol['simbol'],
                                                        'jenis_biaya' => 'PB',
                                                        'biaya' => $biaya,
                                                        'tanggal' => $tanggal[$r],
                                                    ];
                                                    $this->m_biaya->distinct($data_biaya_pb_edit['id_biaya']);
                                                    $this->m_biaya->save($data_biaya_pb_edit);
                                                } else {
                                                    $data_biaya_pb_edit = [
                                                        'id_biaya' => $resultBiaya['id_biaya'],
                                                        'id_pum' => null,
                                                        'id_pjum' => null,
                                                        'id_pb' => $value['id_pb'],
                                                        'kategori' => $kategori[$r],
                                                        'id_valas' => $id_valas['id_valas'],
                                                        'kode_valas' => $kode_valas,
                                                        'simbol' => $simbol['simbol'],
                                                        'jenis_biaya' => 'PB',
                                                        'biaya' => $biaya,
                                                        'tanggal' => $tanggal[$r],
                                                        'edited_at' => $timestamp,
                                                        'edited_by' => $nik,
                                                    ];
                                                    $this->m_biaya->distinct($data_biaya_pb_edit['id_biaya']);
                                                    $this->m_biaya->save($data_biaya_pb_edit);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } elseif($role == 'treasury') {
                            if(empty($cekdatapjum['jenis_biaya'])) {
                                // echo 'PJUM KOSONG - ';
                            } else {
                                if ($submit_pjum['kirim_pjum'] == 1 && $cekdatapjum['created_by'] != '05080' && $cekdatapjum['tanggal'] == $tanggal[$r] && $cekdatapjum['kategori'] == $kategori[$r] && $cekdatapjum['status'] == $status[$r] && $cekdatapjum['ref'] == $ref[$r] && $cekdatapjum['note'] == $note[$r] && $cekdatapjum['negara_tujuan'] == $negara_tujuan[$r] && $cekdatapjum['negara_trading'] == $negara_trading[$r] && $cekdatapjum['jumlah_personil'] == $jumlah_personil[$r]) {

                                } else {
                                    $bar = $r + 1;
                                    session()->setFlashdata('warning', ['Pada baris ke '.$bar.' telah ada data yang diupload oleh User Bagian, silahkan export file kembali dan tambahkan data pada baris yang kosong lalu upload ulang file excel']);
                                    return redirect()-> to('dashboard/'.$id_transaksi);
                                }
                            }

                            if(empty($ceknomorpb)) {
                                $nomor_pb = [
                                    'id_transaksi' => $id_transaksi,
                                    'kolom' => $k + 1,
                                    'nomor' => $nopb[$k],
                                    'created_by' => '05080',
                                ];
                                $this->m_pb->insert($nomor_pb);

                            // $data = [
                            //     'id_transaksi' => $id_transaksi,
                            //     'kirim_pb' => 1,
                            // ];
                            // $this->m_id->save($data);
                            } elseif($id_transaksi == $ceknomorpb['id_transaksi'] && $k + 1 == $ceknomorpb['kolom']) {
                                $ceknompb = $this->m_pb->ceknopb($id_transaksi, $k + 1);
                                foreach ($ceknompb as $key => $value) {
                                    $val_pb = $this->m_pb->where('id_pb', $value['id_pb'])->select('id_valas')->first();

                                    if($id_valas['id_valas'] != $val_pb['id_valas']) {
                                        $tanggal_pb_edit = [
                                            'id_pb' => $value['id_pb'],
                                            'tanggal' => null,
                                        ];
                                        $this->m_pb->save($tanggal_pb_edit);
                                        $this->m_kurs->deletekurspb($tanggal_pb_edit['id_pb']);
                                        $this->m_kurs->query('ALTER TABLE kurs AUTO_INCREMENT 1');
                                    }

                                    $nomor_pb_edit = [
                                        'id_pb' => $value['id_pb'],
                                        'nomor' => $nopb[$k],
                                        'id_valas' => $id_valas['id_valas'],
                                        'kode_valas' => $kode_valas,
                                        'edited_at' => $timestamp,
                                        'edited_by' => '05080',
                                    ];
                                    $this->m_pb->save($nomor_pb_edit);
                                }
                            }

                            if(empty($cekdata1)) {
                                // echo 'KATEGORI KOSONG - ';
                                $ceknopb = $this->m_pb->ceknomorpb($id_transaksi, $nopb[$k]);
                                foreach ($ceknopb as $key => $value) {
                                    $data_kategori_pb = [
                                        'baris' => $r + 1,
                                        'id_transaksi' => $id_transaksi,
                                        'id_pjum' => null,
                                        'id_pb' => $value['id_pb'],
                                        'jenis_biaya' => 'PB',
                                        'kategori' => $kategori[$r],
                                        'status' => $status[$r],
                                        'tanggal' => $tanggal[$r],
                                        'note' => $note[$r],
                                        'ref' => $ref[$r],
                                        'jumlah_personil' => $jumlah_personil[$r],
                                        'negara_tujuan' => $negara_tujuan[$r],
                                        'negara_trading' => $negara_trading[$r],
                                        'created_by' => '05080',
                                    ];
                                    $this->m_kategori->insert($data_kategori_pb);
                                }
                            } else {
                                // echo 'KATEGORI ISI - ';
                                if(empty($cekdatapjum['jenis_biaya'])) {
                                    // echo 'PJUM KOSONG - ';
                                } else {
                                    // echo 'PJUM ISI - ';
                                    if($id_transaksi == $cekdatapjum['id_transaksi'] && $r + 1 == $cekdatapjum['baris'] && 'PJUM' == $cekdatapjum['jenis_biaya']) {
                                        $resultKategori = $this->m_kategori->where('id_transaksi', $id_transaksi)->where('baris', $r + 1)->where('jenis_biaya', 'PJUM')->select('id_kategori as id_kategori')->first();
                                        $kirim = $this->m_id->where('id_transaksi', $id_transaksi)->select('kirim_pb')->first();
                                        $ceknopb = $this->m_pb->ceknomorpb($id_transaksi, $nopb[$k]);
                                        foreach ($ceknopb as $key => $value) {
                                            if ($kirim['kirim_pb'] == 0) {
                                                $data_kategori_pb_edit = [
                                                    'id_kategori' => $resultKategori['id_kategori'],
                                                    'baris' => $r + 1,
                                                    'id_pjum' => null,
                                                    'id_pb' => $value['id_pb'],
                                                    'jenis_biaya' => 'PB',
                                                    'kategori' => $kategori[$r],
                                                    'status' => $status[$r],
                                                    'tanggal' => $tanggal[$r],
                                                    'note' => $note[$r],
                                                    'ref' => $ref[$r],
                                                    'jumlah_personil' => $jumlah_personil[$r],
                                                    'negara_tujuan' => $negara_tujuan[$r],
                                                    'negara_trading' => $negara_trading[$r],
                                                ];
                                                $this->m_kategori->distinct($data_kategori_pb_edit['id_kategori']);
                                                $this->m_kategori->save($data_kategori_pb_edit);
                                            } else {
                                                $data_kategori_pb_edit = [
                                                    'id_kategori' => $resultKategori['id_kategori'],
                                                    'baris' => $r + 1,
                                                    'id_pjum' => null,
                                                    'id_pb' => $value['id_pb'],
                                                    'jenis_biaya' => 'PB',
                                                    'kategori' => $kategori[$r],
                                                    'status' => $status[$r],
                                                    'tanggal' => $tanggal[$r],
                                                    'note' => $note[$r],
                                                    'ref' => $ref[$r],
                                                    'jumlah_personil' => $jumlah_personil[$r],
                                                    'negara_tujuan' => $negara_tujuan[$r],
                                                    'negara_trading' => $negara_trading[$r],
                                                    'edited_at' => $timestamp,
                                                    'edited_by' => '05080',
                                                ];
                                                $this->m_kategori->distinct($data_kategori_pb_edit['id_kategori']);
                                                $this->m_kategori->save($data_kategori_pb_edit);
                                            }
                                        }
                                    }
                                }

                                if(empty($cekdatapb['jenis_biaya'])) {
                                    // 'PB KOSONG) ';
                                } else {
                                    // 'PB ISI) ';
                                    if($id_transaksi == $cekdatapb['id_transaksi'] && $r + 1 == $cekdatapb['baris'] && 'PB' == $cekdatapb['jenis_biaya']) {
                                        $resultKategori = $this->m_kategori->where('id_transaksi', $id_transaksi)->where('baris', $r + 1)->where('jenis_biaya', 'PB')->select('id_kategori as id_kategori')->first();
                                        $kirim = $this->m_id->where('id_transaksi', $id_transaksi)->select('kirim_pb')->first();
                                        $ceknopb = $this->m_pb->ceknomorpb($id_transaksi, $nopb[$k]);
                                        foreach ($ceknopb as $key => $value) {
                                            if ($kirim['kirim_pb'] == 0) {
                                                $data_kategori_pb_edit = [
                                                    'id_kategori' => $resultKategori['id_kategori'],
                                                    'baris' => $r + 1,
                                                    'id_pjum' => null,
                                                    'id_pb' => $value['id_pb'],
                                                    'jenis_biaya' => 'PB',
                                                    'kategori' => $kategori[$r],
                                                    'status' => $status[$r],
                                                    'tanggal' => $tanggal[$r],
                                                    'note' => $note[$r],
                                                    'ref' => $ref[$r],
                                                    'jumlah_personil' => $jumlah_personil[$r],
                                                    'negara_tujuan' => $negara_tujuan[$r],
                                                    'negara_trading' => $negara_trading[$r],
                                                ];
                                                $this->m_kategori->distinct($data_kategori_pb_edit['id_kategori']);
                                                $this->m_kategori->save($data_kategori_pb_edit);
                                            } else {
                                                $data_kategori_pb_edit = [
                                                    'id_kategori' => $resultKategori['id_kategori'],
                                                    'baris' => $r + 1,
                                                    'id_pjum' => null,
                                                    'id_pb' => $value['id_pb'],
                                                    'jenis_biaya' => 'PB',
                                                    'kategori' => $kategori[$r],
                                                    'status' => $status[$r],
                                                    'tanggal' => $tanggal[$r],
                                                    'note' => $note[$r],
                                                    'ref' => $ref[$r],
                                                    'jumlah_personil' => $jumlah_personil[$r],
                                                    'negara_tujuan' => $negara_tujuan[$r],
                                                    'negara_trading' => $negara_trading[$r],
                                                    'edited_at' => $timestamp,
                                                    'edited_by' => '05080',
                                                ];
                                                $this->m_kategori->distinct($data_kategori_pb_edit['id_kategori']);
                                                $this->m_kategori->save($data_kategori_pb_edit);
                                            }
                                        }
                                    }
                                }
                            }

                            if(empty($cekvaluta1)) {
                                // echo 'BIAYA KOSONG - ';
                                $ceknopb = $this->m_pb->ceknomorpb($id_transaksi, $nopb[$k]);
                                $resultKategori = $this->m_kategori->where('id_transaksi', $id_transaksi)->where('baris', $r + 1)->where('jenis_biaya', 'PB')->select('id_kategori as id_kategori')->first();
                                foreach ($ceknopb as $key => $value) {
                                    $data_biaya_pb = [
                                        'id_kategori' => $resultKategori['id_kategori'],
                                        'id_pum' => null,
                                        'id_pjum' => null,
                                        'id_pb' => $value['id_pb'],
                                        'baris' => $r + 1,
                                        'kolom' => $k + 1,
                                        'kategori' => $kategori[$r],
                                        'id_transaksi' => $id_transaksi,
                                        'id_valas' => $id_valas['id_valas'],
                                        'kode_valas' => $kode_valas,
                                        'simbol' => $simbol['simbol'],
                                        'jenis_biaya' => 'PB',
                                        'biaya' => $biaya,
                                        'tanggal' => $tanggal[$r],
                                        'created_by' => '05080',
                                    ];
                                    $this->m_biaya->insert($data_biaya_pb);
                                }
                            } else {
                                // echo 'BIAYA ISI - ';
                                if(empty($cekvalutapjum['jenis_biaya'])) {
                                    // echo 'PJUM KOSONG - ';
                                } else {
                                    // echo 'PJUM ISI - ';
                                    if ($submit_pjum['kirim_pjum'] == 1 && $cekvalutapjum['created_by'] != '05080' && $cekvalutapjum['biaya'] == $biaya) {

                                    } else {
                                        $bar = $r + 1;
                                        session()->setFlashdata('warning', ['Tidak dapat melakukan edit biaya pada baris ke '.$bar.', karena merupakan data yang diupload oleh User Bagian']);
                                        return redirect()-> to('dashboard/'.$id_transaksi);
                                    }

                                    if ($submit_pjum['kirim_pjum'] == 1 && $cekvalutapjum['created_by'] != '05080' && $cekvalutapjum['id_valas'] == $id_valas['id_valas']) {

                                    } else {
                                        $alpha = $array[$k];
                                        session()->setFlashdata('warning', ['Tidak dapat melakukan edit valas pada kolom '.$alpha.', karena merupakan data yang diupload oleh User Bagian']);
                                        return redirect()-> to('dashboard/'.$id_transaksi);
                                    }

                                    if($id_transaksi == $cekvalutapjum['id_transaksi'] && $r + 1 == $cekvalutapjum['baris'] && $k + 1 == $cekvalutapjum['kolom'] && 'PJUM' == $cekvalutapjum['jenis_biaya']) {
                                        $resultBiaya = $this->m_biaya->where('id_transaksi', $id_transaksi)->where('baris', $r + 1)->where('kolom', $k + 1)->where('jenis_biaya', 'PJUM')->select('id_biaya as id_biaya')->first();
                                        $kirim = $this->m_id->where('id_transaksi', $id_transaksi)->select('kirim_pb')->first();
                                        $ceknopb = $this->m_pb->ceknomorpb($id_transaksi, $nopb[$k]);
                                        foreach ($ceknopb as $key => $value) {
                                            if ($kirim['kirim_pb'] == 0) {
                                                $data_biaya_pb_edit = [
                                                    'id_biaya' => $resultBiaya['id_biaya'],
                                                    'id_pum' => null,
                                                    'id_pjum' => null,
                                                    'id_pb' => $value['id_pb'],
                                                    'kategori' => $kategori[$r],
                                                    'id_valas' => $id_valas['id_valas'],
                                                    'kode_valas' => $kode_valas,
                                                    'simbol' => $simbol['simbol'],
                                                    'jenis_biaya' => 'PB',
                                                    'biaya' => $biaya,
                                                    'tanggal' => $tanggal[$r],
                                                ];
                                                $this->m_biaya->distinct($data_biaya_pb_edit['id_biaya']);
                                                $this->m_biaya->save($data_biaya_pb_edit);
                                            } else {
                                                $data_biaya_pb_edit = [
                                                    'id_biaya' => $resultBiaya['id_biaya'],
                                                    'id_pum' => null,
                                                    'id_pjum' => null,
                                                    'id_pb' => $value['id_pb'],
                                                    'kategori' => $kategori[$r],
                                                    'id_valas' => $id_valas['id_valas'],
                                                    'kode_valas' => $kode_valas,
                                                    'simbol' => $simbol['simbol'],
                                                    'jenis_biaya' => 'PB',
                                                    'biaya' => $biaya,
                                                    'tanggal' => $tanggal[$r],
                                                    'edited_at' => $timestamp,
                                                    'edited_by' => '05080',
                                                ];
                                                $this->m_biaya->distinct($data_biaya_pb_edit['id_biaya']);
                                                $this->m_biaya->save($data_biaya_pb_edit);
                                            }
                                        }
                                    }
                                }

                                if(empty($cekvalutapb['jenis_biaya'])) {
                                    // echo 'PB KOSONG) ';
                                } else {
                                    // echo 'PB ISI) ';
                                    if($id_transaksi == $cekvalutapb['id_transaksi'] && $r + 1 == $cekvalutapb['baris'] && $k + 1 == $cekvalutapb['kolom'] && 'PB' == $cekvalutapb['jenis_biaya']) {
                                        $resultBiaya = $this->m_biaya->where('id_transaksi', $id_transaksi)->where('baris', $r + 1)->where('kolom', $k + 1)->where('jenis_biaya', 'PB')->select('id_biaya as id_biaya')->first();
                                        $kirim = $this->m_id->where('id_transaksi', $id_transaksi)->select('kirim_pb')->first();
                                        $ceknopb = $this->m_pb->ceknomorpb($id_transaksi, $nopb[$k]);
                                        foreach ($ceknopb as $key => $value) {
                                            if ($kirim['kirim_pb'] == 0) {
                                                $data_biaya_pb_edit = [
                                                    'id_biaya' => $resultBiaya['id_biaya'],
                                                    'id_pum' => null,
                                                    'id_pjum' => null,
                                                    'id_pb' => $value['id_pb'],
                                                    'kategori' => $kategori[$r],
                                                    'id_valas' => $id_valas['id_valas'],
                                                    'kode_valas' => $kode_valas,
                                                    'simbol' => $simbol['simbol'],
                                                    'jenis_biaya' => 'PB',
                                                    'biaya' => $biaya,
                                                    'tanggal' => $tanggal[$r],
                                                ];
                                                $this->m_biaya->distinct($data_biaya_pb_edit['id_biaya']);
                                                $this->m_biaya->save($data_biaya_pb_edit);
                                            } else {
                                                $data_biaya_pb_edit = [
                                                    'id_biaya' => $resultBiaya['id_biaya'],
                                                    'id_pum' => null,
                                                    'id_pjum' => null,
                                                    'id_pb' => $value['id_pb'],
                                                    'kategori' => $kategori[$r],
                                                    'id_valas' => $id_valas['id_valas'],
                                                    'kode_valas' => $kode_valas,
                                                    'simbol' => $simbol['simbol'],
                                                    'jenis_biaya' => 'PB',
                                                    'biaya' => $biaya,
                                                    'tanggal' => $tanggal[$r],
                                                    'edited_at' => $timestamp,
                                                    'edited_by' => '05080',
                                                ];
                                                $this->m_biaya->distinct($data_biaya_pb_edit['id_biaya']);
                                                $this->m_biaya->save($data_biaya_pb_edit);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    session()->setFlashdata('success', 'Data Biaya berhasil di import');
                    break;
                } else {
                    // echo 'BIAYA KOSONG ';
                    $role = session()->get('akun_role');
                    $submit_pjum = $this->m_id->where('id_transaksi', $id_transaksi)->select('submit_pjum, kirim_pjum')->first();
                    $submit_pb = $this->m_id->where('id_transaksi', $id_transaksi)->select('submit_pb, kirim_pb')->first();
                    if($role == 'admin' || $role == 'user') {// && $submit_pjum['kirim_pjum'] == 0 && $submit_pb['kirim_pb'] == 0
                        $nik = session()->get('akun_nik');
                        $timestamp = date('Y-m-d H:i:s');
                        if (!empty($sheet[$r][2])) {
                            // echo 'KATEGORI ISI ';
                            $cekvaluta = $this->m_biaya->cekvaluta($id_transaksi, $r + 1, $k + 1, $jenis_biaya[$k]);
                            $cekvalutapjum = $this->m_biaya->cekvaluta($id_transaksi, $r + 1, $k + 1, 'PJUM');
                            $cekvalutapb = $this->m_biaya->cekvaluta($id_transaksi, $r + 1, $k + 1, 'PB');
                            $cekvaluta1 = $this->m_biaya->cekvaluta1($id_transaksi, $r + 1, $k + 1);

                            if(empty($cekvalutapjum['jenis_biaya'])) {
                                // echo 'PJUM KOSONG ';
                            } else {
                                if($id_transaksi == $cekvalutapjum['id_transaksi'] && $r + 1 == $cekvalutapjum['baris'] && $k + 1 == $cekvalutapjum['kolom'] && 'PJUM' == $cekvalutapjum['jenis_biaya']) {
                                    // echo 'PJUM ISI ';
                                    $resultBiaya = $this->m_biaya->where('id_transaksi', $id_transaksi)->where('baris', $r + 1)->where('kolom', $k + 1)->where('jenis_biaya', 'PJUM')->select('id_biaya as id_biaya')->first();
                                    $cekidkategori = $this->m_kategori->where('id_transaksi', $id_transaksi)->where('jenis_biaya', 'Support')->select('id_kategori as id_kategori')->findAll();
                                    $ceknopjum = $this->m_pjum->ceknomorpjum($id_transaksi, $nopjum[$k]);
                                    foreach ($ceknopjum as $key => $value) {
                                        $data_biaya_pjum_edit = [
                                            'id_biaya' => $resultBiaya['id_biaya'],
                                            'id_pum' => null,
                                            'id_pjum' => $value['id_pjum'],
                                            'id_pb' => null,
                                            'biaya' => null,
                                        ];
                                        $this->m_pjum->deletePostId($data_biaya_pjum_edit['id_pjum']);
                                        $this->m_kurs->deletekurspjum($data_biaya_pjum_edit['id_pjum']);

                                        $data = [
                                            'id_transaksi' => $id_transaksi,
                                            'submit_pjum' => 0,
                                            'kirim_pjum' => 0,
                                        ];
                                        $this->m_id->save($data);
                                    }

                                    foreach ($cekidkategori as $key => $value) {
                                        $data_id = [
                                            'id_kategori' => $value['id_kategori'],
                                        ];
                                        $this->m_kategori->deleteKategori($data_id['id_kategori']);
                                    }

                                    $this->m_pum->query('ALTER TABLE pum AUTO_INCREMENT 1');
                                    $this->m_pjum->query('ALTER TABLE pjum AUTO_INCREMENT 1');
                                    $this->m_kurs->query('ALTER TABLE kurs AUTO_INCREMENT 1');
                                    $this->m_kategori->query('ALTER TABLE kategori AUTO_INCREMENT 1');
                                    $this->m_biaya->query('ALTER TABLE biaya AUTO_INCREMENT 1');

                                    session()->setFlashdata('success', 'Data Biaya berhasil di hapus');
                                }
                            }

                            if(empty($cekvalutapb['jenis_biaya'])) {
                                // echo 'PB KOSONG ';
                            } else {
                                // echo 'PB ISI ';
                                if($id_transaksi == $cekvalutapb['id_transaksi'] && $r + 1 == $cekvalutapb['baris'] && $k + 1 == $cekvalutapb['kolom'] && 'PB' == $cekvalutapb['jenis_biaya']) {
                                    $resultBiaya = $this->m_biaya->where('id_transaksi', $id_transaksi)->where('baris', $r + 1)->where('kolom', $k + 1)->where('jenis_biaya', 'PB')->select('id_biaya as id_biaya')->first();
                                    $cekidkategori = $this->m_kategori->where('id_transaksi', $id_transaksi)->where('jenis_biaya', 'Support')->select('id_kategori as id_kategori')->findAll();
                                    $ceknopb = $this->m_pb->ceknomorpb($id_transaksi, $nopb[$k]);
                                    foreach ($ceknopb as $key => $value) {
                                        $val_pb = $this->m_pb->where('id_pb', $value['id_pb'])->select('created_by')->first();
                                        if($val_pb['created_by'] == '05080') {
                                            $bar = $r + 1;
                                            session()->setFlashdata('warning', ['Tidak dapat menghapus biaya pada baris ke '.$bar.', karena merupakan data yang diupload oleh Treasury']);
                                            return redirect()-> to('dashboard/'.$id_transaksi);
                                        } else {
                                            $data_biaya_pb_edit = [
                                                'id_biaya' => $resultBiaya['id_biaya'],
                                                'id_pum' => null,
                                                'id_pjum' => null,
                                                'id_pb' => $value['id_pb'],
                                                'biaya' => null,
                                            ];
                                            $this->m_pb->deletePostId($data_biaya_pb_edit['id_pb']);
                                            $this->m_kurs->deletekurspb($data_biaya_pb_edit['id_pb']);

                                            $data = [
                                                'id_transaksi' => $id_transaksi,
                                                'submit_pb' => 0,
                                                'kirim_pb' => 0,
                                            ];
                                            $this->m_id->save($data);
                                        }
                                    }

                                    foreach ($cekidkategori as $key => $value) {
                                        $val_pb = $this->m_pb->where('id_pb', $value['id_pb'])->select('created_by')->first();
                                        if($val_pb['created_by'] == '05080') {
                                            $bar = $r + 1;
                                            session()->setFlashdata('warning', ['Tidak dapat menghapus data PB pada baris ke '.$bar.', karena merupakan data yang diupload oleh Treasury']);
                                            return redirect()-> to('dashboard/'.$id_transaksi);
                                        } else {
                                            $data_id = [
                                                'id_kategori' => $value['id_kategori'],
                                            ];

                                            $this->m_kategori->deleteKategori($data_id['id_kategori']);
                                        }
                                    }

                                    $this->m_pb->query('ALTER TABLE pb AUTO_INCREMENT 1');
                                    $this->m_kurs->query('ALTER TABLE kurs AUTO_INCREMENT 1');
                                    $this->m_kategori->query('ALTER TABLE kategori AUTO_INCREMENT 1');
                                    $this->m_biaya->query('ALTER TABLE biaya AUTO_INCREMENT 1');

                                    session()->setFlashdata('success', 'Data Biaya berhasil di hapus');
                                }
                            }
                        }
                    } elseif($submit_pjum['kirim_pjum'] == 1 && $role == 'treasury') {
                        $nik = session()->get('akun_nik');
                        $timestamp = date('Y-m-d H:i:s');
                        if (!empty($sheet[$r][2])) {
                            // echo 'KATEGORI ISI ';
                            $cekvaluta = $this->m_biaya->cekvaluta($id_transaksi, $r + 1, $k + 1, $jenis_biaya[$k]);
                            $cekvalutapjum = $this->m_biaya->cekvaluta($id_transaksi, $r + 1, $k + 1, 'PJUM');
                            $cekvalutapb = $this->m_biaya->cekvaluta($id_transaksi, $r + 1, $k + 1, 'PB');
                            $cekvaluta1 = $this->m_biaya->cekvaluta1($id_transaksi, $r + 1, $k + 1);

                            if(empty($cekvalutapjum['jenis_biaya'])) {
                                // echo 'PJUM KOSONG ';
                            } else {
                                if($id_transaksi == $cekvalutapjum['id_transaksi'] && $r + 1 == $cekvalutapjum['baris'] && $k + 1 == $cekvalutapjum['kolom'] && 'PJUM' == $cekvalutapjum['jenis_biaya']) {
                                    $ceknopjum = $this->m_pjum->ceknomorpjum($id_transaksi, $nopjum[$k]);
                                    foreach ($ceknopjum as $key => $value) {
                                        $val_pjum = $this->m_pjum->where('id_pjum', $value['id_pjum'])->select('created_by')->first();
                                        if($val_pjum['created_by'] != '05080') {
                                            $bar = $r + 1;
                                            session()->setFlashdata('warning', ['Tidak dapat menghapus biaya pada baris ke '.$bar.', karena merupakan data yang diupload oleh User Bagian']);
                                            return redirect()-> to('dashboard/'.$id_transaksi);
                                        }
                                    }

                                    foreach ($cekidkategori as $key => $value) {
                                        $val_pjum = $this->m_pjum->where('id_pjum', $value['id_pjum'])->select('created_by')->first();
                                        if($val_pjum['created_by'] != '05080') {
                                            $bar = $r + 1;
                                            session()->setFlashdata('warning', ['Tidak dapat menghapus data PJUM pada baris ke '.$bar.', karena merupakan data yang diupload oleh User Bagian']);
                                            return redirect()-> to('dashboard/'.$id_transaksi);
                                        }
                                    }
                                }
                            }

                            if(empty($cekvalutapb['jenis_biaya'])) {
                                // echo 'PB KOSONG ';
                            } else {
                                // echo 'PB ISI ';
                                if($id_transaksi == $cekvalutapb['id_transaksi'] && $r + 1 == $cekvalutapb['baris'] && $k + 1 == $cekvalutapb['kolom'] && 'PB' == $cekvalutapb['jenis_biaya']) {
                                    $resultBiaya = $this->m_biaya->where('id_transaksi', $id_transaksi)->where('baris', $r + 1)->where('kolom', $k + 1)->where('jenis_biaya', 'PB')->select('id_biaya as id_biaya')->first();
                                    $cekidkategori = $this->m_kategori->where('id_transaksi', $id_transaksi)->where('jenis_biaya', 'Support')->select('id_kategori as id_kategori')->findAll();
                                    $ceknopb = $this->m_pb->ceknomorpb($id_transaksi, $nopb[$k]);
                                    foreach ($ceknopb as $key => $value) {
                                        $val_pb = $this->m_pb->where('id_pb', $value['id_pb'])->select('created_by')->first();
                                        if($val_pb['created_by'] != '05080') {
                                            $bar = $r + 1;
                                            session()->setFlashdata('warning', ['Tidak dapat menghapus biaya pada baris ke '.$bar.', karena merupakan data yang diupload oleh User Bagian']);
                                            return redirect()-> to('dashboard/'.$id_transaksi);
                                        } else {
                                            $data_biaya_pb_edit = [
                                                'id_biaya' => $resultBiaya['id_biaya'],
                                                'id_pum' => null,
                                                'id_pjum' => null,
                                                'id_pb' => $value['id_pb'],
                                                'biaya' => null,
                                            ];
                                            $this->m_pb->deletePostId($data_biaya_pb_edit['id_pb']);
                                            $this->m_kurs->deletekurspb($data_biaya_pb_edit['id_pb']);

                                            $data = [
                                                'id_transaksi' => $id_transaksi,
                                                'submit_pb' => 0,
                                                'kirim_pb' => 0,
                                            ];
                                            $this->m_id->save($data);
                                        }
                                    }

                                    foreach ($cekidkategori as $key => $value) {
                                        $val_pb = $this->m_pb->where('id_pb', $value['id_pb'])->select('created_by')->first();
                                        if($val_pb['created_by'] != '05080') {
                                            $bar = $r + 1;
                                            session()->setFlashdata('warning', ['Tidak dapat menghapus data PB pada baris ke '.$bar.', karena merupakan data yang diupload oleh Treasury']);
                                            return redirect()-> to('dashboard/'.$id_transaksi);
                                        } else {
                                            $data_id = [
                                                'id_kategori' => $value['id_kategori'],
                                            ];
                                            $this->m_kategori->deleteKategori($data_id['id_kategori']);
                                        }
                                    }

                                    $this->m_pb->query('ALTER TABLE pb AUTO_INCREMENT 1');
                                    $this->m_kurs->query('ALTER TABLE kurs AUTO_INCREMENT 1');
                                    $this->m_kategori->query('ALTER TABLE kategori AUTO_INCREMENT 1');
                                    $this->m_biaya->query('ALTER TABLE biaya AUTO_INCREMENT 1');

                                    session()->setFlashdata('success', 'Data Biaya berhasil di hapus');
                                }
                            }
                        }
                    }
                }
            }
        }
        return redirect()->to('dashboard/'.$id_transaksi);
    }

    public function exportbiaya($id_transaksi)
    {
        $role = session()->get('akun_role');

        $kat = $this->m_kategori->kategori($id_transaksi);
        if(empty($kat)) {
            session()->setFlashdata('warning', ['Tidak ada data, silahkan upload data biaya terlebih dahulu']);
            return redirect()-> to('dashboard/'.$id_transaksi);
        }

        $bia = $this->m_biaya->biaya($id_transaksi);
        if(empty($bia)) {
            session()->setFlashdata('warning', ['Tidak ada data, silahkan upload data biaya terlebih dahulu']);
            return redirect()-> to('dashboard/'.$id_transaksi);
        }

        $kategori = $this->m_kategori->kategori($id_transaksi);
        $biaya = $this->m_biaya->biaya($id_transaksi);
        $pum1 = $this->m_pum->pum($id_transaksi);
        $pjum = $this->m_pjum->pjum1($id_transaksi);
        $pb = $this->m_pb->pb1($id_transaksi);

        $bawah = $this->m_kategori->where('id_transaksi', $id_transaksi)->select('baris')->orderBy('id_kategori', 'desc')->first();
        $bawahpum = $this->m_pum->where('id_transaksi', $id_transaksi)->select('kolom')->orderBy('kolom', 'desc')->first();
        $bawahpjum = $this->m_pjum->where('id_transaksi', $id_transaksi)->select('kolom')->orderBy('kolom', 'desc')->first();
        $bawahpb = $this->m_pb->where('id_transaksi', $id_transaksi)->select('kolom')->orderBy('kolom', 'desc')->first();
        $baris = $this->m_kategori->where('id_transaksi', $id_transaksi)->select('baris')->orderBy('id_kategori', 'asc')->findAll();
        $nik = $this->m_id->where('id_transaksi', $id_transaksi)->select('nik')->first();

        $bawah1 = $this->m_pjum->where('id_transaksi', $id_transaksi)->select('kolom')->orderBy('kolom', 'asc')->first();
        $bawah2 = $this->m_pb->where('id_transaksi', $id_transaksi)->select('kolom')->orderBy('kolom', 'asc')->first();
        $atas1 = $this->m_pjum->where('id_transaksi', $id_transaksi)->select('kolom')->orderBy('kolom', 'desc')->first();
        $atas2 = $this->m_pb->where('id_transaksi', $id_transaksi)->select('kolom')->orderBy('kolom', 'desc')->first();

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(12);
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle("Data Biaya PDLN");

        $personil = $this->m_personil->personil($id_transaksi);
        $negara = $this->m_negara_tujuan->negaratujuan($id_transaksi);

        $niknm_perso = '';
        $nik_perso = '';
        foreach ($personil as $pr => $perso) {
            $niknm_perso .= $perso['niknm'].', ';
            $nik_perso .= $perso['nik'].'_';
        }

        $tmp_negara = '';
        foreach ($negara as $ng => $neg) {
            $tmp_negara .= $neg['negara_tujuan'].', ';
        }

        $pum = $this->m_pum->where('id_transaksi', $id_transaksi)->groupBy(['pum', 'id_transaksi', 'id_valas'])->orderBy('id_pum', 'asc')->select('pum')->findAll();
        $uang_kembali = $this->m_pum->where('id_transaksi', $id_transaksi)->groupBy(['uang_kembali', 'id_transaksi', 'id_valas'])->orderBy('id_pum', 'asc')->select('uang_kembali')->findAll();
        $nopjum = $this->m_pjum->where('id_transaksi', $id_transaksi)->groupBy(['id_pjum'])->orderBy('kolom', 'asc')->select('nomor')->findAll();
        $nopb = $this->m_pb->where('id_transaksi', $id_transaksi)->groupBy(['id_pb'])->orderBy('kolom', 'asc')->select('nomor')->findAll();

        $arr1 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $pum));

        $exp1 = explode(' ', $arr1);

        $arr2 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $uang_kembali));

        $exp2 = explode(' ', $arr2);

        $arr3 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $nopjum));

        $exp3 = explode(' ', $arr3);

        $arr4 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $nopb));

        $exp4 = explode(' ', $arr4);

        $sheet->setCellValue('B1', 'PERJALANAN DINAS LUAR NEGERI '.substr($nik_perso, 0, -1).'_'.$id_transaksi);
        $sheet->setCellValue('I2', 'No PJUM =>');
        $sheet->setCellValue('I3', 'No PB =>');
        $sheet->setCellValue('I4', 'PUM =>');
        $sheet->setCellValue('I5', 'SISA UANG DIKEMBALIKAN =>');
        $sheet->setCellValue('B2', 'Negara Tujuan =>');
        $sheet->setCellValue('C2', substr($tmp_negara, 0, -2));
        $sheet->setCellValue('C5', 'LIST KATEGORI ADA DI SHEET MASTER');
        $sheet->setCellValue('B5', 'FORMAT TANGGAL : YYYY-MM-DD');
        $sheet->setCellValue('B6', 'Tanggal');
        $sheet->setCellValue('C6', 'Kategori');
        $sheet->setCellValue('D6', 'Status');
        $sheet->setCellValue('E6', 'Ref');
        $sheet->setCellValue('F6', 'Note');
        $sheet->setCellValue('G6', 'Negara Tujuan');
        $sheet->setCellValue('H6', 'Negara Transit');
        $sheet->setCellValue('I6', 'Jumlah Personil');
        $sheet->setCellValue('J6', 'Valas');

        $array = array('G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ');
        $array1 = array('?','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK');
        $valas = $this->m_biaya->valuta($id_transaksi);
        $count = count((array)$valas);
        $alpha = $array[$count];

        if (empty($bawah1['kolom'])) {
            $bawah1['kolom'] = 0;
        }
        if (empty($bawah2['kolom'])) {
            $bawah2['kolom'] = 0;
        }
        if (empty($atas1['kolom'])) {
            $atas1['kolom'] = 0;
        }
        if (empty($atas2['kolom'])) {
            $atas2['kolom'] = 0;
        }

        if($role == 'admin' || $role == 'user') {
            $alpha1 = $array1[$bawah1['kolom']];
            $alpha2 = $array1[$bawah2['kolom']];
            $alpha3 = $array1[$atas1['kolom']];
            $alpha4 = $array1[$atas2['kolom']];

            if($count > $bawah1['kolom'] && $count > $bawah2['kolom']) {
                $alpha = $array[$count];
            } elseif($bawah1['kolom'] > $count && $bawah1['kolom'] > $bawah2['kolom']) {
                $alpha = $array1[$atas1['kolom']];
            } elseif($bawah2['kolom'] > $count && $bawah2['kolom'] > $bawah1['kolom']) {
                $alpha = $array1[$atas2['kolom']];
            }

            $sheet->mergeCells('B1:'.$alpha.'1');
            $sheet->mergeCells('B6:B7');
            $sheet->mergeCells('C6:C7');
            $sheet->mergeCells('D6:D7');
            $sheet->mergeCells('E6:E7');
            $sheet->mergeCells('F6:F7');
            $sheet->mergeCells('G6:G7');
            $sheet->mergeCells('H6:H7');
            $sheet->mergeCells('I6:I7');
            $sheet->mergeCells('J6:'.$alpha.'6');

            foreach ($biaya as $key => $value) {
                $i = 7;
                $row = $value['baris'];
                $column = $value['kolom'];
                $sheet->setCellValueByColumnAndRow($column, $i, $value['kode_valas']);
                for ($j = 8; $j <= $bawah['baris']; $j++) {
                    $bia = $value['biaya'];
                    $sheet->setCellValueByColumnAndRow($column, $row, $bia);
                }
            }

            $i = 8;
            foreach ($kategori as $key => $value) {
                $sheet->setCellValue('B'.$i, $value['tanggal']);
                $sheet->setCellValue('C'.$i, $value['kategori']);
                $sheet->setCellValue('D'.$i, $value['status']);
                $sheet->setCellValue('E'.$i, $value['ref']);
                $sheet->setCellValue('F'.$i, $value['note']);
                $sheet->setCellValue('G'.$i, $value['negara_tujuan']);
                $sheet->setCellValue('H'.$i, $value['negara_trading']);
                $sheet->setCellValue('I'.$i, $value['jumlah_personil']);
                $sheet->getStyle('B6:'.$alpha.$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('I2:'.$alpha.$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $i++;
            }

            foreach ($pum1 as $key => $value) {
                $row = 4;
                $row1 = 5;
                $column = $value['kolom'];
                for ($j = $column; $j <= $bawahpum['kolom']; $j++) {
                    $pum = $value['pum'];
                    $uangkembali = $value['uang_kembali'];
                    $sheet->setCellValueByColumnAndRow($column, $row, $pum);
                    $sheet->setCellValueByColumnAndRow($column, $row1, $uangkembali);
                }
            }

            foreach ($pjum as $key => $value) {
                $row = 2;
                $column = $value['kolom'];
                for ($j = $column; $j <= $bawahpjum['kolom']; $j++) {
                    $nomor = $value['nomor'];
                    $sheet->setCellValueByColumnAndRow($column, $row, $nomor);
                }
            }

            foreach ($pb as $key => $value) {
                $row = 3;
                $column = $value['kolom'];
                for ($j = $column; $j <= $bawahpb['kolom']; $j++) {
                    $nomor = $value['nomor'];
                    $sheet->setCellValueByColumnAndRow($column, $row, $nomor);
                }
            }

            for ($k = 'B'; $k <= $alpha; $k++) {
                $spreadsheet->getActiveSheet()->getColumnDimension($k)->setWidth(20);
            }

            for ($k = 'J'; $k <= $alpha; $k++) {
                $spreadsheet->getActiveSheet()->getColumnDimension($k)->setAutoSize(true);
            }

            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('A')->setVisible(false);

            $sheet->getStyle('B:I')->getAlignment()->setHorizontal('center');
            $sheet->getStyle('B:I')->getAlignment()->setVertical('center');
            $sheet->getStyle('J:'.$alpha)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('B')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
            $sheet->getStyle('J:'.$alpha)->getNumberFormat()->setFormatCode('#,##0.00');

            $spreadsheet->createSheet();
            $sheet1 = $spreadsheet->setActiveSheetIndex(1);

            // Rename worksheet
            $spreadsheet->getActiveSheet(1)->setTitle('Master');

            $sheet1->setCellValue('A1', 'Kategori');
            $sheet1->setCellValue('A2', 'Tiket Pesawat');
            $sheet1->setCellValue('A3', 'Bagasi Pesawat');
            $sheet1->setCellValue('A4', 'Porter Pesawat');
            $sheet1->setCellValue('A5', 'Hotel');
            $sheet1->setCellValue('A6', 'Makan dan Minum');
            $sheet1->setCellValue('A7', 'Transportasi');
            $sheet1->setCellValue('A8', 'Laundry');
            $sheet1->setCellValue('A9', 'Lain-lain');
            $sheet1->setCellValue('A10', 'Tukar Uang Keluar');
            $sheet1->setCellValue('A11', 'Tukar Uang Masuk');
            $sheet1->setCellValue('A12', 'Kembalian');

            $sheet1->setCellValue('B1', 'Note');
            $sheet1->setCellValue('B2', 'Tiket Pesawat');
            $sheet1->setCellValue('B3', 'Bagasi Pesawat');
            $sheet1->setCellValue('B4', 'Porter Pesawat');
            $sheet1->setCellValue('B5', 'Tiket Hotel');
            $sheet1->setCellValue('B6', 'Tip Hotel');
            $sheet1->setCellValue('B7', 'Makan dan Minum');
            $sheet1->setCellValue('B8', 'Tip Makan dan Minum');
            $sheet1->setCellValue('B9', 'Taxi');
            $sheet1->setCellValue('B10', 'Laundry');
            $sheet1->setCellValue('B11', 'Beli SIM Card');
            $sheet1->setCellValue('B12', 'Beli Sample');
            $sheet1->setCellValue('B13', 'Tukar Uang');
            $sheet1->setCellValue('B14', 'Kembalian');

            $sheet1->setCellValue('C1', 'Valas');
            $sheet1->setCellValue('C2', 'IDR');
            $sheet1->setCellValue('C3', 'USD');
            $sheet1->setCellValue('C4', 'SGD');
            $sheet1->setCellValue('C5', 'KHR');
            $sheet1->setCellValue('C6', 'VND');
            $sheet1->setCellValue('C7', 'MYR');
            $sheet1->setCellValue('C8', 'THB');
            $sheet1->setCellValue('C9', 'LAK');
            $sheet1->setCellValue('C10', 'MMK');
            $sheet1->setCellValue('C11', 'BND');
            $sheet1->setCellValue('C12', 'PHP');
            $sheet1->setCellValue('C13', 'EUR');
            $sheet1->setCellValue('C14', 'GBP');

            $sheet1->setCellValue('D1', 'Status');
            $sheet1->setCellValue('D2', 'Dibelikan GS');
            $sheet1->setCellValue('D3', 'Beli Sendiri');

            $sheet1->getStyle('A1:D14')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            for ($k = 'A'; $k <= 'D'; $k++) {
                $spreadsheet->getActiveSheet(1)->getColumnDimension($k)->setAutoSize(true);
            }

            $sheet1->getStyle('A:D')->getAlignment()->setHorizontal('center');
            $sheet1->getStyle('A:D')->getAlignment()->setVertical('center');
            $sheet1->getStyle('A1:D1')->getFont()->setBold(true);

            $spreadsheet->setActiveSheetIndex(0);

            $nik = substr($nik_perso, 0, -1);
            $niknm = substr($niknm_perso, 0, -2);

            $writer = new Xls($spreadsheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=Biaya Perjalanan Dinas Luar Negeri/'.$nik.'/'.$id_transaksi.'.xls');
            $writer->save("php://output");
        // $writer->save('Biaya '.substr($nik_perso, 0, -1).'_'.$id_transaksi.'.xls');
        // return redirect()->to('dashboard/'.$id_transaksi);
        } elseif($role == 'treasury') {
            $alpha1 = $array1[$bawah1['kolom']];
            $alpha2 = $array1[$bawah2['kolom']];
            $alpha3 = $array1[$atas1['kolom']];
            $alpha4 = $array1[$atas2['kolom']];

            if($count > $bawah1['kolom'] && $count > $bawah2['kolom']) {
                $alpha = $array[$count];
            } elseif($bawah1['kolom'] > $count && $bawah1['kolom'] > $bawah2['kolom']) {
                $alpha = $array1[$atas1['kolom']];
            } elseif($bawah2['kolom'] > $count && $bawah2['kolom'] > $bawah1['kolom']) {
                $alpha = $array1[$atas2['kolom']];
            }

            $sheet->mergeCells('B1:'.$alpha.'1');
            $sheet->mergeCells('B6:B7');
            $sheet->mergeCells('C6:C7');
            $sheet->mergeCells('D6:D7');
            $sheet->mergeCells('E6:E7');
            $sheet->mergeCells('F6:F7');
            $sheet->mergeCells('G6:G7');
            $sheet->mergeCells('H6:H7');
            $sheet->mergeCells('I6:I7');
            $sheet->mergeCells('J6:'.$alpha.'6');

            foreach ($biaya as $key => $value) {
                $i = 7;
                $row = $value['baris'];
                $column = $value['kolom'];
                $sheet->setCellValueByColumnAndRow($column, $i, $value['kode_valas']);
                for ($j = 8; $j <= $bawah['baris']; $j++) {
                    $bia = $value['biaya'];
                    $sheet->setCellValueByColumnAndRow($column, $row, $bia);
                }
            }

            $i = 8;
            foreach ($kategori as $key => $value) {
                $sheet->setCellValue('B'.$i, $value['tanggal']);
                $sheet->setCellValue('C'.$i, $value['kategori']);
                $sheet->setCellValue('D'.$i, $value['status']);
                $sheet->setCellValue('E'.$i, $value['ref']);
                $sheet->setCellValue('F'.$i, $value['note']);
                $sheet->setCellValue('G'.$i, $value['negara_tujuan']);
                $sheet->setCellValue('H'.$i, $value['negara_trading']);
                $sheet->setCellValue('I'.$i, $value['jumlah_personil']);
                $sheet->getStyle('B6:'.$alpha.$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('I2:'.$alpha.$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $i++;
            }

            foreach ($pum1 as $key => $value) {
                $row = 4;
                $row1 = 5;
                $column = $value['kolom'];
                for ($j = $column; $j <= $bawahpum['kolom']; $j++) {
                    $pum = $value['pum'];
                    $uangkembali = $value['uang_kembali'];
                    $sheet->setCellValueByColumnAndRow($column, $row, $pum);
                    $sheet->setCellValueByColumnAndRow($column, $row1, $uangkembali);
                }
            }

            foreach ($pjum as $key => $value) {
                $row = 2;
                $column = $value['kolom'];
                for ($j = $column; $j <= $bawahpjum['kolom']; $j++) {
                    $nomor = $value['nomor'];
                    $sheet->setCellValueByColumnAndRow($column, $row, $nomor);
                }
            }

            foreach ($pb as $key => $value) {
                $row = 3;
                $column = $value['kolom'];
                for ($j = $column; $j <= $bawahpb['kolom']; $j++) {
                    $nomor = $value['nomor'];
                    $sheet->setCellValueByColumnAndRow($column, $row, $nomor);
                }
            }

            for ($k = 'B'; $k <= $alpha; $k++) {
                $spreadsheet->getActiveSheet()->getColumnDimension($k)->setWidth(20);
            }

            for ($k = 'J'; $k <= $alpha; $k++) {
                $spreadsheet->getActiveSheet()->getColumnDimension($k)->setAutoSize(true);
            }

            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('A')->setVisible(false);

            $sheet->getStyle('B:I')->getAlignment()->setHorizontal('center');
            $sheet->getStyle('B:I')->getAlignment()->setVertical('center');
            $sheet->getStyle('J:'.$alpha)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('B')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
            $sheet->getStyle('J:'.$alpha)->getNumberFormat()->setFormatCode('#,##0.00');

            $spreadsheet->createSheet();
            $sheet1 = $spreadsheet->setActiveSheetIndex(1);

            // Rename worksheet
            $spreadsheet->getActiveSheet(1)->setTitle('Master');

            $sheet1->setCellValue('A1', 'Kategori');
            $sheet1->setCellValue('A2', 'Tiket Pesawat');
            $sheet1->setCellValue('A3', 'Bagasi Pesawat');
            $sheet1->setCellValue('A4', 'Porter Pesawat');
            $sheet1->setCellValue('A5', 'Hotel');
            $sheet1->setCellValue('A6', 'Makan dan Minum');
            $sheet1->setCellValue('A7', 'Transportasi');
            $sheet1->setCellValue('A8', 'Laundry');
            $sheet1->setCellValue('A9', 'Lain-lain');
            $sheet1->setCellValue('A10', 'Tukar Uang Keluar');
            $sheet1->setCellValue('A11', 'Tukar Uang Masuk');
            $sheet1->setCellValue('A12', 'Kembalian');

            $sheet1->setCellValue('B1', 'Note');
            $sheet1->setCellValue('B2', 'Tiket Pesawat');
            $sheet1->setCellValue('B3', 'Bagasi Pesawat');
            $sheet1->setCellValue('B4', 'Porter Pesawat');
            $sheet1->setCellValue('B5', 'Tiket Hotel');
            $sheet1->setCellValue('B6', 'Tip Hotel');
            $sheet1->setCellValue('B7', 'Makan dan Minum');
            $sheet1->setCellValue('B8', 'Tip Makan dan Minum');
            $sheet1->setCellValue('B9', 'Taxi');
            $sheet1->setCellValue('B10', 'Laundry');
            $sheet1->setCellValue('B11', 'Beli SIM Card');
            $sheet1->setCellValue('B12', 'Beli Sample');
            $sheet1->setCellValue('B13', 'Tukar Uang');
            $sheet1->setCellValue('B14', 'Kembalian');

            $sheet1->setCellValue('C1', 'Valas');
            $sheet1->setCellValue('C2', 'IDR');
            $sheet1->setCellValue('C3', 'USD');
            $sheet1->setCellValue('C4', 'SGD');
            $sheet1->setCellValue('C5', 'KHR');
            $sheet1->setCellValue('C6', 'VND');
            $sheet1->setCellValue('C7', 'MYR');
            $sheet1->setCellValue('C8', 'THB');
            $sheet1->setCellValue('C9', 'LAK');
            $sheet1->setCellValue('C10', 'MMK');
            $sheet1->setCellValue('C11', 'BND');
            $sheet1->setCellValue('C12', 'PHP');
            $sheet1->setCellValue('C13', 'EUR');
            $sheet1->setCellValue('C14', 'GBP');

            $sheet1->setCellValue('D1', 'Status');
            $sheet1->setCellValue('D2', 'Dibelikan GS');
            $sheet1->setCellValue('D3', 'Beli Sendiri');

            $sheet1->getStyle('A1:D14')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            for ($k = 'A'; $k <= 'D'; $k++) {
                $spreadsheet->getActiveSheet(1)->getColumnDimension($k)->setAutoSize(true);
            }

            $sheet1->getStyle('A:D')->getAlignment()->setHorizontal('center');
            $sheet1->getStyle('A:D')->getAlignment()->setVertical('center');
            $sheet1->getStyle('A1:D1')->getFont()->setBold(true);

            $spreadsheet->setActiveSheetIndex(0);

            $nik = substr($nik_perso, 0, -1);
            $niknm = substr($niknm_perso, 0, -2);

            $writer = new Xls($spreadsheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=Biaya Perjalanan Dinas Luar Negeri/'.$nik.'/'.$id_transaksi.'.xls');
            $writer->save("php://output");
            // $writer->save('Biaya '.substr($nik_perso, 0, -1).'_'.$id_transaksi.'.xls');
            // return redirect()->to('dashboard/'.$id_transaksi);
        }
    }

    public function exporterp($id_transaksi)
    {
        $role = session()->get('akun_role');

        $kat = $this->m_kategori->kategori($id_transaksi);
        if(empty($kat)) {
            session()->setFlashdata('warning', ['Tidak ada data, silahkan upload data biaya terlebih dahulu']);
            return redirect()-> to('dashboard/'.$id_transaksi);
        }

        $bia = $this->m_biaya->biaya($id_transaksi);
        if(empty($bia)) {
            session()->setFlashdata('warning', ['Tidak ada data, silahkan upload data biaya terlebih dahulu']);
            return redirect()-> to('dashboard/'.$id_transaksi);
        }

        // $id_pjum = $this->m_kategori->where('id_transaksi', $id_transaksi)->select('id_pjum')->findAll();

        // foreach ($id_pjum as $key => $value) {
        //     if ($value['id_pjum'] != null) {
        //         $nopjum = $this->m_pjum->where('id_pjum', $value['id_pjum'])->select('id_pjum, tanggal')->findAll();
        //         foreach ($nopjum as $np => $nopj) {
        //             if($nopj['tanggal'] == null) {
        //                 session()->setFlashdata('warning', ['Tambahkan tanggal pembuatan no PJUM terlebih dahulu untuk melakukan submit data']);
        //                 return redirect()-> to('dashboard/'.$id_transaksi);
        //             }
        //         }
        //     }
        // }

        $kategori = $this->m_kategori->kategori($id_transaksi);
        $biaya = $this->m_biaya->biaya($id_transaksi);
        $pjum = $this->m_pjum->pjum1($id_transaksi);
        $pb = $this->m_pb->pb1($id_transaksi);

        $bawah = $this->m_kategori->where('id_transaksi', $id_transaksi)->select('baris')->orderBy('id_kategori', 'desc')->first();
        $bawahpjum = $this->m_pjum->where('id_transaksi', $id_transaksi)->select('kolom')->orderBy('kolom', 'desc')->first();
        $bawahpb = $this->m_pb->where('id_transaksi', $id_transaksi)->select('kolom')->orderBy('kolom', 'desc')->first();
        $baris = $this->m_kategori->where('id_transaksi', $id_transaksi)->select('baris')->orderBy('id_kategori', 'asc')->findAll();
        $nik = $this->m_id->where('id_transaksi', $id_transaksi)->select('nik, tanggal_berangkat, tanggal_pulang')->first();

        $bawah1 = $this->m_pjum->where('id_transaksi', $id_transaksi)->select('kolom')->orderBy('kolom', 'asc')->first();
        $bawah2 = $this->m_pb->where('id_transaksi', $id_transaksi)->select('kolom')->orderBy('kolom', 'asc')->first();
        $atas1 = $this->m_pjum->where('id_transaksi', $id_transaksi)->select('kolom')->orderBy('kolom', 'desc')->first();
        $atas2 = $this->m_pb->where('id_transaksi', $id_transaksi)->select('kolom')->orderBy('kolom', 'desc')->first();

        $spreadsheet = new Spreadsheet();
        Calculation::getInstance($spreadsheet)->disableCalculationCache();
        Calculation::getInstance()->setCalculationCacheEnabled(false);
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(12);
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle("Rekap Perjalanan Dinas LN");

        $personil = $this->m_personil->personil($id_transaksi);
        $negara = $this->m_negara_tujuan->negaratujuan($id_transaksi);

        $niknm_perso = '';
        $nik_perso = '';
        foreach ($personil as $pr => $perso) {
            $niknm_perso .= $perso['niknm'].', ';
            $nik_perso .= $perso['nik'].'_';
        }

        $tmp_negara = '';
        foreach ($negara as $ng => $neg) {
            $tmp_negara .= $neg['negara_tujuan'].', ';
        }

        $totalbiayatot = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support', 'PB'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->groupBy(['id_valas', 'id_transaksi'])->orderBy('id_biaya', 'asc')->select('sum(biaya) as sum, id_valas, kode_valas')->findAll();
        $valastot = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support', 'PB'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->groupBy(['kode_valas', 'id_transaksi'])->orderBy('id_biaya', 'asc')->select('kode_valas')->findAll();

        $totalbiayakurs = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support', 'PB'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->groupBy(['id_valas', 'id_transaksi'])->orderBy('id_biaya', 'asc')->select('sum(biaya) as sum, id_valas, kode_valas')->findAll();
        $valaskurs = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support', 'PB'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->groupBy(['kode_valas', 'id_transaksi'])->orderBy('id_biaya', 'asc')->select('kode_valas')->findAll();

        foreach($valastot as $iv => $val) {
            $valas_ada = 0; // 0= tidak ada, 1= ada
            $temp_isi = 0;
            foreach($totalbiayatot as $ib => $bia) {
                if($val['kode_valas'] == $bia['kode_valas']) {
                    $count = count((array)$valastot);
                    $valas_ada = 1;
                    $temp_isi = $bia['sum'];
                    break;
                }
            }

            $temptotalbiayatot[$iv] = array(
                'sum' => $temp_isi,
            );
        }

        $totalbiayatot = $temptotalbiayatot;

        foreach($valaskurs as $iv => $val) {
            $valas_ada = 0; // 0= tidak ada, 1= ada
            $temp_isi = 0;
            foreach($totalbiayakurs as $ib => $bia) {
                foreach ($pjum as $key => $value) {
                    $kurs = $this->m_kurs->where('id_pjum', $value['id_pjum'])->select('kurs, id_kurs, id_valas, kode_valas')->findAll();
                    foreach ($kurs as $kr => $kur) {
                        if($val['kode_valas'] == $bia['kode_valas'] && $val['kode_valas'] == $kur['kode_valas']) {
                            $count = count((array)$valastot);
                            $valas_ada = 1;
                            $temp_isi = $bia['sum'] * $kur['kurs'];
                            break;
                        }
                    }
                }

                $kurstotalbiaya[$iv] = array(
                    'sum' => (string)$temp_isi,
                );
            }
        }

        $totalbiayakurs = $kurstotalbiaya;

        foreach($valaskurs as $iv => $val) {
            $valas_ada = 0; // 0= tidak ada, 1= ada
            $temp_isi = 1;
            foreach ($pjum as $key => $value) {
                $kurs = $this->m_kurs->where('id_pjum', $value['id_pjum'])->select('kurs, id_kurs, id_valas, kode_valas')->findAll();
                foreach ($kurs as $kr => $kur) {
                    if($val['kode_valas'] == $kur['kode_valas']) {
                        $valas_ada = 1;
                        $temp_isi = $kur['kurs'];
                        break;
                    }
                }
            }

            $kursbiaya[$iv] = array(
                'sum' => (string)$temp_isi,
            );
        }

        $biayakurs = $kursbiaya;

        // foreach($valaskurs as $iv => $val){
        //     $valas_ada = 0; // 0= tidak ada, 1= ada
        //     $temp_isi = 0;
        //     foreach($totalbiayakurs as $ib => $bia){
        //         foreach ($pjum as $key => $value) {
        //             $kurs = $this->m_kurs->where('id_pjum', $value['id_pjum'])->select('kurs, id_kurs, id_valas, kode_valas')->findAll();
        //             foreach ($kurs as $kr => $kur) {
        //                 if($val['kode_valas'] == $bia['kode_valas'] && $val['kode_valas'] == $kur['kode_valas']){
        //                     $count = count((array)$valastot);
        //                     $valas_ada = 1;
        //                     $temp_isi = $bia['sum'] * $kur['kurs'];
        //                     break;
        //                 }
        //             }
        //         }

        //         $kurstotalbiaya[$iv] = array(
        //             'sum' => (string)$temp_isi,
        //         );
        //     }
        // }

        // $totalbiayakurs = $kurstotalbiaya;

        $pum = $this->m_pum->where('id_transaksi', $id_transaksi)->groupBy(['pum', 'id_transaksi', 'id_valas'])->orderBy('id_pum', 'asc')->select('pum')->findAll();
        $uang_kembali = $this->m_pum->where('id_transaksi', $id_transaksi)->groupBy(['uang_kembali', 'id_transaksi', 'id_valas'])->orderBy('id_pum', 'asc')->select('uang_kembali')->findAll();
        $nopjum = $this->m_pjum->where('id_transaksi', $id_transaksi)->groupBy(['id_pjum'])->orderBy('kolom', 'asc')->select('nomor')->findAll();
        $nopb = $this->m_pb->where('id_transaksi', $id_transaksi)->groupBy(['id_pb'])->orderBy('kolom', 'asc')->select('nomor')->findAll();

        $arr1 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $pum));

        $exp1 = explode(' ', $arr1);

        $arr2 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $uang_kembali));

        $exp2 = explode(' ', $arr2);

        $arr3 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $nopjum));

        $exp3 = explode(' ', $arr3);

        $arr4 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $nopb));

        $exp4 = explode(' ', $arr4);

        $arr5 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $valastot));

        $exp5 = explode(' ', $arr5);

        $arr6 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $totalbiayatot));

        $exp6 = explode(' ', $arr6);

        $arr7 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $biayakurs));

        $exp7 = explode(' ', $arr7);

        $sheet->setCellValue('B1', 'PERJALANAN DINAS LUAR NEGERI '.substr($nik_perso, 0, -1).'_'.$id_transaksi);
        $sheet->setCellValue('B3', 'Personil =>');
        $sheet->setCellValue('B4', 'Tanggal Perjalanan =>');
        $sheet->setCellValue('C3', substr($niknm_perso, 0, -2));
        $sheet->setCellValue('C4', tanggal_indo1($nik['tanggal_berangkat']).' s/d '.tanggal_indo1($nik['tanggal_pulang']));
        $sheet->setCellValue('B8', 'Total Pengeluaran');
        $sheet->setCellValue('B9', 'Nilai PUM');
        $sheet->setCellValue('B10', 'Sisa Uang Seharusnya');
        $sheet->setCellValue('B11', 'Sisa Uang Dikembalikan');
        $sheet->setCellValue('B12', 'Kekurangan/Kelebihan');
        // $sheet->setCellValue('B13', 'Kurs Saat PJUM');
        // $sheet->setCellValue('B14', 'Kekurangan/Kelebihan Dalam IDR');
        $sheet->setCellValue('C6', 'Valas');

        $array = array('B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ');
        $array1 = array('?','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK');
        $valas = $this->m_biaya->valuta($id_transaksi);
        $alpha = $array[$count];

        $sheet->mergeCells('B1:'.$alpha.'1');
        $sheet->mergeCells('B6:B7');
        $sheet->mergeCells('C6:'.$alpha.'6');

        $sheet->fromArray($exp5, null, 'C7');
        $sheet->fromArray($exp6, null, 'C8');
        $sheet->fromArray($exp1, null, 'C9');

        for ($k = 'C'; $k <= $alpha; $k++) {
            $sheet->setCellValue($k.'10', '='.$k.'9-'.$k.'8');
        }

        $sheet->fromArray($exp2, null, 'C11');

        for ($k = 'C'; $k <= $alpha; $k++) {
            $sheet->setCellValue($k.'12', '='.$k.'11-'.$k.'10');
        }

        // $sheet->fromArray($exp7, NULL, 'C13');

        // for ($k = 'C'; $k <= $alpha; $k++) {
        //     $sheet->setCellValue($k.'14', '='.$k.'12*'.$k.'13');
        // }

        for ($k = 'C'; $k <= $alpha; $k++) {
            $spreadsheet->getActiveSheet()->getColumnDimension($k)->setWidth(20);
        }

        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('A')->setVisible(false);

        // $sheet->getTabColor()->setRGB('FFFF00');
        // $sheet->getStyle('B14:'.$alpha.'14')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
        $sheet->getStyle('B6:'.$alpha.'12')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('B6:'.$alpha.'14')->getFont()->setBold(true);
        $sheet->getStyle('B8:'.$alpha.'8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
        $sheet->getStyle('B10:'.$alpha.'10')->getFont()->getColor()->setARGB('993300');
        $sheet->getStyle('B11:'.$alpha.'11')->getFont()->getColor()->setARGB('0066CC');
        // $sheet->getStyle('B13:'.$alpha.'13')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('99CC00');
        // $sheet->getStyle('B14:'.$alpha.'14')->getFont()->getColor()->setARGB('FF0000');
        $sheet->getStyle('C3')->getAlignment()->setWrapText(true);
        $sheet->getStyle('C4')->getAlignment()->setWrapText(true);
        $sheet->getStyle('B:'.$alpha)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('C8:'.$alpha.'14')->getAlignment()->setHorizontal('right');
        $sheet->getStyle('C:'.$alpha)->getNumberFormat()->setFormatCode('#,##0.00');

        $nik = substr($nik_perso, 0, -1);
        $niknm = substr($niknm_perso, 0, -2);

        $writer = new Xls($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=PJUM Perjalanan Dinas Luar Negeri/'.$nik.'/'.$id_transaksi.'.xls');
        $writer->save("php://output");
        // $writer->save('PJUM Perjalanan Dinas Luar Negeri '.$nik.'_'.$id_transaksi.'.xls');
        // return redirect()->to('dashboard/'.$id_transaksi);
    }

    public function exportlaporan($id_transaksi)
    {
        $arraypjum = array('C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ');
        $arraypb = array('F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK');
        $valaspjum = $this->m_biaya->valas($id_transaksi, 'pjum');
        $valaspb = $this->m_biaya->valas($id_transaksi, 'pb');
        $valassup = $this->m_biaya->valas($id_transaksi, 'support');
        $valasall = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->groupBy(['kode_valas', 'id_transaksi'])->orderBy('id_biaya', 'asc')->select('kode_valas')->findAll();
        $valassel = $this->m_biaya->where('id_transaksi', $id_transaksi)->groupBy(['kode_valas', 'id_transaksi'])->orderBy('id_biaya', 'asc')->select('kode_valas')->findAll();
        $valassupport = $this->m_biaya->support($id_transaksi, 'support');
        $countpjum = count((array)$valaspjum);
        $countpb = count((array)$valaspb);
        $countall = count((array)$valasall);
        $countsel = count((array)$valassel);
        $countsupport = count((array)$valassupport);
        $countsup = count((array)$valassup);
        if ($countpjum == null) {
            $countpjum = $countall;
        }
        if ($countpb == null) {
            $countpb = $countall;
        }
        $akhirpjum = $countpjum + 3;
        $akhirpb = $akhirpjum + 4;
        $alphapjum = $arraypjum[$countpjum];
        $alphaall = $arraypjum[$countall];
        $alphasel = $arraypjum[$countsel];
        $alphasup = $arraypjum[$countsup + 1];
        $alphapjum2 = $arraypjum[$countpjum + 2];
        $alphapb = $arraypb[$countpb + 2];
        $alphatotal = $arraypjum[$countpjum + 2 + $countpb + 1];

        $kategoripesawatpjum = $this->m_kategori->kategoripesawatpjum($id_transaksi);
        $kategoripesawatpb = $this->m_kategori->kategoripesawatpb($id_transaksi);

        $kategoripjum = $this->m_kategori->getDataIdtransaksi($id_transaksi, 'pjum');
        $kategoripb = $this->m_kategori->getDataIdtransaksi($id_transaksi, 'pb');
        $kategorisupport = $this->m_kategori->getDataIdtransaksi($id_transaksi, 'support');

        $biayapesawatpjum = $this->m_biaya->biayapesawatpjum($id_transaksi);
        $biayapesawatpb = $this->m_biaya->biayapesawatpb($id_transaksi);

        $jumlahbarispesawatpjum = $this->m_kategori->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support', 'pb'])->whereIn('kategori', ['Pesawat'])->groupBy(['baris', 'id_transaksi'])->orderBy('id_kategori', 'asc')->select('baris')->findAll();
        $barisataspesawatpjum = $this->m_kategori->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support', 'pb'])->whereIn('kategori', ['Pesawat'])->groupBy(['baris', 'id_transaksi'])->orderBy('id_kategori', 'asc')->select('baris')->first();
        $jumlahbarispesawatpb = $this->m_kategori->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support', 'pjum'])->whereIn('kategori', ['Pesawat'])->groupBy(['baris', 'id_transaksi'])->orderBy('id_kategori', 'asc')->select('baris')->findAll();
        $barisataspesawatpb = $this->m_kategori->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support', 'pjum'])->whereIn('kategori', ['Pesawat'])->groupBy(['baris', 'id_transaksi'])->orderBy('id_kategori', 'asc')->select('baris')->first();

        $valaspesawat = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->groupBy(['kode_valas', 'id_transaksi'])->orderBy('id_biaya', 'asc')->select('kode_valas')->findAll();
        $valastot = $this->m_biaya->where('id_transaksi', $id_transaksi)->groupBy(['kode_valas', 'id_transaksi'])->orderBy('id_biaya', 'asc')->select('kode_valas')->findAll();
        $countpes = count((array)$valaspesawat);

        $listkategori = $this->m_kategori->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->groupBy(['kategori', 'id_transaksi'])->orderBy('kategori', 'asc')->select('kategori')->findAll();
        $countkat = count((array)$listkategori);
        $alphakat = $arraypjum[$countkat];

        $totalbiayapesawat = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->whereIn('kategori', ['Tiket Pesawat'])->groupBy(['id_valas', 'id_transaksi'])->orderBy('id_biaya', 'asc')->select('sum(biaya) as sum, kode_valas')->findAll();
        $totalbiayabagasi = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->whereIn('kategori', ['Bagasi Pesawat'])->groupBy(['id_valas', 'id_transaksi'])->orderBy('id_biaya', 'asc')->select('sum(biaya) as sum, kode_valas')->findAll();
        $totalbiayaporter = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->whereIn('kategori', ['Porter Pesawat'])->groupBy(['id_valas', 'id_transaksi'])->orderBy('id_biaya', 'asc')->select('sum(biaya) as sum, kode_valas')->findAll();
        $totalbiayahotel = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->whereIn('kategori', ['Hotel'])->groupBy(['id_valas', 'id_transaksi'])->orderBy('id_biaya', 'asc')->select('sum(biaya) as sum, kode_valas')->findAll();
        $totalbiayamakan = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->whereIn('kategori', ['Makan dan Minum'])->groupBy(['id_valas', 'id_transaksi'])->orderBy('id_biaya', 'asc')->select('sum(biaya) as sum, kode_valas')->findAll();
        $totalbiayatrans = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->whereIn('kategori', ['Transportasi'])->groupBy(['id_valas', 'id_transaksi'])->orderBy('id_biaya', 'asc')->select('sum(biaya) as sum, kode_valas')->findAll();
        $totalbiayalaundry = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->whereIn('kategori', ['Laundry'])->groupBy(['id_valas', 'id_transaksi'])->orderBy('id_biaya', 'asc')->select('sum(biaya) as sum, kode_valas')->findAll();
        $totalbiayalain = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->whereIn('kategori', ['Lain-lain'])->groupBy(['id_valas', 'id_transaksi'])->orderBy('id_biaya', 'asc')->select('sum(biaya) as sum, kode_valas')->findAll();
        $totalbiayatot = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->groupBy(['id_valas', 'id_transaksi'])->orderBy('id_biaya', 'asc')->select('sum(biaya) as sum, kode_valas')->findAll();
        $totalbiayasel = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->groupBy(['id_valas', 'id_transaksi'])->orderBy('id_biaya', 'asc')->select('sum(biaya) as sum, kode_valas')->findAll();

        foreach($valaspesawat as $iv => $val) {
            $valas_ada = null; // 0= tidak ada, 1= ada
            $temp_isi = null;
            foreach($totalbiayapesawat as $ib => $bia) {
                if($val['kode_valas'] == $bia['kode_valas']) {
                    $valas_ada = 1;
                    $temp_isi = $bia['sum'];
                    break;
                }
            }

            $temptotalbiayapesawat[$iv] = array(
                'sum' => $temp_isi,
            );
        }

        $totalbiayapesawat = $temptotalbiayapesawat;

        foreach($valaspesawat as $iv => $val) {
            $valas_ada = null; // 0= tidak ada, 1= ada
            $temp_isi = null;
            foreach($totalbiayabagasi as $ib => $bia) {
                if($val['kode_valas'] == $bia['kode_valas']) {
                    $valas_ada = 1;
                    $temp_isi = $bia['sum'];
                    break;
                }
            }

            $temptotalbiayabagasi[$iv] = array(
                'sum' => $temp_isi,
            );
        }

        $totalbiayabagasi = $temptotalbiayabagasi;

        foreach($valaspesawat as $iv => $val) {
            $valas_ada = null; // 0= tidak ada, 1= ada
            $temp_isi = null;
            foreach($totalbiayaporter as $ib => $bia) {
                if($val['kode_valas'] == $bia['kode_valas']) {
                    $valas_ada = 1;
                    $temp_isi = $bia['sum'];
                    break;
                }
            }

            $temptotalbiayaporter[$iv] = array(
                'sum' => $temp_isi,
            );
        }

        $totalbiayaporter = $temptotalbiayaporter;

        foreach($valaspesawat as $iv => $val) {
            $valas_ada = null; // 0= tidak ada, 1= ada
            $temp_isi = null;
            foreach($totalbiayahotel as $ib => $bia) {
                if($val['kode_valas'] == $bia['kode_valas']) {
                    $valas_ada = 1;
                    $temp_isi = $bia['sum'];
                    break;
                }
            }

            $temptotalbiayahotel[$iv] = array(
                'sum' => $temp_isi,
            );
        }

        $totalbiayahotel = $temptotalbiayahotel;

        foreach($valaspesawat as $iv => $val) {
            $valas_ada = null; // 0= tidak ada, 1= ada
            $temp_isi = null;
            foreach($totalbiayamakan as $ib => $bia) {
                if($val['kode_valas'] == $bia['kode_valas']) {
                    $valas_ada = 1;
                    $temp_isi = $bia['sum'];
                    break;
                }
            }

            $temptotalbiayamakan[$iv] = array(
                'sum' => $temp_isi,
            );
        }

        $totalbiayamakan = $temptotalbiayamakan;

        foreach($valaspesawat as $iv => $val) {
            $valas_ada = null; // 0= tidak ada, 1= ada
            $temp_isi = null;
            foreach($totalbiayatrans as $ib => $bia) {
                if($val['kode_valas'] == $bia['kode_valas']) {
                    $valas_ada = 1;
                    $temp_isi = $bia['sum'];
                    break;
                }
            }

            $temptotalbiayatrans[$iv] = array(
                'sum' => $temp_isi,
            );
        }

        $totalbiayatrans = $temptotalbiayatrans;

        foreach($valaspesawat as $iv => $val) {
            $valas_ada = null; // 0= tidak ada, 1= ada
            $temp_isi = null;
            foreach($totalbiayalaundry as $ib => $bia) {
                if($val['kode_valas'] == $bia['kode_valas']) {
                    $valas_ada = 1;
                    $temp_isi = $bia['sum'];
                    break;
                }
            }

            $temptotalbiayalaundry[$iv] = array(
                'sum' => $temp_isi,
            );
        }

        $totalbiayalaundry = $temptotalbiayalaundry;

        foreach($valaspesawat as $iv => $val) {
            $valas_ada = null; // 0= tidak ada, 1= ada
            $temp_isi = null;
            foreach($totalbiayalain as $ib => $bia) {
                if($val['kode_valas'] == $bia['kode_valas']) {
                    $valas_ada = 1;
                    $temp_isi = $bia['sum'];
                    break;
                }
            }

            $temptotalbiayalain[$iv] = array(
                'sum' => $temp_isi,
            );
        }

        $totalbiayalain = $temptotalbiayalain;

        foreach($valastot as $iv => $val) {
            $valas_ada = null; // 0= tidak ada, 1= ada
            $temp_isi = null;
            foreach($totalbiayatot as $ib => $bia) {
                if($val['kode_valas'] == $bia['kode_valas']) {
                    $valas_ada = 1;
                    $temp_isi = $bia['sum'];
                    break;
                }
            }

            $temptotalbiayatot[$iv] = array(
                'sum' => $temp_isi,
            );
        }

        $totalbiayatot = $temptotalbiayatot;

        foreach($valastot as $iv => $val) {
            $valas_ada = null; // 0= tidak ada, 1= ada
            $temp_isi = null;
            foreach($totalbiayasel as $ib => $bia) {
                if($val['kode_valas'] == $bia['kode_valas']) {
                    $valas_ada = 1;
                    $temp_isi = $bia['sum'];
                    break;
                }
            }

            $temptotalbiayasel[$iv] = array(
                'sum' => $temp_isi,
            );
        }

        $totalbiayasel = $temptotalbiayasel;

        $totalbiayasupport = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['pjum', 'pb'])->groupBy(['id_valas', 'id_transaksi'])->orderBy('id_biaya', 'asc')->select('sum(biaya)')->findAll();

        $arr2 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $valaspesawat));

        $exp2 = explode(' ', $arr2);

        $arr3 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $totalbiayapesawat));

        $exp3 = explode(' ', $arr3);

        $arr3a = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $totalbiayabagasi));

        $exp3a = explode(' ', $arr3a);

        $arr3b = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $totalbiayaporter));

        $exp3b = explode(' ', $arr3b);

        $arr4 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $totalbiayahotel));

        $exp4 = explode(' ', $arr4);

        $arr5 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $totalbiayamakan));

        $exp5 = explode(' ', $arr5);

        $arr6 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $totalbiayatrans));

        $exp6 = explode(' ', $arr6);

        $arr7 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $totalbiayalaundry));

        $exp7 = explode(' ', $arr7);

        $arr8 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $totalbiayalain));

        $exp8 = explode(' ', $arr8);

        $arr9 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $totalbiayatot));

        $exp9 = explode(' ', $arr9);

        $arr10 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $totalbiayasupport));

        $exp10 = explode(' ', $arr10);

        $arr11 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $valastot));

        $exp11 = explode(' ', $arr11);

        $arr12 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $totalbiayasel));

        $exp12 = explode(' ', $arr12);

        $arr13 = implode(' ', array_map(function ($entry) {
            return ($entry[key($entry)]);
        }, $totalbiayatot));

        $exp13 = explode(' ', $arr13);

        $jumlahpjumpesawat = count((array)$jumlahbarispesawatpjum);
        $jumlahpbpesawat = count((array)$jumlahbarispesawatpb);
        if ($jumlahpjumpesawat < $jumlahpbpesawat) {
            $jumlah = $jumlahpbpesawat;
        } else {
            $jumlah = $jumlahpjumpesawat;
        }

        // $barispjumpesawat = $jumlahpjumpesawat + 13;
        // $barispbpesawat = $jumlahpbpesawat + 13;
        // if ($barispjumpesawat < $barispbpesawat) {
        //     $baris = $barispbpesawat;
        // } else {
        //     $baris = $barispjumpesawat;
        // }

        $baris = 16;

        $biayapb = $this->m_biaya->getDataBiaya($id_transaksi, 'pb');
        $biayasupport = $this->m_biaya->getDataBiaya($id_transaksi, 'support');

        $bawahpb = $this->m_kategori->where('id_transaksi', $id_transaksi)->where('jenis_biaya', 'pb')->select('baris')->orderBy('id_kategori', 'desc')->first();
        $bawahsupport = $this->m_kategori->where('id_transaksi', $id_transaksi)->where('jenis_biaya', 'support')->select('baris')->orderBy('id_kategori', 'desc')->first();

        $pum = $this->m_pum->alldataIdt($id_transaksi);
        $nik = $this->m_id->where('id_transaksi', $id_transaksi)->select('nik, tanggal_berangkat, tanggal_pulang')->first();
        $kota = $this->m_id->where('id_transaksi', $id_transaksi)->select('kota')->first();
        $karyawan = $this->m_am21->where('nik', $nik['nik'])->select('niknm, strorg')->first();
        $bagian = $this->m_bm06->where('strorg', $karyawan['strorg'])->select('strorgnm')->first();

        $personil = $this->m_personil->personil($id_transaksi);
        $negara = $this->m_negara_tujuan->negaratujuan($id_transaksi);
        $nomorpjum = $this->m_pjum->pjum($id_transaksi);
        $nomorpb = $this->m_pb->pb($id_transaksi);

        $niknm_perso = '';
        $nik_perso = '';
        foreach ($personil as $pr => $perso) {
            $niknm_perso .= $perso['niknm'].', ';
            $nik_perso .= $perso['nik'].'_';
        }

        $tmp_negara = '';
        foreach ($negara as $ng => $neg) {
            $tmp_negara .= $neg['negara_tujuan'].', ';
        }

        $tmp_nopjum = '';
        foreach ($nomorpjum as $np => $nopjum) {
            $nompjum = $nopjum['nomor'];
            if ($nompjum == null) {
                $nompjum = "no pjum masih kosong";
            }
            $tmp_nopjum .= $nompjum.', ';
        }

        $tmp_nopb = '';
        foreach ($nomorpb as $np => $nopb) {
            $nompb = $nopb['nomor'];
            if ($nompb == null) {
                $nompb = "no pb masih kosong";
            }
            $tmp_nopb .= $nompb.', ';
        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(12);
        $sheet = $spreadsheet->getActiveSheet();

        // Total Biaya Pengeluaran
        $sheet->setTitle("Biaya Perjalanan Dinas LN");

        $sheet->setCellValue('B1', 'PERJALANAN DINAS LUAR NEGERI_'.substr($nik_perso, 0, -1).'_'.$id_transaksi);
        $sheet->setCellValue('B3', 'Data Karyawan');
        $sheet->setCellValue('B4', 'Nama Karyawan =>');
        $sheet->setCellValue('B5', 'Bagian =>');
        $sheet->setCellValue('B6', 'No PJUM =>');
        $sheet->setCellValue('B7', 'No PB =>');
        $sheet->setCellValue('B9', 'Waktu Tugas');
        $sheet->setCellValue('B10', 'Negara Tujuan =>');
        $sheet->setCellValue('B11', 'Tanggal Keberangkatan (YYYY-MM-DD) =>');
        $sheet->setCellValue('B12', 'Tanggal Pulang (YYYY-MM-DD) =>');
        $sheet->setCellValue('B13', 'Berangkat dari kota =>');
        $sheet->setCellValue('C4', substr($niknm_perso, 0, -2));
        $sheet->setCellValue('C5', $bagian['strorgnm']);
        $sheet->setCellValue('C6', substr($tmp_nopjum, 0, -2));
        $sheet->setCellValue('C7', substr($tmp_nopb, 0, -2));
        $sheet->setCellValue('C10', substr($tmp_negara, 0, -2));
        $sheet->setCellValue('C11', $nik['tanggal_berangkat']);
        $sheet->setCellValue('C12', $nik['tanggal_pulang']);
        $sheet->setCellValue('C13', $kota['kota']);

        $sheet->mergeCells('B1:'.$alphaall.'1');

        $sheet->getStyle('B1')->getFont()->setBold(true);
        $sheet->getStyle('B3')->getFont()->setBold(true);
        $sheet->getStyle('B9')->getFont()->setBold(true);
        $sheet->getStyle('D:'.$alphasel)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D:'.$alphasel)->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('B:'.$alphaall)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B:'.$alphaall)->getAlignment()->setVertical('center');

        // $sheet->getStyle('C4')->getAlignment()->setWrapText(true);
        // $sheet->getStyle('C10')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('A')->setVisible(false);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);

        for ($k = 'D'; $k <= $alphasel; $k++) {
            $spreadsheet->getActiveSheet()->getColumnDimension($k)->setWidth(15);
        }

        // $sheet->setCellValue('B15', 'Biaya Pengeluaran Selama Perjalanan Dinas Luar Negeri');
        // $sheet->setCellValue('B17', 'Kategori (PJUM + PB)');
        // $sheet->setCellValue('D17', 'Total Biaya (PJUM + PB)');

        // $sheet->fromArray($exp2, NULL, 'D'.$baris+2);

        // $sheet->mergeCells('B17:C'.(int)$baris + 2);
        // $sheet->mergeCells('B15:'.$alphaall.'16');
        // $sheet->mergeCells('D17:'.$alphaall.'17');

        // $sheet->getStyle('B15:'.$alphaall.'16')->getFont()->setBold( true );

        // $i = $countkat + 18;
        // $sheet->getStyle('B15:'.$alphaall.$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        // $i++;

        // $row = 19;
        // foreach ($listkategori as $lk => $liskat) {
        //     $totalbiaya = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->whereIn('kategori', [$liskat['kategori']])->groupBy(['id_valas', 'id_transaksi'])->orderBy('id_biaya', 'asc')->select('sum(biaya) as sum, kode_valas')->findAll();
        //     foreach($valaspesawat as $iv => $val){
        //         $valas_ada = 0; // 0= tidak ada, 1= ada
        //         $temp_isi = 0;
        //         foreach($totalbiaya as $ib => $bia){
        //             if($val['kode_valas'] == $bia['kode_valas']){
        //                 $valas_ada = 1;
        //                 $temp_isi = $bia['sum'];
        //                 break;
        //             }
        //         }

        //         $temptotalbiaya[$iv] = array(
        //             'sum' => $temp_isi,
        //         );
        //     }
        //     $totalbiaya = $temptotalbiaya;

        //     $sheet->mergeCells('B'.$row.':C'.$row);

        //     $sheet->setCellValue('B'.$row, $liskat['kategori']);

        //     $column = 4;
        //     foreach ($totalbiaya as $key => $value) {
        //         $sheet->setCellValueByColumnAndRow($column, $row, $value['sum']);
        //         $column++;
        //     }
        //     $row++;
        // }

        // $baristotal = 19 + $countkat; //24
        // $sheet->setCellValue('B'.$baristotal, 'Total Seluruh Biaya Pengeluaran (PJUM + PB)');//24

        // $sheet->fromArray($exp9, NULL, 'D'.$baristotal);//24

        // $baristot = $baristotal - 1;//24
        // $baristot1 = $baristotal;//25
        // $sheet->mergeCells('B'.$baristot1.':C'.$baristot1 + 1);

        // for ($i='D', $k='D'; $i<=$alphaall; $i++, $k++) {
        //     $sheet->mergeCells($i.$baristot1.':'.$k.$baristot1 + 1);
        // }

        // $sheet->getStyle('B'.$baristot1.':'.$alphaall.$baristot1 + 1)->getFont()->setBold( true );

        // $i = $baristotal + 1;//41
        // $sheet->getStyle('B'.$baristot1.':'.$alphaall.$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        // $i++;

        // // Biaya Support
        // $barissup = $baristotal + 8; //
        // $indexjudul = $baristotal + 5; //
        // $indexsupport = $countsupport + $barissup; //39
        // $indextotal = $indexsupport + 2;
        // $indexkat = $baristotal + 6;

        // $sheet->setCellValue('B'.$indexjudul, 'Biaya Support Perjalanan Dinas Luar Negeri');
        // $sheet->setCellValue('B'.$indexjudul + 1, 'Tanggal');
        // $sheet->setCellValue('C'.$indexjudul + 1, 'Kategori');
        // $sheet->setCellValue('D'.$indexjudul + 1, 'Jumlah Personil');
        // $sheet->setCellValue('E'.$indexjudul + 1, 'Biaya');
        // $sheet->setCellValue('B'.$indexsupport, 'Total Biaya Support');

        // $sheet->fromArray($exp10, NULL, 'E'.$indexsupport);

        // $sheet->mergeCells('B'.$indexjudul.':'.$alphasup.$indexjudul);
        // $sheet->mergeCells('B'.$indexkat.':B'.$indexkat + 1);
        // $sheet->mergeCells('C'.$indexkat.':C'.$indexkat + 1);
        // $sheet->mergeCells('D'.$indexkat.':D'.$indexkat + 1);
        // $sheet->mergeCells('B'.$indexsupport.':D'.$indexsupport);

        // $sheet->getStyle('B'.$indexjudul)->getFont()->setBold( true );
        // $sheet->getStyle('B'.$indexsupport.':'.$alphasup.$indexsupport)->getFont()->setBold( true );

        // $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

        // for ($i=$indexjudul; $i <= $indexsupport; $i++) {
        //     $sheet->getStyle('B'.$indexjudul.':'.$alphasup.$indexsupport)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        //     $sheet->getStyle('D'.$indexjudul.':D'.$i)->getNumberFormat()->setFormatCode('#');
        //     $i++;
        // }

        // foreach ($biayasupport as $key => $value) {
        //     $i = $indexjudul + 2;
        //     $column = $value['kolom'];
        //     $row = $baristotal + (int)$value['baris'];
        //     $sheet->setCellValueByColumnAndRow($column, $i, $value['kode_valas']);
        //     $i++;
        //     for ($j=$indexjudul + 3; $j <= $indexsupport; $j++) {
        //         $bia = $value['biaya'];
        //         if ($bia == 0) {
        //             $bia = null;
        //         }
        //         $sheet->setCellValueByColumnAndRow(5, $row, $bia);
        //     }
        // }

        // $row = $indexjudul + 3;
        // foreach ($kategorisupport as $key => $value) {
        //     $sheet->setCellValue('B'.$row, $value['tanggal']);
        //     $sheet->setCellValue('C'.$row, $value['kategori']);
        //     $sheet->setCellValue('D'.$row, $value['jumlah_personil']);
        //     $row++;
        // }

        // $sheet->setCellValue('B'.$indextotal, 'Total Biaya Perjalanan Dinas Luar Negeri');//57
        // $sheet->setCellValue('B'.$indextotal + 1, 'Total Biaya (PJUM + PB + Support)');//58

        // $sheet->fromArray($exp11, NULL, 'D'.$indexsupport + 3);//58
        // $sheet->fromArray($exp12, NULL, 'D'.$indexsupport + 4);//59

        // $bottom = $indexsupport + 4;

        // $sheet->getStyle('B'.$bottom.':'.$alphaall.$bottom)->getFont()->setBold( true );

        // $barissel = $indextotal;//57
        // $barissel1 = $indextotal + 1;//58
        // $sheet->mergeCells('B'.$barissel.':'.$alphasel.$barissel);
        // $sheet->mergeCells('B'.$barissel1.':C'.$indextotal + 2);//59

        // $sheet->getStyle('B'.$barissel.':B'.$barissel1)->getFont()->setBold( true );
        // // $sheet->getStyle('B'.$barissel)->getAlignment()->setHorizontal('left');

        // $i = $indextotal + 2;//59
        // $sheet->getStyle('B'.$barissel.':'.$alphasel.$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        // $i++;
        //Akhir baris

        $baris = 15;
        $sheet->setCellValue('B'.(int)$baris + 1, '1. Tiket Pesawat');
        $sheet->setCellValue('B'.(int)$baris + 2, 'Total Biaya Kategori Tiket Pesawat (PJUM + PB)');

        $sheet->fromArray($exp2, null, 'D'.(int)$baris + 2);//20
        $sheet->fromArray($exp3, null, 'D'.(int)$baris + 3);//21

        $barispesawat = (int)$baris;//18
        $barishotel = (int)$baris + 1;//19
        $barishotel1 = (int)$baris + 2;//20
        $barishotel2 = (int)$baris + 3;//20
        $sheet->mergeCells('B'.$barispesawat.':'.$alphaall.$barispesawat);
        $sheet->mergeCells('B'.$barishotel.':'.$alphaall.$barishotel);
        $sheet->mergeCells('B'.$barishotel1.':C'.(int)$baris + 3);//21

        $sheet->getStyle('B'.$barishotel.':B'.$barishotel1)->getFont()->setBold(true);
        $sheet->getStyle('B'.$barishotel)->getAlignment()->setHorizontal('left');
        $sheet->getStyle('D'.$barishotel2.':'.$alphaall.$barishotel2)->getAlignment()->setHorizontal('right');

        $i = (int)$baris + 3;
        $sheet->getStyle('B'.$barispesawat.':'.$alphaall.$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $i++;

        $baris = 19;
        $sheet->setCellValue('B'.(int)$baris + 1, '2. Bagasi Pesawat');
        $sheet->setCellValue('B'.(int)$baris + 2, 'Total Biaya Kategori Bagasi Pesawat (PJUM + PB)');

        $sheet->fromArray($exp2, null, 'D'.(int)$baris + 2);//20
        $sheet->fromArray($exp3a, null, 'D'.(int)$baris + 3);//21

        $barispesawat = (int)$baris;//18
        $barishotel = (int)$baris + 1;//19
        $barishotel1 = (int)$baris + 2;//20
        $barishotel2 = (int)$baris + 3;//20
        $sheet->mergeCells('B'.$barispesawat.':'.$alphaall.$barispesawat);
        $sheet->mergeCells('B'.$barishotel.':'.$alphaall.$barishotel);
        $sheet->mergeCells('B'.$barishotel1.':C'.(int)$baris + 3);//21

        $sheet->getStyle('B'.$barishotel.':B'.$barishotel1)->getFont()->setBold(true);
        $sheet->getStyle('B'.$barishotel)->getAlignment()->setHorizontal('left');
        $sheet->getStyle('D'.$barishotel2.':'.$alphaall.$barishotel2)->getAlignment()->setHorizontal('right');

        $i = (int)$baris + 3;
        $sheet->getStyle('B'.$barispesawat.':'.$alphaall.$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $i++;

        $baris = 23;
        $sheet->setCellValue('B'.(int)$baris + 1, '3. Porter Pesawat');
        $sheet->setCellValue('B'.(int)$baris + 2, 'Total Biaya Kategori Porter Pesawat (PJUM + PB)');

        $sheet->fromArray($exp2, null, 'D'.(int)$baris + 2);//20
        $sheet->fromArray($exp3b, null, 'D'.(int)$baris + 3);//21

        $barispesawat = (int)$baris;//18
        $barishotel = (int)$baris + 1;//19
        $barishotel1 = (int)$baris + 2;//20
        $barishotel2 = (int)$baris + 3;//20
        $sheet->mergeCells('B'.$barispesawat.':'.$alphaall.$barispesawat);
        $sheet->mergeCells('B'.$barishotel.':'.$alphaall.$barishotel);
        $sheet->mergeCells('B'.$barishotel1.':C'.(int)$baris + 3);//21

        $sheet->getStyle('B'.$barishotel.':B'.$barishotel1)->getFont()->setBold(true);
        $sheet->getStyle('B'.$barishotel)->getAlignment()->setHorizontal('left');
        $sheet->getStyle('D'.$barishotel2.':'.$alphaall.$barishotel2)->getAlignment()->setHorizontal('right');

        $i = (int)$baris + 3;
        $sheet->getStyle('B'.$barispesawat.':'.$alphaall.$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $i++;

        $baris = 27;
        $sheet->setCellValue('B'.(int)$baris + 1, '4. Hotel');
        $sheet->setCellValue('B'.(int)$baris + 2, 'Total Biaya Kategori Hotel (PJUM + PB)');

        $sheet->fromArray($exp2, null, 'D'.(int)$baris + 2);//20
        $sheet->fromArray($exp4, null, 'D'.(int)$baris + 3);//21

        $barispesawat = (int)$baris;//18
        $barishotel = (int)$baris + 1;//19
        $barishotel1 = (int)$baris + 2;//20
        $barishotel2 = (int)$baris + 3;//20
        $sheet->mergeCells('B'.$barispesawat.':'.$alphaall.$barispesawat);
        $sheet->mergeCells('B'.$barishotel.':'.$alphaall.$barishotel);
        $sheet->mergeCells('B'.$barishotel1.':C'.(int)$baris + 3);//21

        $sheet->getStyle('B'.$barishotel.':B'.$barishotel1)->getFont()->setBold(true);
        $sheet->getStyle('B'.$barishotel)->getAlignment()->setHorizontal('left');
        $sheet->getStyle('D'.$barishotel2.':'.$alphaall.$barishotel2)->getAlignment()->setHorizontal('right');

        $i = (int)$baris + 3;
        $sheet->getStyle('B'.$barispesawat.':'.$alphaall.$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $i++;

        $baris = 31;
        $sheet->setCellValue('B'.(int)$baris + 1, '5. Makan dan Minum');
        $sheet->setCellValue('B'.(int)$baris + 2, 'Total Biaya Kategori Makan dan Minum (PJUM + PB)');

        $sheet->fromArray($exp2, null, 'D'.(int)$baris + 2);//20
        $sheet->fromArray($exp5, null, 'D'.(int)$baris + 3);//21

        $barispesawat = (int)$baris;//18
        $barishotel = (int)$baris + 1;//19
        $barishotel1 = (int)$baris + 2;//20
        $barishotel2 = (int)$baris + 3;//20
        $sheet->mergeCells('B'.$barispesawat.':'.$alphaall.$barispesawat);
        $sheet->mergeCells('B'.$barishotel.':'.$alphaall.$barishotel);
        $sheet->mergeCells('B'.$barishotel1.':C'.(int)$baris + 3);//21

        $sheet->getStyle('B'.$barishotel.':B'.$barishotel1)->getFont()->setBold(true);
        $sheet->getStyle('B'.$barishotel)->getAlignment()->setHorizontal('left');
        $sheet->getStyle('D'.$barishotel2.':'.$alphaall.$barishotel2)->getAlignment()->setHorizontal('right');

        $i = (int)$baris + 3;
        $sheet->getStyle('B'.$barispesawat.':'.$alphaall.$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $i++;

        $baris = 35;
        $sheet->setCellValue('B'.(int)$baris + 1, '6. Transportasi');
        $sheet->setCellValue('B'.(int)$baris + 2, 'Total Biaya Kategori Transportasi (PJUM + PB)');

        $sheet->fromArray($exp2, null, 'D'.(int)$baris + 2);//20
        $sheet->fromArray($exp6, null, 'D'.(int)$baris + 3);//21

        $barispesawat = (int)$baris;//18
        $barishotel = (int)$baris + 1;//19
        $barishotel1 = (int)$baris + 2;//20
        $barishotel2 = (int)$baris + 3;//20
        $sheet->mergeCells('B'.$barispesawat.':'.$alphaall.$barispesawat);
        $sheet->mergeCells('B'.$barishotel.':'.$alphaall.$barishotel);
        $sheet->mergeCells('B'.$barishotel1.':C'.(int)$baris + 3);//21

        $sheet->getStyle('B'.$barishotel.':B'.$barishotel1)->getFont()->setBold(true);
        $sheet->getStyle('B'.$barishotel)->getAlignment()->setHorizontal('left');
        $sheet->getStyle('D'.$barishotel2.':'.$alphaall.$barishotel2)->getAlignment()->setHorizontal('right');

        $i = (int)$baris + 3;
        $sheet->getStyle('B'.$barispesawat.':'.$alphaall.$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $i++;

        $baris = 39;
        $sheet->setCellValue('B'.(int)$baris + 1, '7. Laundry');
        $sheet->setCellValue('B'.(int)$baris + 2, 'Total Biaya Kategori Laundry (PJUM + PB)');

        $sheet->fromArray($exp2, null, 'D'.(int)$baris + 2);//20
        $sheet->fromArray($exp7, null, 'D'.(int)$baris + 3);//21

        $barispesawat = (int)$baris;//18
        $barishotel = (int)$baris + 1;//19
        $barishotel1 = (int)$baris + 2;//20
        $barishotel2 = (int)$baris + 3;//20
        $sheet->mergeCells('B'.$barispesawat.':'.$alphaall.$barispesawat);
        $sheet->mergeCells('B'.$barishotel.':'.$alphaall.$barishotel);
        $sheet->mergeCells('B'.$barishotel1.':C'.(int)$baris + 3);//21

        $sheet->getStyle('B'.$barishotel.':B'.$barishotel1)->getFont()->setBold(true);
        $sheet->getStyle('B'.$barishotel)->getAlignment()->setHorizontal('left');
        $sheet->getStyle('D'.$barishotel2.':'.$alphaall.$barishotel2)->getAlignment()->setHorizontal('right');

        $i = (int)$baris + 3;
        $sheet->getStyle('B'.$barispesawat.':'.$alphaall.$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $i++;

        $baris = 43;
        $sheet->setCellValue('B'.(int)$baris + 1, '8. Lain-lain');
        $sheet->setCellValue('B'.(int)$baris + 2, 'Total Biaya Kategori Lain-lain (PJUM + PB)');

        $sheet->fromArray($exp2, null, 'D'.(int)$baris + 2);//20
        $sheet->fromArray($exp8, null, 'D'.(int)$baris + 3);//21

        $barispesawat = (int)$baris;//18
        $barishotel = (int)$baris + 1;//19
        $barishotel1 = (int)$baris + 2;//20
        $barishotel2 = (int)$baris + 3;//20
        $sheet->mergeCells('B'.$barispesawat.':'.$alphaall.$barispesawat);
        $sheet->mergeCells('B'.$barishotel.':'.$alphaall.$barishotel);
        $sheet->mergeCells('B'.$barishotel1.':C'.(int)$baris + 3);//21

        $sheet->getStyle('B'.$barishotel.':B'.$barishotel1)->getFont()->setBold(true);
        $sheet->getStyle('B'.$barishotel)->getAlignment()->setHorizontal('left');
        $sheet->getStyle('D'.$barishotel2.':'.$alphaall.$barishotel2)->getAlignment()->setHorizontal('right');

        $i = (int)$baris + 3;
        $sheet->getStyle('B'.$barispesawat.':'.$alphaall.$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $i++;

        $baris = 47;
        $sheet->setCellValue('B'.(int)$baris + 1, 'Total Biaya Perjalanan Dinas Luar Negeri');
        $sheet->setCellValue('B'.(int)$baris + 2, 'Total Biaya (PJUM + PB)');

        $sheet->fromArray($exp2, null, 'D'.(int)$baris + 2);//20
        $sheet->fromArray($exp13, null, 'D'.(int)$baris + 3);//21

        $barispesawat = (int)$baris;//18
        $barishotel = (int)$baris + 1;//19
        $barishotel1 = (int)$baris + 2;//20
        $barishotel2 = (int)$baris + 3;//20
        $sheet->mergeCells('B'.$barispesawat.':'.$alphaall.$barispesawat);
        $sheet->mergeCells('B'.$barishotel.':'.$alphaall.$barishotel);
        $sheet->mergeCells('B'.$barishotel1.':C'.(int)$baris + 3);//21

        $sheet->getStyle('B'.$barishotel.':B'.$barishotel1)->getFont()->setBold(true);
        $sheet->getStyle('D'.$barishotel2.':'.$alphaall.$barishotel2)->getFont()->setBold(true);
        $sheet->getStyle('B'.$barishotel)->getAlignment()->setHorizontal('left');
        $sheet->getStyle('D'.$barishotel2.':'.$alphaall.$barishotel2)->getAlignment()->setHorizontal('right');

        $i = (int)$baris + 3;
        $sheet->getStyle('B'.$barispesawat.':'.$alphaall.$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $i++;

        // Biaya Support

        if (empty($biayasupport)) {

        } else {
            $baris = 53;//49
            // $barissup = $baristotal + 8; //
            $indexsupport = $countsupport + 57; //39
            // $indextotal = $indexsupport + 2;
            $indexkat = $baris + 2;

            $sheet->setCellValue('B'.$baris, 'Biaya Support Perjalanan Dinas Luar Negeri');
            $sheet->setCellValue('B'.$indexkat, 'Tanggal');
            $sheet->setCellValue('C'.$indexkat, 'Kategori');
            $sheet->setCellValue('D'.$indexkat, 'Jumlah Personil');
            $sheet->setCellValue('E'.$indexkat, 'Biaya');
            $sheet->setCellValue('B'.$indexsupport, 'Total Biaya Support');

            $sheet->fromArray($exp10, null, 'E'.$indexsupport);

            $sheet->mergeCells('B'.$baris.':'.$alphasup.$baris + 1);
            $sheet->mergeCells('B'.$indexkat.':B'.$indexkat + 1);
            $sheet->mergeCells('C'.$indexkat.':C'.$indexkat + 1);
            $sheet->mergeCells('D'.$indexkat.':D'.$indexkat + 1);
            $sheet->mergeCells('B'.$indexsupport.':D'.$indexsupport);

            $sheet->getStyle('B'.$baris)->getFont()->setBold(true);
            $sheet->getStyle('B'.$indexsupport.':'.$alphasup.$indexsupport)->getFont()->setBold(true);

            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

            for ($i = $baris; $i <= $indexsupport; $i++) {
                $sheet->getStyle('B'.$baris.':'.$alphasup.$indexsupport)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('D'.$baris.':D'.$i)->getNumberFormat()->setFormatCode('#');
                $i++;
            }

            foreach ($biayasupport as $key => $value) {
                $i = $baris + 3;
                $column = $value['kolom'];
                $row = 49 + (int)$value['baris'];
                $sheet->setCellValueByColumnAndRow($column, $i, $value['kode_valas']);
                $i++;
                for ($j = $baris + 4; $j <= $indexsupport; $j++) {
                    $bia = $value['biaya'];
                    if ($bia == 0) {
                        $bia = null;
                    }
                    $sheet->setCellValueByColumnAndRow(5, $row, $bia);
                    $sheet->getStyle('E'.$row.':E'.$indexsupport)->getAlignment()->setHorizontal('right');
                }
            }

            $row = $baris + 4;
            foreach ($kategorisupport as $key => $value) {
                $sheet->setCellValue('B'.$row, $value['tanggal']);
                $sheet->setCellValue('C'.$row, $value['kategori']);
                $sheet->setCellValue('D'.$row, $value['jumlah_personil']);
                $row++;
            }

            $baris = $indexsupport + 2;
            $sheet->setCellValue('B'.(int)$baris + 1, 'Total Biaya Perjalanan Dinas Luar Negeri');
            $sheet->setCellValue('B'.(int)$baris + 2, 'Total Biaya (PJUM + PB + Support)');

            $sheet->fromArray($exp11, null, 'D'.(int)$baris + 2);//20
            $sheet->fromArray($exp12, null, 'D'.(int)$baris + 3);//21

            $barispesawat = (int)$baris;//18
            $barishotel = (int)$baris + 1;//19
            $barishotel1 = (int)$baris + 2;//20
            $barishotel2 = (int)$baris + 3;//20
            $sheet->mergeCells('B'.$barispesawat.':'.$alphaall.$barispesawat);
            $sheet->mergeCells('B'.$barishotel.':'.$alphaall.$barishotel);
            $sheet->mergeCells('B'.$barishotel1.':C'.(int)$baris + 3);//21

            $sheet->getStyle('B'.$barishotel.':B'.$barishotel1)->getFont()->setBold(true);
            $sheet->getStyle('D'.$barishotel2.':'.$alphaall.$barishotel2)->getFont()->setBold(true);
            $sheet->getStyle('B'.$barishotel)->getAlignment()->setHorizontal('left');
            $sheet->getStyle('D'.$barishotel2.':'.$alphaall.$barishotel2)->getAlignment()->setHorizontal('right');

            $i = (int)$baris + 3;
            $sheet->getStyle('B'.$barispesawat.':'.$alphaall.$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $i++;
        }

        // $sheet->setCellValue('B'.$indextotal, 'Total Biaya Perjalanan Dinas Luar Negeri');//57
        // $sheet->setCellValue('B'.$indextotal + 1, 'Total Biaya (PJUM + PB + Support)');//58

        // $sheet->fromArray($exp11, NULL, 'D'.$indexsupport + 3);//58
        // $sheet->fromArray($exp12, NULL, 'D'.$indexsupport + 4);//59

        // $bottom = $indexsupport + 4;

        // $sheet->getStyle('B'.$bottom.':'.$alphaall.$bottom)->getFont()->setBold( true );

        // $barissel = $indextotal;//57
        // $barissel1 = $indextotal + 1;//58
        // $sheet->mergeCells('B'.$barissel.':'.$alphasel.$barissel);
        // $sheet->mergeCells('B'.$barissel1.':C'.$indextotal + 2);//59

        // $sheet->getStyle('B'.$barissel.':B'.$barissel1)->getFont()->setBold( true );
        // // $sheet->getStyle('B'.$barissel)->getAlignment()->setHorizontal('left');

        // $i = $indextotal + 2;//59
        // $sheet->getStyle('B'.$barissel.':'.$alphasel.$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        // $i++;

        $spreadsheet->createSheet();
        $sheet1 = $spreadsheet->setActiveSheetIndex(1);

        // Rename worksheet
        $spreadsheet->getActiveSheet(1)->setTitle('Biaya dalam Rupiah');

        $sheet1->setCellValue('B1', 'PERJALANAN DINAS LUAR NEGERI_'.substr($nik_perso, 0, -1).'_'.$id_transaksi);
        $sheet1->setCellValue('B3', 'Data Karyawan');
        $sheet1->setCellValue('B4', 'Nama Karyawan =>');
        $sheet1->setCellValue('B5', 'Bagian =>');
        $sheet1->setCellValue('B6', 'No PJUM =>');
        $sheet1->setCellValue('B7', 'No PB =>');
        $sheet1->setCellValue('B9', 'Waktu Tugas');
        $sheet1->setCellValue('B10', 'Negara Tujuan =>');
        $sheet1->setCellValue('B11', 'Tanggal Keberangkatan (YYYY-MM-DD) =>');
        $sheet1->setCellValue('B12', 'Tanggal Pulang (YYYY-MM-DD) =>');
        $sheet1->setCellValue('B13', 'Berangkat dari kota =>');
        $sheet1->setCellValue('C4', substr($niknm_perso, 0, -2));
        $sheet1->setCellValue('C5', $bagian['strorgnm']);
        $sheet1->setCellValue('C6', substr($tmp_nopjum, 0, -2));
        $sheet1->setCellValue('C7', substr($tmp_nopb, 0, -2));
        $sheet1->setCellValue('C10', substr($tmp_negara, 0, -2));
        $sheet1->setCellValue('C11', $nik['tanggal_berangkat']);
        $sheet1->setCellValue('C12', $nik['tanggal_pulang']);
        $sheet1->setCellValue('C13', $kota['kota']);

        $sheet1->mergeCells('B1:E1');

        $sheet1->getStyle('B1')->getFont()->setBold(true);
        $sheet1->getStyle('B3')->getFont()->setBold(true);
        $sheet1->getStyle('B9')->getFont()->setBold(true);
        $sheet1->getStyle('D:'.$alphasel)->getAlignment()->setHorizontal('center');
        $sheet1->getStyle('D:'.$alphasel)->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet1->getStyle('B:'.$alphaall)->getAlignment()->setHorizontal('center');
        $sheet1->getStyle('B:'.$alphaall)->getAlignment()->setVertical('center');

        // $sheet1->getStyle('C4')->getAlignment()->setWrapText(true);
        // $sheet1->getStyle('C10')->getAlignment()->setWrapText(true);
        $sheet1->getColumnDimension('C')->setAutoSize(true);
        $sheet1->getColumnDimension('A')->setVisible(false);
        $spreadsheet->getActiveSheet(1)->getColumnDimension('B')->setWidth(40);
        $spreadsheet->getActiveSheet(1)->getColumnDimension('C')->setWidth(40);
        $spreadsheet->getActiveSheet(1)->getColumnDimension('F')->setWidth(15);

        for ($k = 'D'; $k <= $alphasel; $k++) {
            $spreadsheet->getActiveSheet(1)->getColumnDimension($k)->setWidth(15);
        }

        $totalbiayapesawat = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->whereIn('kategori', ['Tiket Pesawat'])->orderBy('id_biaya', 'asc')->select('biaya, id_valas, id_pjum, id_pb')->findAll();
        $totalbiayabagasi = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->whereIn('kategori', ['Bagasi Pesawat'])->orderBy('id_biaya', 'asc')->select('biaya, id_valas, id_pjum, id_pb')->findAll();
        $totalbiayaporter = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->whereIn('kategori', ['Porter Pesawat'])->orderBy('id_biaya', 'asc')->select('biaya, id_valas, id_pjum, id_pb')->findAll();
        $totalbiayahotel = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->whereIn('kategori', ['Hotel'])->orderBy('id_biaya', 'asc')->select('biaya, id_valas, id_pjum, id_pb')->findAll();
        $totalbiayamakan = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->whereIn('kategori', ['Makan dan Minum'])->orderBy('id_biaya', 'asc')->select('biaya, id_valas, id_pjum, id_pb')->findAll();
        $totalbiayatrans = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->whereIn('kategori', ['Transportasi'])->orderBy('id_biaya', 'asc')->select('biaya, id_valas, id_pjum, id_pb')->findAll();
        $totalbiayalaundry = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->whereIn('kategori', ['Laundry'])->orderBy('id_biaya', 'asc')->select('biaya, id_valas, id_pjum, id_pb')->findAll();
        $totalbiayalain = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->whereIn('kategori', ['Lain-lain'])->orderBy('id_biaya', 'asc')->select('biaya, id_valas, id_pjum, id_pb')->findAll();
        $totalbiayatot = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('id_biaya', 'asc')->select('biaya, id_valas, id_pjum, id_pb')->findAll();
        $totalbiayasel = $this->m_biaya->where('id_transaksi', $id_transaksi)->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('id_biaya', 'asc')->select('biaya, id_valas, id_pjum, id_pb')->findAll();

        $baris = 15;
        $sheet1->setCellValue('B'.(int)$baris + 1, '1. Tiket Pesawat');
        $sheet1->setCellValue('B'.(int)$baris + 2, 'Total Biaya Kategori Tiket Pesawat (PJUM + PB)');
        $sheet1->setCellValue('D'.(int)$baris + 2, 'IDR');//20

        $i = 18;
        $sum = 0;
        foreach ($totalbiayapesawat as $key => $value) {
            $id_valas = $value['id_valas'];
            $id_pjum = $value['id_pjum'];
            $id_pb = $value['id_pb'];
            $biaya = $value['biaya'];
            if ($id_pjum != null) {
                $kurs = $this->m_kurs->where('id_pjum', $id_pjum)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                if (empty($kurs)) {
                    $kurs = 1;
                    $biaya = $biaya * $kurs;
                } elseif (!empty($kurs)) {
                    foreach ($kurs as $k => $kur) {
                        if ($id_valas != 76 && $kur['id_valas']) {
                            $kurs = $kur['kurs'];
                            $biaya = $biaya * $kurs;
                        }

                        if ($id_valas == 76) {
                            $kurs = 1;
                            $biaya = $biaya * $kurs;
                        }
                    }
                }

            }

            if ($id_pb != null) {
                $kurs = $this->m_kurs->where('id_pb', $id_pb)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                if (empty($kurs)) {
                    $kurs = 1;
                    $biaya = $biaya * $kurs;
                } elseif (!empty($kurs)) {
                    foreach ($kurs as $k => $kur) {
                        if ($id_valas != 76 && $kur['id_valas']) {
                            $kurs = $kur['kurs'];
                            $biaya = $biaya * $kurs;
                        }

                        if ($id_valas == 76) {
                            $kurs = 1;
                            $biaya = $biaya * $kurs;
                        }
                    }
                }
            }

            $sum += $biaya;
            $sheet1->setCellValue('D'.$i, $sum);
        }

        $barispesawat = (int)$baris;//18
        $barishotel = (int)$baris + 1;//19
        $barishotel1 = (int)$baris + 2;//20
        $barishotel2 = (int)$baris + 3;//20
        $sheet1->mergeCells('B'.$barispesawat.':D'.$barispesawat);
        $sheet1->mergeCells('B'.$barishotel.':D'.$barishotel);
        $sheet1->mergeCells('B'.$barishotel1.':C'.$barishotel2);//21

        $sheet1->getStyle('B'.$barishotel.':B'.$barishotel1)->getFont()->setBold(true);
        $sheet1->getStyle('B'.$barishotel)->getAlignment()->setHorizontal('left');
        $sheet1->getStyle('D'.$barishotel2.':D'.$barishotel2)->getAlignment()->setHorizontal('right');

        $i = (int)$baris + 3;
        $sheet1->getStyle('B'.$barispesawat.':D'.$barishotel2)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $i++;

        $baris = 19;
        $sheet1->setCellValue('B'.(int)$baris + 1, '2. Bagasi Pesawat');
        $sheet1->setCellValue('B'.(int)$baris + 2, 'Total Biaya Kategori Bagasi Pesawat (PJUM + PB)');
        $sheet1->setCellValue('D'.(int)$baris + 2, 'IDR');//20

        $i = 22;
        $sum = 0;
        foreach ($totalbiayabagasi as $key => $value) {
            $id_valas = $value['id_valas'];
            $id_pjum = $value['id_pjum'];
            $id_pb = $value['id_pb'];
            $biaya = $value['biaya'];
            if ($id_pjum != null) {
                $kurs = $this->m_kurs->where('id_pjum', $id_pjum)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                if (empty($kurs)) {
                    $kurs = 1;
                    $biaya = $biaya * $kurs;
                } elseif (!empty($kurs)) {
                    foreach ($kurs as $k => $kur) {
                        if ($id_valas != 76 && $kur['id_valas']) {
                            $kurs = $kur['kurs'];
                            $biaya = $biaya * $kurs;
                        }

                        if ($id_valas == 76) {
                            $kurs = 1;
                            $biaya = $biaya * $kurs;
                        }
                    }
                }

            }

            if ($id_pb != null) {
                $kurs = $this->m_kurs->where('id_pb', $id_pb)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                if (empty($kurs)) {
                    $kurs = 1;
                    $biaya = $biaya * $kurs;
                } elseif (!empty($kurs)) {
                    foreach ($kurs as $k => $kur) {
                        if ($id_valas != 76 && $kur['id_valas']) {
                            $kurs = $kur['kurs'];
                            $biaya = $biaya * $kurs;
                        }

                        if ($id_valas == 76) {
                            $kurs = 1;
                            $biaya = $biaya * $kurs;
                        }
                    }
                }
            }

            $sum += $biaya;
            $sheet1->setCellValue('D'.$i, $sum);
        }

        $barispesawat = (int)$baris;//18
        $barishotel = (int)$baris + 1;//19
        $barishotel1 = (int)$baris + 2;//20
        $barishotel2 = (int)$baris + 3;//20
        $sheet1->mergeCells('B'.$barispesawat.':D'.$barispesawat);
        $sheet1->mergeCells('B'.$barishotel.':D'.$barishotel);
        $sheet1->mergeCells('B'.$barishotel1.':C'.$barishotel2);//21

        $sheet1->getStyle('B'.$barishotel.':B'.$barishotel1)->getFont()->setBold(true);
        $sheet1->getStyle('B'.$barishotel)->getAlignment()->setHorizontal('left');
        $sheet1->getStyle('D'.$barishotel2.':D'.$barishotel2)->getAlignment()->setHorizontal('right');

        $i = (int)$baris + 3;
        $sheet1->getStyle('B'.$barispesawat.':D'.$barishotel2)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $i++;

        $baris = 23;
        $sheet1->setCellValue('B'.(int)$baris + 1, '3. Porter Pesawat');
        $sheet1->setCellValue('B'.(int)$baris + 2, 'Total Biaya Kategori Porter Pesawat (PJUM + PB)');
        $sheet1->setCellValue('D'.(int)$baris + 2, 'IDR');//20

        $i = 26;
        $sum = 0;
        foreach ($totalbiayaporter as $key => $value) {
            $id_valas = $value['id_valas'];
            $id_pjum = $value['id_pjum'];
            $id_pb = $value['id_pb'];
            $biaya = $value['biaya'];
            if ($id_pjum != null) {
                $kurs = $this->m_kurs->where('id_pjum', $id_pjum)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                if (empty($kurs)) {
                    $kurs = 1;
                    $biaya = $biaya * $kurs;
                } elseif (!empty($kurs)) {
                    foreach ($kurs as $k => $kur) {
                        if ($id_valas != 76 && $kur['id_valas']) {
                            $kurs = $kur['kurs'];
                            $biaya = $biaya * $kurs;
                        }

                        if ($id_valas == 76) {
                            $kurs = 1;
                            $biaya = $biaya * $kurs;
                        }
                    }
                }

            }

            if ($id_pb != null) {
                $kurs = $this->m_kurs->where('id_pb', $id_pb)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                if (empty($kurs)) {
                    $kurs = 1;
                    $biaya = $biaya * $kurs;
                } elseif (!empty($kurs)) {
                    foreach ($kurs as $k => $kur) {
                        if ($id_valas != 76 && $kur['id_valas']) {
                            $kurs = $kur['kurs'];
                            $biaya = $biaya * $kurs;
                        }

                        if ($id_valas == 76) {
                            $kurs = 1;
                            $biaya = $biaya * $kurs;
                        }
                    }
                }
            }

            $sum += $biaya;
            $sheet1->setCellValue('D'.$i, $sum);
        }

        $barispesawat = (int)$baris;//18
        $barishotel = (int)$baris + 1;//19
        $barishotel1 = (int)$baris + 2;//20
        $barishotel2 = (int)$baris + 3;//20
        $sheet1->mergeCells('B'.$barispesawat.':D'.$barispesawat);
        $sheet1->mergeCells('B'.$barishotel.':D'.$barishotel);
        $sheet1->mergeCells('B'.$barishotel1.':C'.$barishotel2);//21

        $sheet1->getStyle('B'.$barishotel.':B'.$barishotel1)->getFont()->setBold(true);
        $sheet1->getStyle('B'.$barishotel)->getAlignment()->setHorizontal('left');
        $sheet1->getStyle('D'.$barishotel2.':D'.$barishotel2)->getAlignment()->setHorizontal('right');

        $i = (int)$baris + 3;
        $sheet1->getStyle('B'.$barispesawat.':D'.$barishotel2)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $i++;

        $baris = 27;
        $sheet1->setCellValue('B'.(int)$baris + 1, '4. Hotel');
        $sheet1->setCellValue('B'.(int)$baris + 2, 'Total Biaya Kategori Hotel (PJUM + PB)');
        $sheet1->setCellValue('D'.(int)$baris + 2, 'IDR');//20

        $i = 30;
        $sum = 0;
        foreach ($totalbiayahotel as $key => $value) {
            $id_valas = $value['id_valas'];
            $id_pjum = $value['id_pjum'];
            $id_pb = $value['id_pb'];
            $biaya = $value['biaya'];
            if ($id_pjum != null) {
                $kurs = $this->m_kurs->where('id_pjum', $id_pjum)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                if (empty($kurs)) {
                    $kurs = 1;
                    $biaya = $biaya * $kurs;
                } elseif (!empty($kurs)) {
                    foreach ($kurs as $k => $kur) {
                        if ($id_valas != 76 && $kur['id_valas']) {
                            $kurs = $kur['kurs'];
                            $biaya = $biaya * $kurs;
                        }

                        if ($id_valas == 76) {
                            $kurs = 1;
                            $biaya = $biaya * $kurs;
                        }
                    }
                }

            }

            if ($id_pb != null) {
                $kurs = $this->m_kurs->where('id_pb', $id_pb)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                if (empty($kurs)) {
                    $kurs = 1;
                    $biaya = $biaya * $kurs;
                } elseif (!empty($kurs)) {
                    foreach ($kurs as $k => $kur) {
                        if ($id_valas != 76 && $kur['id_valas']) {
                            $kurs = $kur['kurs'];
                            $biaya = $biaya * $kurs;
                        }

                        if ($id_valas == 76) {
                            $kurs = 1;
                            $biaya = $biaya * $kurs;
                        }
                    }
                }
            }

            $sum += $biaya;
            $sheet1->setCellValue('D'.$i, $sum);
        }

        $barispesawat = (int)$baris;//18
        $barishotel = (int)$baris + 1;//19
        $barishotel1 = (int)$baris + 2;//20
        $barishotel2 = (int)$baris + 3;//20
        $sheet1->mergeCells('B'.$barispesawat.':D'.$barispesawat);
        $sheet1->mergeCells('B'.$barishotel.':D'.$barishotel);
        $sheet1->mergeCells('B'.$barishotel1.':C'.$barishotel2);//21

        $sheet1->getStyle('B'.$barishotel.':B'.$barishotel1)->getFont()->setBold(true);
        $sheet1->getStyle('B'.$barishotel)->getAlignment()->setHorizontal('left');
        $sheet1->getStyle('D'.$barishotel2.':D'.$barishotel2)->getAlignment()->setHorizontal('right');

        $i = (int)$baris + 3;
        $sheet1->getStyle('B'.$barispesawat.':D'.$barishotel2)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $i++;

        $baris = 31;
        $sheet1->setCellValue('B'.(int)$baris + 1, '5. Makan dan Minum');
        $sheet1->setCellValue('B'.(int)$baris + 2, 'Total Biaya Kategori Makan dan Minum (PJUM + PB)');
        $sheet1->setCellValue('D'.(int)$baris + 2, 'IDR');//20

        $i = 34;
        $sum = 0;
        foreach ($totalbiayamakan as $key => $value) {
            $id_valas = $value['id_valas'];
            $id_pjum = $value['id_pjum'];
            $id_pb = $value['id_pb'];
            $biaya = $value['biaya'];
            if ($id_pjum != null) {
                $kurs = $this->m_kurs->where('id_pjum', $id_pjum)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                if (empty($kurs)) {
                    $kurs = 1;
                    $biaya = $biaya * $kurs;
                } elseif (!empty($kurs)) {
                    foreach ($kurs as $k => $kur) {
                        if ($id_valas != 76 && $kur['id_valas']) {
                            $kurs = $kur['kurs'];
                            $biaya = $biaya * $kurs;
                        }

                        if ($id_valas == 76) {
                            $kurs = 1;
                            $biaya = $biaya * $kurs;
                        }
                    }
                }

            }

            if ($id_pb != null) {
                $kurs = $this->m_kurs->where('id_pb', $id_pb)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                if (empty($kurs)) {
                    $kurs = 1;
                    $biaya = $biaya * $kurs;
                } elseif (!empty($kurs)) {
                    foreach ($kurs as $k => $kur) {
                        if ($id_valas != 76 && $kur['id_valas']) {
                            $kurs = $kur['kurs'];
                            $biaya = $biaya * $kurs;
                        }

                        if ($id_valas == 76) {
                            $kurs = 1;
                            $biaya = $biaya * $kurs;
                        }
                    }
                }
            }

            $sum += $biaya;
            $sheet1->setCellValue('D'.$i, $sum);
        }

        $barispesawat = (int)$baris;//18
        $barishotel = (int)$baris + 1;//19
        $barishotel1 = (int)$baris + 2;//20
        $barishotel2 = (int)$baris + 3;//20
        $sheet1->mergeCells('B'.$barispesawat.':D'.$barispesawat);
        $sheet1->mergeCells('B'.$barishotel.':D'.$barishotel);
        $sheet1->mergeCells('B'.$barishotel1.':C'.$barishotel2);//21

        $sheet1->getStyle('B'.$barishotel.':B'.$barishotel1)->getFont()->setBold(true);
        $sheet1->getStyle('B'.$barishotel)->getAlignment()->setHorizontal('left');
        $sheet1->getStyle('D'.$barishotel2.':D'.$barishotel2)->getAlignment()->setHorizontal('right');

        $i = (int)$baris + 3;
        $sheet1->getStyle('B'.$barispesawat.':D'.$barishotel2)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $i++;

        $baris = 35;
        $sheet1->setCellValue('B'.(int)$baris + 1, '6. Transportasi');
        $sheet1->setCellValue('B'.(int)$baris + 2, 'Total Biaya Kategori Transportasi (PJUM + PB)');
        $sheet1->setCellValue('D'.(int)$baris + 2, 'IDR');//20

        $i = 38;
        $sum = 0;
        foreach ($totalbiayatrans as $key => $value) {
            $id_valas = $value['id_valas'];
            $id_pjum = $value['id_pjum'];
            $id_pb = $value['id_pb'];
            $biaya = $value['biaya'];
            if ($id_pjum != null) {
                $kurs = $this->m_kurs->where('id_pjum', $id_pjum)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                if (empty($kurs)) {
                    $kurs = 1;
                    $biaya = $biaya * $kurs;
                } elseif (!empty($kurs)) {
                    foreach ($kurs as $k => $kur) {
                        if ($id_valas != 76 && $kur['id_valas']) {
                            $kurs = $kur['kurs'];
                            $biaya = $biaya * $kurs;
                        }

                        if ($id_valas == 76) {
                            $kurs = 1;
                            $biaya = $biaya * $kurs;
                        }
                    }
                }

            }

            if ($id_pb != null) {
                $kurs = $this->m_kurs->where('id_pb', $id_pb)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                if (empty($kurs)) {
                    $kurs = 1;
                    $biaya = $biaya * $kurs;
                } elseif (!empty($kurs)) {
                    foreach ($kurs as $k => $kur) {
                        if ($id_valas != 76 && $kur['id_valas']) {
                            $kurs = $kur['kurs'];
                            $biaya = $biaya * $kurs;
                        }

                        if ($id_valas == 76) {
                            $kurs = 1;
                            $biaya = $biaya * $kurs;
                        }
                    }
                }
            }

            $sum += $biaya;
            $sheet1->setCellValue('D'.$i, $sum);
        }

        $barispesawat = (int)$baris;//18
        $barishotel = (int)$baris + 1;//19
        $barishotel1 = (int)$baris + 2;//20
        $barishotel2 = (int)$baris + 3;//20
        $sheet1->mergeCells('B'.$barispesawat.':D'.$barispesawat);
        $sheet1->mergeCells('B'.$barishotel.':D'.$barishotel);
        $sheet1->mergeCells('B'.$barishotel1.':C'.$barishotel2);//21

        $sheet1->getStyle('B'.$barishotel.':B'.$barishotel1)->getFont()->setBold(true);
        $sheet1->getStyle('B'.$barishotel)->getAlignment()->setHorizontal('left');
        $sheet1->getStyle('D'.$barishotel2.':D'.$barishotel2)->getAlignment()->setHorizontal('right');

        $i = (int)$baris + 3;
        $sheet1->getStyle('B'.$barispesawat.':D'.$barishotel2)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $i++;

        $baris = 39;
        $sheet1->setCellValue('B'.(int)$baris + 1, '7. Laundry');
        $sheet1->setCellValue('B'.(int)$baris + 2, 'Total Biaya Kategori Laundry (PJUM + PB)');
        $sheet1->setCellValue('D'.(int)$baris + 2, 'IDR');//20

        $i = 42;
        $sum = 0;
        foreach ($totalbiayalaundry as $key => $value) {
            $id_valas = $value['id_valas'];
            $id_pjum = $value['id_pjum'];
            $id_pb = $value['id_pb'];
            $biaya = $value['biaya'];
            if ($id_pjum != null) {
                $kurs = $this->m_kurs->where('id_pjum', $id_pjum)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                if (empty($kurs)) {
                    $kurs = 1;
                    $biaya = $biaya * $kurs;
                } elseif (!empty($kurs)) {
                    foreach ($kurs as $k => $kur) {
                        if ($id_valas != 76 && $kur['id_valas']) {
                            $kurs = $kur['kurs'];
                            $biaya = $biaya * $kurs;
                        }

                        if ($id_valas == 76) {
                            $kurs = 1;
                            $biaya = $biaya * $kurs;
                        }
                    }
                }

            }

            if ($id_pb != null) {
                $kurs = $this->m_kurs->where('id_pb', $id_pb)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                if (empty($kurs)) {
                    $kurs = 1;
                    $biaya = $biaya * $kurs;
                } elseif (!empty($kurs)) {
                    foreach ($kurs as $k => $kur) {
                        if ($id_valas != 76 && $kur['id_valas']) {
                            $kurs = $kur['kurs'];
                            $biaya = $biaya * $kurs;
                        }

                        if ($id_valas == 76) {
                            $kurs = 1;
                            $biaya = $biaya * $kurs;
                        }
                    }
                }
            }

            $sum += $biaya;
            $sheet1->setCellValue('D'.$i, $sum);
        }

        $barispesawat = (int)$baris;//18
        $barishotel = (int)$baris + 1;//19
        $barishotel1 = (int)$baris + 2;//20
        $barishotel2 = (int)$baris + 3;//20
        $sheet1->mergeCells('B'.$barispesawat.':D'.$barispesawat);
        $sheet1->mergeCells('B'.$barishotel.':D'.$barishotel);
        $sheet1->mergeCells('B'.$barishotel1.':C'.$barishotel2);//21

        $sheet1->getStyle('B'.$barishotel.':B'.$barishotel1)->getFont()->setBold(true);
        $sheet1->getStyle('B'.$barishotel)->getAlignment()->setHorizontal('left');
        $sheet1->getStyle('D'.$barishotel2.':D'.$barishotel2)->getAlignment()->setHorizontal('right');

        $i = (int)$baris + 3;
        $sheet1->getStyle('B'.$barispesawat.':D'.$barishotel2)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $i++;

        $baris = 43;
        $sheet1->setCellValue('B'.(int)$baris + 1, '8. Lain-lain');
        $sheet1->setCellValue('B'.(int)$baris + 2, 'Total Biaya Kategori Lain-lain (PJUM + PB)');
        $sheet1->setCellValue('D'.(int)$baris + 2, 'IDR');//20

        $i = 46;
        $sum = 0;
        foreach ($totalbiayalain as $key => $value) {
            $id_valas = $value['id_valas'];
            $id_pjum = $value['id_pjum'];
            $id_pb = $value['id_pb'];
            $biaya = $value['biaya'];
            if ($id_pjum != null) {
                $kurs = $this->m_kurs->where('id_pjum', $id_pjum)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                if (empty($kurs)) {
                    $kurs = 1;
                    $biaya = $biaya * $kurs;
                } elseif (!empty($kurs)) {
                    foreach ($kurs as $k => $kur) {
                        if ($id_valas != 76 && $kur['id_valas']) {
                            $kurs = $kur['kurs'];
                            $biaya = $biaya * $kurs;
                        }

                        if ($id_valas == 76) {
                            $kurs = 1;
                            $biaya = $biaya * $kurs;
                        }
                    }
                }

            }

            if ($id_pb != null) {
                $kurs = $this->m_kurs->where('id_pb', $id_pb)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                if (empty($kurs)) {
                    $kurs = 1;
                    $biaya = $biaya * $kurs;
                } elseif (!empty($kurs)) {
                    foreach ($kurs as $k => $kur) {
                        if ($id_valas != 76 && $kur['id_valas']) {
                            $kurs = $kur['kurs'];
                            $biaya = $biaya * $kurs;
                        }

                        if ($id_valas == 76) {
                            $kurs = 1;
                            $biaya = $biaya * $kurs;
                        }
                    }
                }
            }

            $sum += $biaya;
            $sheet1->setCellValue('D'.$i, $sum);
        }

        $barispesawat = (int)$baris;//18
        $barishotel = (int)$baris + 1;//19
        $barishotel1 = (int)$baris + 2;//20
        $barishotel2 = (int)$baris + 3;//20
        $sheet1->mergeCells('B'.$barispesawat.':D'.$barispesawat);
        $sheet1->mergeCells('B'.$barishotel.':D'.$barishotel);
        $sheet1->mergeCells('B'.$barishotel1.':C'.$barishotel2);//21

        $sheet1->getStyle('B'.$barishotel.':B'.$barishotel1)->getFont()->setBold(true);
        $sheet1->getStyle('B'.$barishotel)->getAlignment()->setHorizontal('left');
        $sheet1->getStyle('D'.$barishotel2.':D'.$barishotel2)->getAlignment()->setHorizontal('right');

        $i = (int)$baris + 3;
        $sheet1->getStyle('B'.$barispesawat.':D'.$barishotel2)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $i++;

        $baris = 47;
        $sheet1->setCellValue('B'.(int)$baris + 1, 'Total Biaya Perjalanan Dinas Luar Negeri');
        $sheet1->setCellValue('B'.(int)$baris + 2, 'Total Biaya (PJUM + PB)');
        $sheet1->setCellValue('D'.(int)$baris + 2, 'IDR');//20

        $i = 50;
        $sum = 0;
        foreach ($totalbiayatot as $key => $value) {
            $id_valas = $value['id_valas'];
            $id_pjum = $value['id_pjum'];
            $id_pb = $value['id_pb'];
            $biaya = $value['biaya'];
            if ($id_pjum != null) {
                $kurs = $this->m_kurs->where('id_pjum', $id_pjum)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                if (empty($kurs)) {
                    $kurs = 1;
                    $biaya = $biaya * $kurs;
                } elseif (!empty($kurs)) {
                    foreach ($kurs as $k => $kur) {
                        if ($id_valas != 76 && $kur['id_valas']) {
                            $kurs = $kur['kurs'];
                            $biaya = $biaya * $kurs;
                        }

                        if ($id_valas == 76) {
                            $kurs = 1;
                            $biaya = $biaya * $kurs;
                        }
                    }
                }

            }

            if ($id_pb != null) {
                $kurs = $this->m_kurs->where('id_pb', $id_pb)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                if (empty($kurs)) {
                    $kurs = 1;
                    $biaya = $biaya * $kurs;
                } elseif (!empty($kurs)) {
                    foreach ($kurs as $k => $kur) {
                        if ($id_valas != 76 && $kur['id_valas']) {
                            $kurs = $kur['kurs'];
                            $biaya = $biaya * $kurs;
                        }

                        if ($id_valas == 76) {
                            $kurs = 1;
                            $biaya = $biaya * $kurs;
                        }
                    }
                }
            }

            $sum += $biaya;
            $sheet1->setCellValue('D'.$i, $sum);
        }

        $barispesawat = (int)$baris;//18
        $barishotel = (int)$baris + 1;//19
        $barishotel1 = (int)$baris + 2;//20
        $barishotel2 = (int)$baris + 3;//20
        $sheet1->mergeCells('B'.$barispesawat.':D'.$barispesawat);
        $sheet1->mergeCells('B'.$barishotel.':D'.$barishotel);
        $sheet1->mergeCells('B'.$barishotel1.':C'.$barishotel2);//21

        $sheet1->getStyle('B'.$barishotel.':B'.$barishotel1)->getFont()->setBold(true);
        $sheet1->getStyle('D50')->getFont()->setBold(true);
        $sheet1->getStyle('B'.$barishotel)->getAlignment()->setHorizontal('left');
        $sheet1->getStyle('D'.$barishotel2.':D'.$barishotel2)->getAlignment()->setHorizontal('right');

        $i = (int)$baris + 3;
        $sheet1->getStyle('B'.$barispesawat.':D'.$barishotel2)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $i++;

        // Biaya Support

        if (empty($biayasupport)) {

        } else {
            $baris = 53;//49
            // $barissup = $baristotal + 8; //
            $indexsupport = $countsupport + 57; //39
            // $indextotal = $indexsupport + 2;
            $indexkat = $baris + 2;

            $sheet1->setCellValue('B'.$baris, 'Biaya Support Perjalanan Dinas Luar Negeri');
            $sheet1->setCellValue('B'.$indexkat, 'Tanggal');
            $sheet1->setCellValue('C'.$indexkat, 'Kategori');
            $sheet1->setCellValue('D'.$indexkat, 'Jumlah Personil');
            $sheet1->setCellValue('E'.$indexkat, 'Biaya');
            $sheet1->setCellValue('B'.$indexsupport, 'Total Biaya Support');

            $sheet1->fromArray($exp10, null, 'E'.$indexsupport);

            $sheet1->mergeCells('B'.$baris.':'.$alphasup.$baris + 1);
            $sheet1->mergeCells('B'.$indexkat.':B'.$indexkat + 1);
            $sheet1->mergeCells('C'.$indexkat.':C'.$indexkat + 1);
            $sheet1->mergeCells('D'.$indexkat.':D'.$indexkat + 1);
            $sheet1->mergeCells('B'.$indexsupport.':D'.$indexsupport);

            $sheet1->getStyle('B'.$baris)->getFont()->setBold(true);
            $sheet1->getStyle('B'.$indexsupport.':'.$alphasup.$indexsupport)->getFont()->setBold(true);

            $spreadsheet->getActiveSheet(1)->getColumnDimension('D')->setAutoSize(true);

            for ($i = $baris; $i <= $indexsupport; $i++) {
                $sheet1->getStyle('B'.$baris.':'.$alphasup.$indexsupport)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet1->getStyle('D'.$baris.':D'.$i)->getNumberFormat()->setFormatCode('#');
                $i++;
            }

            foreach ($biayasupport as $key => $value) {
                $i = $baris + 3;
                $column = $value['kolom'];
                $row = 49 + (int)$value['baris'];
                $sheet1->setCellValueByColumnAndRow($column, $i, $value['kode_valas']);
                $i++;
                for ($j = $baris + 4; $j <= $indexsupport; $j++) {
                    $bia = $value['biaya'];
                    if ($bia == 0) {
                        $bia = null;
                    }
                    $sheet1->setCellValueByColumnAndRow(5, $row, $bia);
                    $sheet1->getStyle('E'.$row.':E'.$indexsupport)->getAlignment()->setHorizontal('right');
                }
            }

            $row = $baris + 4;
            foreach ($kategorisupport as $key => $value) {
                $sheet1->setCellValue('B'.$row, $value['tanggal']);
                $sheet1->setCellValue('C'.$row, $value['kategori']);
                $sheet1->setCellValue('D'.$row, $value['jumlah_personil']);
                $row++;
            }

            $baris = $indexsupport + 2;
            $sheet1->setCellValue('B'.(int)$baris + 1, 'Total Biaya Perjalanan Dinas Luar Negeri');
            $sheet1->setCellValue('B'.(int)$baris + 2, 'Total Biaya (PJUM + PB + Support)');
            $sheet1->setCellValue('D'.(int)$baris + 2, 'IDR');//20

            $i = $indexsupport + 5;
            $sum = 0;
            foreach ($totalbiayasel as $key => $value) {
                $id_valas = $value['id_valas'];
                $id_pjum = $value['id_pjum'];
                $id_pb = $value['id_pb'];
                $biaya = $value['biaya'];
                if ($id_pjum != null) {
                    $kurs = $this->m_kurs->where('id_pjum', $id_pjum)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                    if (empty($kurs)) {
                        $kurs = 1;
                        $biaya = $biaya * $kurs;
                    } elseif (!empty($kurs)) {
                        foreach ($kurs as $k => $kur) {
                            if ($id_valas != 76 && $kur['id_valas']) {
                                $kurs = $kur['kurs'];
                                $biaya = $biaya * $kurs;
                            }

                            if ($id_valas == 76) {
                                $kurs = 1;
                                $biaya = $biaya * $kurs;
                            }
                        }
                    }

                }

                if ($id_pb != null) {
                    $kurs = $this->m_kurs->where('id_pb', $id_pb)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                    if (empty($kurs)) {
                        $kurs = 1;
                        $biaya = $biaya * $kurs;
                    } elseif (!empty($kurs)) {
                        foreach ($kurs as $k => $kur) {
                            if ($id_valas != 76 && $kur['id_valas']) {
                                $kurs = $kur['kurs'];
                                $biaya = $biaya * $kurs;
                            }

                            if ($id_valas == 76) {
                                $kurs = 1;
                                $biaya = $biaya * $kurs;
                            }
                        }
                    }
                }

                $sum += $biaya;
                $sheet1->setCellValue('D'.$i, $sum);
            }

            $barispesawat = (int)$baris;//18
            $barishotel = (int)$baris + 1;//19
            $barishotel1 = (int)$baris + 2;//20
            $barishotel2 = (int)$baris + 3;//20
            $sheet1->mergeCells('B'.$barispesawat.':D'.$barispesawat);
            $sheet1->mergeCells('B'.$barishotel.':D'.$barishotel);
            $sheet1->mergeCells('B'.$barishotel1.':C'.$barishotel2);//21

            $sheet1->getStyle('B'.$barishotel.':B'.$barishotel1)->getFont()->setBold(true);
            $sheet1->getStyle('D'.$indexsupport + 5)->getFont()->setBold(true);
            $sheet1->getStyle('B'.$barishotel)->getAlignment()->setHorizontal('left');
            $sheet1->getStyle('D'.$barishotel2.':D'.$barishotel2)->getAlignment()->setHorizontal('right');

            $i = (int)$baris + 3;
            $sheet1->getStyle('B'.$barispesawat.':D'.$barishotel2)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $i++;
        }

        // $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xls($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=Report Biaya Perjalanan Dinas Luar Negeri/'.substr($nik_perso, 0, -1).'/'.$id_transaksi.'.xls');
        $writer->save("php://output");
        // $writer->save('Report Biaya '.substr($nik_perso, 0, -1).'.xls');
        // return redirect()->to('dashboard/'.$id_transaksi);
    }
}
