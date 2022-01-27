# Drupal T search

Sometimes customer asks to prepare list of all strings for translations. This package can be used (with phpcs together) to parse and prepare this list

## Installation

Will be with composer, but not implemented yet.

## Usage

Run command to register sniffer.

```
phpcs --config-set installed_paths /somepath/yuskinpark/drupal_t_search/sniffers
```

After that you can run

```
phpcs --standard=SearchTPhp path/to/custom/modules/
```

Be sure phpcs is available.
