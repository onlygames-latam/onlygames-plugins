# Onlygames Plugins

List of current Plugins used by [onlygames-frontend](https://github.com/onlygames-latam/onlygames-frontend)

## Shared Hosting Plugins

Plugins que hace falta descargar del hosting compartido

- [x] feedzy-rss-feeds-lite@4.2.7
- [x] jetpack-por-wordpress.com@9.6
- [x] simple-iframe@1.1.1
- [ ] smash-balloon@6.0.5
- [ ] wpbakery-page-builder@6.5.0
- [x] yoast-seo@17.3

### Goodgame Theme plugins

- [x] envato-market@2.0.10
- [x] planetshine-goodgame-theme-extension@1.0.2
- [x] visual-composer@45.3.0
- [x] wordpress-popular-posts@6.1.13

## How to create a custom Composer package registry

Create a Custom Composer Package:

1. Gather all the existing plugins that you want to include in your custom package.

- Create a new directory for your custom package and place all the plugin files inside it. The directory structure may look like this:
  vbnet

```
your-custom-package/
├── plugin1/
├── plugin2/
├── plugin3/
├── composer.json
└── README.md (optional)
```

- In the composer.json file of your custom package, define the name, version, and other necessary details of the package. You can also specify the plugins as required dependencies in the require section.
  Set Up a Private Composer Repository:

2. Set up a private Composer repository using [Satis](https://github.com/composer/satis), Toran Proxy, or any other suitable solution.
   Make sure your custom package is included in the repository.
   Add the Custom Repository to Composer:

3. Add your private Composer repository to your WordPress project's composer.json file under the repositories section.
   Require the Custom Package:

4. In your WordPress project's composer.json, require your custom package as a dependency. Use the package name and version specified in your custom package's composer.json.
   For example:
   json

```json
"require": {
    "wpackagist-plugin/plugin-name": "1.0.0", // Any other dependencies from wpackagist.org
    "your-custom-vendor/your-custom-package": "1.0.0" // Your custom package
},
```

5. Install Dependencies:

Run `composer install` or `composer update` in your WordPress project to install the custom package and its dependencies.

By creating a custom package that includes all your existing plugins and pushing it to your private registry, you can easily manage and version control the plugins as a single unit. This simplifies the dependency management for your WordPress project and ensures that all the required plugins are readily available from your custom repository.
