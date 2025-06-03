<?php

namespace App\Models;

use CodeIgniter\Model;

class ETiketModel extends Model
{
    protected $table = "e_tiket";
    protected $primaryKey = "id_e_tiket";
    protected $allowedFields =['id_trans', 'id_tiket', 'id_detail_pengguna', 'a1_nilai', 'b1_nilai', 'c1_nilai', 'd1_nilai', 'komentar', 'status', 'tgl_input'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder->where('nama_pengguna=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function e_tiket_id($id_e_tiket)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_e_tiket', $id_e_tiket);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_e_tiket($id_e_tiket){
        $builder= $this->table($this->table);
        $builder->where('id_e_tiket', $id_e_tiket);
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