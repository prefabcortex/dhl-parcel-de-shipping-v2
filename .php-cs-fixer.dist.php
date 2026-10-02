<?php

declare(strict_types=1);

/**
 * The coding standard applied to a generated package, and shipped inside it.
 *
 * This is not the standard for this application's own code — that is Pint, configured in
 * pint.json. This file travels: `fixer-config-file` makes the generator copy it into every
 * package it writes, so whoever receives the package can reformat it with the same rules it
 * was produced under, and .gitattributes marks it export-ignore so it stays out of the
 * Composer tarball.
 *
 * The rules are the generator's house style, matching its reference projects and the rule set
 * its own FixerParityTest holds the printer to. The printer already emits output this pass
 * leaves untouched; the pass runs anyway, because "already correct" is a claim worth checking
 * on the way out rather than assuming.
 */
// The path is a placeholder, and deliberately a simple one. At generation time the generator
// passes the output directory explicitly, so this finder is not what decides what gets fixed;
// when the file is copied into the package, ComposerPackageGenerator rewrites this exact call
// to the package's own `src/` and `examples/`. It refuses any `->in(…)` holding nested
// parentheses, so keep the argument a plain expression.
$finder = PhpCsFixer\Finder::create()->in([__DIR__ . '/src', __DIR__ . '/examples', __DIR__ . '/tests']);

return new PhpCsFixer\Config()
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PHP8x0Migration' => true,
        '@PHP8x0Migration:risky' => true,
        '@PHP8x1Migration' => true,
        '@PHP8x2Migration' => true,
        '@PHP8x3Migration' => true,
        '@PHP8x4Migration' => true,
        'declare_strict_types' => true,
        'array_syntax' => ['syntax' => 'short'],
        'concat_space' => ['spacing' => 'one'],
        'yoda_style' => false,
        'no_unused_imports' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha', 'imports_order' => ['class', 'function', 'const']],
        'native_function_invocation' => ['include' => ['@internal'], 'scope' => 'namespaced', 'strict' => true],
        'native_constant_invocation' => true,
        'nullable_type_declaration' => false,
        'new_with_parentheses' => true,
        'no_superfluous_phpdoc_tags' => ['allow_mixed' => true],
        'global_namespace_import' => ['import_classes' => true, 'import_constants' => true, 'import_functions' => true],

        // Off on purpose, and the one @Symfony rule the generator's width limit cannot live beside.
        // It pulls a `throw` back onto one line whatever its length, which is exactly what the
        // printer has just decided against for the calls inside it — and a rule that undoes a
        // decision made with the finished line in view is the wrong of the two.
        'single_line_throw' => false,

        // Off on purpose. `phpdoc_types` normalises the case of PHP's own type names and cannot
        // tell one from a class name, so a model called `Resource` — a name OpenAPI descriptions
        // use freely — comes back as the `resource` pseudo-type. That would have this pass
        // introduce the very defect it runs to catch. The library's FixerParityTest excludes it
        // for the same reason; keep the two in step.
        'phpdoc_types' => false,
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true);
