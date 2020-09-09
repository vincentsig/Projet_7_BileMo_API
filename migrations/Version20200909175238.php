<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200909175238 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE specification (id INT AUTO_INCREMENT NOT NULL, os VARCHAR(255) NOT NULL, storage VARCHAR(255) NOT NULL, sim VARCHAR(255) NOT NULL, network VARCHAR(255) NOT NULL, wifi VARCHAR(255) NOT NULL, rear_camera_resolution VARCHAR(255) NOT NULL, front_camera_resolution VARCHAR(255) NOT NULL, display_resolution VARCHAR(255) NOT NULL, dimensions VARCHAR(255) NOT NULL, weight VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phone (id INT AUTO_INCREMENT NOT NULL, specifications_id INT NOT NULL, name VARCHAR(255) NOT NULL, brand VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, stock INT NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_444F97DDBDE4EC11 (specifications_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE phone ADD CONSTRAINT FK_444F97DDBDE4EC11 FOREIGN KEY (specifications_id) REFERENCES specification (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE phone DROP FOREIGN KEY FK_444F97DDBDE4EC11');
        $this->addSql('DROP TABLE phone');
        $this->addSql('DROP TABLE specification');
    }
}
