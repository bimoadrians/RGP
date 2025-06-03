<?php

namespace App\Models;

use CodeIgniter\Model;

class TransModel extends Model
{
    protected $table = "trans";
    protected $primaryKey = "id_trans";
    protected $allowedFields =['id_trans', 'id_detail_pengguna', 'id_bagian', 'pic', 'tamu', 'pemesanan', 'alasan_batal', 'tgl_input', 'edited_by', 'edited_at'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder->where('nama_kota=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function trans_id($id_trans)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_trans', $id_trans);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_trans($id_trans){
        $builder= $this->table($this->table);
        $builder->where('id_trans', $id_trans);
        if($builder->delete()){
            return true;
        } else {
            return false;
        }
    }

    public function getDataAll()
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $query = $builder->get();
        return $query->getResultArray();
    }
}   