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

    public function getSigner(array $where=[], array $select=null,
        array $pagination=[])
    {
        $_page = isset($pagination['page']) && is_numeric($pagination['page']) ? $pagination['page'] : 1;
        $_size = isset($pagination['size']) && is_numeric($pagination['size']) ? $pagination['size'] : 7;

        $_select = isset($select) && is_array($select) ?
            $select : [];

        $signer = $this
            ->where($where)
            ->select($_select)
            ->paginate($_size, ['*'], 'page', $_page)
            ->toArray();

        return $signer;
    }
}
