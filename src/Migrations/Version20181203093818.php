<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181203093818 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649979B1AD6 ON user (company_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E979B1AD6');
        $this->addSql('DROP INDEX IDX_29D6873E979B1AD6 ON offer');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649979B1AD6');
        $this->addSql('DROP INDEX IDX_8D93D649979B1AD6 ON user');
        $this->addSql('ALTER TABLE user DROP company_id');
    }
}
