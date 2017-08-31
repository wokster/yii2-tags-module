<?php
/**
 * Created by internetsite.com.ua
 * User: Tymofeiev Maksym
 * Date: 10.08.2016
 * Time: 19:22
 */

namespace wokster\tags;


use wokster\tags\models\Tags;
use wokster\tags\models\TagsRelation;
use yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class TagsBehavior extends Behavior
{
  public $new_tags = [];

  public function attach($owner)
  {
    parent::attach($owner);
    $validator = \yii\validators\Validator::createValidator('safe', $owner, 'new_tags');
    $owner->validators[] = $validator;
  }

  public function events()
  {
    return [
        ActiveRecord::EVENT_BEFORE_DELETE => 'deleteTags',
        ActiveRecord::EVENT_AFTER_FIND => 'tagsToArray',
        ActiveRecord::EVENT_AFTER_INSERT => 'saveTag',
        ActiveRecord::EVENT_AFTER_UPDATE => 'saveTag',
    ];
  }

  public function deleteTags()
  {
    TagsRelation::deleteAll(['and',['related_class'=>$this->owner->className()],['related_item'=>$this->owner->id]]);
  }

  public function getTagRalation(){
    return $this->owner->hasMany(TagsRelation::className(),['related_item'=>'id'])->andWhere(['related_class'=>$this->owner->className()]);
  }

  public function getTags(){
    return $this->owner->hasMany(Tags::className(),['id'=>'tag_id'])->via('tagRalation');
  }

  public function tagsToArray()
  {
    $this->new_tags = \yii\helpers\ArrayHelper::map($this->getTags()->all(),'name','name');
  }

  public function saveTag()
  {
    if(is_array($this->new_tags)){
      $old_tags = ArrayHelper::map($this->owner->tags,'name','id');
      foreach ($this->new_tags as $one_new_tag){
        if(isset($old_tags[$one_new_tag])){
          unset($old_tags[$one_new_tag]);
        }else{
          if($tg = $this->createNewTag($one_new_tag)){
            Yii::$app->session->addFlash('succsess','добавлен тег ' . $one_new_tag);
          }else{
            Yii::$app->session->addFlash('error','тег ' . $one_new_tag . ' не добавился');
          }
        }
      }
      TagsRelation::deleteAll(['and',['and',['related_item'=>$this->owner->id],['related_class'=>$this->owner->className()]],['tag_id'=>$old_tags]]);
    }else{
      TagsRelation::deleteAll(['and',['related_item'=>$this->owner->id],['related_class'=>$this->owner->className()]]);
    }
  }
  private function createNewTag($new_tag){
    if(!$tag = Tags::find()->andWhere(['name'=>$new_tag])->one()){
      $tag = new Tags();
      $tag->name = $new_tag;
      if(!$tag->save()){
        $tag = null;
      }
    }
    if($tag instanceof Tags){
      $rel_tag = new TagsRelation();
      $rel_tag->related_class = $this->owner->className();
      $rel_tag->related_item = $this->owner->id;
      $rel_tag->tag_id = $tag->id;
      if($rel_tag->save())
        return $rel_tag->id;
    }
    return false;
  }
}
