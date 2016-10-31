<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161026182213 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.');

        $this->createUsersTable();
        $this->createQuizzesTable();
        $this->createQuizQuestionsTable();
        $this->createRussianWordsTable();
        $this->createEnglishWordsTable();
        $this->createWrongAnswersTable();
    }

    private function createUsersTable()
    {
        $sql = <<<SQL
CREATE TABLE `users` (
  `id` INT UNSIGNED AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),

  UNIQUE INDEX `udx_users_1` (`username`)
)
  DEFAULT CHARACTER SET utf8
  COLLATE utf8_general_ci
  ENGINE = InnoDB;
SQL;
        $this->addSql($sql);
    }

    private function createQuizzesTable()
    {
        $sql = <<<SQL
CREATE TABLE `quizzes` (
  `id` INT UNSIGNED AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `score` TINYINT NOT NULL DEFAULT 0,
  `current_fails_number` TINYINT NOT NULL DEFAULT 0,
  `fails_number` TINYINT NOT NULL,
  `current_question_number` TINYINT NOT NULL DEFAULT 0,
  `questions_number` TINYINT NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),

  CONSTRAINT `fk_quizzes_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
)
  ENGINE = InnoDB;
SQL;
        $this->addSql($sql);
    }

    private function createQuizQuestionsTable()
    {
        $sql = <<<SQL
CREATE TABLE `quiz_questions` (
  `id` INT UNSIGNED AUTO_INCREMENT,
  `quiz_id` INT UNSIGNED NOT NULL,
  `is_answered` BOOL NOT NULL DEFAULT FALSE,
  `type` ENUM ('ru', 'en') NOT NULL,
  `question_word_id` TINYINT NOT NULL DEFAULT 0,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),

  CONSTRAINT `fk_quiz_questions_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`)
)
  ENGINE = InnoDB;
SQL;
        $this->addSql($sql);
    }

    private function createRussianWordsTable()
    {
        $sql = <<<SQL
CREATE TABLE `russian_words` (
  `id` INT UNSIGNED AUTO_INCREMENT NOT NULL,
  `word` VARCHAR(255) NOT NULL,
  `translation_id` INT UNSIGNED, 
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),
  
  UNIQUE INDEX `udx_russian_words_1` (`word`)
)
  DEFAULT CHARACTER SET utf8
  COLLATE utf8_general_ci
  ENGINE = InnoDB;
SQL;
        $this->addSql($sql);
    }

    private function createEnglishWordsTable()
    {
        $sql = <<<SQL
CREATE TABLE `english_words` (
  `id` INT UNSIGNED AUTO_INCREMENT NOT NULL,
  `word` VARCHAR(255) NOT NULL,
  `translation_id` INT UNSIGNED, 
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),
  
  UNIQUE INDEX `udx_english_words_1` (`word`),
  CONSTRAINT `fk_english_words_1` FOREIGN KEY (`translation_id`) REFERENCES `russian_words` (`id`)
)
  DEFAULT CHARACTER SET latin1
  COLLATE latin1_general_ci
  ENGINE = InnoDB;
SQL;
        $this->addSql($sql);

        $sql = <<<SQL
ALTER TABLE `russian_words` 
  ADD CONSTRAINT `fk_russian_words_1` FOREIGN KEY (`translation_id`) REFERENCES `english_words` (`id`)
SQL;
        $this->addSql($sql);
    }

    private function createWrongAnswersTable()
    {
        $sql = <<<SQL
CREATE TABLE `wrong_answers` (
  `id` INT UNSIGNED AUTO_INCREMENT,
  `question_id` INT UNSIGNED NOT NULL,
  `english_word_id` INT UNSIGNED NOT NULL,
  `russian_word_id` INT UNSIGNED NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),

  CONSTRAINT `fk_wrong_answers_1` FOREIGN KEY (`question_id`) REFERENCES `quiz_questions` (`id`),
  CONSTRAINT `fk_wrong_answers_2` FOREIGN KEY (`english_word_id`) REFERENCES `english_words` (`id`),
  CONSTRAINT `fk_wrong_answers_3` FOREIGN KEY (`russian_word_id`) REFERENCES `russian_words` (`id`)
)
  ENGINE = InnoDB;
SQL;
        $this->addSql($sql);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE `wrong_answers`');
        $this->addSql('ALTER TABLE `english_words` DROP FOREIGN KEY `fk_english_words_1`');

        $this->addSql('DROP TABLE `russian_words`');
        $this->addSql('DROP TABLE `english_words`');

        $this->addSql('DROP TABLE `quiz_questions`');
        $this->addSql('DROP TABLE `quizzes`');
        $this->addSql('DROP TABLE `users`');
    }
}
