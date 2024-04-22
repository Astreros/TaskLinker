<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422091717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employe (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, email VARCHAR(320) NOT NULL, contract_type VARCHAR(255) NOT NULL, joining_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employe_project (employe_id INT NOT NULL, project_id INT NOT NULL, INDEX IDX_EBBCBC4D1B65292 (employe_id), INDEX IDX_EBBCBC4D166D1F9C (project_id), PRIMARY KEY(employe_id, project_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deadline_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_archived TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_status (project_id INT NOT NULL, status_id INT NOT NULL, INDEX IDX_6CA48E56166D1F9C (project_id), INDEX IDX_6CA48E566BF700BD (status_id), PRIMARY KEY(project_id, status_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, status_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, employe_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date DATETIME NOT NULL, INDEX IDX_527EDB251B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task_status (task_id INT NOT NULL, status_id INT NOT NULL, INDEX IDX_40A9E1CF8DB60186 (task_id), INDEX IDX_40A9E1CF6BF700BD (status_id), PRIMARY KEY(task_id, status_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employe_project ADD CONSTRAINT FK_EBBCBC4D1B65292 FOREIGN KEY (employe_id) REFERENCES employe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employe_project ADD CONSTRAINT FK_EBBCBC4D166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_status ADD CONSTRAINT FK_6CA48E56166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_status ADD CONSTRAINT FK_6CA48E566BF700BD FOREIGN KEY (status_id) REFERENCES status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB251B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE task_status ADD CONSTRAINT FK_40A9E1CF8DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_status ADD CONSTRAINT FK_40A9E1CF6BF700BD FOREIGN KEY (status_id) REFERENCES status (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employe_project DROP FOREIGN KEY FK_EBBCBC4D1B65292');
        $this->addSql('ALTER TABLE employe_project DROP FOREIGN KEY FK_EBBCBC4D166D1F9C');
        $this->addSql('ALTER TABLE project_status DROP FOREIGN KEY FK_6CA48E56166D1F9C');
        $this->addSql('ALTER TABLE project_status DROP FOREIGN KEY FK_6CA48E566BF700BD');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB251B65292');
        $this->addSql('ALTER TABLE task_status DROP FOREIGN KEY FK_40A9E1CF8DB60186');
        $this->addSql('ALTER TABLE task_status DROP FOREIGN KEY FK_40A9E1CF6BF700BD');
        $this->addSql('DROP TABLE employe');
        $this->addSql('DROP TABLE employe_project');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_status');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_status');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
