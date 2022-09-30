<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220930110936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', garage_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', image VARCHAR(255) DEFAULT NULL, ownership_status VARCHAR(1) NOT NULL, brand VARCHAR(50) NOT NULL, model VARCHAR(50) NOT NULL, year INT NOT NULL, trim VARCHAR(50) DEFAULT NULL, modifications LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', horse_power INT NOT NULL, torque INT NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_773DE69DC4FFF555 (garage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE club (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', slug VARCHAR(128) NOT NULL, name VARCHAR(255) NOT NULL, banner_image VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, location VARCHAR(100) NOT NULL, categories LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_B8EE3872989D9B62 (slug), INDEX IDX_B8EE38727E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE club_members (club_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_48E8777D61190A32 (club_id), INDEX IDX_48E8777DA76ED395 (user_id), PRIMARY KEY(club_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE garage (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_9F26610BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', club_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, media_path VARCHAR(255) NOT NULL, likes LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', comments LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_5A8A6C8D989D9B62 (slug), INDEX IDX_5A8A6C8DA76ED395 (user_id), INDEX IDX_5A8A6C8D61190A32 (club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE token_request (id INT AUTO_INCREMENT NOT NULL, user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D598961CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', username VARCHAR(50) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, active TINYINT(1) DEFAULT 0 NOT NULL, activated_at DATETIME DEFAULT NULL, profile LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DC4FFF555 FOREIGN KEY (garage_id) REFERENCES garage (id)');
        $this->addSql('ALTER TABLE club ADD CONSTRAINT FK_B8EE38727E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE club_members ADD CONSTRAINT FK_48E8777D61190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE club_members ADD CONSTRAINT FK_48E8777DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE garage ADD CONSTRAINT FK_9F26610BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D61190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE token_request ADD CONSTRAINT FK_D598961CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE club_members DROP FOREIGN KEY FK_48E8777D61190A32');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D61190A32');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DC4FFF555');
        $this->addSql('ALTER TABLE club DROP FOREIGN KEY FK_B8EE38727E3C61F9');
        $this->addSql('ALTER TABLE club_members DROP FOREIGN KEY FK_48E8777DA76ED395');
        $this->addSql('ALTER TABLE garage DROP FOREIGN KEY FK_9F26610BA76ED395');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE token_request DROP FOREIGN KEY FK_D598961CA76ED395');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE club');
        $this->addSql('DROP TABLE club_members');
        $this->addSql('DROP TABLE garage');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE token_request');
        $this->addSql('DROP TABLE user');
    }
}
