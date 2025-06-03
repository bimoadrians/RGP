<?php

namespace App\Models;

use CodeIgniter\Model;

class MobilModel extends Model
{
    protected $table = "mobil";
    protected $primaryKey = "id_mobil";
    protected $allowedFields =['id_pool', 'id_jenis_bbm', 'id_jenis_kendaraan', 'nama_mobil', 'nopol', 'non_mobil', 'tgl_stnk', 'tgl_keur', 'km_mesin', 'km_awal_mesin', 'km_oli', 'km_awal_oli', 'km_bbm', 'km_awal_bbm', 'km_udara', 'km_awal_udara'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder->where('nama_mobil=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function mobil_id($id_mobil)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_mobil', $id_mobil);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_mobil($id_mobil){
        $builder= $this->table($this->table);
        $builder->where('id_mobil', $id_mobil);
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