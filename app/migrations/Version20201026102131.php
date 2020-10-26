<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201026102131 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_taxonomy (product_id INT NOT NULL, taxonomy_id INT NOT NULL, INDEX IDX_36A2D2AA4584665A (product_id), INDEX IDX_36A2D2AA9557E6F6 (taxonomy_id), PRIMARY KEY(product_id, taxonomy_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taxonomy (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_taxonomy ADD CONSTRAINT FK_36A2D2AA4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_taxonomy ADD CONSTRAINT FK_36A2D2AA9557E6F6 FOREIGN KEY (taxonomy_id) REFERENCES taxonomy (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product ADD cost DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_taxonomy DROP FOREIGN KEY FK_36A2D2AA9557E6F6');
        $this->addSql('DROP TABLE product_taxonomy');
        $this->addSql('DROP TABLE taxonomy');
        $this->addSql('ALTER TABLE product DROP cost');
    }
}
