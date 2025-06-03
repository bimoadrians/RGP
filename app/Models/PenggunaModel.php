<?php

namespace App\Models;

use CodeIgniter\Model;

class PenggunaModel extends Model
{
    protected $table = "pengguna";
    protected $primaryKey = "id_pengguna";
    protected $allowedFields = ['nama_pengguna', 'nik_pengguna', 'email_pengguna', 'jenis_kelamin', 'no_hp_pengguna', 'alamat_rumah', 'tgl_input'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder->Where('email_pengguna=', $parameter);
        $builder->orWhere('nik_pengguna=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function pengguna_id($id_pengguna)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_pengguna', $id_pengguna);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_pengguna($id_pengguna){
        $builder= $this->table($this->table);
        $builder->where('id_pengguna', $id_pengguna);
        if($builder->delete()){
            return true;
        } else {
            return false;
        }
    }

    public function getDataAll()
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $query = $builder->findAll();
        return $query;
    }

    public function getDataNik($nik)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder -> where('nik', $nik);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function selectData(){
        $builder = $this->table($this->table);
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function updateData($data)
    {
        $builder = $this->table($this->table);
        if($builder->save($data)) {
            return true;
        } else {
            return false;
        }
    }
}
