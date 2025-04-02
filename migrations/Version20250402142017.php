<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402142017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE chapter (id INT AUTO_INCREMENT NOT NULL, subject_id INT NOT NULL, name VARCHAR(255) NOT NULL, display_name VARCHAR(255) NOT NULL, INDEX IDX_F981B52E23EDC87 (subject_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE course_element (id INT AUTO_INCREMENT NOT NULL, element_type_id INT NOT NULL, chapter_id INT NOT NULL, number INT NOT NULL, statement VARCHAR(10000) NOT NULL, details JSON NOT NULL, proofs JSON NOT NULL, INDEX IDX_49835BD532A7CCC7 (element_type_id), INDEX IDX_49835BD5579F4768 (chapter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE course_element_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, display_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4E2A0E2D5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE exercise (id INT AUTO_INCREMENT NOT NULL, exercise_type_id INT DEFAULT NULL, chapter_id INT NOT NULL, statement VARCHAR(10000) NOT NULL, hints JSON NOT NULL, solutions JSON NOT NULL, INDEX IDX_AEDAD51C1F597BD6 (exercise_type_id), INDEX IDX_AEDAD51C579F4768 (chapter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE exercise_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, display_name VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D5FB359B5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE subject (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, display_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapter ADD CONSTRAINT FK_F981B52E23EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE course_element ADD CONSTRAINT FK_49835BD532A7CCC7 FOREIGN KEY (element_type_id) REFERENCES course_element_type (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE course_element ADD CONSTRAINT FK_49835BD5579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exercise ADD CONSTRAINT FK_AEDAD51C1F597BD6 FOREIGN KEY (exercise_type_id) REFERENCES exercise_type (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exercise ADD CONSTRAINT FK_AEDAD51C579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52E23EDC87
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE course_element DROP FOREIGN KEY FK_49835BD532A7CCC7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE course_element DROP FOREIGN KEY FK_49835BD5579F4768
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exercise DROP FOREIGN KEY FK_AEDAD51C1F597BD6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exercise DROP FOREIGN KEY FK_AEDAD51C579F4768
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE chapter
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE course_element
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE course_element_type
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE exercise
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE exercise_type
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE subject
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `user`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
