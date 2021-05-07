<?php

namespace App\Twig;

use App\Service\UserRetriever;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    private SerializerInterface $serializer;
    private UserRetriever $userRetriever;

    public function __construct(SerializerInterface $serializer, UserRetriever $userRetriever)
    {
        $this->serializer = $serializer;
        $this->userRetriever = $userRetriever;
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

        return $this->userRetriever->retrieve($decodedEnvelope->getMessage()->getPost()->getUserId());
    }
}
