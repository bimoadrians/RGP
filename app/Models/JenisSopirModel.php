<?php

namespace App\Models;

use CodeIgniter\Model;

class JenisSopirModel extends Model
{
    protected $table = "jenis_sopir";
    protected $primaryKey = "id_jenis_sopir";
    protected $allowedFields = ['jenis_sopir'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);

        $builder->select('nama_jenis_sopir');
        $builder->where('id_jenis_sopir=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function jenis_sopir_id($id_jenis_sopir)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_jenis_sopir', $id_jenis_sopir);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_jenis_bbm($id_jenis_sopir){
        $builder= $this->table($this->table);
        $builder->where('id_jenis_sopir', $id_jenis_sopir);
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
