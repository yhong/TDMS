<?

class config_plugin_main extends Config {
	public function config_plugin_main(){
		$this->dsn			= "mysqli://root@localhost/tdms";
		$this->plugin		= "ADMIN";
	}
}
?>