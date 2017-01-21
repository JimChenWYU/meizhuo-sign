<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signer extends Model
{
    protected $table = 'signer';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'student_id',
        'major',
        'phone_num',
        'grade',
        'department',
        'introduce',
    ];
    //
    public function insertSigner(array $data=[])
    {
        $user = $this
            ->where('student_id', $data['student_id'])
            ->select(['department'])->get()->toArray();

        $department = ($data['department'] == "移动组") ? "安卓组" : $data['department'];

        $response = function ($msg='success', $state=true) {
            return [ 'state' => $state, 'msg' => $msg ];
        };

        foreach ($user as &$value) {
            if ($value['department'] == $data['department']) {
                return $response('你已经报名面试'.$department.'！', false);
            }
        }

        if ($data && $this->create($data)) {
            return $response();
        }

        return $response('error', false);
    }

    public function getSigner(array $where=[], $current_page=1, array $select=null)
    {
        $_page = is_numeric($current_page) ? $current_page : 1;

        $_select = isset($select) && is_array($select) ?
            $select : (is_array($current_page) ? $current_page : []);

        $signer = $this
            ->where($where)
            ->select($_select)
            ->paginate(7, ['*'], 'page', $_page)
            ->toArray();

        return $signer;
    }
}
