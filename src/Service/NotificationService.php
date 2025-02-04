<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class NotificationService {

   
    public function __construct(
        private EntityManagerInterface $entityManager

    ){
       
    }

    public function notify(User $user, string $title, string $message): void
    {
       
        $notification = new Notification();
        $notification->setUser($user);
        $notification->setTitle($title);
        $notification->setMessage($message);

       $this->entityManager->persist($notification);
       $this->entityManager->flush();


    }

    public function notifyTeam (array $teamMembers, string $title, string $message) : void
    {

        foreach($teamMembers as $member)
        {
            $this->notify($member,$title,$message);
        }
    } 

    public function markAsRead(Notification $notification) : void {
        $notification->setIsRead(true);
        $this->entityManager->flush();
        
    }


}
?>