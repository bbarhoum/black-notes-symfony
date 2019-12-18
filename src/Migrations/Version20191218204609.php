<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191218204609 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE todo ADD owner_id INT NOT NULL, ADD is_done TINYINT(1) DEFAULT NULL, CHANGE due_date due_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE todo ADD CONSTRAINT FK_5A0EB6A07E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5A0EB6A07E3C61F9 ON todo (owner_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE birth_date birth_date DATE DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE todo DROP FOREIGN KEY FK_5A0EB6A07E3C61F9');
        $this->addSql('DROP INDEX IDX_5A0EB6A07E3C61F9 ON todo');
        $this->addSql('ALTER TABLE todo DROP owner_id, DROP is_done, CHANGE due_date due_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE birth_date birth_date DATE DEFAULT \'NULL\'');
    }
}
