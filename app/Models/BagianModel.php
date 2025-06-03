<?php

namespace App\Models;

use CodeIgniter\Model;

class BagianModel extends Model
{
    protected $table = "bagian";
    protected $primaryKey = "id_bagian";
    protected $allowedFields = ['nama_bagian', 'tgl_input'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);

        $builder->select('nama_bagian');
        $builder->where('id_bagian=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function bagian_id($id_bagian)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_bagian', $id_bagian);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_bagian($id_bagian){
        $builder= $this->table($this->table);
        $builder->where('id_bagian', $id_bagian);
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
