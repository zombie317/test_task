<?php
namespace app\models;
use Yii;
use yii\base\Model;

class AjaxForm extends Model
{
    public $id_first_node;
    public $id_second_node;
    public $weight;

    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['id_first_node', 'id_second_node', 'weight'], 'required'],
            [['id_first_node', 'id_second_node', 'weight'], 'integer'],

        ];
    }
    public function attributeLabels()
    {
        return [
            'id_first_node' => 'Первая точка',
            'id_second_node' => 'Вторая точка',
            'weight' => 'Вес',
        ];
    }

}