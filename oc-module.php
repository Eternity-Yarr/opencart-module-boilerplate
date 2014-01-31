<?php

function usage() {

$doc = <<<'EOT'
OpenCart Module Boilerplate generator
Usage: php-cli oc-module.php <module_name>

Note: Name of the module must contain only alphabetic, lower case characters
and underscores!

EOT;
print_r($doc);
exit(0);

}

if (
	(count($argv)!=2)  OR
	(!ctype_alpha(str_replace("_","",$argv[1]))) OR
	(!ctype_lower(str_replace("_","",$argv[1])))
	) usage();

define('MODULE', $argv[1]);

$files = array(
	array("catalog/controller/module/".MODULE.".php", 					<<<'EOT'
<?php
class ControllerModule%CamelName% extends Controller {

	protected function index() {

		$this->language->loaf('module/%module%');
		$this->data['heading_title'] = $this->language->get('heading_title');

		/*

			Your controller code goes here ...

		*/


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/%module%.tpl')) {
                        $this->template = $this->config->get('config_template') . '/template/module/%module%.tpl';
                } else {
                        $this->template = 'default/template/module/%module%.tpl';
                }

        $this->render();

	}
}
?>

EOT
),
	array("catalog/language/english/module/".MODULE.".php", 			<<<'EOT'
<?php
// Heading
$_['heading_title']  = '%CamelName% module heading';

/*

	Your  string constans goes here ...

*/

?>

EOT
),
	array("catalog/view/theme/default/template/module/".MODULE.".tpl",  <<<'EOT'
<div><?php /* Your module template goes here... */ </div>

EOT
),
	array("admin/controller/module/".MODULE.".php",  					<<<'EOT'
<?php
class ControllerModule%CamelName% extends Controller {

	public function index() {

		$this->language->load('module/%module%');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		        $this->model_setting_setting->editSetting('%module%', $this->request->post);
		
		        $this->session->data['success'] = $this->language->get('text_success');
		
		        $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		/*

			Load additional language constants here ..

		*/

        if (isset($this->error['warning'])) {
                $this->data['error_warning'] = $this->error['warning'];
        } else {
                $this->data['error_warning'] = '';
        }

        if (isset($this->error['code'])) {
                $this->data['error_code'] = $this->error['code'];
        } else {
                $this->data['error_code'] = '';
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $this->data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_module'),
                'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
        );

        $this->data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('module/%module%', 'token=' . $this->session->data['token'], 'SSL')
        );
                
        $this->data['action'] = $this->url->link('module/%module%', 'token=' . $this->session->data['token'], 'SSL');
                
        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        /*

        	 Assign your form variables here ...
        
        */

        $this->load->model('design/layout');
                
        $this->data['layouts'] = $this->model_design_layout->getLayouts();
                
        $this->template = 'module/%module%.tpl';
        $this->children = array(
                'common/header',
                'common/footer'
        );
                
        $this->response->setOutput($this->render());


	}

	public function validate() {

        if (!$this->user->hasPermission('modify', 'module/%module%')) {
                $this->error['warning'] = $this->language->get('error_permission');
        }
                
        /*

        	Validate your form here ...

        */
                
        if (!$this->error) {
                return true;
        } else {
                return false;
        }


	}

}



?>

EOT
),
	array("admin/language/english/module/".MODULE.".php",  				<<<'EOT'
<?php
// Heading
$_['heading_title']       = '%CamelName% module title';

// Text
$_['text_success']        = 'Success: You have modified module %CamelName%!';

// Entry
/*

	Your text entries here ...

*/

// Error
$_['error_permission']    = 'Warning: You do not have permission to modify module %CamelName%!';
?>

EOT
),
	array("admin/view/template/module/".MODULE.".tpl",  				<<<'EOT'
<php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?ph
    </div>
    <div class="content">
    </div>
</div>
<php echo $footer;?>

EOT
));
$camelname = str_replace(" ","",ucwords(str_replace("_", " ", MODULE)));
$error = 0;
foreach ($files as $file)
{
$filename = $file[0];
echo "Creating file ".$filename;
if ($fp = fopen($filename,'w+')) {
	echo " [OK]".PHP_EOL;
	echo "Writing ... ";
	if (fwrite($fp,
		str_replace(
			"%module%", 
			MODULE, 
			str_replace(
				"%CamelName%",
				$camelname,$file[1]))))
		echo " [OK]".PHP_EOL;
	else {
		$error++;
		echo " [ERROR]".PHP_EOL;
	}
	
	fclose($fp);
} else $error++;
}
if ($error) echo "Done with $error errors".PHP_EOL;
else echo "Done.".PHP_EOL;

?>