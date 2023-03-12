<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

/**
 * Class Clients
 *
 * @package App\carsManufacturer
 *
 * @property int $ref
 * @property integer $idClient
 * @property string $dateCurr
 * @property string $decision
 * @property string $dateBirthday
 * @property string $phone
 * @property string $mail
 * @property string $address
 * @property numeric $monthSalary
 * @property string $currSalary
 * @property numeric $requestLimit
 * @property numeric $limitItog
 */
class Clients extends Model
{
    use HasFactory;

    /**
     *
     *
     * @var string
     */
    protected $table = 'сlient';

    protected $primaryKey = 'Ref';
    public $incrementing = false;

    /**
     *
     *
     * @var array
     */
    public $fillable = [
        'ref',
        'idClient',
        'dateCurr',
        'phone',
        'mail',
        'address',
        'monthSalary',
        'currSalary',
        'decision',
        'requestLimit',
        'limitItog',
    ];

    protected $casts = [
        'monthSalary' => 'decimal:2',
        'limitItog' => 'decimal:2',
    ];

    public static function ValidateRequest($request)
    {
        $validator = Validator::make($request, [
            'idClient' => 'integer|required',
            'dateBirthday' => 'string|required|max:10',
            'phone' => 'string|required',
            'mail' => 'string',
            'address' => 'string',
            'monthSalary' => 'numeric',
            'currSalary' => 'string',
            'requestLimit' => 'numeric|required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'message' => 'Validation error'], 400);
        }
    }
}
