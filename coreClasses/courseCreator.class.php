<?php
require_once ("courseController.class.php");

class courseCreator
{
	private $_course;
	private $_tee;
	private $_firstColumnArray = array (
		'Hole',
		'Par',
		'Yards',
		'SI',
	);
	private $_theNines = array (
		'Front 9',
		'Back 9'
	);
	private $_holeDefaults = array(); //this propery is an array which we use to store the hole defaults - means we just need to do the query once then not worry about it
	private $_courseDefaults = array(); //used to holed course defaults
	private $_teeDefaults = array(); //used to hold tee defaults
	
	public function setCourse($val)
	{
		$this->_course = $val;
	}
	
	public function getCourse()
	{
		return $this->_course;
	}
	
	public function setTee($val)
	{
		$this->_tee = $val;
	}
	
	public function getTee()
	{
		return $this->_tee;
	}
	
	public function getFirstColumnArray()
	{
		return $this->_firstColumnArray;
	}
	
	public function getTheNines()
	{
		return $this->_theNines;
	}
	
	private function setHoleDefaults($array)
	{
		$this->_holeDefaults = $array;
	}
	
	private function getHoleDefaults()
	{
		return $this->_holeDefaults;
	}
	
	private function setCourseDefaults($array)
	{
		$this->_courseDefaults = $array;
	}
	
	private function getCourseDefaults()
	{
		return $this->_courseDefaults;
	}
	
	private function setTeeDefaults($array)
	{
		$this->_teeDefaults = $array;
	}
	
	private function getTeeDefaults()
	{
		return $this->_teeDefaults;
	}
	
	public function createCourse()
	{
		return $this->generateScoreCard();
	}
	
	private function generateScoreCard()
	{
		$firstColumnArray = $this->getFirstColumnArray();
		$theNines = $this->getTheNines();
		
		foreach ($theNines as $nine) {
			
			$row[] = "<tr><td colspan='10'><h4>$nine</h4></td></tr>";
			
			foreach ($firstColumnArray as $vals) {
				$row[] = "<tr><td>$vals</td>";
				
				if ($nine == "Front 9") {
					$startValue = 1;
					$endValue = 9;
				} else if ($nine == "Back 9") {
					$startValue = 10;
					$endValue = 18;
				}
				
				for ($x=$startValue; $x<=$endValue; $x++) {				
					if ($vals == "Hole") {
						$row[] = "<td>$x</td>";
					} else if ($vals == "Par") {
						$tabIndex = $x*3;
						
						$default = $this->_generateDefault($x, "par");
						
						$option3 = "";
						$option4 = "";
						$option5 = "";
						
						if ($default == 3) {
							$option3 = "selected='selected'";
						} else if ($default == 4) {
							$option4 = "selected='selected'";
						} else if ($default == 5) {
							$option5 = "selected='selected'";
						} else {
							$option4 = "selected='selected'";
						}
						
						$row[] = "<td><select id='par$x' name='par$x' class='form-control addCourseSelect' tabindex='$tabIndex'><option value='3' $option3>3</option><option value='4' $option4>4</option><option value='5' $option5>5</option></select></td>";
					} else if ($vals == "Yards") {
						$tabIndex = ($x*3)+1;
						$row[] = "<td><div id='divyards$x' class='form-group addCourseHoleInput'><input type='input' id='yards$x' name='yards$x' class='form-control yardsInput' value='".$this->_generateDefault($x, "yards")."' tabindex='$tabIndex' /></di></td>";
					} else if ($vals == "SI") {
						$tabIndex = ($x*3)+2;
						$row[] = "<td><div id='divsi$x' class='form-group addCourseHoleInput'><input type='number' id='si$x' name='si$x' class='form-control siInput' value='".$this->_generateDefault($x, "si")."' tabindex='$tabIndex' max='18' min='1' /></div></td>";
					}
				}
				$row[] = "</tr>";
			}
			
			if ($nine == "Front 9") {
				$row[] = "<tr><td colspan='10'>&nbsp;</td></tr>";
			}
		}
		
		if ($row) {
			return "<table class='courseCreatorTable'>".implode($row)."</table>";
		}
	}
	
	//function to edit the course
	public function editCourse()
	{
		$this->_generateDefaultValues();
		return $this->generateScoreCard();
	}
	
