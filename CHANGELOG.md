# Change Log

## [v2.3.0](https://github.com/ubleipzig/tx-booking/tree/2.3.0)

https://github.com/ubleipzig/tx-booking/compare/2.2.1...2.3.0

**Fixes**

* does a few methods properties to public at _Room_ model
* uses _@TYPO3\CMS\Extbase\Annotation\Inject_ for repositories includes at _AbstractController_ class due to not accepting namespace defined 

## [v2.2.1](https://github.com/ubleipzig/tx-booking/tree/2.2.1)

[Full Changelog](https://github.com/ubleipzig/tx-booking/compare/2.2.0...2.2.1)

**Fixes**

* fixin' deprecated @inject annotation cmp. [82869](https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.0/Feature-82869-ReplaceInjectWithTYPO3CMSExtbaseAnnotationInject.html)
* fixin' deprecated @cascade annotation cmp. [83093](https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.0/Feature-83093-ReplaceCascadeWithTYPO3CMSExtbaseAnnotationORMCascade.html)
* fixin' deprecated @lazy annotation cmp. [83078](https://docs.typo3.org/c/typo3/cms-core/12.4/en-us/Changelog/9.0/Feature-83078-ReplaceLazyWithTYPO3CMSExtbaseAnnotationORMLazy.html)

## [v2.2.0](https://github.com/ubleipzig/tx-booking/tree/2.2.0)

[Full Changelog](https://github.com/ubleipzig/tx-booking/compare/2.1.1...2.2.0)

**Support**

* removes support for typo3 v7
* adds support for typo3 v9

**Refactoring**

* adjusts ViewHelpers to new requirements of fluid package
  * changes namespace of extended classes 
  * renames occupationSwitchViewHelper to GetOccupationViewHelper due to new parent class AbstractViewHelper 
  * adds partials below folder _Occupation_ for each kind of booking at table row
* removes _class.ext_update.php_ because of improbable use case and deprecated method to drop and rename tables 

**Fixes**

* renames method _addFlashMessage_ to _addFlashMessagesHelper_ due to corruption of native t3 method 

## [v2.1.1](https://github.com/ubleipzig/tx-booking/tree/2.1.1)

[Full Changelog](https://github.com/ubleipzig/tx-booking/compare/2.1.0...2.1.1)

**Fixes**

* fixes brackets of array syntax adjustment at _tx_booking_domain_closingday.php_  

## [v2.1.0](https://github.com/ubleipzig/tx-booking/tree/2.1.0)

[Full Changelog](https://github.com/ubleipzig/tx-booking/compare/2.0.4...2.1.0)

**Support**

* removes support for typo3 v6 and adds support for typo3 v8

**Fixes**

* removes or adjusts deprecated parameters at /Configuration/TCA models
* replaces all deprecated syntax for _array()_ by _[]_

## [v2.0.4](https://github.com/ubleipzig/tx-booking/tree/2.0.4)

[Full Changelog](https://github.com/ubleipzig/tx-booking/compare/2.0.3...2.0.4)

**Fixes**

* fixes order of options at /Configuration/TCA/tx_booking_domain_model_booking.php at types showitems

## [v2.0.3](https://github.com/ubleipzig/tx-booking/tree/2.0.3)

[Full Changelog](https://github.com/ubleipzig/tx-booking/compare/2.0.2...2.0.3)

**Refactoring**

* Refactoring of TCA tables structures. Replacing global tca.php by single table instances. 

## [v2.0.2](https://github.com/ubleipzig/tx-booking/tree/2.0.2)

[Full Changelog](https://github.com/ubleipzig/tx-booking/compare/2.0.1...2.0.2)

**Minor enhancements**

* Renames composer.json package 'mikey179/vfsstream' to lower case due to compatibility issues.  
