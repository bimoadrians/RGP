<?php

namespace App\Models;

use CodeIgniter\Model;

class PoolModel extends Model
{
    protected $table = "pool";
    protected $primaryKey = "id_pool";
    protected $allowedFields =['nama_pool', 'tgl_input', 'no_hp_pool', 'email_pool'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder->where('nama_kota=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function pool_id($id_pool)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_pool', $id_pool);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_pool($id_pool){
        $builder= $this->table($this->table);
        $builder->where('id_pool', $id_pool);
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