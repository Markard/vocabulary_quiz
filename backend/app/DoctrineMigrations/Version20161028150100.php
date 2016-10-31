<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161028150100 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $words = [
            ['apple', 'яблоко'],
            ['pear', 'персик'],
            ['orange', 'апельсин'],
            ['grape', 'виноград'],
            ['lemon', 'лимон'],
            ['pineapple', 'ананас'],
            ['watermelon', 'арбуз'],
            ['coconut', 'кокос'],
            ['banana', 'банан'],
            ['pomelo', 'помело'],
            ['strawberry', 'клубника'],
            ['raspberry', 'малина'],
            ['melon', 'дыня'],
            ['apricot', 'абрикос'],
            ['mango', 'манго'],
            ['plum', 'слива'],
            ['pomegranate', 'гранат'],
            ['cherry', 'вишня'],
        ];

        foreach ($words as list($english, $russian)) {
            $this->addWords($english, $russian);
        }
    }

    private function addWords($english, $russian)
    {
        $this->connection->insert('english_words', ['word' => $english]);
        $enWordId = $this->connection->lastInsertId();

        $this->connection->insert('russian_words', ['word' => $russian, 'translation_id' => $enWordId]);
        $ruWordId = $this->connection->lastInsertId();

        $this->connection->update('english_words', ['translation_id' => $ruWordId], ['id' => $enWordId]);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        
    }
}
