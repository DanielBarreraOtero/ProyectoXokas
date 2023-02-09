<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230209180305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE posicionamiento (id INT AUTO_INCREMENT NOT NULL, mesa_id INT NOT NULL, distribucion_id INT NOT NULL, pos_x INT NOT NULL, pos_y INT NOT NULL, INDEX IDX_CE1AE8F18BDC7AE9 (mesa_id), INDEX IDX_CE1AE8F1CCCF7FF5 (distribucion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE posicionamiento ADD CONSTRAINT FK_CE1AE8F18BDC7AE9 FOREIGN KEY (mesa_id) REFERENCES mesa (id)');
        $this->addSql('ALTER TABLE posicionamiento ADD CONSTRAINT FK_CE1AE8F1CCCF7FF5 FOREIGN KEY (distribucion_id) REFERENCES distribucion (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE posicionamiento DROP FOREIGN KEY FK_CE1AE8F18BDC7AE9');
        $this->addSql('ALTER TABLE posicionamiento DROP FOREIGN KEY FK_CE1AE8F1CCCF7FF5');
        $this->addSql('DROP TABLE posicionamiento');
    }
}
