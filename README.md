opencart-module-boilerplate
===========================

Creates all needed files for new opencart module.

I mean this ones:

catalog/controller/module/module_name.php 

catalog/language/english/module/module_name.php

catalog/view/theme/default/template/module/module_name.tpl

admin/controller/module/module_name.php

admin/language/english/module/module_name.php

admin/view/template/module/module_name.tpl


Usage:

* Copy oc-module.php to OpenCart root folder
* execute from console ```php-cli oc-module.php <module_name>```
* That's it. Files == created.

However, be careful: this script will overwrite any existing files (if modulename clashes e.g.)

And finally: By looking at templates it must be suitable for opencarts v1.5.x and, 
I believe, below. v2 templates are different a bit, but layout are similar.
