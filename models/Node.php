<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "node".
 *
 * @property int $id
 * @property int $id_heap
 * @property string $name
 */
class Node extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'node';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_heap', 'name'], 'required'],
            [['id_heap'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_heap' => 'Id Heap',
            'name' => 'Name',
        ];
    }
}