	//function to generate any default hole values
	private function _generateDefaultValues()
	{
		$holeArgs = array (
			'course' => $this->getCourse(),
			'tees' => $this->getTee(),
		);
		
		$courseDb = new courseController();
		$info = $courseDb->returnHolesForCourseAndTee($holeArgs);
		
		if ($info['res']) {
			foreach ($info['res'] as $vals) {
				$newArray[$vals['hole']] = array (
					'par' => $vals['par'],
					'yards' => $vals['yards'],
					'si' => $vals['si'],
				);
			}
			
			$this->setHoleDefaults($newArray);
		}
	}
	
	//function to return a default for an area
	private function _generateDefault($hole, $type)
	{
		$holeDefaults = $this->getHoleDefaults();
		
		return $holeDefaults[$hole][$type];
	}
	
	public function generateAddCourseBasicInfo()
	{
		return $this->_generateCourseInfoForm();
	}
	
	//function to get the edit course info
	public function generateEditCourseBasicInfo()
	{
		$courseArgs = array (
			'name' => $this->getCourse(),
		);
	
		$courseDb = new courseController();
		$courseInfo = $courseDb->returnCourseInfo ($courseArgs);
		$this->setCourseDefaults($courseInfo['res'][0]);
		
		$teeArgs = array (
			'course' => $this->getCourse(),
			'tee' => $this->getTee(),
		);
		
		$teeInfo = $courseDb->returnTeeInfo($teeArgs);
		$this->setTeeDefaults($teeInfo['res'][0]);
		
		return $this->_generateCourseInfoForm();
	}
	
	//function to geneare the course info form
	private function _generateCourseInfoForm()
	{
		$courseDefaults = $this->getCourseDefaults();
		$teeDefaults = $this->getTeeDefaults();
		$name = $courseDefaults['label'];
		$location = $courseDefaults['location'];
		$website = $courseDefaults['website'];
		$phoneno = $courseDefaults['phonenno'];
		$tee = $teeDefaults['teelabel'];		
		$ss = $teeDefaults['ss'];
		
		return<<<EOHTML
		<div id="courseNameForm" class="form-group">
			<label for="courseName" class="col-sm-2 control-label">Course Name:</label>
			<div class="col-sm-5">
				<input type='input' class='form-control' id='courseName' name='courseName' value='$name' placeholder='Enter Course Name' />
			</div>
			<div id="hiddenCourseNameDiv" class="col-sm-5">
				You must enter a course name.
			</div>
		</div>
			
		<div id="courseLocationForm" class="form-group">
			<label for="courseLocation" class="col-sm-2 control-label">Course Location:</label>
			<div class="col-sm-5">
				<input type='input' class='form-control' id='courseLocation' name='courseLocation' value='$location' placeholder='Enter Course Location' />
			</div>
			<div id="hiddenCourseLocationDiv" class="col-sm-5">
				You must enter a course location.
			</div>
		</div>
		
		<div id="courseTeeForm" class="form-group">
			<label for="courseTee" class="col-sm-2 control-label">Course Tee:</label>
			<div class="col-sm-5">
				<input type='input' class='form-control' id='courseTee' name='courseTee' value='$tee' placeholder='Enter Course Tee' />
			</div>
			<div id="hiddenCourseTeeDiv" class="col-sm-5">
				You must enter a course tee.
			</div>
		</div>
		
		<div id="courseSSSForm" class="form-group">
			<label for="courseSSS" class="col-sm-2 control-label">Tee SSS:</label>
			<div class="col-sm-5">
				<input type='input' class='form-control' id='courseSSS' name='courseSSS' value='$ss' placeholder='Enter Course SSS' />
			</div>
		</div>
		
		<div id="courseWebsiteForm" class="form-group">
			<label for="courseWebsite" class="col-sm-2 control-label">Website:</label>
			<div class="col-sm-5">
				<input type='input' class='form-control' id='courseWebsite' name='courseWebsite' value='$website' placeholder='Enter Course Website' />
			</div>
		</div>
		
		<div id="coursePhoneNoForm" class="form-group">
			<label for="coursePhoneNo" class="col-sm-2 control-label">Phone Number:</label>
			<div class="col-sm-5">
				<input type='input' class='form-control' id='coursePhoneNo' name='coursePhoneNo' value='$phoneno' placeholder='Enter Course Phone Number' />
			</div>
		</div>
EOHTML;
	}
}

?>
