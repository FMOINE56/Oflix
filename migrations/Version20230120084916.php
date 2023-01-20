<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230120084916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE casting (id INT AUTO_INCREMENT NOT NULL, movie_id INT NOT NULL, person_id INT NOT NULL, role VARCHAR(255) NOT NULL, credit_order INT NOT NULL, INDEX IDX_D11BBA508F93B6FC (movie_id), INDEX IDX_D11BBA50217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE casting ADD CONSTRAINT FK_D11BBA508F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE casting ADD CONSTRAINT FK_D11BBA50217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE watch DROP FOREIGN KEY FK_500B4A26A76ED395');
        $this->addSql('ALTER TABLE watch DROP FOREIGN KEY FK_500B4A268F93B6FC');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE watch');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nickname VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE watch (user_id INT NOT NULL, movie_id INT NOT NULL, time_second INT NOT NULL, INDEX IDX_500B4A26A76ED395 (user_id), INDEX IDX_500B4A268F93B6FC (movie_id), PRIMARY KEY(user_id, movie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE watch ADD CONSTRAINT FK_500B4A26A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE watch ADD CONSTRAINT FK_500B4A268F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE casting DROP FOREIGN KEY FK_D11BBA508F93B6FC');
        $this->addSql('ALTER TABLE casting DROP FOREIGN KEY FK_D11BBA50217BBB47');
        $this->addSql('DROP TABLE casting');
        $this->addSql('DROP TABLE person');
    }
}
