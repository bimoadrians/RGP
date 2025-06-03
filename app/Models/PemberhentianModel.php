<?php

namespace App\Models;

use CodeIgniter\Model;

class PemberhentianModel extends Model
{
    protected $table = "pemberhentian";
    protected $primaryKey = "id_pemberhentian";
    protected $allowedFields =['jenis_pemberhentian', 'nama_pemberhentian', 'nama_kota', 'tgl_input'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder->where('nama_pemberhentian=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function pemberhentian_id($id_pemberhentian)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_pemberhentian', $id_pemberhentian);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_pemberhentian($id_pemberhentian){
        $builder= $this->table($this->table);
        $builder->where('id_pemberhentian', $id_pemberhentian);
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