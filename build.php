<?php
$file = new SplFileObject(__DIR__ . '/data/data/country-codes.csv');
$isoMap = $tldMap = [];
try {
    $header = $file->fgetcsv();
    $tldColumn = array_search('TLD', $header);
    $isoColumn = array_search('ISO3166-1-Alpha-2', $header);
    while (count($data = $file->fgetcsv()) > 1) {
        [$tldColumn => $tld, $isoColumn => $iso] = $data;
        $iso = mb_strtoupper($iso);
        $tlds = array_filter(explode(',', $tld), 'strlen');
        foreach ($tlds as &$tld) {
            $tld = trim(mb_strtoupper($tld), '.');
            $tldMap[$tld] = $iso;
        }
        unset($tld);
        if (count($tlds)) {
            $isoMap[$iso] = $tlds;
        }
    }
} finally {
    $file = null;
}

$output = [
    'TldToIso' => function () use ($tldMap) {
        return ['string', '[' . str_replace(['array (', "\n"], ['', "\n      "], trim(var_export($tldMap, true), ",)\n")) . "\n    ]"];
    },
    'IsoToTld' => function () use ($isoMap) {
        $list = [];
        foreach ($isoMap as $iso => $tlds) {
            $tlds = array_map(function ($tld) {
                return var_export($tld, true);
            }, $tlds);
            $list[] = var_export($iso, true) . ' => [' . implode(', ', $tlds) . ']';
        }
        return ['array', "[\n        " . implode(",\n        ", $list) . "\n    ]"];
    }
];

$template = '<?php

declare(strict_types=1);

namespace JonasRaoni\TldToIso;

class %s
{
    public const MAPPINGS = %s;

    public static function get(?string $input): ?%s
    {
        return self::MAPPINGS[mb_strtoupper($input ?? '')] ?? null;
    }
}
';

$outputFolder = __DIR__ . '/src/';
is_dir($outputFolder) || mkdir($outputFolder);
foreach ($output as $file => $processor) {
    [$type, $source] = $processor();
    file_put_contents($outputFolder . $file . '.php', sprintf($template, $file, $source, $type));
}
