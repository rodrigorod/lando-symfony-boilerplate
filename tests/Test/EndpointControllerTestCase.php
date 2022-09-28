<?php

namespace App\Tests\Test;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class EndpointTestCase.
 *
 * @coversNothing
 *
 * @internal
 */
class EndpointControllerTestCase extends WebTestCase
{
    /**
     * Anonymous client.
     */
    protected KernelBrowser $anonymousClient;

    /**
     * Authenticated client.
     */
    protected KernelBrowser $authenticatedClient;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->anonymousClient = static::createClient();
    }

    public function setUpAuthenticatedClient(): void
    {
        if (empty($credentials)) {
            //
        }
    }
}
