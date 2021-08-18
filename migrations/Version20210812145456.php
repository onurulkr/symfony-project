<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210812145456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answer ADD options_id INT NOT NULL');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A253ADB05F1 FOREIGN KEY (options_id) REFERENCES `option` (id)');
        $this->addSql('CREATE INDEX IDX_DADD4A253ADB05F1 ON answer (options_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A253ADB05F1');
        $this->addSql('DROP INDEX IDX_DADD4A253ADB05F1 ON answer');
        $this->addSql('ALTER TABLE answer DROP options_id');
    }
}
