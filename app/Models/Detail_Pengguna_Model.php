<?php

namespace App\Models;

use CodeIgniter\Model;

class Detail_pengguna_Model extends Model
{
    protected $table = "detail_pengguna";
    protected $primaryKey = "id_detail_pengguna";
    protected $allowedFields = ['id_pengguna', 'id_pool', 'id_bagian', 'id_jabatan', 'username', 'pass', 'admin_gs', 'tgl_input'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder->where('id_pengguna=', $parameter);
        $builder->orWhere('username=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function detail_pengguna_id($id_detail_pengguna)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_detail_pengguna', $id_detail_pengguna);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_detail_pengguna($id_detail_pengguna){
        $builder= $this->table($this->table);
        $builder->where('id_detail_pengguna', $id_detail_pengguna);
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

    public function pass($id_pengguna)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);

        $builder->select('pass');
        $builder ->where('id_pengguna', $id_pengguna);

        $query = $builder->get();
        return $query->getRowArray();
    }

    public function nama_pool($id_pengguna)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);

        $builder->select('nama_pool');
        $builder ->where('id_pengguna', $id_pengguna);

        $query = $builder->get();
        return $query->getRowArray();
    }

    public function admin_gs($id_pengguna)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);

        $builder->select('admin_gs');
        $builder ->where('id_pengguna', $id_pengguna);

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

    public function nik($strorg)
    {
        $builder = $this->table($this->table);

        $builder->Where('SUBSTRING(strorg, 1, 4)', $strorg);
        $builder->orderBy('nik', 'asc');

        $query = $builder->get();
        return $query->getResultArray();
    }
}
