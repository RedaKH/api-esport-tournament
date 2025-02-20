<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Team;
use Symfony\Bundle\SecurityBundle\Security;

class TeamProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $persistProcessor, 
    private Security $security){}


    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void{
       if($data instanceof Team){
        $data->addManager($this->security->getUser());
       }
       $this->persistProcessor->process($data, $operation, $uriVariables,$context);
    }
}






?>