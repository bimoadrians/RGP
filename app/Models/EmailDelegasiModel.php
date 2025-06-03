<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailDelegasiModel extends Model
{
    protected $table = "email_delegasi";
    protected $primaryKey = "id_email_delegasi";
    protected $allowedFields = ['id_pengguna', 'id_detail_pengguna', 'id_pool', 'username', 'email_pengguna', 'tanggal_jam_mulai', 'tanggal_jam_akhir', 'edited_by', 'edited_at'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder->Where('username=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function email_delegasi_id($id_email_delegasi)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_email_delegasi', $id_email_delegasi);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_email_delegasi($id_email_delegasi){
        $builder= $this->table($this->table);
        $builder->where('id_email_delegasi', $id_email_delegasi);
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
