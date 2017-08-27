<?php

use yii\db\Migration;

class m170504_124751_tags extends Migration
{
    public function safeUp()
    {
        $this->createTable('tags',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(50)->unique()->notNull(),
            ]
        );
        $this->createTable('tags_relation',
            [
                'id' => $this->primaryKey(),
                'tag_id' => $this->integer()->notNull(),
                'related_class' => $this->string(50),
                'related_item' => $this->integer(),
            ]
        );
        $this->createIndex(
            'tags_relation_tag_id',
            'tags_relation',
            'tag_id'
        );
        $this->createIndex(
        'tags_relation_related_class',
        'tags_relation',
        'related_class'
    );
    }

    public function safeDown()
    {
        $this->dropTable('tags_relation');
        $this->dropTable('tags');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170504_124749_seo cannot be reverted.\n";

        return false;
    }
    */
}
