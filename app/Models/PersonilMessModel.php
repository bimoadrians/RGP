<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonilMessModel extends Model
{
    protected $table = "personil_mess";
    protected $primaryKey = "id_personil_mess";
    protected $allowedFields = ['id_trans', 'id_akomodasi', 'atas_nama', 'jenis_kelamin', 'kamar_mess', 'tanggal_mess', 'status', 'batal'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);

        $builder->select('nama_personil_mess');
        $builder->where('id_personil_mess=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function personil_mess_id($id_personil_mess)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_personil_mess', $id_personil_mess);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_personil_mess($id_personil_mess){
        $builder= $this->table($this->table);
        $builder->where('id_personil_mess', $id_personil_mess);
        if($builder->delete()){
            return true;
        } else {
            return false;
        }
    }

    public function getDataAll()
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder->orderBy('id_personil_mess', 'asc');
        $query = $builder->findAll();
        return $query;
    }

    public function getDataNik($id_personil_mess)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder -> where('id_personil_mess', $nik);
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

    public function personil_mess($strorg)
    {
        $builder = $this->table($this->table);

        $builder->select('strorgnm');

        $builder->Where('SUBSTRING(strorg, 1, 4)', $strorg);
        $builder->orderBy('strorg', 'asc');

        $query = $builder->get();
        return $query->getResultArray();
    }
}
