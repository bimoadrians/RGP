<?php

namespace App\Models;

use CodeIgniter\Model;

class TiketModel extends Model
{
    protected $table = "tiket";
    protected $primaryKey = "id_tiket";
    protected $allowedFields =['id_trans', 'id_vendor', 'id_keberangkatan', 'id_pemberhentian', 'id_pool', 'peminta', 'atas_nama', 'jenis_kelamin', 'jabatan', 'jumlah_tiket', 'pembayaran', 'harga_tiket', 'refund_tiket', 'tanggal_jam_tiket', 'dari_tiket', 'tujuan_tiket', 'email_info', 'email_eval', 'kirim_eval', 'status_tiket', 'batal_tiket', 'keterangan_tiket', 'tgl_input', 'edited_by', 'edited_at'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder->where('nama_pengguna=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function tiket_id($id_tiket)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_tiket', $id_tiket);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_tiket($id_tiket){
        $builder= $this->table($this->table);
        $builder->where('id_tiket', $id_tiket);
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