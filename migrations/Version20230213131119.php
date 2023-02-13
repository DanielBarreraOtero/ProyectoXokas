<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230213131119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evento_tramo DROP FOREIGN KEY FK_5FCF2FAD6E801575');
        $this->addSql('ALTER TABLE evento_tramo DROP FOREIGN KEY FK_5FCF2FAD87A5F842');
        $this->addSql('DROP TABLE evento_tramo');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3B6E801575 FOREIGN KEY (tramo_id) REFERENCES tramo (id)');
        $this->addSql('CREATE INDEX IDX_188D2E3B6E801575 ON reserva (tramo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evento_tramo (evento_id INT NOT NULL, tramo_id INT NOT NULL, INDEX IDX_5FCF2FAD87A5F842 (evento_id), INDEX IDX_5FCF2FAD6E801575 (tramo_id), PRIMARY KEY(evento_id, tramo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE evento_tramo ADD CONSTRAINT FK_5FCF2FAD6E801575 FOREIGN KEY (tramo_id) REFERENCES tramo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evento_tramo ADD CONSTRAINT FK_5FCF2FAD87A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3B6E801575');
        $this->addSql('DROP INDEX IDX_188D2E3B6E801575 ON reserva');
    }
}
