<?php
   namespace CharlesRothDotNet\Alfred;

   use Smarty\Smarty;

   class SmartyPage extends Smarty {
      function __construct($debug = false) {
         parent::__construct();

         $this->setTemplateDir('../templates/');
         $this->setCompileDir ('../templates_c/');
         $this->setConfigDir  ('../configs/');
         $this->setCacheDir   ('../cache/');

         $this->caching = Smarty::CACHING_LIFETIME_CURRENT;
         $this->debugging = $debug;
       }

       function register1ArgModifiers(string ... $names) {
          foreach ($names as $name) {
             $this->registerPlugin(PLUGIN_MODIFIER, $name, $name);
          }
       }
   }
