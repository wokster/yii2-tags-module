<?php

namespace wokster\tags\models;

use Yii;

/**
 * This is the model class for table "tags".
 *
 * @property integer $id
 * @property integer $tag_id
 * @property string $related_class
 * @property integer $related_item
 */
class TagsRelation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tags_relation';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_id'], 'required'],
            [['related_item', 'tag_id'], 'integer'],
            [['related_class'], 'string', 'max'=>50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'related_class' => 'связанная модель',
            'tag_id' => 'Tag ID',
            'related_item' => 'related_item',
        ];
    }

    public function getTags(){
        return $this->hasOne(Tags::className(),['id'=>'tag_id']);
    }
}
