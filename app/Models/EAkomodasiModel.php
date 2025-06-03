<?php

namespace App\Models;

use CodeIgniter\Model;

class EAkomodasiModel extends Model
{
    protected $table = "e_akomodasi";
    protected $primaryKey = "id_e_akomodasi";
    protected $allowedFields =['id_trans', 'id_akomodasi', 'id_detail_pengguna', 'a1_nilai', 'b1_nilai', 'c1_nilai', 'd1_nilai', 'e1_nilai', 'f1_nilai', 'g1_nilai', 'a2_nilai', 'b2_nilai', 'c2_nilai', 'd2_nilai', 'komentar', 'status', 'tgl_input'];

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