<?php

namespace App\Models;

use CodeIgniter\Model;

class NegaraModel extends Model
{
    protected $table = "negara";
    protected $primaryKey = "id_negara";
    protected $allowedFields =['nama_negara', 'tgl_input'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder->where('nama_negara=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function negara_id($id_negara)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_negara', $id_negara);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_negara($id_negara){
        $builder= $this->table($this->table);
        $builder->where('id_negara', $id_negara);
        if($builder->delete()){
            return true;
        } else {
            return false;
        }
    }

    public function getDataAll()
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);

        $builder->select('negara_tujuan');

        $query = $builder->get();
        return $query->getResultArray();
    }
}   