<?php

namespace App\Models;

use CodeIgniter\Model;

class VendorModel extends Model
{
    protected $table = "vendor";
    protected $primaryKey = "id_vendor";
    protected $allowedFields =['jenis_vendor', 'nama_vendor', 'tgl_input'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder->where('nama_vendor=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function vendor_id($id_vendor)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_vendor', $id_vendor);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_vendor($id_vendor){
        $builder= $this->table($this->table);
        $builder->where('id_vendor', $id_vendor);
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