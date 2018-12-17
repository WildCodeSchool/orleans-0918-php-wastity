<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181217094118 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE status (id VARCHAR(255) NOT NULL, class_color_name VARCHAR(255) NOT NULL, status_text VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offer ADD status_key_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EB05EFD7E FOREIGN KEY (status_key_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_29D6873EB05EFD7E ON offer (status_key_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EB05EFD7E');
        $this->addSql('ALTER TABLE offer CHANGE status_key_id status_key_id VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EB05EFD7E FOREIGN KEY (status_key_id) REFERENCES status (const_key)');
        $this->addSql('ALTER TABLE status MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_7B00651C36FE784F ON status');
        $this->addSql('ALTER TABLE status DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE status CHANGE id id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE status ADD PRIMARY KEY (const_key)');
    }
}
