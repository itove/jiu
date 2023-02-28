<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215132603 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bottle DROP FOREIGN KEY FK_ACA9A955D8177B3F');
        $this->addSql('DROP INDEX IDX_ACA9A955D8177B3F ON bottle');
        $this->addSql('ALTER TABLE bottle DROP box_id');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEED8177B3F');
        $this->addSql('DROP INDEX IDX_E52FFDEED8177B3F ON orders');
        $this->addSql('ALTER TABLE orders DROP box_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bottle ADD box_id INT NOT NULL');
        $this->addSql('ALTER TABLE bottle ADD CONSTRAINT FK_ACA9A955D8177B3F FOREIGN KEY (box_id) REFERENCES box (id)');
        $this->addSql('CREATE INDEX IDX_ACA9A955D8177B3F ON bottle (box_id)');
        $this->addSql('ALTER TABLE orders ADD box_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEED8177B3F FOREIGN KEY (box_id) REFERENCES box (id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEED8177B3F ON orders (box_id)');
    }
}
