<?php

namespace App\DataFixtures;

use App\Entity\FoundObject;
use App\Entity\Station;
use App\Entity\User;
use App\Enum\ObjectStatusEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function generateRandomDate($startYear = 2020)
    {
        $startDate = strtotime("$startYear-01-01");
        $endDate = time();
        $randomTimestamp = rand($startDate, $endDate);
        $randomDate = new \DateTime();
        $randomDate->setTimestamp($randomTimestamp);
        return $randomDate;
    }

    public function load(ObjectManager $manager, ): void
    {
        $descriptions = [
            "rouge, un peu abimé, mouillé",
            "bleu, en excellent état, sec",
            "vert, légèrement rayé, propre",
            "jaune, usé, humide",
            "noir, en bon état, poussiéreux",
            "blanc, neuf, sec",
            "marron, un peu décoloré, légèrement humide",
            "gris, légèrement égratigné, sec",
            "orange, parfait état, mouillé",
            "violet, usé, sec",
            "rose, abimé, sec",
            "beige, en très bon état, humide",
            "argenté, neuf, propre",
            "doré, légèrement abimé, sec",
            "café, un peu froissé, mouillé",
            "turquoise, en bon état, humide",
            "kaki, sale, sec",
            "crème, abimé, sec",
            "lavande, en excellent état, légèrement humide",
            "indigo, neuf, mouillé",
            "fuchsia, légèrement abimé, sec",
            "bordeaux, en bon état, mouillé",
            "aqua, usé, légèrement sec",
            "platinum, rayé, sec",
            "safran, abimé, mouillé",
            "perle, très propre, sec",
            "mauve, légèrement usé, humide",
            "chocolat, très abimé, sec",
            "cyan, usé, propre",
            "pêche, neuf, légèrement mouillé",
            "émeraude, en parfait état, sec",
            "sable, légèrement rayé, humide",
            "olive, usé, mouillé",
            "pistache, en excellent état, humide",
            "gris clair, rayé, mouillé",
            "cerise, très propre, sec",
            "blanc cassé, légèrement abimé, sec",
            "ambre, en bon état, mouillé",
            "argile, très usé, sec",
            "moutarde, en très bon état, sec"
        ];

        $objects = [
            "vélo",
            "téléphone portable",
            "chaise",
            "lampe",
            "ordinateur portable",
            "canapé",
            "table",
            "sac à dos",
            "montre",
            "chaussures",
            "livre",
            "bouteille d'eau",
            "parapluie",
            "portefeuille",
            "tasse",
            "clavier",
            "maillot de bain",
            "souris d'ordinateur",
            "mug",
            "veste",
            "casque audio",
            "lampe de bureau",
            "pantalon",
            "chemise",
            "pochette",
            "chaîne stéréo",
            "coque de téléphone",
            "appareil photo",
            "couteau",
            "pantalon de sport",
            "sac à main",
            "panier",
            "chaudron",
            "brouette",
            "valise",
            "guitare",
            "télévision",
            "table basse",
            "tente",
            "carnet de notes",
            "plante en pot"
        ];

        $foundLocations = [
            "À côté des toilettes",
            "Sur un banc près de la cafeteria",
            "Sous l'escalator près du quai 5",
            "Sur un porte-vélo devant l'entrée principale",
            "Dans le coin à côté du kiosque à journaux",
            "Près du guichet de billetterie",
            "Sous la plateforme 3, près du panneau d'affichage",
            "Dans le hall principal, près de l'ascenseur",
            "Sur une étagère près du magasin de souvenirs",
            "Dans le coin près de la porte d'accès VIP",
            "Sur le banc près de la sortie Nord",
            "À côté de la fontaine d'eau potable",
            "Sur un banc au milieu de la salle d'attente",
            "Dans un coin près de l'entrée des trains régionaux",
            "Près de la porte du contrôleur à l'entrée de la gare",
            "Sous le panneau d'information dans la zone des quais",
            "Près de l'entrée secondaire de la gare",
            "Sur une chaise près du point de recharge pour téléphones",
            "À côté du distributeur automatique de billets",
            "Dans le coin près des distributeurs de café",
            "Dans les escaliers menant à la sortie Est",
            "Sous le pont qui relie les deux halls",
            "Dans l'espace de stockage des bagages",
            "À l'entrée du tunnel sous-terrain",
            "Sur le banc en face de la sortie principale",
            "Près du panneau d'affichage des horaires de train",
            "Dans un coin de la salle d'attente près du café",
            "Sous le grand horloge dans le hall",
            "Sur une étagère dans le coin du salon VIP",
            "Près des portes automatiques de la gare",
            "Dans la zone de stockage des poussettes",
            "Sur une table à côté du centre d'information",
            "Près des sièges près du train à grande vitesse",
            "À côté de la machine à tickets pour les voyageurs",
            "Près de l'escalier mécanique menant au parking",
            "Sous les panneaux de direction vers le quai 10",
            "Dans le hall près des boutiques de journaux",
            "À côté du point d'embarquement pour les trains régionaux",
            "Sur un banc près de l'entrée du parking souterrain",
            "Près du tableau d'affichage des départs",
            "Dans le coin à côté du magasin de vêtements",
            "Sur une chaise près de la porte du contrôleur",
            "À l'extérieur près de l'entrée du hall central",
            "Sur un banc au niveau du point de contrôle de sécurité",
            "Sous la grande horloge de la gare",
            "Dans le coin près des toilettes pour hommes"
        ];

        $users = [
            [
                'first_name' => 'Alice',
                'last_name' => 'Dupont',
                'email' => 'alice.dupont@example.com',
                'phone_number' => '0123456789',
            ],
            [
                'first_name' => 'Bob',
                'last_name' => 'Martin',
                'email' => 'bob.martin@example.com',
                'phone_number' => '0987654321',
            ],
            [
                'first_name' => 'Charlie',
                'last_name' => 'Durand',
                'email' => 'charlie.durand@example.com',
                'phone_number' => '0112233445',
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Lemoine',
                'email' => 'david.lemoine@example.com',
                'phone_number' => '0678901234',
            ],
            [
                'first_name' => 'Emma',
                'last_name' => 'Lemoine',
                'email' => 'emma.lemoine@example.com',
                'phone_number' => '0654321098',
            ],
        ];

        $station1 = new Station();
        $station1->setName('Paris_Nord');
        $station1->setLocalisation('18 Rue de Dunkerque, 75010 Paris, France');
        $station2 = new Station();
        $station2->setName('Lille_Flandre');
        $station2->setLocalisation('Place des Buisses, 59800 Lille, France');
        $station3 = new Station();
        $station3->setName('LyonPartDieu');
        $station3->setLocalisation('6 Rue de la Villette, 69003 Lyon, France');
        $station4 = new Station();
        $station4->setName('MarseilleSaintCharles');
        $station4->setLocalisation('17 Boulevard Maurice Bourdet, 13001 Marseille, France');
        $station5 = new Station();
        $station5->setName('NiceVille');
        $station5->setLocalisation('Avenue Thiers, 06000 Nice, France');
        $stationsArr = [$station1, $station2, $station3, $station4, $station5];
        foreach ($stationsArr as $station) {
            $manager->persist($station);
        }

        for ($i = 0; $i < 200; $i++) {
            $fObject = new FoundObject();
            $fObject->setDescription($objects[rand(0, count($objects) - 1)] . ", couleur : " . $descriptions[rand(0, count($descriptions) - 1)]);
            $fObject->setReportDate(AppFixtures::generateRandomDate());
            $fObject->setStation($stationsArr[rand(0, count($stationsArr) - 1)]);
            $fObject->setStatus(ObjectStatusEnum::cases()[array_rand(ObjectStatusEnum::cases())]);
            $fObject->setLocalisation($foundLocations[rand(0, count($foundLocations) - 1)]);
            $manager->persist($fObject);
        }

        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setFirstName($users[$i]["first_name"]);
            $user->setSurname($users[$i]["last_name"]);
            $user->setEmail($users[$i]["email"]);
            $user->setPhoneNumber($users[$i]["phone_number"]);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
