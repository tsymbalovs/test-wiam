<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Model loan
 *
 * @property int $id
 * @property int $user_id
 * @property int $amount
 * @property int $term
 * @property string $status
 * @property string $created_at
 */
class Loan extends ActiveRecord
{
    public static function tableName()
    {
        return 'loan';
    }

    public function rules()
    {
        return [
            [['user_id', 'amount', 'term'], 'required'],
            [['user_id', 'amount', 'term'], 'integer'],
            ['status', 'in', 'range' => ['pending', 'approved', 'rejected']],
        ];
    }

    public static function hasApprovedRequest($userId)
    {
        return self::find()
            ->where(['user_id' => $userId, 'status' => 'approved'])
            ->exists();
    }
}
