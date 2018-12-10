<?php
class Log{
	public function __construct($filename, $path){
		$this->path     = ($path) ? $path : "/";
		$this->filename = ($filename) ? $filename : "log";
		$this->date     = date("Y-m-d H:i:s");
	}

	public function insert($text, $dated, $clear, $backup, $website){
		if ($dated) {
			$date   = "_" . str_replace(" ", "_", $this->date);
			$append = null;
		}
		else {
			$date   = "";
			$append = ($clear) ? null : FILE_APPEND | LOCK_EX;
			if ($backup) {
				$result = (copy($this->path . $this->filename . ".log", $this->path . $this->filename . "_" . str_replace(" ", "_", $this->date) . $website."-backup.log")) ? 1 : 0;
				 $append = ($result) ? $result : FILE_APPEND;
			}
		};
		$log    = "\r\n".$this->date . " -> " . $text . PHP_EOL;
		$result = (file_put_contents($this->path . $this->filename . $date . ".log", $log, FILE_APPEND | LOCK_EX)) ? 1 : 0;
		return $result;
	}

}