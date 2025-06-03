<?php

namespace App\Models;

use CodeIgniter\Model;

class TujuanModel extends Model
{
    protected $table = "tujuan";
    protected $primaryKey = "id_tujuan";
    protected $allowedFields = ['nama_tujuan', 'tgl_input'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);

        $builder->select('id_atasan');
        $builder->where('id_bawahan=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function tujuan_id($id_tujuan)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_tujuan', $id_tujuan);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_tujuan($id_tujuan){
        $builder= $this->table($this->table);
        $builder->where('id_tujuan', $id_tujuan);
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
