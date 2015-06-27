<?php

namespace Carnage\Cqrs\Aggregate\Identity;

use RandomLib\Factory;

/**
 * Class YouTubeStyleIdentityGenerator
 * @package Carnage\Cqrs\Aggregate\Identity
 *
 * Generates a youtube style id matching regex: [0-9A-Za-z-_]{11}
 */
class YouTubeStyleIdentityGenerator implements GeneratorInterface
{
    private $random;

    public function __construct()
    {
        $this->random = (new Factory())->getMediumStrengthGenerator();
    }

    public function generateIdentity()
    {
        return rtrim(strtr(base64_encode($this->random->generate(22)), '+/', '-_'), '=');
    }
}