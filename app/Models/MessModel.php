<?php

namespace App\Models;

use CodeIgniter\Model;

class MessModel extends Model
{
    protected $table = "mess_kx_jkt";
    protected $primaryKey = "id_mess";
    protected $allowedFields = ['id_hotel', 'nama_kamar', 'kapasitas_kamar', 'terpakai', 'tanggal_mess', 'tgl_input', 'edited_at'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);

        $builder->select('nama_mess');
        $builder->where('id_mess=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function mess_id($id_mess)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_mess', $id_mess);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_mess($id_mess){
        $builder= $this->table($this->table);
        $builder->where('id_mess', $id_mess);
        if($builder->delete()){
            return true;
        } else {
            return false;
        }
    }

    public function getDataAll()
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder->orderBy('id_mess', 'asc');
        $query = $builder->findAll();
        return $query;
    }

    public function getDataNik($id_mess)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder -> where('id_mess', $nik);
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

    public function mess($strorg)
    {
        $builder = $this->table($this->table);

        $builder->select('strorgnm');

        $builder->Where('SUBSTRING(strorg, 1, 4)', $strorg);
        $builder->orderBy('strorg', 'asc');

        $query = $builder->get();
        return $query->getResultArray();
    }
}
