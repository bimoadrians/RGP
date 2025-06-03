<?php
namespace App\Controllers\Admin;

date_default_timezone_set("Asia/Jakarta");

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use CodeIgniter\HTTP\IncomingRequest;
use App\Models\TransaksiModel;
use App\Models\TransModel;
use App\Models\TiketModel;
use App\Models\AkomodasiModel;
use App\Models\TransportasiModel;
use App\Models\TransportasiJemputModel;
use App\Models\Detail_Pengguna_Model;
use App\Models\PenggunaModel;
use App\Models\PengemudiModel;
use App\Models\MobilModel;
use App\Models\BagianModel;
use App\Models\NegaraModel;
use App\Models\KotaModel;
use App\Models\PoolModel;
use App\Models\VendorModel;
use App\Models\PemberhentianModel;
use App\Models\HotelModel;
use App\Models\DetailHotelModel;
use App\Models\MessModel;
use App\Models\TanggalMessModel;
use App\Models\PersonilMessModel;
use App\Models\JenisKendaraanModel;
use App\Models\SetDriverModel;
use App\Models\ETiketModel;
use App\Models\EAkomodasiModel;
use App\Models\ETransportasiModel;
use App\Models\EmailDelegasiModel;

class Transaksi extends BaseController
{
    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $session = \Config\Services::session();

        $this->m_trans = new TransModel();
        $this->m_tiket = new TiketModel();
        $this->m_akomodasi = new AkomodasiModel();
        $this->m_transportasi = new TransportasiModel();
        $this->m_transportasi_jemput = new TransportasiJemputModel();
        $this->m_detail_pengguna = new Detail_Pengguna_Model();
        $this->m_pengguna = new PenggunaModel();
        $this->m_pengemudi = new PengemudiModel();
        $this->m_mobil = new MobilModel();
        $this->m_bagian = new BagianModel();
        $this->m_negara = new NegaraModel();
        $this->m_kota = new KotaModel();
        $this->m_pool = new PoolModel();
        $this->m_vendor = new VendorModel();
        $this->m_pemberhentian = new PemberhentianModel();
        $this->m_hotel = new HotelModel();
        $this->m_detail_hotel = new DetailHotelModel();
        $this->m_mess = new MessModel();
        $this->m_tanggal_mess = new TanggalMessModel();
        $this->m_personil_mess = new PersonilMessModel();
        $this->m_jenis_kendaraan = new JenisKendaraanModel();
        $this->m_set_driver = new SetDriverModel();
        $this->m_e_tiket = new ETiketModel();
        $this->m_e_akomodasi = new EAkomodasiModel();
        $this->m_e_transportasi = new ETransportasiModel();
        $this->m_email_delegasi = new EmailDelegasiModel();

        $pager = \Config\Services::pager();
        helper('global_fungsi_helper');
        helper('url');
        helper(["html"]);

        require 'vendor/autoload.php';
        require 'vendor/phpmailer/phpmailer/src/Exception.php';
        require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
        require 'vendor/phpmailer/phpmailer/src/SMTP.php';
    }

    public function trans()
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

        if($this->request->getVar('aksi') == 'permintaan_batal_tiket' && $this->request->getVar('id_trans') && $this->request->getVar('id_tiket')) {
            $tiket_id = $this->m_tiket->tiket_id($this->request->getVar('id_tiket'));
            if($tiket_id['id_tiket']) {//memastikan bahwa ada data
                session()->setFlashdata('success', ('Masukkan alasan batal'));
                return redirect()->to("permintaan_batal_tiket/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_tiket'));
            }
        }

        if($this->request->getVar('aksi') == 'permintaan_batal_akomodasi' && $this->request->getVar('id_trans') && $this->request->getVar('id_akomodasi')) {
            $akomodasi_id = $this->m_akomodasi->akomodasi_id($this->request->getVar('id_akomodasi'));
            if($akomodasi_id['id_akomodasi']) {//memastikan bahwa ada data
                session()->setFlashdata('success', ('Masukkan alasan batal'));
                return redirect()->to("permintaan_batal_akomodasi/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_akomodasi'));
            }
        }

        if($this->request->getVar('aksi') == 'permintaan_batal_transportasi_antar' && $this->request->getVar('id_trans') && $this->request->getVar('id_transportasi')) {
            $transportasi_id = $this->m_transportasi->transportasi_id($this->request->getVar('id_transportasi'));
            if($transportasi_id['id_transportasi']) {//memastikan bahwa ada data
                session()->setFlashdata('success', ('Masukkan alasan batal'));
                return redirect()->to("permintaan_batal_transport_antar/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_transportasi'));
            }
        }

        if($this->request->getVar('aksi') == 'permintaan_batal_transportasi_jemput' && $this->request->getVar('id_trans') && $this->request->getVar('id_transportasi_jemput')) {
            $transportasi_jemput_id = $this->m_transportasi_jemput->transportasi_jemput_id($this->request->getVar('id_transportasi_jemput'));
            if($transportasi_jemput_id['id_transportasi_jemput']) {//memastikan bahwa ada data
                session()->setFlashdata('success', ('Masukkan alasan batal'));
                return redirect()->to("permintaan_batal_transportasi/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_transportasi_jemput'));
            }
        }

        $id_bagian = session()->get('id_bagian');
        $id_detail_pengguna = session()->get('id_detail_pengguna');

        $trans = $this->m_trans->where('id_detail_pengguna', $id_detail_pengguna)->where('tanggal_jam_tiket >', $timestamp)->orwhere('id_detail_pengguna', $id_detail_pengguna)->where('tanggal_jam_keluar >', $timestamp)->orwhere('id_detail_pengguna', $id_detail_pengguna)->where('transportasi.tanggal_mobil >=', $date)->orwhere('id_detail_pengguna', $id_detail_pengguna)->where('transportasi_jemput.tanggal_mobil >=', $date)->join('tiket', 'tiket.id_trans = trans.id_trans', 'left')->join('akomodasi', 'akomodasi.id_trans = trans.id_trans', 'left')->join('transportasi', 'transportasi.id_trans = trans.id_trans', 'left')->join('transportasi_jemput', 'transportasi_jemput.id_trans = trans.id_trans', 'left')->select('trans.id_trans, pemesanan, tanggal_jam_tiket, tanggal_jam_keluar, transportasi.tanggal_mobil, transportasi_jemput.tanggal_mobil, trans.created_at')->orderBy('trans.created_at', 'desc')->orderBy('pemesanan', 'asc')->findAll();
        
        $tiket = $this->m_tiket->where('tiket.tanggal_jam_tiket >', $timestamp)->join('trans', 'trans.id_trans = tiket.id_trans', 'left')->join('vendor', 'vendor.id_vendor = tiket.id_vendor', 'left')->join('pemberhentian', 'pemberhentian.id_pemberhentian = tiket.id_pemberhentian', 'left')->join('pool', 'pool.id_pool = tiket.id_pool', 'left')->select('id_tiket, tiket.id_trans, id_keberangkatan, nama_vendor, nama_pemberhentian, nama_pool, pic, atas_nama, jabatan, jumlah_tiket, pembayaran, tiket.tanggal_jam_tiket, dari_tiket, tujuan_tiket, harga_tiket, keterangan_tiket, status_tiket, batal_tiket, tiket.created_at')->findAll();
        
        $akomodasi = $this->m_akomodasi->where('akomodasi.tanggal_jam_keluar >', $timestamp)->join('trans', 'trans.id_trans = akomodasi.id_trans', 'left')->join('hotel', 'hotel.id_hotel = akomodasi.id_hotel', 'left')->join('detail_hotel', 'detail_hotel.id_detail_hotel = akomodasi.id_detail_hotel', 'left')->join('pool', 'pool.id_pool = akomodasi.id_pool', 'left')->join('kota', 'kota.id_kota = akomodasi.id_kota', 'left')->select('akomodasi.id_trans, id_akomodasi, nama_hotel, jenis_kamar, nama_pool, nama_kota, pic, atas_nama, jabatan, jumlah_kamar, pembayaran, tanggal_jam_masuk, akomodasi.tanggal_jam_keluar, keterangan_akomodasi, harga_akomodasi, status_akomodasi, batal_akomodasi, status_mess')->findAll();
        
        $transportasi = $this->m_transportasi->where('transportasi.tanggal_mobil >=', $date)->join('trans', 'trans.id_trans = transportasi.id_trans', 'left')->join('pool', 'pool.id_pool = transportasi.id_pool', 'left')->join('transportasi_jemput', 'transportasi_jemput.id_transportasi = transportasi.id_transportasi', 'left')->select('transportasi.id_transportasi, transportasi.id_trans, pic, nama_pool, transportasi.jemput, transportasi.jenis_kendaraan, transportasi.tenaga_angkut, transportasi.dalkot_lukot, transportasi.menginap, transportasi.kapasitas, transportasi.jumlah_mobil, transportasi.tanggal_mobil, transportasi.tujuan_mobil, transportasi.siap_di, transportasi.jam_siap, transportasi.atas_nama, transportasi.jabatan, transportasi.keterangan_mobil, transportasi.status_mobil, transportasi.batal_transportasi')->findAll();
        
        $transportasi_jemput = $this->m_transportasi_jemput->where('transportasi_jemput.jemput=', 1)->where('transportasi_jemput.tanggal_mobil >=', $date)->join('trans', 'trans.id_trans = transportasi_jemput.id_trans', 'left')->join('transportasi', 'transportasi.id_transportasi = transportasi_jemput.id_transportasi', 'left')->join('pool', 'pool.id_pool = transportasi_jemput.id_pool', 'left')->select('transportasi_jemput.id_transportasi_jemput, transportasi_jemput.id_transportasi, transportasi_jemput.id_trans, transportasi_jemput.jemput, nama_pool, transportasi_jemput.atas_nama, transportasi_jemput.jabatan, transportasi_jemput.jenis_kendaraan, transportasi_jemput.tenaga_angkut, transportasi_jemput.dalkot_lukot, transportasi_jemput.menginap, transportasi_jemput.kapasitas, transportasi_jemput.jumlah_mobil, transportasi_jemput.tanggal_mobil, transportasi_jemput.tujuan_mobil, transportasi_jemput.siap_di, transportasi_jemput.jam_siap, transportasi_jemput.keterangan_mobil, transportasi_jemput.status_mobil, transportasi_jemput.batal_transportasi_jemput')->findAll();
        
        $transportasi_antar_jemput = $this->m_transportasi_jemput->where('transportasi_jemput.jemput=', 2)->where('transportasi_jemput.tanggal_mobil >=', $date)->join('trans', 'trans.id_trans = transportasi_jemput.id_trans', 'left')->join('transportasi', 'transportasi.id_transportasi = transportasi_jemput.id_transportasi', 'left')->join('pool', 'pool.id_pool = transportasi_jemput.id_pool', 'left')->select('transportasi_jemput.id_transportasi_jemput, transportasi_jemput.id_transportasi, transportasi_jemput.id_trans, transportasi_jemput.jemput, nama_pool, transportasi_jemput.atas_nama, transportasi_jemput.jabatan, transportasi_jemput.jenis_kendaraan, transportasi_jemput.tenaga_angkut, transportasi_jemput.dalkot_lukot, transportasi_jemput.menginap, transportasi_jemput.kapasitas, transportasi_jemput.jumlah_mobil, transportasi_jemput.tanggal_mobil, transportasi_jemput.tujuan_mobil, transportasi_jemput.siap_di, transportasi_jemput.jam_siap, transportasi_jemput.keterangan_mobil, transportasi_jemput.status_mobil, transportasi_jemput.batal_transportasi_jemput')->findAll();

        $pemberhentian = $this->m_pemberhentian->select('id_pemberhentian, nama_pemberhentian')->findAll();
        
        $data = [
            'trans' => $trans,
            'tiket' => $tiket,
            'akomodasi' => $akomodasi,
            'transportasi' => $transportasi,
            'transportasi_jemput' => $transportasi_jemput,
            'transportasi_antar_jemput' => $transportasi_antar_jemput,

            'pemberhentian' => $pemberhentian,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_user', $data);
        echo view('transaksi/v_trans', $data);
        echo view('ui/v_footer', $data);
        // d(session()->get(''));
    }

    public function trans_add()
    {
        $data = [];

        // ucwords($nama);//capitalize each word

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

        $mail = new PHPMailer(true);
        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view

            //Trans
            $id_trans = $this->m_trans->select('id_trans')->orderBy('id_trans', 'desc')->first();
            if (empty($id_trans)) {
                $id_trans1 = 1;
                $id_trans2 = 1;
                $id_trans3 = 1;
                $id_trans4 = 1;
                $id_trans_mess = 1;
            } else {
                $id_trans1 = (int)$id_trans['id_trans'] + 1;
                $id_trans2 = (int)$id_trans['id_trans'] + 1;
                $id_trans3 = (int)$id_trans['id_trans'] + 1;
                $id_trans4 = (int)$id_trans['id_trans'] + 1;
                $id_trans_mess = (int)$id_trans['id_trans'] + 1;
            }

            if(!empty($_POST['pemesanan'])) {                
                for($a = 0; $a < count($_POST['pemesanan']); $a++) {
                    if($_POST['pemesanan'][$a] == 'pilih') {
                        session()->setFlashdata('warning', ['Silahkan pilih kategori pemesanan terlebih dahulu']);
                        return redirect()->to('trans_add');
                    }
                    //Tiket
                    else if ($_POST['pemesanan'][$a] == '0') {
                        //Tiket
                        $nama_tiket = $_POST['tiket'];
                        $nama_pool = $_POST['gs_tiket'];
                        $keberangkatan = $_POST['keberangkatan'];
                        $pemberhentian = $_POST['pemberhentian'];

                        if ($_POST['pilihan_tiket'][$a] == 0) {
                            session()->setFlashdata('warning', ['Pilih armada transportasi terlebih dahulu']);
                            return redirect()->to('trans_add');
                        } else if ($_POST['pilihan_tiket'][$a] == "Travel") {
                            $id_keberangkatan = null;
                            $id_pemberhentian = null;
                            $dari_tiket = $_POST['keberangkatan'][$a];
                            $tujuan_tiket = $_POST['pemberhentian'][$a];
                        } else {
                            $nama_keberangkatan = substr($keberangkatan[$a], 0, strpos($keberangkatan[$a], " - "));
                            $dari_tiket = substr($keberangkatan[$a], strpos($keberangkatan[$a], " - ")+3);
                            $id_berangkat = $this->m_pemberhentian->where('nama_pemberhentian', $nama_keberangkatan)->select('id_pemberhentian')->findAll();
                            $id_keberangkatan = $id_berangkat[0]['id_pemberhentian'];
                            
                            $nama_pemberhentian = substr($pemberhentian[$a], 0, strpos($pemberhentian[$a], " - "));
                            $tujuan_tiket = substr($pemberhentian[$a], strpos($pemberhentian[$a], " - ")+3);
                            $id_berhenti = $this->m_pemberhentian->where('nama_pemberhentian', $nama_pemberhentian)->select('id_pemberhentian')->findAll();
                            $id_pemberhentian = $id_berhenti[0]['id_pemberhentian'];
                        }

                        if ($_POST['keberangkatan'][$a] == $_POST['pemberhentian'][$a]) {
                            session()->setFlashdata('warning', ['Keberangkatan dan Pemberhentian Tiket tidak boleh sama']);
                            return redirect()->to('trans_add');
                        }

                        if ($_POST['jumlah_tiket'][$a] == 0) {
                            session()->setFlashdata('warning', ['Jumlah tiket tidak boleh 0']);
                            return redirect()->to("trans_add");
                        }

                        $biaya = $_POST['harga_tiket'][$a];
                        $comma = ',';
                        $number = preg_replace('/[^0-9\\-]+/','', $biaya);
                        if ($number == null) {
                            $string = 0;
                        } else {
                            if( strpos($biaya, $comma) !== false ) {
                                $string = $number/100;
                            } else {
                                $string = $number;
                            }
                        }
                        
                        $vendor = $this->m_vendor->where('nama_vendor', $nama_tiket[$a])->select('id_vendor')->findAll();
                        if (empty($vendor)) {
                            session()->setFlashdata('warning', ['Tidak ada data Tiket']);
                            return redirect()->to("trans_add");
                        }
                        $pool_tiket = $this->m_pool->where('nama_pool', $nama_pool[$a])->select('id_pool')->findAll();

                        if ($_POST['tamu'][$a] == 'Karyawan Konimex') {
                            // foreach ($nama_select_h as $ns_h => $nase_h) {
                            //     $nama_sel_h[] = substr($nase_h, 0, strpos($nase_h, " - "));d($nase_h);
                                
                            //     $jabatan_sel_h_arr = substr($nase_h, strpos($nase_h, " - ")+3);
                            //     $jabatan_sel_h[] = substr($jabatan_sel_h_arr, 0, strpos($jabatan_sel_h_arr, " - "));

                            //     $jenis_kelamin_h_arr = substr($nase_h, strpos($nase_h, " - ")+3);
                            //     $jenis_kelamin_h[] = substr($jenis_kelamin_h_arr, strpos($jenis_kelamin_h_arr, " - ")+3);

                            //     $jabatan = implode(", ", $jabatan_sel_h);
                            //     $jenis_kelamin = implode(", ", $jenis_kelamin_h);
                            // }
                            $count = $_POST["count"][$a];
                            $nama_select_h = $_POST["nama_select".$count];
                            
                            $atas_namaa = implode(" - ", $nama_select_h);

                            $atas_namas = explode(" - ", $atas_namaa);

                            $jumlah = count($atas_namas);

                            for ($i=0; $i<$jumlah; $i++){
                                if ($i % 3 == 0){
                                    $atas_namat[$i] = $atas_namas[$i];
                                    $jabatant[$i] = $atas_namas[$i+1];
                                    $jenis_kelamint[$i] = $atas_namas[$i+2];
                                }
                            }
                            $atas_nama = implode(", ", $atas_namat);
                            $jabatan = implode(", ", $jabatant);
                            $jenis_kelamin = implode(", ", $jenis_kelamint);

                            $pic = $atas_nama;
                            $email_info = $atas_nama;
                            $email_eval = $atas_nama;

                            $pembayaran = $_POST['pembayaran'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                        } else {
                            $atas_nama = $_POST['nama_inputan'][$a];
                            $jabatan = $_POST['jabatan_inputan'][$a];
                            $jenis_kelamin = null;
                            $pic = session()->get('nama_pengguna');
                            $email_info = session()->get('nama_pengguna');
                            $email_eval = session()->get('nama_pengguna');

                            $pembayaran = $_POST['pembayaran_inputan'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                            $atas_nama = ucwords($atas_nama);
                            $jabatan = ucwords($jabatan);
                        }
                        if (empty($atas_nama)) {
                            session()->setFlashdata('warning', ['Nama harus diisi']);
                            return redirect()->to('trans_add');
                        }
                        if (empty($jabatan)) {
                            session()->setFlashdata('warning', ['Jabatan harus diisi']);
                            return redirect()->to('trans_add');
                        }

                        $keterangan_tiket = $_POST['keterangan_tiket'][$a];
                        if(empty($keterangan_tiket)){
                            $keterangan_tiket = null;
                        }

                        if(!empty($atas_nama) && !empty($jabatan) && !empty($pic) && !empty($email_eval) && !empty($_POST['jumlah_tiket'][$a]) && !empty($_POST['tanggal_jam_tiket'][$a])) {
                            $trans_tik[] = array(
                                'id_trans' => $id_trans1++,
                                'id_detail_pengguna' => session()->get('id_detail_pengguna'),
                                'id_bagian' => session()->get('id_bagian'),
                                'pemesanan' => $_POST['pemesanan'][$a],
                                'pic' => $pic,
                                'tamu' => $_POST['tamu'][$a],
                                'tgl_input' => date('Ymd'),
                            );

                            $tiket[] = array(
                                'id_trans' => $id_trans2++,
                                'id_vendor' => $vendor[0]['id_vendor'],
                                'id_keberangkatan' => $id_keberangkatan,
                                'id_pemberhentian' => $id_pemberhentian,
                                'id_pool' => $pool_tiket[0]['id_pool'],
                                'peminta' => session()->get('nama_pengguna'),
                                'atas_nama' => $atas_nama,
                                'jabatan' => $jabatan,
                                'jenis_kelamin' => $jenis_kelamin,
                                'jumlah_tiket' => $_POST['jumlah_tiket'][$a],
                                'pembayaran' => $pembayaran,
                                'harga_tiket' => $string,
                                'tanggal_jam_tiket' => $_POST['tanggal_jam_tiket'][$a],
                                'dari_tiket' => $dari_tiket,
                                'tujuan_tiket' => $tujuan_tiket,
                                'email_info' => $email_info,
                                'email_eval' => $email_eval,
                                'kirim_eval' => 0,
                                'keterangan_tiket' => $keterangan_tiket,
                                'tgl_input' => date('Ymd'),
                            );
                            $id_trans_dummy3 = $id_trans3++;
                            $id_trans_dummy4 = $id_trans4++;
                            $id_trans_dummy5 = $id_trans_mess++;
                        } else {
                            if(empty($atas_nama)) {
                                session()->setFlashdata('warning', ['Nama harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($jabatan)) {
                                session()->setFlashdata('warning', ['Jabatan harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($pic)) {
                                session()->setFlashdata('warning', ['PIC harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($email_eval)) {
                                session()->setFlashdata('warning', ['Email Evaluasi harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($_POST['jumlah_tiket'][$a])) {
                                session()->setFlashdata('warning', ['Jumlah tiket harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($_POST['tanggal_jam_tiket'][$a])) {
                                session()->setFlashdata('warning', ['Tanggal dan Jam tiket harus diisi']);
                                return redirect()->to("trans_add");
                            }
                        }
                    }
                    //Hotel
                    else if ($_POST['pemesanan'][$a] == '1') {
                        //Hotel
                        $jumlah_kamar = $_POST['jumlah_kamar'];
                        $nama_pool = $_POST['gs_hotel'];

                        if ($nama_pool[$a] == '2. Pool Jakarta') {
                            $nama_kota = $_POST['kota_jkt'];
                            $nama_akomodasi = $_POST['hotel_jkt'];
                        } else {
                            $nama_kota = $_POST['kota'];
                            $nama_akomodasi = $_POST['hotel'];
                        }

                        $nama_hotel = substr($nama_akomodasi[$a], 0, strpos($nama_akomodasi[$a], " - "));
                        $jenis_kamar = substr($nama_akomodasi[$a], strpos($nama_akomodasi[$a], ' - ') + 3);

                        $pool_hotel = $this->m_pool->where('nama_pool', $nama_pool[$a])->select('id_pool')->findAll();
                        $kota_hotel = $this->m_kota->where('nama_kota', $nama_kota[$a])->select('id_kota')->findAll();
                        $id_hotel = $this->m_hotel->where('nama_hotel', $nama_hotel)->select('id_hotel')->findAll();
                        if (empty($id_hotel)) {
                            session()->setFlashdata('warning', ['Tidak ada data Hotel']);
                            return redirect()->to("trans_add");
                        }
                        $id_detail_hotel = $this->m_detail_hotel->where('id_hotel', $id_hotel[0]['id_hotel'])->where('jenis_kamar', $jenis_kamar)->select('id_detail_hotel')->findAll();

                        $biaya = $_POST['harga_hotel'][$a];
                        $comma = ',';
                        $number = preg_replace('/[^0-9\\-]+/','', $biaya);
                        if ($number == null) {
                            $string = 0;
                        } else {
                            if( strpos($biaya, $comma) !== false ) {
                                $string = $number/100;
                            } else {
                                $string = $number;
                            }
                        }

                        if ($_POST['tamu'][$a] == 'Karyawan Konimex') {
                            $count = $_POST["count"][$a];
                            $nama_select_h = $_POST["nama_select".$count];
                            
                            $atas_namaa = implode(" - ", $nama_select_h);

                            $atas_namas = explode(" - ", $atas_namaa);

                            $jumlah = count($atas_namas);

                            for ($i=0; $i<$jumlah; $i++){
                                if ($i % 3 == 0){
                                    $atas_namat[$i] = $atas_namas[$i];
                                    $jabatant[$i] = $atas_namas[$i+1];
                                    $jenis_kelamint[$i] = $atas_namas[$i+2];
                                }
                            }
                            $atas_nama = implode(", ", $atas_namat);
                            $jabatan = implode(", ", $jabatant);
                            $jenis_kelamin = implode(", ", $jenis_kelamint);

                            $pic = $atas_nama;
                            $email_info = $atas_nama;
                            $email_eval = $atas_nama;

                            $pembayaran = $_POST['pembayaran'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                        } else {
                            $atas_nama = $_POST['nama_inputan'][$a];
                            $jabatan = $_POST['jabatan_inputan'][$a];
                            $jenis_kelamin = null;
                            $pic = session()->get('nama_pengguna');
                            $email_info = session()->get('nama_pengguna');
                            $email_eval = session()->get('nama_pengguna');

                            $pembayaran = $_POST['pembayaran_inputan'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                            $atas_nama = ucwords($atas_nama);
                            $jabatan = ucwords($jabatan);
                        }
                        if (empty($atas_nama)) {
                            session()->setFlashdata('warning', ['Nama harus diisi']);
                            return redirect()->to('trans_add');
                        }
                        if (empty($jabatan)) {
                            session()->setFlashdata('warning', ['Jabatan harus diisi']);
                            return redirect()->to('trans_add');
                        }

                        $perso_mess = explode(", ", $atas_nama);
                        if ($jenis_kelamin == null) {
                            $jk_mess = explode(", ", $atas_nama);
                        } else {
                            $jk_mess = explode(", ", $jenis_kelamin);
                        }

                        if ($pool_hotel[0]['id_pool'] == 1 || $pool_hotel[0]['id_pool'] == 3) {
                            $tanggal_jam_masuk = $_POST['tanggal_jam_masuk'][$a];
                            $tanggal_jam_keluar = $_POST['tanggal_jam_keluar'][$a];
                            $tamu = $_POST['tamu'][$a];
                            if ($tanggal_jam_masuk == $tanggal_jam_keluar) {
                                session()->setFlashdata('warning', ['Tanggal jam masuk dan keluar tidak boleh sama']);
                                return redirect()->to("trans_add");
                            }
                        } else {
                            if ($_POST['pesan_mnj'][$a] == "Iya") {
                                $tamu = "MNJ";
                            } else {
                                $tamu = $_POST['tamu'][$a];
                            }
                            $tanggal_jam_masuk = $_POST['tanggal_jam_masuk_jkt'][$a];
                            $tanggal_jam_keluar = $_POST['tanggal_jam_keluar_jkt'][$a];

                            if ($tanggal_jam_masuk == $tanggal_jam_keluar) {
                                session()->setFlashdata('warning', ['Tanggal jam masuk dan keluar tidak boleh sama']);
                                return redirect()->to("trans_add");
                            }

                            if ($id_hotel[0]['id_hotel'] == 158) {// 158 itu id_hotel untuk Mess Kx Jkt
                                // Declare two dates
                                $Date1 = $tanggal_jam_masuk;
                                $Date2 = $tanggal_jam_keluar;

                                // Declare an empty array
                                $date_arr = array();
                                    
                                // Use strtotime function
                                $Variable1 = strtotime($Date1);
                                $Variable2 = strtotime($Date2);
                                
                                // Use for loop to store dates into array
                                // 86400 sec = 24 hrs = 60*60*24 = 1 day
                                for ($currentDate = $Variable1; $currentDate <= $Variable2;
                                                                $currentDate += (86400)) {
                                    $Store = date('Y-m-d', $currentDate);
                                    $Store1 = date('H:i:s', $currentDate);
                                    
                                    foreach ($perso_mess as $pe => $per) {
                                        $pers[$pe] = $per;
                                    }

                                    if ($jenis_kelamin == null) {
                                        foreach ($jk_mess as $jk => $jkm) {
                                            $personil_mess_ako[] = [
                                                'id_trans' => $id_trans_mess,
                                                'atas_nama' => $pers[$jk],
                                                'jenis_kelamin' => null,
                                                'tanggal_mess' => $Store,
                                                'status' => 0,
                                                'batal' => 0,
                                            ];
                                        }
                                    } else {
                                        foreach ($jk_mess as $jk => $jkm) {
                                            $personil_mess_ako[] = [
                                                'id_trans' => $id_trans_mess,
                                                'atas_nama' => $pers[$jk],
                                                'jenis_kelamin' => $jkm,
                                                'tanggal_mess' => $Store,
                                                'status' => 0,
                                                'batal' => 0,
                                            ];
                                        }
                                    }

                                    $tanggal_mess_ako[] = [
                                        'id_trans' => $id_trans_mess,
                                        'tanggal_mess' => $Store,
                                        'jumlah_personil' => $_POST['jumlah_kamar'][$a],
                                        'status' => 0,
                                        'batal' => 0,
                                    ];

                                    $cek_tanggal_mess =  $this->m_tanggal_mess->where('tanggal_mess', $Store)->where('status', 0)->where('batal', 0)->select('tanggal_mess, jumlah_personil, sum(jumlah_personil) as sum')->findAll();
                                    
                                    foreach ($cek_tanggal_mess as $ctm => $ctme) {
                                        $sum = $ctme['sum'] + $_POST['jumlah_kamar'][$a];
                                        if ($sum > 18) {
                                            session()->setFlashdata('warning', ['Mess Kx Jkt sudah penuh untuk hari '.tanggal_indo($ctme['tanggal_mess'])]);
                                            return redirect()->to("trans_add");
                                        }
                                    }
                                }
                            } else {

                            }
                        }

                        $keterangan_akomodasi = $_POST['keterangan_akomodasi'][$a];
                        if(empty($keterangan_akomodasi)){
                            $keterangan_akomodasi = null;
                        }

                        if ($nama_hotel == "Mess Kx Jkt") {
                            $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                            $status_mess = 1;
                            
                            // foreach ($mess as $m => $mes) {
                            //     if ($mes['terpakai'] == 18) {
                            //         session()->setFlashdata('warning', ['Mess Kx Jkt sudah penuh']);
                            //         return redirect()->to("trans_add");
                            //     } else {
                            //         $terpakai = [
                            //             'id_mess' => 8,
                            //             'terpakai' => $mes['terpakai'] + $jumlah_kamar[$a],
                            //             'edited_at' => $timestamp,
                            //         ];
                            //         $this->m_mess->save($terpakai);
                            //     }
                            // }
                        } else {
                            $status_mess = 0;
                        }

                        if(!empty($atas_nama) && !empty($jabatan) && !empty($pic) && !empty($email_eval) && !empty($_POST['jumlah_kamar'][$a]) && !empty($tanggal_jam_masuk) && !empty($tanggal_jam_keluar)) {
                            $trans_ako[] = array(
                                'id_trans' => $id_trans1++,
                                'id_detail_pengguna' => session()->get('id_detail_pengguna'),
                                'id_bagian' => session()->get('id_bagian'),
                                'pemesanan' => $_POST['pemesanan'][$a],
                                'pic' => $pic,
                                'tamu' => $tamu,
                                'tgl_input' => date('Ymd'),
                            );

                            $akomodasi[] = array(
                                'id_trans' => $id_trans2++,
                                'id_hotel' => $id_hotel[0]['id_hotel'],
                                'id_detail_hotel' => $id_detail_hotel[0]['id_detail_hotel'],
                                'id_pool' => $pool_hotel[0]['id_pool'],
                                'id_kota' => $kota_hotel[0]['id_kota'],
                                'peminta' => session()->get('nama_pengguna'),
                                'atas_nama' => $atas_nama,
                                'jabatan' => $jabatan,
                                'jenis_kelamin' => $jenis_kelamin,
                                'type' => $_POST['type'][$a],
                                'jumlah_kamar' => $_POST['jumlah_kamar'][$a],
                                'pembayaran' => $pembayaran,
                                'harga_akomodasi' => $string,
                                'email_info' => $email_info,
                                'email_eval' => $email_eval,
                                'tanggal_jam_masuk' => $tanggal_jam_masuk,
                                'tanggal_jam_keluar' => $tanggal_jam_keluar,
                                'kirim_eval' => 0,
                                'status_mess' => $status_mess,
                                'status_akomodasi' => 0,
                                'keterangan_akomodasi' => $keterangan_akomodasi,
                                'tgl_input' => date('Ymd'),
                            );
                            $id_trans_dummy3 = $id_trans3++;
                            $id_trans_dummy4 = $id_trans4++;
                            $id_trans_dummy5 = $id_trans_mess++;
                        } else {
                            if(empty($atas_nama)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Nama harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($jabatan)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Jabatan harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($pic)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['PIC harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($email_eval)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Email Evaluasi harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($_POST['jumlah_kamar'][$a])) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Jumlah Kamar harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($tanggal_jam_masuk)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Tanggal dan Jam Masuk harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($tanggal_jam_keluar)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Tanggal dan Jam Keluar harus diisi']);
                                return redirect()->to("trans_add");
                            }
                        }
                    }
                    //Mobil
                    else if ($_POST['pemesanan'][$a] == '2') {
                        //Mobil
                        $nama_pool = $_POST['gs_mobil'];
                        $pool_mobil = $this->m_pool->where('nama_pool', $nama_pool[$a])->select('id_pool')->findAll();

                        if ($_POST['tamu'][$a] == 'Karyawan Konimex') {
                            $count = $_POST["count"][$a];
                            $nama_select_h = $_POST["nama_select".$count];
                            
                            $atas_namaa = implode(" - ", $nama_select_h);

                            $atas_namas = explode(" - ", $atas_namaa);

                            $jumlah = count($atas_namas);

                            for ($i=0; $i<$jumlah; $i++){
                                if ($i % 3 == 0){
                                    $atas_namat[$i] = $atas_namas[$i];
                                    $jabatant[$i] = $atas_namas[$i+1];
                                    $jenis_kelamint[$i] = $atas_namas[$i+2];
                                }
                            }
                            $atas_nama = implode(", ", $atas_namat);
                            $jabatan = implode(", ", $jabatant);
                            $jenis_kelamin = implode(", ", $jenis_kelamint);

                            $pic = $atas_nama;
                            $email_info = $atas_nama;
                            $email_eval = $atas_nama;

                            $pembayaran = $_POST['pembayaran'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                        } else {
                            $atas_nama = $_POST['nama_inputan'][$a];
                            $jabatan = $_POST['jabatan_inputan'][$a];
                            $jenis_kelamin = null;
                            $pic = session()->get('nama_pengguna');
                            $email_info = session()->get('nama_pengguna');
                            $email_eval = session()->get('nama_pengguna');

                            $pembayaran = $_POST['pembayaran_inputan'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                            $atas_nama = ucwords($atas_nama);
                            $jabatan = ucwords($jabatan);
                        }
                        if (empty($atas_nama)) {
                            session()->setFlashdata('warning', ['Nama harus diisi']);
                            return redirect()->to('trans_add');
                        }
                        if (empty($jabatan)) {
                            session()->setFlashdata('warning', ['Jabatan harus diisi']);
                            return redirect()->to('trans_add');
                        }

                        $tujuan_mobil = $_POST['tujuan_mobil'][$a];
                        $tujuan_mobil = ucwords($tujuan_mobil);

                        $siap_di = $_POST['siap_di'][$a];
                        $siap_di = ucwords($siap_di);

                        $jenis_kendaraan = $_POST['jenis_kendaraan'][$a];
                        if($jenis_kendaraan == 'Sedan'){
                            $jenis_kendaraan = 's';
                        } else if($jenis_kendaraan == 'Station'){
                            $jenis_kendaraan = 'a';
                        } else if($jenis_kendaraan == 'Pick Up'){
                            $jenis_kendaraan = 'p';
                        } else if($jenis_kendaraan == 'Box'){
                            $jenis_kendaraan = 'b';
                        } else if($jenis_kendaraan == 'Truck'){
                            $jenis_kendaraan = 't';
                        }
                        
                        $dalkot_lukot = $_POST['dalkot_lukot'][$a];

                        if($dalkot_lukot == 'Dalam Kota'){
                            $dalkot_lukot = 'd';
                            $menginap = 0;
                        } else {
                            $dalkot_lukot = 'l';
                            $menginap = $_POST['menginap'][$a];
                            if($menginap == 'Iya'){
                                $menginap = '1';
                            } else {
                                $menginap = '0';
                            }
                        }

                        $keterangan_mobil = $_POST['keterangan_mobil'][$a];
                        if(empty($keterangan_mobil)){
                            session()->setFlashdata('warning', ['Keterangan Mobil harus diisi']);
                            return redirect()->to('trans_add');
                        }
                        
                        if(!empty($atas_nama) && !empty($jabatan) && !empty($pic) && !empty($email_eval) && !empty($_POST['jumlah_mobil'][$a]) && !empty($_POST['tujuan_mobil'][$a]) && !empty($_POST['siap_di'][$a]) && !empty($_POST['tanggal_mobil'][$a]) && !empty($_POST['jam_siap'][$a])) {
                            $trans_tra[] = array(
                                'id_trans' => $id_trans1++,
                                'id_detail_pengguna' => session()->get('id_detail_pengguna'),
                                'id_bagian' => session()->get('id_bagian'),
                                'pemesanan' => $_POST['pemesanan'][$a],
                                'pic' => $pic,
                                'tamu' => $_POST['tamu'][$a],
                                'tgl_input' => date('Ymd'),
                            );

                            $transportasi[] = array(
                                'id_trans' => $id_trans2++,
                                'id_pool' => $pool_mobil[0]['id_pool'],
                                'peminta' => session()->get('nama_pengguna'),
                                'atas_nama' => $atas_nama,
                                'jenis_kelamin' => $jenis_kelamin,
                                'jabatan' => $jabatan,
                                'jumlah_mobil' => $_POST['jumlah_mobil'][$a],
                                'pembayaran' => $pembayaran,
                                'jenis_kendaraan' => $jenis_kendaraan,
                                'dalkot_lukot' => $dalkot_lukot,
                                'menginap' => $menginap,
                                'kapasitas' => $_POST['kapasitas'][$a],
                                'tujuan_mobil' => $tujuan_mobil,
                                'siap_di' => $siap_di,
                                'tanggal_mobil' => $_POST['tanggal_mobil'][$a],
                                'jam_siap' => $_POST['jam_siap'][$a],
                                'email_info' => $email_info,
                                'email_eval' => $email_eval,
                                'kirim_eval' => 0,
                                'status_mobil' => 0,
                                'keterangan_mobil' => $keterangan_mobil,
                                'tgl_input' => date('Ymd'),
                            );
                            $id_trans_dummy3 = $id_trans3++;
                            $id_trans_dummy4 = $id_trans4++;
                            $id_trans_dummy5 = $id_trans_mess++;
                        } else {
                            if(empty($atas_nama)) {
                                session()->setFlashdata('warning', ['Nama harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($jabatan)) {
                                session()->setFlashdata('warning', ['Jabatan harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($pic)) {
                                session()->setFlashdata('warning', ['PIC harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($email_eval)) {
                                session()->setFlashdata('warning', ['Email Evaluasi harus diisi']);
                                return redirect()->to("trans_add");
                            } else if (empty($_POST['jumlah_mobil'][$a])) {
                                session()->setFlashdata('warning', ['Jumlah Mobil harus diisi']);
                                return redirect()->to('trans_add');
                            } else if (empty($_POST['tujuan_mobil'][$a])) {
                                session()->setFlashdata('warning', ['Tujuan harus diisi']);
                                return redirect()->to('trans_add');
                            } else if (empty($_POST['siap_di'][$a])) {
                                session()->setFlashdata('warning', ['Siap Di harus diisi']);
                                return redirect()->to('trans_add');
                            } else if (empty($_POST['tanggal_mobil'][$a])) {
                                session()->setFlashdata('warning', ['Tanggal harus diisi']);
                                return redirect()->to('trans_add');
                            } else if (empty($_POST['jam_siap'][$a])) {
                                session()->setFlashdata('warning', ['Jam Siap harus diisi']);
                                return redirect()->to('trans_add');
                            }
                        }
                    }
                    //Tiket + Hotel
                    else if ($_POST['pemesanan'][$a] == '3') {
                        //Tiket
                        $nama_tiket = $_POST['tiket'];
                        $nama_pool = $_POST['gs_tiket'];
                        $keberangkatan = $_POST['keberangkatan'];
                        $pemberhentian = $_POST['pemberhentian'];

                        if ($_POST['pilihan_tiket'][$a] == 0) {
                            session()->setFlashdata('warning', ['Pilih armada transportasi terlebih dahulu']);
                            return redirect()->to('trans_add');
                        } else if ($_POST['pilihan_tiket'][$a] == "Travel") {
                            $id_keberangkatan = null;
                            $id_pemberhentian = null;
                            $dari_tiket = $_POST['keberangkatan'][$a];
                            $tujuan_tiket = $_POST['pemberhentian'][$a];
                        } else {
                            $nama_keberangkatan = substr($keberangkatan[$a], 0, strpos($keberangkatan[$a], " - "));
                            $dari_tiket = substr($keberangkatan[$a], strpos($keberangkatan[$a], " - ")+3);
                            $id_berangkat = $this->m_pemberhentian->where('nama_pemberhentian', $nama_keberangkatan)->select('id_pemberhentian')->findAll();
                            $id_keberangkatan = $id_berangkat[0]['id_pemberhentian'];
                            
                            $nama_pemberhentian = substr($pemberhentian[$a], 0, strpos($pemberhentian[$a], " - "));
                            $tujuan_tiket = substr($pemberhentian[$a], strpos($pemberhentian[$a], " - ")+3);
                            $id_berhenti = $this->m_pemberhentian->where('nama_pemberhentian', $nama_pemberhentian)->select('id_pemberhentian')->findAll();
                            $id_pemberhentian = $id_berhenti[0]['id_pemberhentian'];
                        }

                        if ($_POST['keberangkatan'][$a] == $_POST['pemberhentian'][$a]) {
                            session()->setFlashdata('warning', ['Keberangkatan dan Pemberhentian Tiket tidak boleh sama']);
                            return redirect()->to('trans_add');
                        }

                        if ($_POST['jumlah_tiket'][$a] == 0) {
                            session()->setFlashdata('warning', ['Jumlah tiket tidak boleh 0']);
                            return redirect()->to("trans_add");
                        }

                        $biaya = $_POST['harga_tiket'][$a];
                        $comma = ',';
                        $number = preg_replace('/[^0-9\\-]+/','', $biaya);
                        if ($number == null) {
                            $string = 0;
                        } else {
                            if( strpos($biaya, $comma) !== false ) {
                                $string = $number/100;
                            } else {
                                $string = $number;
                            }
                        }
                        
                        $vendor = $this->m_vendor->where('nama_vendor', $nama_tiket[$a])->select('id_vendor')->findAll();
                        if (empty($vendor)) {
                            session()->setFlashdata('warning', ['Tidak ada data Tiket']);
                            return redirect()->to("trans_add");
                        }
                        $pool_tiket = $this->m_pool->where('nama_pool', $nama_pool[$a])->select('id_pool')->findAll();

                        if ($_POST['tamu'][$a] == 'Karyawan Konimex') {
                            $count = $_POST["count"][$a];
                            $nama_select_h = $_POST["nama_select".$count];
                            
                            $atas_namaa = implode(" - ", $nama_select_h);

                            $atas_namas = explode(" - ", $atas_namaa);

                            $jumlah = count($atas_namas);

                            for ($i=0; $i<$jumlah; $i++){
                                if ($i % 3 == 0){
                                    $atas_namat[$i] = $atas_namas[$i];
                                    $jabatant[$i] = $atas_namas[$i+1];
                                    $jenis_kelamint[$i] = $atas_namas[$i+2];
                                }
                            }
                            $atas_nama = implode(", ", $atas_namat);
                            $jabatan = implode(", ", $jabatant);
                            $jenis_kelamin = implode(", ", $jenis_kelamint);

                            $pic = $atas_nama;
                            $email_info = $atas_nama;
                            $email_eval = $atas_nama;

                            $pembayaran = $_POST['pembayaran'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                        } else {
                            $atas_nama = $_POST['nama_inputan'][$a];
                            $jabatan = $_POST['jabatan_inputan'][$a];
                            $jenis_kelamin = null;
                            $pic = session()->get('nama_pengguna');
                            $email_info = session()->get('nama_pengguna');
                            $email_eval = session()->get('nama_pengguna');

                            $pembayaran = $_POST['pembayaran_inputan'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                            $atas_nama = ucwords($atas_nama);
                            $jabatan = ucwords($jabatan);
                        }
                        if (empty($atas_nama)) {
                            session()->setFlashdata('warning', ['Nama harus diisi']);
                            return redirect()->to('trans_add');
                        }
                        if (empty($jabatan)) {
                            session()->setFlashdata('warning', ['Jabatan harus diisi']);
                            return redirect()->to('trans_add');
                        }

                        $keterangan_tiket = $_POST['keterangan_tiket'][$a];
                        if(empty($keterangan_tiket)){
                            $keterangan_tiket = null;
                        }

                        //Hotel
                        $jumlah_kamar = $_POST['jumlah_kamar'];
                        $nama_pool = $_POST['gs_hotel'];

                        if ($nama_pool[$a] == '2. Pool Jakarta') {
                            $nama_kota = $_POST['kota_jkt'];
                            $nama_akomodasi = $_POST['hotel_jkt'];
                        } else {
                            $nama_kota = $_POST['kota'];
                            $nama_akomodasi = $_POST['hotel'];
                        }

                        $nama_hotel = substr($nama_akomodasi[$a], 0, strpos($nama_akomodasi[$a], " - "));
                        $jenis_kamar = substr($nama_akomodasi[$a], strpos($nama_akomodasi[$a], ' - ') + 3);

                        $pool_hotel = $this->m_pool->where('nama_pool', $nama_pool[$a])->select('id_pool')->findAll();
                        $kota_hotel = $this->m_kota->where('nama_kota', $nama_kota[$a])->select('id_kota')->findAll();
                        $id_hotel = $this->m_hotel->where('nama_hotel', $nama_hotel)->select('id_hotel')->findAll();
                        if (empty($id_hotel)) {
                            session()->setFlashdata('warning', ['Tidak ada data Hotel']);
                            return redirect()->to("trans_add");
                        }
                        $id_detail_hotel = $this->m_detail_hotel->where('id_hotel', $id_hotel[0]['id_hotel'])->where('jenis_kamar', $jenis_kamar)->select('id_detail_hotel')->findAll();

                        $biaya = $_POST['harga_hotel'][$a];
                        $comma = ',';
                        $number = preg_replace('/[^0-9\\-]+/','', $biaya);
                        if ($number == null) {
                            $string = 0;
                        } else {
                            if( strpos($biaya, $comma) !== false ) {
                                $string = $number/100;
                            } else {
                                $string = $number;
                            }
                        }

                        if ($_POST['tamu'][$a] == 'Karyawan Konimex') {
                            $count = $_POST["count"][$a];
                            $nama_select_h = $_POST["nama_select".$count];
                            
                            $atas_namaa = implode(" - ", $nama_select_h);

                            $atas_namas = explode(" - ", $atas_namaa);

                            $jumlah = count($atas_namas);

                            for ($i=0; $i<$jumlah; $i++){
                                if ($i % 3 == 0){
                                    $atas_namat[$i] = $atas_namas[$i];
                                    $jabatant[$i] = $atas_namas[$i+1];
                                    $jenis_kelamint[$i] = $atas_namas[$i+2];
                                }
                            }
                            $atas_nama = implode(", ", $atas_namat);
                            $jabatan = implode(", ", $jabatant);
                            $jenis_kelamin = implode(", ", $jenis_kelamint);

                            $pic = $atas_nama;
                            $email_info = $atas_nama;
                            $email_eval = $atas_nama;

                            $pembayaran = $_POST['pembayaran'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                        } else {
                            $atas_nama = $_POST['nama_inputan'][$a];
                            $jabatan = $_POST['jabatan_inputan'][$a];
                            $jenis_kelamin = null;
                            $pic = session()->get('nama_pengguna');
                            $email_info = session()->get('nama_pengguna');
                            $email_eval = session()->get('nama_pengguna');

                            $pembayaran = $_POST['pembayaran_inputan'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                            $atas_nama = ucwords($atas_nama);
                            $jabatan = ucwords($jabatan);
                        }
                        if (empty($atas_nama)) {
                            session()->setFlashdata('warning', ['Nama harus diisi']);
                            return redirect()->to('trans_add');
                        }
                        if (empty($jabatan)) {
                            session()->setFlashdata('warning', ['Jabatan harus diisi']);
                            return redirect()->to('trans_add');
                        }

                        $perso_mess = explode(", ", $atas_nama);
                        if ($jenis_kelamin == null) {
                            $jk_mess = explode(", ", $atas_nama);
                        } else {
                            $jk_mess = explode(", ", $jenis_kelamin);
                        }

                        if ($pool_hotel[0]['id_pool'] == 1 || $pool_hotel[0]['id_pool'] == 3) {
                            $tanggal_jam_masuk = $_POST['tanggal_jam_masuk'][$a];
                            $tanggal_jam_keluar = $_POST['tanggal_jam_keluar'][$a];
                            $tamu = $_POST['tamu'][$a];
                            if ($tanggal_jam_masuk == $tanggal_jam_keluar) {
                                session()->setFlashdata('warning', ['Tanggal jam masuk dan keluar tidak boleh sama']);
                                return redirect()->to("trans_add");
                            }
                        } else {
                            if ($_POST['pesan_mnj'][$a] == "Iya") {
                                $tamu = "MNJ";
                            } else {
                                $tamu = $_POST['tamu'][$a];
                            }
                            $tanggal_jam_masuk = $_POST['tanggal_jam_masuk_jkt'][$a];
                            $tanggal_jam_keluar = $_POST['tanggal_jam_keluar_jkt'][$a];

                            if ($tanggal_jam_masuk == $tanggal_jam_keluar) {
                                session()->setFlashdata('warning', ['Tanggal jam masuk dan keluar tidak boleh sama']);
                                return redirect()->to("trans_add");
                            }

                            if ($id_hotel[0]['id_hotel'] == 158) {// 158 itu id_hotel untuk Mess Kx Jkt
                                // Declare two dates
                                $Date1 = $tanggal_jam_masuk;
                                $Date2 = $tanggal_jam_keluar;

                                // Declare an empty array
                                $date_arr = array();
                                    
                                // Use strtotime function
                                $Variable1 = strtotime($Date1);
                                $Variable2 = strtotime($Date2);
                                
                                // Use for loop to store dates into array
                                // 86400 sec = 24 hrs = 60*60*24 = 1 day
                                for ($currentDate = $Variable1; $currentDate <= $Variable2;
                                                                $currentDate += (86400)) {
                                    $Store = date('Y-m-d', $currentDate);
                                    $Store1 = date('H:i:s', $currentDate);
                                    
                                    foreach ($perso_mess as $pe => $per) {
                                        $pers[$pe] = $per;
                                    }

                                    if ($jenis_kelamin == null) {
                                        foreach ($jk_mess as $jk => $jkm) {
                                            $personil_mess_ta[] = [
                                                'id_trans' => $id_trans_mess,
                                                'atas_nama' => $pers[$jk],
                                                'jenis_kelamin' => null,
                                                'tanggal_mess' => $Store,
                                                'status' => 0,
                                                'batal' => 0,
                                            ];
                                        }
                                    } else {
                                        foreach ($jk_mess as $jk => $jkm) {
                                            $personil_mess_ta[] = [
                                                'id_trans' => $id_trans_mess,
                                                'atas_nama' => $pers[$jk],
                                                'jenis_kelamin' => $jkm,
                                                'tanggal_mess' => $Store,
                                                'status' => 0,
                                                'batal' => 0,
                                            ];
                                        }
                                    }

                                    $tanggal_mess_ta[] = [
                                        'id_trans' => $id_trans_mess,
                                        'tanggal_mess' => $Store,
                                        'jumlah_personil' => $_POST['jumlah_kamar'][$a],
                                        'status' => 0,
                                        'batal' => 0,
                                    ];

                                    $cek_tanggal_mess =  $this->m_tanggal_mess->where('tanggal_mess', $Store)->where('status', 0)->where('batal', 0)->select('tanggal_mess, jumlah_personil, sum(jumlah_personil) as sum')->findAll();
                                    
                                    foreach ($cek_tanggal_mess as $ctm => $ctme) {
                                        $sum = $ctme['sum'] + $_POST['jumlah_kamar'][$a];
                                        if ($sum > 18) {
                                            session()->setFlashdata('warning', ['Mess Kx Jkt sudah penuh untuk hari '.tanggal_indo($ctme['tanggal_mess'])]);
                                            return redirect()->to("trans_add");
                                        }
                                    }
                                }
                            } else {

                            }
                        }

                        $keterangan_akomodasi = $_POST['keterangan_akomodasi'][$a];
                        if(empty($keterangan_akomodasi)){
                            $keterangan_akomodasi = null;
                        }

                        if ($nama_hotel == "Mess Kx Jkt") {
                            $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                            $status_mess = 1;
                            
                            // foreach ($mess as $m => $mes) {
                            //     if ($mes['terpakai'] == 18) {
                            //         session()->setFlashdata('warning', ['Mess Kx Jkt sudah penuh']);
                            //         return redirect()->to("trans_add");
                            //     } else {
                            //         $terpakai = [
                            //             'id_mess' => 8,
                            //             'terpakai' => $mes['terpakai'] + $jumlah_kamar[$a],
                            //             'edited_at' => $timestamp,
                            //         ];
                            //         $this->m_mess->save($terpakai);
                            //     }
                            // }
                        } else {
                            $status_mess = 0;
                        }

                        if(!empty($atas_nama) && !empty($jabatan) && !empty($pic) && !empty($email_eval) && !empty($_POST['jumlah_tiket'][$a]) && !empty($_POST['tanggal_jam_tiket'][$a]) && !empty($_POST['jumlah_kamar'][$a]) && !empty($tanggal_jam_masuk) && !empty($tanggal_jam_keluar)) {
                            $trans_ta[] = array(
                                'id_trans' => $id_trans1++,
                                'id_detail_pengguna' => session()->get('id_detail_pengguna'),
                                'id_bagian' => session()->get('id_bagian'),
                                'pemesanan' => $_POST['pemesanan'][$a],
                                'pic' => $pic,
                                'tamu' => $_POST['tamu'][$a],
                                'tgl_input' => date('Ymd'),
                            );

                            $tiket_akomodasi_ta[] = array(
                                'id_trans' => $id_trans2++,
                                'id_vendor' => $vendor[0]['id_vendor'],
                                'id_keberangkatan' => $id_keberangkatan,
                                'id_pemberhentian' => $id_pemberhentian,
                                'id_pool' => $pool_tiket[0]['id_pool'],
                                'peminta' => session()->get('nama_pengguna'),
                                'atas_nama' => $atas_nama,
                                'jenis_kelamin' => $jenis_kelamin,
                                'jabatan' => $jabatan,
                                'jumlah_tiket' => $_POST['jumlah_tiket'][$a],
                                'pembayaran' => $pembayaran,
                                'harga_tiket' => $string,
                                'tanggal_jam_tiket' => $_POST['tanggal_jam_tiket'][$a],
                                'dari_tiket' => $dari_tiket,
                                'tujuan_tiket' => $tujuan_tiket,
                                'email_info' => $email_info,
                                'email_eval' => $email_eval,
                                'kirim_eval' => 0,
                                'keterangan_tiket' => $keterangan_tiket,
                                'tgl_input' => date('Ymd'),
                            );

                            $akomodasi_tiket_ta[] = array(
                                'id_trans' => $id_trans3++,
                                'id_hotel' => $id_hotel[0]['id_hotel'],
                                'id_detail_hotel' => $id_detail_hotel[0]['id_detail_hotel'],
                                'id_pool' => $pool_hotel[0]['id_pool'],
                                'id_kota' => $kota_hotel[0]['id_kota'],
                                'peminta' => session()->get('nama_pengguna'),
                                'atas_nama' => $atas_nama,
                                'jenis_kelamin' => $jenis_kelamin,
                                'jabatan' => $jabatan,
                                'type' => $_POST['type'][$a],
                                'jumlah_kamar' => $_POST['jumlah_kamar'][$a],
                                'pembayaran' => $pembayaran,
                                'harga_akomodasi' => $string,
                                'email_info' => $email_info,
                                'email_eval' => $email_eval,
                                'tanggal_jam_masuk' => $tanggal_jam_masuk,
                                'tanggal_jam_keluar' => $tanggal_jam_keluar,
                                'kirim_eval' => 0,
                                'status_mess' => $status_mess,
                                'status_akomodasi' => 0,
                                'keterangan_akomodasi' => $keterangan_akomodasi,
                                'tgl_input' => date('Ymd'),
                            );
                            $id_trans_dummy4 = $id_trans4++;
                            $id_trans_dummy5 = $id_trans_mess++;
                        } else {
                            if(empty($atas_nama)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Nama harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($jabatan)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Jabatan harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($pic)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['PIC harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($email_eval)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Email Evaluasi harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($_POST['jumlah_tiket'][$a])) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Jumlah tiket harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($_POST['tanggal_jam_tiket'][$a])) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Tanggal dan Jam tiket harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($_POST['jumlah_kamar'][$a])) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Jumlah Kamar harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($tanggal_jam_masuk)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Tanggal dan Jam Masuk harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($tanggal_jam_keluar)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Tanggal dan Jam Keluar harus diisi']);
                                return redirect()->to("trans_add");
                            }
                        }
                    }
                    //Tiket + Mobil
                    else if ($_POST['pemesanan'][$a] == '4') {
                        //Tiket
                        $nama_tiket = $_POST['tiket'];
                        $nama_pool = $_POST['gs_tiket'];
                        $keberangkatan = $_POST['keberangkatan'];
                        $pemberhentian = $_POST['pemberhentian'];

                        if ($_POST['pilihan_tiket'][$a] == 0) {
                            session()->setFlashdata('warning', ['Pilih armada transportasi terlebih dahulu']);
                            return redirect()->to('trans_add');
                        } else if ($_POST['pilihan_tiket'][$a] == "Travel") {
                            $id_keberangkatan = null;
                            $id_pemberhentian = null;
                            $dari_tiket = $_POST['keberangkatan'][$a];
                            $tujuan_tiket = $_POST['pemberhentian'][$a];
                        } else {
                            $nama_keberangkatan = substr($keberangkatan[$a], 0, strpos($keberangkatan[$a], " - "));
                            $dari_tiket = substr($keberangkatan[$a], strpos($keberangkatan[$a], " - ")+3);
                            $id_berangkat = $this->m_pemberhentian->where('nama_pemberhentian', $nama_keberangkatan)->select('id_pemberhentian')->findAll();
                            $id_keberangkatan = $id_berangkat[0]['id_pemberhentian'];
                            
                            $nama_pemberhentian = substr($pemberhentian[$a], 0, strpos($pemberhentian[$a], " - "));
                            $tujuan_tiket = substr($pemberhentian[$a], strpos($pemberhentian[$a], " - ")+3);
                            $id_berhenti = $this->m_pemberhentian->where('nama_pemberhentian', $nama_pemberhentian)->select('id_pemberhentian')->findAll();
                            $id_pemberhentian = $id_berhenti[0]['id_pemberhentian'];
                        }

                        if ($_POST['keberangkatan'][$a] == $_POST['pemberhentian'][$a]) {
                            session()->setFlashdata('warning', ['Keberangkatan dan Pemberhentian Tiket tidak boleh sama']);
                            return redirect()->to('trans_add');
                        }

                        if ($_POST['jumlah_tiket'][$a] == 0) {
                            session()->setFlashdata('warning', ['Jumlah tiket tidak boleh 0']);
                            return redirect()->to("trans_add");
                        }

                        $biaya = $_POST['harga_tiket'][$a];
                        $comma = ',';
                        $number = preg_replace('/[^0-9\\-]+/','', $biaya);
                        if ($number == null) {
                            $string = 0;
                        } else {
                            if( strpos($biaya, $comma) !== false ) {
                                $string = $number/100;
                            } else {
                                $string = $number;
                            }
                        }
                        
                        $vendor = $this->m_vendor->where('nama_vendor', $nama_tiket[$a])->select('id_vendor')->findAll();
                        if (empty($vendor)) {
                            session()->setFlashdata('warning', ['Tidak ada data Tiket']);
                            return redirect()->to("trans_add");
                        }
                        $pool_tiket = $this->m_pool->where('nama_pool', $nama_pool[$a])->select('id_pool')->findAll();

                        if ($_POST['tamu'][$a] == 'Karyawan Konimex') {
                            $count = $_POST["count"][$a];
                            $nama_select_h = $_POST["nama_select".$count];
                            
                            $atas_namaa = implode(" - ", $nama_select_h);

                            $atas_namas = explode(" - ", $atas_namaa);

                            $jumlah = count($atas_namas);

                            for ($i=0; $i<$jumlah; $i++){
                                if ($i % 3 == 0){
                                    $atas_namat[$i] = $atas_namas[$i];
                                    $jabatant[$i] = $atas_namas[$i+1];
                                    $jenis_kelamint[$i] = $atas_namas[$i+2];
                                }
                            }
                            $atas_nama = implode(", ", $atas_namat);
                            $jabatan = implode(", ", $jabatant);
                            $jenis_kelamin = implode(", ", $jenis_kelamint);

                            $pic = $atas_nama;
                            $email_info = $atas_nama;
                            $email_eval = $atas_nama;

                            $pembayaran = $_POST['pembayaran'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                        } else {
                            $atas_nama = $_POST['nama_inputan'][$a];
                            $jabatan = $_POST['jabatan_inputan'][$a];
                            $jenis_kelamin = null;
                            $pic = session()->get('nama_pengguna');
                            $email_info = session()->get('nama_pengguna');
                            $email_eval = session()->get('nama_pengguna');

                            $pembayaran = $_POST['pembayaran_inputan'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                            $atas_nama = ucwords($atas_nama);
                            $jabatan = ucwords($jabatan);
                        }
                        if (empty($atas_nama)) {
                            session()->setFlashdata('warning', ['Nama harus diisi']);
                            return redirect()->to('trans_add');
                        }
                        if (empty($jabatan)) {
                            session()->setFlashdata('warning', ['Jabatan harus diisi']);
                            return redirect()->to('trans_add');
                        }

                        $keterangan_tiket = $_POST['keterangan_tiket'][$a];
                        if(empty($keterangan_tiket)){
                            $keterangan_tiket = null;
                        }

                        //Mobil
                        $nama_pool = $_POST['gs_mobil'];
                        $pool_mobil = $this->m_pool->where('nama_pool', $nama_pool[$a])->select('id_pool')->findAll();

                        if ($_POST['tamu'][$a] == 'Karyawan Konimex') {
                            $count = $_POST["count"][$a];
                            $nama_select_h = $_POST["nama_select".$count];
                            
                            $atas_namaa = implode(" - ", $nama_select_h);

                            $atas_namas = explode(" - ", $atas_namaa);

                            $jumlah = count($atas_namas);

                            for ($i=0; $i<$jumlah; $i++){
                                if ($i % 3 == 0){
                                    $atas_namat[$i] = $atas_namas[$i];
                                    $jabatant[$i] = $atas_namas[$i+1];
                                    $jenis_kelamint[$i] = $atas_namas[$i+2];
                                }
                            }
                            $atas_nama = implode(", ", $atas_namat);
                            $jabatan = implode(", ", $jabatant);
                            $jenis_kelamin = implode(", ", $jenis_kelamint);

                            $pic = $atas_nama;
                            $email_info = $atas_nama;
                            $email_eval = $atas_nama;

                            $pembayaran = $_POST['pembayaran'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                        } else {
                            $atas_nama = $_POST['nama_inputan'][$a];
                            $jabatan = $_POST['jabatan_inputan'][$a];
                            $jenis_kelamin = null;
                            $pic = session()->get('nama_pengguna');
                            $email_info = session()->get('nama_pengguna');
                            $email_eval = session()->get('nama_pengguna');

                            $pembayaran = $_POST['pembayaran_inputan'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                            $atas_nama = ucwords($atas_nama);
                            $jabatan = ucwords($jabatan);
                        }
                        if (empty($atas_nama)) {
                            session()->setFlashdata('warning', ['Nama harus diisi']);
                            return redirect()->to('trans_add');
                        }
                        if (empty($jabatan)) {
                            session()->setFlashdata('warning', ['Jabatan harus diisi']);
                            return redirect()->to('trans_add');
                        }

                        $tujuan_mobil = $_POST['tujuan_mobil'][$a];
                        $tujuan_mobil = ucwords($tujuan_mobil);

                        $siap_di = $_POST['siap_di'][$a];
                        $siap_di = ucwords($siap_di);

                        $jenis_kendaraan = $_POST['jenis_kendaraan'][$a];
                        if($jenis_kendaraan == 'Sedan'){
                            $jenis_kendaraan = 's';
                        } else if($jenis_kendaraan == 'Station'){
                            $jenis_kendaraan = 'a';
                        } else if($jenis_kendaraan == 'Pick Up'){
                            $jenis_kendaraan = 'p';
                        } else if($jenis_kendaraan == 'Box'){
                            $jenis_kendaraan = 'b';
                        } else if($jenis_kendaraan == 'Truck'){
                            $jenis_kendaraan = 't';
                        }
                        
                        $dalkot_lukot = $_POST['dalkot_lukot'][$a];

                        if($dalkot_lukot == 'Dalam Kota'){
                            $dalkot_lukot = 'd';
                            $menginap = 0;
                        } else {
                            $dalkot_lukot = 'l';
                            $menginap = $_POST['menginap'][$a];
                            if($menginap == 'Iya'){
                                $menginap = '1';
                            } else {
                                $menginap = '0';
                            }
                        }

                        $keterangan_mobil = $_POST['keterangan_mobil'][$a];
                        if(empty($keterangan_mobil)){
                            session()->setFlashdata('warning', ['Keterangan Mobil harus diisi']);
                            return redirect()->to('trans_add');
                        }

                        if(!empty($atas_nama) && !empty($jabatan) && !empty($pic) && !empty($email_eval) && !empty($_POST['jumlah_tiket'][$a]) && !empty($_POST['tanggal_jam_tiket'][$a]) && !empty($_POST['jumlah_mobil'][$a]) && !empty($_POST['tujuan_mobil'][$a]) && !empty($_POST['siap_di'][$a]) && !empty($_POST['tanggal_mobil'][$a]) && !empty($_POST['jam_siap'][$a])) {
                            $trans_tt[] = array(
                                'id_trans' => $id_trans1++,
                                'id_detail_pengguna' => session()->get('id_detail_pengguna'),
                                'id_bagian' => session()->get('id_bagian'),
                                'pemesanan' => $_POST['pemesanan'][$a],
                                'pic' => $pic,
                                'tamu' => $_POST['tamu'][$a],
                                'tgl_input' => date('Ymd'),
                            );

                            $tiket_transportasi_tt[] = array(
                                'id_trans' => $id_trans2++,
                                'id_vendor' => $vendor[0]['id_vendor'],
                                'id_keberangkatan' => $id_keberangkatan,
                                'id_pemberhentian' => $id_pemberhentian,
                                'id_pool' => $pool_tiket[0]['id_pool'],
                                'peminta' => session()->get('nama_pengguna'),
                                'atas_nama' => $atas_nama,
                                'jenis_kelamin' => $jenis_kelamin,
                                'jabatan' => $jabatan,
                                'jumlah_tiket' => $_POST['jumlah_tiket'][$a],
                                'pembayaran' => $pembayaran,
                                'harga_tiket' => $string,
                                'tanggal_jam_tiket' => $_POST['tanggal_jam_tiket'][$a],
                                'dari_tiket' => $dari_tiket,
                                'tujuan_tiket' => $tujuan_tiket,
                                'email_info' => $email_info,
                                'email_eval' => $email_eval,
                                'kirim_eval' => 0,
                                'keterangan_tiket' => $keterangan_tiket,
                                'tgl_input' => date('Ymd'),
                            );

                            $transportasi_tiket_tt[] = array(
                                'id_trans' => $id_trans3++,
                                'id_pool' => $pool_mobil[0]['id_pool'],
                                'peminta' => session()->get('nama_pengguna'),
                                'atas_nama' => $atas_nama,
                                'jenis_kelamin' => $jenis_kelamin,
                                'jabatan' => $jabatan,
                                'jumlah_mobil' => $_POST['jumlah_mobil'][$a],
                                'pembayaran' => $pembayaran,
                                'jenis_kendaraan' => $jenis_kendaraan,
                                'dalkot_lukot' => $dalkot_lukot,
                                'menginap' => $menginap,
                                'kapasitas' => $_POST['kapasitas'][$a],
                                'tujuan_mobil' => $tujuan_mobil,
                                'siap_di' => $siap_di,
                                'tanggal_mobil' => $_POST['tanggal_mobil'][$a],
                                'jam_siap' => $_POST['jam_siap'][$a],
                                'email_info' => $email_info,
                                'email_eval' => $email_eval,
                                'kirim_eval' => 0,
                                'status_mobil' => 0,
                                'keterangan_mobil' => $keterangan_mobil,
                                'tgl_input' => date('Ymd'),
                            );
                            $id_trans_dummy4 = $id_trans4++;
                            $id_trans_dummy5 = $id_trans_mess++;
                        } else {
                            if(empty($atas_nama)) {
                                session()->setFlashdata('warning', ['Nama harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($jabatan)) {
                                session()->setFlashdata('warning', ['Jabatan harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($pic)) {
                                session()->setFlashdata('warning', ['PIC harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($email_eval)) {
                                session()->setFlashdata('warning', ['Email Evaluasi harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($_POST['jumlah_tiket'][$a])) {
                                session()->setFlashdata('warning', ['Jumlah tiket harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($_POST['tanggal_jam_tiket'][$a])) {
                                session()->setFlashdata('warning', ['Tanggal dan Jam tiket harus diisi']);
                                return redirect()->to("trans_add");
                            } else if (empty($_POST['jumlah_mobil'][$a])) {
                                session()->setFlashdata('warning', ['Jumlah Mobil harus diisi']);
                                return redirect()->to('trans_add');
                            } else if (empty($_POST['tujuan_mobil'][$a])) {
                                session()->setFlashdata('warning', ['Tujuan harus diisi']);
                                return redirect()->to('trans_add');
                            } else if (empty($_POST['siap_di'][$a])) {
                                session()->setFlashdata('warning', ['Siap Di harus diisi']);
                                return redirect()->to('trans_add');
                            } else if (empty($_POST['tanggal_mobil'][$a])) {
                                session()->setFlashdata('warning', ['Tanggal harus diisi']);
                                return redirect()->to('trans_add');
                            } else if (empty($_POST['jam_siap'][$a])) {
                                session()->setFlashdata('warning', ['Jam Siap harus diisi']);
                                return redirect()->to('trans_add');
                            }
                        }
                    }
                    //Hotel + Mobil
                    else if ($_POST['pemesanan'][$a] == '5') {
                        //Hotel
                        $jumlah_kamar = $_POST['jumlah_kamar'];
                        $nama_pool = $_POST['gs_hotel'];

                        if ($nama_pool[$a] == '2. Pool Jakarta') {
                            $nama_kota = $_POST['kota_jkt'];
                            $nama_akomodasi = $_POST['hotel_jkt'];
                        } else {
                            $nama_kota = $_POST['kota'];
                            $nama_akomodasi = $_POST['hotel'];
                        }

                        $nama_hotel = substr($nama_akomodasi[$a], 0, strpos($nama_akomodasi[$a], " - "));
                        $jenis_kamar = substr($nama_akomodasi[$a], strpos($nama_akomodasi[$a], ' - ') + 3);

                        $pool_hotel = $this->m_pool->where('nama_pool', $nama_pool[$a])->select('id_pool')->findAll();
                        $kota_hotel = $this->m_kota->where('nama_kota', $nama_kota[$a])->select('id_kota')->findAll();
                        $id_hotel = $this->m_hotel->where('nama_hotel', $nama_hotel)->select('id_hotel')->findAll();
                        if (empty($id_hotel)) {
                            session()->setFlashdata('warning', ['Tidak ada data Hotel']);
                            return redirect()->to("trans_add");
                        }
                        $id_detail_hotel = $this->m_detail_hotel->where('id_hotel', $id_hotel[0]['id_hotel'])->where('jenis_kamar', $jenis_kamar)->select('id_detail_hotel')->findAll();

                        $biaya = $_POST['harga_hotel'][$a];
                        $comma = ',';
                        $number = preg_replace('/[^0-9\\-]+/','', $biaya);
                        if ($number == null) {
                            $string = 0;
                        } else {
                            if( strpos($biaya, $comma) !== false ) {
                                $string = $number/100;
                            } else {
                                $string = $number;
                            }
                        }

                        if ($_POST['tamu'][$a] == 'Karyawan Konimex') {
                            $count = $_POST["count"][$a];
                            $nama_select_h = $_POST["nama_select".$count];
                            
                            $atas_namaa = implode(" - ", $nama_select_h);

                            $atas_namas = explode(" - ", $atas_namaa);

                            $jumlah = count($atas_namas);

                            for ($i=0; $i<$jumlah; $i++){
                                if ($i % 3 == 0){
                                    $atas_namat[$i] = $atas_namas[$i];
                                    $jabatant[$i] = $atas_namas[$i+1];
                                    $jenis_kelamint[$i] = $atas_namas[$i+2];
                                }
                            }
                            $atas_nama = implode(", ", $atas_namat);
                            $jabatan = implode(", ", $jabatant);
                            $jenis_kelamin = implode(", ", $jenis_kelamint);

                            $pic = $atas_nama;
                            $email_info = $atas_nama;
                            $email_eval = $atas_nama;

                            $pembayaran = $_POST['pembayaran'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                        } else {
                            $atas_nama = $_POST['nama_inputan'][$a];
                            $jabatan = $_POST['jabatan_inputan'][$a];
                            $jenis_kelamin = null;
                            $pic = session()->get('nama_pengguna');
                            $email_info = session()->get('nama_pengguna');
                            $email_eval = session()->get('nama_pengguna');

                            $pembayaran = $_POST['pembayaran_inputan'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                            $atas_nama = ucwords($atas_nama);
                            $jabatan = ucwords($jabatan);
                        }
                        if (empty($atas_nama)) {
                            session()->setFlashdata('warning', ['Nama harus diisi']);
                            return redirect()->to('trans_add');
                        }
                        if (empty($jabatan)) {
                            session()->setFlashdata('warning', ['Jabatan harus diisi']);
                            return redirect()->to('trans_add');
                        }

                        $perso_mess = explode(", ", $atas_nama);
                        if ($jenis_kelamin == null) {
                            $jk_mess = explode(", ", $atas_nama);
                        } else {
                            $jk_mess = explode(", ", $jenis_kelamin);
                        }

                        if ($pool_hotel[0]['id_pool'] == 1 || $pool_hotel[0]['id_pool'] == 3) {
                            $tanggal_jam_masuk = $_POST['tanggal_jam_masuk'][$a];
                            $tanggal_jam_keluar = $_POST['tanggal_jam_keluar'][$a];
                            $tamu = $_POST['tamu'][$a];
                            if ($tanggal_jam_masuk == $tanggal_jam_keluar) {
                                session()->setFlashdata('warning', ['Tanggal jam masuk dan keluar tidak boleh sama']);
                                return redirect()->to("trans_add");
                            }
                        } else {
                            if ($_POST['pesan_mnj'][$a] == "Iya") {
                                $tamu = "MNJ";
                            } else {
                                $tamu = $_POST['tamu'][$a];
                            }
                            $tanggal_jam_masuk = $_POST['tanggal_jam_masuk_jkt'][$a];
                            $tanggal_jam_keluar = $_POST['tanggal_jam_keluar_jkt'][$a];

                            if ($tanggal_jam_masuk == $tanggal_jam_keluar) {
                                session()->setFlashdata('warning', ['Tanggal jam masuk dan keluar tidak boleh sama']);
                                return redirect()->to("trans_add");
                            }

                            if ($id_hotel[0]['id_hotel'] == 158) {// 158 itu id_hotel untuk Mess Kx Jkt
                                // Declare two dates
                                $Date1 = $tanggal_jam_masuk;
                                $Date2 = $tanggal_jam_keluar;

                                // Declare an empty array
                                $date_arr = array();
                                    
                                // Use strtotime function
                                $Variable1 = strtotime($Date1);
                                $Variable2 = strtotime($Date2);
                                
                                // Use for loop to store dates into array
                                // 86400 sec = 24 hrs = 60*60*24 = 1 day
                                for ($currentDate = $Variable1; $currentDate <= $Variable2;
                                                                $currentDate += (86400)) {
                                    $Store = date('Y-m-d', $currentDate);
                                    $Store1 = date('H:i:s', $currentDate);
                                    
                                    foreach ($perso_mess as $pe => $per) {
                                        $pers[$pe] = $per;
                                    }

                                    if ($jenis_kelamin == null) {
                                        foreach ($jk_mess as $jk => $jkm) {
                                            $personil_mess_at[] = [
                                                'id_trans' => $id_trans_mess,
                                                'atas_nama' => $pers[$jk],
                                                'jenis_kelamin' => null,
                                                'tanggal_mess' => $Store,
                                                'status' => 0,
                                                'batal' => 0,
                                            ];
                                        }
                                    } else {
                                        foreach ($jk_mess as $jk => $jkm) {
                                            $personil_mess_at[] = [
                                                'id_trans' => $id_trans_mess,
                                                'atas_nama' => $pers[$jk],
                                                'jenis_kelamin' => $jkm,
                                                'tanggal_mess' => $Store,
                                                'status' => 0,
                                                'batal' => 0,
                                            ];
                                        }
                                    }

                                    $tanggal_mess_at[] = [
                                        'id_trans' => $id_trans_mess,
                                        'tanggal_mess' => $Store,
                                        'jumlah_personil' => $_POST['jumlah_kamar'][$a],
                                        'status' => 0,
                                        'batal' => 0,
                                    ];

                                    $cek_tanggal_mess =  $this->m_tanggal_mess->where('tanggal_mess', $Store)->where('status', 0)->where('batal', 0)->select('tanggal_mess, jumlah_personil, sum(jumlah_personil) as sum')->findAll();
                                    
                                    foreach ($cek_tanggal_mess as $ctm => $ctme) {
                                        $sum = $ctme['sum'] + $_POST['jumlah_kamar'][$a];
                                        if ($sum > 18) {
                                            session()->setFlashdata('warning', ['Mess Kx Jkt sudah penuh untuk hari '.tanggal_indo($ctme['tanggal_mess'])]);
                                            return redirect()->to("trans_add");
                                        }
                                    }
                                }
                            } else {

                            }
                        }

                        $keterangan_akomodasi = $_POST['keterangan_akomodasi'][$a];
                        if(empty($keterangan_akomodasi)){
                            $keterangan_akomodasi = null;
                        }

                        if ($nama_hotel == "Mess Kx Jkt") {
                            $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                            $status_mess = 1;
                            
                            // foreach ($mess as $m => $mes) {
                            //     if ($mes['terpakai'] == 18) {
                            //         session()->setFlashdata('warning', ['Mess Kx Jkt sudah penuh']);
                            //         return redirect()->to("trans_add");
                            //     } else {
                            //         $terpakai = [
                            //             'id_mess' => 8,
                            //             'terpakai' => $mes['terpakai'] + $jumlah_kamar[$a],
                            //             'edited_at' => $timestamp,
                            //         ];
                            //         $this->m_mess->save($terpakai);
                            //     }
                            // }
                        } else {
                            $status_mess = 0;
                        }

                        //Mobil
                        $nama_pool = $_POST['gs_mobil'];
                        $pool_mobil = $this->m_pool->where('nama_pool', $nama_pool[$a])->select('id_pool')->findAll();

                        if ($_POST['tamu'][$a] == 'Karyawan Konimex') {
                            $count = $_POST["count"][$a];
                            $nama_select_h = $_POST["nama_select".$count];
                            
                            $atas_namaa = implode(" - ", $nama_select_h);

                            $atas_namas = explode(" - ", $atas_namaa);

                            $jumlah = count($atas_namas);

                            for ($i=0; $i<$jumlah; $i++){
                                if ($i % 3 == 0){
                                    $atas_namat[$i] = $atas_namas[$i];
                                    $jabatant[$i] = $atas_namas[$i+1];
                                    $jenis_kelamint[$i] = $atas_namas[$i+2];
                                }
                            }
                            $atas_nama = implode(", ", $atas_namat);
                            $jabatan = implode(", ", $jabatant);
                            $jenis_kelamin = implode(", ", $jenis_kelamint);

                            $pic = $atas_nama;
                            $email_info = $atas_nama;
                            $email_eval = $atas_nama;

                            $pembayaran = $_POST['pembayaran'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                        } else {
                            $atas_nama = $_POST['nama_inputan'][$a];
                            $jabatan = $_POST['jabatan_inputan'][$a];
                            $jenis_kelamin = null;
                            $pic = session()->get('nama_pengguna');
                            $email_info = session()->get('nama_pengguna');
                            $email_eval = session()->get('nama_pengguna');

                            $pembayaran = $_POST['pembayaran_inputan'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                            $atas_nama = ucwords($atas_nama);
                            $jabatan = ucwords($jabatan);
                        }
                        if (empty($atas_nama)) {
                            session()->setFlashdata('warning', ['Nama harus diisi']);
                            return redirect()->to('trans_add');
                        }
                        if (empty($jabatan)) {
                            session()->setFlashdata('warning', ['Jabatan harus diisi']);
                            return redirect()->to('trans_add');
                        }

                        $tujuan_mobil = $_POST['tujuan_mobil'][$a];
                        $tujuan_mobil = ucwords($tujuan_mobil);

                        $siap_di = $_POST['siap_di'][$a];
                        $siap_di = ucwords($siap_di);

                        $jenis_kendaraan = $_POST['jenis_kendaraan'][$a];
                        if($jenis_kendaraan == 'Sedan'){
                            $jenis_kendaraan = 's';
                        } else if($jenis_kendaraan == 'Station'){
                            $jenis_kendaraan = 'a';
                        } else if($jenis_kendaraan == 'Pick Up'){
                            $jenis_kendaraan = 'p';
                        } else if($jenis_kendaraan == 'Box'){
                            $jenis_kendaraan = 'b';
                        } else if($jenis_kendaraan == 'Truck'){
                            $jenis_kendaraan = 't';
                        }
                        
                        $dalkot_lukot = $_POST['dalkot_lukot'][$a];

                        if($dalkot_lukot == 'Dalam Kota'){
                            $dalkot_lukot = 'd';
                            $menginap = 0;
                        } else {
                            $dalkot_lukot = 'l';
                            $menginap = $_POST['menginap'][$a];
                            if($menginap == 'Iya'){
                                $menginap = '1';
                            } else {
                                $menginap = '0';
                            }
                        }

                        $keterangan_mobil = $_POST['keterangan_mobil'][$a];
                        if(empty($keterangan_mobil)){
                            session()->setFlashdata('warning', ['Keterangan Mobil harus diisi']);
                            return redirect()->to('trans_add');
                        }

                        if(!empty($atas_nama) && !empty($jabatan) && !empty($pic) && !empty($email_eval) && !empty($_POST['jumlah_kamar'][$a]) && !empty($tanggal_jam_masuk) && !empty($tanggal_jam_keluar) && !empty($_POST['jumlah_mobil'][$a]) && !empty($_POST['tujuan_mobil'][$a]) && !empty($_POST['siap_di'][$a]) && !empty($_POST['tanggal_mobil'][$a]) && !empty($_POST['jam_siap'][$a])) {
                            $trans_at[] = array(
                                'id_trans' => $id_trans1++,
                                'id_detail_pengguna' => session()->get('id_detail_pengguna'),
                                'id_bagian' => session()->get('id_bagian'),
                                'pemesanan' => $_POST['pemesanan'][$a],
                                'pic' => $pic,
                                'tamu' => $_POST['tamu'][$a],
                                'tgl_input' => date('Ymd'),
                            );

                            $akomodasi_transportasi_at[] = array(
                                'id_trans' => $id_trans2++,
                                'id_hotel' => $id_hotel[0]['id_hotel'],
                                'id_detail_hotel' => $id_detail_hotel[0]['id_detail_hotel'],
                                'id_pool' => $pool_hotel[0]['id_pool'],
                                'id_kota' => $kota_hotel[0]['id_kota'],
                                'peminta' => session()->get('nama_pengguna'),
                                'atas_nama' => $atas_nama,
                                'jenis_kelamin' => $jenis_kelamin,
                                'jabatan' => $jabatan,
                                'type' => $_POST['type'][$a],
                                'jumlah_kamar' => $_POST['jumlah_kamar'][$a],
                                'pembayaran' => $pembayaran,
                                'harga_akomodasi' => $string,
                                'email_info' => $email_info,
                                'email_eval' => $email_eval,
                                'tanggal_jam_masuk' => $tanggal_jam_masuk,
                                'tanggal_jam_keluar' => $tanggal_jam_keluar,
                                'kirim_eval' => 0,
                                'status_mess' => $status_mess,
                                'status_akomodasi' => 0,
                                'keterangan_akomodasi' => $keterangan_akomodasi,
                                'tgl_input' => date('Ymd'),
                            );

                            $transportasi_akomodasi_at[] = array(
                                'id_trans' => $id_trans3++,
                                'id_pool' => $pool_mobil[0]['id_pool'],
                                'peminta' => session()->get('nama_pengguna'),
                                'atas_nama' => $atas_nama,
                                'jenis_kelamin' => $jenis_kelamin,
                                'jabatan' => $jabatan,
                                'jumlah_mobil' => $_POST['jumlah_mobil'][$a],
                                'pembayaran' => $pembayaran,
                                'jenis_kendaraan' => $jenis_kendaraan,
                                'dalkot_lukot' => $dalkot_lukot,
                                'menginap' => $menginap,
                                'kapasitas' => $_POST['kapasitas'][$a],
                                'tujuan_mobil' => $tujuan_mobil,
                                'siap_di' => $siap_di,
                                'tanggal_mobil' => $_POST['tanggal_mobil'][$a],
                                'jam_siap' => $_POST['jam_siap'][$a],
                                'email_info' => $email_info,
                                'email_eval' => $email_eval,
                                'kirim_eval' => 0,
                                'status_mobil' => 0,
                                'keterangan_mobil' => $keterangan_mobil,
                                'tgl_input' => date('Ymd'),
                            );
                            $id_trans_dummy4 = $id_trans4++;
                            $id_trans_dummy5 = $id_trans_mess++;
                        } else {
                            if(empty($atas_nama)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Nama harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($jabatan)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Jabatan harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($pic)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['PIC harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($email_eval)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Email Evaluasi harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($_POST['jumlah_kamar'][$a])) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Jumlah Kamar harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($tanggal_jam_masuk)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Tanggal dan Jam Masuk harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($tanggal_jam_keluar)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Tanggal dan Jam Keluar harus diisi']);
                                return redirect()->to("trans_add");
                            } else if (empty($_POST['jumlah_mobil'][$a])) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Jumlah Mobil harus diisi']);
                                return redirect()->to('trans_add');
                            } else if (empty($_POST['tujuan_mobil'][$a])) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Tujuan harus diisi']);
                                return redirect()->to('trans_add');
                            } else if (empty($_POST['siap_di'][$a])) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Siap Di harus diisi']);
                                return redirect()->to('trans_add');
                            } else if (empty($_POST['tanggal_mobil'][$a])) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Tanggal harus diisi']);
                                return redirect()->to('trans_add');
                            } else if (empty($_POST['jam_siap'][$a])) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Jam Siap harus diisi']);
                                return redirect()->to('trans_add');
                            }
                        }
                    }
                    //Tiket + Hotel + Mobil
                    else if ($_POST['pemesanan'][$a] == '6') {
                        //Tiket
                        $nama_tiket = $_POST['tiket'];
                        $nama_pool = $_POST['gs_tiket'];
                        $keberangkatan = $_POST['keberangkatan'];
                        $pemberhentian = $_POST['pemberhentian'];

                        if ($_POST['pilihan_tiket'][$a] == 0) {
                            session()->setFlashdata('warning', ['Pilih armada transportasi terlebih dahulu']);
                            return redirect()->to('trans_add');
                        } else if ($_POST['pilihan_tiket'][$a] == "Travel") {
                            $id_keberangkatan = null;
                            $id_pemberhentian = null;
                            $dari_tiket = $_POST['keberangkatan'][$a];
                            $tujuan_tiket = $_POST['pemberhentian'][$a];
                        } else {
                            $nama_keberangkatan = substr($keberangkatan[$a], 0, strpos($keberangkatan[$a], " - "));
                            $dari_tiket = substr($keberangkatan[$a], strpos($keberangkatan[$a], " - ")+3);
                            $id_berangkat = $this->m_pemberhentian->where('nama_pemberhentian', $nama_keberangkatan)->select('id_pemberhentian')->findAll();
                            $id_keberangkatan = $id_berangkat[0]['id_pemberhentian'];
                            
                            $nama_pemberhentian = substr($pemberhentian[$a], 0, strpos($pemberhentian[$a], " - "));
                            $tujuan_tiket = substr($pemberhentian[$a], strpos($pemberhentian[$a], " - ")+3);
                            $id_berhenti = $this->m_pemberhentian->where('nama_pemberhentian', $nama_pemberhentian)->select('id_pemberhentian')->findAll();
                            $id_pemberhentian = $id_berhenti[0]['id_pemberhentian'];
                        }

                        if ($_POST['keberangkatan'][$a] == $_POST['pemberhentian'][$a]) {
                            session()->setFlashdata('warning', ['Keberangkatan dan Pemberhentian Tiket tidak boleh sama']);
                            return redirect()->to('trans_add');
                        }

                        if ($_POST['jumlah_tiket'][$a] == 0) {
                            session()->setFlashdata('warning', ['Jumlah tiket tidak boleh 0']);
                            return redirect()->to("trans_add");
                        }

                        $biaya = $_POST['harga_tiket'][$a];
                        $comma = ',';
                        $number = preg_replace('/[^0-9\\-]+/','', $biaya);
                        if ($number == null) {
                            $string = 0;
                        } else {
                            if( strpos($biaya, $comma) !== false ) {
                                $string = $number/100;
                            } else {
                                $string = $number;
                            }
                        }
                        
                        $vendor = $this->m_vendor->where('nama_vendor', $nama_tiket[$a])->select('id_vendor')->findAll();
                        if (empty($vendor)) {
                            session()->setFlashdata('warning', ['Tidak ada data Tiket']);
                            return redirect()->to("trans_add");
                        }
                        $pool_tiket = $this->m_pool->where('nama_pool', $nama_pool[$a])->select('id_pool')->findAll();

                        if ($_POST['tamu'][$a] == 'Karyawan Konimex') {
                            $count = $_POST["count"][$a];
                            $nama_select_h = $_POST["nama_select".$count];
                            
                            $atas_namaa = implode(" - ", $nama_select_h);

                            $atas_namas = explode(" - ", $atas_namaa);

                            $jumlah = count($atas_namas);

                            for ($i=0; $i<$jumlah; $i++){
                                if ($i % 3 == 0){
                                    $atas_namat[$i] = $atas_namas[$i];
                                    $jabatant[$i] = $atas_namas[$i+1];
                                    $jenis_kelamint[$i] = $atas_namas[$i+2];
                                }
                            }
                            $atas_nama = implode(", ", $atas_namat);
                            $jabatan = implode(", ", $jabatant);
                            $jenis_kelamin = implode(", ", $jenis_kelamint);

                            $pic = $atas_nama;
                            $email_info = $atas_nama;
                            $email_eval = $atas_nama;

                            $pembayaran = $_POST['pembayaran'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                        } else {
                            $atas_nama = $_POST['nama_inputan'][$a];
                            $jabatan = $_POST['jabatan_inputan'][$a];
                            $jenis_kelamin = null;
                            $pic = session()->get('nama_pengguna');
                            $email_info = session()->get('nama_pengguna');
                            $email_eval = session()->get('nama_pengguna');

                            $pembayaran = $_POST['pembayaran_inputan'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                            $atas_nama = ucwords($atas_nama);
                            $jabatan = ucwords($jabatan);
                        }
                        if (empty($atas_nama)) {
                            session()->setFlashdata('warning', ['Nama harus diisi']);
                            return redirect()->to('trans_add');
                        }
                        if (empty($jabatan)) {
                            session()->setFlashdata('warning', ['Jabatan harus diisi']);
                            return redirect()->to('trans_add');
                        }

                        $keterangan_tiket = $_POST['keterangan_tiket'][$a];
                        if(empty($keterangan_tiket)){
                            $keterangan_tiket = null;
                        }

                        //Hotel
                        $jumlah_kamar = $_POST['jumlah_kamar'];
                        $nama_pool = $_POST['gs_hotel'];

                        if ($nama_pool[$a] == '2. Pool Jakarta') {
                            $nama_kota = $_POST['kota_jkt'];
                            $nama_akomodasi = $_POST['hotel_jkt'];
                        } else {
                            $nama_kota = $_POST['kota'];
                            $nama_akomodasi = $_POST['hotel'];
                        }

                        $nama_hotel = substr($nama_akomodasi[$a], 0, strpos($nama_akomodasi[$a], " - "));
                        $jenis_kamar = substr($nama_akomodasi[$a], strpos($nama_akomodasi[$a], ' - ') + 3);

                        $pool_hotel = $this->m_pool->where('nama_pool', $nama_pool[$a])->select('id_pool')->findAll();
                        $kota_hotel = $this->m_kota->where('nama_kota', $nama_kota[$a])->select('id_kota')->findAll();
                        $id_hotel = $this->m_hotel->where('nama_hotel', $nama_hotel)->select('id_hotel')->findAll();
                        if (empty($id_hotel)) {
                            session()->setFlashdata('warning', ['Tidak ada data Hotel']);
                            return redirect()->to("trans_add");
                        }
                        $id_detail_hotel = $this->m_detail_hotel->where('id_hotel', $id_hotel[0]['id_hotel'])->where('jenis_kamar', $jenis_kamar)->select('id_detail_hotel')->findAll();

                        $biaya = $_POST['harga_hotel'][$a];
                        $comma = ',';
                        $number = preg_replace('/[^0-9\\-]+/','', $biaya);
                        if ($number == null) {
                            $string = 0;
                        } else {
                            if( strpos($biaya, $comma) !== false ) {
                                $string = $number/100;
                            } else {
                                $string = $number;
                            }
                        }

                        if ($_POST['tamu'][$a] == 'Karyawan Konimex') {
                            $count = $_POST["count"][$a];
                            $nama_select_h = $_POST["nama_select".$count];
                            
                            $atas_namaa = implode(" - ", $nama_select_h);

                            $atas_namas = explode(" - ", $atas_namaa);

                            $jumlah = count($atas_namas);

                            for ($i=0; $i<$jumlah; $i++){
                                if ($i % 3 == 0){
                                    $atas_namat[$i] = $atas_namas[$i];
                                    $jabatant[$i] = $atas_namas[$i+1];
                                    $jenis_kelamint[$i] = $atas_namas[$i+2];
                                }
                            }
                            $atas_nama = implode(", ", $atas_namat);
                            $jabatan = implode(", ", $jabatant);
                            $jenis_kelamin = implode(", ", $jenis_kelamint);

                            $pic = $atas_nama;
                            $email_info = $atas_nama;
                            $email_eval = $atas_nama;

                            $pembayaran = $_POST['pembayaran'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                        } else {
                            $atas_nama = $_POST['nama_inputan'][$a];
                            $jabatan = $_POST['jabatan_inputan'][$a];
                            $jenis_kelamin = null;
                            $pic = session()->get('nama_pengguna');
                            $email_info = session()->get('nama_pengguna');
                            $email_eval = session()->get('nama_pengguna');

                            $pembayaran = $_POST['pembayaran_inputan'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                            $atas_nama = ucwords($atas_nama);
                            $jabatan = ucwords($jabatan);
                        }
                        if (empty($atas_nama)) {
                            session()->setFlashdata('warning', ['Nama harus diisi']);
                            return redirect()->to('trans_add');
                        }
                        if (empty($jabatan)) {
                            session()->setFlashdata('warning', ['Jabatan harus diisi']);
                            return redirect()->to('trans_add');
                        }

                        $perso_mess = explode(", ", $atas_nama);
                        if ($jenis_kelamin == null) {
                            $jk_mess = explode(", ", $atas_nama);
                        } else {
                            $jk_mess = explode(", ", $jenis_kelamin);
                        }

                        if ($pool_hotel[0]['id_pool'] == 1 || $pool_hotel[0]['id_pool'] == 3) {
                            $tanggal_jam_masuk = $_POST['tanggal_jam_masuk'][$a];
                            $tanggal_jam_keluar = $_POST['tanggal_jam_keluar'][$a];
                            $tamu = $_POST['tamu'][$a];
                            if ($tanggal_jam_masuk == $tanggal_jam_keluar) {
                                session()->setFlashdata('warning', ['Tanggal jam masuk dan keluar tidak boleh sama']);
                                return redirect()->to("trans_add");
                            }
                        } else {
                            if ($_POST['pesan_mnj'][$a] == "Iya") {
                                $tamu = "MNJ";
                            } else {
                                $tamu = $_POST['tamu'][$a];
                            }
                            $tanggal_jam_masuk = $_POST['tanggal_jam_masuk_jkt'][$a];
                            $tanggal_jam_keluar = $_POST['tanggal_jam_keluar_jkt'][$a];

                            if ($tanggal_jam_masuk == $tanggal_jam_keluar) {
                                session()->setFlashdata('warning', ['Tanggal jam masuk dan keluar tidak boleh sama']);
                                return redirect()->to("trans_add");
                            }

                            if ($id_hotel[0]['id_hotel'] == 158) {// 158 itu id_hotel untuk Mess Kx Jkt
                                // Declare two dates
                                $Date1 = $tanggal_jam_masuk;
                                $Date2 = $tanggal_jam_keluar;

                                // Declare an empty array
                                $date_arr = array();
                                    
                                // Use strtotime function
                                $Variable1 = strtotime($Date1);
                                $Variable2 = strtotime($Date2);
                                
                                // Use for loop to store dates into array
                                // 86400 sec = 24 hrs = 60*60*24 = 1 day
                                for ($currentDate = $Variable1; $currentDate <= $Variable2;
                                                                $currentDate += (86400)) {
                                    $Store = date('Y-m-d', $currentDate);
                                    $Store1 = date('H:i:s', $currentDate);
                                    
                                    foreach ($perso_mess as $pe => $per) {
                                        $pers[$pe] = $per;
                                    }

                                    if ($jenis_kelamin == null) {
                                        foreach ($jk_mess as $jk => $jkm) {
                                            $personil_mess_tat[] = [
                                                'id_trans' => $id_trans_mess,
                                                'atas_nama' => $pers[$jk],
                                                'jenis_kelamin' => null,
                                                'tanggal_mess' => $Store,
                                                'status' => 0,
                                                'batal' => 0,
                                            ];
                                        }
                                    } else {
                                        foreach ($jk_mess as $jk => $jkm) {
                                            $personil_mess_tat[] = [
                                                'id_trans' => $id_trans_mess,
                                                'atas_nama' => $pers[$jk],
                                                'jenis_kelamin' => $jkm,
                                                'tanggal_mess' => $Store,
                                                'status' => 0,
                                                'batal' => 0,
                                            ];
                                        }
                                    }

                                    $tanggal_mess_tat[] = [
                                        'id_trans' => $id_trans_mess,
                                        'tanggal_mess' => $Store,
                                        'jumlah_personil' => $_POST['jumlah_kamar'][$a],
                                        'status' => 0,
                                        'batal' => 0,
                                    ];

                                    $cek_tanggal_mess =  $this->m_tanggal_mess->where('tanggal_mess', $Store)->where('status', 0)->where('batal', 0)->select('tanggal_mess, jumlah_personil, sum(jumlah_personil) as sum')->findAll();
                                    
                                    foreach ($cek_tanggal_mess as $ctm => $ctme) {
                                        $sum = $ctme['sum'] + $_POST['jumlah_kamar'][$a];
                                        if ($sum > 18) {
                                            session()->setFlashdata('warning', ['Mess Kx Jkt sudah penuh untuk hari '.tanggal_indo($ctme['tanggal_mess'])]);
                                            return redirect()->to("trans_add");
                                        }
                                    }
                                }
                            } else {

                            }
                        }

                        $keterangan_akomodasi = $_POST['keterangan_akomodasi'][$a];
                        if(empty($keterangan_akomodasi)){
                            $keterangan_akomodasi = null;
                        }

                        if ($nama_hotel == "Mess Kx Jkt") {
                            $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                            $status_mess = 1;
                            
                            // foreach ($mess as $m => $mes) {
                            //     if ($mes['terpakai'] == 18) {
                            //         session()->setFlashdata('warning', ['Mess Kx Jkt sudah penuh']);
                            //         return redirect()->to("trans_add");
                            //     } else {
                            //         $terpakai = [
                            //             'id_mess' => 8,
                            //             'terpakai' => $mes['terpakai'] + $jumlah_kamar[$a],
                            //             'edited_at' => $timestamp,
                            //         ];
                            //         $this->m_mess->save($terpakai);
                            //     }
                            // }
                        } else {
                            $status_mess = 0;
                        }

                        //Mobil
                        $nama_pool = $_POST['gs_mobil'];
                        $pool_mobil = $this->m_pool->where('nama_pool', $nama_pool[$a])->select('id_pool')->findAll();

                        if ($_POST['tamu'][$a] == 'Karyawan Konimex') {
                            $count = $_POST["count"][$a];
                            $nama_select_h = $_POST["nama_select".$count];
                            
                            $atas_namaa = implode(" - ", $nama_select_h);

                            $atas_namas = explode(" - ", $atas_namaa);

                            $jumlah = count($atas_namas);

                            for ($i=0; $i<$jumlah; $i++){
                                if ($i % 3 == 0){
                                    $atas_namat[$i] = $atas_namas[$i];
                                    $jabatant[$i] = $atas_namas[$i+1];
                                    $jenis_kelamint[$i] = $atas_namas[$i+2];
                                }
                            }
                            $atas_nama = implode(", ", $atas_namat);
                            $jabatan = implode(", ", $jabatant);
                            $jenis_kelamin = implode(", ", $jenis_kelamint);

                            $pic = $atas_nama;
                            $email_info = $atas_nama;
                            $email_eval = $atas_nama;

                            $pembayaran = $_POST['pembayaran'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                        } else {
                            $atas_nama = $_POST['nama_inputan'][$a];
                            $jabatan = $_POST['jabatan_inputan'][$a];
                            $jenis_kelamin = null;
                            $pic = session()->get('nama_pengguna');
                            $email_info = session()->get('nama_pengguna');
                            $email_eval = session()->get('nama_pengguna');

                            $pembayaran = $_POST['pembayaran_inputan'][$a];
                            if($pembayaran == 'Company Acc'){
                                $pembayaran = 'k';
                            } else if($pembayaran == 'Personal Acc'){
                                $pembayaran = 'p';
                            }
                            $atas_nama = ucwords($atas_nama);
                            $jabatan = ucwords($jabatan);
                        }
                        if (empty($atas_nama)) {
                            session()->setFlashdata('warning', ['Nama harus diisi']);
                            return redirect()->to('trans_add');
                        }
                        if (empty($jabatan)) {
                            session()->setFlashdata('warning', ['Jabatan harus diisi']);
                            return redirect()->to('trans_add');
                        }

                        $tujuan_mobil = $_POST['tujuan_mobil'][$a];
                        $tujuan_mobil = ucwords($tujuan_mobil);

                        $siap_di = $_POST['siap_di'][$a];
                        $siap_di = ucwords($siap_di);

                        $jenis_kendaraan = $_POST['jenis_kendaraan'][$a];
                        if($jenis_kendaraan == 'Sedan'){
                            $jenis_kendaraan = 's';
                        } else if($jenis_kendaraan == 'Station'){
                            $jenis_kendaraan = 'a';
                        } else if($jenis_kendaraan == 'Pick Up'){
                            $jenis_kendaraan = 'p';
                        } else if($jenis_kendaraan == 'Box'){
                            $jenis_kendaraan = 'b';
                        } else if($jenis_kendaraan == 'Truck'){
                            $jenis_kendaraan = 't';
                        }
                        
                        $dalkot_lukot = $_POST['dalkot_lukot'][$a];

                        if($dalkot_lukot == 'Dalam Kota'){
                            $dalkot_lukot = 'd';
                            $menginap = 0;
                        } else {
                            $dalkot_lukot = 'l';
                            $menginap = $_POST['menginap'][$a];
                            if($menginap == 'Iya'){
                                $menginap = '1';
                            } else {
                                $menginap = '0';
                            }
                        }

                        $keterangan_mobil = $_POST['keterangan_mobil'][$a];
                        if(empty($keterangan_mobil)){
                            session()->setFlashdata('warning', ['Keterangan Mobil harus diisi']);
                            return redirect()->to('trans_add');
                        }

                        if(!empty($atas_nama) && !empty($jabatan) && !empty($pic) && !empty($email_eval) && !empty($_POST['jumlah_tiket'][$a]) && !empty($_POST['tanggal_jam_tiket'][$a]) && !empty($_POST['jumlah_kamar'][$a]) && !empty($tanggal_jam_masuk) && !empty($tanggal_jam_keluar) && !empty($_POST['jumlah_mobil'][$a]) && !empty($_POST['tujuan_mobil'][$a]) && !empty($_POST['siap_di'][$a]) && !empty($_POST['tanggal_mobil'][$a]) && !empty($_POST['jam_siap'][$a])) {
                            $trans_tat[] = array(
                                'id_trans' => $id_trans1++,
                                'id_detail_pengguna' => session()->get('id_detail_pengguna'),
                                'id_bagian' => session()->get('id_bagian'),
                                'pemesanan' => $_POST['pemesanan'][$a],
                                'pic' => $pic,
                                'tamu' => $_POST['tamu'][$a],
                                'tgl_input' => date('Ymd'),
                            );

                            $tiket_akomodasi_transportasi[] = array(
                                'id_trans' => $id_trans2++,
                                'id_vendor' => $vendor[0]['id_vendor'],
                                'id_keberangkatan' => $id_keberangkatan,
                                'id_pemberhentian' => $id_pemberhentian,
                                'id_pool' => $pool_tiket[0]['id_pool'],
                                'peminta' => session()->get('nama_pengguna'),
                                'atas_nama' => $atas_nama,
                                'jenis_kelamin' => $jenis_kelamin,
                                'jabatan' => $jabatan,
                                'jumlah_tiket' => $_POST['jumlah_tiket'][$a],
                                'pembayaran' => $pembayaran,
                                'harga_tiket' => $string,
                                'tanggal_jam_tiket' => $_POST['tanggal_jam_tiket'][$a],
                                'dari_tiket' => $dari_tiket,
                                'tujuan_tiket' => $tujuan_tiket,
                                'email_info' => $email_info,
                                'email_eval' => $email_eval,
                                'kirim_eval' => 0,
                                'keterangan_tiket' => $keterangan_tiket,
                                'tgl_input' => date('Ymd'),
                            );

                            $akomodasi_tiket_transportasi[] = array(
                                'id_trans' => $id_trans3++,
                                'id_hotel' => $id_hotel[0]['id_hotel'],
                                'id_detail_hotel' => $id_detail_hotel[0]['id_detail_hotel'],
                                'id_pool' => $pool_hotel[0]['id_pool'],
                                'id_kota' => $kota_hotel[0]['id_kota'],
                                'peminta' => session()->get('nama_pengguna'),
                                'atas_nama' => $atas_nama,
                                'jenis_kelamin' => $jenis_kelamin,
                                'jabatan' => $jabatan,
                                'type' => $_POST['type'][$a],
                                'jumlah_kamar' => $_POST['jumlah_kamar'][$a],
                                'pembayaran' => $pembayaran,
                                'harga_akomodasi' => $string,
                                'email_info' => $email_info,
                                'email_eval' => $email_eval,
                                'tanggal_jam_masuk' => $tanggal_jam_masuk,
                                'tanggal_jam_keluar' => $tanggal_jam_keluar,
                                'kirim_eval' => 0,
                                'status_mess' => $status_mess,
                                'status_akomodasi' => 0,
                                'keterangan_akomodasi' => $keterangan_akomodasi,
                                'tgl_input' => date('Ymd'),
                            );

                            $transportasi_tiket_akomodasi[] = array(
                                'id_trans' => $id_trans4++,
                                'id_pool' => $pool_mobil[0]['id_pool'],
                                'peminta' => session()->get('nama_pengguna'),
                                'atas_nama' => $atas_nama,
                                'jenis_kelamin' => $jenis_kelamin,
                                'jabatan' => $jabatan,
                                'jumlah_mobil' => $_POST['jumlah_mobil'][$a],
                                'pembayaran' => $pembayaran,
                                'jenis_kendaraan' => $jenis_kendaraan,
                                'dalkot_lukot' => $dalkot_lukot,
                                'menginap' => $menginap,
                                'kapasitas' => $_POST['kapasitas'][$a],
                                'tujuan_mobil' => $tujuan_mobil,
                                'siap_di' => $siap_di,
                                'tanggal_mobil' => $_POST['tanggal_mobil'][$a],
                                'jam_siap' => $_POST['jam_siap'][$a],
                                'email_info' => $email_info,
                                'email_eval' => $email_eval,
                                'kirim_eval' => 0,
                                'status_mobil' => 0,
                                'keterangan_mobil' => $keterangan_mobil,
                                'tgl_input' => date('Ymd'),
                            );
                            $id_trans_dummy5 = $id_trans_mess++;
                        } else {
                            if(empty($atas_nama)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Nama harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($jabatan)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Jabatan harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($pic)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['PIC harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($email_eval)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Email Evaluasi harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($_POST['jumlah_tiket'][$a])) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Jumlah tiket harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($_POST['tanggal_jam_tiket'][$a])) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Tanggal dan Jam tiket harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($_POST['jumlah_kamar'][$a])) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Jumlah Kamar harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($tanggal_jam_masuk)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Tanggal dan Jam Masuk harus diisi']);
                                return redirect()->to("trans_add");
                            } else if(empty($tanggal_jam_keluar)) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Tanggal dan Jam Keluar harus diisi']);
                                return redirect()->to("trans_add");
                            } else if (empty($_POST['jumlah_mobil'][$a])) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Jumlah Mobil harus diisi']);
                                return redirect()->to('trans_add');
                            } else if (empty($_POST['tujuan_mobil'][$a])) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Tujuan harus diisi']);
                                return redirect()->to('trans_add');
                            } else if (empty($_POST['siap_di'][$a])) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Siap Di harus diisi']);
                                return redirect()->to('trans_add');
                            } else if (empty($_POST['tanggal_mobil'][$a])) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Tanggal harus diisi']);
                                return redirect()->to('trans_add');
                            } else if (empty($_POST['jam_siap'][$a])) {
                                if ($nama_hotel == "Mess Kx Jkt") {
                                    $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                                    
                                    foreach ($mess as $m => $mes) {
                                        if ($mes['terpakai'] == 0) {
                                            
                                        } else {
                                            $terpakai = [
                                                'id_mess' => 8,
                                                'terpakai' => $mes['terpakai'] - $jumlah_kamar[$a],
                                                'edited_at' => $timestamp,
                                            ];
                                            $this->m_mess->save($terpakai);
                                        }
                                    }
                                }
                                session()->setFlashdata('warning', ['Jam Siap harus diisi']);
                                return redirect()->to('trans_add');
                            }
                        }
                    }
                }

                //Tiket
                if (empty($trans_tik) && empty($tiket)) {
                    
                } else {
                    $this->m_trans->insertBatch($trans_tik);
                    $this->m_tiket->insertBatch($tiket);

                    // d($trans_tik);
                    // d($tiket);
                    // $email_info = $_POST['email_info'];
                    // $email_pengguna_info = $this->m_pengguna->whereIn('nama_pengguna', $email_info)->select('email_pengguna')->findAll();

                    // try {
                    //     //Server settings
                    //     // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    //     $mail->isSMTP();
                    //     $mail->Host       = 'mail.konimex.com';
                    //     $mail->SMTPAuth   = true;
                    //     $mail->Username   = PDLN_EMAIL;
                    //     $mail->Password   = PDLN_PASS;
                    //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    //     $mail->Port       = 587;
                    
                    //     //Recipients
                    //     $mail->setFrom('noreply@konimex.com');

                    //     foreach ($email_pengguna_info as $e => $ema) {
                    //         $mail->addAddress($ema['email_pengguna']);//Add a recipient
                    //     }
                    
                    //     //Content
                    //     // $link='https://konimex.com:447/pdln/listpb/pb/'.$id_transaksi;
                    //     $mail->Subject = 'Tes Program TABS (Bimo)';
                    //     $mail->Body    = nl2br("Tes kirim email harap diabaikan saja terima kasih.");
                    //     // $mail->Body    = nl2br("User bagian (").$niknmuser['niknm']."/".$nikuser['nik']."/".$nikuser['strorgnm'].(") telah selesai mengisi data PB, silahkan periksa data PB dengan cara klik link: $link");
                    
                    //     $mail->send();
                    //     // echo 'Message has been sent';
                    // } catch (Exception $e) {
                    //     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    // }
                }
                
                //Hotel
                if (empty($trans_ako) && empty($akomodasi)) {
                    
                } else {
                    $this->m_trans->insertBatch($trans_ako);
                    $this->m_akomodasi->insertBatch($akomodasi);

                    if (empty($tanggal_mess_ako)) {
                        
                    } else {
                        $this->m_tanggal_mess->insertBatch($tanggal_mess_ako);
                    }

                    if (empty($personil_mess_ako)) {
                        
                    } else {
                        $this->m_personil_mess->insertBatch($personil_mess_ako);
                    }
                    
                    // d($trans_ako);
                    // d($akomodasi);
                    // d($tanggal_mess_ako);
                    // d($personil_mess_ako);
                    // $email_info = $_POST['email_info'];
                    // $email_pengguna_info = $this->m_pengguna->whereIn('nama_pengguna', $email_info)->select('email_pengguna')->findAll();

                    // try {
                    //     //Server settings
                    //     // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    //     $mail->isSMTP();
                    //     $mail->Host       = 'mail.konimex.com';
                    //     $mail->SMTPAuth   = true;
                    //     $mail->Username   = PDLN_EMAIL;
                    //     $mail->Password   = PDLN_PASS;
                    //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    //     $mail->Port       = 587;
                    
                    //     //Recipients
                    //     $mail->setFrom('noreply@konimex.com');

                    //     foreach ($email_pengguna_info as $e => $ema) {
                    //         $mail->addAddress($ema['email_pengguna']);//Add a recipient
                    //     }
                    
                    //     //Content
                    //     // $link='https://konimex.com:447/pdln/listpb/pb/'.$id_transaksi;
                    //     $mail->Subject = 'Tes Program TABS (Bimo)';
                    //     $mail->Body    = nl2br("Tes kirim email harap diabaikan saja terima kasih.");
                    //     // $mail->Body    = nl2br("User bagian (").$niknmuser['niknm']."/".$nikuser['nik']."/".$nikuser['strorgnm'].(") telah selesai mengisi data PB, silahkan periksa data PB dengan cara klik link: $link");
                    
                    //     $mail->send();
                    //     // echo 'Message has been sent';
                    // } catch (Exception $e) {
                    //     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    // }
                }

                //Mobil
                if (empty($trans_tra) && empty($transportasi)) {
                    
                } else {
                    $this->m_trans->insertBatch($trans_tra);
                    $this->m_transportasi->insertBatch($transportasi);

                    // d($trans_tra);
                    // d($transportasi);
                    // $email_info = $_POST['email_info'];
                    // $email_pengguna_info = $this->m_pengguna->whereIn('nama_pengguna', $email_info)->select('email_pengguna')->findAll();

                    // try {
                    //     //Server settings
                    //     // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    //     $mail->isSMTP();
                    //     $mail->Host       = 'mail.konimex.com';
                    //     $mail->SMTPAuth   = true;
                    //     $mail->Username   = PDLN_EMAIL;
                    //     $mail->Password   = PDLN_PASS;
                    //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    //     $mail->Port       = 587;
                    
                    //     //Recipients
                    //     $mail->setFrom('noreply@konimex.com');

                    //     foreach ($email_pengguna_info as $e => $ema) {
                    //         $mail->addAddress($ema['email_pengguna']);//Add a recipient
                    //     }
                    
                    //     //Content
                    //     // $link='https://konimex.com:447/pdln/listpb/pb/'.$id_transaksi;
                    //     $mail->Subject = 'Tes Program TABS (Bimo)';
                    //     $mail->Body    = nl2br("Tes kirim email harap diabaikan saja terima kasih.");
                    //     // $mail->Body    = nl2br("User bagian (").$niknmuser['niknm']."/".$nikuser['nik']."/".$nikuser['strorgnm'].(") telah selesai mengisi data PB, silahkan periksa data PB dengan cara klik link: $link");
                    
                    //     $mail->send();
                    //     // echo 'Message has been sent';
                    // } catch (Exception $e) {
                    //     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    // }
                }

                //Tiket + Hotel
                if (empty($trans_ta) && empty($tiket_akomodasi_ta) && empty($akomodasi_tiket_ta)) {
                    
                } else {
                    $this->m_trans->insertBatch($trans_ta);
                    $this->m_tiket->insertBatch($tiket_akomodasi_ta);
                    $this->m_akomodasi->insertBatch($akomodasi_tiket_ta);

                    if (empty($tanggal_mess_ta)) {
                        
                    } else {
                        $this->m_tanggal_mess->insertBatch($tanggal_mess_ta);
                    }

                    if (empty($personil_mess_ta)) {
                        
                    } else {
                        $this->m_personil_mess->insertBatch($personil_mess_ta);
                    }

                    // d($trans_ta);
                    // d($tiket_akomodasi_ta);
                    // d($akomodasi_tiket_ta);
                    // d($tanggal_mess_ta);
                    // d($personil_mess_ta);
                    // $email_info = $_POST['email_info'];
                    // $email_pengguna_info = $this->m_pengguna->whereIn('nama_pengguna', $email_info)->select('email_pengguna')->findAll();

                    // try {
                    //     //Server settings
                    //     // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    //     $mail->isSMTP();
                    //     $mail->Host       = 'mail.konimex.com';
                    //     $mail->SMTPAuth   = true;
                    //     $mail->Username   = PDLN_EMAIL;
                    //     $mail->Password   = PDLN_PASS;
                    //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    //     $mail->Port       = 587;
                    
                    //     //Recipients
                    //     $mail->setFrom('noreply@konimex.com');

                    //     foreach ($email_pengguna_info as $e => $ema) {
                    //         $mail->addAddress($ema['email_pengguna']);//Add a recipient
                    //     }
                    
                    //     //Content
                    //     // $link='https://konimex.com:447/pdln/listpb/pb/'.$id_transaksi;
                    //     $mail->Subject = 'Tes Program TABS (Bimo)';
                    //     $mail->Body    = nl2br("Tes kirim email harap diabaikan saja terima kasih.");
                    //     // $mail->Body    = nl2br("User bagian (").$niknmuser['niknm']."/".$nikuser['nik']."/".$nikuser['strorgnm'].(") telah selesai mengisi data PB, silahkan periksa data PB dengan cara klik link: $link");
                    
                    //     $mail->send();
                    //     // echo 'Message has been sent';
                    // } catch (Exception $e) {
                    //     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    // }
                }

                //Tiket + Mobil
                if (empty($trans_tt) && empty($tiket_transportasi_tt) && empty($transportasi_tiket_tt)) {
                    
                } else {
                    $this->m_trans->insertBatch($trans_tt);
                    $this->m_tiket->insertBatch($tiket_transportasi_tt);
                    $this->m_transportasi->insertBatch($transportasi_tiket_tt);

                    // d($trans_tt);
                    // d($tiket_transportasi_tt);
                    // d($transportasi_tiket_tt);
                    // $email_info = $_POST['email_info'];
                    // $email_pengguna_info = $this->m_pengguna->whereIn('nama_pengguna', $email_info)->select('email_pengguna')->findAll();

                    // try {
                    //     //Server settings
                    //     // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    //     $mail->isSMTP();
                    //     $mail->Host       = 'mail.konimex.com';
                    //     $mail->SMTPAuth   = true;
                    //     $mail->Username   = PDLN_EMAIL;
                    //     $mail->Password   = PDLN_PASS;
                    //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    //     $mail->Port       = 587;
                    
                    //     //Recipients
                    //     $mail->setFrom('noreply@konimex.com');

                    //     foreach ($email_pengguna_info as $e => $ema) {
                    //         $mail->addAddress($ema['email_pengguna']);//Add a recipient
                    //     }
                    
                    //     //Content
                    //     // $link='https://konimex.com:447/pdln/listpb/pb/'.$id_transaksi;
                    //     $mail->Subject = 'Tes Program TABS (Bimo)';
                    //     $mail->Body    = nl2br("Tes kirim email harap diabaikan saja terima kasih.");
                    //     // $mail->Body    = nl2br("User bagian (").$niknmuser['niknm']."/".$nikuser['nik']."/".$nikuser['strorgnm'].(") telah selesai mengisi data PB, silahkan periksa data PB dengan cara klik link: $link");
                    
                    //     $mail->send();
                    //     // echo 'Message has been sent';
                    // } catch (Exception $e) {
                    //     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    // }
                }

                //Hotel + Mobil
                if (empty($trans_at) && empty($akomodasi_transportasi_at) && empty($transportasi_akomodasi_at)) {
                    
                } else {
                    $this->m_trans->insertBatch($trans_at);
                    $this->m_akomodasi->insertBatch($akomodasi_transportasi_at);
                    $this->m_transportasi->insertBatch($transportasi_akomodasi_at);

                    if (empty($tanggal_mess_at)) {
                        
                    } else {
                        $this->m_tanggal_mess->insertBatch($tanggal_mess_at);
                    }

                    if (empty($personil_mess_at)) {
                        
                    } else {
                        $this->m_personil_mess->insertBatch($personil_mess_at);
                    }

                    // d($trans_at);
                    // d($akomodasi_transportasi_at);
                    // d($transportasi_akomodasi_at);
                    // d($tanggal_mess_at);
                    // d($personil_mess_at);
                    // $email_info = $_POST['email_info'];
                    // $email_pengguna_info = $this->m_pengguna->whereIn('nama_pengguna', $email_info)->select('email_pengguna')->findAll();

                    // try {
                    //     //Server settings
                    //     // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    //     $mail->isSMTP();
                    //     $mail->Host       = 'mail.konimex.com';
                    //     $mail->SMTPAuth   = true;
                    //     $mail->Username   = PDLN_EMAIL;
                    //     $mail->Password   = PDLN_PASS;
                    //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    //     $mail->Port       = 587;
                    
                    //     //Recipients
                    //     $mail->setFrom('noreply@konimex.com');

                    //     foreach ($email_pengguna_info as $e => $ema) {
                    //         $mail->addAddress($ema['email_pengguna']);//Add a recipient
                    //     }
                    
                    //     //Content
                    //     // $link='https://konimex.com:447/pdln/listpb/pb/'.$id_transaksi;
                    //     $mail->Subject = 'Tes Program TABS (Bimo)';
                    //     $mail->Body    = nl2br("Tes kirim email harap diabaikan saja terima kasih.");
                    //     // $mail->Body    = nl2br("User bagian (").$niknmuser['niknm']."/".$nikuser['nik']."/".$nikuser['strorgnm'].(") telah selesai mengisi data PB, silahkan periksa data PB dengan cara klik link: $link");
                    
                    //     $mail->send();
                    //     // echo 'Message has been sent';
                    // } catch (Exception $e) {
                    //     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    // }
                }
                
                //Tiket + Hotel + Mobil
                if (empty($trans_tat) && empty($tiket_akomodasi_transportasi) && empty($akomodasi_tiket_transportasi) && empty($transportasi_tiket_akomodasi)) {
                    
                } else {
                    $this->m_trans->insertBatch($trans_tat);
                    $this->m_tiket->insertBatch($tiket_akomodasi_transportasi);
                    $this->m_akomodasi->insertBatch($akomodasi_tiket_transportasi);
                    $this->m_transportasi->insertBatch($transportasi_tiket_akomodasi);

                    if (empty($tanggal_mess_tat)) {
                        
                    } else {
                        $this->m_tanggal_mess->insertBatch($tanggal_mess_tat);
                    }

                    if (empty($personil_mess_tat)) {
                        
                    } else {
                        $this->m_personil_mess->insertBatch($personil_mess_tat);
                    }

                    // d($trans_tat);
                    // d($tiket_akomodasi_transportasi);
                    // d($akomodasi_tiket_transportasi);
                    // d($transportasi_tiket_akomodasi);
                    // d($tanggal_mess_tat);
                    // d($personil_mess_tat);
                    // $email_info = $_POST['email_info'];
                    // $email_pengguna_info = $this->m_pengguna->whereIn('nama_pengguna', $email_info)->select('email_pengguna')->findAll();

                    // try {
                    //     //Server settings
                    //     // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    //     $mail->isSMTP();
                    //     $mail->Host       = 'mail.konimex.com';
                    //     $mail->SMTPAuth   = true;
                    //     $mail->Username   = PDLN_EMAIL;
                    //     $mail->Password   = PDLN_PASS;
                    //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    //     $mail->Port       = 587;
                    
                    //     //Recipients
                    //     $mail->setFrom('noreply@konimex.com');

                    //     foreach ($email_pengguna_info as $e => $ema) {
                    //         $mail->addAddress($ema['email_pengguna']);//Add a recipient
                    //     }
                    
                    //     //Content
                    //     // $link='https://konimex.com:447/pdln/listpb/pb/'.$id_transaksi;
                    //     $mail->Subject = 'Tes Program TABS (Bimo)';
                    //     $mail->Body    = nl2br("Tes kirim email harap diabaikan saja terima kasih.");
                    //     // $mail->Body    = nl2br("User bagian (").$niknmuser['niknm']."/".$nikuser['nik']."/".$nikuser['strorgnm'].(") telah selesai mengisi data PB, silahkan periksa data PB dengan cara klik link: $link");
                    
                    //     $mail->send();
                    //     // echo 'Message has been sent';
                    // } catch (Exception $e) {
                    //     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    // }
                }
                
                session()->setFlashdata('success', ('Data berhasil ditambahkan'));
                return redirect()->to("trans");
            } else {
                session()->setFlashdata('warning', ['Silahkan lengkapi data terlebih dahulu']);
                return redirect()->to('trans_add');
            }
        }

        //Tiket
        $id_bagian = session()->get('id_bagian');
        $pengguna = $this->m_detail_pengguna->where('detail_pengguna.id_bagian', $id_bagian)->join('pengguna', 'pengguna.id_pengguna = detail_pengguna.id_pengguna', 'left')->join('jabatan', 'jabatan.id_jabatan = detail_pengguna.id_jabatan', 'left')->select('detail_pengguna.id_pengguna, nama_pengguna, nama_jabatan, jenis_kelamin')->orderBy('nama_pengguna', 'asc')->groupBy('id_pengguna')->findAll();
        $pool = $this->m_pool->select('nama_pool')->findAll();
        $bus = $this->m_vendor->where('jenis_vendor', 'B')->select('id_vendor, nama_vendor')->orderBy('nama_vendor', 'asc')->findAll();
        $kereta = $this->m_vendor->where('jenis_vendor', 'K')->select('id_vendor, nama_vendor')->orderBy('nama_vendor', 'asc')->findAll();
        $pesawat = $this->m_vendor->where('jenis_vendor', 'P')->select('id_vendor, nama_vendor')->orderBy('nama_vendor', 'asc')->findAll();
        $travel = $this->m_vendor->where('jenis_vendor', 'T')->select('id_vendor, nama_vendor')->orderBy('nama_vendor', 'asc')->findAll();
        $kapal_laut = $this->m_vendor->where('jenis_vendor', 'Ka')->select('id_vendor, nama_vendor')->orderBy('nama_vendor', 'asc')->findAll();
        $bandara = $this->m_pemberhentian->where('jenis_pemberhentian', 'B')->select('id_pemberhentian, nama_pemberhentian, nama_kota')->orderBy('nama_pemberhentian', 'asc')->findAll();
        $pelabuhan = $this->m_pemberhentian->where('jenis_pemberhentian', 'P')->select('id_pemberhentian, nama_pemberhentian, nama_kota')->orderBy('nama_pemberhentian', 'asc')->findAll();
        $stasiun = $this->m_pemberhentian->where('jenis_pemberhentian', 'S')->select('id_pemberhentian, nama_pemberhentian, nama_kota')->orderBy('nama_pemberhentian', 'asc')->findAll();
        $terminal = $this->m_pemberhentian->where('jenis_pemberhentian', 'T')->select('id_pemberhentian, nama_pemberhentian, nama_kota')->orderBy('nama_pemberhentian', 'asc')->findAll();
        $negara = $this->m_negara->select('id_negara, nama_negara')->orderBy('nama_negara', 'asc')->findAll();
        $kota = $this->m_kota->select('id_kota, id_negara, nama_kota')->orderBy('nama_kota', 'asc')->findAll();

        //Hotel
        $hotel = $this->m_hotel->join('kota', 'kota.id_kota = hotel.id_kota', 'left')->join('detail_hotel', 'detail_hotel.id_hotel = hotel.id_hotel', 'left')->where('jenis_kamar !=', null)->where('nama_hotel !=', 'Mess Kx Jkt')->select('hotel.id_hotel, nama_hotel, nama_kota, alamat_hotel, telp_hotel, email_hotel, bintang_hotel, jenis_kamar, price_kamar, tgl_valid')->orderBy('nama_hotel', 'asc')->findAll();
        $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
        $hotel_jkt = $this->m_hotel->join('kota', 'kota.id_kota = hotel.id_kota', 'left')->join('detail_hotel', 'detail_hotel.id_hotel = hotel.id_hotel', 'left')->where('jenis_kamar !=', null)->where('nama_kota', 'Jakarta')->select('hotel.id_hotel, nama_hotel, nama_kota, alamat_hotel, telp_hotel, email_hotel, bintang_hotel, jenis_kamar, price_kamar, tgl_valid')->orderBy('nama_hotel', 'asc')->findAll();
        // $mess = $this->m_mess->select('sum(kapasitas_kamar) as total_kamar')->findAll();
        
        //Mobil
        $jenis_kendaraan = $this->m_jenis_kendaraan->select('jenis_kendaraan')->orderBy('id_jenis_kendaraan', 'asc')->findAll();

        //akomodasi
        $tanggal_mess = $this->m_tanggal_mess->where('status', 0)->where('batal', 0)->select('tanggal_mess, sum(jumlah_personil) as sum')->groupBy('tanggal_mess')->orderBy('tanggal_mess', 'asc')->findAll();//->having('sum(jumlah_kamar) =', 18)

        foreach ($tanggal_mess as $ta => $tam) {
            if ($sum == 18) {
                $merged[] = $tam['tanggal_mess'];
            }
        }

        // $tanggal_masuk = $this->m_akomodasi->where('id_hotel', 158)->where('tanggal_jam_keluar >', $timestamp)->where('batal_akomodasi <', 2)->select('tanggal_jam_masuk, tanggal_jam_keluar, sum(jumlah_kamar) as sum')->groupBy('tanggal_jam_masuk')->orderBy('tanggal_jam_masuk', 'asc')->findAll();//->having('sum(jumlah_kamar) =', 18)
        
        // foreach ($tanggal_masuk as $tp => $tang_mas) {
        //     // $list_tanggal = array(tanggal($value['tanggal_jam_masuk']), tanggal($value['tanggal_jam_keluar']));

        //     // Declare two dates
        //     $Date1 = tanggal($tang_mas['tanggal_jam_masuk']);
        //     $Date2 = tanggal($tang_mas['tanggal_jam_keluar']);

        //     // Declare an empty array
        //     $date_arr = array();
                
        //     // Use strtotime function
        //     $Variable1 = strtotime($Date1);
        //     $Variable2 = strtotime($Date2);
            
        //     // Use for loop to store dates into array
        //     // 86400 sec = 24 hrs = 60*60*24 = 1 day
        //     for ($currentDate = $Variable1; $currentDate <= $Variable2;
        //                                     $currentDate += (86400)) {
                                                
        //     $Store = date('Y-m-d', $currentDate);
        //     $date_arr[] = $Store;
        //     }
        //     $tanggal_arr[] = $date_arr;

        //     if ($tang_mas['sum'] == 18) {
        //         $merged[] = $Date1;
        //     } else {
        //         $merged = array();
        //     }
            
        //     // Display the dates in array format
        //     // d($date_arr);
        // }
        // // $merged = array_unique(call_user_func_array('array_merge', $tanggal_arr));
        if (empty($merged)) {
            $merged = array();
        } else {

        }

        $data = [
            //Tiket
            'pengguna' => $pengguna,
            'pool' => $pool,
            'bus' => $bus,
            'kereta' => $kereta,
            'pesawat' => $pesawat,
            'travel' => $travel,
            'kapal_laut' => $kapal_laut,
            'bandara' => $bandara,
            'pelabuhan' => $pelabuhan,
            'stasiun' => $stasiun,
            'terminal' => $terminal,
            'negara' => $negara,
            'kota' => $kota,

            //Hotel
            'hotel' => $hotel,
            'mess' => $mess,
            'hotel_jkt' => $hotel_jkt,

            //Mobil
            'jenis_kendaraan' => $jenis_kendaraan,
            'merged' => $merged,
            'tanggal_mess' => $tanggal_mess
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_user', $data);
        echo view('transaksi/v_trans_add', $data);
        echo view('ui/v_footer', $data);
        // d(session()->get(''));
    }

    public function permintaan_batal_tiket($id_trans, $id_tiket)
    {
        $data = [];

        $id_pool = session()->get('pool_pengguna');
        $admin_gs = session()->get('admin_gs');
        $id_detail_pengguna = session()->get('id_detail_pengguna');

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

        $tiket = $this->m_tiket->where('trans.id_trans', $id_trans)->where('batal_tiket <', 2)->where('trans.id_detail_pengguna', $id_detail_pengguna)->join('trans', 'trans.id_trans = tiket.id_trans', 'left')->join('pool', 'pool.id_pool = tiket.id_pool', 'left')->select('tiket.id_tiket, tiket.id_trans, tiket.id_pool, trans.id_detail_pengguna, status_tiket')->findAll();

        if (empty($tiket)) {
            session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini']);
            return redirect()->to('trans');
        }

        if($this->request->getMethod() == 'post') {
            $trans = [
                'id_trans' => $id_trans,
                'alasan_batal' => $this->request->getVar('alasan_batal'),
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $tiket = [
                'id_tiket' => $id_tiket,
                'batal_tiket' => 1,
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $this->m_trans->save($trans);
            $this->m_tiket->save($tiket);
            session()->setFlashdata('success', ('Permintaan pembatalan transaksi berhasil'));
            return redirect()->to("trans");
        }

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_user', $data);
        echo view('transaksi/v_batal_tiket', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function permintaan_batal_akomodasi($id_trans, $id_akomodasi)
    {
        $data = [];

        $id_pool = session()->get('pool_pengguna');
        $admin_gs = session()->get('admin_gs');
        $id_detail_pengguna = session()->get('id_detail_pengguna');

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

        $akomodasi = $this->m_akomodasi->where('trans.id_trans', $id_trans)->where('batal_akomodasi <', 2)->where('trans.id_detail_pengguna', $id_detail_pengguna)->join('trans', 'trans.id_trans = akomodasi.id_trans', 'left')->join('pool', 'pool.id_pool = akomodasi.id_pool', 'left')->select('akomodasi.id_akomodasi, akomodasi.id_trans, akomodasi.id_pool, trans.id_detail_pengguna, status_akomodasi')->findAll();

        if (empty($akomodasi)) {
            session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini']);
            return redirect()->to('trans');
        }

        if($this->request->getMethod() == 'post') {
            $trans = [
                'id_trans' => $id_trans,
                'alasan_batal' => $this->request->getVar('alasan_batal'),
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $akomodasi = [
                'id_akomodasi' => $id_akomodasi,
                'batal_akomodasi' => 1,
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $this->m_trans->save($trans);
            $this->m_akomodasi->save($akomodasi);
            session()->setFlashdata('success', ('Permintaan pembatalan transaksi berhasil'));
            return redirect()->to("trans");
        }

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_user', $data);
        echo view('transaksi/v_batal_akomodasi', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function permintaan_batal_transport_antar($id_trans, $id_transportasi)
    {
        $data = [];

        $id_pool = session()->get('pool_pengguna');
        $admin_gs = session()->get('admin_gs');
        $id_detail_pengguna = session()->get('id_detail_pengguna');

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

        $transportasi = $this->m_transportasi->where('trans.id_trans', $id_trans)->where('batal_transportasi <', 2)->where('trans.id_detail_pengguna', $id_detail_pengguna)->join('trans', 'trans.id_trans = transportasi.id_trans', 'left')->join('pool', 'pool.id_pool = transportasi.id_pool', 'left')->select('transportasi.id_transportasi, transportasi.id_trans, transportasi.id_pool, trans.id_detail_pengguna, status_mobil')->findAll();

        if (empty($transportasi)) {
            session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini']);
            return redirect()->to('trans');
        }

        if($this->request->getMethod() == 'post') {
            $trans = [
                'id_trans' => $id_trans,
                'alasan_batal' => $this->request->getVar('alasan_batal'),
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $transportasi = [
                'id_transportasi' => $id_transportasi,
                'batal_transportasi' => 1,
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $this->m_trans->save($trans);
            $this->m_transportasi->save($transportasi);
            session()->setFlashdata('success', ('Permintaan pembatalan transaksi berhasil'));
            return redirect()->to("trans");
        }

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_user', $data);
        echo view('transaksi/v_batal_transport', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function permintaan_batal_transport_jemput($id_trans, $id_transportasi, $id_transportasi_jemput)
    {
        $data = [];

        $id_pool = session()->get('pool_pengguna');
        $admin_gs = session()->get('admin_gs');
        $id_detail_pengguna = session()->get('id_detail_pengguna');

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

        $transportasi_jemput = $this->m_transportasi_jemput->where('trans.id_trans', $id_trans)->where('batal_transportasi_jemput <', 2)->where('trans.id_detail_pengguna', $id_detail_pengguna)->join('trans', 'trans.id_trans = transportasi_jemput.id_trans', 'left')->join('pool', 'pool.id_pool = transportasi_jemput.id_pool', 'left')->select('transportasi_jemput.id_transportasi, id_transportasi_jemput, transportasi_jemput.id_trans, transportasi_jemput.id_pool, trans.id_detail_pengguna, transportasi_jemput.status_mobil')->findAll();

        if (empty($transportasi_jemput)) {
            session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini']);
            return redirect()->to('trans');
        }

        if($this->request->getMethod() == 'post') {
            $trans = [
                'id_trans' => $id_trans,
                'alasan_batal' => $this->request->getVar('alasan_batal'),
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $transportasi = [
                'id_transportasi' => $id_transportasi,
                'batal_transportasi' => 1,
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $transportasi_jemput = [
                'id_transportasi_jemput' => $id_transportasi_jemput,
                'batal_transportasi_jemput' => 1,
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $this->m_trans->save($trans);
            $this->m_transportasi->save($transportasi);
            $this->m_transportasi_jemput->save($transportasi_jemput);
            session()->setFlashdata('success', ('Permintaan pembatalan transaksi berhasil'));
            return redirect()->to("trans");
        }

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_user', $data);
        echo view('transaksi/v_batal_transport', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function tiket_admin()
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');
        $id_pool = session()->get('pool_pengguna');

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

        if($this->request->getVar('aksi') == 'confirm' && $this->request->getVar('id_trans') && $this->request->getVar('id_tiket')) {
            $tiket_id = $this->m_tiket->tiket_id($this->request->getVar('id_tiket'));
            if($tiket_id['id_tiket']) {//memastikan bahwa ada data
                $tiket_confirm = $this->m_tiket->where('tiket.id_tiket', $this->request->getVar('id_tiket'))->where('batal_tiket <', 2)->where('tiket.id_pool', $id_pool)->join('trans', 'trans.id_trans = tiket.id_trans', 'left')->join('vendor', 'vendor.id_vendor = tiket.id_vendor', 'left')->join('pemberhentian', 'pemberhentian.id_pemberhentian = tiket.id_pemberhentian', 'left')->join('pool', 'pool.id_pool = tiket.id_pool', 'left')->select('tiket.id_tiket, tiket.id_trans, tiket.id_pool, harga_tiket')->findAll();

                if (empty($tiket_confirm[0]['harga_tiket']) || $tiket_confirm[0]['harga_tiket'] == '0.00') {
                    session()->setFlashdata('success', ('Masukkan harga tiket'));
                    return redirect()->to("harga_tiket/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_tiket'));
                } else {
                    $tiket = [
                        'id_tiket' => $this->request->getVar('id_tiket'),
                        'status_tiket' => 1,
                        'edited_by' => session()->get('login_by'),
                        'edited_at' => $timestamp,
                    ];
                    $this->m_tiket->save($tiket);
                    session()->setFlashdata('success', ('Transaksi telah dikonfirmasi'));
                    return redirect()->to("tiket_admin");
                }
            }
        }

        if($this->request->getVar('aksi') == 'batal' && $this->request->getVar('id_trans') && $this->request->getVar('id_tiket')) {
            $tiket_id = $this->m_tiket->tiket_id($this->request->getVar('id_tiket'));
            if($tiket_id['id_tiket']) {//memastikan bahwa ada data
                session()->setFlashdata('success', ('Masukkan alasan batal'));
                return redirect()->to("batal_tiket/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_tiket'));
            }
        }

        if($this->request->getVar('aksi') == 'batal_confirm' && $this->request->getVar('id_trans') && $this->request->getVar('id_tiket')) {
            $tiket_id = $this->m_tiket->tiket_id($this->request->getVar('id_tiket'));
            if($tiket_id['id_tiket']) {//memastikan bahwa ada data
                session()->setFlashdata('success', ('Masukkan alasan batal'));
                return redirect()->to("batal_tiket_confirm/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_tiket'));
            }
        }

        if($this->request->getVar('aksi') == 'tolak_permintaan_batal' && $this->request->getVar('id_trans') && $this->request->getVar('id_tiket')) {
            $tiket_id = $this->m_tiket->tiket_id($this->request->getVar('id_tiket'));
            if($tiket_id['id_tiket']) {//memastikan bahwa ada data
                $trans = [
                    'id_trans' => $this->request->getVar('id_trans'),
                    'alasan_batal' => null,
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];
    
                $tiket = [
                    'id_tiket' => $this->request->getVar('id_tiket'),
                    'batal_tiket' => 0,
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];
    
                $this->m_trans->save($trans);
                $this->m_tiket->save($tiket);
                session()->setFlashdata('success', ('Transaksi tidak jadi dibatalkan'));
                return redirect()->to("tiket_admin");
            }
        }

        if($this->request->getVar('aksi') == 'refund' && $this->request->getVar('id_trans') && $this->request->getVar('id_tiket')) {
            $tiket_id = $this->m_tiket->tiket_id($this->request->getVar('id_tiket'));
            if($tiket_id['id_tiket']) {//memastikan bahwa ada data
                session()->setFlashdata('success', ('Masukkan nilai refund'));
                return redirect()->to("harga_tiket/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_tiket'));
            }
        }

        $id_bagian = session()->get('id_bagian');
        $id_detail_pengguna = session()->get('id_detail_pengguna');
        
        $trans = $this->m_trans->where('tanggal_jam_tiket >', $timestamp)->where('batal_tiket <', 2)->where('tiket.id_pool', $id_pool)->join('tiket', 'tiket.id_trans = trans.id_trans', 'left')->select('trans.id_trans, tiket.id_pool, tanggal_jam_tiket, trans.created_at, tiket.created_at')->orderBy('tiket.created_at', 'desc')->findAll();
        
        $tiket = $this->m_tiket->where('tiket.tanggal_jam_tiket >', $timestamp)->where('batal_tiket <', 2)->where('tiket.id_pool', $id_pool)->join('trans', 'trans.id_trans = tiket.id_trans', 'left')->join('vendor', 'vendor.id_vendor = tiket.id_vendor', 'left')->join('pemberhentian', 'pemberhentian.id_pemberhentian = tiket.id_pemberhentian', 'left')->join('pool', 'pool.id_pool = tiket.id_pool', 'left')->select('tiket.id_tiket, tiket.id_trans, tiket.id_pool, id_keberangkatan, nama_vendor, nama_pemberhentian, peminta, nama_pool, pic, atas_nama, jabatan, jumlah_tiket, pembayaran, tiket.tanggal_jam_tiket, dari_tiket, tujuan_tiket, keterangan_tiket, status_tiket, batal_tiket, harga_tiket, tiket.created_at')->findAll();

        $trans_batal = $this->m_trans->where('batal_tiket', 3)->where('tiket.id_pool', $id_pool)->join('tiket', 'tiket.id_trans = trans.id_trans', 'left')->select('trans.id_trans, tiket.id_pool, tanggal_jam_tiket, trans.created_at, tiket.created_at')->orderBy('tiket.created_at', 'desc')->findAll();

        $tiket_batal = $this->m_tiket->where('batal_tiket', 3)->where('tiket.id_pool', $id_pool)->join('trans', 'trans.id_trans = tiket.id_trans', 'left')->join('vendor', 'vendor.id_vendor = tiket.id_vendor', 'left')->join('pemberhentian', 'pemberhentian.id_pemberhentian = tiket.id_pemberhentian', 'left')->join('pool', 'pool.id_pool = tiket.id_pool', 'left')->select('tiket.id_tiket, tiket.id_trans, tiket.id_pool, id_keberangkatan, nama_vendor, nama_pemberhentian, peminta, nama_pool, pic, atas_nama, jabatan, jumlah_tiket, pembayaran, tiket.tanggal_jam_tiket, dari_tiket, tujuan_tiket, keterangan_tiket, status_tiket, batal_tiket, harga_tiket, refund_tiket, tiket.created_at')->findAll();

        $berangkat_tiket = $this->m_pemberhentian->select('id_pemberhentian, nama_pemberhentian')->findAll();

        $data = [
            'trans' => $trans,
            'tiket' => $tiket,
            'trans_batal' => $trans_batal,
            'tiket_batal' => $tiket_batal,
            'berangkat_tiket' => $berangkat_tiket,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_tiket_admin', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function harga_tiket($id_trans, $id_tiket)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');
        $id_pool = session()->get('pool_pengguna');

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

        $tiket = $this->m_tiket->where('trans.id_trans', $id_trans)->where('batal_tiket =', 0)->where('tiket.id_pool', $id_pool)->orwhere('trans.id_trans', $id_trans)->where('batal_tiket =', 3)->where('tiket.id_pool', $id_pool)->join('trans', 'trans.id_trans = tiket.id_trans', 'left')->join('pool', 'pool.id_pool = tiket.id_pool', 'left')->select('tiket.id_tiket, tiket.id_trans, tiket.id_pool, status_tiket, batal_tiket')->findAll();

        if (empty($tiket)) {
            session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini']);
            return redirect()->to('tiket_admin');
        }

        if ($tiket[0]['status_tiket'] == 1) {
            if ($tiket[0]['batal_tiket'] == 3) {
                # code...
            } else {
                session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini karena telah dikonfirmasi']);
                return redirect()->to('tiket_admin');
            }
        }

        if($this->request->getMethod() == 'post') {
            if (empty($this->request->getVar('nilai_refund'))) {
                $biaya = $this->request->getVar('harga_tiket');
                $comma = ',';
                $number = preg_replace('/[^0-9\\-]+/','', $biaya);
                if( strpos($biaya, $comma) !== false ) {
                    $string = $number/100;
                } else {
                    $string = $number;
                }

                $tiket = [
                    'id_tiket' => $id_tiket,
                    'status_tiket' => 1,
                    'harga_tiket' => $string,
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];
                $this->m_tiket->save($tiket);
                session()->setFlashdata('success', ('Transaksi telah dikonfirmasi'));
                return redirect()->to("tiket_admin");
            } else {
                $biaya = $this->request->getVar('nilai_refund');
                $comma = ',';
                $number = preg_replace('/[^0-9\\-]+/','', $biaya);
                if( strpos($biaya, $comma) !== false ) {
                    $string = $number/100;
                } else {
                    $string = $number;
                }

                $tiket = [
                    'id_tiket' => $id_tiket,
                    'batal_tiket' => 4,
                    'refund_tiket' => $string,//sampe sini kemaren rabu
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];
                $this->m_tiket->save($tiket);
                session()->setFlashdata('success', ('Nilai refund berhasil diinputkan'));
                return redirect()->to("tiket_admin");
            }
        }

        $data = [
            'tiket' => $tiket,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_harga_tiket', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function batal_tiket($id_trans, $id_tiket)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');
        $id_pool = session()->get('pool_pengguna');

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

        $tiket = $this->m_tiket->where('trans.id_trans', $id_trans)->where('batal_tiket <', 2)->where('tiket.id_pool', $id_pool)->join('trans', 'trans.id_trans = tiket.id_trans', 'left')->join('pool', 'pool.id_pool = tiket.id_pool', 'left')->select('tiket.id_tiket, tiket.id_trans, tiket.id_pool, status_tiket')->findAll();

        if (empty($tiket)) {
            session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini']);
            return redirect()->to('tiket_admin');
        }

        if($this->request->getMethod() == 'post') {
            $trans = [
                'id_trans' => $id_trans,
                'alasan_batal' => $this->request->getVar('alasan_batal'),
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $tiket = [
                'id_tiket' => $id_tiket,
                'batal_tiket' => 2,
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $this->m_trans->save($trans);
            $this->m_tiket->save($tiket);
            session()->setFlashdata('success', ('Transaksi telah dibatalkan'));
            return redirect()->to("tiket_admin");
        }

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_batal_tiket', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function batal_tiket_confirm($id_trans, $id_tiket)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');
        $id_pool = session()->get('pool_pengguna');

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

        $tiket = $this->m_tiket->where('trans.id_trans', $id_trans)->where('batal_tiket <', 2)->where('tiket.id_pool', $id_pool)->join('trans', 'trans.id_trans = tiket.id_trans', 'left')->join('pool', 'pool.id_pool = tiket.id_pool', 'left')->select('tiket.id_tiket, tiket.id_trans, tiket.id_pool, status_tiket')->findAll();

        if (empty($tiket)) {
            session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini']);
            return redirect()->to('tiket_admin');
        }

        if($this->request->getMethod() == 'post') {
            $trans = [
                'id_trans' => $id_trans,
                'alasan_batal' => $this->request->getVar('alasan_batal'),
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $tiket = [
                'id_tiket' => $id_tiket,
                'batal_tiket' => 3,
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $this->m_trans->save($trans);
            $this->m_tiket->save($tiket);
            session()->setFlashdata('success', ('Transaksi telah dibatalkan'));
            return redirect()->to("tiket_admin");
        }

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_batal_tiket', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function edit_tiket($id_trans, $id_tiket)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');
        $id_pool = session()->get('pool_pengguna');

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

        $id_bagian = session()->get('id_bagian');
        $id_detail_pengguna = session()->get('id_detail_pengguna');

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar();

            //Tiket
            $nama_tiket = $_POST['tiket'];
            $nama_pool = $_POST['gs_tiket'];
            $keberangkatan = $_POST['keberangkatan'];
            $pemberhentian = $_POST['pemberhentian'];

            if ($_POST['pilihan_tiket'] == 0) {
                session()->setFlashdata('warning', ['Pilih armada transportasi terlebih dahulu']);
                return redirect()->to("edit_tiket/".$id_trans."/".$id_tiket);
            } else if ($_POST['pilihan_tiket'] == "Travel") {
                $id_keberangkatan = null;
                $id_pemberhentian = null;
                $dari_tiket = $_POST['keberangkatan'];
                $tujuan_tiket = $_POST['pemberhentian'];
            } else {
                $nama_keberangkatan = substr($keberangkatan, 0, strpos($keberangkatan, " - "));
                $dari_tiket = substr($keberangkatan, strpos($keberangkatan, " - ")+3);
                $id_berangkat = $this->m_pemberhentian->where('nama_pemberhentian', $nama_keberangkatan)->select('id_pemberhentian')->findAll();
                $id_keberangkatan = $id_berangkat[0]['id_pemberhentian'];
                
                $nama_pemberhentian = substr($pemberhentian, 0, strpos($pemberhentian, " - "));
                $tujuan_tiket = substr($pemberhentian, strpos($pemberhentian, " - ")+3);
                $id_berhenti = $this->m_pemberhentian->where('nama_pemberhentian', $nama_pemberhentian)->select('id_pemberhentian')->findAll();
                $id_pemberhentian = $id_berhenti[0]['id_pemberhentian'];
            }

            if ($_POST['keberangkatan'] == $_POST['pemberhentian']) {
                session()->setFlashdata('warning', ['Keberangkatan dan Pemberhentian Tiket tidak boleh sama']);
                return redirect()->to("edit_tiket/".$id_trans."/".$id_tiket);
            }

            $biaya = $_POST['harga_tiket'];
            $comma = ',';
            $number = preg_replace('/[^0-9\\-]+/','', $biaya);
            if ($number == null) {
                $string = 0;
            } else {
                if( strpos($biaya, $comma) !== false ) {
                    $string = $number/100;
                } else {
                    $string = $number;
                }
            }
            
            $vendor = $this->m_vendor->where('nama_vendor', $nama_tiket)->select('id_vendor')->findAll();
            $pool_tiket = $this->m_pool->where('nama_pool', $nama_pool)->select('id_pool')->findAll();

            if ($_POST['tamu'] == 'Karyawan Konimex') {
                if(!empty($_POST['nama_select'])) {                
                    for($a = 0; $a < count($_POST['nama_select']); $a++) {
                        $nama_select_h = $_POST["nama_select"];
                        $atas_namaa = implode(" - ", $nama_select_h);

                        $atas_namas = explode(" - ", $atas_namaa);

                        $jumlah = count($atas_namas);

                        for ($i=0; $i<$jumlah; $i++){
                            if ($i % 3 == 0){
                                $atas_namat[$i] = $atas_namas[$i];
                                $jabatant[$i] = $atas_namas[$i+1];
                                $jenis_kelamint[$i] = $atas_namas[$i+2];
                            }
                        }
                        $atas_nama = implode(", ", $atas_namat);
                        $jabatan = implode(", ", $jabatant);
                        $jenis_kelamin = implode(", ", $jenis_kelamint);
                    }
                }

                $pic = $atas_nama;

                $pembayaran = $_POST['pembayaran'];
                if($pembayaran == 'Company Acc'){
                    $pembayaran = 'k';
                } else if($pembayaran == 'Personal Acc'){
                    $pembayaran = 'p';
                }
            } else {
                $atas_nama = $_POST['nama_inputan'];
                $jabatan = $_POST['jabatan_inputan'];
                $jenis_kelamin = null;
                $pic = $atas_nama;

                $pembayaran = $_POST['pembayaran_inputan'];
                if($pembayaran == 'Company Acc'){
                    $pembayaran = 'k';
                } else if($pembayaran == 'Personal Acc'){
                    $pembayaran = 'p';
                }
                $atas_nama = ucwords($atas_nama);
                $jabatan = ucwords($jabatan);
            }
            if (empty($atas_nama)) {
                session()->setFlashdata('warning', ['Nama harus diisi']);
                redirect()->to("edit_tiket/".$id_trans."/".$id_akomodasi);
            }
            if (empty($jabatan)) {
                session()->setFlashdata('warning', ['Jabatan harus diisi']);
                redirect()->to("edit_tiket/".$id_trans."/".$id_akomodasi);
            }

            $keterangan_tiket = $_POST['keterangan_tiket'];
            if(empty($keterangan_tiket)){
                $keterangan_tiket = null;
            }

            if(!empty($atas_nama) && !empty($jabatan) && !empty($_POST['jumlah_tiket']) && !empty($_POST['tanggal_jam_tiket'])) {
                $trans = [
                    'id_trans' => $id_trans,
                    'pic' => $pic,
                    'tamu' => $_POST['tamu'],
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];

                $tiket = [
                    'id_tiket' => $id_tiket,
                    'id_vendor' => $vendor[0]['id_vendor'],
                    'id_keberangkatan' => $id_keberangkatan,
                    'id_pemberhentian' => $id_pemberhentian,
                    'id_pool' => $pool_tiket[0]['id_pool'],
                    'atas_nama' => $atas_nama,
                    'jabatan' => $jabatan,
                    'jenis_kelamin' => $jenis_kelamin,
                    'jumlah_tiket' => $_POST['jumlah_tiket'],
                    'pembayaran' => $pembayaran,
                    'harga_tiket' => $string,
                    'tanggal_jam_tiket' => $_POST['tanggal_jam_tiket'],
                    'dari_tiket' => $dari_tiket,
                    'tujuan_tiket' => $tujuan_tiket,
                    'keterangan_tiket' => $keterangan_tiket,
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];
            } else {
                if(empty($atas_nama)) {
                    session()->setFlashdata('warning', ['Nama harus diisi']);
                    return redirect()->to("edit_tiket/".$id_trans."/".$id_tiket);
                } else if(empty($jabatan)) {
                    session()->setFlashdata('warning', ['Jabatan harus diisi']);
                    return redirect()->to("edit_tiket/".$id_trans."/".$id_tiket);
                } else if(empty($_POST['jumlah_tiket'])) {
                    session()->setFlashdata('warning', ['Jumlah tiket harus diisi']);
                    return redirect()->to("edit_tiket/".$id_trans."/".$id_tiket);
                } else if(empty($_POST['tanggal_jam_tiket'])) {
                    session()->setFlashdata('warning', ['Tanggal dan Jam tiket harus diisi']);
                    return redirect()->to("edit_tiket/".$id_trans."/".$id_tiket);
                }
            }
            
            $this->m_trans->save($trans);
            $this->m_tiket->save($tiket);
            session()->setFlashdata('success', ('Data berhasil diedit'));
            return redirect()->to("tiket_admin");
        }
        
        $trans = $this->m_trans->where('trans.id_trans', $id_trans)->where('batal_tiket =', 0)->where('tiket.id_pool', $id_pool)->join('tiket', 'tiket.id_trans = trans.id_trans', 'left')->select('trans.id_trans, trans.id_detail_pengguna, tiket.id_pool, tanggal_jam_tiket, id_bagian, trans.created_at')->orderBy('created_at', 'desc')->findAll();
        
        $tiket = $this->m_tiket->where('trans.id_trans', $id_trans)->where('batal_tiket =', 0)->where('tiket.id_pool', $id_pool)->join('trans', 'trans.id_trans = tiket.id_trans', 'left')->join('vendor', 'vendor.id_vendor = tiket.id_vendor', 'left')->join('pemberhentian', 'pemberhentian.id_pemberhentian = tiket.id_pemberhentian', 'left')->join('pool', 'pool.id_pool = tiket.id_pool', 'left')->select('tiket.id_tiket, tiket.id_trans, tiket.id_pool, tiket.id_vendor, tiket.id_pemberhentian, id_keberangkatan, nama_vendor, jenis_vendor, nama_pemberhentian, jenis_pemberhentian, peminta, nama_pool, nama_kota, pic, tamu, atas_nama, jabatan, jenis_kelamin, jumlah_tiket, pembayaran, tiket.tanggal_jam_tiket, dari_tiket, tujuan_tiket, harga_tiket, keterangan_tiket, status_tiket, tiket.created_at')->findAll();
        
        if (empty($tiket)) {
            session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini']);
            return redirect()->to('tiket_admin');
        }

        // if ($tiket[0]['status_tiket'] == 1) {
        //     session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini karena telah dikonfirmasi']);
        //     return redirect()->to('tiket_admin');
        // }

        foreach ($tiket as $ti => $tik) {
            $atas_nama_tiket = explode(', ', $tik['atas_nama']);
            $jabatan_tiket = explode(', ', $tik['jabatan']);
            $jenis_kelamin_tiket = explode(', ', $tik['jenis_kelamin']);

            foreach ($atas_nama_tiket as $ti => $tik) {
                $atas_nama[$ti] = $tik;
            }

            foreach ($jabatan_tiket as $ja => $jab) {
                $jabatan[$ja] = $jab;
            }

            foreach ($jenis_kelamin_tiket as $je => $jen) {
                $result[] = [
                    'atas_nama' => $atas_nama[$je],
                    'jabatan' => $jabatan[$je],
                    'jenis_kelamin' => $jen,
                ];
            }
        }
        
        $kota_tiket_dari = $this->m_kota->where('nama_kota', $tiket[0]['dari_tiket'])->select('id_kota, id_negara')->findAll();
        $negara_tiket_dari = $this->m_negara->where('id_negara =', $kota_tiket_dari[0]['id_negara'])->select('id_negara, nama_negara')->findAll();
        $negara_dari = $this->m_negara->select('id_negara, nama_negara')->orderBy('nama_negara', 'asc')->findAll();
        $kota_dari = $this->m_kota->where('id_kota !=', $kota_tiket_dari[0]['id_kota'])->select('id_kota, id_negara, nama_kota')->orderBy('nama_kota', 'asc')->findAll();
        
        $kota_tiket_tujuan = $this->m_kota->where('nama_kota', $tiket[0]['tujuan_tiket'])->select('id_kota, id_negara')->findAll();
        $negara_tiket_tujuan = $this->m_negara->where('id_negara =', $kota_tiket_tujuan[0]['id_negara'])->select('id_negara, nama_negara')->findAll();
        $negara_tujuan = $this->m_negara->select('id_negara, nama_negara')->orderBy('nama_negara', 'asc')->findAll();
        $kota_tujuan = $this->m_kota->where('id_kota !=', $kota_tiket_tujuan[0]['id_kota'])->select('id_kota, id_negara, nama_kota')->orderBy('nama_kota', 'asc')->findAll();

        $pool = $this->m_pool->select('nama_pool')->findAll();
        $jenis_vendor = $this->m_vendor->where('id_vendor', $tiket[0]['id_vendor'])->select('jenis_vendor')->orderBy('nama_vendor', 'asc')->findAll();
        $vendor = $this->m_vendor->where('jenis_vendor', $jenis_vendor[0]['jenis_vendor'])->where('id_vendor !=', $tiket[0]['id_vendor'])->select('nama_vendor')->orderBy('nama_vendor', 'asc')->findAll();
        $bus = $this->m_vendor->where('jenis_vendor', 'B')->select('id_vendor, nama_vendor')->orderBy('nama_vendor', 'asc')->findAll();
        $kereta = $this->m_vendor->where('jenis_vendor', 'K')->select('id_vendor, nama_vendor')->orderBy('nama_vendor', 'asc')->findAll();
        $pesawat = $this->m_vendor->where('jenis_vendor', 'P')->select('id_vendor, nama_vendor')->orderBy('nama_vendor', 'asc')->findAll();
        $travel = $this->m_vendor->where('jenis_vendor', 'T')->select('id_vendor, nama_vendor')->orderBy('nama_vendor', 'asc')->findAll();
        $kapal_laut = $this->m_vendor->where('jenis_vendor', 'Ka')->select('id_vendor, nama_vendor')->orderBy('nama_vendor', 'asc')->findAll();
        
        $jenis_berangkat = $this->m_pemberhentian->where('id_pemberhentian', $tiket[0]['id_keberangkatan'])->select('jenis_pemberhentian')->orderBy('nama_pemberhentian', 'asc')->findAll();
        $berangkat = $this->m_pemberhentian->where('jenis_pemberhentian', $jenis_berangkat[0]['jenis_pemberhentian'])->where('id_pemberhentian !=', $tiket[0]['id_keberangkatan'])->select('nama_pemberhentian, nama_kota')->orderBy('nama_pemberhentian', 'asc')->findAll();
        $berangkat_tiket = $this->m_pemberhentian->where('id_pemberhentian', $tiket[0]['id_keberangkatan'])->select('nama_pemberhentian, nama_kota')->orderBy('nama_pemberhentian', 'asc')->first();
        $jenis_berhenti = $this->m_pemberhentian->where('id_pemberhentian', $tiket[0]['id_pemberhentian'])->select('jenis_pemberhentian')->orderBy('nama_pemberhentian', 'asc')->findAll();
        $berhenti = $this->m_pemberhentian->where('jenis_pemberhentian', $jenis_berangkat[0]['jenis_pemberhentian'])->where('id_pemberhentian !=', $tiket[0]['id_pemberhentian'])->select('nama_pemberhentian, nama_kota')->orderBy('nama_pemberhentian', 'asc')->findAll();
        $berhenti_tiket = $this->m_pemberhentian->where('id_pemberhentian', $tiket[0]['id_pemberhentian'])->select('nama_pemberhentian, nama_kota')->orderBy('nama_pemberhentian', 'asc')->first();
        $bandara = $this->m_pemberhentian->where('jenis_pemberhentian', 'B')->select('id_pemberhentian, nama_pemberhentian, nama_kota')->orderBy('nama_pemberhentian', 'asc')->findAll();
        $pelabuhan = $this->m_pemberhentian->where('jenis_pemberhentian', 'P')->select('id_pemberhentian, nama_pemberhentian, nama_kota')->orderBy('nama_pemberhentian', 'asc')->findAll();
        $stasiun = $this->m_pemberhentian->where('jenis_pemberhentian', 'S')->select('id_pemberhentian, nama_pemberhentian, nama_kota')->orderBy('nama_pemberhentian', 'asc')->findAll();
        $terminal = $this->m_pemberhentian->where('jenis_pemberhentian', 'T')->select('id_pemberhentian, nama_pemberhentian, nama_kota')->orderBy('nama_pemberhentian', 'asc')->findAll();

        $pengguna = $this->m_detail_pengguna->where('detail_pengguna.id_bagian', $trans[0]['id_bagian'])->join('pengguna', 'pengguna.id_pengguna = detail_pengguna.id_pengguna', 'left')->join('jabatan', 'jabatan.id_jabatan = detail_pengguna.id_jabatan', 'left')->select('detail_pengguna.id_pengguna, nama_pengguna, nama_jabatan, jenis_kelamin')->orderBy('nama_pengguna', 'asc')->groupBy('id_pengguna')->findAll();
        $kota = $this->m_kota->select('id_kota, id_negara, nama_kota')->orderBy('nama_kota', 'asc')->findAll();
        
        $data = [
            'trans' => $trans,
            'tiket' => $tiket,
            'result' => $result,
            'pool' => $pool,
            'bus' => $bus,
            'kereta' => $kereta,
            'pesawat' => $pesawat,
            'travel' => $travel,
            'kapal_laut' => $kapal_laut,
            'bandara' => $bandara,
            'pelabuhan' => $pelabuhan,
            'stasiun' => $stasiun,
            'terminal' => $terminal,
            'negara_tiket_dari' => $negara_tiket_dari,
            'negara_dari' => $negara_dari,
            'kota_dari' => $kota_dari,
            'negara_tiket_tujuan' => $negara_tiket_tujuan,
            'negara_tujuan' => $negara_tujuan,
            'kota_tujuan' => $kota_tujuan,
            'pengguna' => $pengguna,
            'vendor' => $vendor,
            'kota' => $kota,
            'berangkat' => $berangkat,
            'berangkat_tiket' => $berangkat_tiket,
            'berhenti' => $berhenti,
            'berhenti_tiket' => $berhenti_tiket,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_edit_tiket', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function akomodasi_admin()
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');
        $id_pool = session()->get('pool_pengguna');

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

        if($this->request->getVar('aksi') == 'confirm' && $this->request->getVar('id_akomodasi')) {
            $akomodasi_id = $this->m_akomodasi->akomodasi_id($this->request->getVar('id_akomodasi'));
            if($akomodasi_id['id_akomodasi']) {//memastikan bahwa ada data
                $akomodasi_confirm = $this->m_akomodasi->where('akomodasi.id_akomodasi', $this->request->getVar('id_akomodasi'))->where('batal_akomodasi <', 2)->where('akomodasi.id_pool', $id_pool)->join('trans', 'trans.id_trans = akomodasi.id_trans', 'left')->join('pool', 'pool.id_pool = akomodasi.id_pool', 'left')->select('akomodasi.id_akomodasi, akomodasi.id_trans, akomodasi.id_pool, harga_akomodasi')->findAll();

                if (empty($akomodasi_confirm[0]['harga_akomodasi']) || $akomodasi_confirm[0]['harga_akomodasi'] == '0.00') {
                    session()->setFlashdata('success', ('Masukkan harga akomodasi'));
                    return redirect()->to("harga_akomodasi/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_akomodasi'));
                } else {
                    $akomodasi = [
                        'id_akomodasi' => $this->request->getVar('id_akomodasi'),
                        'status_akomodasi' => 1,
                        'edited_by' => session()->get('login_by'),
                        'edited_at' => $timestamp,
                    ];
                    $this->m_akomodasi->save($akomodasi);
                    session()->setFlashdata('success', ('Transaksi telah dikonfirmasi'));
                    return redirect()->to(session()->get('url_kend'));
                }
            }
        }

        if($this->request->getVar('aksi') == 'batal' && $this->request->getVar('id_trans') && $this->request->getVar('id_akomodasi')) {
            $akomodasi_id = $this->m_akomodasi->akomodasi_id($this->request->getVar('id_akomodasi'));
            if($akomodasi_id['id_akomodasi']) {//memastikan bahwa ada data
                session()->setFlashdata('success', ('Masukkan alasan batal'));
                return redirect()->to("batal_akomodasi/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_akomodasi'));
            }
        }

        if($this->request->getVar('aksi') == 'batal_confirm' && $this->request->getVar('id_trans') && $this->request->getVar('id_akomodasi')) {
            $akomodasi_id = $this->m_akomodasi->akomodasi_id($this->request->getVar('id_akomodasi'));
            if($akomodasi_id['id_akomodasi']) {//memastikan bahwa ada data
                session()->setFlashdata('success', ('Masukkan alasan batal'));
                return redirect()->to("batal_akomodasi_confirm/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_akomodasi'));
            }
        }

        if($this->request->getVar('aksi') == 'tolak_permintaan_batal' && $this->request->getVar('id_trans') && $this->request->getVar('id_akomodasi')) {
            $akomodasi_id = $this->m_akomodasi->akomodasi_id($this->request->getVar('id_akomodasi'));
            if($akomodasi_id['id_akomodasi']) {//memastikan bahwa ada data
                $trans = [
                    'id_trans' => $this->request->getVar('id_trans'),
                    'alasan_batal' => null,
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];
    
                $akomodasi = [
                    'id_akomodasi' => $this->request->getVar('id_akomodasi'),
                    'batal_akomodasi' => 0,
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];
    
                $this->m_trans->save($trans);
                $this->m_akomodasi->save($akomodasi);
                session()->setFlashdata('success', ('Transaksi tidak jadi dibatalkan'));
                return redirect()->to(session()->get('url_kend'));
            }
        }

        if($this->request->getVar('aksi') == 'refund' && $this->request->getVar('id_trans') && $this->request->getVar('id_akomodasi')) {
            $akomodasi_id = $this->m_akomodasi->akomodasi_id($this->request->getVar('id_akomodasi'));
            if($akomodasi_id['id_akomodasi']) {//memastikan bahwa ada data
                session()->setFlashdata('success', ('Masukkan nilai refund'));
                return redirect()->to("harga_akomodasi/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_akomodasi'));
            }
        }

        if($this->request->getVar('aksi') == 'mess_jkt') {
            session()->setFlashdata('success', ('Silahkan melakukan setting Mess'));
            return redirect()->to("set_mess_jkt/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_akomodasi'));
        }

        $id_bagian = session()->get('id_bagian');
        $id_detail_pengguna = session()->get('id_detail_pengguna');

        $trans = $this->m_trans->where('tanggal_jam_keluar >', $timestamp)->where('id_hotel !=', 158)->where('batal_akomodasi <', 2)->where('akomodasi.id_pool', $id_pool)->join('akomodasi', 'akomodasi.id_trans = trans.id_trans', 'left')->select('trans.id_trans, akomodasi.id_pool, akomodasi.id_hotel, tanggal_jam_masuk, tanggal_jam_keluar, trans.created_at, akomodasi.created_at')->orderBy('akomodasi.created_at', 'desc')->findAll();

        $akomodasi = $this->m_akomodasi->where('akomodasi.tanggal_jam_keluar >', $timestamp)->where('batal_akomodasi <', 2)->where('akomodasi.id_pool', $id_pool)->join('trans', 'trans.id_trans = akomodasi.id_trans', 'left')->join('hotel', 'hotel.id_hotel = akomodasi.id_hotel', 'left')->join('detail_hotel', 'detail_hotel.id_detail_hotel = akomodasi.id_detail_hotel', 'left')->join('pool', 'pool.id_pool = akomodasi.id_pool', 'left')->join('kota', 'kota.id_kota = akomodasi.id_kota', 'left')->select('akomodasi.id_trans, akomodasi.id_akomodasi, akomodasi.id_pool, , akomodasi.id_hotel, nama_hotel, jenis_kamar, nama_pool, nama_kota, pic, peminta, atas_nama, jabatan, jumlah_kamar, pembayaran, tanggal_jam_masuk, akomodasi.tanggal_jam_keluar, keterangan_akomodasi, status_akomodasi, batal_akomodasi, harga_akomodasi, refund_akomodasi, status_mess, akomodasi.created_at')->findAll();

        $trans_batal = $this->m_trans->where('batal_akomodasi', 3)->where('akomodasi.id_pool', $id_pool)->join('akomodasi', 'akomodasi.id_trans = trans.id_trans', 'left')->select('trans.id_trans, akomodasi.id_pool, tanggal_jam_masuk, tanggal_jam_keluar, trans.created_at')->orderBy('akomodasi.created_at', 'desc')->orderBy('tanggal_jam_masuk', 'desc')->findAll();

        $akomodasi_batal = $this->m_akomodasi->where('batal_akomodasi', 3)->where('akomodasi.id_pool', $id_pool)->join('trans', 'trans.id_trans = akomodasi.id_trans', 'left')->join('hotel', 'hotel.id_hotel = akomodasi.id_hotel', 'left')->join('detail_hotel', 'detail_hotel.id_detail_hotel = akomodasi.id_detail_hotel', 'left')->join('pool', 'pool.id_pool = akomodasi.id_pool', 'left')->join('kota', 'kota.id_kota = akomodasi.id_kota', 'left')->select('akomodasi.id_trans, akomodasi.id_akomodasi, akomodasi.id_pool, nama_hotel, jenis_kamar, nama_pool, nama_kota, pic, peminta, atas_nama, jabatan, jumlah_kamar, pembayaran, tanggal_jam_masuk, akomodasi.tanggal_jam_keluar, keterangan_akomodasi, status_akomodasi, batal_akomodasi, harga_akomodasi, refund_akomodasi, status_mess, akomodasi.created_at')->findAll();

        session()->set('url_kend', current_url());

        $data = [
            'trans' => $trans,
            'akomodasi' => $akomodasi,
            'trans_batal' => $trans_batal,
            'akomodasi_batal' => $akomodasi_batal,
            'id_pool' => $id_pool,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_akomodasi_admin', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function mess_admin()
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');
        $id_pool = session()->get('pool_pengguna');

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

        if($this->request->getVar('aksi') == 'confirm' && $this->request->getVar('id_akomodasi')) {
            $akomodasi_id = $this->m_akomodasi->akomodasi_id($this->request->getVar('id_akomodasi'));
            if($akomodasi_id['id_akomodasi']) {//memastikan bahwa ada data
                $akomodasi = [
                    'id_akomodasi' => $this->request->getVar('id_akomodasi'),
                    'status_akomodasi' => 1,
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];
                $this->m_akomodasi->save($akomodasi);
                session()->setFlashdata('success', ('Silahkan Set Kamar Mess'));
                return redirect()->to("set_mess_jkt/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_akomodasi'));
            }
        }

        if($this->request->getVar('aksi') == 'batal' && $this->request->getVar('id_trans') && $this->request->getVar('id_akomodasi')) {
            $akomodasi_id = $this->m_akomodasi->akomodasi_id($this->request->getVar('id_akomodasi'));
            if($akomodasi_id['id_akomodasi']) {//memastikan bahwa ada data
                session()->setFlashdata('success', ('Masukkan alasan batal'));
                return redirect()->to("batal_akomodasi/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_akomodasi'));
            }
        }

        if($this->request->getVar('aksi') == 'batal_confirm' && $this->request->getVar('id_trans') && $this->request->getVar('id_akomodasi')) {
            $akomodasi_id = $this->m_akomodasi->akomodasi_id($this->request->getVar('id_akomodasi'));
            if($akomodasi_id['id_akomodasi']) {//memastikan bahwa ada data
                session()->setFlashdata('success', ('Masukkan alasan batal'));
                return redirect()->to("batal_akomodasi_confirm/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_akomodasi'));
            }
        }

        if($this->request->getVar('aksi') == 'tolak_permintaan_batal' && $this->request->getVar('id_trans') && $this->request->getVar('id_akomodasi')) {
            $akomodasi_id = $this->m_akomodasi->akomodasi_id($this->request->getVar('id_akomodasi'));
            if($akomodasi_id['id_akomodasi']) {//memastikan bahwa ada data
                $trans = [
                    'id_trans' => $this->request->getVar('id_trans'),
                    'alasan_batal' => null,
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];
    
                $akomodasi = [
                    'id_akomodasi' => $this->request->getVar('id_akomodasi'),
                    'batal_akomodasi' => 0,
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];
    
                $this->m_trans->save($trans);
                $this->m_akomodasi->save($akomodasi);
                session()->setFlashdata('success', ('Transaksi tidak jadi dibatalkan'));
                return redirect()->to(session()->get('url_kend'));
            }
        }

        if($this->request->getVar('aksi') == 'refund' && $this->request->getVar('id_trans') && $this->request->getVar('id_akomodasi')) {
            $akomodasi_id = $this->m_akomodasi->akomodasi_id($this->request->getVar('id_akomodasi'));
            if($akomodasi_id['id_akomodasi']) {//memastikan bahwa ada data
                session()->setFlashdata('success', ('Masukkan nilai refund'));
                return redirect()->to("harga_akomodasi/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_akomodasi'));
            }
        }

        if($this->request->getVar('aksi') == 'mess_jkt') {
            session()->setFlashdata('success', ('Silahkan melakukan setting Mess'));
            return redirect()->to("set_mess_jkt/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_akomodasi'));
        }

        $id_bagian = session()->get('id_bagian');
        $id_detail_pengguna = session()->get('id_detail_pengguna');

        $trans = $this->m_trans->where('tanggal_jam_keluar >', $timestamp)->where('id_hotel', 158)->where('batal_akomodasi <', 2)->where('akomodasi.id_pool', $id_pool)->join('akomodasi', 'akomodasi.id_trans = trans.id_trans', 'left')->select('trans.id_trans, akomodasi.id_pool, akomodasi.id_hotel, tanggal_jam_masuk, tanggal_jam_keluar, trans.created_at, akomodasi.created_at')->orderBy('akomodasi.created_at', 'desc')->findAll();

        $akomodasi = $this->m_akomodasi->where('akomodasi.tanggal_jam_keluar >', $timestamp)->where('batal_akomodasi <', 2)->where('akomodasi.id_pool', $id_pool)->join('trans', 'trans.id_trans = akomodasi.id_trans', 'left')->join('hotel', 'hotel.id_hotel = akomodasi.id_hotel', 'left')->join('detail_hotel', 'detail_hotel.id_detail_hotel = akomodasi.id_detail_hotel', 'left')->join('pool', 'pool.id_pool = akomodasi.id_pool', 'left')->join('kota', 'kota.id_kota = akomodasi.id_kota', 'left')->select('akomodasi.id_trans, akomodasi.id_akomodasi, akomodasi.id_pool, , akomodasi.id_hotel, nama_hotel, jenis_kamar, nama_pool, nama_kota, pic, peminta, atas_nama, jabatan, jumlah_kamar, pembayaran, tanggal_jam_masuk, akomodasi.tanggal_jam_keluar, keterangan_akomodasi, status_akomodasi, batal_akomodasi, harga_akomodasi, refund_akomodasi, status_mess, akomodasi.created_at')->findAll();

        $trans_batal = $this->m_trans->where('batal_akomodasi', 3)->where('akomodasi.id_pool', $id_pool)->join('akomodasi', 'akomodasi.id_trans = trans.id_trans', 'left')->select('trans.id_trans, akomodasi.id_pool, tanggal_jam_masuk, tanggal_jam_keluar, trans.created_at')->orderBy('akomodasi.created_at', 'desc')->orderBy('tanggal_jam_masuk', 'desc')->findAll();

        $akomodasi_batal = $this->m_akomodasi->where('batal_akomodasi', 3)->where('akomodasi.id_pool', $id_pool)->join('trans', 'trans.id_trans = akomodasi.id_trans', 'left')->join('hotel', 'hotel.id_hotel = akomodasi.id_hotel', 'left')->join('detail_hotel', 'detail_hotel.id_detail_hotel = akomodasi.id_detail_hotel', 'left')->join('pool', 'pool.id_pool = akomodasi.id_pool', 'left')->join('kota', 'kota.id_kota = akomodasi.id_kota', 'left')->select('akomodasi.id_trans, akomodasi.id_akomodasi, akomodasi.id_pool, nama_hotel, jenis_kamar, nama_pool, nama_kota, pic, peminta, atas_nama, jabatan, jumlah_kamar, pembayaran, tanggal_jam_masuk, akomodasi.tanggal_jam_keluar, keterangan_akomodasi, status_akomodasi, batal_akomodasi, harga_akomodasi, refund_akomodasi, status_mess, akomodasi.created_at')->findAll();

        $data = [
            'trans' => $trans,
            'akomodasi' => $akomodasi,
            'trans_batal' => $trans_batal,
            'akomodasi_batal' => $akomodasi_batal,
            'id_pool' => $id_pool,
        ];

        session()->set('url_kend', current_url());

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_mess_admin', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function set_mess_jkt($id_trans, $id_akomodasi)
    {
        $data = [];
        
        $admin_gs = session()->get('admin_gs');
        $id_pool = session()->get('pool_pengguna');

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

        if($this->request->getVar('aksi') == 'batal_mp' && $this->request->getVar('id_trans') && $this->request->getVar('id_akomodasi') && $this->request->getVar('id_personil_mess')) {
            $akomodasi_id = $this->m_akomodasi->akomodasi_id($this->request->getVar('id_akomodasi'));
            if($akomodasi_id['id_akomodasi']) {//memastikan bahwa ada data
                $akomodasi = [
                    'id_personil_mess' => $this->request->getVar('id_personil_mess'),
                    'kamar_mess' => 0,
                    'status' => 0,
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];
                $this->m_personil_mess->save($akomodasi);
                session()->setFlashdata('success', ('Silahkan Set Kamar Mess'));
                return redirect()->to("set_mess_jkt/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_akomodasi'));
            }
        }

        if($this->request->getVar('aksi') == 'batal_mc' && $this->request->getVar('id_trans') && $this->request->getVar('id_akomodasi') && $this->request->getVar('id_mess')) {
            $akomodasi_id = $this->m_akomodasi->akomodasi_id($this->request->getVar('id_akomodasi'));
            if($akomodasi_id['id_akomodasi']) {//memastikan bahwa ada data
                session()->setFlashdata('success', ('Masukkan alasan batal'));
                return redirect()->to("batal_kamar_mess/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_akomodasi')."/".$this->request->getVar('id_mess'));
            }
        }

        if($this->request->getMethod() == 'post') {
            $tanggal = $this->request->getVar('filter_k1');
            $kamar_mess = $this->request->getVar('kamar_mess');
            $pindah_mess = $this->request->getVar('pindah_mess');
            $jenis_kelamin = $this->request->getVar('jenis_kelamin');
            if (!empty($kamar_mess)) {
                if ($this->request->getVar('kamar_mess') == 'pilih') {
                    session()->setFlashdata('warning', ['Silahkan pilih kamar mess terlebih dahulu']);
                    return redirect()->to("set_mess_jkt/".$this->request->getVar('id_tra')."/".$this->request->getVar('id_ako'));
                }

                if ($this->request->getVar('gender') == null) {
                    session()->setFlashdata('warning', ['Silahkan masukkan data Jenis Kelamin terlebih dahulu']);
                    return redirect()->to("set_mess_jkt/".$this->request->getVar('id_tra')."/".$this->request->getVar('id_ako'));
                }

                $id_perso_mess = $this->m_personil_mess->where('id_trans', $this->request->getVar('id_tra'))->where('atas_nama', $this->request->getVar('atas_nama'))->select('id_personil_mess, tanggal_mess')->findAll();

                $nilai_1 = $this->request->getVar('1_nilai');
                if (empty($nilai_1)) {
                    session()->setFlashdata('warning', ['Silahkan pilih tanggal mess terlebih dahulu']);
                    return redirect()->to("set_mess_jkt/".$this->request->getVar('id_tra')."/".$this->request->getVar('id_ako'));
                }
                $tanggal_centang = $nilai_1[1];
                $jumlah_centang = count($nilai_1[1]);
                $jumlah_tanggal = count($id_perso_mess);

                if ($jumlah_centang != $jumlah_tanggal) {
                    foreach ($tanggal_centang as $ta => $tac) {
                        $id_tanggal_centang = $this->m_personil_mess->where('id_trans', $this->request->getVar('id_tra'))->where('tanggal_mess', $tac)->where('atas_nama', $this->request->getVar('atas_nama'))->select('id_personil_mess, tanggal_mess')->first();

                        $cek_kamar = $this->m_personil_mess->where('tanggal_mess', $id_tanggal_centang['tanggal_mess'])->where('kamar_mess', $this->request->getVar('kamar_mess'))->where('status', 1)->where('batal', 0)->select('kamar_mess, sum(status) as sum')->orderBy('tanggal_mess', 'asc')->findAll();

                        $tang[] = $id_tanggal_centang['tanggal_mess'];

                        foreach ($cek_kamar as $ce => $cek) {
                            if ($kamar_mess == 1) {
                                if ($cek['sum'] == 3) {
                                    
                                } else {
                                    $kamar = $this->request->getVar('kamar_mess');
                                    $id_personil_mess = $id_tanggal_centang['id_personil_mess'];
                                    $akomodasi[] = [
                                        'id_personil_mess' => $id_personil_mess,
                                        'id_trans' => $this->request->getVar('id_tra'),
                                        'id_akomodasi' => $this->request->getVar('id_ako'),
                                        'kamar_mess' => $kamar,
                                        'status' => 1,
                                        'edited_by' => session()->get('login_by'),
                                        'edited_at' => $timestamp,
                                    ];
                                }
                            } else if ($kamar_mess == 2) {
                                if ($cek['sum'] == 3) {
                                    
                                } else {
                                    $kamar = $this->request->getVar('kamar_mess');
                                    $id_personil_mess = $id_tanggal_centang['id_personil_mess'];
                                    $akomodasi[] = [
                                        'id_personil_mess' => $id_personil_mess,
                                        'id_trans' => $this->request->getVar('id_tra'),
                                        'id_akomodasi' => $this->request->getVar('id_ako'),
                                        'kamar_mess' => $kamar,
                                        'status' => 1,
                                        'edited_by' => session()->get('login_by'),
                                        'edited_at' => $timestamp,
                                    ];
                                }
                            } else if ($kamar_mess == 3) {
                                if ($cek['sum'] == 2) {
                                    
                                } else {
                                    $kamar = $this->request->getVar('kamar_mess');
                                    $id_personil_mess = $id_tanggal_centang['id_personil_mess'];
                                    $akomodasi[] = [
                                        'id_personil_mess' => $id_personil_mess,
                                        'id_trans' => $this->request->getVar('id_tra'),
                                        'id_akomodasi' => $this->request->getVar('id_ako'),
                                        'kamar_mess' => $kamar,
                                        'status' => 1,
                                        'edited_by' => session()->get('login_by'),
                                        'edited_at' => $timestamp,
                                    ];
                                }
                            } else if ($kamar_mess == 4) {
                                if ($cek['sum'] == 2) {
                                    
                                } else {
                                    $kamar = $this->request->getVar('kamar_mess');
                                    $id_personil_mess = $id_tanggal_centang['id_personil_mess'];
                                    $akomodasi[] = [
                                        'id_personil_mess' => $id_personil_mess,
                                        'id_trans' => $this->request->getVar('id_tra'),
                                        'id_akomodasi' => $this->request->getVar('id_ako'),
                                        'kamar_mess' => $kamar,
                                        'status' => 1,
                                        'edited_by' => session()->get('login_by'),
                                        'edited_at' => $timestamp,
                                    ];
                                }
                            } else if ($kamar_mess == 5) {
                                if ($cek['sum'] == 2) {
                                    
                                } else {
                                    $kamar = $this->request->getVar('kamar_mess');
                                    $id_personil_mess = $id_tanggal_centang['id_personil_mess'];
                                    $akomodasi[] = [
                                        'id_personil_mess' => $id_personil_mess,
                                        'id_trans' => $this->request->getVar('id_tra'),
                                        'id_akomodasi' => $this->request->getVar('id_ako'),
                                        'kamar_mess' => $kamar,
                                        'status' => 1,
                                        'edited_by' => session()->get('login_by'),
                                        'edited_at' => $timestamp,
                                    ];
                                }
                            } else if ($kamar_mess == 6) {
                                if ($cek['sum'] == 2) {
                                    
                                } else {
                                    $kamar = $this->request->getVar('kamar_mess');
                                    $id_personil_mess = $id_tanggal_centang['id_personil_mess'];
                                    $akomodasi[] = [
                                        'id_personil_mess' => $id_personil_mess,
                                        'id_trans' => $this->request->getVar('id_tra'),
                                        'id_akomodasi' => $this->request->getVar('id_ako'),
                                        'kamar_mess' => $kamar,
                                        'status' => 1,
                                        'edited_by' => session()->get('login_by'),
                                        'edited_at' => $timestamp,
                                    ];
                                }
                            } else if ($kamar_mess == 7) {
                                if ($cek['sum'] == 4) {
                                    
                                } else {
                                    $kamar = $this->request->getVar('kamar_mess');
                                    $id_personil_mess = $id_tanggal_centang['id_personil_mess'];
                                    $akomodasi[] = [
                                        'id_personil_mess' => $id_personil_mess,
                                        'id_trans' => $this->request->getVar('id_tra'),
                                        'id_akomodasi' => $this->request->getVar('id_ako'),
                                        'kamar_mess' => $kamar,
                                        'status' => 1,
                                        'edited_by' => session()->get('login_by'),
                                        'edited_at' => $timestamp,
                                    ];
                                }
                            }
                        }
                    }
                } else {
                    foreach ($id_perso_mess as $idp => $idm) {
                        $cek_kamar = $this->m_personil_mess->where('tanggal_mess', $idm['tanggal_mess'])->where('kamar_mess', $this->request->getVar('kamar_mess'))->where('status', 1)->where('batal', 0)->select('kamar_mess, sum(status) as sum')->orderBy('tanggal_mess', 'asc')->findAll();

                        $id_personil_mess = $idm['id_personil_mess'];

                        $akom[] = [
                            'id_personil_mess' => $id_personil_mess,
                        ];
    
                        $tang[] = $idm['tanggal_mess'];
    
                        foreach ($cek_kamar as $ck => $cek) {
                            if ($kamar_mess == 1) {
                                if ($cek['sum'] == 3) {
                                    $sip = $idm['tanggal_mess'];
                                    $ketinggalan = 1;
                                } else {
                                    $kamar = $this->request->getVar('kamar_mess');
                                    $id_personil_mess = $idm['id_personil_mess'];
                                    $akomodasi[] = [
                                        'id_personil_mess' => $id_personil_mess,
                                        'id_trans' => $this->request->getVar('id_tra'),
                                        'id_akomodasi' => $this->request->getVar('id_ako'),
                                        'kamar_mess' => $kamar,
                                        'status' => 1,
                                        'edited_by' => session()->get('login_by'),
                                        'edited_at' => $timestamp,
                                    ];
                                }
                            } else if ($kamar_mess == 2) {
                                if ($cek['sum'] == 3) {
                                    $sip = $idm['tanggal_mess'];
                                    $ketinggalan = 1;
                                } else {
                                    $kamar = $this->request->getVar('kamar_mess');
                                    $id_personil_mess = $idm['id_personil_mess'];
                                    $akomodasi[] = [
                                        'id_personil_mess' => $id_personil_mess,
                                        'id_trans' => $this->request->getVar('id_tra'),
                                        'id_akomodasi' => $this->request->getVar('id_ako'),
                                        'kamar_mess' => $kamar,
                                        'status' => 1,
                                        'edited_by' => session()->get('login_by'),
                                        'edited_at' => $timestamp,
                                    ];
                                }
                            } else if ($kamar_mess == 3) {
                                if ($cek['sum'] == 2) {
                                    $sip = $idm['tanggal_mess'];
                                    $ketinggalan = 1;
                                } else {
                                    $kamar = $this->request->getVar('kamar_mess');
                                    $id_personil_mess = $idm['id_personil_mess'];
                                    $akomodasi[] = [
                                        'id_personil_mess' => $id_personil_mess,
                                        'id_trans' => $this->request->getVar('id_tra'),
                                        'id_akomodasi' => $this->request->getVar('id_ako'),
                                        'kamar_mess' => $kamar,
                                        'status' => 1,
                                        'edited_by' => session()->get('login_by'),
                                        'edited_at' => $timestamp,
                                    ];
                                }
                            } else if ($kamar_mess == 4) {
                                if ($cek['sum'] == 2) {
                                    $sip = $idm['tanggal_mess'];
                                    $ketinggalan = 1;
                                } else {
                                    $kamar = $this->request->getVar('kamar_mess');
                                    $id_personil_mess = $idm['id_personil_mess'];
                                    $akomodasi[] = [
                                        'id_personil_mess' => $id_personil_mess,
                                        'id_trans' => $this->request->getVar('id_tra'),
                                        'id_akomodasi' => $this->request->getVar('id_ako'),
                                        'kamar_mess' => $kamar,
                                        'status' => 1,
                                        'edited_by' => session()->get('login_by'),
                                        'edited_at' => $timestamp,
                                    ];
                                }
                            } else if ($kamar_mess == 5) {
                                if ($cek['sum'] == 2) {
                                    $sip = $idm['tanggal_mess'];
                                    $ketinggalan = 1;
                                } else {
                                    $kamar = $this->request->getVar('kamar_mess');
                                    $id_personil_mess = $idm['id_personil_mess'];
                                    $akomodasi[] = [
                                        'id_personil_mess' => $id_personil_mess,
                                        'id_trans' => $this->request->getVar('id_tra'),
                                        'id_akomodasi' => $this->request->getVar('id_ako'),
                                        'kamar_mess' => $kamar,
                                        'status' => 1,
                                        'edited_by' => session()->get('login_by'),
                                        'edited_at' => $timestamp,
                                    ];
                                }
                            } else if ($kamar_mess == 6) {
                                if ($cek['sum'] == 2) {
                                    $sip = $idm['tanggal_mess'];
                                    $ketinggalan = 1;
                                } else {
                                    $kamar = $this->request->getVar('kamar_mess');
                                    $id_personil_mess = $idm['id_personil_mess'];
                                    $akomodasi[] = [
                                        'id_personil_mess' => $id_personil_mess,
                                        'id_trans' => $this->request->getVar('id_tra'),
                                        'id_akomodasi' => $this->request->getVar('id_ako'),
                                        'kamar_mess' => $kamar,
                                        'status' => 1,
                                        'edited_by' => session()->get('login_by'),
                                        'edited_at' => $timestamp,
                                    ];
                                }
                            } else if ($kamar_mess == 7) {
                                if ($cek['sum'] == 4) {
                                    $sip = $idm['tanggal_mess'];
                                    $ketinggalan = 1;
                                } else {
                                    $kamar = $this->request->getVar('kamar_mess');
                                    $id_personil_mess = $idm['id_personil_mess'];
                                    $akomodasi[] = [
                                        'id_personil_mess' => $id_personil_mess,
                                        'id_trans' => $this->request->getVar('id_tra'),
                                        'id_akomodasi' => $this->request->getVar('id_ako'),
                                        'kamar_mess' => $kamar,
                                        'status' => 1,
                                        'edited_by' => session()->get('login_by'),
                                        'edited_at' => $timestamp,
                                    ];
                                }
                            }
                        }
                    }
                }
    
                if (empty($akomodasi)) {
                    foreach ($tang as $ta => $tan) {
                        $myArray[$ta] = "Kamar sudah penuh di hari ".tanggal_indo($tan);
                    }
                    session()->setFlashdata('warning', $myArray);
                    return redirect()->to("set_mess_jkt/".$this->request->getVar('id_tra')."/".$this->request->getVar('id_ako'));
                }
                $this->m_personil_mess->updateBatch($akomodasi, 'id_personil_mess');
                if (empty($ketinggalan)) {
                    session()->setFlashdata('success', ('Transaksi telah dikonfirmasi'));
                } else {
                    session()->setFlashdata('warning', ['Kamar sudah penuh di hari '.tanggal_indo($sip)]);
                }
                return redirect()->to("set_mess_jkt/".$this->request->getVar('id_tra')."/".$this->request->getVar('id_ako'));
            } else if (!empty($pindah_mess)) {
                if ($this->request->getVar('pindah_mess') == 'pilih') {
                    session()->setFlashdata('warning', ['Silahkan pilih kamar mess terlebih dahulu']);
                    return redirect()->to("set_mess_jkt/".$this->request->getVar('id_tra')."/".$this->request->getVar('id_ako'));
                }

                $id_perso_mess = $this->m_personil_mess->where('id_personil_mess', $this->request->getVar('id_personil_mess'))->select('id_personil_mess, tanggal_mess, kamar_mess')->first();

                $cek = $this->m_personil_mess->where('kamar_mess', $pindah_mess)->where('tanggal_mess', $id_perso_mess['tanggal_mess'])->where('status', 1)->where('batal', 0)->select('id_personil_mess, tanggal_mess, kamar_mess')->findAll();

                if ($pindah_mess == 1) {
                    if (count($cek) == 3) {
                        session()->setFlashdata('warning', ['Kamar sudah penuh di hari '.tanggal_indo($id_perso_mess['tanggal_mess'])]);
                        return redirect()->to("set_mess_jkt/".$this->request->getVar('id_tra')."/".$this->request->getVar('id_ako'));
                    } else {
                        $akomodasi = [
                            'id_personil_mess' => $this->request->getVar('id_personil_mess'),
                            'kamar_mess' => $pindah_mess,
                            'edited_by' => session()->get('login_by'),
                            'edited_at' => $timestamp,
                        ];
                    }
                } else if ($pindah_mess == 2) {
                    if (count($cek) == 3) {
                        session()->setFlashdata('warning', ['Kamar sudah penuh di hari '.tanggal_indo($id_perso_mess['tanggal_mess'])]);
                        return redirect()->to("set_mess_jkt/".$this->request->getVar('id_tra')."/".$this->request->getVar('id_ako'));
                    } else {
                        $akomodasi = [
                            'id_personil_mess' => $this->request->getVar('id_personil_mess'),
                            'kamar_mess' => $pindah_mess,
                            'edited_by' => session()->get('login_by'),
                            'edited_at' => $timestamp,
                        ];
                    }
                } else if ($pindah_mess == 3) {
                    if (count($cek) == 2) {
                        session()->setFlashdata('warning', ['Kamar sudah penuh di hari '.tanggal_indo($id_perso_mess['tanggal_mess'])]);
                        return redirect()->to("set_mess_jkt/".$this->request->getVar('id_tra')."/".$this->request->getVar('id_ako'));
                    } else {
                        $akomodasi = [
                            'id_personil_mess' => $this->request->getVar('id_personil_mess'),
                            'kamar_mess' => $pindah_mess,
                            'edited_by' => session()->get('login_by'),
                            'edited_at' => $timestamp,
                        ];
                    }
                } else if ($pindah_mess == 4) {
                    if (count($cek) == 2) {
                        session()->setFlashdata('warning', ['Kamar sudah penuh di hari '.tanggal_indo($id_perso_mess['tanggal_mess'])]);
                        return redirect()->to("set_mess_jkt/".$this->request->getVar('id_tra')."/".$this->request->getVar('id_ako'));
                    } else {
                        $akomodasi = [
                            'id_personil_mess' => $this->request->getVar('id_personil_mess'),
                            'kamar_mess' => $pindah_mess,
                            'edited_by' => session()->get('login_by'),
                            'edited_at' => $timestamp,
                        ];
                    }
                } else if ($pindah_mess == 5) {
                    if (count($cek) == 2) {
                        session()->setFlashdata('warning', ['Kamar sudah penuh di hari '.tanggal_indo($id_perso_mess['tanggal_mess'])]);
                        return redirect()->to("set_mess_jkt/".$this->request->getVar('id_tra')."/".$this->request->getVar('id_ako'));
                    } else {
                        $akomodasi = [
                            'id_personil_mess' => $this->request->getVar('id_personil_mess'),
                            'kamar_mess' => $pindah_mess,
                            'edited_by' => session()->get('login_by'),
                            'edited_at' => $timestamp,
                        ];
                    }
                } else if ($pindah_mess == 6) {
                    if (count($cek) == 2) {
                        session()->setFlashdata('warning', ['Kamar sudah penuh di hari '.tanggal_indo($id_perso_mess['tanggal_mess'])]);
                        return redirect()->to("set_mess_jkt/".$this->request->getVar('id_tra')."/".$this->request->getVar('id_ako'));
                    } else {
                        $akomodasi = [
                            'id_personil_mess' => $this->request->getVar('id_personil_mess'),
                            'kamar_mess' => $pindah_mess,
                            'edited_by' => session()->get('login_by'),
                            'edited_at' => $timestamp,
                        ];
                    }
                } else if ($pindah_mess == 7) {
                    if (count($cek) == 4) {
                        session()->setFlashdata('warning', ['Kamar sudah penuh di hari '.tanggal_indo($id_perso_mess['tanggal_mess'])]);
                        return redirect()->to("set_mess_jkt/".$this->request->getVar('id_tra')."/".$this->request->getVar('id_ako'));
                    } else {
                        $akomodasi = [
                            'id_personil_mess' => $this->request->getVar('id_personil_mess'),
                            'kamar_mess' => $pindah_mess,
                            'edited_by' => session()->get('login_by'),
                            'edited_at' => $timestamp,
                        ];
                    }
                }
                $this->m_personil_mess->save($akomodasi);
                session()->setFlashdata('success', ('Transaksi telah dikonfirmasi'));
                return redirect()->to("set_mess_jkt/".$this->request->getVar('id_tra')."/".$this->request->getVar('id_ako'));
            } else if (!empty($tanggal)) {
                session()->setFlashdata('tanggal', ($tanggal));
                return redirect()->to("set_mess_jkt/".$id_trans."/".$id_akomodasi);
            } else if (!empty($jenis_kelamin)) {
                if ($this->request->getVar('jenis_kelamin') == 'pilih') {
                    session()->setFlashdata('warning', ['Silahkan pilih salah satu jenis kelamin terlebih dahulu']);
                    return redirect()->to("set_mess_jkt/".$this->request->getVar('id_tra')."/".$this->request->getVar('id_ako'));
                }

                $id_perso_mess = $this->m_personil_mess->where('id_trans', $this->request->getVar('id_tra'))->where('atas_nama', $this->request->getVar('atas_nama'))->select('id_personil_mess, tanggal_mess')->findAll();

                foreach ($id_perso_mess as $ip => $idp) {
                    $akomodasi[] = [
                        'id_personil_mess' => $idp['id_personil_mess'],
                        'jenis_kelamin' => $jenis_kelamin,
                        'edited_by' => session()->get('login_by'),
                        'edited_at' => $timestamp,
                    ];
                }                
                $this->m_personil_mess->updateBatch($akomodasi, 'id_personil_mess');
                session()->setFlashdata('success', ('Transaksi telah diperbaharui'));
                return redirect()->to("set_mess_jkt/".$id_trans."/".$id_akomodasi);
            }
        }

        $trans = $this->m_trans->where('tanggal_jam_keluar >', $timestamp)->where('batal_akomodasi <', 2)->where('akomodasi.id_pool', $id_pool)->join('akomodasi', 'akomodasi.id_trans = trans.id_trans', 'left')->select('trans.id_trans, akomodasi.id_pool, tanggal_jam_masuk, tanggal_jam_keluar, trans.created_at')->orderBy('created_at', 'asc')->findAll();
        
        if (empty($trans)) {
            session()->setFlashdata('warning', ['Tidak ada data']);
            return redirect()->to("set_mess_jkt/".$id_trans."/".$id_akomodasi);
        }

        $mess_confirm = $this->m_akomodasi->where('akomodasi.tanggal_jam_keluar >', $timestamp)->where('batal_akomodasi <', 2)->where('status_mess', 2)->where('akomodasi.id_pool', $id_pool)->where('nama_hotel', "Mess Kx Jkt")->join('trans', 'trans.id_trans = akomodasi.id_trans', 'left')->join('hotel', 'hotel.id_hotel = akomodasi.id_hotel', 'left')->join('detail_hotel', 'detail_hotel.id_detail_hotel = akomodasi.id_detail_hotel', 'left')->join('pool', 'pool.id_pool = akomodasi.id_pool', 'left')->join('kota', 'kota.id_kota = akomodasi.id_kota', 'left')->join('mess_kx_jkt', 'mess_kx_jkt.id_mess = akomodasi.id_mess', 'left')->select('akomodasi.id_trans, akomodasi.id_akomodasi, akomodasi.id_mess, akomodasi.id_pool, nama_hotel, nama_kamar, jenis_kamar, nama_pool, nama_kota, pic, peminta, atas_nama, jabatan, jumlah_kamar, pembayaran, tanggal_jam_masuk, akomodasi.tanggal_jam_keluar, keterangan_akomodasi, status_akomodasi, batal_akomodasi, harga_akomodasi, refund_akomodasi, status_mess, akomodasi.created_at')->findAll();

        $list_mess = $this->m_personil_mess->where('personil_mess.id_trans', $id_trans)->where('personil_mess.batal', 0)->join('akomodasi', 'akomodasi.id_trans = personil_mess.id_trans', 'left')->select('id_personil_mess, personil_mess.id_trans, akomodasi.id_akomodasi, personil_mess.atas_nama, tanggal_mess, personil_mess.jenis_kelamin, kamar_mess, personil_mess.status, tanggal_jam_masuk, tanggal_jam_keluar')->orderBy('atas_nama')->orderBy('tanggal_mess')->findAll();

        $tangg = $this->m_personil_mess->where('personil_mess.id_trans', $id_trans)->where('tanggal_mess >', $date)->where('personil_mess.batal', 0)->join('akomodasi', 'akomodasi.id_trans = personil_mess.id_trans', 'left')->select('id_personil_mess, personil_mess.id_trans, akomodasi.id_akomodasi, personil_mess.atas_nama, tanggal_mess, personil_mess.jenis_kelamin, kamar_mess, personil_mess.status, tanggal_jam_masuk, tanggal_jam_keluar')->orderBy('atas_nama')->orderBy('tanggal_mess')->findAll();

        if (empty($tangg)) {
            session()->setFlashdata('warning', ['Data tidak ditemukan']);
            return redirect()->to("mess_admin");
        }

        $list_kamar = $this->m_personil_mess->where('status', 1)->where('batal', 0)->join('akomodasi', 'akomodasi.id_akomodasi = personil_mess.id_akomodasi', 'left')->select('id_personil_mess, personil_mess.id_trans, personil_mess.id_akomodasi, personil_mess.atas_nama, tanggal_mess, personil_mess.jenis_kelamin, kamar_mess, personil_mess.status, tanggal_jam_masuk, tanggal_jam_keluar')->findAll();

        // $kamar_mess = $this->m_mess->select('id_mess, nama_kamar, kapasitas_kamar, terpakai')->findAll();

        $data = [
            'trans' => $trans,
            'mess_confirm' => $mess_confirm,
            'date' => $date,
            'list_mess' => $list_mess,
            'tangg' => $tangg,
            'list_kamar' => $list_kamar,
            'id_trans' => $id_trans,
            'id_akomodasi' => $id_akomodasi,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_set_mess_jkt', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function harga_akomodasi($id_trans, $id_akomodasi)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');
        $id_pool = session()->get('pool_pengguna');

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
        
        $nama_hotel = $this->m_akomodasi->where('id_akomodasi', $id_akomodasi)->join('hotel', 'hotel.id_hotel = akomodasi.id_hotel', 'left')->join('mess_kx_jkt', 'mess_kx_jkt.id_hotel = akomodasi.id_hotel', 'left')->select('nama_hotel, jumlah_kamar, kapasitas_kamar, terpakai')->findAll();
        
        if ($nama_hotel[0]['nama_hotel'] == "Mess Kx Jkt") {
            $akomodasi = [
                'id_akomodasi' => $id_akomodasi,
                'status_akomodasi' => 1,
                'harga_akomodasi' => 0,
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];
            $this->m_akomodasi->save($akomodasi);
            session()->setFlashdata('success', ('Transaksi telah dikonfirmasi'));
            return redirect()->to(session()->get('url_kend'));
        }

        $akomodasi = $this->m_akomodasi->where('trans.id_trans', $id_trans)->where('batal_akomodasi =', 0)->where('akomodasi.id_pool', $id_pool)->orwhere('trans.id_trans', $id_trans)->where('batal_akomodasi =', 3)->where('akomodasi.id_pool', $id_pool)->join('trans', 'trans.id_trans = akomodasi.id_trans', 'left')->join('pool', 'pool.id_pool = akomodasi.id_pool', 'left')->select('akomodasi.id_akomodasi, akomodasi.id_trans, akomodasi.id_pool, status_akomodasi, batal_akomodasi')->findAll();

        if (empty($akomodasi)) {
            session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini']);
            return redirect()->to(session()->get('url_kend'));
        }

        if ($akomodasi[0]['status_akomodasi'] == 1) {
            if ($akomodasi[0]['batal_akomodasi'] == 3) {
            
            } else {
                session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini karena telah dikonfirmasi']);
                return redirect()->to(session()->get('url_kend'));
            }
        }

        if($this->request->getMethod() == 'post') {
            if (empty($this->request->getVar('nilai_refund'))) {
                $biaya = $this->request->getVar('harga_akomodasi');
                $comma = ',';
                $number = preg_replace('/[^0-9\\-]+/','', $biaya);
                if( strpos($biaya, $comma) !== false ) {
                    $string = $number/100;
                } else {
                    $string = $number;
                }

                $akomodasi = [
                    'id_akomodasi' => $id_akomodasi,
                    'status_akomodasi' => 1,
                    'harga_akomodasi' => $string,
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];
                $this->m_akomodasi->save($akomodasi);
                session()->setFlashdata('success', ('Transaksi telah dikonfirmasi'));
                return redirect()->to(session()->get('url_kend'));
            } else {
                $biaya = $this->request->getVar('nilai_refund');
                $comma = ',';
                $number = preg_replace('/[^0-9\\-]+/','', $biaya);
                if( strpos($biaya, $comma) !== false ) {
                    $string = $number/100;
                } else {
                    $string = $number;
                }

                $akomodasi = [
                    'id_akomodasi' => $id_akomodasi,
                    'batal_akomodasi' => 4,
                    'refund_akomodasi' => $string,//sampe sini kemaren rabu
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];
                $this->m_akomodasi->save($akomodasi);
                session()->setFlashdata('success', ('Nilai refund berhasil diinputkan'));
                return redirect()->to(session()->get('url_kend'));
            }
        }

        $data = [
            'akomodasi' => $akomodasi,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_harga_akomodasi', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function batal_akomodasi($id_trans, $id_akomodasi)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');
        $id_pool = session()->get('pool_pengguna');

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

        $akomodasi = $this->m_akomodasi->where('trans.id_trans', $id_trans)->where('batal_akomodasi <', 2)->where('akomodasi.id_pool', $id_pool)->join('trans', 'trans.id_trans = akomodasi.id_trans', 'left')->join('pool', 'pool.id_pool = akomodasi.id_pool', 'left')->select('akomodasi.id_akomodasi, akomodasi.id_trans, akomodasi.id_pool, status_akomodasi')->findAll();

        if (empty($akomodasi)) {
            session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini']);
            return redirect()->to(session()->get('url_kend'));
        }

        if($this->request->getMethod() == 'post') {
            $trans = [
                'id_trans' => $id_trans,
                'alasan_batal' => $this->request->getVar('alasan_batal'),
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $akomodasi = [
                'id_akomodasi' => $id_akomodasi,
                'status_mess' => 0,
                'batal_akomodasi' => 2,
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $this->m_trans->save($trans);
            $this->m_akomodasi->save($akomodasi);

            $nama_hotel = $this->m_akomodasi->where('id_akomodasi', $id_akomodasi)->join('hotel', 'hotel.id_hotel = akomodasi.id_hotel', 'left')->join('mess_kx_jkt', 'mess_kx_jkt.id_hotel = akomodasi.id_hotel', 'left')->select('nama_hotel, jumlah_kamar, kapasitas_kamar, terpakai')->findAll();
        
            if ($nama_hotel[0]['nama_hotel'] == "Mess Kx Jkt") {
                $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                
                foreach ($mess as $m => $mes) {
                    if ($mes['terpakai'] == 0) {
                        
                    } else {
                        $terpakai = [
                            'id_mess' => 8,
                            'terpakai' => $mes['terpakai'] - $nama_hotel[0]['jumlah_kamar'],
                            'edited_at' => $timestamp,
                        ];
                        $this->m_mess->save($terpakai);
                    }
                }
            } else {
                
            }
            session()->setFlashdata('success', ('Transaksi telah dibatalkan'));
            return redirect()->to(session()->get('url_kend'));
        }

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_batal_akomodasi', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function batal_kamar_mess($id_trans, $id_akomodasi, $id_mess)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');
        $id_pool = session()->get('pool_pengguna');

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

        $akomodasi = $this->m_akomodasi->where('trans.id_trans', $id_trans)->where('batal_akomodasi <', 2)->where('akomodasi.id_pool', $id_pool)->join('trans', 'trans.id_trans = akomodasi.id_trans', 'left')->join('pool', 'pool.id_pool = akomodasi.id_pool', 'left')->select('akomodasi.id_akomodasi, akomodasi.id_trans, akomodasi.id_pool, status_akomodasi')->findAll();

        if (empty($akomodasi)) {
            session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini']);
            return redirect()->to(session()->get('url_kend'));
        }

        if($this->request->getMethod() == 'post') {
            $trans = [
                'id_trans' => $id_trans,
                'alasan_batal' => $this->request->getVar('alasan_batal'),
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $akomodasi = [
                'id_akomodasi' => $id_akomodasi,
                'id_mess' => null,
                'status_mess' => 0,
                'batal_akomodasi' => 2,
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $this->m_trans->save($trans);
            $this->m_akomodasi->save($akomodasi);

            $nama_hotel = $this->m_akomodasi->where('id_akomodasi', $id_akomodasi)->join('hotel', 'hotel.id_hotel = akomodasi.id_hotel', 'left')->join('mess_kx_jkt', 'mess_kx_jkt.id_hotel = akomodasi.id_hotel', 'left')->select('nama_hotel, jumlah_kamar, kapasitas_kamar, terpakai')->findAll();
        
            if ($nama_hotel[0]['nama_hotel'] == "Mess Kx Jkt") {
                $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                $kamar_mess = $this->m_mess->where('id_mess', $id_mess)->select('terpakai')->findAll();
                
                foreach ($mess as $m => $mes) {
                    if ($mes['terpakai'] == 0) {
                        
                    } else {
                        $terpakai = [
                            'id_mess' => 8,
                            'terpakai' => $mes['terpakai'] - $nama_hotel[0]['jumlah_kamar'],
                            'edited_at' => $timestamp,
                        ];
                        $this->m_mess->save($terpakai);
                    }
                }

                foreach ($kamar_mess as $km => $kames) {
                    if ($kames['terpakai'] == 0) {
                        
                    } else {
                        $terpakai = [
                            'id_mess' => $id_mess,
                            'terpakai' => $kames['terpakai'] - $nama_hotel[0]['jumlah_kamar'],
                            'edited_at' => $timestamp,
                        ];
                        $this->m_mess->save($terpakai);
                    }
                }
            } else {
                
            }
            session()->setFlashdata('success', ('Transaksi telah dibatalkan'));
            return redirect()->to(session()->get('url_kend'));
        }

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_batal_akomodasi', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function batal_akomodasi_confirm($id_trans, $id_akomodasi)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');
        $id_pool = session()->get('pool_pengguna');

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

        $akomodasi = $this->m_akomodasi->where('trans.id_trans', $id_trans)->where('batal_akomodasi <', 2)->where('akomodasi.id_pool', $id_pool)->join('trans', 'trans.id_trans = akomodasi.id_trans', 'left')->join('pool', 'pool.id_pool = akomodasi.id_pool', 'left')->select('akomodasi.id_akomodasi, akomodasi.id_trans, akomodasi.id_pool, status_akomodasi')->findAll();

        if (empty($akomodasi)) {
            session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini']);
            return redirect()->to(session()->get('url_kend'));
        }

        if($this->request->getMethod() == 'post') {
            $trans = [
                'id_trans' => $id_trans,
                'alasan_batal' => $this->request->getVar('alasan_batal'),
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $akomodasi = [
                'id_akomodasi' => $id_akomodasi,
                'status_mess' => 0,
                'batal_akomodasi' => 3,
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $this->m_trans->save($trans);
            $this->m_akomodasi->save($akomodasi);

            $nama_hotel = $this->m_akomodasi->where('id_akomodasi', $id_akomodasi)->join('hotel', 'hotel.id_hotel = akomodasi.id_hotel', 'left')->join('mess_kx_jkt', 'mess_kx_jkt.id_hotel = akomodasi.id_hotel', 'left')->select('nama_hotel, jumlah_kamar, kapasitas_kamar, terpakai')->findAll();
        
            if ($nama_hotel[0]['nama_hotel'] == "Mess Kx Jkt") {
                $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                
                foreach ($mess as $m => $mes) {
                    if ($mes['terpakai'] == 0) {
                        
                    } else {
                        $terpakai = [
                            'id_mess' => 8,
                            'terpakai' => $mes['terpakai'] - $nama_hotel[0]['jumlah_kamar'],
                            'edited_at' => $timestamp,
                        ];
                        $this->m_mess->save($terpakai);
                    }
                }
            } else {
                
            }
            session()->setFlashdata('success', ('Transaksi telah dibatalkan'));
            return redirect()->to(session()->get('url_kend'));
        }

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_batal_akomodasi', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function edit_akomodasi($id_trans, $id_akomodasi)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');
        $id_pool = session()->get('pool_pengguna');

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

        $id_bagian = session()->get('id_bagian');
        $id_detail_pengguna = session()->get('id_detail_pengguna');

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar();
            $jumlah_kamar = $_POST['jumlah_kamar'];
            $tanggal_jam_masuk = $_POST['tanggal_jam_masuk'];
            $tanggal_jam_keluar = $_POST['tanggal_jam_keluar'];

            //Hotel
            $jumlah_kamar = $_POST['jumlah_kamar'];
            $nama_pool = $_POST['gs_hotel'];

            if ($nama_pool == '2. Pool Jakarta') {
                $nama_kota = $_POST['kota_jkt'];
                $nama_akomodasi = $_POST['hotel_jkt'];
            } else {
                $nama_kota = $_POST['kota'];
                $nama_akomodasi = $_POST['hotel'];
            }

            $nama_hotel = substr($nama_akomodasi, 0, strpos($nama_akomodasi, " - "));
            $jenis_kamar = substr($nama_akomodasi, strpos($nama_akomodasi, ' - ') + 3);

            $pool_hotel = $this->m_pool->where('nama_pool', $nama_pool)->select('id_pool')->findAll();
            $kota_hotel = $this->m_kota->where('nama_kota', $nama_kota)->select('id_kota')->findAll();
            $id_hotel = $this->m_hotel->where('nama_hotel', $nama_hotel)->select('id_hotel')->findAll();
            if (empty($id_hotel)) {
                session()->setFlashdata('warning', ['Tidak ada data Hotel']);
                return redirect()->to("edit_akomodasi/".$id_trans."/".$id_akomodasi);
            }
            $id_detail_hotel = $this->m_detail_hotel->where('id_hotel', $id_hotel[0]['id_hotel'])->where('jenis_kamar', $jenis_kamar)->select('id_detail_hotel')->findAll();

            $biaya = $_POST['harga_hotel'];
            $comma = ',';
            $number = preg_replace('/[^0-9\\-]+/','', $biaya);
            if ($number == null) {
                $string = 0;
            } else {
                if( strpos($biaya, $comma) !== false ) {
                    $string = $number/100;
                } else {
                    $string = $number;
                }
            }

            if ($_POST['tamu'] == 'Karyawan Konimex') {
                if(!empty($_POST['nama_select'])) {                
                    for($a = 0; $a < count($_POST['nama_select']); $a++) {
                        $nama_select_h = $_POST["nama_select"];
                        $atas_namaa = implode(" - ", $nama_select_h);

                        $atas_namas = explode(" - ", $atas_namaa);

                        $jumlah = count($atas_namas);

                        for ($i=0; $i<$jumlah; $i++){
                            if ($i % 3 == 0){
                                $atas_namat[$i] = $atas_namas[$i];
                                $jabatant[$i] = $atas_namas[$i+1];
                                $jenis_kelamint[$i] = $atas_namas[$i+2];
                            }
                        }
                        $atas_nama = implode(", ", $atas_namat);
                        $jabatan = implode(", ", $jabatant);
                        $jenis_kelamin = implode(", ", $jenis_kelamint);
                    }
                }

                $pic = $atas_nama;

                $pembayaran = $_POST['pembayaran'];
                if($pembayaran == 'Company Acc'){
                    $pembayaran = 'k';
                } else if($pembayaran == 'Personal Acc'){
                    $pembayaran = 'p';
                }
            } else {
                $atas_nama = $_POST['nama_inputan'];
                $jabatan = $_POST['jabatan_inputan'];
                $jenis_kelamin = null;
                $pic = $atas_nama;

                $pembayaran = $_POST['pembayaran_inputan'];
                if($pembayaran == 'Company Acc'){
                    $pembayaran = 'k';
                } else if($pembayaran == 'Personal Acc'){
                    $pembayaran = 'p';
                }
                $atas_nama = ucwords($atas_nama);
                $jabatan = ucwords($jabatan);
            }
            if (empty($atas_nama)) {
                session()->setFlashdata('warning', ['Nama harus diisi']);
                return redirect()->to("edit_akomodasi/".$id_trans."/".$id_akomodasi);
            }
            if (empty($jabatan)) {
                session()->setFlashdata('warning', ['Jabatan harus diisi']);
                return redirect()->to("edit_akomodasi/".$id_trans."/".$id_akomodasi);
            }

            $perso_mess = explode(", ", $atas_nama);
            if ($jenis_kelamin == null) {
                $jk_mess = explode(", ", $atas_nama);
            } else {
                $jk_mess = explode(", ", $jenis_kelamin);
            }

            if ($pool_hotel[0]['id_pool'] == 1 || $pool_hotel[0]['id_pool'] == 3) {
                $tanggal_jam_masuk = $_POST['tanggal_jam_masuk'];
                $tanggal_jam_keluar = $_POST['tanggal_jam_keluar'];
                $tamu = $_POST['tamu'];
                if ($tanggal_jam_masuk == $tanggal_jam_keluar) {
                    session()->setFlashdata('warning', ['Tanggal jam masuk dan keluar tidak boleh sama']);
                    return redirect()->to("edit_akomodasi/".$id_trans."/".$id_akomodasi);
                }

                $cek_tanggal_mess =  $this->m_tanggal_mess->where('id_trans', $id_trans)->select('id_tanggal_mess')->findAll();

                foreach ($cek_tanggal_mess as $ctm => $ctme) {
                    $id_tanggal_mess = $ctme['id_tanggal_mess'];
                    $aksi = $this->m_tanggal_mess->delete_tanggal_mess($id_tanggal_mess);
                    if($aksi == true) {
                        $this->m_tanggal_mess->query('ALTER TABLE tanggal_mess AUTO_INCREMENT 1');
                    } else {
                        
                    }
                }

                $cek_personil_mess =  $this->m_personil_mess->where('id_trans', $id_trans)->select('id_personil_mess')->findAll();
                
                foreach ($cek_personil_mess as $ctm => $ctme) {
                    $id_personil_mess = $ctme['id_personil_mess'];
                    $aksi = $this->m_personil_mess->delete_personil_mess($id_personil_mess);
                    if($aksi == true) {
                        $this->m_personil_mess->query('ALTER TABLE personil_mess AUTO_INCREMENT 1');
                    } else {
                        
                    }
                }
            } else {
                if ($_POST['pesan_mnj'] == "Iya") {
                    $tamu = "MNJ";
                } else {
                    $tamu = $_POST['tamu'];
                }
                $tanggal_jam_masuk = $_POST['tanggal_jam_masuk_jkt'];
                $tanggal_jam_keluar = $_POST['tanggal_jam_keluar_jkt'];

                if ($tanggal_jam_masuk == $tanggal_jam_keluar) {
                    session()->setFlashdata('warning', ['Tanggal jam masuk dan keluar tidak boleh sama']);
                    return redirect()->to("edit_akomodasi/".$id_trans."/".$id_akomodasi);
                }

                if ($id_hotel[0]['id_hotel'] == 158) {// 158 itu id_hotel untuk Mess Kx Jkt
                    $cek_tanggal_mess =  $this->m_tanggal_mess->where('id_trans', $id_trans)->select('id_tanggal_mess, tanggal_mess, jumlah_personil')->findAll();
                    $cek_personil_mess =  $this->m_personil_mess->where('id_trans', $id_trans)->select('id_personil_mess, atas_nama')->findAll();
                    
                    if (empty($cek_tanggal_mess) && empty($cek_personil_mess)) {
                        // Declare two dates
                        $Date1 = $tanggal_jam_masuk;
                        $Date2 = $tanggal_jam_keluar;

                        // Declare an empty array
                        $date_arr = array();
                            
                        // Use strtotime function
                        $Variable1 = strtotime($Date1);
                        $Variable2 = strtotime($Date2);
                        
                        // Use for loop to store dates into array
                        // 86400 sec = 24 hrs = 60*60*24 = 1 day
                        for ($currentDate = $Variable1; $currentDate <= $Variable2;
                                                        $currentDate += (86400)) {
                            $Store = date('Y-m-d', $currentDate);
                            $Store1 = date('H:i:s', $currentDate);
                            
                            foreach ($perso_mess as $pe => $per) {
                                $pers[$pe] = $per;
                            }

                            if ($jenis_kelamin == null) {
                                foreach ($jk_mess as $jk => $jkm) {
                                    $personil_mess_ako[] = [
                                        'id_trans' => $id_trans,
                                        'atas_nama' => $pers[$jk],
                                        'jenis_kelamin' => null,
                                        'tanggal_mess' => $Store,
                                        'status' => 0,
                                        'batal' => 0,
                                    ];
                                }
                            } else {
                                foreach ($jk_mess as $jk => $jkm) {
                                    $personil_mess_ako[] = [
                                        'id_trans' => $id_trans,
                                        'atas_nama' => $pers[$jk],
                                        'jenis_kelamin' => $jkm,
                                        'tanggal_mess' => $Store,
                                        'status' => 0,
                                        'batal' => 0,
                                    ];
                                }
                            }

                            $tanggal_mess_ako[] = [
                                'id_trans' => $id_trans,
                                'tanggal_mess' => $Store,
                                'jumlah_personil' => $_POST['jumlah_kamar'],
                                'status' => 0,
                                'batal' => 0,
                            ];

                            $cek_tanggal_mess =  $this->m_tanggal_mess->where('tanggal_mess', $Store)->where('status', 0)->where('batal', 0)->select('tanggal_mess, jumlah_personil, sum(jumlah_personil) as sum')->findAll();
                            
                            foreach ($cek_tanggal_mess as $ctm => $ctme) {
                                $sum = $ctme['sum'] + $_POST['jumlah_kamar'];
                                if ($sum > 18) {
                                    session()->setFlashdata('warning', ['Mess Kx Jkt sudah penuh untuk hari '.tanggal_indo($ctme['tanggal_mess'])]);
                                    return redirect()->to("edit_akomodasi/".$id_trans."/".$id_akomodasi);
                                }
                            }
                        }
                    } else {
                        // foreach ($cek_personil_mess as $cp => $cek) {
                        //     foreach ($perso_mess as $pe => $per) {
                        //         $pers[$pe] = $per;
                        //     }
                        //     d($cek['atas_nama']);
                        //     d($pers[$cp]);
                        // }
                    }
                } else {
                    $cek_tanggal_mess =  $this->m_tanggal_mess->where('id_trans', $id_trans)->select('id_tanggal_mess, tanggal_mess, jumlah_personil')->findAll();

                    foreach ($cek_tanggal_mess as $ctm => $ctme) {
                        $id_tanggal_mess = $ctme['id_tanggal_mess'];
                        $aksi = $this->m_tanggal_mess->delete_tanggal_mess($id_tanggal_mess);
                        if($aksi == true) {
                            $this->m_tanggal_mess->query('ALTER TABLE tanggal_mess AUTO_INCREMENT 1');
                        } else {
                            
                        }
                    }

                    $cek_personil_mess =  $this->m_personil_mess->where('id_trans', $id_trans)->select('id_personil_mess')->findAll();
                    
                    foreach ($cek_personil_mess as $ctm => $ctme) {
                        $id_personil_mess = $ctme['id_personil_mess'];
                        $aksi = $this->m_personil_mess->delete_personil_mess($id_personil_mess);
                        if($aksi == true) {
                            $this->m_personil_mess->query('ALTER TABLE personil_mess AUTO_INCREMENT 1');
                        } else {
                            
                        }
                    }
                }
            }

            $keterangan_akomodasi = $_POST['keterangan_akomodasi'];
            if(empty($keterangan_akomodasi)){
                $keterangan_akomodasi = null;
            }

            if ($nama_hotel == "Mess Kx Jkt") {
                $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                $status_mess = 1;
                
                // foreach ($mess as $m => $mes) {
                //     if ($mes['terpakai'] == 18) {
                //         session()->setFlashdata('warning', ['Mess Kx Jkt sudah penuh']);
                //         return redirect()->to("edit_akomodasi/".$id_trans."/".$id_akomodasi);
                //     } else {
                //         $terpakai = [
                //             'id_mess' => 8,
                //             'terpakai' => $mes['terpakai'] + $jumlah_kamar,
                //             'edited_at' => $timestamp,
                //         ];
                //         $this->m_mess->save($terpakai);
                //     }
                // }
            } else {
                $status_mess = 0;
            }

            if(!empty($atas_nama) && !empty($jabatan) && !empty($_POST['jumlah_kamar']) && !empty($tanggal_jam_masuk) && !empty($tanggal_jam_keluar)) {
                $trans = [
                    'id_trans' => $id_trans,
                    'pic' => $pic,
                    'tamu' => $_POST['tamu'],
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];

                $akomodasi = [
                    'id_akomodasi' => $id_akomodasi,
                    'id_trans' => $id_trans,
                    'id_hotel' => $id_hotel[0]['id_hotel'],
                    'id_detail_hotel' => $id_detail_hotel[0]['id_detail_hotel'],
                    'id_pool' => $pool_hotel[0]['id_pool'],
                    'id_kota' => $kota_hotel[0]['id_kota'],
                    'atas_nama' => $atas_nama,
                    'jabatan' => $jabatan,
                    'jenis_kelamin' => $jenis_kelamin,
                    'type' => $_POST['type'],
                    'jumlah_kamar' => $_POST['jumlah_kamar'],
                    'pembayaran' => $pembayaran,
                    'harga_akomodasi' => $string,
                    'tanggal_jam_masuk' => $tanggal_jam_masuk,
                    'tanggal_jam_keluar' => $tanggal_jam_keluar,
                    'status_mess' => $status_mess,
                    'keterangan_akomodasi' => $keterangan_akomodasi,
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];
            } else {
                if(empty($atas_nama)) {
                    if ($nama_hotel == "Mess Kx Jkt") {
                        $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                        
                        foreach ($mess as $m => $mes) {
                            if ($mes['terpakai'] == 0) {
                                
                            } else {
                                $terpakai = [
                                    'id_mess' => 8,
                                    'terpakai' => $mes['terpakai'] - $jumlah_kamar,
                                    'edited_at' => $timestamp,
                                ];
                                $this->m_mess->save($terpakai);
                            }
                        }
                    }
                    session()->setFlashdata('warning', ['Nama harus diisi']);
                    return redirect()->to("edit_akomodasi/".$id_trans."/".$id_akomodasi);
                } else if(empty($jabatan)) {
                    if ($nama_hotel == "Mess Kx Jkt") {
                        $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                        
                        foreach ($mess as $m => $mes) {
                            if ($mes['terpakai'] == 0) {
                                
                            } else {
                                $terpakai = [
                                    'id_mess' => 8,
                                    'terpakai' => $mes['terpakai'] - $jumlah_kamar,
                                    'edited_at' => $timestamp,
                                ];
                                $this->m_mess->save($terpakai);
                            }
                        }
                    }
                    session()->setFlashdata('warning', ['Jabatan harus diisi']);
                    return redirect()->to("edit_akomodasi/".$id_trans."/".$id_akomodasi);
                } else if(empty($_POST['jumlah_kamar'])) {
                    if ($nama_hotel == "Mess Kx Jkt") {
                        $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                        
                        foreach ($mess as $m => $mes) {
                            if ($mes['terpakai'] == 0) {
                                
                            } else {
                                $terpakai = [
                                    'id_mess' => 8,
                                    'terpakai' => $mes['terpakai'] - $jumlah_kamar,
                                    'edited_at' => $timestamp,
                                ];
                                $this->m_mess->save($terpakai);
                            }
                        }
                    }
                    session()->setFlashdata('warning', ['Jumlah Kamar harus diisi']);
                    return redirect()->to("edit_akomodasi/".$id_trans."/".$id_akomodasi);
                } else if(empty($tanggal_jam_masuk)) {
                    if ($nama_hotel == "Mess Kx Jkt") {
                        $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                        
                        foreach ($mess as $m => $mes) {
                            if ($mes['terpakai'] == 0) {
                                
                            } else {
                                $terpakai = [
                                    'id_mess' => 8,
                                    'terpakai' => $mes['terpakai'] - $jumlah_kamar,
                                    'edited_at' => $timestamp,
                                ];
                                $this->m_mess->save($terpakai);
                            }
                        }
                    }
                    session()->setFlashdata('warning', ['Tanggal dan Jam Masuk harus diisi']);
                    return redirect()->to("edit_akomodasi/".$id_trans."/".$id_akomodasi);
                } else if(empty($tanggal_jam_keluar)) {
                    if ($nama_hotel == "Mess Kx Jkt") {
                        $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
                        
                        foreach ($mess as $m => $mes) {
                            if ($mes['terpakai'] == 0) {
                                
                            } else {
                                $terpakai = [
                                    'id_mess' => 8,
                                    'terpakai' => $mes['terpakai'] - $jumlah_kamar,
                                    'edited_at' => $timestamp,
                                ];
                                $this->m_mess->save($terpakai);
                            }
                        }
                    }
                    session()->setFlashdata('warning', ['Tanggal dan Jam Keluar harus diisi']);
                    return redirect()->to("edit_akomodasi/".$id_trans."/".$id_akomodasi);
                }
            }

            $this->m_trans->save($trans);
            $this->m_akomodasi->save($akomodasi);

            if (empty($tanggal_mess_ako)) {
                
            } else {
                $this->m_tanggal_mess->insertBatch($tanggal_mess_ako);
            }

            if (empty($personil_mess_ako)) {
                
            } else {
                $this->m_personil_mess->insertBatch($personil_mess_ako);
            }
            session()->setFlashdata('success', ('Data berhasil diedit'));
            return redirect()->to(session()->get('url_kend'));
        }
        
        $trans = $this->m_trans->where('trans.id_trans', $id_trans)->where('batal_akomodasi =', 0)->where('akomodasi.id_pool', $id_pool)->join('akomodasi', 'akomodasi.id_trans = trans.id_trans', 'left')->select('trans.id_trans, akomodasi.id_pool, tanggal_jam_keluar, id_bagian, trans.created_at')->orderBy('created_at', 'desc')->findAll();
        
        $akomodasi = $this->m_akomodasi->where('trans.id_trans', $id_trans)->where('batal_akomodasi =', 0)->where('akomodasi.id_pool', $id_pool)->join('trans', 'trans.id_trans = akomodasi.id_trans', 'left')->join('hotel', 'hotel.id_hotel = akomodasi.id_hotel', 'left')->join('detail_hotel', 'detail_hotel.id_detail_hotel = akomodasi.id_detail_hotel', 'left')->join('pool', 'pool.id_pool = akomodasi.id_pool', 'left')->join('kota', 'kota.id_kota = akomodasi.id_kota', 'left')->select('akomodasi.id_trans, akomodasi.id_akomodasi, akomodasi.id_pool, akomodasi.id_hotel, akomodasi.id_detail_hotel, nama_hotel, jenis_kamar, tamu, nama_pool, nama_kota, type, pic, peminta, atas_nama, jabatan, jenis_kelamin, jumlah_kamar, harga_akomodasi, pembayaran, tanggal_jam_masuk, akomodasi.tanggal_jam_keluar, keterangan_akomodasi, status_akomodasi, status_mess, akomodasi.created_at')->findAll();

        if (empty($akomodasi)) {
            session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini']);
            return redirect()->to(session()->get('url_kend'));
        }

        // if ($akomodasi[0]['status_akomodasi'] == 1) {
        //     session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini karena telah dikonfirmasi']);
        //     return redirect()->to(session()->get('url_kend'));
        // }

        foreach ($akomodasi as $ak => $ako) {
            $atas_nama_akomodasi = explode(', ', $ako['atas_nama']);
            $jabatan_akomodasi = explode(', ', $ako['jabatan']);
            $jenis_kelamin_akomodasi = explode(', ', $ako['jenis_kelamin']);

            foreach ($atas_nama_akomodasi as $ti => $tik) {
                $atas_nama[$ti] = $tik;
            }

            foreach ($jabatan_akomodasi as $ja => $jab) {
                $jabatan[$ja] = $jab;
            }

            foreach ($jenis_kelamin_akomodasi as $je => $jen) {
                $result[] = [
                    'atas_nama' => $atas_nama[$je],
                    'jabatan' => $jabatan[$je],
                    'jenis_kelamin' => $jen,
                ];
            }
        }

        // $result = array_merge($atas_nama_akomodasi, $jabatan_akomodasi, $jenis_kelamin_akomodasi);

        // $jumlah = count($result);

        // for ($i=0; $i<$jumlah; $i++){
        //     if ($i % 2 == 0){
        //         $atas_namat[$i] = $result[$i];
        //         $jabatant[$i] = $result[$i+1];
        //     }
        // }
        // $atas_nama = implode(" - ", $atas_namat);
        // $jabatan = implode(" - ", $jabatant);

        // $merged = $atas_nama;
        // $merged .= ", ";
        // $merged .= $jabatan;

        // $explode = explode(", ", $merged);

        $kota_akomodasi = $this->m_kota->where('nama_kota', $akomodasi[0]['nama_kota'])->select('id_kota, id_negara')->findAll();

        $negara_akomodasi = $this->m_negara->where('id_negara', $kota_akomodasi[0]['id_negara'])->select('id_negara, nama_negara')->findAll();

        $pool = $this->m_pool->select('nama_pool')->findAll();
        $negara_hotel = $this->m_negara->where('id_negara =', $negara_akomodasi[0]['id_negara'])->select('id_negara, nama_negara')->orderBy('nama_negara', 'asc')->findAll(); 
        $negara = $this->m_negara->select('id_negara, nama_negara')->orderBy('nama_negara', 'asc')->findAll();
        $kota = $this->m_kota->select('id_kota, id_negara, nama_kota')->orderBy('nama_kota', 'asc')->findAll();
        $kota_hide = $this->m_kota->where('id_kota !=', $kota_akomodasi[0]['id_kota'])->select('id_kota, id_negara, nama_kota')->orderBy('nama_kota', 'asc')->findAll();
        $kota_hotel = $this->m_kota->where('id_kota =', $kota_akomodasi[0]['id_kota'])->select('id_kota, id_negara, nama_kota')->orderBy('nama_kota', 'asc')->findAll();

        $hotel_jkt = $this->m_hotel->join('kota', 'kota.id_kota = hotel.id_kota', 'left')->join('detail_hotel', 'detail_hotel.id_hotel = hotel.id_hotel', 'left')->where('jenis_kamar !=', null)->where('nama_kota', 'Jakarta')->select('hotel.id_hotel, nama_hotel, nama_kota, alamat_hotel, telp_hotel, email_hotel, bintang_hotel, jenis_kamar, price_kamar, tgl_valid')->orderBy('nama_hotel', 'asc')->findAll();

        $hotel = $this->m_hotel->join('kota', 'kota.id_kota = hotel.id_kota', 'left')->join('detail_hotel', 'detail_hotel.id_hotel = hotel.id_hotel', 'left')->where('jenis_kamar !=', null)->where('nama_hotel !=', 'Mess Kx Jkt')->select('hotel.id_hotel, nama_hotel, nama_kota, alamat_hotel, telp_hotel, email_hotel, bintang_hotel, jenis_kamar, price_kamar, tgl_valid')->orderBy('nama_hotel', 'asc')->findAll();
        $mess = $this->m_mess->where('nama_kamar', 'mess')->select('kapasitas_kamar, terpakai')->findAll();
        $pengguna = $this->m_detail_pengguna->where('detail_pengguna.id_bagian', $trans[0]['id_bagian'])->join('pengguna', 'pengguna.id_pengguna = detail_pengguna.id_pengguna', 'left')->join('jabatan', 'jabatan.id_jabatan = detail_pengguna.id_jabatan', 'left')->select('detail_pengguna.id_pengguna, nama_pengguna, nama_jabatan, jenis_kelamin')->orderBy('nama_pengguna', 'asc')->groupBy('id_pengguna')->findAll();

        $data = [
            'trans' => $trans,
            'akomodasi' => $akomodasi,
            'result' => $result,
            'pool' => $pool,
            'negara_akomodasi' => $negara_akomodasi,
            'negara_hotel' => $negara_hotel,
            'negara' => $negara,
            'kota' => $kota,
            'kota_hide' => $kota_hide,
            'kota_hotel' => $kota_hotel,
            'hotel' => $hotel,
            'mess' => $mess,
            'pengguna' => $pengguna,
            'hotel_jkt' => $hotel_jkt,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_edit_akomodasi', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }
    
    public function transport_admin()
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');
        $id_pool = session()->get('pool_pengguna');

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

        if($this->request->getVar('aksi') == 'confirm' && $this->request->getVar('id_trans') && $this->request->getVar('id_transportasi')) {
            $transportasi_id = $this->m_transportasi->transportasi_id($this->request->getVar('id_transportasi'));
            if($transportasi_id['id_transportasi']) {//memastikan bahwa ada data
                session()->setFlashdata('success', ('Silahkan set driver dan mobil untuk transaksi ini'));
                return redirect()->to("set_driver/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_transportasi'));
            }
        }

        if($this->request->getVar('aksi') == 'batal' && $this->request->getVar('id_trans') && $this->request->getVar('id_transportasi')) {
            $transportasi_id = $this->m_transportasi->transportasi_id($this->request->getVar('id_transportasi'));
            if($transportasi_id['id_transportasi']) {//memastikan bahwa ada data
                session()->setFlashdata('success', ('Masukkan alasan batal'));
                return redirect()->to("batal_transport/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_transportasi'));
            }
        }

        if($this->request->getVar('aksi') == 'batal_confirm' && $this->request->getVar('id_trans') && $this->request->getVar('id_transportasi')) {
            $transportasi_id = $this->m_transportasi->transportasi_id($this->request->getVar('id_transportasi'));
            if($transportasi_id['id_transportasi']) {//memastikan bahwa ada data
                session()->setFlashdata('success', ('Masukkan alasan batal'));
                return redirect()->to("batal_transport_confirm/".$this->request->getVar('id_trans')."/".$this->request->getVar('id_transportasi'));
            }
        }

        if($this->request->getVar('aksi') == 'tolak_permintaan_batal' && $this->request->getVar('id_trans') && $this->request->getVar('id_transportasi')) {
            $transportasi_id = $this->m_transportasi->transportasi_id($this->request->getVar('id_transportasi'));
            if($transportasi_id['id_transportasi']) {//memastikan bahwa ada data
                $trans = [
                    'id_trans' => $this->request->getVar('id_trans'),
                    'alasan_batal' => null,
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];
    
                $transportasi = [
                    'id_transportasi' => $this->request->getVar('id_transportasi'),
                    'batal_transportasi' => 0,
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];
    
                $this->m_trans->save($trans);
                $this->m_transportasi->save($transportasi);
                session()->setFlashdata('success', ('Transaksi tidak jadi dibatalkan'));
                return redirect()->to("transport_admin");
            }
        }

        $id_bagian = session()->get('id_bagian');

        $id_detail_pengguna = session()->get('id_detail_pengguna');

        $trans = $this->m_trans->where('batal_transportasi <', 2)->where('transportasi.tanggal_mobil >=', $date)->where('transportasi.id_pool', $id_pool)->orwhere('transportasi_jemput.tanggal_mobil >=', $date)->where('transportasi_jemput.id_pool', $id_pool)->join('transportasi', 'transportasi.id_trans = trans.id_trans', 'left')->join('transportasi_jemput', 'transportasi_jemput.id_trans = trans.id_trans', 'left')->select('trans.id_trans, transportasi.id_pool, transportasi.tanggal_mobil, transportasi.jam_siap, transportasi_jemput.id_pool, transportasi_jemput.tanggal_mobil, batal_transportasi, trans.created_at, transportasi.created_at')->orderBy('transportasi.created_at', 'desc')->findAll();

        $transportasi_antar = $this->m_transportasi->where('batal_transportasi <', 2)->where('transportasi.tanggal_mobil >=', $date)->where('transportasi.id_pool', $id_pool)->join('trans', 'trans.id_trans = transportasi.id_trans', 'left')->join('pool', 'pool.id_pool = transportasi.id_pool', 'left')->join('transportasi_jemput', 'transportasi_jemput.id_transportasi = transportasi.id_transportasi', 'left')->select('transportasi.id_transportasi, transportasi.id_trans, transportasi.peminta, pic, nama_pool, transportasi.jemput, transportasi.jenis_kendaraan, transportasi.dalkot_lukot, transportasi.menginap, transportasi.kapasitas, transportasi.jumlah_mobil, transportasi.tanggal_mobil, transportasi.tujuan_mobil, transportasi.siap_di, transportasi.jam_siap, transportasi.atas_nama, transportasi.jabatan, transportasi.keterangan_mobil, transportasi.status_mobil, batal_transportasi, transportasi.created_at')->findAll();
        
        $transportasi_jemput = $this->m_transportasi_jemput->where('transportasi_jemput.tanggal_mobil >=', $date)->where('transportasi_jemput.jemput =', 1)->where('transportasi_jemput.id_pool', $id_pool)->join('trans', 'trans.id_trans = transportasi_jemput.id_trans', 'left')->join('transportasi', 'transportasi.id_transportasi = transportasi_jemput.id_transportasi', 'left')->join('pool', 'pool.id_pool = transportasi_jemput.id_pool', 'left')->select('transportasi_jemput.id_transportasi, transportasi_jemput.id_transportasi_jemput, transportasi_jemput.id_trans, transportasi_jemput.jemput, transportasi_jemput.peminta, pic, nama_pool, transportasi_jemput.atas_nama, transportasi_jemput.jabatan, transportasi_jemput.jenis_kendaraan, transportasi_jemput.dalkot_lukot, transportasi_jemput.menginap, transportasi_jemput.kapasitas, transportasi_jemput.jumlah_mobil, transportasi_jemput.tanggal_mobil, transportasi_jemput.tujuan_mobil, transportasi_jemput.siap_di, transportasi_jemput.jam_siap, transportasi_jemput.keterangan_mobil, transportasi_jemput.status_mobil, batal_transportasi_jemput, batal_transportasi, transportasi.created_at')->findAll();

        $transportasi_antar_jemput1 = $this->m_transportasi->where('transportasi.tanggal_mobil >=', $date)->where('transportasi.jemput =', 2)->where('transportasi.id_pool', $id_pool)->join('trans', 'trans.id_trans = transportasi.id_trans', 'left')->join('pool', 'pool.id_pool = transportasi.id_pool', 'left')->join('transportasi_jemput', 'transportasi_jemput.id_transportasi = transportasi.id_transportasi', 'left')->select('transportasi.id_transportasi, transportasi.id_trans, transportasi.peminta, pic, nama_pool, transportasi.jemput, transportasi.jenis_kendaraan, transportasi.dalkot_lukot, transportasi.menginap, transportasi.kapasitas, transportasi.jumlah_mobil, transportasi.tanggal_mobil, transportasi.tujuan_mobil, transportasi.siap_di, transportasi.jam_siap, transportasi.atas_nama, transportasi.jabatan, transportasi.keterangan_mobil, transportasi.status_mobil, batal_transportasi_jemput, transportasi.created_at')->findAll();
        
        $transportasi_antar_jemput2 = $this->m_transportasi_jemput->where('transportasi_jemput.tanggal_mobil >=', $date)->where('transportasi_jemput.jemput =', 2)->where('transportasi_jemput.id_pool', $id_pool)->join('trans', 'trans.id_trans = transportasi_jemput.id_trans', 'left')->join('transportasi', 'transportasi.id_transportasi = transportasi_jemput.id_transportasi', 'left')->join('pool', 'pool.id_pool = transportasi_jemput.id_pool', 'left')->select('transportasi_jemput.id_transportasi, transportasi_jemput.id_transportasi_jemput, transportasi_jemput.id_trans, transportasi_jemput.jemput, transportasi_jemput.peminta, pic, nama_pool, transportasi_jemput.atas_nama, transportasi_jemput.jabatan, transportasi_jemput.jenis_kendaraan, transportasi_jemput.dalkot_lukot, transportasi_jemput.menginap, transportasi_jemput.kapasitas, transportasi_jemput.jumlah_mobil, transportasi_jemput.tanggal_mobil, transportasi_jemput.tujuan_mobil, transportasi_jemput.siap_di, transportasi_jemput.jam_siap, transportasi_jemput.keterangan_mobil, transportasi_jemput.status_mobil, batal_transportasi_jemput, transportasi.created_at')->findAll();

        $status_pengemudi = $this->m_pengemudi->where('tanggal_jam_awal >', $time)->where('id_pool', $id_pool)->orwhere('tanggal_jam_akhir <', $time)->where('id_pool', $id_pool)->orwhere('tanggal_jam_awal !=', null)->where('tanggal_jam_akhir', null)->where('id_pool', $id_pool)->orwhere('tanggal_jam_awal', null)->where('tanggal_jam_akhir', null)->where('id_pool', $id_pool)->select('id_pengemudi, nama_pengemudi, jenis_sopir, nomor_hp, email')->orderBy('nama_pengemudi', 'asc')->findAll();

        $pengemudi = $this->m_pengemudi->where('id_pool', $id_pool)->orderBy('nama_pengemudi', 'asc')->findAll();

        $set_driver = $this->m_set_driver->select('id_trans, end')->findAll();

        $mobil_pengemudi = $this->m_pengemudi->join('mobil', 'mobil.id_mobil = pengemudi.id_mobil', 'left')->select('id_pengemudi, pengemudi.id_mobil, nama_mobil')->findAll();

        $data = [
            'trans' => $trans,
            'transportasi_antar' => $transportasi_antar,
            'transportasi_jemput' => $transportasi_jemput,
            'transportasi_antar_jemput1' => $transportasi_antar_jemput1,
            'transportasi_antar_jemput2' => $transportasi_antar_jemput2,

            'pengemudi' => $pengemudi,
            'mobil_pengemudi' => $mobil_pengemudi,
            'status_pengemudi' => $status_pengemudi,

            'set_driver' => $set_driver,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_transport_admin', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function batal_transport($id_trans, $id_transportasi)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');
        $id_pool = session()->get('pool_pengguna');

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

        $transportasi = $this->m_transportasi->where('trans.id_trans', $id_trans)->where('batal_transportasi <', 2)->where('transportasi.tanggal_mobil >=', $date)->where('transportasi.id_pool', $id_pool)->join('trans', 'trans.id_trans = transportasi.id_trans', 'left')->join('pool', 'pool.id_pool = transportasi.id_pool', 'left')->join('transportasi_jemput', 'transportasi_jemput.id_transportasi = transportasi.id_transportasi', 'left')->select('transportasi.id_transportasi, transportasi.id_trans, transportasi.id_pool, transportasi.status_mobil')->findAll();

        if (empty($transportasi)) {
            session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini']);
            return redirect()->to('transport_admin');
        }

        if($this->request->getMethod() == 'post') {
            $trans = [
                'id_trans' => $id_trans,
                'alasan_batal' => $this->request->getVar('alasan_batal'),
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $transportasi = [
                'id_transportasi' => $id_transportasi,
                'batal_transportasi' => 2,
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $this->m_trans->save($trans);
            $this->m_transportasi->save($transportasi);
            session()->setFlashdata('success', ('Transaksi telah dibatalkan'));
            return redirect()->to("transport_admin");
        }

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_batal_transport', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function batal_transport_confirm($id_trans, $id_transportasi)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');
        $id_pool = session()->get('pool_pengguna');

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

        $transportasi = $this->m_transportasi->where('trans.id_trans', $id_trans)->where('batal_transportasi <', 2)->where('transportasi.tanggal_mobil >=', $date)->where('transportasi.id_pool', $id_pool)->join('trans', 'trans.id_trans = transportasi.id_trans', 'left')->join('pool', 'pool.id_pool = transportasi.id_pool', 'left')->join('transportasi_jemput', 'transportasi_jemput.id_transportasi = transportasi.id_transportasi', 'left')->select('transportasi.id_transportasi, transportasi.id_trans, transportasi.id_pool, transportasi.status_mobil')->findAll();

        if (empty($transportasi)) {
            session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini']);
            return redirect()->to('transport_admin');
        }

        if($this->request->getMethod() == 'post') {
            $trans = [
                'id_trans' => $id_trans,
                'alasan_batal' => $this->request->getVar('alasan_batal'),
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $transportasi = [
                'id_transportasi' => $id_transportasi,
                'batal_transportasi' => 3,
                'edited_by' => session()->get('login_by'),
                'edited_at' => $timestamp,
            ];

            $this->m_trans->save($trans);
            $this->m_transportasi->save($transportasi);
            session()->setFlashdata('success', ('Transaksi telah dibatalkan'));
            return redirect()->to("transport_admin");
        }

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_batal_transport', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function loadData()
	{
		// on page load this ajax code block will be run
		// $data = $event->where([
		// 	'title' => $this->request->getVar('title'),
		// 	'start' => $this->request->getVar('start'),
		// ])->findAll();
        $id_pool = session()->get('pool_pengguna');

        $query = $this->m_set_driver->where('id_pool', $id_pool)->select('id, id_transportasi, title, start, end, description')->orderBy('id', 'asc')->findAll();

		return json_encode($query);
	}

	public function ajax()
	{
		$event = new EventModel();

		switch ($this->request->getVar('type')) {

				// For add EventModel
			case 'add':
				$data = [
					'title' => $this->request->getVar('title'),
					'description' => $this->request->getVar('description'),
					'start' => $this->request->getVar('start'),
					'end' => $this->request->getVar('end'),
				];
				$event->insert($data);
				return json_encode($event);
				break;

				// For update EventModel        
			case 'update':
				$data = [
					'title' => $this->request->getVar('title'),
					'start' => $this->request->getVar('start'),
					'end' => $this->request->getVar('end'),
				];

				$event_id = $this->request->getVar('id');
				
				$event->update($event_id, $data);

				return json_encode($event);
				break;

				// For delete EventModel    
			case 'delete':

				$event_id = $this->request->getVar('id');

				$event->delete($event_id);

				return json_encode($event);
				break;

			default:
				break;
		}
	}

    public function set_driver($id_trans, $id_transportasi)
    {
        $data = [];
        
        $admin_gs = session()->get('admin_gs');
        $id_pool = session()->get('pool_pengguna');

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

        if($this->request->getMethod() == 'post') {
            $cek_driver = $this->m_set_driver->where('id_transportasi', $id_transportasi)->select('id ,id_transportasi')->findAll();

            if(empty($this->request->getVar('hapus_id'))){
                if (empty($cek_driver)) {
                    $id_pengemudi = $this->m_pengemudi->where('nama_pengemudi', $this->request->getVar('nama_pengemudi'))->select('id_pengemudi')->first();

                    $id_mobil = $this->m_mobil->where('nama_mobil', $this->request->getVar('nama_mobil'))->select('id_mobil')->first();
                    $tanggal_kalender = $this->request->getVar('start_time');
                    $tanggal_mobil = $this->request->getVar('tanggal_mobil');
                    $jam_siap = $this->request->getVar('jam_siap');
                    $menginap = $this->request->getVar('menginap');
                    if ($menginap == '0') {
                        $title = $this->request->getVar('nama_pengemudi');
                    } else {
                        $title = $this->request->getVar('nama_pengemudi')." (Menginap)";
                    }

                    $satuan_waktu = $this->request->getVar('satuan_waktu');
                    if ($satuan_waktu == 'Hari') {
                        $durasi = ((int)$this->request->getVar('durasi') * 86400);
                    } else if ($satuan_waktu == 'Jam') {
                        $durasi = ((int)$this->request->getVar('durasi') * 3600);
                    } else {
                        $durasi = ((int)$this->request->getVar('durasi') * 60);
                    }

                    $tanggal_waktu_start = tanggal_waktu($tanggal_kalender, $jam_siap);
                    $epoch_awal = strtotime($tanggal_waktu_start) + $durasi;
                    $end = date("Y-m-d H:i:s", substr($epoch_awal, 0, 10));
                    $epoch_akhir = date("Y-m-d H:i:s", substr($epoch_awal, 0, 10));
                    $jam_selesai = waktu($epoch_akhir);
                    $tanggal_waktu_end = tanggal_waktu($tanggal_kalender, $jam_selesai);
                    
                    $cek_jadwal = $this->m_set_driver->where('id_pengemudi', $id_pengemudi['id_pengemudi'])->select('id, start, end')->findAll();

                    if (empty($cek_jadwal)) {
                        
                    } else {
                        foreach ($cek_jadwal as $cj => $jad) {
                            if (strtotime($tanggal_waktu_start) >= strtotime($jad['start']) && strtotime($tanggal_waktu_start) < strtotime($jad['end'])) {
                                session()->setFlashdata('warning', ['Driver yang bersangkutan sudah ada agenda pengantaran di jam tersebut']);
                                return redirect()->to('set_driver/'.$id_trans."/".$id_transportasi);
                            }
                        }
                    }
                    
                    $driver = [
                        'id_trans' => $id_trans,
                        'id_transportasi' => $id_transportasi,
                        'id_pengemudi' => $id_pengemudi['id_pengemudi'],
                        'id_mobil' => $id_mobil['id_mobil'],
                        'id_pool' => $this->request->getVar('id_pool'),
                        'title' => $title,
                        'description' => $this->request->getVar('description'),
                        'start' => $tanggal_waktu_start,
                        'end' => null,
                        'tanggal_mobil' => $tanggal_kalender,
                        'tujuan_mobil' => $this->request->getVar('tujuan_mobil'),
                        'edited_by' => session()->get('login_by'),
                        'edited_at' => $timestamp,
                    ];
    
                    $transportasi = [
                        'id_transportasi' => $id_transportasi,
                        'id_pengemudi' => $id_pengemudi['id_pengemudi'],
                        'id_mobil' => $id_mobil['id_mobil'],
                        'tanggal_mobil' => $tanggal_kalender,
                        'jam_siap' => $jam_siap,
                        'status_mobil' => 1,
                        'edited_by' => session()->get('login_by'),
                        'edited_at' => $timestamp,
                    ];

                    $this->m_set_driver->insert($driver);
                    $this->m_transportasi->save($transportasi);
                    session()->setFlashdata('success', ('Driver telah diset'));
                    return redirect()->to("transport_admin");
                } else {
                    $id_pengemudi = $this->m_pengemudi->where('nama_pengemudi', $this->request->getVar('nama_pengemudi'))->select('id_pengemudi')->first();

                    $id_mobil = $this->m_mobil->where('nama_mobil', $this->request->getVar('nama_mobil'))->select('id_mobil')->first();
                    $tanggal_kalender = $this->request->getVar('start_time');
                    $tanggal_mobil = $this->request->getVar('tanggal_mobil');
                    $jam_siap = $this->request->getVar('jam_siap');
                    $menginap = $this->request->getVar('menginap');
                    if ($menginap == '0') {
                        $title = $this->request->getVar('nama_pengemudi');
                    } else {
                        $title = $this->request->getVar('nama_pengemudi')." (Menginap)";
                    }

                    $satuan_waktu = $this->request->getVar('satuan_waktu');
                    if ($satuan_waktu == 'Hari') {
                        $durasi = ((int)$this->request->getVar('durasi') * 86400);
                    } else if ($satuan_waktu == 'Jam') {
                        $durasi = ((int)$this->request->getVar('durasi') * 3600);
                    } else {
                        $durasi = ((int)$this->request->getVar('durasi') * 60);
                    }

                    $tanggal_waktu_start = tanggal_waktu($tanggal_kalender, $jam_siap);
                    $epoch_awal = strtotime($tanggal_waktu_start) + $durasi;
                    $end = date("Y-m-d H:i:s", substr($epoch_awal, 0, 10));
                    $epoch_akhir = date("Y-m-d H:i:s", substr($epoch_awal, 0, 10));
                    $jam_selesai = waktu($epoch_akhir);
                    $tanggal_waktu_end = tanggal_waktu($tanggal_kalender, $jam_selesai);
                    
                    $cek_jadwal = $this->m_set_driver->where('id_pengemudi', $id_pengemudi['id_pengemudi'])->select('id, start, end')->findAll();

                    if (empty($cek_jadwal)) {
                        
                    } else {
                        foreach ($cek_jadwal as $cj => $jad) {
                            if (strtotime($tanggal_waktu_start) >= strtotime($jad['start']) && strtotime($tanggal_waktu_start) < strtotime($jad['end'])) {
                                session()->setFlashdata('warning', ['Driver yang bersangkutan sudah ada agenda pengantaran di jam tersebut']);
                                return redirect()->to('set_driver/'.$id_trans."/".$id_transportasi);
                            }
                        }
                    }

                    $driver = [
                        'id' => $cek_driver[0]['id'],
                        'id_pengemudi' => $id_pengemudi['id_pengemudi'],
                        'id_mobil' => $id_mobil['id_mobil'],
                        'title' => $title,
                        'description' => $this->request->getVar('description'),
                        'start' => $tanggal_waktu_start,
                        'end' => null,
                        'tanggal_mobil' => $tanggal_kalender,
                        'tujuan_mobil' => $this->request->getVar('tujuan_mobil'),
                        'edited_by' => session()->get('login_by'),
                        'edited_at' => $timestamp,
                    ];
    
                    $transportasi = [
                        'id_transportasi' => $id_transportasi,
                        'id_pengemudi' => $id_pengemudi['id_pengemudi'],
                        'id_mobil' => $id_mobil['id_mobil'],
                        'tanggal_mobil' => $tanggal_kalender,
                        'jam_siap' => $jam_siap,
                        'status_mobil' => 1,
                        'edited_by' => session()->get('login_by'),
                        'edited_at' => $timestamp,
                    ];
    
                    $this->m_set_driver->save($driver);
                    $this->m_transportasi->save($transportasi);
                    session()->setFlashdata('success', ('Driver telah diubah'));
                    return redirect()->to("transport_admin");
                }
            } else {
                $driver_id_transportasi = $this->m_set_driver->where('id', $this->request->getVar('hapus_id'))->select('id_transportasi')->findAll();

                $driver = [
                    'id' => $this->request->getVar('hapus_id'),
                ];

                $transportasi = [
                    'id_transportasi' => $driver_id_transportasi[0]['id_transportasi'],
                    'id_pengemudi' => null,
                    'id_mobil' => null,
                    'status_mobil' => 0,
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];

                $this->m_set_driver->delete($this->request->getVar('hapus_id'));
                $this->m_transportasi->save($transportasi);
                $this->m_set_driver->query('ALTER TABLE set_driver AUTO_INCREMENT 1');
                session()->setFlashdata('success', ('Set Driver telah dihapus'));
                return redirect()->to("set_driver/".$id_trans."/".$id_transportasi);
            }
        }

        $trans = $this->m_trans->where('batal_transportasi <', 2)->where('trans.id_trans', $id_trans)->where('transportasi.id_pool', $id_pool)->orwhere('trans.id_trans', $id_trans)->where('transportasi_jemput.id_pool', $id_pool)->join('transportasi', 'transportasi.id_trans = trans.id_trans', 'left')->join('transportasi_jemput', 'transportasi_jemput.id_trans = trans.id_trans', 'left')->select('transportasi.id_transportasi, transportasi_jemput.id_transportasi_jemput, trans.id_trans, transportasi.id_pool')->findAll();
        
        if (empty($trans)) {
            session()->setFlashdata('warning', ['Transaksi ini tidak sesuai dengan pool Anda']);
            return redirect()->to('transport_admin');
        }

        $transportasi_antar = $this->m_transportasi->where('transportasi.id_transportasi', $id_transportasi)->where('batal_transportasi <', 2)->where('trans.id_trans', $id_trans)->where('transportasi.id_pool', $id_pool)->join('trans', 'trans.id_trans = transportasi.id_trans', 'left')->join('pool', 'pool.id_pool = transportasi.id_pool', 'left')->join('transportasi_jemput', 'transportasi_jemput.id_transportasi = transportasi.id_transportasi', 'left')->join('pengemudi', 'pengemudi.id_pengemudi = transportasi.id_pengemudi', 'left')->join('mobil', 'mobil.id_mobil = transportasi.id_mobil', 'left')->select('transportasi.id_transportasi, transportasi.id_trans, transportasi.id_pool, transportasi.id_pengemudi, transportasi.id_mobil, transportasi.jemput, nama_pengemudi, nama_mobil, transportasi.tanggal_mobil, transportasi.status_mobil, transportasi.jenis_kendaraan, transportasi.tenaga_angkut, transportasi.dalkot_lukot, transportasi.menginap, transportasi.jumlah_mobil, transportasi.kapasitas, transportasi.atas_nama, transportasi.jabatan, transportasi.siap_di, transportasi.jam_siap, transportasi.jam_selesai, transportasi.tujuan_mobil, transportasi.keterangan_mobil')->findAll();

        $pengemudi = $this->m_pengemudi->where('id_pool', $id_pool)->orderBy('nama_pengemudi', 'asc')->findAll();
        $mobil_pengemudi = $this->m_mobil->where('id_pool', $id_pool)->wherenotIn('id_mobil', ['149', '161', '163', '165'])->orderBy('nama_mobil', 'asc')->findAll();

        // $data_driver = json_decode($this->loadData(), true);

        $data = [
            'trans' => $trans,
            'transportasi_antar' => $transportasi_antar,

            'date' => $date,
            'pengemudi' => $pengemudi,
            'mobil_pengemudi' => $mobil_pengemudi,
            'id_trans' => $id_trans,
            'id_transportasi' => $id_transportasi,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_set_driver', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function edit_transportasi_antar($id_trans, $id_transportasi)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');
        $id_pool = session()->get('pool_pengguna');

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

        $id_bagian = session()->get('id_bagian');
        $id_detail_pengguna = session()->get('id_detail_pengguna');

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar();

            //Mobil
            $nama_pool = $_POST['gs_mobil'];
            $pool_mobil = $this->m_pool->where('nama_pool', $nama_pool)->select('id_pool')->findAll();

            if ($_POST['tamu'] == 'Karyawan Konimex') {
                if(!empty($_POST['nama_select'])) {                
                    for($a = 0; $a < count($_POST['nama_select']); $a++) {
                        $nama_select_h = $_POST["nama_select"];
                        $atas_namaa = implode(" - ", $nama_select_h);

                        $atas_namas = explode(" - ", $atas_namaa);

                        $jumlah = count($atas_namas);

                        for ($i=0; $i<$jumlah; $i++){
                            if ($i % 3 == 0){
                                $atas_namat[$i] = $atas_namas[$i];
                                $jabatant[$i] = $atas_namas[$i+1];
                                $jenis_kelamint[$i] = $atas_namas[$i+2];
                            }
                        }
                        $atas_nama = implode(", ", $atas_namat);
                        $jabatan = implode(", ", $jabatant);
                        $jenis_kelamin = implode(", ", $jenis_kelamint);
                    }
                }

                $pic = $atas_nama;
                $email_info = $atas_nama;
                $email_eval = $atas_nama;

                $pembayaran = $_POST['pembayaran'];
                if($pembayaran == 'Company Acc'){
                    $pembayaran = 'k';
                } else if($pembayaran == 'Personal Acc'){
                    $pembayaran = 'p';
                }
            } else {
                $atas_nama = $_POST['nama_inputan'];
                $jabatan = $_POST['jabatan_inputan'];
                $jenis_kelamin = null;
                $pic = $atas_nama;

                $pembayaran = $_POST['pembayaran_inputan'];
                if($pembayaran == 'Company Acc'){
                    $pembayaran = 'k';
                } else if($pembayaran == 'Personal Acc'){
                    $pembayaran = 'p';
                }
                $atas_nama = ucwords($atas_nama);
                $jabatan = ucwords($jabatan);
            }
            if (empty($atas_nama)) {
                session()->setFlashdata('warning', ['Nama harus diisi']);
                return redirect()->to("edit_transportasi_antar/".$id_trans."/".$id_transportasi);
            }
            if (empty($jabatan)) {
                session()->setFlashdata('warning', ['Jabatan harus diisi']);
                return redirect()->to("edit_transportasi_antar/".$id_trans."/".$id_transportasi);
            }

            $tujuan_mobil = $_POST['tujuan_mobil'];
            $tujuan_mobil = ucwords($tujuan_mobil);

            $siap_di = $_POST['siap_di'];
            $siap_di = ucwords($siap_di);

            $jenis_kendaraan = $_POST['jenis_kendaraan'];
            if($jenis_kendaraan == 'Sedan'){
                $jenis_kendaraan = 's';
            } else if($jenis_kendaraan == 'Station'){
                $jenis_kendaraan = 'a';
            } else if($jenis_kendaraan == 'Pick Up'){
                $jenis_kendaraan = 'p';
            } else if($jenis_kendaraan == 'Box'){
                $jenis_kendaraan = 'b';
            } else if($jenis_kendaraan == 'Truck'){
                $jenis_kendaraan = 't';
            }
            
            $dalkot_lukot = $_POST['dalkot_lukot'];

            if($dalkot_lukot == 'Dalam Kota'){
                $dalkot_lukot = 'd';
                $menginap = 0;
            } else {
                $dalkot_lukot = 'l';
                $menginap = $_POST['menginap'];
                if($menginap == 'Iya'){
                    $menginap = '1';
                } else {
                    $menginap = '0';
                }
            }

            $keterangan_mobil = $_POST['keterangan_mobil'];
            if(empty($keterangan_mobil)){
                session()->setFlashdata('warning', ['Keterangan Mobil harus diisi']);
                return redirect()->to("edit_transportasi_antar/".$id_trans."/".$id_transportasi);
            }

            $keterangan_gs = $_POST['keterangan_gs'];
            if(empty($keterangan_gs)){
                $keterangan_gs = null;
            }
            
            if(!empty($_POST['nama']) && !empty($_POST['jabatan']) && !empty($_POST['pic']) && !empty($_POST['jumlah_mobil']) && !empty($_POST['tujuan_mobil']) && !empty($_POST['siap_di']) && !empty($_POST['tanggal_mobil']) && !empty($_POST['jam_siap'])) {
                $trans = [
                    'id_trans' => $id_trans,
                    'pic' => $pic,
                    'tamu' => $_POST['tamu'],
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];

                $transportasi = [
                    'id_transportasi' => $id_transportasi,
                    'id_trans' => $id_trans,
                    'id_pool' => $pool_mobil[0]['id_pool'],
                    'atas_nama' => $atas_nama,
                    'jabatan' => $jabatan,
                    'jumlah_mobil' => $_POST['jumlah_mobil'],
                    'pembayaran' => $pembayaran,
                    'jenis_kendaraan' => $jenis_kendaraan,
                    'dalkot_lukot' => $dalkot_lukot,
                    'menginap' => $menginap,
                    'kapasitas' => $_POST['kapasitas'],
                    'tujuan_mobil' => $tujuan_mobil,
                    'siap_di' => $siap_di,
                    'tanggal_mobil' => $_POST['tanggal_mobil'],
                    'jam_siap' => $_POST['jam_siap'],
                    'keterangan_mobil' => $keterangan_mobil,
                    'keterangan_gs' => $keterangan_gs,
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];
            } else {
                if(empty($_POST['nama'])) {
                    session()->setFlashdata('warning', ['Nama harus diisi']);
                    return redirect()->to("edit_transportasi_antar/".$id_trans."/".$id_transportasi);
                } else if(empty($_POST['jabatan'])) {
                    session()->setFlashdata('warning', ['Jabatan harus diisi']);
                    return redirect()->to("edit_transportasi_antar/".$id_trans."/".$id_transportasi);
                } else if(empty($_POST['pic'])) {
                    session()->setFlashdata('warning', ['PIC harus diisi']);
                    return redirect()->to("edit_transportasi_antar/".$id_trans."/".$id_transportasi);
                } else if (empty($_POST['jumlah_mobil'])) {
                    session()->setFlashdata('warning', ['Jumlah Mobil harus diisi']);
                    return redirect()->to("edit_transportasi_antar/".$id_trans."/".$id_transportasi);
                } else if (empty($_POST['tujuan_mobil'])) {
                    session()->setFlashdata('warning', ['Tujuan harus diisi']);
                    return redirect()->to("edit_transportasi_antar/".$id_trans."/".$id_transportasi);
                } else if (empty($_POST['siap_di'])) {
                    session()->setFlashdata('warning', ['Siap Di harus diisi']);
                    return redirect()->to("edit_transportasi_antar/".$id_trans."/".$id_transportasi);
                } else if (empty($_POST['tanggal_mobil'])) {
                    session()->setFlashdata('warning', ['Tanggal harus diisi']);
                    return redirect()->to("edit_transportasi_antar/".$id_trans."/".$id_transportasi);
                } else if (empty($_POST['jam_siap'])) {
                    session()->setFlashdata('warning', ['Jam Siap harus diisi']);
                    return redirect()->to("edit_transportasi_antar/".$id_trans."/".$id_transportasi);
                }
            }

            $this->m_trans->save($trans);
            $this->m_transportasi->save($transportasi);
            session()->setFlashdata('success', ('Data berhasil diedit'));
            return redirect()->to("transport_admin");
        }
        
        $trans = $this->m_trans->where('trans.id_trans', $id_trans)->where('batal_transportasi =', 0)->where('transportasi.id_pool', $id_pool)->join('transportasi', 'transportasi.id_trans = trans.id_trans', 'left')->select('trans.id_trans, transportasi.id_pool, tanggal_mobil, jam_siap, id_bagian, trans.created_at')->orderBy('created_at', 'desc')->findAll();
        
        $transportasi = $this->m_transportasi->where('trans.id_trans', $id_trans)->where('batal_transportasi =', 0)->where('transportasi.id_pool', $id_pool)->join('trans', 'trans.id_trans = transportasi.id_trans', 'left')->join('transportasi_jemput', 'transportasi_jemput.id_transportasi = transportasi.id_transportasi', 'left')->join('pool', 'pool.id_pool = transportasi.id_pool', 'left')->select('transportasi.id_trans, transportasi.id_transportasi, id_transportasi_jemput, transportasi.id_pool, pic, tamu, nama_pool, transportasi.peminta, transportasi.atas_nama, transportasi.jabatan, transportasi.jenis_kelamin, transportasi.pembayaran, transportasi.jenis_kendaraan, transportasi.tenaga_angkut, transportasi.tanggal_mobil, transportasi.jam_siap, transportasi.dalkot_lukot, transportasi.jumlah_mobil, transportasi.kapasitas, transportasi.tujuan_mobil, transportasi.siap_di, transportasi.keterangan_mobil, transportasi.status_mobil, transportasi.keterangan_gs, transportasi.menginap, transportasi.created_at')->findAll();

        if (empty($transportasi)) {
            session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini']);
            return redirect()->to('transport_admin');
        }

        // if ($transportasi[0]['status_mobil'] == 1) {
        //     session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini karena telah dikonfirmasi']);
        //     return redirect()->to('transport_admin');
        // }

        $pool = $this->m_pool->select('nama_pool')->findAll();

        $pengguna = $this->m_detail_pengguna->where('detail_pengguna.id_bagian', $trans[0]['id_bagian'])->join('pengguna', 'pengguna.id_pengguna = detail_pengguna.id_pengguna', 'left')->join('jabatan', 'jabatan.id_jabatan = detail_pengguna.id_jabatan', 'left')->select('detail_pengguna.id_pengguna, nama_pengguna, nama_jabatan, jenis_kelamin')->orderBy('nama_pengguna', 'asc')->groupBy('id_pengguna')->findAll();

        foreach ($transportasi as $tr => $transpo) {
            $atas_nama_transportasi = explode(', ', $transpo['atas_nama']);
            $jabatan_transportasi = explode(', ', $transpo['jabatan']);
            $jenis_kelamin_transportasi = explode(', ', $transpo['jenis_kelamin']);

            foreach ($atas_nama_transportasi as $ti => $tik) {
                $atas_nama[$ti] = $tik;
            }

            foreach ($jabatan_transportasi as $ja => $jab) {
                $jabatan[$ja] = $jab;
            }

            foreach ($jenis_kelamin_transportasi as $je => $jen) {
                $result[] = [
                    'atas_nama' => $atas_nama[$je],
                    'jabatan' => $jabatan[$je],
                    'jenis_kelamin' => $jen,
                ];
            }
        }

        $data = [
            'trans' => $trans,
            'transportasi' => $transportasi,
            'result' => $result,
            'pool' => $pool,
            'pengguna' => $pengguna,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_edit_transportasi_antar', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function edit_transportasi_jemput($id_trans, $id_transportasi, $id_transportasi_jemput)
    {
        $data = [];

        $admin_gs = session()->get('admin_gs');
        $id_pool = session()->get('pool_pengguna');

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

        $id_bagian = session()->get('id_bagian');
        $id_detail_pengguna = session()->get('id_detail_pengguna');

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar();

            //Trans
            $pic = $_POST['pic'];
            $pic = ucwords($pic);
            
            //Mobil Pulang
            $nama_pool = $_POST['gs_mobil_pulang'];

            $pool_mobil_pulang = $this->m_pool->where('nama_pool', $nama_pool)->select('id_pool')->findAll();

            $atas_nama = $_POST['nama'];
            $atas_nama = ucwords($atas_nama);

            $jabatan = $_POST['jabatan'];
            $jabatan = ucwords($jabatan);

            $tujuan_mobil_pulang = $_POST['tujuan_mobil_pulang'];
            $tujuan_mobil_pulang = ucwords($tujuan_mobil_pulang);

            $siap_di_pulang = $_POST['siap_di_pulang'];
            $siap_di_pulang = ucwords($siap_di_pulang);

            $pembayaran = $_POST['pembayaran'];
            if($pembayaran == 'Company Acc'){
                $pembayaran = 'k';
            } else if($pembayaran == 'Personal Acc'){
                $pembayaran = 'p';
            }

            $jenis_kendaraan_pulang = $_POST['jenis_kendaraan_pulang'];
            if($jenis_kendaraan_pulang == 'Sedan'){
                $jenis_kendaraan_pulang = 's';
            } else if($jenis_kendaraan_pulang == 'Station'){
                $jenis_kendaraan_pulang = 'a';
            } else if($jenis_kendaraan_pulang == 'Pick Up'){
                $jenis_kendaraan_pulang = 'p';
            } else if($jenis_kendaraan_pulang == 'Box'){
                $jenis_kendaraan_pulang = 'b';
            } else if($jenis_kendaraan_pulang == 'Truck'){
                $jenis_kendaraan_pulang = 't';
            }

            $tenaga_angkut_pulang = $_POST['tenaga_angkut_pulang'];
            if($tenaga_angkut_pulang == 'Iya'){
                $tenaga_angkut_pulang = '1';
            } else {
                $tenaga_angkut_pulang = '0';
            }
            
            $dalkot_lukot_pulang = $_POST['dalkot_lukot_pulang'];

            if($dalkot_lukot_pulang == 'Dalam Kota'){
                $dalkot_lukot_pulang = 'd';
                $menginap_pulang = 0;
            } else {
                $dalkot_lukot_pulang = 'l';
                $menginap_pulang = $_POST['menginap_pulang'];
                if($menginap_pulang == 'Iya'){
                    $menginap_pulang = '1';
                } else {
                    $menginap_pulang = '0';
                }
            }

            $keterangan_mobil_pulang = $_POST['keterangan_mobil_pulang'];
            if(empty($keterangan_mobil_pulang)){
                $keterangan_mobil_pulang = null;
            }

            $keterangan_gs_pulang = $_POST['keterangan_gs_pulang'];
            if(empty($keterangan_gs_pulang)){
                $keterangan_gs_pulang = null;
            }
            
            if(!empty($_POST['nama']) && !empty($_POST['jabatan']) && !empty($_POST['pic']) && !empty($_POST['jumlah_mobil_pulang']) && !empty($_POST['tujuan_mobil_pulang']) && !empty($_POST['siap_di_pulang']) && !empty($_POST['tanggal_mobil_pulang']) && !empty($_POST['jam_siap_pulang'])) {
                $trans = [
                    'id_trans' => $id_trans,
                    'tamu' => $_POST['tamu'],
                    'pic' => $pic,
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];

                $transportasi = [
                    'id_transportasi' => $id_transportasi,
                    'id_trans' => $id_trans,
                    'id_pool' => $pool_mobil_pulang[0]['id_pool'],
                    'tanggal_mobil' => $_POST['tanggal_mobil_pulang'],
                    'jam_siap' => $_POST['jam_siap_pulang'],
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];

                $transportasi_jemput = [
                    'id_transportasi_jemput' => $id_transportasi_jemput,
                    'id_transportasi' => $id_transportasi,
                    'id_trans' => $id_trans,
                    'id_pool' => $pool_mobil_pulang[0]['id_pool'],
                    'atas_nama' => $atas_nama,
                    'jabatan' => $jabatan,
                    'jumlah_mobil' => $_POST['jumlah_mobil_pulang'],
                    'pembayaran' => $pembayaran,
                    'jenis_kendaraan' => $jenis_kendaraan_pulang,
                    'tenaga_angkut' => $tenaga_angkut_pulang,
                    'dalkot_lukot' => $dalkot_lukot_pulang,
                    'menginap' => $menginap_pulang,
                    'kapasitas' => $_POST['kapasitas_pulang'],
                    'tujuan_mobil' => $tujuan_mobil_pulang,
                    'siap_di' => $siap_di_pulang,
                    'tanggal_mobil' => $_POST['tanggal_mobil_pulang'],
                    'jam_siap' => $_POST['jam_siap_pulang'],
                    'keterangan_mobil' => $keterangan_mobil_pulang,
                    'keterangan_gs' => $keterangan_gs_pulang,
                    'edited_by' => session()->get('login_by'),
                    'edited_at' => $timestamp,
                ];
            } else {
                if(empty($_POST['nama'])) {
                    session()->setFlashdata('warning', ['Nama harus diisi']);
                    return redirect()->to('edit_transportasi_jemput');
                } else if(empty($_POST['jabatan'])) {
                    session()->setFlashdata('warning', ['Jabatan harus diisi']);
                    return redirect()->to('edit_transportasi_jemput');
                } else if(empty($_POST['pic'])) {
                    session()->setFlashdata('warning', ['PIC harus diisi']);
                    return redirect()->to('edit_transportasi_jemput');
                } else if (empty($_POST['jumlah_mobil_pulang'])) {
                    session()->setFlashdata('warning', ['Jumlah Mobil harus diisi']);
                    return redirect()->to('edit_transportasi_jemput');
                } else if (empty($_POST['tujuan_mobil_pulang'])) {
                    session()->setFlashdata('warning', ['Tujuan harus diisi']);
                    return redirect()->to('edit_transportasi_jemput');
                } else if (empty($_POST['siap_di_pulang'])) {
                    session()->setFlashdata('warning', ['Siap Di harus diisi']);
                    return redirect()->to('edit_transportasi_jemput');
                } else if (empty($_POST['tanggal_mobil_pulang'])) {
                    session()->setFlashdata('warning', ['Tanggal harus diisi']);
                    return redirect()->to('edit_transportasi_jemput');
                } else if (empty($_POST['jam_siap_pulang'])) {
                    session()->setFlashdata('warning', ['Jam Siap harus diisi']);
                    return redirect()->to('edit_transportasi_jemput');
                }
            }

            $this->m_trans->save($trans);
            $this->m_transportasi->save($transportasi);
            $this->m_transportasi_jemput->save($transportasi_jemput);
            session()->setFlashdata('success', ('Data berhasil diedit'));
            return redirect()->to("transport_admin");
        }
        
        $trans = $this->m_trans->where('trans.id_trans', $id_trans)->where('batal =', 0)->where('transportasi.id_pool', $id_pool)->join('transportasi', 'transportasi.id_trans = trans.id_trans', 'left')->select('trans.id_trans, transportasi.id_pool, tanggal_mobil, jam_siap, trans.created_at')->orderBy('created_at', 'desc')->findAll();
        
        $transportasi_jemput = $this->m_transportasi_jemput->where('trans.id_trans', $id_trans)->where('batal =', 0)->where('transportasi_jemput.id_pool', $id_pool)->join('trans', 'trans.id_trans = transportasi_jemput.id_trans', 'left')->join('transportasi', 'transportasi.id_transportasi = transportasi_jemput.id_transportasi', 'left')->join('pool', 'pool.id_pool = transportasi_jemput.id_pool', 'left')->select('transportasi_jemput.id_trans, transportasi_jemput.id_transportasi_jemput, transportasi_jemput.id_transportasi, transportasi_jemput.id_pool, pic, tamu, nama_pool, transportasi_jemput.peminta, transportasi_jemput.atas_nama, transportasi_jemput.jabatan, transportasi_jemput.pembayaran, transportasi_jemput.jenis_kendaraan, transportasi_jemput.tenaga_angkut, transportasi_jemput.tanggal_mobil, transportasi_jemput.jam_siap, transportasi_jemput.dalkot_lukot, transportasi_jemput.jumlah_mobil, transportasi_jemput.kapasitas, transportasi_jemput.tujuan_mobil, transportasi_jemput.siap_di, transportasi_jemput.keterangan_mobil, transportasi_jemput.status_mobil, transportasi_jemput.created_at')->findAll();

        if (empty($transportasi_jemput)) {
            session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini']);
            return redirect()->to('transport_admin');
        }

        // if ($transportasi_jemput[0]['status_mobil'] == 1) {
        //     session()->setFlashdata('warning', ['Tidak dapat mengedit transaksi ini karena telah dikonfirmasi']);
        //     return redirect()->to('transport_admin');
        // }

        $pool = $this->m_pool->select('nama_pool')->findAll();

        $data = [
            'trans' => $trans,
            'transportasi_jemput' => $transportasi_jemput,
            'pool' => $pool,
        ];

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_edit_transportasi_jemput', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }
    
    public function tele()
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

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_tele', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }
    
    public function cetak_pas()
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

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_cetak_pas', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }
    
    public function arsip_tiket()
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

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_arsip_tiket', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }
    
    public function arsip_akomodasi()
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

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_arsip_akomodasi', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }
    
    public function arsip_transport()
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

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_arsip_transport', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }
    
    public function bbm()
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

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_bbm', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }
    
    public function daftar_pakai_kend()
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

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_daftar_pakai_kend', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }
    
    public function set_pas()
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

        echo view('ui/v_header', $data);
        echo view('ui/v_menu_trans_admin', $data);
        echo view('transaksi/v_set_pas', $data);
        echo view('ui/v_footer', $data);
        // print_r(session()->get(''));
    }

    public function transaksi()
    {
        $strorg = session()->get('strorg');
        $nik = session()->get('akun_nik');
        $niknm = session()->get('niknm');
        $role = session()->get('akun_role');


        if ($role == 'admin') {
            $submit = $this->m_id->where('SUBSTRING(strorg, 1, 4)', substr($strorg, 0, 4))->orderBy('submit_pjum', 'desc')->orderBy('submit_pb', 'desc')->select('submit_pjum, submit_pb')->first();
        } else if ($role == 'user') {
            // $submit = $this->m_id->where('nik', $nik)->where('SUBSTRING(strorg, 1, 4)', substr($strorg, 0, 4))->findAll();
            $submit = $this->m_id->where('strorg', $strorg)->orderBy('submit_pjum', 'desc')->orderBy('submit_pb', 'desc')->select('submit_pjum, submit_pb')->first();
        } else if($role == 'treasury' || $role == 'gs'){
            $submit = null;
        }

        if($this->request->getVar('aksi') == 'hapus' && $this->request->getVar('id_transaksi')) {
            $dataPost = $this->m_id->getPostId($this->request->getVar('id_transaksi'), substr($strorg, 0, 4));
            if($dataPost['id_transaksi']) {//memastikan bahwa ada data
                $aksi = $this->m_id->deletePostId($this->request->getVar('id_transaksi'));
                if($aksi == true) {
                    $this->m_id->query('ALTER TABLE transaksi AUTO_INCREMENT 1');
                    $this->m_personil->query('ALTER TABLE personil AUTO_INCREMENT 1');
                    $this->m_negara_tujuan->query('ALTER TABLE negaratujuan AUTO_INCREMENT 1');
                    $this->m_pum->query('ALTER TABLE pum AUTO_INCREMENT 1');
                    $this->m_pjum->query('ALTER TABLE pjum AUTO_INCREMENT 1');
                    $this->m_pb->query('ALTER TABLE pb AUTO_INCREMENT 1');
                    $this->m_kurs->query('ALTER TABLE kurs AUTO_INCREMENT 1');
                    $this->m_kategori->query('ALTER TABLE kategori AUTO_INCREMENT 1');
                    $this->m_biaya->query('ALTER TABLE biaya AUTO_INCREMENT 1');
                    session()->setFlashdata('success', "ID Transaksi berhasil dihapus");
                } else {
                    session()->setFlashdata('warning', ['ID Transaksi gagal dihapus']);
                }
            }
            return redirect()->to("transaksi");
        }

        if($this->request->getMethod() == 'post') {
            $tanggal_awal = $this->request->getVar('tanggal_awal');
            $tanggal_akhir = $this->request->getVar('tanggal_akhir');
            $strorgnm = $this->request->getVar('strorgnm');
            $negara = $this->request->getVar('negara');
            $kategori = $this->request->getVar('kategori');

            if (empty($strorgnm)) {
                $strorgnm = null;
            } else {
                $stro = $this->m_bm06->whereIn('strorgnm', $strorgnm)->select('strorg')->findAll();

                $str = implode(' ', array_map(function ($entry) {
                    return ($entry[key($entry)]);
                }, $stro));
    
                $strorg = explode(' ', $str);
            }

            //Memilih tanggal dan bagian untuk menentukan id transaksi
            if (empty($strorgnm)) { //semua bagian, semua negara, semua kategori
                $id = $this->m_id->tanggalsemua($tanggal_awal, $tanggal_akhir, substr($strorg, 0, 4));
            } else if (!empty($strorgnm)) { //milih bagian, semua negara, semua kategori
                $id = $this->m_id->whereIn('strorg', $strorg)->Where('tanggal_berangkat >=', $tanggal_awal)->Where('tanggal_pulang <=', $tanggal_akhir)->Where('submit_pjum', 4)->Where('submit_pb', 4)->findAll();
            }

            $arr_id = implode(' ', array_map(function ($entry) {
                return ($entry[key($entry)]);
            }, $id));

            if(empty($arr_id)) {
                session()->setFlashdata('warning', ['Data tidak ditemukan']);
                return redirect()->to('transaksi');
            }

            $id_tran = explode(' ', $arr_id);
            $id_trans = array_unique($id_tran);

            $id_transaksi = array_values($id_trans);

            if (empty($negara) && empty($kategori)) { //semua negara, semua kategori
                $kategori1 = $this->m_kategori->whereIn('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->findAll();
                $biaya = $this->m_biaya->whereIn('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->findAll();
                $valas = $this->m_biaya->whereIn('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->select('id_valas')->findAll();
                $valas1 = $this->m_biaya->whereIn('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->select('kode_valas')->findAll();
                $valas2 = $this->m_biaya->whereIn('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->select('kolom')->findAll();
                $totalbiayatot = $this->m_biaya->whereIn('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->groupBy(['id_valas'])->orderBy('tanggal', 'asc')->select('sum(biaya) as sum, id_valas')->findAll();
                $kurs = $this->m_kurs->whereIn('id_transaksi', $id_transaksi)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
            } else if (!empty($negara) && empty($kategori)) { //milih negara, semua kategori
                $kategori1 = $this->m_kategori->whereIn('id_transaksi', $id_transaksi)->whereIn('negara_tujuan', $negara)->orwhereIn('id_transaksi', $id_transaksi)->whereIn('negara_trading', $negara)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->findAll();
                $id_kat = $this->m_kategori->whereIn('id_transaksi', $id_transaksi)->whereIn('negara_tujuan', $negara)->orwhereIn('id_transaksi', $id_transaksi)->whereIn('negara_trading', $negara)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->select('id_kategori')->findAll();
                $id_pjum = $this->m_kategori->whereIn('id_transaksi', $id_transaksi)->whereIn('negara_tujuan', $negara)->orwhereIn('id_transaksi', $id_transaksi)->whereIn('negara_trading', $negara)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->select('id_pjum')->findAll();
                $id_pb = $this->m_kategori->whereIn('id_transaksi', $id_transaksi)->whereIn('negara_tujuan', $negara)->orwhereIn('id_transaksi', $id_transaksi)->whereIn('negara_trading', $negara)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->select('id_pb')->findAll();

                $arr_kat = implode(' ', array_map(function ($entry) {
                    return ($entry[key($entry)]);
                }, $id_kat));

                $arr_pjum = implode(' ', array_map(function ($entry) {
                    return ($entry[key($entry)]);
                }, $id_pjum));

                $arr_pb = implode(' ', array_map(function ($entry) {
                    return ($entry[key($entry)]);
                }, $id_pb));

                if(empty($arr_kat)) {
                    session()->setFlashdata('warning', ['Data tidak ditemukan']);
                    return redirect()->to('transaksi');
                }

                $id_kategori = explode(' ', $arr_kat);
                $id_pjum = explode(' ', $arr_pjum);
                $id_pb = explode(' ', $arr_pb);

                $biaya = $this->m_biaya->whereIn('id_kategori', $id_kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->findAll();
                $valas = $this->m_biaya->whereIn('id_kategori', $id_kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->select('id_valas')->findAll();
                $valas1 = $this->m_biaya->whereIn('id_kategori', $id_kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->select('kode_valas')->findAll();
                $valas2 = $this->m_biaya->whereIn('id_kategori', $id_kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->select('kolom')->findAll();
                $totalbiayatot = $this->m_biaya->whereIn('id_kategori', $id_kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->groupBy(['id_valas'])->orderBy('tanggal', 'asc')->select('sum(biaya) as sum, id_valas')->findAll();
                $kurs = $this->m_kurs->whereIn('id_pjum', $id_pjum)->orwhereIn('id_pb', $id_pb)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
            } else if (empty($negara) && !empty($kategori)) { //semua negara, milih kategori
                $kategori1 = $this->m_kategori->whereIn('id_transaksi', $id_transaksi)->whereIn('kategori', $kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->findAll();
                $id_kat = $this->m_kategori->whereIn('id_transaksi', $id_transaksi)->whereIn('kategori', $kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->select('id_kategori')->findAll();
                $id_pjum = $this->m_kategori->whereIn('id_transaksi', $id_transaksi)->whereIn('kategori', $kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->select('id_pjum')->findAll();
                $id_pb = $this->m_kategori->whereIn('id_transaksi', $id_transaksi)->whereIn('kategori', $kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->select('id_pb')->findAll();

                $arr_kat = implode(' ', array_map(function ($entry) {
                    return ($entry[key($entry)]);
                }, $id_kat));

                $arr_pjum = implode(' ', array_map(function ($entry) {
                    return ($entry[key($entry)]);
                }, $id_pjum));

                $arr_pb = implode(' ', array_map(function ($entry) {
                    return ($entry[key($entry)]);
                }, $id_pb));

                if(empty($arr_kat)) {
                    session()->setFlashdata('warning', ['Data tidak ditemukan']);
                    return redirect()->to('transaksi');
                }

                $id_kategori = explode(' ', $arr_kat);
                $id_pjum = explode(' ', $arr_pjum);
                $id_pb = explode(' ', $arr_pb);

                $biaya = $this->m_biaya->whereIn('id_kategori', $id_kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->findAll();
                $valas = $this->m_biaya->whereIn('id_kategori', $id_kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->select('id_valas')->findAll();
                $valas1 = $this->m_biaya->whereIn('id_kategori', $id_kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->select('kode_valas')->findAll();
                $valas2 = $this->m_biaya->whereIn('id_kategori', $id_kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->select('kolom')->findAll();
                $totalbiayatot = $this->m_biaya->whereIn('id_kategori', $id_kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->groupBy(['id_valas'])->orderBy('tanggal', 'asc')->select('sum(biaya) as sum, id_valas')->findAll();
                $kurs = $this->m_kurs->whereIn('id_pjum', $id_pjum)->orwhereIn('id_pb', $id_pb)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
            } else if (!empty($negara) && !empty($kategori)) { //milih negara, milih kategori
                $kategori1 = $this->m_kategori->whereIn('id_transaksi', $id_transaksi)->whereIn('negara_tujuan', $negara)->whereIn('kategori', $kategori)->orwhereIn('id_transaksi', $id_transaksi)->whereIn('negara_trading', $negara)->whereIn('kategori', $kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->findAll();
                $id_kat = $this->m_kategori->whereIn('id_transaksi', $id_transaksi)->whereIn('negara_tujuan', $negara)->whereIn('kategori', $kategori)->orwhereIn('id_transaksi', $id_transaksi)->whereIn('negara_trading', $negara)->whereIn('kategori', $kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->select('id_kategori')->findAll();
                $id_pjum = $this->m_kategori->whereIn('id_transaksi', $id_transaksi)->whereIn('negara_tujuan', $negara)->whereIn('kategori', $kategori)->orwhereIn('id_transaksi', $id_transaksi)->whereIn('negara_trading', $negara)->whereIn('kategori', $kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->select('id_pjum')->findAll();
                $id_pb = $this->m_kategori->whereIn('id_transaksi', $id_transaksi)->whereIn('negara_tujuan', $negara)->whereIn('kategori', $kategori)->orwhereIn('id_transaksi', $id_transaksi)->whereIn('negara_trading', $negara)->whereIn('kategori', $kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->select('id_pb')->findAll();

                $arr_kat = implode(' ', array_map(function ($entry) {
                    return ($entry[key($entry)]);
                }, $id_kat));

                $arr_pjum = implode(' ', array_map(function ($entry) {
                    return ($entry[key($entry)]);
                }, $id_pjum));

                $arr_pb = implode(' ', array_map(function ($entry) {
                    return ($entry[key($entry)]);
                }, $id_pb));

                if(empty($arr_kat)) {
                    session()->setFlashdata('warning', ['Data tidak ditemukan']);
                    return redirect()->to('transaksi');
                }

                $id_kategori = explode(' ', $arr_kat);
                $id_pjum = explode(' ', $arr_pjum);
                $id_pb = explode(' ', $arr_pb);

                $biaya = $this->m_biaya->whereIn('id_kategori', $id_kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->findAll();
                $valas = $this->m_biaya->whereIn('id_kategori', $id_kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->select('id_valas')->findAll();
                $valas1 = $this->m_biaya->whereIn('id_kategori', $id_kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->select('kode_valas')->findAll();
                $valas2 = $this->m_biaya->whereIn('id_kategori', $id_kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->orderBy('tanggal', 'asc')->select('kolom')->findAll();
                $totalbiayatot = $this->m_biaya->whereIn('id_kategori', $id_kategori)->wherenotIn('jenis_biaya', ['Support'])->wherenotIn('kategori', ['Tukar Uang Masuk', 'Tukar Uang Keluar', 'Kembalian'])->groupBy(['id_valas'])->orderBy('tanggal', 'asc')->select('sum(biaya) as sum, id_valas')->findAll();
                $kurs = $this->m_kurs->whereIn('id_pjum', $id_pjum)->orwhereIn('id_pb', $id_pb)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
            }

            $array = array('I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR');
            $array1 = array('8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45');
            $arraypjum = array('C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR');

            $kategorisupport = $this->m_kategori->whereIn('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['pjum', 'pb'])->orderBy('tanggal', 'asc')->findAll();
            $biayasupport = $this->m_biaya->whereIn('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['pjum', 'pb'])->orderBy('tanggal', 'asc')->findAll();
            $valassupport = $this->m_biaya->whereIn('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['pjum', 'pb'])->groupBy(['id_biaya', 'id_transaksi', 'jenis_biaya'])->orderBy('tanggal', 'asc')->select('id_biaya')->findAll();
            $valassup = $this->m_biaya->whereIn('id_transaksi', $id_transaksi)->wherenotIn('jenis_biaya', ['pjum', 'pb'])->groupBy(['id_valas', 'id_transaksi', 'jenis_biaya'])->orderBy('tanggal', 'asc')->select('id_valas')->findAll();

            $arr1 = implode(' ', array_map(function ($entry) {
                return ($entry[key($entry)]);
            }, $valas));

            $exp1 = explode(' ', $arr1);

            $arr2 = implode(' ', array_map(function ($entry) {
                return ($entry[key($entry)]);
            }, $valas1));

            $exp2 = explode(' ', $arr2);

            $arr3 = implode(' ', array_map(function ($entry) {
                return ($entry[key($entry)]);
            }, $valas2));

            $exp3 = explode(' ', $arr3);

            $valas_unique = array_unique($exp1);
            $kode_uniqu = array_unique($exp2);
            $kolom_uniqu = array_unique($exp3);

            $id_valas_unique = array_values($valas_unique);
            $kode_unique = array_values($kode_uniqu);
            $kolom_unique = array_values($kolom_uniqu);

            $count = count((array)$id_valas_unique);
            $count1 = array_keys($id_valas_unique);
            $count2 = count((array)$negara);
            $count3 = count((array)$kategori);
            $count4 = count((array)$kategori1);
            $count5 = count((array)$strorgnm);
            $count6= array_keys($id_valas_unique, 76);
            $count7= count((array)$count6);
            $countsup = count((array)$valassup);
            $countsupport = count((array)$valassupport);
            $baris_total = (int)$count4 + 8;
            $baris_support = (int)$count4 + 10;
            $alpha = $array[$count];
            $alphasup = $arraypjum[$countsup + 1];
            $num = $array1[$count];

            $spreadsheet = new Spreadsheet();
            Calculation::getInstance($spreadsheet)->disableCalculationCache();
            Calculation::getInstance()->setCalculationCacheEnabled(FALSE);
            $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');
            $spreadsheet->getDefaultStyle()->getFont()->setSize(12);
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setTitle("Laporan Biaya Semua Valuta");

            $sheet->setCellValue('B1', 'PERJALANAN DINAS LUAR NEGERI PERIODE '.strtoupper(tanggal_indo1($tanggal_awal)).' HINGGA '.strtoupper(tanggal_indo1($tanggal_akhir)));
            $sheet->setCellValue('B2', 'Bagian =>');
            $sheet->setCellValue('B3', 'Negara =>');
            $sheet->setCellValue('B4', 'Kategori =>');
            $sheet->setCellValue('B6', 'Tanggal');
            $sheet->setCellValue('C6', 'Kategori');
            $sheet->setCellValue('D6', 'Status');
            $sheet->setCellValue('E6', 'Ref');
            $sheet->setCellValue('F6', 'Note');
            $sheet->setCellValue('G6', 'Negara Tujuan');
            $sheet->setCellValue('H6', 'Negara Transit');
            $sheet->setCellValue('I6', 'Jumlah Personil');
            $sheet->setCellValue('J6', 'Valas');
            $sheet->setCellValue('B'.$baris_total, 'TOTAL BIAYA');

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
            $sheet->mergeCells('B'.$baris_total.':I'.$baris_total);

            $sheet->getStyle('B'.$baris_total.':'.$alpha.$baris_total)->getFont()->setBold( true );
            $sheet->getStyle('J7:'.$alpha.'7')->getFont()->setBold( true );
            $sheet->getStyle('B:'.$alpha)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('B:'.$alpha)->getAlignment()->setVertical('center');
            $sheet->getStyle('J8:'.$alpha.$baris_total)->getAlignment()->setHorizontal('right');

            for ($k = 'B'; $k <= $alpha; $k++) {
                $spreadsheet->getActiveSheet()->getColumnDimension($k)->setWidth(20);
            }

            $sheet->getColumnDimension('A')->setVisible(false);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);

            $sheet->fromArray($kode_unique, null, 'J7');

            $i = 8;
            foreach ($kategori1 as $key => $value) {
                $sheet->setCellValue('B'.$i, $value['tanggal']);
                $sheet->setCellValue('C'.$i, $value['kategori']);
                $sheet->setCellValue('D'.$i, $value['status']);
                $sheet->setCellValue('E'.$i, $value['ref']);
                $sheet->setCellValue('F'.$i, $value['note']);
                $sheet->setCellValue('G'.$i, $value['negara_tujuan']);
                $sheet->setCellValue('H'.$i, $value['negara_trading']);
                $sheet->setCellValue('I'.$i, $value['jumlah_personil']);
                $sheet->getStyle('B6:'.$alpha.$i+1)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $i++;
            }

            $i = 8;
            foreach ($biaya as $key => $value) {
                for ($j = 0; $j < $count; $j++) {
                    $array = array('J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR');
                    $alpha = $array[$count1[$j]];
                    if ($value['id_valas'] == $id_valas_unique[$j]) {
                        $sheet->setCellValue($alpha.$i, $value['biaya']);
                        $i++;
                    }
                }
            }

            $baris = $baris_support;
            $bar_tot = $baris_total - 2;
            $indexval = $baris + 1;
            $indexkat = $baris + 2;
            $inde = $baris + 4;
            $indexsupport = $countsupport + $inde;

            foreach ($totalbiayatot as $key => $value) {
                for ($j = 0; $j < $count; $j++) {
                    $array = array('J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR');
                    $alpha = $array[$count1[$j]];
                    if ($id_valas_unique[$j] == $value['id_valas']) {
                        $sheet->setCellValue($alpha.$baris_total, $value['sum']);
                        $sheet->setCellValue($alpha.$indexkat, $value['sum']);
                    }
                }
            }

            // Biaya Support

            if (empty($biayasupport)) {
                
            } else {
                $sheet->setCellValue('B'.$baris, 'Biaya Support Perjalanan Dinas Luar Negeri');
                $sheet->setCellValue('G'.$baris, 'TOTAL BIAYA (PJUM + PB + SUPPORT)');
                $sheet->setCellValue('B'.$indexkat, 'Tanggal');
                $sheet->setCellValue('C'.$indexkat, 'Kategori');
                $sheet->setCellValue('D'.$indexkat, 'Jumlah Personil');
                $sheet->setCellValue('E'.$indexkat, 'Biaya');
                $sheet->setCellValue('E'.$indexkat+1, 'IDR');
                $sheet->setCellValue('B'.$indexsupport, 'TOTAL BIAYA SUPPORT');
                
                $sheet->fromArray($kode_unique, null, 'J'.$baris+1);

                $sheet->mergeCells('B'.$baris.':E'.$baris + 1);
                $sheet->mergeCells('B'.$indexkat.':B'.$indexkat + 1);
                $sheet->mergeCells('C'.$indexkat.':C'.$indexkat + 1);
                $sheet->mergeCells('D'.$indexkat.':D'.$indexkat + 1);
                $sheet->mergeCells('B'.$indexsupport.':D'.$indexsupport);

                $sheet->getStyle('B'.$baris)->getFont()->setBold(true);
                $sheet->getStyle('G'.$baris)->getFont()->setBold(true);
                $sheet->getStyle('E'.$indexkat+1)->getFont()->setBold(true);
                $sheet->getStyle('E'.$indexkat+1)->getFont()->setBold(true);
                $sheet->getStyle('B'.$indexsupport.':E'.$indexsupport)->getFont()->setBold(true);
                $sheet->getStyle('E'.$inde.':E'.$indexsupport)->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle('E'.$inde.':E'.$indexsupport)->getAlignment()->setHorizontal('right');

                for ($i=$baris; $i <= $indexsupport; $i++) { 
                    $sheet->getStyle('B'.$baris.':E'.$indexsupport)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet->getStyle('G'.$baris.':'.$alpha.$baris + 2)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet->getStyle('D'.$baris.':D'.$i)->getNumberFormat()->setFormatCode('#');
                    $i++;
                }
    
                $row = $baris + 4;
                foreach ($kategorisupport as $key => $value) {
                    $sheet->setCellValue('B'.$row, $value['tanggal']);
                    $sheet->setCellValue('C'.$row, $value['kategori']);
                    $sheet->setCellValue('D'.$row, $value['jumlah_personil']);
                    $row++;
                }

                $row = $baris + 4;
                foreach ($biayasupport as $key => $value) {
                    $sheet->setCellValue('E'.$row, $value['biaya']);
                    $row++;
                }

                if (!empty($count7)) {
                    for ($j = 0; $j < $count7; $j++) {
                        $array_tot = array('J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR');
                        $alpha_tot = $array_tot[$count6[$j]];

                        $sheet->mergeCells('G'.$baris.':I'.$baris + 2);
                        $sheet->mergeCells('J'.$baris.':'.$alpha.$baris);

                        $sheet->getStyle('J'.$indexval.':'.$alpha.$indexkat)->getFont()->setBold(true);
                        $sheet->getStyle('J'.$indexkat.':'.$alpha.$indexkat)->getAlignment()->setHorizontal('right');
                        $sheet->getStyle('J:'.$alpha)->getNumberFormat()->setFormatCode('#,##0.00');

                        $sheet->setCellValue('J'.$baris, 'Valas');
                        $sheet->setCellValue($alpha_tot.$indexkat, '=('.$alpha_tot.$baris_total.') + (E'.($indexsupport).')');
                    }
                } else {
                    $sheet->mergeCells('G'.$baris.':H'.$baris + 2);
                    $sheet->mergeCells('I'.$baris.':'.$alpha.$baris);

                    $sheet->getStyle('I'.$indexval.':'.$alpha.$indexkat)->getFont()->setBold(true);
                    $sheet->getStyle('I'.$indexkat.':'.$alpha.$indexkat)->getAlignment()->setHorizontal('right');
                    $sheet->getStyle('I:'.$alpha)->getNumberFormat()->setFormatCode('#,##0.00');

                    $sheet->setCellValue('I'.$baris, 'Valas');
                    $sheet->setCellValue('I'.$baris+1, 'IDR');
                    $sheet->setCellValue('I'.$indexkat, '=E'.($indexsupport));
                }

                $sheet->setCellValue('E'.$indexsupport, '=SUM(E'.$inde.':E'.($indexsupport - 1).')');
            }

            $spreadsheet->createSheet();
            $sheet1 = $spreadsheet->setActiveSheetIndex(1);

            // Rename worksheet
            $spreadsheet->getActiveSheet(1)->setTitle('Laporan Biaya dalam Rupiah');

            $sheet1->setCellValue('B1', 'PERJALANAN DINAS LUAR NEGERI PERIODE '.strtoupper(tanggal_indo1($tanggal_awal)).' HINGGA '.strtoupper(tanggal_indo1($tanggal_akhir)));
            $sheet1->setCellValue('B2', 'Bagian =>');
            $sheet1->setCellValue('B3', 'Negara =>');
            $sheet1->setCellValue('B4', 'Kategori =>');
            $sheet1->setCellValue('B6', 'Tanggal');
            $sheet1->setCellValue('C6', 'Kategori');
            $sheet1->setCellValue('D6', 'Status');
            $sheet1->setCellValue('E6', 'Ref');
            $sheet1->setCellValue('F6', 'Note');
            $sheet1->setCellValue('G6', 'Negara Tujuan');
            $sheet1->setCellValue('H6', 'Negara Transit');
            $sheet1->setCellValue('I6', 'Jumlah Personil');
            $sheet1->setCellValue('J6', 'Valas');
            $sheet1->setCellValue('J7', 'IDR');
            $sheet1->setCellValue('B'.$baris_total, 'TOTAL BIAYA');

            $sheet1->mergeCells('B1:J1');
            $sheet1->mergeCells('B6:B7');
            $sheet1->mergeCells('C6:C7');
            $sheet1->mergeCells('D6:D7');
            $sheet1->mergeCells('E6:E7');
            $sheet1->mergeCells('F6:F7');
            $sheet1->mergeCells('G6:G7');
            $sheet1->mergeCells('H6:H7');
            $sheet1->mergeCells('I6:I7');
            $sheet1->mergeCells('B'.$baris_total.':I'.$baris_total);

            $sheet1->getStyle('B'.$baris_total.':J'.$baris_total)->getFont()->setBold( true );
            $sheet1->getStyle('B:J')->getAlignment()->setHorizontal('center');
            $sheet1->getStyle('B:J')->getAlignment()->setVertical('center');
            $sheet1->getStyle('J8:J'.$baris_total)->getAlignment()->setHorizontal('right');
            $sheet1->getStyle('J')->getNumberFormat()->setFormatCode('#,##0.00');

            for ($k = 'B'; $k <= 'J'; $k++) {
                $spreadsheet->getActiveSheet(1)->getColumnDimension($k)->setWidth(20);
            }

            $sheet1->getColumnDimension('A')->setVisible(false);
            $sheet1->getColumnDimension('C')->setAutoSize(true);
            $sheet1->getColumnDimension('F')->setAutoSize(true);

            $i = 8;
            foreach ($kategori1 as $key => $value) {
                $sheet1->setCellValue('B'.$i, $value['tanggal']);
                $sheet1->setCellValue('C'.$i, $value['kategori']);
                $sheet1->setCellValue('D'.$i, $value['status']);
                $sheet1->setCellValue('E'.$i, $value['ref']);
                $sheet1->setCellValue('F'.$i, $value['note']);
                $sheet1->setCellValue('G'.$i, $value['negara_tujuan']);
                $sheet1->setCellValue('H'.$i, $value['negara_trading']);
                $sheet1->setCellValue('I'.$i, $value['jumlah_personil']);
                $sheet1->getStyle('B6:J'.$i+1)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $i++;
            }

            $i = 8;
            foreach ($biaya as $key => $value) {
                $id_valas = $value['id_valas'];
                $id_pjum = $value['id_pjum'];
                $id_pb = $value['id_pb'];
                $biaya = $value['biaya'];
                if ($id_valas != 76 && $id_pjum != null) {
                    $kurs = $this->m_kurs->where('id_pjum', $id_pjum)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                    if (empty($kurs)) {
                        if ($id_valas != 76) {
                            $kurs = 1;
                            $kurs_biaya = $biaya * $kurs;
                            $sheet1->setCellValue('J'.$i, $kurs_biaya);
                            $i++;
                        }
                    } else if (!empty($kurs)) {
                        foreach ($kurs as $k => $kur) {
                            if ($id_valas != 76 && $kur['id_valas']) {
                                $kurs = $kur['kurs'];
                                $kurs_biaya = $biaya * $kurs;
                                $sheet1->setCellValue('J'.$i, $kurs_biaya);
                                $i++;
                            }
                        }
                    }
                } else if ($id_valas != 76 && $id_pb != null) {
                    $kurs = $this->m_kurs->where('id_pb', $id_pb)->select('id_valas, kode_valas, tanggal, kurs')->findAll();
                    if (empty($kurs)) {
                        if ($id_valas != 76) {
                            $kurs = 1;
                            $kurs_biaya = $biaya * $kurs;
                            $sheet1->setCellValue('J'.$i, $kurs_biaya);
                            $i++;
                        }
                    } else if (!empty($kurs)) {
                        foreach ($kurs as $k => $kur) {
                            if ($id_valas != 76 && $kur['id_valas']) {
                                $kurs = $kur['kurs'];
                                $kurs_biaya = $biaya * $kurs;
                                $sheet1->setCellValue('J'.$i, $kurs_biaya);
                                $i++;
                            }
                        }
                    }
                } else if ($id_valas == 76) {
                    $sheet1->setCellValue('J'.$i, $biaya);
                    $i++;
                }
                
                $sheet1->setCellValue('J'.$baris_total, '=SUM(J8:J'.($baris_total - 1).')');
            }

            // Biaya Support

            if (empty($biayasupport)) {
                
            } else {
                $sheet1->setCellValue('B'.$baris, 'Biaya Support Perjalanan Dinas Luar Negeri');
                $sheet1->setCellValue('G'.$baris, 'TOTAL BIAYA (PJUM + PB + SUPPORT)');
                $sheet1->setCellValue('J'.$baris, 'Valas');
                $sheet1->setCellValue('J'.$baris+1, 'IDR');
                $sheet1->setCellValue('B'.$indexkat, 'Tanggal');
                $sheet1->setCellValue('C'.$indexkat, 'Kategori');
                $sheet1->setCellValue('D'.$indexkat, 'Jumlah Personil');
                $sheet1->setCellValue('E'.$indexkat, 'Biaya');
                $sheet1->setCellValue('E'.$indexkat+1, 'IDR');
                $sheet1->setCellValue('B'.$indexsupport, 'TOTAL BIAYA SUPPORT');

                $sheet1->mergeCells('B'.$baris.':E'.$baris + 1);
                $sheet1->mergeCells('G'.$baris.':I'.$baris + 2);
                $sheet1->mergeCells('B'.$indexkat.':B'.$indexkat + 1);
                $sheet1->mergeCells('C'.$indexkat.':C'.$indexkat + 1);
                $sheet1->mergeCells('D'.$indexkat.':D'.$indexkat + 1);
                $sheet1->mergeCells('B'.$indexsupport.':D'.$indexsupport);

                $sheet1->getStyle('B'.$baris)->getFont()->setBold(true);
                $sheet1->getStyle('G'.$baris)->getFont()->setBold(true);
                $sheet1->getStyle('B'.$indexsupport.':E'.$indexsupport)->getFont()->setBold(true);
                $sheet1->getStyle('J'.$indexkat)->getFont()->setBold(true);
                $sheet1->getStyle('E'.$inde.':E'.$indexsupport)->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet1->getStyle('E'.$inde.':E'.$indexsupport)->getAlignment()->setHorizontal('right');
                $sheet1->getStyle('J'.$indexkat)->getAlignment()->setHorizontal('right');

                for ($i=$baris; $i <= $indexsupport; $i++) { 
                    $sheet1->getStyle('B'.$baris.':E'.$indexsupport)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet1->getStyle('G'.$baris.':J'.$baris + 2)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet1->getStyle('D'.$baris.':D'.$i)->getNumberFormat()->setFormatCode('#');
                    $i++;
                }
    
                $row = $baris + 4;
                foreach ($kategorisupport as $key => $value) {
                    $sheet1->setCellValue('B'.$row, $value['tanggal']);
                    $sheet1->setCellValue('C'.$row, $value['kategori']);
                    $sheet1->setCellValue('D'.$row, $value['jumlah_personil']);
                    $row++;
                }

                $row = $baris + 4;
                foreach ($biayasupport as $key => $value) {
                    $sheet1->setCellValue('E'.$row, $value['biaya']);
                    $row++;
                }

                $sheet1->setCellValue('E'.$indexsupport, '=SUM(E'.$inde.':E'.($indexsupport - 1).')');
                $sheet1->setCellValue('J'.$indexkat, '=(J'.$baris_total.') + (E'.($indexsupport).')');
            }

            if(empty($strorgnm)) {
                $strorgnm = "Semua";
                $sheet->setCellValue('C2', $strorgnm);
                $sheet1->setCellValue('C2', $strorgnm);
            } else {
                $tmp_strorgnm = '';
                for ($i=0; $i < $count5; $i++) {
                    $tmp_strorgnm .= $strorgnm[$i].', ';
                    $sheet->setCellValue('C2', substr($tmp_strorgnm, 0, -2));
                    $sheet1->setCellValue('C2', substr($tmp_strorgnm, 0, -2));
                }
            }

            if(empty($negara)){
                $tmp_negara = "Semua";
                $sheet->setCellValue('C3', $tmp_negara);
                $sheet1->setCellValue('C3', $tmp_negara);
            } else {
                $tmp_negara = '';
                for ($i=0; $i < $count2; $i++) {
                    $tmp_negara .= $negara[$i].', ';
                    $sheet->setCellValue('C3', substr($tmp_negara, 0, -2));
                    $sheet1->setCellValue('C3', substr($tmp_negara, 0, -2));
                }
            }

            if(empty($kategori)) {
                $tmp_kategori = "Semua";
                $sheet->setCellValue('C4', $tmp_kategori);
                $sheet1->setCellValue('C4', $tmp_kategori);
            } else {
                $tmp_kategori = '';
                for ($i=0; $i < $count3; $i++) {
                    $tmp_kategori .= $kategori[$i].', ';
                    $sheet->setCellValue('C4', substr($tmp_kategori, 0, -2));
                    $sheet1->setCellValue('C4', substr($tmp_kategori, 0, -2));
                }
            }

            $spreadsheet->setActiveSheetIndex(0);

            $writer = new Xls($spreadsheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=Biaya Perjalanan Dinas LN periode '.tanggal_indo1($tanggal_awal).' sampai '.tanggal_indo1($tanggal_akhir).'.xls');
            $writer->save("php://output");
            // $writer->save('Biaya PDLN periode '.tanggal_indo1($tanggal_awal).' sampai '.tanggal_indo1($tanggal_akhir).'.xls');
            // return redirect()->to('transaksi');
        }

        $strorg = session()->get('strorg');
        $nik = session()->get('akun_nik');
        $niknm = session()->get('niknm');
        $role = session()->get('akun_role');

        $id_transaksi = $this->m_id->select('id_transaksi')->findAll();

        foreach ($id_transaksi as $key => $value) {
            $login_by = $this->m_id->where('id_transaksi', $value['id_transaksi'])->select('login_by')->first();
            if ($login_by['login_by'] == $niknm) {
                $data = [
                    'id_transaksi' => $value['id_transaksi'],
                    'login' => 0,
                    'login_by' => null,
                ];
                $this->m_id->save($data);
                return redirect()->to('transaksi');
            } else if ($login_by['login_by'] == null) {

            }
        }

        $bm06 = $this->m_bm06->getData($strorg);

        $akun = [
            'strorgnm' => $bm06['strorgnm'],
            'tglsls' => $bm06['tglsls'],
        ];
        session()->set($akun);
        $strorgnm = session()->get('strorgnm');

        if($role == 'admin') {
            $hasil = $this->m_id->listAdminId(substr($strorg, 0, 4));
        } else if($role == 'user') {
            $hasil = $this->m_id->listNikId($strorg, $nik);
        } else if($role == 'treasury') {
            $hasil = $this->m_id->listTreasury();
        } else if($role == 'gs') {
            $hasil = $this->m_id->listGS();
        }

        // echo substr($strorg, 0, 4);

        // $timestamp = date('Y-m-d H:i:s');
        // $time = (strtotime($timestamp));

        // $logout = $time - session()->get('login_at');

        // if($logout > 5){
        //     return redirect()->to('logout');
        // }

        session()->set('url_transaksi', current_url());

        $data = [
            'header' => "ID Transaksi Perjalanan Dinas Luar Negeri",
            'hasil' => $hasil,
            'id_t' => $this->m_id->getDataAll(),
            'role' => $role,
            'bag' => $this->m_bm06->bagian(substr($strorg, 0, 4)),
            'neg' => $this->m_negara->getDataAll(),
            'submit' => $submit,
            'date_min' => $this->m_id->where('SUBSTRING(strorg, 1, 4)', substr($strorg, 0, 4))->where('submit_pjum', 4)->where('submit_pb >=', 3)->select('tanggal_berangkat')->orderBy('tanggal_berangkat', 'asc')->first(),
            'date_max' => $this->m_id->where('SUBSTRING(strorg, 1, 4)', substr($strorg, 0, 4))->where('submit_pjum', 4)->where('submit_pb >=', 3)->select('tanggal_pulang')->orderBy('tanggal_pulang', 'desc')->first(),
        ];
        echo view('transaksi/v_transaksi', $data);
        // print_r(session()->get(''));
    }

    public function islogin($id_transaksi)
    {
        $nik = session()->get('akun_nik');
        $role = session()->get('akun_role');
        $niknm = session()->get('niknm');

        $login = $this->m_id->where('id_transaksi', $id_transaksi)->select('login')->first();

        if ($login['login'] == 0) {
            if ($role == 'admin' || $role == 'user') {
                $data = [
                    'id_transaksi' => $id_transaksi,
                    'login' => 1,
                    'login_by' => $niknm,
                ];
                $this->m_id->save($data);
                return redirect()->to('dashboard/'.$id_transaksi);
            }
        } else {
            session()->setFlashdata('warning', ['Id transaksi sedang diedit, harap menunggu beberapa saat lagi']);
            return redirect()->to("transaksi");
        }
    }

    public function tambahdataid()
    {
        $role = session()->get('akun_role');
        $strorg = session()->get('strorg');

        $nik = $this->m_am21->nik(substr($strorg, 0, 4));

        if($role == 'treasury') {
            return redirect()->to("transaksi");
        } elseif($role == 'gs') {
            return redirect()->to("transaksi");
        }

        $data = [];
        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            $aturan = [
                'jumlah_personil' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Jumlah Personil harus diisi'
                    ]
                ],
                'tanggal_berangkat' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Tanggal Keberangkatan harus diisi'
                    ]
                ],
                'tanggal_pulang' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Tanggal Pulang harus diisi'
                    ]
                ],
            ];
            if(!$this->validate($aturan)) {
                session()->setFlashdata('warning', $this->validation->getErrors());
            } else {
                $session = \Config\Services::session();
                $ni = $this->request->getVar('nik');
                $split = explode(" - ", $ni);
                if($role == 'admin') {
                    $ni = $split[0] ;
                    $strorg = $split[2];
                    $bagian = $this->m_bm06->where('strorg', $split[2])->select('strorgnm')->first();
                    $strorgnm = $bagian['strorgnm'];
                } elseif($role == 'user') {
                    $ni = session()->get('akun_nik');
                    $strorg = session()->get('strorg');
                    $strorgnm = session()->get('strorgnm');
                }
                $record = [
                    'nik' => $ni,
                    'role' => 'user',
                    'strorg' => $strorg,
                    'strorgnm' => $strorgnm,
                    'jumlah_personil' => $this->request->getVar('jumlah_personil'),
                    'tanggal_berangkat' => $this->request->getVar('tanggal_berangkat'),
                    'tanggal_pulang' => $this->request->getVar('tanggal_pulang'),
                    'created_by' => session()->get('akun_nik'),
                ];
                $aksi = $this->m_id->insertTransaksi($record);

                if($aksi != false) { //dibagian aksi tidak false atau ada isinya
                    $page_id = $aksi;
                    session()->setFlashdata('success', 'ID Transaksi Perjalanan Dinas Luar Negeri berhasil dibuat');
                    return redirect()->to('transaksi');
                } else {
                    session()->setFlashdata('warning', ['ID Transaksi Perjalanan Dinas Luar Negeri gagal dibuat']);
                    return redirect()->to('transaksi');
                }
            }
        }
        $data = [
            'header' => "Tambah Data ID Transaksi Perjalanan Dinas Luar Negeri",
            'id_t' => $this->m_id->getDataAll(),
            'role' => $role,
            'nik' => $nik,
        ];
        $data['id'] = $this->m_admin->selectData();
        echo view('transaksi/v_tambahdataid', $data);
        // print_r(session()->get());
    }

    public function detailtransaksi($id_transaksi)
    {
        $session = [
            'id_transaksi' => $id_transaksi,
        ];
        session()->set($session);
        $id_transaksi = session()->get('id_transaksi');

        $strorg = session()->get('strorg');
        $nik = session()->get('akun_nik');
        $role = session()->get('akun_role');
        if($role == 'admin') {
            $dataPost = $this->m_id->getPostId($id_transaksi, substr($strorg, 0, 4));
        } elseif($role == 'user') {
            $dataPost = $this->m_id->getId($id_transaksi, $nik);
        } elseif($role == 'treasury') {
            $dataPost = $this->m_id->getTreasury($id_transaksi);
        } elseif($role == 'gs') {
            $dataPost = $this->m_id->getGS($id_transaksi);
        }
        if(empty($dataPost)) {
            return redirect()-> to("transaksi");
        }
        $data = $dataPost;

        $submit_pjum = $this->m_id->where('id_transaksi', $id_transaksi)->select('submit_pjum')->first();
        $submit_pb = $this->m_id->where('id_transaksi', $id_transaksi)->select('submit_pb')->first();

        if ($role == 'treasury' && $submit_pjum['submit_pjum'] != 1 && $submit_pb['submit_pb'] != 1) {
            return redirect()-> to("transaksi");
        } elseif ($role == 'gs' && $submit_pjum['submit_pjum'] < 2 && $submit_pb['submit_pb'] < 2) {
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
        $kota = $this->m_id->where('id_transaksi', $id_transaksi)->select('kota as kota')->first();

        if($role == 'treasury') {
            $id = $this->m_id->getTreasury($id_transaksi);
        } elseif($role == 'gs') {
            $id = $this->m_id->getGS($id_transaksi);
        } else {
            $id = $this->m_id->getPostId($id_transaksi, substr($strorg, 0, 4));
        }

        $data = [
            'header' => " Detail ID Transaksi Perjalanan Dinas Luar Negeri",
            'id' => $id,
            'kot' => $kota['kota'],
            'personil' => $personil,
            'neg' => $negara,
            'negara' => $negara,
        ];
        echo view('transaksi/v_detailtransaksi', $data);
        // print_r(session()->get());
    }

    public function tambahpersonil($id_transaksi)
    {
        $nik = session()->get('akun_nik');
        $role = session()->get('akun_role');

        if($role == 'treasury') {
            return redirect()->to("transaksi");
        } elseif($role == 'gs') {
            return redirect()->to("transaksi");
        }

        $strorg = session()->get('strorg');
        if($role == 'admin') {
            $dataPost = $this->m_id->getPostId($id_transaksi, substr($strorg, 0, 4));
        } elseif($role == 'user') {
            $dataPost = $this->m_id->getId($id_transaksi, $nik);
        }
        if(empty($dataPost)) {
            return redirect()-> to("transaksi");
        }
        $data = $dataPost;

        $session = [
            'id_transaksi' => $id_transaksi,
        ];
        session()->set($session);
        $id_transaksi = session()->get('id_transaksi');

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            $aturan = [
                'niknm' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama Lengkap Personil harus diisi'
                    ]
                ],
                'strorgnm' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Bagian harus diisi'
                    ]
                ],
                'kota' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harap isi berangkat dari kota mana'
                    ]
                ],
            ];
            if(!$this->validate($aturan)) {
                session()->setFlashdata('warning', $this->validation->getErrors());
            } else {
                $session = \Config\Services::session();
                // $nama_lengkap_string = implode(" ",$nama_lengkap);
                // $inisial_string = implode(" ",$inisial);
                // $nik_string = implode(" ",$nik);
                // $jabatan_string = implode(" ",$jabatan);

                if(isset($_POST['submit'])) {
                    $n = $_POST['niknm'];
                    if(!empty($n)) {
                        for($a = 0; $a < count($n); $a++) {
                            if(!empty($n[$a])) {
                                $username = $n[$a];
                                //membuat insert data sementara
                                // echo 'Data ke -' .($a+1).'=> Nama: '.$username.';</br>';
                                $nama = $_POST['niknm'][$a];
                                $split = explode(" - ", $nama);
                                $record[] = array(
                                    'niknm' => $split[0],
                                    'nik' => $split[1],
                                    'strorgnm' => $_POST['strorgnm'],
                                    'id_transaksi' => $id_transaksi,
                                );
                                $kota = [
                                    'id_transaksi' => $id_transaksi,
                                    'kota' => $this->request->getVar('kota'),
                                ];
                            }
                        }
                    }
                }
                $aksi = $this->m_personil->insertPersonil($record);
                $this->m_id->save($kota);

                if($aksi != true) { //dibagian aksi tidak false atau ada isinya
                    $page_id = $aksi;
                    session()->setFlashdata('success', 'Data berhasil ditambahkan');
                    return redirect()->to('tambahnegara/'.$id_transaksi);
                } else {
                    session()->setFlashdata('warning', ['Data gagal ditambahkan']);
                    return redirect()->to('tambahpersonil/'.$id_transaksi);
                }
            }
        }
        $data = [
            'header' => "Tambah Data Personil",
            'bag' => $this->m_bm06->bagian(substr($strorg, 0, 4)),
            'nama' => $this->m_am21->nik(substr($strorg, 0, 4)),
            'neg' => $this->m_negara->getDataAll(),
            'kot' => $this->m_kota->getDataAll(),
            'id_t' => $this->m_id->getDataAll(),
            'personil' => $this->m_personil->getDataAllId($id_transaksi),
        ];
        $data['id'] = $this->m_admin->selectData();
        echo view('transaksi/v_tambahpersonil', $data);
        // print_r(session()->get());
    }

    public function tambahnegara($id_transaksi)
    {
        $nik = session()->get('akun_nik');
        $niknm = session()->get('niknm');
        $role = session()->get('akun_role');

        if($role == 'treasury') {
            return redirect()->to("transaksi");
        } elseif($role == 'gs') {
            return redirect()->to("transaksi");
        }

        $strorg = session()->get('strorg');
        if($role == 'admin') {
            $dataPost = $this->m_id->getPostId($id_transaksi, substr($strorg, 0, 4));
        } elseif($role == 'user') {
            $dataPost = $this->m_id->getId($id_transaksi, $nik);
        }
        if(empty($dataPost)) {
            return redirect()-> to("transaksi");
        }
        $data = $dataPost;

        $session = [
            'id_transaksi' => $id_transaksi,
        ];
        session()->set($session);
        $id_transaksi = session()->get('id_transaksi');

        if($this->request->getMethod() == 'post') {
            $data = $this->request->getVar(); //setiap yang diinputkan akan dikembalikan ke view
            $aturan = [
                'negara_tujuan' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Negara Tujuan harus diisi'
                    ]
                ],
            ];
            if(!$this->validate($aturan)) {
                session()->setFlashdata('warning', $this->validation->getErrors());
            } else {
                $session = \Config\Services::session();

                if(isset($_POST['submit'])) {
                    $n = $_POST['negara_tujuan'];
                    if(!empty($n)) {
                        for($a = 0; $a < count($n); $a++) {
                            if(!empty($n[$a])) {
                                $username = $n[$a];
                                //membuat insert data sementara
                                // echo 'Data ke -' .($a+1).'=> Nama: '.$username.';</br>';
                                $record[] = array(
                                    'negara_tujuan' => $_POST['negara_tujuan'][$a],
                                    'id_transaksi' => $id_transaksi,
                                );
                            }
                        }
                    }
                }
                $aksi = $this->m_negara_tujuan->insertNegara($record);

                if($aksi != true) { //dibagian aksi tidak false atau ada isinya
                    $page_id = $aksi;
                    $data = [
                        'id_transaksi' => $id_transaksi,
                        'login' => 1,
                        'login_by' => $niknm,
                    ];
                    $this->m_id->save($data);
                    session()->setFlashdata('success', 'Data berhasil ditambahkan');
                    return redirect()->to('dashboard/'.$id_transaksi);
                } else {
                    session()->setFlashdata('warning', ['Data gagal ditambahkan']);
                    return redirect()->to('tambahnegara/'.$id_transaksi);
                }
            }
        }
        $data = [
            'header' => "Tambah Data Negara Tujuan",
            'neg' => $this->m_negara->getDataAll(),
            'id_t' => $this->m_id->getDataAll(),
        ];
        $data['id'] = $this->m_admin->selectData();
        echo view('transaksi/v_tambahnegara', $data);
        // print_r(session()->get());
    }
}
