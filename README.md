## About

[![Latest Version on Packagist](https://img.shields.io/packagist/v/happones/laravel-vue-i18n-generator.svg?style=flat-square)](https://packagist.org/packages/happones/laravel-vue-i18n-generator)
[![Build Status](https://travis-ci.org/happones/laravel-vue-i18n-generator.png?branch=master)](https://travis-ci.org/happones/laravel-vue-i18n-generator)
[![Total Downloads](https://img.shields.io/packagist/dt/happones/laravel-vue-i18n-generator.svg?style=flat-square)](https://packagist.org/packages/happones/laravel-vue-i18n-generator)

Laravel 6 package that allows you to share your [Laravel localizations](https://laravel.com/docs/5.8/localization)
with your [vue](http://vuejs.org/) front-end, using [vue-i18n](https://github.com/kazupon/vue-i18n) or [vuex-i18n](https://github.com/dkfbasel/vuex-i18n).

## Install the package

### For Laravel 13.x:

In your project:
```composer require happones/laravel-vue-i18n-generator --dev```

>By default, the Laravel application skeleton does not include the lang directory. If you would like to customize Laravel's language files, you may publish them via the lang:publish Artisan command. [Read more.](https://laravel.com/docs/13.x/localization)

### For Laravel 10.x - 12.x:

In your project:
```composer require happones/laravel-vue-i18n-generator:0.1.47 --dev```

### For Laravel 6.0 and below:
For older versions of the framework:

Register the service provider in ```config/app.php```

```php
Happones\VueInternationalizationGenerator\GeneratorProvider::class;
```

Next, publish the package default config:

```
php artisan vendor:publish --provider="Happones\VueInternationalizationGenerator\GeneratorProvider"
```

## Using vue-i18n

Next, you need to install one out of two supported VueJs i18n libraries. We support [vue-i18n](https://github.com/kazupon/vue-i18n) as default library. Beside that we also support [vuex-i18n](https://github.com/dkfbasel/vuex-i18n).

When you go with the default option, you only need to install the library through your favorite package manager.
### vue-i18n
```
npm i --save vue-i18n
```

```
yarn add vue-i18n
```

Then generate the include file with
```
php artisan vue-i18n:generate
```

Assuming you are using a recent version of vue-i18n (>=6.x), adjust your vue app with something like:
```js
import Vue from 'vue';
import VueInternationalization from 'vue-i18n';
import Locale from './vue-i18n-locales.generated';

Vue.use(VueInternationalization);

const lang = document.documentElement.lang.substr(0, 2);
// or however you determine your current app locale

const i18n = new VueInternationalization({
    locale: lang,
    messages: Locale
});

const app = new Vue({
    el: '#app',
    i18n,
    components: {
       ...
    }
}
```

### Laravel Inertia

```javascript
import { createI18n } from 'vue-i18n';

const i18n = createI18n({
    locale: 'ja', // set locale
    fallbackLocale: 'en', // set fallback locale
    messages, // set locale messages
    // If you need to specify other options, you can set other options
    // ...
})

createInertiaApp({
    setup({ el, app, props, plugin }) {
        const VueApp = createApp({ render: () => h(app, props) });
        VueApp.use(plugin)
            .use(i18n)
            .mixin({ methods: { route } })
            .mount(el);
    },
});
```


For older vue-i18n (5.x), the initialization looks something like:
```js
import Vue from 'vue';
import VueInternationalization from 'vue-i18n';
import Locales from './vue-i18n-locales.generated.js';

Vue.use(VueInternationalization);

Vue.config.lang = 'en';

Object.keys(Locales).forEach(function (lang) {
  Vue.locale(lang, Locales[lang])
});

...
```




## Using vuex-i18n

### vuex-i18n
```
npm i --save vuex-i18n
```

```
yarn add vuex-i18n vuex
```

Next, open `config/vue-i18n-generator.php` and do the following changes:

```diff
- 'i18nLib' => 'vue-i18n',
+ 'i18nLib' => 'vuex-i18n',
```

Then generate the include file with
```
php artisan vue-i18n:generate
```

Assuming you are using a recent version of vuex-i18n, adjust your vue app with something like:
```js
import Vuex from 'vuex';
import vuexI18n from 'vuex-i18n';
import Locales from './vue-i18n-locales.generated.js';

const store = new Vuex.Store();

Vue.use(vuexI18n.plugin, store);

Vue.i18n.add('en', Locales.en);
Vue.i18n.add('de', Locales.de);

// set the start locale to use
Vue.i18n.set(Spark.locale);

require('./components/bootstrap');

var app = new Vue({
    store,
    mixins: [require('spark')]
});
```

## Output Formats

You can specify the output formats from `es6`, `umd`, or `json` with the `--format` option. (defaults to `es6`)

```
php artisan vue-i18n:generate --format {es6,umd,json}
```

### Use case example for UMD module

```
php artisan vue-i18n:generate --format umd
```
An UMD module can be imported into the browser, build system, node and etc.

Now you can include the generated script in the browser as a normal script and reference it with window.vuei18nLocales.
```vue
<script src="{{ asset('js/vue-i18n-locales.generated.js') }}"></script>

// in your js
Vue.use(VueI18n)
Vue.config.lang = Laravel.language
Object.keys(window.vuei18nLocales).forEach(function (lang) {
  Vue.locale(lang, window.vuei18nLocales[lang])
})
```
You can still require/import it in your build system as stated above.

One advantage of doing things like this is you are not obligated to do a build of your javascript each time a the translation files get changed/saved. A good example is if you have a backend that can read and write to your translation files (like Backpack). You can listen to a save event there and call vue-i18n-generator.

## Generating Multiple Files

Sometimes you may want to generate multiple files as you want to make use of lazy loading. As such, you can specify that the generator produces multiple files within the destination directory.

There are two options:
1. One file per laravel module language file using switch ```--multi```
2. One file per locale using switch ```--multi-locales```

```
php artisan vue-i18n:generate --multi{-locales}
```

## Parameters

The generator adjusts the strings in order to work with vue-i18n's named formatting,
so you can reuse your Laravel translations with parameters.

resource/lang/message.php:
```php
return [
    'hello' => 'Hello :name',
];
```

in vue-i18n-locales.generated.js:
```js
...
    "hello": "Hello {name}",
...
```

Blade template:
```php
<div class="message">
    <p>{{ trans('message.hello', ['name' => 'visitor']) }}</p>
</div>
```

Vue template:
```js
<div class="message">
    <p>{{ $t('message.hello', {name: 'visitor'}) }}</p>
</div>
```


## Notices

- The generated file is an ES6 module.

Basic pluralization is supported by stripping Laravel tags (`{0}`, `[1,10]`, etc.), making it compatible with [vue-i18n](https://vue-i18n.intlify.dev/guide/essentials/pluralization). More sophisticated pluralization localization as described [here](https://laravel.com/docs/master/localization#pluralization) may still require custom pluralization rules in your Vue application.

# License

Under [MIT](LICENSE)
