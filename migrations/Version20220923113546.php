<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220923113546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, garage_id INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, ownership_status VARCHAR(1) NOT NULL, brand VARCHAR(50) NOT NULL, model VARCHAR(50) NOT NULL, year INT NOT NULL, trim VARCHAR(50) DEFAULT NULL, modifications LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', horse_power INT NOT NULL, torque INT NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_773DE69DC4FFF555 (garage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DC4FFF555 FOREIGN KEY (garage_id) REFERENCES garage (id)');
        $this->addSql('ALTER TABLE garage DROP cars');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE car');
        $this->addSql('ALTER TABLE garage ADD cars LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }
}
