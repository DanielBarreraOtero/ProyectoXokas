<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230130152914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invitacion (id INT AUTO_INCREMENT NOT NULL, evento_id INT NOT NULL, usuario_id INT NOT NULL, asiste TINYINT(1) NOT NULL, INDEX IDX_3CD30E8487A5F842 (evento_id), INDEX IDX_3CD30E84DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE presentacion (id INT AUTO_INCREMENT NOT NULL, evento_id INT NOT NULL, juego_id INT NOT NULL, INDEX IDX_56A887B587A5F842 (evento_id), INDEX IDX_56A887B513375255 (juego_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reserva (id INT AUTO_INCREMENT NOT NULL, mesa_id INT NOT NULL, juegos_id INT NOT NULL, usuario_id INT NOT NULL, asiste TINYINT(1) NOT NULL, fecha DATE NOT NULL, INDEX IDX_188D2E3B8BDC7AE9 (mesa_id), INDEX IDX_188D2E3BFC632F0C (juegos_id), INDEX IDX_188D2E3BDB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reserva_tramo (reserva_id INT NOT NULL, tramo_id INT NOT NULL, INDEX IDX_D87F0788D67139E8 (reserva_id), INDEX IDX_D87F07886E801575 (tramo_id), PRIMARY KEY(reserva_id, tramo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nombre VARCHAR(255) NOT NULL, ap1 VARCHAR(255) NOT NULL, ap2 VARCHAR(255) DEFAULT NULL, tlf INT NOT NULL, UNIQUE INDEX UNIQ_2265B05DE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invitacion ADD CONSTRAINT FK_3CD30E8487A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id)');
        $this->addSql('ALTER TABLE invitacion ADD CONSTRAINT FK_3CD30E84DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE presentacion ADD CONSTRAINT FK_56A887B587A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id)');
        $this->addSql('ALTER TABLE presentacion ADD CONSTRAINT FK_56A887B513375255 FOREIGN KEY (juego_id) REFERENCES juego (id)');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3B8BDC7AE9 FOREIGN KEY (mesa_id) REFERENCES mesa (id)');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3BFC632F0C FOREIGN KEY (juegos_id) REFERENCES juego (id)');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3BDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE reserva_tramo ADD CONSTRAINT FK_D87F0788D67139E8 FOREIGN KEY (reserva_id) REFERENCES reserva (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reserva_tramo ADD CONSTRAINT FK_D87F07886E801575 FOREIGN KEY (tramo_id) REFERENCES tramo (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invitacion DROP FOREIGN KEY FK_3CD30E8487A5F842');
        $this->addSql('ALTER TABLE invitacion DROP FOREIGN KEY FK_3CD30E84DB38439E');
        $this->addSql('ALTER TABLE presentacion DROP FOREIGN KEY FK_56A887B587A5F842');
        $this->addSql('ALTER TABLE presentacion DROP FOREIGN KEY FK_56A887B513375255');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3B8BDC7AE9');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3BFC632F0C');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3BDB38439E');
        $this->addSql('ALTER TABLE reserva_tramo DROP FOREIGN KEY FK_D87F0788D67139E8');
        $this->addSql('ALTER TABLE reserva_tramo DROP FOREIGN KEY FK_D87F07886E801575');
        $this->addSql('DROP TABLE invitacion');
        $this->addSql('DROP TABLE presentacion');
        $this->addSql('DROP TABLE reserva');
        $this->addSql('DROP TABLE reserva_tramo');
        $this->addSql('DROP TABLE usuario');
    }
}
