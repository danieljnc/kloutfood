<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221103233638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE kf_category (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE kf_product (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', category_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', description VARCHAR(500) DEFAULT \'NULL\', stock INT NOT NULL, image VARCHAR(255) DEFAULT NULL, leftover INT DEFAULT NULL, measurement_unit VARCHAR(3) NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_27D4556612469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE kf_product_recipe (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', recipe_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', product_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', quantity DOUBLE PRECISION NOT NULL, INDEX IDX_D60EF83159D8A214 (recipe_id), INDEX IDX_D60EF8314584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE kf_recipe (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', description VARCHAR(500) DEFAULT \'NULL\', image VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE kf_product ADD CONSTRAINT FK_27D4556612469DE2 FOREIGN KEY (category_id) REFERENCES kf_category (id)');
        $this->addSql('ALTER TABLE kf_product_recipe ADD CONSTRAINT FK_D60EF83159D8A214 FOREIGN KEY (recipe_id) REFERENCES kf_recipe (id)');
        $this->addSql('ALTER TABLE kf_product_recipe ADD CONSTRAINT FK_D60EF8314584665A FOREIGN KEY (product_id) REFERENCES kf_product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE kf_product DROP FOREIGN KEY FK_27D4556612469DE2');
        $this->addSql('ALTER TABLE kf_product_recipe DROP FOREIGN KEY FK_D60EF83159D8A214');
        $this->addSql('ALTER TABLE kf_product_recipe DROP FOREIGN KEY FK_D60EF8314584665A');
        $this->addSql('DROP TABLE kf_category');
        $this->addSql('DROP TABLE kf_product');
        $this->addSql('DROP TABLE kf_product_recipe');
        $this->addSql('DROP TABLE kf_recipe');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
