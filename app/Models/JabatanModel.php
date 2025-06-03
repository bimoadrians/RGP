<?php

namespace App\Models;

use CodeIgniter\Model;

class JabatanModel extends Model
{
    protected $table = "jabatan";
    protected $primaryKey = "id_jabatan";
    protected $allowedFields = ['id_bagian', 'nama_jabatan', 'bts_eval', 'tgl_input'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);

        $builder->select('nama_bagian');
        $builder->where('id_bagian=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function jabatan_id($id_jabatan)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_jabatan', $id_jabatan);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_jabatan($id_jabatan){
        $builder= $this->table($this->table);
        $builder->where('id_jabatan', $id_jabatan);
        if($builder->delete()){
            return true;
        } else {
            return false;
        }
    }

    public function getDataAll()
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder->orderBy('id_bagian', 'asc');
        $query = $builder->findAll();
        return $query;
    }

    public function getDataNik($id_bagian)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder -> where('id_bagian', $nik);
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

    public function bagian($strorg)
    {
        $builder = $this->table($this->table);

        $builder->select('strorgnm');

        $builder->Where('SUBSTRING(strorg, 1, 4)', $strorg);
        $builder->orderBy('strorg', 'asc');

        $query = $builder->get();
        return $query->getResultArray();
    }
}
