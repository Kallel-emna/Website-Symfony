<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230310042015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bilan_medical (id INT AUTO_INCREMENT NOT NULL, dossier_medical_id INT DEFAULT NULL, user_id INT DEFAULT NULL, antecedents LONGTEXT NOT NULL, taille VARCHAR(255) NOT NULL, poids VARCHAR(255) NOT NULL, examens_biologiques LONGTEXT NOT NULL, INDEX IDX_42DB77C77750B79F (dossier_medical_id), INDEX IDX_42DB77C7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE calendar (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, description LONGTEXT NOT NULL, all_day TINYINT(1) NOT NULL, background_color VARCHAR(7) NOT NULL, border_color VARCHAR(7) NOT NULL, text_color VARCHAR(7) NOT NULL, INDEX IDX_6EA9A146A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom_cat VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chambre (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, etage INT NOT NULL, prix DOUBLE PRECISION NOT NULL, capacite INT NOT NULL, INDEX IDX_C509E4FFED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consultation (id INT AUTO_INCREMENT NOT NULL, id_rendezvous_id INT DEFAULT NULL, notes LONGTEXT NOT NULL, prix DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_964685A68D6D31C6 (id_rendezvous_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dossier_medical (id INT AUTO_INCREMENT NOT NULL, ordonnance_id INT DEFAULT NULL, certificat LONGTEXT NOT NULL, groupe_sanguin VARCHAR(255) NOT NULL, INDEX IDX_3581EE622BF23B8F (ordonnance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ordonnance (id INT AUTO_INCREMENT NOT NULL, consultation_id INT DEFAULT NULL, user_id INT DEFAULT NULL, nompatient VARCHAR(255) NOT NULL, date DATE NOT NULL, medicament LONGTEXT NOT NULL, INDEX IDX_924B326C62FF6CDF (consultation_id), INDEX IDX_924B326CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, telephone NUMERIC(10, 0) NOT NULL, photo VARCHAR(255) NOT NULL, date_admission DATE NOT NULL, date_sortie DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, nom_produit VARCHAR(255) NOT NULL, quantite INT NOT NULL, prix INT NOT NULL, INDEX IDX_29A5EC27BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendez_vous (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, time TIME NOT NULL, date_rendezvous DATE NOT NULL, confirmation TINYINT(1) NOT NULL, INDEX IDX_65E8AA0AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_chambre (id INT AUTO_INCREMENT NOT NULL, chambre_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, date_admission DATE NOT NULL, date_sortie DATE DEFAULT NULL, INDEX IDX_A29C5F7A9B177F54 (chambre_id), INDEX IDX_A29C5F7A6B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, nom_service VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, address LONGTEXT NOT NULL, telephone NUMERIC(10, 0) NOT NULL, token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bilan_medical ADD CONSTRAINT FK_42DB77C77750B79F FOREIGN KEY (dossier_medical_id) REFERENCES dossier_medical (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bilan_medical ADD CONSTRAINT FK_42DB77C7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE calendar ADD CONSTRAINT FK_6EA9A146A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chambre ADD CONSTRAINT FK_C509E4FFED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A68D6D31C6 FOREIGN KEY (id_rendezvous_id) REFERENCES rendez_vous (id)');
        $this->addSql('ALTER TABLE dossier_medical ADD CONSTRAINT FK_3581EE622BF23B8F FOREIGN KEY (ordonnance_id) REFERENCES ordonnance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ordonnance ADD CONSTRAINT FK_924B326C62FF6CDF FOREIGN KEY (consultation_id) REFERENCES consultation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ordonnance ADD CONSTRAINT FK_924B326CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation_chambre ADD CONSTRAINT FK_A29C5F7A9B177F54 FOREIGN KEY (chambre_id) REFERENCES chambre (id)');
        $this->addSql('ALTER TABLE reservation_chambre ADD CONSTRAINT FK_A29C5F7A6B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bilan_medical DROP FOREIGN KEY FK_42DB77C77750B79F');
        $this->addSql('ALTER TABLE bilan_medical DROP FOREIGN KEY FK_42DB77C7A76ED395');
        $this->addSql('ALTER TABLE calendar DROP FOREIGN KEY FK_6EA9A146A76ED395');
        $this->addSql('ALTER TABLE chambre DROP FOREIGN KEY FK_C509E4FFED5CA9E6');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A68D6D31C6');
        $this->addSql('ALTER TABLE dossier_medical DROP FOREIGN KEY FK_3581EE622BF23B8F');
        $this->addSql('ALTER TABLE ordonnance DROP FOREIGN KEY FK_924B326C62FF6CDF');
        $this->addSql('ALTER TABLE ordonnance DROP FOREIGN KEY FK_924B326CA76ED395');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0AA76ED395');
        $this->addSql('ALTER TABLE reservation_chambre DROP FOREIGN KEY FK_A29C5F7A9B177F54');
        $this->addSql('ALTER TABLE reservation_chambre DROP FOREIGN KEY FK_A29C5F7A6B899279');
        $this->addSql('DROP TABLE bilan_medical');
        $this->addSql('DROP TABLE calendar');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE chambre');
        $this->addSql('DROP TABLE consultation');
        $this->addSql('DROP TABLE dossier_medical');
        $this->addSql('DROP TABLE ordonnance');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE rendez_vous');
        $this->addSql('DROP TABLE reservation_chambre');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
