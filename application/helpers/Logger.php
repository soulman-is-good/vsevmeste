<?php

class Logger extends X3_Component {

    private $file;
    private $error = false;

    function __construct($file) {
        if (file_exists($file) && ((filesize($file) / 1024) > 1024)) {
            $dirname = dirname($file);
            $filename = basename($file);
            $newname = $dirname . '/' . date('d.m.Y') . '_' . $filename;
            if (!rename($file, $newname)) {
                $this->error = 'Can\'t rename the old log file';
            }
            foreach (glob($dirname . '/*.log') as $logfile) {
                //ако има стари логове на повече от 1 месец
                if (filemtime($logfile) < (time() - (30 * 24 * 3600))) {
                    unlink($logfile);
                }
            }
            file_put_contents($file, '');
        } elseif (!file_exists($file)) {
            file_put_contents($file, '');
        }
        $this->file = $file;
    }

    function log() {
        $args = func_get_args();
        $message = "";
        for($i = 0; $i < func_num_args(); $i++) {
            $arg = $args[$i];
            $message .= $arg . " ";
        }
        if($message != "") {
            $message = date('d.m.Y h:i:s') . "> " . $message . PHP_EOL;
        }
        if ($message != "" && !file_put_contents($this->file, $message, FILE_APPEND)) {
            $this->error = 'Can\'t write to log';
        }
    }

    function is_error() {
        if ($this->error != false) {
            return true;
        }
        return false;
    }

    function get_error() {
        return $this->error;
    }

}
?>