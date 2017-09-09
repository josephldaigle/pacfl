<?php

/**
*	This class creates the drop down of states for the donation form
*	helper to reduce code in the form template
*/

class PAC_Dropdown {
	
	private $state_file;
	private $xml_states;

	/**
	*	CONSTRUCTOR
	*/
	public function PAC_Dropdown() {
		$this->state_file = plugin_dir_path(__DIR__) . '/xml/states.xml';
		$this->xml_states = simplexml_load_file($this->state_file);
	}
	
	/**
	*	@param(optional)	the state that is "selected" in the drop-down
	*	@return				the html for state drop down, selecting @param
	*/
	public function get_dropdown($user_state) {
		$drop_down = '';
		if($user_state == null || empty($user_state)) {
			//insert blank placeholder if no state exists
			$drop_down .= '<option value="" selected="selected"></option>';
			
			//user state not selected
			foreach($this->xml_states->state as $state) {
				$curr_state = $state->attributes()->name;
				$drop_down .= '<option value="' . $curr_state . '">' . $curr_state . '</option>';
			}
		} else {
			//user state selected
			foreach($this->xml_states->state as $state) {
				$curr_state = $state->attributes()->name;
				if (preg_match('/' . $user_state .'/', $curr_state)) {
					$drop_down .= '<option value="' . $curr_state . '" selected="selected">' . $curr_state . '</option>';
				} else {
					$remaining .= '<option value="' . $curr_state . '">' . $curr_state . '</option>';
				}
			}
			$drop_down .= $remaining;
		}
		return $drop_down;
	}
	
	/**
	*	 @param -the state name
	*	 @return - the abbreviation for @param
	*/
	public function get_state_abbr($state) {
		foreach($this->xml_states->state as $xmlState) {
			if ($state == $xmlState->attributes()->name) {
				return $xmlState->attributes()->abbreviation;
			}
		}
	}

}//end class