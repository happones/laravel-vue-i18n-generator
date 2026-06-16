<?php

use Happones\VueInternationalizationGenerator\Generator;

if (!function_exists('base_path')) {
    function base_path() {
        return sys_get_temp_dir();
    }
}

class GenerateTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     *
     * @param array $arr
     * @return string
     */
    private function generateLocaleFilesFrom(array $arr): string
    {
        $root = sys_get_temp_dir() . '/' . sha1(microtime(true) . mt_rand());

        if (!is_dir($root)) {
            mkdir($root, 0777, true);
        }

        foreach ($arr as $key => $val) {

            if (!is_dir($root . '/' . $key)) {
                mkdir($root . '/' . $key);
            }

            foreach ($val as $group => $content) {
                $outFile = $root . '/'. $key . '/' . $group . '.php';
                file_put_contents($outFile, '<?php return ' . var_export($content, true) . ';');
            }
        }

        return $root;
    }

    /**
     * @param array $arr
     * @param $root
     */
    private function destroyLocaleFilesFrom(array $arr, $root): void
    {
        foreach ($arr as $key => $val) {

            foreach ($val as $group => $content) {
                $outFile = $root . '/'. $key . '/' . $group . '.php';
                if (file_exists($outFile)) {
                    unlink($outFile);
                }
            }

            if (is_dir($root . '/' . $key)) {
                rmdir($root . '/' . $key);
            }

        }

        if (is_dir($root)) {
            rmdir($root);
        }
    }

    /**
     * @throws Exception
     */
    function testBasic(): void
    {
        $arr = [
            'en' => [
                'help' => [
                    'yes' => 'yes',
                    'no' => 'no',
                ]
            ],
            'sv' => [
                'help' => [
                    'yes' => 'ja',
                    'no' => 'nej',
                ]
            ]
        ];

        $root = $this->generateLocaleFilesFrom($arr);
        $this->assertEquals(
            'export default {' . PHP_EOL
            . '    "en": {' . PHP_EOL
            . '        "help": {' . PHP_EOL
            . '            "yes": "yes",' . PHP_EOL
            . '            "no": "no"' . PHP_EOL
            . '        }' . PHP_EOL
            . '    },' . PHP_EOL
            . '    "sv": {' . PHP_EOL
            . '        "help": {' . PHP_EOL
            . '            "yes": "ja",' . PHP_EOL
            . '            "no": "nej"' . PHP_EOL
            . '        }' . PHP_EOL
            . '    }' . PHP_EOL
            . '} as const;' . PHP_EOL,
            (new Generator([]))->generateFromPath($root));
        $this->destroyLocaleFilesFrom($arr, $root);
    }

    function testBasicES6Format(): void
    {
        $format = 'es6';

        $arr = [
            'en' => [
                'help' => [
                    'yes' => 'yes',
                    'no' => 'no',
                ]
            ],
            'sv' => [
                'help' => [
                    'yes' => 'ja',
                    'no' => 'nej',
                ]
            ]
        ];

        $root = $this->generateLocaleFilesFrom($arr);
        $this->assertEquals(
            'export default {' . PHP_EOL
            . '    "en": {' . PHP_EOL
            . '        "help": {' . PHP_EOL
            . '            "yes": "yes",' . PHP_EOL
            . '            "no": "no"' . PHP_EOL
            . '        }' . PHP_EOL
            . '    },' . PHP_EOL
            . '    "sv": {' . PHP_EOL
            . '        "help": {' . PHP_EOL
            . '            "yes": "ja",' . PHP_EOL
            . '            "no": "nej"' . PHP_EOL
            . '        }' . PHP_EOL
            . '    }' . PHP_EOL
            . '}' . PHP_EOL,
            (new Generator([]))->generateFromPath($root, $format));
        $this->destroyLocaleFilesFrom($arr, $root);
    }

    function testBasicTSFormat(): void
    {
        $format = 'ts';

        $arr = [
            'en' => [
                'help' => [
                    'yes' => 'yes',
                    'no' => 'no',
                ]
            ],
            'sv' => [
                'help' => [
                    'yes' => 'ja',
                    'no' => 'nej',
                ]
            ]
        ];

        $root = $this->generateLocaleFilesFrom($arr);
        $this->assertEquals(
            'export default {' . PHP_EOL
            . '    "en": {' . PHP_EOL
            . '        "help": {' . PHP_EOL
            . '            "yes": "yes",' . PHP_EOL
            . '            "no": "no"' . PHP_EOL
            . '        }' . PHP_EOL
            . '    },' . PHP_EOL
            . '    "sv": {' . PHP_EOL
            . '        "help": {' . PHP_EOL
            . '            "yes": "ja",' . PHP_EOL
            . '            "no": "nej"' . PHP_EOL
            . '        }' . PHP_EOL
            . '    }' . PHP_EOL
            . '} as const;' . PHP_EOL,
            (new Generator([]))->generateFromPath($root, $format));
        $this->destroyLocaleFilesFrom($arr, $root);
    }

    function testBasicWithUMDFormat(): void
    {
        $format = 'umd';
        $arr = [
            'en' => [
                'help' => [
                    'yes' => 'yes',
                    'no' => 'no',
                ]
            ],
            'sv' => [
                'help' => [
                    'yes' => 'ja',
                    'no' => 'nej',
                ]
            ]
        ];

        $root = $this->generateLocaleFilesFrom($arr);
        $this->assertEquals(
            '(function (global, factory) {' . PHP_EOL
            . '    typeof exports === \'object\' && typeof module !== \'undefined\' ? module.exports = factory() :' . PHP_EOL
            . '        typeof define === \'function\' && define.amd ? define(factory) :' . PHP_EOL
            . '            typeof global.vuei18nLocales === \'undefined\' ? global.vuei18nLocales = factory() : Object.keys(factory()).forEach(function (key) {global.vuei18nLocales[key] = factory()[key]});' . PHP_EOL
            . '}(this, (function () { \'use strict\';' . PHP_EOL
            . '    return {' . PHP_EOL
            . '    "en": {' . PHP_EOL
            . '        "help": {' . PHP_EOL
            . '            "yes": "yes",' . PHP_EOL
            . '            "no": "no"' . PHP_EOL
            . '        }' . PHP_EOL
            . '    },' . PHP_EOL
            . '    "sv": {' . PHP_EOL
            . '        "help": {' . PHP_EOL
            . '            "yes": "ja",' . PHP_EOL
            . '            "no": "nej"' . PHP_EOL
            . '        }' . PHP_EOL
            . '    }' . PHP_EOL
            . '}' . PHP_EOL
            . PHP_EOL
            . '})));',
            (new Generator([]))->generateFromPath($root, $format));
        $this->destroyLocaleFilesFrom($arr, $root);
    }

    function testBasicWithJSONFormat(): void
    {
        $format = 'json';
        $arr = [
            'en' => [
                'help' => [
                    'yes' => 'yes',
                    'no' => 'no',
                ]
            ],
            'sv' => [
                'help' => [
                    'yes' => 'ja',
                    'no' => 'nej',
                ]
            ]
        ];

        $root = $this->generateLocaleFilesFrom($arr);
        $this->assertEquals(
            '{' . PHP_EOL
            . '    "en": {' . PHP_EOL
            . '        "help": {' . PHP_EOL
            . '            "yes": "yes",' . PHP_EOL
            . '            "no": "no"' . PHP_EOL
            . '        }' . PHP_EOL
            . '    },' . PHP_EOL
            . '    "sv": {' . PHP_EOL
            . '        "help": {' . PHP_EOL
            . '            "yes": "ja",' . PHP_EOL
            . '            "no": "nej"' . PHP_EOL
            . '        }' . PHP_EOL
            . '    }' . PHP_EOL
            . '}' . PHP_EOL,
            (new Generator([]))->generateFromPath($root, $format));
        $this->destroyLocaleFilesFrom($arr, $root);
    }

    /**
     * @throws Exception
     */
    function testInvalidFormat(): void
    {
        $format = 'es5';
        $arr = [];

        $root = $this->generateLocaleFilesFrom($arr);
        try {
            (new Generator([]))->generateFromPath($root, $format);
        } catch(RuntimeException $e) {
            $this->assertEquals('Invalid format passed: ' . $format, $e->getMessage());

        }
        $this->destroyLocaleFilesFrom($arr, $root);
        $this->assertTrue(true);
    }

    function testBasicWithTranslationString(): void
    {
        $arr = [
            'en' => [
                'main' => [
                    'hello :name' => 'Hello :name',
                ]
            ],
        ];

        $root = $this->generateLocaleFilesFrom($arr);
        $this->assertEquals(
            'export default {' . PHP_EOL
            . '    "en": {' . PHP_EOL
            . '        "main": {' . PHP_EOL
            . '            "hello {name}": "Hello {name}"' . PHP_EOL
            . '        }' . PHP_EOL
            . '    }' . PHP_EOL
            . '} as const;' . PHP_EOL,
            (new Generator([]))->generateFromPath($root));
        $this->destroyLocaleFilesFrom($arr, $root);
    }

    function testBasicWithEscapedTranslationString(): void
    {
        $arr = [
            'en' => [
                'main' => [
                    'hello :name' => 'Hello :name',
                    'time test 10!:00' => 'Time test 10!:00',
                ]
            ],
        ];

        $root = $this->generateLocaleFilesFrom($arr);
        $this->assertEquals(
            'export default {' . PHP_EOL
            . '    "en": {' . PHP_EOL
            . '        "main": {' . PHP_EOL
            . '            "hello {name}": "Hello {name}",' . PHP_EOL
            . '            "time test 10:00": "Time test 10:00"' . PHP_EOL
            . '        }' . PHP_EOL
            . '    }' . PHP_EOL
            . '} as const;' . PHP_EOL,
            (new Generator([]))->generateFromPath($root));
        $this->destroyLocaleFilesFrom($arr, $root);
    }

    function testBasicWithVendor(): void
    {
        $arr = [
            'en' => [
                'help' => [
                    'yes' => 'yes',
                    'no' => 'no',
                ]
            ],
            'sv' => [
                'help' => [
                    'yes' => 'ja',
                    'no' => 'nej',
                ]
            ],
            'vendor' => [
                'test-vendor' => [
                    'en' => [
                        'test-lang' => [
                            'maybe' => 'maybe'
                        ]
                    ],
                    'sv' => [
                        'test-lang' => [
                            'maybe' => 'kanske'
                        ]
                    ]
                ]
            ],
        ];

        $root = $this->generateLocaleFilesFrom($arr);

        $this->assertEquals(
            'export default {' . PHP_EOL
            . '    "en": {' . PHP_EOL
            . '        "help": {' . PHP_EOL
            . '            "yes": "yes",' . PHP_EOL
            . '            "no": "no"' . PHP_EOL
            . '        },' . PHP_EOL
            . '        "vendor": {' . PHP_EOL
            . '            "test-vendor": {' . PHP_EOL
            . '                "test-lang": {' . PHP_EOL
            . '                    "maybe": "maybe"' . PHP_EOL
            . '                }' . PHP_EOL
            . '            }' . PHP_EOL
            . '        }' . PHP_EOL
            . '    },' . PHP_EOL
            . '    "sv": {' . PHP_EOL
            . '        "help": {' . PHP_EOL
            . '            "yes": "ja",' . PHP_EOL
            . '            "no": "nej"' . PHP_EOL
            . '        },' . PHP_EOL
            . '        "vendor": {' . PHP_EOL
            . '            "test-vendor": {' . PHP_EOL
            . '                "test-lang": {' . PHP_EOL
            . '                    "maybe": "kanske"' . PHP_EOL
            . '                }' . PHP_EOL
            . '            }' . PHP_EOL
            . '        }' . PHP_EOL
            . '    }' . PHP_EOL
            . '}' . PHP_EOL,
            (new Generator([]))->generateFromPath($root, 'es6', true));

        $this->destroyLocaleFilesFrom($arr, $root);
    }

    function testBasicWithVuexLib(): void
    {
        $arr = [
            'en' => [
                'help' => [
                    'yes' => 'yes',
                    'no' => 'no',
                ]
            ],
            'sv' => [
                'help' => [
                    'yes' => 'ja',
                    'no' => 'nej',
                ]
            ]
        ];

        $root = $this->generateLocaleFilesFrom($arr);

        $this->assertEquals(
            'export default {' . PHP_EOL
            . '    "en": {' . PHP_EOL
            . '        "help": {' . PHP_EOL
            . '            "yes": "yes",' . PHP_EOL
            . '            "no": "no"' . PHP_EOL
            . '        }' . PHP_EOL
            . '    },' . PHP_EOL
            . '    "sv": {' . PHP_EOL
            . '        "help": {' . PHP_EOL
            . '            "yes": "ja",' . PHP_EOL
            . '            "no": "nej"' . PHP_EOL
            . '        }' . PHP_EOL
            . '    }' . PHP_EOL
            . '} as const;' . PHP_EOL,
            (new Generator([]))->generateFromPath($root));

        $this->destroyLocaleFilesFrom($arr, $root);
    }

    function testNamed(): void
    {
        $arr = [
            'en' => [
                'help' => [
                    'yes' => 'see :link y :lonk',
                    'no' => [
                        'one' => 'see :link',
                    ]
                ]
            ]
        ];

        $root = $this->generateLocaleFilesFrom($arr);

        $this->assertEquals(
            'export default {' . PHP_EOL
            . '    "en": {' . PHP_EOL
            . '        "help": {' . PHP_EOL
            . '            "yes": "see {link} y {lonk}",' . PHP_EOL
            . '            "no": {' . PHP_EOL
            . '                "one": "see {link}"' . PHP_EOL
            . '            }' . PHP_EOL
            . '        }' . PHP_EOL
            . '    }' . PHP_EOL
            . '} as const;' . PHP_EOL,
            (new Generator([]))->generateFromPath($root));

        $this->destroyLocaleFilesFrom($arr, $root);
    }

    function testNamedWithEscaped(): void
    {
        $arr = [
            'en' => [
                'help' => [
                    'yes' => 'see :link y :lonk at 08!:00',
                    'no' => [
                        'one' => 'see :link',
                    ]
                ]
            ]
        ];

        $root = $this->generateLocaleFilesFrom($arr);

        $this->assertEquals(
            'export default {' . PHP_EOL
            . '    "en": {' . PHP_EOL
            . '        "help": {' . PHP_EOL
            . '            "yes": "see {link} y {lonk} at 08:00",' . PHP_EOL
            . '            "no": {' . PHP_EOL
            . '                "one": "see {link}"' . PHP_EOL
            . '            }' . PHP_EOL
            . '        }' . PHP_EOL
            . '    }' . PHP_EOL
            . '} as const;' . PHP_EOL,
            (new Generator([]))->generateFromPath($root));

        $this->destroyLocaleFilesFrom($arr, $root);
    }

    function testEscapedEscapeCharacter(): void
    {
        $arr = [
            'en' => [
                'help' => [
                    'test escaped' => 'escaped escape char not !!:touched',
                ]
            ]
        ];

        $root = $this->generateLocaleFilesFrom($arr);

        $this->assertEquals(
            'export default {' . PHP_EOL
            . '    "en": {' . PHP_EOL
            . '        "help": {' . PHP_EOL
            . '            "test escaped": "escaped escape char not !:touched"' . PHP_EOL
            . '        }' . PHP_EOL
            . '    }' . PHP_EOL
            . '} as const;' . PHP_EOL,
            (new Generator([]))->generateFromPath($root));

        $this->destroyLocaleFilesFrom($arr, $root);
    }

    function testShouldNotTouchHtmlTags(): void
    {
        $arr = [
            'en' => [
                'help' => [
                    'yes' => 'see <a href="mailto:mail@com">',
                    'no' => 'see <a href=":link">',
                    'maybe' => 'It is a <strong>Test</strong> ok!',
                ]
            ]
        ];

        $root = $this->generateLocaleFilesFrom($arr);

        $this->assertEquals(
            'export default {' . PHP_EOL
            . '    "en": {' . PHP_EOL
            . '        "help": {' . PHP_EOL
            . '            "yes": "see <a href=\"mailto:mail@com\">",' . PHP_EOL
            . '            "no": "see <a href=\"{link}\">",' . PHP_EOL
            . '            "maybe": "It is a <strong>Test</strong> ok!"' . PHP_EOL
            . '        }' . PHP_EOL
            . '    }' . PHP_EOL
            . '} as const;' . PHP_EOL,
            (new Generator([]))->generateFromPath($root));

        $this->destroyLocaleFilesFrom($arr, $root);
    }

    function testPluralization(): void
    {
        $arr = [
            'en' => [
                'plural' => [
                    'one' => 'There is one apple|There are many apples',
                    'two' => 'There is one apple | There are many apples',
                    'five' => [
                        'three' => 'There is one apple    | There are many apples',
                        'four' => 'There is one apple |     There are many apples',
                    ]
                ]
            ]
        ];

        $root = $this->generateLocaleFilesFrom($arr);

        // vue-i18n
        $this->assertEquals(
            'export default {' . PHP_EOL
            . '    "en": {' . PHP_EOL
            . '        "plural": {' . PHP_EOL
            . '            "one": "There is one apple|There are many apples",' . PHP_EOL
            . '            "two": "There is one apple | There are many apples",' . PHP_EOL
            . '            "five": {' . PHP_EOL
            . '                "three": "There is one apple    | There are many apples",' . PHP_EOL
            . '                "four": "There is one apple |     There are many apples"' . PHP_EOL
            . '            }' . PHP_EOL
            . '        }' . PHP_EOL
            . '    }' . PHP_EOL
            . '} as const;' . PHP_EOL,
            (new Generator(['i18nLib' => 'vue-i18n']))->generateFromPath($root));

        // vuex-i18n
        $this->assertEquals(
            'export default {' . PHP_EOL
            . '    "en": {' . PHP_EOL
            . '        "plural": {' . PHP_EOL
            . '            "one": "There is one apple ::: There are many apples",' . PHP_EOL
            . '            "two": "There is one apple ::: There are many apples",' . PHP_EOL
            . '            "five": {' . PHP_EOL
            . '                "three": "There is one apple ::: There are many apples",' . PHP_EOL
            . '                "four": "There is one apple ::: There are many apples"' . PHP_EOL
            . '            }' . PHP_EOL
            . '        }' . PHP_EOL
            . '    }' . PHP_EOL
            . '} as const;' . PHP_EOL,
            (new Generator(['i18nLib' => 'vuex-i18n']))->generateFromPath($root));

        $this->destroyLocaleFilesFrom($arr, $root);
    }

    function testFullPluralization(): void
    {
        $arr = [
            'en' => [
                'plural' => [
                    'complex' => '{0} No apples|{1} One apple|[2,Inf] :count apples',
                    'range' => '[1,19] There are some|[20,Inf] There are many',
                    'mixed' => '{0} None|One apple|Many apples',
                ]
            ]
        ];

        $root = $this->generateLocaleFilesFrom($arr);

        $this->assertEquals(
            'export default {' . PHP_EOL
            . '    "en": {' . PHP_EOL
            . '        "plural": {' . PHP_EOL
            . '            "complex": "No apples|One apple|{count} apples",' . PHP_EOL
            . '            "range": "There are some|There are many",' . PHP_EOL
            . '            "mixed": "None|One apple|Many apples"' . PHP_EOL
            . '        }' . PHP_EOL
            . '    }' . PHP_EOL
            . '} as const;' . PHP_EOL,
            (new Generator(['i18nLib' => 'vue-i18n']))->generateFromPath($root));

        $this->destroyLocaleFilesFrom($arr, $root);
    }

    function testGenerateMultiple(): void
    {
        $arr = [
            'en' => [
                'help' => [
                    'yes' => 'yes',
                    'no' => 'no',
                ]
            ],
            'sv' => [
                'help' => [
                    'yes' => 'ja',
                    'no' => 'nej',
                ]
            ]
        ];

        $root = $this->generateLocaleFilesFrom($arr);
        $tempOutDir = sys_get_temp_dir() . '/' . sha1(microtime(true) . mt_rand()) . '/';
        mkdir($tempOutDir, 0777, true);

        // Remove the base_path() prefix if it's there
        $jsPath = str_replace(sys_get_temp_dir(), '', $tempOutDir);

        $generator = new Generator([
            'jsPath' => $jsPath,
            'langPath' => str_replace(sys_get_temp_dir(), '', $root),
            'excludes' => []
        ]);

        $createdFiles = $generator->generateMultiple($root, 'ts');

        // Check if correct file is created
        $expectedEnFile = $tempOutDir . 'help.ts';
        $this->assertTrue(file_exists($expectedEnFile));

        $enContent = file_get_contents($expectedEnFile);
        $this->assertStringContainsString('export default {', $enContent);
        $this->assertStringContainsString('"yes": "yes"', $enContent);
        $this->assertStringContainsString('} as const;', $enContent);

        // Check with ES6 format
        $generatorEs6 = new Generator([
            'jsPath' => $jsPath,
            'langPath' => str_replace(sys_get_temp_dir(), '', $root),
            'excludes' => []
        ]);
        $createdFilesEs6 = $generatorEs6->generateMultiple($root, 'es6');
        $expectedEnFileEs6 = $tempOutDir . 'help.js';
        $this->assertTrue(file_exists($expectedEnFileEs6));

        $enContentEs6 = file_get_contents($expectedEnFileEs6);
        $this->assertStringContainsString('export default {', $enContentEs6);
        $this->assertStringNotContainsString('} as const;', $enContentEs6);

        // Cleanup
        unlink($expectedEnFile);
        unlink($expectedEnFileEs6);
        rmdir($tempOutDir);
        $this->destroyLocaleFilesFrom($arr, $root);
    }
}
