<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "edge".
 *
 * @property int $id
 * @property int $id_first_node
 * @property int $id_second_node
 * @property int $weight
 */
class Edge extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'edge';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_first_node', 'id_second_node', 'weight'], 'required'],
            [['id_first_node', 'id_second_node', 'weight'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_first_node' => 'Id First Node',
            'id_second_node' => 'Id Second Node',
            'weight' => 'Weight',
        ];
    }
}
