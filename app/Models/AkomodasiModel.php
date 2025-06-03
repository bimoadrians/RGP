<?php

namespace App\Models;

use CodeIgniter\Model;

class AkomodasiModel extends Model
{
    protected $table = "akomodasi";
    protected $primaryKey = "id_akomodasi";
    protected $allowedFields =['id_trans', 'id_hotel', 'id_detail_hotel', 'id_mess', 'id_pool', 'id_kota', 'status_mess', 'peminta', 'atas_nama', 'jenis_kelamin', 'jabatan', 'type', 'jumlah_kamar', 'jumlah_personil_mess', 'pembayaran', 'harga_akomodasi', 'refund_akomodasi', 'email_info', 'email_eval', 'tanggal_jam_masuk', 'tanggal_jam_keluar', 'kirim_eval', 'status_akomodasi', 'batal_akomodasi', 'keterangan_akomodasi', 'tgl_input', 'edited_by', 'edited_at'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder->where('nama_pengguna=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function akomodasi_id($id_akomodasi)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_akomodasi', $id_akomodasi);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_akomodasi($id_akomodasi){
        $builder= $this->table($this->table);
        $builder->where('id_akomodasi', $id_akomodasi);
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