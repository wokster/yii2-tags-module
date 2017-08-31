<?php

namespace wokster\tags\controllers;

use yii;
use yii\web\Controller;
use wokster\seomodule\models\Seo;
use wokster\seomodule\models\SeoSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Default controller for the `tags` module
 */
class DefaultController extends Controller
{
  /**
   * Return list of tags
   * @param $q //search query
   *
   * @return string
   * @throws \yii\web\MethodNotAllowedHttpException
   */
  public function actionTagsByQuery($q){
    if(Yii::$app->request->isAjax){
      \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      $out = ['results' => ['id' => '', 'text' => '']];
      if (!is_null($q)) {
        $query = new Query();
        $query->select('id, name AS text')
            ->from('tags')
            ->andWhere(['like','name',$q])
            ->limit(50);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out['results'] = array_values($data);
      }
      return $out;
    }
    throw new yii\web\MethodNotAllowedHttpException();
  }
}
