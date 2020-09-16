<?php
namespace app\controllers;

use app\models\Edge;
use yii\web\Controller;

class SearchController extends Controller
{
    public function actionView($id1,$id2)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id1 = \Yii::$app->request->get("id1");//откуда
        $id2 = \Yii::$app->request->get("id2");//куда

        $query1 = (new \yii\db\Query())
            ->select("id_first_node AS node")
            ->from('edge');

        $query2 = (new \yii\db\Query())
            ->select('id_second_node')
            ->from('edge');

        $combinedQuery = $query1->union($query2);//все точки

        //матрица
        foreach($combinedQuery->each() as $item){
            $i= $item['node'];
            foreach($combinedQuery->each() as $item){
                $j= $item['node'];
                $weight=Edge::find()->select(['weight'])->where(['id_first_node' => $i, 'id_second_node' => $j])->one();
                    $p3[$i][$j] = $weight['weight'];
            }
        }

        //меняем null на большое число
        $p4=$p3;
        $inf=100000;
        foreach($p4 as $i => $iv){
            foreach($p4 as $j => $jv){
                if ($p4[$i][$j] == null)
                    $p4[$i][$j] = $inf;
            }
        }

        //FloydWarshall
        foreach($p4 as $k => $kv){
            foreach($p4 as $i => $iv){
                foreach($p4 as $j => $jv){
                    if ($p4[$i][$k] + $p4[$k][$j] < $p4[$i][$j])
                        $p4[$i][$j] = $p4[$i][$k] + $p4[$k][$j];
                }
            }
        }

        //удаляем из массива недоступные пути
        foreach($p4 as $i => $iv){
            foreach($p4 as $j => $jv){
                if ($p4[$i][$j] == $inf)
                    unset($p4[$i][$j]);
            }
        }

        $min=$p4[$id1][$id2];
        return ($min);
    }
    public function actionEdge($id1,$id2)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id1 = \Yii::$app->request->get("id1");//откуда
        $id2 = \Yii::$app->request->get("id2");//куда

        $query = (new \yii\db\Query())
            ->from('edge')->where(['id_first_node' => $id1, 'id_second_node' => $id2])->one();

        return ($query);
    }

}
?>