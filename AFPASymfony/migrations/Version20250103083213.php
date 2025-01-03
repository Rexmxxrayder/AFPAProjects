<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250103083213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentary (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, recipe_id INT NOT NULL, posted_date DATETIME NOT NULL, comment LONGTEXT NOT NULL, INDEX IDX_1CAC12CAF675F31B (author_id), INDEX IDX_1CAC12CA59D8A214 (recipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, preparation_time INT NOT NULL, cooking_time INT NOT NULL, ingredients LONGTEXT NOT NULL, description LONGTEXT DEFAULT NULL, preparation LONGTEXT NOT NULL, category VARCHAR(255) NOT NULL, image_name VARCHAR(255) NOT NULL, is_private TINYINT(1) NOT NULL, INDEX IDX_DA88B137F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_rating (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, recipe_id INT NOT NULL, rate INT NOT NULL, INDEX IDX_55973803F675F31B (author_id), INDEX IDX_5597380359D8A214 (recipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, first_name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, favorites JSON NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CAF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CA59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recipe_rating ADD CONSTRAINT FK_55973803F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recipe_rating ADD CONSTRAINT FK_5597380359D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CAF675F31B');
        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CA59D8A214');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137F675F31B');
        $this->addSql('ALTER TABLE recipe_rating DROP FOREIGN KEY FK_55973803F675F31B');
        $this->addSql('ALTER TABLE recipe_rating DROP FOREIGN KEY FK_5597380359D8A214');
        $this->addSql('DROP TABLE commentary');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_rating');
        $this->addSql('DROP TABLE user');
    }
}
