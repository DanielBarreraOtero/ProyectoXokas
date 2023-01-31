<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230130150714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dia_festivo (id INT AUTO_INCREMENT NOT NULL, dia DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evento (id INT AUTO_INCREMENT NOT NULL, fecha DATE NOT NULL, max_asistentes INT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evento_tramo (evento_id INT NOT NULL, tramo_id INT NOT NULL, INDEX IDX_5FCF2FAD87A5F842 (evento_id), INDEX IDX_5FCF2FAD6E801575 (tramo_id), PRIMARY KEY(evento_id, tramo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE juego (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, ancho_tablero DOUBLE PRECISION NOT NULL, alto_tablero DOUBLE PRECISION NOT NULL, min_jugadores INT NOT NULL, max_jugadores INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mesa (id INT AUTO_INCREMENT NOT NULL, alto DOUBLE PRECISION NOT NULL, ancho DOUBLE PRECISION NOT NULL, pos_y INT NOT NULL, pos_x INT NOT NULL, sillas INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tramo (id INT AUTO_INCREMENT NOT NULL, hora_inicio TIME NOT NULL, hora_fin TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evento_tramo ADD CONSTRAINT FK_5FCF2FAD87A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evento_tramo ADD CONSTRAINT FK_5FCF2FAD6E801575 FOREIGN KEY (tramo_id) REFERENCES tramo (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evento_tramo DROP FOREIGN KEY FK_5FCF2FAD87A5F842');
        $this->addSql('ALTER TABLE evento_tramo DROP FOREIGN KEY FK_5FCF2FAD6E801575');
        $this->addSql('DROP TABLE dia_festivo');
        $this->addSql('DROP TABLE evento');
        $this->addSql('DROP TABLE evento_tramo');
        $this->addSql('DROP TABLE juego');
        $this->addSql('DROP TABLE mesa');
        $this->addSql('DROP TABLE tramo');
    }
}
