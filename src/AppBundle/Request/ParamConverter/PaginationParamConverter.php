<?php

namespace AppBundle\Request\ParamConverter;

use AppBundle\Pagination\Pagination;
use AppBundle\Pagination\PaginationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PaginationParamConverter.
 */
class PaginationParamConverter implements ParamConverterInterface
{
    /**
     * @var PaginationInterface
     */
    private $pagination;

    /**
     * @param PaginationInterface $pagination
     */
    public function __construct(PaginationInterface $pagination)
    {
        $this->pagination = $pagination;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $this->pagination->setRequest($request);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return Pagination::class === $configuration->getClass();
    }
}
