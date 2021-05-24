<?php

namespace App\Twig;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    private SerializerInterface $serializer;
    private EntityManagerInterface $em;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $this->serializer = $serializer;
        $this->em = $em;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('getPostContent', [$this, 'getPostContent']),
            new TwigFilter('getPostUser', [$this, 'getPostUser']),
        ];
    }

    public function getPostContent(string $string)
    {
        $decodedEnvelope = $this->serializer->decode(['body' => $string]);

        return $decodedEnvelope->getMessage()->getPost()->getPostContent();
    }

    public function getPostUser(string $string)
    {
        $decodedEnvelope = $this->serializer->decode(['body' => $string]);

        return $this->em->getRepository(User::class)->find($decodedEnvelope->getMessage()->getPost()->getUserId());
    }
}
