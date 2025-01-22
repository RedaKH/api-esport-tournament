<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Tournament;
use App\Entity\SponsorshipContract;
use App\Entity\Payment;
use App\Entity\Team;
use App\Entity\Game;
use App\Entity\Sponsor;
use App\Enum\PaymentStatus;
use App\Enum\SponsorshipLevel;
use App\Enum\TournamentStatus;
use App\Enum\GameStatus;
use App\Enum\UserRole;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {}

    public function load(ObjectManager $manager): void
    {
        //création des utilisateurs
        $users = $this->createUsers($manager);
        //création des équipes
        $teams = $this->createTeams($manager,$users);
        //création des tournois
        $tournaments = $this->createTournaments($manager,$users,$teams);
        //création des matchs
        $this->createGames($manager,$tournaments,$teams);
        //création des sponsors
        $sponsors = $this->createSponsors($manager,$users);

    //Creation des contrats de sponsorings
    
    $this->createSponsorshipContracts($manager,$sponsors,$teams,$tournaments);

        $manager->flush();
    }

    private function createUsers(ObjectManager $manager): array {
        $users = [];
        $roles = [
            ['email'=>'admin@esport.com', 'roles'=>[UserRole::ADMIN]],
            ['email'=>'organizer@esport.com','roles' =>[UserRole::ORGANIZER]],
            ['email'=>'teammanager1@esport.com','roles' =>[UserRole::MANAGER]],
            ['email'=>'teammanager2@esport.com','roles' =>[UserRole::MANAGER]],
            ['email'=>'player1@esport.com','roles' =>[UserRole::PLAYER]],
            ['email'=>'player2@esport.com','roles' =>[UserRole::PLAYER]],
            ['email'=>'sponsor@esport.com','roles' =>[UserRole::SPONSOR]],







        ];

        foreach($roles as $userData){
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setPseudo(explode('@',$userData['email'])[0]);
            $user->setPassword($this->passwordHasher->hashPassword($user,'test'));
            $user->setRoles(array_map(fn($role)=>$role->value,$userData['roles']));
            $manager->persist($user);
            $users[] = $user;
        }
        return $users;


    }

    private function createTeams(ObjectManager $manager,array $users): array {
        $teams = [];

        $teamData = [
            ['name'=>'Dragon Risings','manager'=>$users[2]],
            ['name'=>'Fnatic','manager'=>$users[3]]


        ];


        foreach($teamData as $data){
            $team = new Team();
            $team->setName($data['name']);
            $team->addManager($data['manager']);
            $team->setDescription(" Une équipe prêt a tout pour gagner");
            $team->addManager($users[4]);
            $team->addManager($users[5]);
            $manager->persist($team);
            $teams[] = $team;
        }
        return $teams;

    }

    private function createTournaments(ObjectManager $manager,array $users,array $teams): array {
        $tournaments = [];
        $tournamentData = [
            [
                'name'=>'Capcom Cup',
                'game'=> 'Street Fighter',
                'organizer'=> $users[1],
                'teams'=>$teams,
                'prizePool'=>100000,
                'registrationFee'=>100
            ],

            [
                'name'=>'EVO 2024',
                'game'=> 'Dragon Ball Sparking Zero',
                'organizer'=> $users[1],
                'teams'=>$teams,
                'prizePool'=>1000000,
                'registrationFee'=>1000
            ]
            ];

            foreach($tournamentData as $data){
                $tournament = new Tournament();
                $tournament->setName($data['name']);
                $tournament->setGame($data['game']);
                $tournament->setOrganizer($data['organizer']);
                $tournament->setPrizePool($data['prizePool']);
                $tournament->setRegistrationFee($data['registrationFee']);
                $tournament->setStartDate(new \DateTime('+1 month'));
                $tournament->setEndDate(new \DateTime('+2 months'));
                $tournament->setStatus(TournamentStatus::REGISTRATION);

                foreach ($data['teams'] as $team) {
                    $tournament->addTeam($team);
                }
                $manager->persist($tournament);
                $tournaments[] = $tournament;
            }
            return $tournaments;
    }

    private function createGames(ObjectManager $manager,array $tournaments,array $teams) {
        foreach ($tournaments as $tournament) {
            $game = new Game();
            $game->setTournament($tournament);
            $game->setTeamA($teams[0]);
            $game->setTeamB($teams[1]);
            $game->setScheduledAt(new \DateTimeImmutable('+45 days'));
            $game->setStatus(GameStatus::SCHEDULED);
            $game->setScore([]);

            $manager->persist($game);
            # code...
        }

    }

    private function createSponsors(ObjectManager $manager,array $users): array{
        $sponsors = [];
        $sponsorData = [
            [
            'name'=>'Hori',
            'website'=>'https://www.hori.com',
            'user'=> $users[6]
        ],
        [
            'name'=>'Razer',
            'website'=>'https://www.razer.com',
            'user'=> $users[6]
        ]
    ];

    foreach($sponsorData as $data){
        $sponsor = new Sponsor();
        $sponsor->setName($data['name']);
        $sponsor->setWebsite($data['website']);
        $sponsor->setUser($data['user']);
        $manager->persist($sponsor);
        $sponsors[]=$sponsor;
    }
    return $sponsors;
    }

    private function createSponsorshipContracts(
        ObjectManager $manager,
        array $sponsors,
        array $teams,
        array $tournaments
    )
    {
        foreach($sponsors as $sponsor){
            $contract = new SponsorshipContract();
            $contract->setSponsor($sponsor);
            $contract->setTeam($teams[0]);
            $contract->setTournament($tournaments[0]);
            $contract->setLevel(SponsorshipLevel::GOLD);
            $contract->setAmount(150000);
            $contract->setStartDate(new \DateTimeImmutable());
            $contract->setEndDate(new \DateTimeImmutable('+1 year'));
            $contract->setTerms('lorem ipsum dolor sit amet Sponsorship for tournament and team branding rights');
            $manager->persist($contract);

        }
    }


    
}
