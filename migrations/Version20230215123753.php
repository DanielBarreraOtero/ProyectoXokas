<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215123753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evento ADD tramo_inicio_id INT NOT NULL, ADD tramo_fin_id INT NOT NULL');
        $this->addSql('ALTER TABLE evento ADD CONSTRAINT FK_47860B0575D10539 FOREIGN KEY (tramo_inicio_id) REFERENCES tramo (id)');
        $this->addSql('ALTER TABLE evento ADD CONSTRAINT FK_47860B05BDFED04B FOREIGN KEY (tramo_fin_id) REFERENCES tramo (id)');
        $this->addSql('CREATE INDEX IDX_47860B0575D10539 ON evento (tramo_inicio_id)');
        $this->addSql('CREATE INDEX IDX_47860B05BDFED04B ON evento (tramo_fin_id)');
        $this->addSql('ALTER TABLE reserva ADD tramo_inicio_id INT NOT NULL, ADD tramo_fin_id INT NOT NULL');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3B75D10539 FOREIGN KEY (tramo_inicio_id) REFERENCES tramo (id)');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3BBDFED04B FOREIGN KEY (tramo_fin_id) REFERENCES tramo (id)');
        $this->addSql('CREATE INDEX IDX_188D2E3B75D10539 ON reserva (tramo_inicio_id)');
        $this->addSql('CREATE INDEX IDX_188D2E3BBDFED04B ON reserva (tramo_fin_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evento DROP FOREIGN KEY FK_47860B0575D10539');
        $this->addSql('ALTER TABLE evento DROP FOREIGN KEY FK_47860B05BDFED04B');
        $this->addSql('DROP INDEX IDX_47860B0575D10539 ON evento');
        $this->addSql('DROP INDEX IDX_47860B05BDFED04B ON evento');
        $this->addSql('ALTER TABLE evento DROP tramo_inicio_id, DROP tramo_fin_id');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3B75D10539');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3BBDFED04B');
        $this->addSql('DROP INDEX IDX_188D2E3B75D10539 ON reserva');
        $this->addSql('DROP INDEX IDX_188D2E3BBDFED04B ON reserva');
        $this->addSql('ALTER TABLE reserva DROP tramo_inicio_id, DROP tramo_fin_id');
    }
}
