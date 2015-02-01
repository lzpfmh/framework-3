<?php
/**
 * Go! AOP framework
 *
 * @copyright Copyright 2011, Lisachenko Alexander <lisachenko.it@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Go\Aop\Framework;

/**
 * Composer for method invocations
 *
 * This technique allows to build a class for maximum performance of method invocation
 */
class MethodInvocationComposer
{
    const CLOSURE_DYNAMIC_TRAIT = '\Go\Aop\Framework\Block\ClosureDynamicProceedTrait';
    const CLOSURE_SPLAT_TRAIT   = '\Go\Aop\Framework\Block\ClosureSplatDynamicProceedTrait';
    const CLOSURE_STATIC_TRAIT  = '\Go\Aop\Framework\Block\ClosureStaticProceedTrait';
    const REFLECTION_TRAIT      = '\Go\Aop\Framework\Block\ReflectionProceedTrait';
    const VARIADIC_INVOCATION   = '\Go\Aop\Framework\Block\VariadicInvocationTrait';
    const SIMPLE_INVOCATION     = '\Go\Aop\Framework\Block\SimpleInvocationTrait';

    /**
     * Composes a class with specific features and returns its name
     *
     * @param bool $useClosureBinding Enables usage of closures instead of reflection
     * @param bool $useSplatOperator Enables usage of optimized invocation with splat operator
     * @param bool $useVariadics Enables usage of optimized invocation with variadic args
     *
     * @return string Name of composed class
     */
    public static function compose($isStatic, $useClosureBinding, $useSplatOperator, $useVariadics)
    {
        $className = __NAMESPACE__ . '\\';
        $className .= $isStatic ? 'Static' : 'Dynamic';

        $traits = array();

        if ($useClosureBinding) {
            $className .= 'Closure';
            $dynamicTrait = self::CLOSURE_DYNAMIC_TRAIT;
            if ($useSplatOperator && !$isStatic) {
                $className .= 'Splat';
                $dynamicTrait = self::CLOSURE_SPLAT_TRAIT;
            }
            $traits[] = $isStatic ? self::CLOSURE_STATIC_TRAIT : $dynamicTrait;
        } else {
            $className .= 'Reflection';
            $traits[] = self::REFLECTION_TRAIT;
        }

        if ($useVariadics) {
            $className .= 'Variadic';
            $traits[] = self::VARIADIC_INVOCATION;
        } else {
            $traits[] = self::SIMPLE_INVOCATION;
        }

        $className .= 'MethodInvocation';

        return $className;
    }
}
