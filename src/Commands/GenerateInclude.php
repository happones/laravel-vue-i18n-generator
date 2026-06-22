<?php

namespace Happones\VueInternationalizationGenerator\Commands;

use Illuminate\Console\Command;

use Happones\VueInternationalizationGenerator\Generator;

class GenerateInclude extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vue-i18n:generate {--umd} {--multi} {--with-vendor} {--file-name=} {--lang-files=} {--format=ts} {--multi-locales}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generates a vue-i18n|vuex-i18n compatible js/ts array/object out of project translations";

    /**
     * Execute the console command.
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $this->comment("Generating language files...");

        try {
            $root = base_path() . config('vue-i18n-generator.langPath');
            $config = config('vue-i18n-generator');

            // options
            $umd = $this->option('umd');
            $multipleFiles = $this->option('multi');
            $withVendor = $this->option('with-vendor');
            $fileName = $this->option('file-name');
            $langFiles = $this->option('lang-files');
            $format = $this->option('format');
            $multipleLocales = $this->option('multi-locales');

            if ($umd) {
                // if the --umd option is set, set the $format to 'umd'
                $format = 'umd';
            }

            if (!$this->isValidFormat($format)) {
                throw new \RuntimeException('Invalid format passed: ' . $format);
            }

            if ($multipleFiles || $multipleLocales) {
                $files = (new Generator($config))
                    ->generateMultiple($root, $format, $multipleLocales);

                if ($config['showOutputMessages']) {
                    $this->info("Written to : " . $files);
                }

                $this->info("Success! Generated lang files.");
                return 0;
            }

            if ($langFiles) {
                $langFiles = explode(',', $langFiles);
            }

            $data = (new Generator($config))
                ->generateFromPath($root, $format, $withVendor, $langFiles);


            $jsFile = $this->getFileName($fileName);

            if (!isset($fileName)) {
                $ext = 'ts';
                if ($format === 'es6' || $format === 'umd') {
                    $ext = 'js';
                } elseif ($format === 'json') {
                    $ext = 'json';
                }
                $pathInfo = pathinfo($jsFile);
                $jsFile = $pathInfo['dirname'] . DIRECTORY_SEPARATOR . $pathInfo['filename'] . '.' . $ext;
            }

            file_put_contents($jsFile, $data);

            if ($config['showOutputMessages']) {
                $this->info("Written to : " . $jsFile);
            }

            $this->info("Success! Generated lang files.");
            return 0;
        } catch (\Exception $e) {
            $this->error("Error: Failed to generate language files. Reason: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * @param string $fileNameOption
     * @return string
     */
    private function getFileName($fileNameOption)
    {
        if (isset($fileNameOption)) {
            return base_path() . $fileNameOption;
        }

        return base_path() . config('vue-i18n-generator.jsFile');
    }

    /**
     * @param string $format
     * @return boolean
     */
    private function isValidFormat($format)
    {
        $supportedFormats = ['ts', 'es6', 'umd', 'json'];
        return in_array($format, $supportedFormats);
    }
}
