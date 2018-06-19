<?php 

//require the ilp_plugin.php class 
require_once($CFG->dirroot.'/blocks/ilp/classes/plugins/ilp_dashboard_tab.class.php');

class ilp_dashboard_starthere_tab  extends ilp_dashboard_tab {
	
	public		$student_id;
	public		$course_id;
	public 		$filepath;
	public		$linkurl;
	public 		$selectedtab;
		
	
	function __construct($student_id=null,$course_id=NULL)	{
		global 	$CFG;
		
		$this->linkurl				=	$CFG->wwwroot."/blocks/ilp/actions/view_main.php?user_id=".$student_id."&course_id={$course_id}";		
		
		$this->student_id	=	$student_id;
		$this->course_id	=	$course_id;
		$this->filepath		=	$CFG->dirroot."/blocks/ilp/plugins/tabs/entries/overview.php";

		
		//set the id of the tab that will be displayed first as default
		$this->default_tab_id	=	$this->plugin_id.'-1';
		
		//call the parent constructor
		parent::__construct();
	}
	
	/**
	 * Return the text to be displayed on the tab
	 */
	function display_name()	{
		return	get_string('ilp_dashboard_starthere_tab_name','block_ilp');
	}
	

	/**
	 * Returns the content to be displayed 
	 *
	 * @param	string $selectedtab the tab that has been selected this variable
	 * this variable should be used to determined what to display
	 * 
	 * @return none
	  */
	function display($selectedtab=null)	{
		global 	$CFG,$PAGE,$USER, $PARSER;
		
		
		$pluginoutput	=	"";
		
		//get the selecttab param if has been set
		$this->selectedtab = $PARSER->optional_param('selectedtab', NULL, PARAM_INT);

		//get the tabitem param if has been set
		$this->tabitem = $PARSER->optional_param('tabitem', NULL, PARAM_INT);
		
		//split the selected tab id on up 3 ':'
		$seltab	=	explode(':',$selectedtab);
					
		//if the seltab is empty then the highest level tab has been selected
		if (empty($seltab))	$seltab	=	array($selectedtab); 
									
		$plugin_id	= (!empty($seltab[1])) ? $seltab[1] : $this->default_tab_id ;
		
		if ($this->dbc->get_user_by_id($this->student_id)) {
				
				$user	=	$this->dbc->get_user_by_id($this->student_id);

// JAC Add tutor list from ilp_dashboard_student_info.php
				$tutors	=	$this->dbc->get_student_tutors($this->student_id);
				$tutorslist	=	array();
				if (!empty($tutors)) {
					foreach ($tutors as $t) {
						$tutorslist[]	=	"<a href='{$CFG->wwwroot}/message/index.php?id={$t->id}' class='tutorslist' target='_blank'>".fullname($t)."</a>";
					}					
				} else {
					$tutorslist		=	"";
				}
// JAC Add tutor list from ilp_dashboard_student_info.php

			
				//start buffering output
				ob_start();
				
				echo "<div class='ilp_dashboard_starthere_tab'>";
				echo "<h1>".get_string('ilp_dashboard_starthere_tab_rubric1','block_ilp')."</h1><br />";
				echo "<p><strong>".get_string('ilp_dashboard_starthere_tab_rubric2','block_ilp')."</strong></p>";
				echo "<p>".get_string('ilp_dashboard_starthere_tab_rubric3','block_ilp')."</p>";
				echo "<p><strong>".get_string('ilp_dashboard_starthere_tab_rubric4','block_ilp');
				echo implode(', ',$tutorslist)."</strong></p>";
				echo "<p>".get_string('ilp_dashboard_starthere_tab_rubric5','block_ilp')."</p>";
				echo "<p>".get_string('ilp_dashboard_starthere_tab_rubric6','block_ilp')."</p>";
				echo "</div>";

				
				//pass the output instead to the output var
				$pluginoutput = ob_get_contents();
			
				ob_end_clean();
				
		} else {
				$pluginoutput	=	get_string('studentnotfound','block_ilp');
		}
					
		
		return $pluginoutput;
	}

	/**
	 * Adds the string values from the tab to the language file
	 *
	 * @param	array &$string the language strings array passed by reference so we  
	 * just need to simply add the plugins entries on to it
	 */
	 static function language_strings(&$string) {
        $string['ilp_dashboard_starthere_tab'] 					= 'START HERE';
        $string['ilp_dashboard_starthere_tab_name'] 				= 'Start Here';
        $string['ilp_dashboard_starthere_tab_rubric1'] 				= 'Welcome to the Academic Adviser system';
        $string['ilp_dashboard_starthere_tab_rubric2'] 				= 'Advisee Instructions';
        $string['ilp_dashboard_starthere_tab_rubric3'] 				= 'At the beginning of your course you will be assigned an Academic Advisor whose role is to offer you help and advice throughout your stay at the University. This relationship is an important one since, in addition to academic counselling, the Academic Advisor is one person to turn to if you wish to talk about a variety of other issues, for example illness, family problems and career possibilities.';
        $string['ilp_dashboard_starthere_tab_rubric4'] 				= 'Your Academic Adviser (sometimes referred to as Tutor) is ';
        $string['ilp_dashboard_starthere_tab_rubric5'] 				= 'To get started, you first need to request a meeting with your Adviser.  Click on their name in the link above to send them a Moodle message.  Please include as much information in the message as you are able, such as when would be your time/date preference for a meeting, whether there is a particular issue or aspect of your student you wish to discuss.';
        $string['ilp_dashboard_starthere_tab_rubric6'] 				= 'Your Academic Adviser should then reply to arrange a meeting and may also create a report or self-reflective questionnaire to complete before the meeting (see the Reports tab).  For more information about Academic Advice please visit the <a href="http://www.brookes.ac.uk/student/services/handbook/" target="_blank">Student Services Handbook</a> (opens in new window).';
        
        return $string;
    }
	
	
	
	
	
}

?>