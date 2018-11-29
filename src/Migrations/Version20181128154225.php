<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181128154225 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FB9C24126');
        $this->addSql('CREATE TABLE days_of_week (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE day_of_week');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FA40BC2D5');
        $this->addSql('DROP INDEX IDX_4FBF094FA40BC2D5 ON company');
        $this->addSql('ALTER TABLE company DROP schedule_id');
        $this->addSql('DROP INDEX IDX_5A3811FB9C24126 ON schedule');
        $this->addSql('ALTER TABLE schedule CHANGE opening_am opening_am INT DEFAULT NULL, CHANGE closing_am closing_am INT DEFAULT NULL, CHANGE opening_pm opening_pm INT DEFAULT NULL, CHANGE closing_pm closing_pm INT DEFAULT NULL, CHANGE day_id company_id INT NOT NULL');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_5A3811FB979B1AD6 ON schedule (company_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE day_of_week (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL COLLATE utf8mb4_unicode_ci, number_of_day INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE days_of_week');
        $this->addSql('ALTER TABLE company ADD schedule_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FA40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id)');
        $this->addSql('CREATE INDEX IDX_4FBF094FA40BC2D5 ON company (schedule_id)');
        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FB979B1AD6');
        $this->addSql('DROP INDEX IDX_5A3811FB979B1AD6 ON schedule');
        $this->addSql('ALTER TABLE schedule CHANGE opening_am opening_am TIME DEFAULT NULL, CHANGE closing_am closing_am TIME DEFAULT NULL, CHANGE opening_pm opening_pm TIME DEFAULT NULL, CHANGE closing_pm closing_pm TIME DEFAULT NULL, CHANGE company_id day_id INT NOT NULL');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB9C24126 FOREIGN KEY (day_id) REFERENCES day_of_week (id)');
        $this->addSql('CREATE INDEX IDX_5A3811FB9C24126 ON schedule (day_id)');
    }
}
