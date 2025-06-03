<?php

namespace App\Models;

use CodeIgniter\Model;

class TanggalMessModel extends Model
{
    protected $table = "tanggal_mess";
    protected $primaryKey = "id_tanggal_mess";
    protected $allowedFields = ['id_trans', 'tanggal_mess', 'jumlah_personil', 'status', 'batal'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);

        $builder->select('nama_tanggal_mess');
        $builder->where('id_tanggal_mess=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function tanggal_mess_id($id_tanggal_mess)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_tanggal_mess', $id_tanggal_mess);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_tanggal_mess($id_tanggal_mess){
        $builder = $this->table($this->table);
        $builder->where('id_tanggal_mess', $id_tanggal_mess);
        if($builder->delete()){
            return true;
        } else {
            return false;
        }
    }

    public function getDataAll()
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder->orderBy('id_tanggal_mess', 'asc');
        $query = $builder->findAll();
        return $query;
    }

    public function getDataNik($id_tanggal_mess)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder -> where('id_tanggal_mess', $nik);
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

    public function tanggal_mess($strorg)
    {
        $builder = $this->table($this->table);

        $builder->select('strorgnm');

        $builder->Where('SUBSTRING(strorg, 1, 4)', $strorg);
        $builder->orderBy('strorg', 'asc');

        $query = $builder->get();
        return $query->getResultArray();
    }
}
