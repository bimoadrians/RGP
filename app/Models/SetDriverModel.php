<?php

namespace App\Models;

use CodeIgniter\Model;

class SetDriverModel extends Model
{
    protected $DBGroup              = 'default';
	protected $table                = 'set_driver';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_trans',
		'id_transportasi',
		'id_pengemudi',
		'id_mobil',
		'id_pool',
		'title',
		'description',
		'start',
		'end',
		'tanggal_mobil',
		'tujuan_mobil',
		'jam_siap',
		'jam_selesai'
	];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

	public function set_driver_id($id_set_driver)
    {
        $builder = $this->table($this->table);

        $builder -> where('id', $id_set_driver);

        $query = $builder->get();
        return $query->getRowArray();
    }

    function delete_set_driver($id_set_driver){
        $builder= $this->table($this->table);
        $builder->where('id', $id_set_driver);
        if($builder->delete()){
            return true;
        } else {
            return false;
        }
    }
}   