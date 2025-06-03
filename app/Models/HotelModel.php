<?php

namespace App\Models;

use CodeIgniter\Model;

class HotelModel extends Model
{
    protected $table = "hotel";
    protected $primaryKey = "id_hotel";
    protected $allowedFields =['id_kota', 'nama_hotel', 'alamat_hotel', 'telp_hotel', 'email_hotel', 'bintang_hotel', 'tgl_input'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder->where('nama_hotel=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function hotel_id($id_hotel)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_hotel', $id_hotel);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_hotel($id_hotel){
        $builder= $this->table($this->table);
        $builder->where('id_hotel', $id_hotel);
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