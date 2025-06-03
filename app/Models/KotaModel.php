<?php

namespace App\Models;

use CodeIgniter\Model;

class KotaModel extends Model
{
    protected $table = "kota";
    protected $primaryKey = "id_kota";
    protected $allowedFields =['id_negara', 'id_pool', 'nama_kota', 'tgl_input'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder->where('nama_kota=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function kota_id($id_kota)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_kota', $id_kota);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_kota($id_kota){
        $builder= $this->table($this->table);
        $builder->where('id_kota', $id_kota);
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