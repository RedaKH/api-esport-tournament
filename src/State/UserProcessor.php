<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserProcessor implements ProcessorInterface{

    public function __construct(
        private ProcessorInterface $persistProcessor,
        private Security $security,
        private UserPasswordHasherInterface $passwordHasher
    ){}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []){
        if($data instanceof User && $data->getPassword()){
            if(!str_starts_with($data->getPassword(),'$2y$')){
                $hashedPassword = $this->passwordHasher->hashPassword($data,$data->getPassword());
                $data->setPassword($hashedPassword);
            }
        }
        
        $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}

?>