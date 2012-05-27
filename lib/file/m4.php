<?php
namespace Genex\File;

class M4 {
	public $name;
	public $includes = array();
	public $sources = array();

	public function __construct($name) {
		$this->name = $name;
	}

	public function addInclude($path) {
		$this->includes[] = $path;
	}

	public function addSrc($filename) {
		$this->sources[] = $filename;
	}

	public function compile() {
		$low = strtolower($this->name);
		$up = strtoupper($this->name);
		$m4 = "
PHP_ARG_WITH({$low}, for {$this->name} support,
[  --with-{$low}             Include {$this->name} support])

if test \"\$PHP_{$up}\" != \"no\"; then
	PHP_ADD_INCLUDE(.)
	";
		foreach($this->includes as $include) {
			$m4 .= "PHP_ADD_INCLUDE($include)".\n\t;
		}

		$src = implode(" ", $this->sources);
		$m4 .= "PHP_NEW_EXTENSION(sass, \"{$src}\", \$ext_shared)
fi";
		return $m4;
	}
}
