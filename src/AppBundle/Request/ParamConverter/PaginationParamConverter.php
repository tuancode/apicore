<?php

namespace AppBundle\Request\ParamConverter;

use AppBundle\Pagination\Pagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PaginationParamConverter.
 */
class PaginationParamConverter implements ParamConverterInterface
{
    /**
     * @var Pagination
     */
    private $pagination;

    /**
     * PaginationParamConverter constructor.
     *
     * @param Pagination $pagination
     */
    public function __construct(Pagination $pagination)
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
