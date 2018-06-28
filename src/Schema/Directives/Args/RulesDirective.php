<?php

namespace Nuwave\Lighthouse\Schema\Directives\Args;

use GraphQL\Language\AST\ArgumentNode;
use GraphQL\Language\AST\DirectiveNode;
use Nuwave\Lighthouse\Schema\Values\ArgumentValue;
use Nuwave\Lighthouse\Support\Contracts\Directive;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\ArgMiddleware;

class RulesDirective extends BaseDirective implements Directive, ArgMiddleware
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name()
    {
        return 'rules';
    }

    /**
     * Resolve the field directive.
     *
     * @param ArgumentValue $value
     *
     * @return ArgumentValue
     */
    public function handleArgument(ArgumentValue $value)
    {
        if (in_array($value->getField()->getNodeName(), ['Query', 'Mutation'])) {
            return $value;
        }

        $current = $value->getValue();
        $current['rules'] = array_merge(
            array_get($value->getArg(), 'rules', []),
            $this->directiveArgValue('apply')
        );

        return $value->setValue($current);
    }
}