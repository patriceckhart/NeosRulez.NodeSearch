# NodeSearch

A Neos CMS plugin to add search functionality.


## Installation

The NeosRulez.NodeSearch package is listed on packagist (https://packagist.org/packages/neosrulez/nodesearch) - therefore you don't have to include the package in your "repositories" entry any more.

Just run:

```
composer require neosrulez/nodesearch
```

## Settings.yaml

You can define which NodeTypes should not be searched

```yaml
NeosRulez:
  NodeSearch:
    ignoredNodetypes:
      values:
        - 'Acme.Package:Document.Category'
        - 'Acme.Site:Document.404'
```

## Author

* E-Mail: mail@patriceckhart.com
* URL: http://www.patriceckhart.com
