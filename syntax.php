<?php
/**
 * PHP-Wikify plugin: lets the parser wikify output of php scripts
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Kasper Sandberg <redeeman@metanurb.dk>
 * @author     Schplurtz le Déboulonné <Schplurtz@laposte.net>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

class syntax_plugin_phpwikify extends DokuWiki_Syntax_Plugin
{
	function syntax_plugin_phpwikify(){
		global $PARSER_MODES;
		$this->allowedModes = $PARSER_MODES['formatting'];
	}
	
	function getType(){
		return "protected";
	}

	function getPType(){
		return "normal";
	}

	function getSort(){
		return 0;
	}

	function connectTo( $mode ) {
        	$this->Lexer->addEntryPattern("<phpwikify>(?=.*?</phpwikify>)",$mode,"plugin_phpwikify");
	}
 
	function postConnect() {
		$this->Lexer->addExitPattern( "</phpwikify>","plugin_phpwikify");
	}
 
	function handle( $match, $state, $pos, Doku_Handler $handler ){
		return array($state,$match);
	}

	function render( $mode, Doku_Renderer $renderer, $data ) {
		if($mode == 'xhtml'){
			list($state, $data) = $data;
			if ($state === DOKU_LEXER_UNMATCHED) {
				ob_start();
				eval( $data );
				$renderer->doc .= p_render( "xhtml", p_get_instructions( ob_get_contents() ), $info );
				ob_end_clean();
			}
			return true;
		}
		return false;
	}
}
?>
