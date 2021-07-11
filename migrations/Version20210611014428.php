<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210611014428 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE countries (id INT UNSIGNED AUTO_INCREMENT NOT NULL, label VARCHAR(3) NOT NULL, name VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_5D66EBADEA750E8 (label), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genres (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE radio_channels (id INT UNSIGNED AUTO_INCREMENT NOT NULL, country_id INT UNSIGNED DEFAULT NULL, genre_id INT UNSIGNED DEFAULT NULL, name VARCHAR(50) NOT NULL, url VARCHAR(2000) NOT NULL, img VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_12231EBBF92F3E70 (country_id), INDEX IDX_12231EBB4296D31F (genre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT UNSIGNED AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', is_admin TINYINT(1) DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE radio_channels ADD CONSTRAINT FK_12231EBBF92F3E70 FOREIGN KEY (country_id) REFERENCES countries (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE radio_channels ADD CONSTRAINT FK_12231EBB4296D31F FOREIGN KEY (genre_id) REFERENCES genres (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE radio_channels DROP FOREIGN KEY FK_12231EBBF92F3E70');
        $this->addSql('ALTER TABLE radio_channels DROP FOREIGN KEY FK_12231EBB4296D31F');
        $this->addSql('DROP TABLE countries');
        $this->addSql('DROP TABLE genres');
        $this->addSql('DROP TABLE radio_channels');
        $this->addSql('DROP TABLE users');
    }
}
