<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241218000616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE approvisionnement (id SERIAL NOT NULL, demande_id INT DEFAULT NULL, article_id INT NOT NULL, quantite INT NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_516C3FAA80E95E18 ON approvisionnement (demande_id)');
        $this->addSql('CREATE INDEX IDX_516C3FAA7294869C ON approvisionnement (article_id)');
        $this->addSql('CREATE TABLE article (id SERIAL NOT NULL, nom VARCHAR(50) NOT NULL, qte_stock INT DEFAULT NULL, qte_restante INT DEFAULT NULL, prix DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE client (id SERIAL NOT NULL, user_account_id INT DEFAULT NULL, surname VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, telephone VARCHAR(20) NOT NULL, adresse VARCHAR(255) NOT NULL, creer_compte BOOLEAN DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, montant_dette DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C74404553C0C9956 ON client (user_account_id)');
        $this->addSql('CREATE TABLE demande (id SERIAL NOT NULL, client_id INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, tel INT NOT NULL, nom_complet VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, statut VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2694D7A519EB6921 ON demande (client_id)');
        $this->addSql('CREATE TABLE demande_article (id SERIAL NOT NULL, demande_id INT NOT NULL, article_id INT NOT NULL, quantite INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_32CDB5C980E95E18 ON demande_article (demande_id)');
        $this->addSql('CREATE INDEX IDX_32CDB5C97294869C ON demande_article (article_id)');
        $this->addSql('CREATE TABLE dette (id SERIAL NOT NULL, client_id INT NOT NULL, demande_id INT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, montant_verser DOUBLE PRECISION DEFAULT NULL, statut VARCHAR(20) DEFAULT \'non_solde\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_831BC80819EB6921 ON dette (client_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_831BC80880E95E18 ON dette (demande_id)');
        $this->addSql('CREATE TABLE dette_article (id SERIAL NOT NULL, dette_id INT NOT NULL, article_id INT NOT NULL, quantite INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C5321D58E11400A1 ON dette_article (dette_id)');
        $this->addSql('CREATE INDEX IDX_C5321D587294869C ON dette_article (article_id)');
        $this->addSql('CREATE TABLE paiement (id SERIAL NOT NULL, dette_id INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, montant NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B1DC7A1EE11400A1 ON paiement (dette_id)');
        $this->addSql('COMMENT ON COLUMN paiement.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649AA08CB10 ON "user" (login)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE approvisionnement ADD CONSTRAINT FK_516C3FAA80E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE approvisionnement ADD CONSTRAINT FK_516C3FAA7294869C FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404553C0C9956 FOREIGN KEY (user_account_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A519EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE demande_article ADD CONSTRAINT FK_32CDB5C980E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE demande_article ADD CONSTRAINT FK_32CDB5C97294869C FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dette ADD CONSTRAINT FK_831BC80819EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dette ADD CONSTRAINT FK_831BC80880E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dette_article ADD CONSTRAINT FK_C5321D58E11400A1 FOREIGN KEY (dette_id) REFERENCES dette (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dette_article ADD CONSTRAINT FK_C5321D587294869C FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1EE11400A1 FOREIGN KEY (dette_id) REFERENCES dette (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE approvisionnement DROP CONSTRAINT FK_516C3FAA80E95E18');
        $this->addSql('ALTER TABLE approvisionnement DROP CONSTRAINT FK_516C3FAA7294869C');
        $this->addSql('ALTER TABLE client DROP CONSTRAINT FK_C74404553C0C9956');
        $this->addSql('ALTER TABLE demande DROP CONSTRAINT FK_2694D7A519EB6921');
        $this->addSql('ALTER TABLE demande_article DROP CONSTRAINT FK_32CDB5C980E95E18');
        $this->addSql('ALTER TABLE demande_article DROP CONSTRAINT FK_32CDB5C97294869C');
        $this->addSql('ALTER TABLE dette DROP CONSTRAINT FK_831BC80819EB6921');
        $this->addSql('ALTER TABLE dette DROP CONSTRAINT FK_831BC80880E95E18');
        $this->addSql('ALTER TABLE dette_article DROP CONSTRAINT FK_C5321D58E11400A1');
        $this->addSql('ALTER TABLE dette_article DROP CONSTRAINT FK_C5321D587294869C');
        $this->addSql('ALTER TABLE paiement DROP CONSTRAINT FK_B1DC7A1EE11400A1');
        $this->addSql('DROP TABLE approvisionnement');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE demande');
        $this->addSql('DROP TABLE demande_article');
        $this->addSql('DROP TABLE dette');
        $this->addSql('DROP TABLE dette_article');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
