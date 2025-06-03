<?php

namespace App\Models;

use CodeIgniter\Model;

class TransportasiModel extends Model
{
    protected $table = "transportasi";
    protected $primaryKey = "id_transportasi";
    protected $allowedFields =['id_trans', 'id_pool', 'id_pengemudi', 'id_mobil', 'jemput', 'peminta', 'atas_nama', 'jenis_kelamin', 'jabatan', 'jumlah_mobil', 'pembayaran', 'jenis_kendaraan', 'tenaga_angkut', 'dalkot_lukot', 'menginap', 'kapasitas', 'tujuan_mobil', 'siap_di', 'tanggal_mobil', 'jam_siap', 'jam_selesai', 'email_info', 'email_eval', 'kirim_eval', 'status_mobil', 'batal_transportasi', 'keterangan_mobil', 'keterangan_gs', 'tgl_input', 'edited_by', 'edited_at'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder->where('nama_pengguna=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function transportasi_id($id_transportasi)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_transportasi', $id_transportasi);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_transportasi($id_transportasi){
        $builder= $this->table($this->table);
        $builder->where('id_transportasi', $id_transportasi);
        if($builder->delete()){
            return true;
        } else {
            return false;
        }
    }

    public function getDataAll()
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $query = $builder->get();
        return $query->getResultArray();
    }
}   