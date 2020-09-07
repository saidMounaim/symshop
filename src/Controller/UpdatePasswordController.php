<?php 

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UpdatePasswordController
{

    protected $manager;
    protected $encoder;
    protected $jwtToken;
    protected $validator;

    public function __construct(
        ValidatorInterface $validator,
        JWTTokenManagerInterface $jwtToken,
        UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->encoder = $encoder;
        $this->jwtToken = $jwtToken;
        $this->validator = $validator;
    }


    public function __invoke(User $data)
    {
        $this->validator->validate($data);
        $hash = $this->encoder->encodePassword($data, $data->getNewPassword());
        $data->setPassword($hash);
        $this->manager->flush();
    
        $token = $this->jwtToken->create($data);

        return new JsonResponse(['token', $token]);
        
    }

}