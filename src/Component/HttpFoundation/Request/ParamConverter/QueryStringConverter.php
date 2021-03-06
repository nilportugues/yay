<?php

namespace Component\HttpFoundation\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class QueryStringConverter implements ParamConverterInterface
{
    public function supports(ParamConverter $configuration): bool
    {
        return 'QueryString' === $configuration->getConverter();
    }

    public function apply(Request $request, ParamConverter $configuration): void
    {
        $options = $configuration->getOptions();
        $target = $configuration->getName();
        $source = isset($options['field']) ? $options['field'] : $target;

        if (!$request->query->has($source)) {
            return;
        }

        $value = $request->query->get($source);
        $request->attributes->set($target, $value);
    }
}
