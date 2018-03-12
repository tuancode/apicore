<?php

namespace AppBundle\Security;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\ExpiredTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\InvalidTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\MissingTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JwtTokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var JWTEncoderInterface
     */
    private $jwtEncoder;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var TokenExtractorInterface
     */
    protected $tokenExtractor;

    /**
     * @param JWTEncoderInterface      $jwtEncoder
     * @param EntityManagerInterface   $entityManager
     * @param EventDispatcherInterface $dispatcher
     * @param TokenExtractorInterface  $tokenExtractor
     */
    public function __construct(
        JWTEncoderInterface $jwtEncoder,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher,
        TokenExtractorInterface $tokenExtractor
    ) {
        $this->jwtEncoder = $jwtEncoder;
        $this->entityManager = $entityManager;
        $this->dispatcher = $dispatcher;
        $this->tokenExtractor = $tokenExtractor;
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request): ?string
    {
        $token = $this->tokenExtractor->extract($request);
        if (!$token) {
            return false;
        }

        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        try {
            $data = $this->jwtEncoder->decode($credentials);

            return $this->entityManager
                ->getRepository(User::class)
                ->findOneBy(['username' => $data['username']]);
        } catch (JWTDecodeFailureException $e) {
            if (JWTDecodeFailureException::EXPIRED_TOKEN === $e->getReason()) {
                throw new ExpiredTokenException('Expired JWT Token');
            }

            if (JWTDecodeFailureException::INVALID_TOKEN === $e->getReason()) {
                throw new InvalidTokenException('Invalid JWT Token');
            }

            throw new InvalidTokenException('Invalid JWT Token', 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $authException): ?Response
    {
        $response = new JWTAuthenticationFailureResponse($authException->getMessageKey());

        if ($authException instanceof ExpiredTokenException) {
            $event = new JWTExpiredEvent($authException, $response);
            $this->dispatcher->dispatch(Events::JWT_EXPIRED, $event);
        } else {
            $event = new JWTInvalidEvent($authException, $response);
            $this->dispatcher->dispatch(Events::JWT_INVALID, $event);
        }

        return $event->getResponse();
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // do nothing - let the controller be called
    }

    /**
     * {@inheritdoc}
     *
     * @return JWTAuthenticationFailureResponse|Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $exception = new MissingTokenException('JWT Token not found', 0, $authException);
        $event = new JWTNotFoundEvent($exception, new JWTAuthenticationFailureResponse($exception->getMessageKey()));

        $this->dispatcher->dispatch(Events::JWT_NOT_FOUND, $event);

        return $event->getResponse();
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsRememberMe(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request)
    {
        return true;
    }
}
