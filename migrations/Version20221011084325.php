<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221011084325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', garage_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', image VARCHAR(255) DEFAULT NULL, ownership_status VARCHAR(1) NOT NULL, brand VARCHAR(50) NOT NULL, model VARCHAR(50) NOT NULL, year INT NOT NULL, trim VARCHAR(50) DEFAULT NULL, horse_power INT NOT NULL, torque INT NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_773DE69DC4FFF555 (garage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car_categories (car_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', category_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_82CC879C3C6F69F (car_id), INDEX IDX_82CC87912469DE2 (category_id), PRIMARY KEY(car_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(10) NOT NULL, slug VARCHAR(10) NOT NULL, UNIQUE INDEX UNIQ_64C19C15E237E06 (name), UNIQUE INDEX UNIQ_64C19C1989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_club (category_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', club_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_3375423512469DE2 (category_id), INDEX IDX_3375423561190A32 (club_id), PRIMARY KEY(category_id, club_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_car (category_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', car_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_52FF4BC712469DE2 (category_id), INDEX IDX_52FF4BC7C3C6F69F (car_id), PRIMARY KEY(category_id, car_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE club (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, banner_image VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, location VARCHAR(100) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_B8EE3872989D9B62 (slug), INDEX IDX_B8EE38727E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE club_members (club_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_48E8777D61190A32 (club_id), INDEX IDX_48E8777DA76ED395 (user_id), PRIMARY KEY(club_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE club_categories (club_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', category_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_7335088561190A32 (club_id), INDEX IDX_7335088512469DE2 (category_id), PRIMARY KEY(club_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', author_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', post_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', message TINYTEXT NOT NULL, posted_at DATETIME NOT NULL, likes_count INT NOT NULL, INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526C4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment_user (comment_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_ABA574A5F8697D13 (comment_id), INDEX IDX_ABA574A5A76ED395 (user_id), PRIMARY KEY(comment_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE garage (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_9F26610BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modifications (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', car_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', manufacturer_name VARCHAR(150) NOT NULL, name VARCHAR(150) NOT NULL, slug VARCHAR(255) NOT NULL, horse_power_gain INT DEFAULT NULL, torque_gain INT DEFAULT NULL, weight_gain INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, cost INT DEFAULT NULL, labor_cost INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_733C18D6989D9B62 (slug), INDEX IDX_733C18D6C3C6F69F (car_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', club_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, media_path VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, likes_count INT NOT NULL, UNIQUE INDEX UNIQ_5A8A6C8D989D9B62 (slug), INDEX IDX_5A8A6C8DA76ED395 (user_id), INDEX IDX_5A8A6C8D61190A32 (club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_user (post_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_44C6B1424B89032C (post_id), INDEX IDX_44C6B142A76ED395 (user_id), PRIMARY KEY(post_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE token_request (id INT AUTO_INCREMENT NOT NULL, user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D598961CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', username VARCHAR(50) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, active TINYINT(1) DEFAULT 0 NOT NULL, activated_at DATETIME DEFAULT NULL, profile LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_club (user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', club_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_C26F74BBA76ED395 (user_id), INDEX IDX_C26F74BB61190A32 (club_id), PRIMARY KEY(user_id, club_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DC4FFF555 FOREIGN KEY (garage_id) REFERENCES garage (id)');
        $this->addSql('ALTER TABLE car_categories ADD CONSTRAINT FK_82CC879C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE car_categories ADD CONSTRAINT FK_82CC87912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_club ADD CONSTRAINT FK_3375423512469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_club ADD CONSTRAINT FK_3375423561190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_car ADD CONSTRAINT FK_52FF4BC712469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_car ADD CONSTRAINT FK_52FF4BC7C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE club ADD CONSTRAINT FK_B8EE38727E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE club_members ADD CONSTRAINT FK_48E8777D61190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE club_members ADD CONSTRAINT FK_48E8777DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE club_categories ADD CONSTRAINT FK_7335088561190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE club_categories ADD CONSTRAINT FK_7335088512469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE comment_user ADD CONSTRAINT FK_ABA574A5F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment_user ADD CONSTRAINT FK_ABA574A5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE garage ADD CONSTRAINT FK_9F26610BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE modifications ADD CONSTRAINT FK_733C18D6C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D61190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE post_user ADD CONSTRAINT FK_44C6B1424B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_user ADD CONSTRAINT FK_44C6B142A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE token_request ADD CONSTRAINT FK_D598961CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_club ADD CONSTRAINT FK_C26F74BBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_club ADD CONSTRAINT FK_C26F74BB61190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car_categories DROP FOREIGN KEY FK_82CC879C3C6F69F');
        $this->addSql('ALTER TABLE category_car DROP FOREIGN KEY FK_52FF4BC7C3C6F69F');
        $this->addSql('ALTER TABLE modifications DROP FOREIGN KEY FK_733C18D6C3C6F69F');
        $this->addSql('ALTER TABLE car_categories DROP FOREIGN KEY FK_82CC87912469DE2');
        $this->addSql('ALTER TABLE category_club DROP FOREIGN KEY FK_3375423512469DE2');
        $this->addSql('ALTER TABLE category_car DROP FOREIGN KEY FK_52FF4BC712469DE2');
        $this->addSql('ALTER TABLE club_categories DROP FOREIGN KEY FK_7335088512469DE2');
        $this->addSql('ALTER TABLE category_club DROP FOREIGN KEY FK_3375423561190A32');
        $this->addSql('ALTER TABLE club_members DROP FOREIGN KEY FK_48E8777D61190A32');
        $this->addSql('ALTER TABLE club_categories DROP FOREIGN KEY FK_7335088561190A32');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D61190A32');
        $this->addSql('ALTER TABLE user_club DROP FOREIGN KEY FK_C26F74BB61190A32');
        $this->addSql('ALTER TABLE comment_user DROP FOREIGN KEY FK_ABA574A5F8697D13');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DC4FFF555');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE post_user DROP FOREIGN KEY FK_44C6B1424B89032C');
        $this->addSql('ALTER TABLE club DROP FOREIGN KEY FK_B8EE38727E3C61F9');
        $this->addSql('ALTER TABLE club_members DROP FOREIGN KEY FK_48E8777DA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE comment_user DROP FOREIGN KEY FK_ABA574A5A76ED395');
        $this->addSql('ALTER TABLE garage DROP FOREIGN KEY FK_9F26610BA76ED395');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE post_user DROP FOREIGN KEY FK_44C6B142A76ED395');
        $this->addSql('ALTER TABLE token_request DROP FOREIGN KEY FK_D598961CA76ED395');
        $this->addSql('ALTER TABLE user_club DROP FOREIGN KEY FK_C26F74BBA76ED395');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE car_categories');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_club');
        $this->addSql('DROP TABLE category_car');
        $this->addSql('DROP TABLE club');
        $this->addSql('DROP TABLE club_members');
        $this->addSql('DROP TABLE club_categories');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE comment_user');
        $this->addSql('DROP TABLE garage');
        $this->addSql('DROP TABLE modifications');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_user');
        $this->addSql('DROP TABLE token_request');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_club');
    }
}
