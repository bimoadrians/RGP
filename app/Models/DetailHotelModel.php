<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailHotelModel extends Model
{
    protected $table = "detail_hotel";
    protected $primaryKey = "id_detail_hotel";
    protected $allowedFields =['id_hotel', 'jenis_kamar', 'price_kamar', 'tgl_valid', 'tgl_input'];

    public function getData($parameter)
    {
        $builder = $this->table /*builder*/ ($this->table /*Model*/);
        $builder->where('jenis_kamar=', $parameter);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function detail_hotel_id($id_detail_hotel)
    {
        $builder = $this->table($this->table);

        $builder -> where('id_detail_hotel', $id_detail_hotel);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_detail_hotel($id_detail_hotel){
        $builder= $this->table($this->table);
        $builder->where('id_detail_hotel', $id_detail_hotel);
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