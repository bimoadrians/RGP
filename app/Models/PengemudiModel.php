<?php

namespace App\Models;

use CodeIgniter\Model;

class PengemudiModel extends Model
{
    protected $table = "pengemudi";
    protected $primaryKey = "id_pengemudi";
    protected $allowedFields =['id_pool', 'id_mobil', 'nama_pengemudi', 'jenis_sopir', 'nomor_hp', 'email', 'tgl_input', 'tanggal_jam_awal', 'tanggal_jam_akhir', 'edited_at'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder->where('nama_pengemudi=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function pengemudi_id($id_pengemudi)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_pengemudi', $id_pengemudi);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_pengemudi($id_pengemudi){
        $builder= $this->table($this->table);
        $builder->where('id_pengemudi', $id_pengemudi);
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