# PackageFactory.AtomicFusion.Constants

> Constants as a language construct for fusion

## Warning!

This is experimental technology. Constants are currently not part of the fusion language. You can however install this package to make this functionality available to your project.

Our goal is to make this part of the fusion core in the future. It is very likely though, that Syntax, Scoping and implementation details will deviate from what is presented here.

## Installation

PackageFactory.AtomicFusion.Constants is available via packagist. You can install this package with composer:

```sh
composer require packagefactory/atomicfusion-constants
```

We use semantic-versioning so every breaking change will increase the major-version number.

## Usage

This package introduces the `const:` declaration, that let's you define constants within a fusion file. With `const::*` you can use the defined constant anywhere in your fusion file:

```fusion
const: PI = 3.14

prototype(Vendor.Site:MyCircleArea) < prototype(PackageFactory.AtomicFusion:Component) {
	radius = 5
	renderer = ${const::PI * props.radius * props.radius}
}
```

Constants are scoped to the file they are defined in and cannot be overwritten or redeclared within that file.

Constant names need to be ALL_UPPERCASE and can contain letters, numbers and underscores. A name needs to start with either a letter or an underscore.

## Magic Constants

### \_\_FILE\_\_

Similar to PHP's `__FILE__` constant, you can use `const::__FILE__` to reference the location of the current fusion file.

```fusion
prototype(Vendor.Site:MyContentElement) < prototype(Neos.Fusion:Template) {
	@process.attachFileName = ${value + '<br>Brought to you by ' + const::__FILE__}
}
```

### \_\_DIR\_\_

Similar to PHP's `__DIR__` constant, you can use `const::__DIR__` to reference the directory of the current fusion file.

```fusion
prototype(Vendor.Site:MyContentElement) < prototype(Neos.Fusion:Template) {
	templatePath = ${const::__DIR__ + '/MyContentElement.html'}
}
```

## License

see [LICENSE file](LICENSE)
