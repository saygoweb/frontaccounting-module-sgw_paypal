<?php
namespace SGW_Landing;

class View
{
	/**
	 * 
	 * @param string $view
	 * @param string $path
	 */
	public function __construct($view, $path = 'views') {
		$this->_view = $view;
		$this->_path = $path;
		$this->data = array();
		if (!file_exists($this->filePath())) {
			throw new \Exception(sprintf("View '%s' not found", $this->filePath()));
		}
	}
	
	public function set($item, $value) {
		$this->data[$item] = $value;
	}
	
	public function render() {
		echo $this->renderString();
	}
	
	public function renderString() {
		extract($this->data, EXTR_OVERWRITE);
		ob_start();
		include $this->filePath();
		return ob_get_clean();
	}
		
	public function filePath() {
		return $this->_path . '/' . $this->_view . '.html.php';
	}
	
	private $_view;
	
	private $_path;
	
	public $data;
}