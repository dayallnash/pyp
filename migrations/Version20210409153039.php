<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210409153039 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE report_reason ADD parentId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE report_reason ADD CONSTRAINT FK_124B5D9310EE4CEE FOREIGN KEY (parentId) REFERENCES report_reason (id)');
        $this->addSql('CREATE INDEX IDX_124B5D9310EE4CEE ON report_reason (parentId)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE report_reason DROP FOREIGN KEY FK_124B5D9310EE4CEE');
        $this->addSql('DROP INDEX IDX_124B5D9310EE4CEE ON report_reason');
        $this->addSql('ALTER TABLE report_reason DROP parentId');
    }
}
