<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210430133622 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE interaction (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, type VARCHAR(255) NOT NULL, body LONGTEXT DEFAULT NULL, user_id INT NOT NULL, INDEX IDX_378DFDA74B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id INT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, datetime_posted DATETIME NOT NULL, post_content LONGTEXT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, reason_id INT NOT NULL, post_id INT DEFAULT NULL, comment_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_C42F778459BB1592 (reason_id), INDEX IDX_C42F77844B89032C (post_id), INDEX IDX_C42F7784F8697D13 (comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report_reason (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, parentId INT DEFAULT NULL, INDEX IDX_124B5D9310EE4CEE (parentId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'Report reasons have a maximum depth of 4 levels, i.e. Parent > Child > Child2 > Child3\' ');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_influence (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, influence INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_pyp_post (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_9AA0D94B4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE interaction ADD CONSTRAINT FK_378DFDA74B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F778459BB1592 FOREIGN KEY (reason_id) REFERENCES report_reason (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F77844B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784F8697D13 FOREIGN KEY (comment_id) REFERENCES interaction (id)');
        $this->addSql('ALTER TABLE report_reason ADD CONSTRAINT FK_124B5D9310EE4CEE FOREIGN KEY (parentId) REFERENCES report_reason (id)');
        $this->addSql('ALTER TABLE user_pyp_post ADD CONSTRAINT FK_9AA0D94B4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784F8697D13');
        $this->addSql('ALTER TABLE interaction DROP FOREIGN KEY FK_378DFDA74B89032C');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F77844B89032C');
        $this->addSql('ALTER TABLE user_pyp_post DROP FOREIGN KEY FK_9AA0D94B4B89032C');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F778459BB1592');
        $this->addSql('ALTER TABLE report_reason DROP FOREIGN KEY FK_124B5D9310EE4CEE');
        $this->addSql('DROP TABLE interaction');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE report_reason');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_influence');
        $this->addSql('DROP TABLE user_pyp_post');
    }
}
