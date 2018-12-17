<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181217084602 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE status (const_key VARCHAR(255) NOT NULL, class_color_name VARCHAR(255) NOT NULL, status_text VARCHAR(255) NOT NULL,PRIMARY KEY(const_key)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offer ADD status_key_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EB05EFD7E FOREIGN KEY (status_key_id) REFERENCES status (const_key)');
        $this->addSql('CREATE INDEX IDX_29D6873EB05EFD7E ON offer (status_key_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EB05EFD7E');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP INDEX IDX_29D6873EB05EFD7E ON offer');
        $this->addSql('ALTER TABLE offer DROP status_key_id');
    }
}
