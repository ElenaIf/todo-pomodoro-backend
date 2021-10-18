<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211017191822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__notes AS SELECT id, title, done, time_spent, color, userid, hashtag, hasgtags FROM notes');
        $this->addSql('DROP TABLE notes');
        $this->addSql('CREATE TABLE notes (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, done BOOLEAN NOT NULL, time_spent INTEGER DEFAULT NULL, color VARCHAR(255) DEFAULT NULL COLLATE BINARY, userid VARCHAR(255) NOT NULL COLLATE BINARY, hashtag VARCHAR(255) DEFAULT NULL COLLATE BINARY, hashtags CLOB DEFAULT NULL --(DC2Type:array)
        )');
        $this->addSql('INSERT INTO notes (id, title, done, time_spent, color, userid, hashtag, hashtags) SELECT id, title, done, time_spent, color, userid, hashtag, hasgtags FROM __temp__notes');
        $this->addSql('DROP TABLE __temp__notes');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__notes AS SELECT id, title, done, time_spent, color, userid, hashtag, hashtags FROM notes');
        $this->addSql('DROP TABLE notes');
        $this->addSql('CREATE TABLE notes (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, done BOOLEAN NOT NULL, time_spent INTEGER DEFAULT NULL, color VARCHAR(255) DEFAULT NULL, userid VARCHAR(255) NOT NULL, hashtag VARCHAR(255) DEFAULT NULL, hasgtags CLOB DEFAULT NULL COLLATE BINARY --(DC2Type:array)
        )');
        $this->addSql('INSERT INTO notes (id, title, done, time_spent, color, userid, hashtag, hasgtags) SELECT id, title, done, time_spent, color, userid, hashtag, hashtags FROM __temp__notes');
        $this->addSql('DROP TABLE __temp__notes');
    }
}
