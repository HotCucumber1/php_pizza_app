<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240619214603 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE pizza(
                                `pizza_id`    INT          NOT NULL AUTO_INCREMENT,
                                `pizza_name`  VARCHAR(255) NOT NULL,
                                `definition`  VARCHAR(255) DEFAULT NULL,
                                `weight`      INT          NOT NULL,
                                `pizzaType`   INT          DEFAULT NULL,
                                `pizzaImage`  VARCHAR(255) NOT NULL,
                                PRIMARY KEY (`pizza_id`),
                                UNIQUE INDEX `name_idx` (`pizza_name`)
                            );");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE pizza");
    }
}
