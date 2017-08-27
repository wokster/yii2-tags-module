<?php

namespace wokster\tags\models;

use yii;

/**
 * This is the model class for table "tag".
 *
 * @property integer $id
 * @property string $tag
 */
class Tags extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tags';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Тэг',
        ];
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            TagsRelation::deleteAll(['tag_id'=>$this->id]);
            return true;
        } else {
            return false;
        }
    }
}
