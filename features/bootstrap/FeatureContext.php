<?php

use Behat\MinkExtension\Context\MinkContext;
use Behat\Mink\Driver\BrowserKitDriver;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class FeatureContext extends MinkContext implements KernelAwareContext
{
    private $kernel;

    /**
     * @Given I am logged in as :username
     */
    public function iAmLoggedInAs($username)
    {
        $driver = $this->getSession()->getDriver();
        $session = $this->kernel->getContainer()->get('session');
        $user = $this->kernel->getContainer()->get('security.user.provider.concrete.database_users')->loadUserByUsername($username);
        $providerKey = 'secured_area';

        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $session->set('_security_'.$providerKey, serialize($token));
        $session->save();

        if ($driver instanceof BrowserKitDriver) {
            $client = $driver->getClient();
            $cookie = new Cookie($session->getName(), $session->getId());
            $client->getCookieJar()->set($cookie);
        } else if($driver instanceof Selenium2Driver) {
            $this->visitPath('/');
        } else {
            throw new \Exception('Unsupported Driver');
        }

        $this->getSession()->setCookie($session->getName(), $session->getId());
    }

    /**
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
}
