<?php
/**
 * Rules class.
 * @license http://opensource.org/licenses/MIT
 * @author pedroac
 * @since 0.3.0
 */
namespace pedroac\url\normalization;

use pedroac\url\URL;
use pedroac\url\normalization\rule\Rule;
use pedroac\url\normalization\rule\StripWWWRule;
use pedroac\url\normalization\rule\AddTrailingRule;
use pedroac\url\normalization\rule\SortParametersRule;
use pedroac\url\normalization\rule\StripDotSegmentsRule;
use pedroac\url\normalization\rule\StripDefaultPortRule;
use pedroac\url\normalization\rule\DecodeUnreservedRule;
use pedroac\url\normalization\rule\StripDirectoryIndexRule;
use pedroac\url\normalization\rule\StripDuplicatedSlashesRule;

/**
 * A set of URI normalization rules.
 * It should be immutable.
 */
class Rules
{
    /**
     * List of rules.
     * @var Rule[]
     */
    private $rules;

    /**
     * Build a set of normalization rules.
     * It's recommended that it's built calling self::safe, or self:safeAnd,
     * or self::basic, or self::basicAnd static methods.
     * @param Rule[] $rules Rules that should be applied.
     */
    public function __construct(Rule ...$rules)
    {
        $this->rules = $rules;
    }

    /**
     * Create a new object with normalization rules that are safe.
     * @return self
     */
    public static function safe(): self
    {
        return new self(new StripDefaultPortRule);
    }

    /**
     * Create a new object with normalization rules that are safe
     * safe and another specified rules.
     * @param  Rule[] $rules Extra rules that should be applied.
     * @return self
     */
    public static function safeAnd(Rule ...$rules): self
    {
        $obj = self::safe();
        foreach ($rules as $rule) {
            $obj->rules []= $rule;
        }
        return $obj;
    }

    /**
     * Create a new object with normalization rules that are most of the times safe.
     * @return self
     */
    public static function basic(): self
    {
        return new self(new StripDefaultPortRule,
                        new StripDirectoryIndexRule,
                        new StripDotSegmentsRule,
                        new StripDuplicatedSlashesRule,
                        new StripWWWRule,
                        new SortParametersRule,
                        new AddTrailingRule);
    }

    /**
     * Create a new object with normalization rules that are most of the times
     * safe and another specified rules.
     * @param  Rule[] $rules Extra rules that should be applied.
     * @return self
     */
    public static function basicAnd(Rule ...$rules): self
    {
        $obj = self::basic();
        foreach ($rules as $rule) {
            $obj->rules []= $rule;
        }
        return $obj;
    }

    /**
     * Create a new URL value object with the rules applied.
     * @param URL $url URL where the rules should be applied.
     * @return URL     URL with the rules applied.
     */
    public function apply(URL $url): URL
    {
        $components = $url->parse()->toComponents();

        foreach ($this->rules as $rule) {
            $rule->apply($components);
        }
        return new URL($components);
    }
}
