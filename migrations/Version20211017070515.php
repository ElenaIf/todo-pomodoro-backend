<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211017070515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notes ADD COLUMN userid VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE notes ADD COLUMN hashtag VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__notes AS SELECT id, title, done, time_spent, color FROM notes');
        $this->addSql('DROP TABLE notes');
        $this->addSql('CREATE TABLE notes (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, done BOOLEAN NOT NULL, time_spent INTEGER DEFAULT NULL, color VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO notes (id, title, done, time_spent, color) SELECT id, title, done, time_spent, color FROM __temp__notes');
        $this->addSql('DROP TABLE __temp__notes');
    }
}
