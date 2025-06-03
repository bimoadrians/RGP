<?php

namespace App\Models;

use CodeIgniter\Model;

class ETransportasiModel extends Model
{
    protected $table = "e_transportasi";
    protected $primaryKey = "id_e_transportasi";
    protected $allowedFields = ['id_trans', 'id_transportasi', 'id_transportasi_jemput', 'id_detail_pengguna', 'id_pengemudi', 'a1_nilai', 'b1_nilai', 'c1_nilai', 'd1_nilai', 'a2_nilai', 'b2_nilai', 'c2_nilai', 'd2_nilai', 'e2_nilai', 'f2_nilai', '3_nilai', '4_nilai', 'a5_nilai', 'b5_nilai', 'komentar', 'status', 'tgl_input'];

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