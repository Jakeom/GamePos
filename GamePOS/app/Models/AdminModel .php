<?php 
namespace App\Models;
use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'ADMIN';
    protected $primaryKey = 'ADMIN_NO';
    protected $allowedFields = ['ADMIN_NO', 'ADMIN_ID'];
}