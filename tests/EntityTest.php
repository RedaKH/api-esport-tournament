<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\User;
use App\Entity\Team;
use App\Entity\Tournament;
use App\Enum\UserRole;
use App\Enum\TournamentStatus;

class EntityTest extends TestCase
{
    public function testUserCreation()
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPseudo('testUser');
        $user->setRoles([UserRole::PLAYER->value]);
        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('testUser',$user->getPseudo());
        $this->assertContains(UserRole::PLAYER->value,$user->getRoles());
        $this->assertTrue(true);
    }

    public function testTeamCreation(){
        $team = new Team();

        $team->setName('Test team');
        $team->setDescription('lorem ipsum');

        $this->assertEquals('Test team',$team->getName());
        $this->assertEquals('lorem ipsum',$team->getDescription());
    }

    public function testTournamentCreation(){

        $tournament = new Tournament();

        $tournament->setName('Tenkaichi Budokai');
        $tournament->setGame('DBZ Sparking Zero');
        $tournament->setStatus(TournamentStatus::DRAFT);
        $tournament->setPrizePool(10000);

        $this->assertEquals('Tenkaichi Budokai', $tournament->getName());
        $this->assertEquals('DBZ Sparking Zero', $tournament->getGame());
        $this->assertEquals(TournamentStatus::DRAFT, $tournament->getStatus());
        $this->assertEquals(10000, $tournament->getPrizePool());



    }
}
