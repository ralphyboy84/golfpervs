<?php

require_once ("userController.class.php");

class user
{
	private $username;
	
	public function setUsername($val)
	{
		$this->username = $val;
	}
	
	
	public function getUsername()
	{
		return $this->username;
	}
	
	private function _getUserDetails()
	{
		$uc = new userController();
		$userInfo = $uc->returnUserInfo($this->getUsername());
		
		if ($userInfo['res']) {
			return $userInfo['res'];
		}
	}
	
	
	public function getUserInfoForPageHeader()
	{
		$userInfo = $this->_getUserDetails();
		
		if ($userInfo) {
			return $userInfo[0]['forename']." ".$userInfo[0]['surname'];
		}
	}
    
    public function getUserInfoTable()
    {
        $userInfo = $this->_getUserDetails();
        
        $forename = $userInfo[0]['forename'];
        $surname = $userInfo[0]['surname'];
        $email = $userInfo[0]['email'];
        $notifs = $userInfo[0]['receivenotifs'];
        
        if ($notifs == 1) {
            $notifSelect = "selected='selected'";   
        }
        
        if ($userInfo) {
            return<<<EOHTML
            <form class="form-horizontal" id='userprofile' method='POST'>					
                <div class="form-group">
                    <label for="selectCourse" class="col-sm-2 control-label">Forename</label>
                    <div class="col-sm-5">
                        <input type='input' class='form-control disabled' id='forename' name='forename' value='$forename' readonly='readonly' />
                    </div>
                </div>  
                <div class="form-group">
                    <label for="selectCourse" class="col-sm-2 control-label">Surname</label>
                    <div class="col-sm-5">
                        <input type='input' class='form-control disabled' id='surname' name='surname' value='$surname' readonly='readonly' />
                    </div>
                </div> 
                <div class="form-group">
                    <label for="selectCourse" class="col-sm-2 control-label">Email Address</label>
                    <div class="col-sm-5">
                        <input type='input' class='form-control disabled' id='email' name='email' value='$email' readonly='readonly' />
                    </div>
                </div>
                <div class="form-group">
                    <label for="selectCourse" class="col-sm-2 control-label">Receive Notifications</label>
                    <div class="col-sm-5">
                        <select id='receivenotifs' name='receivenotifs' class='form-control disabled' disabled='disabled'>
                            <option value='0'>YES</option>
                            <option value='1' $notifSelect>NO</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" id="editbuttondiv">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button id="editprofilebutton" type="button" class="btn btn-primary">Edit Profile</button>
                    </div>
                </div>
                <div class="form-group" id="savebuttondiv">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button id="updateprofilebutton" type="button" class="btn btn-primary">Update Profile</button>
                    </div>
                </div>
            </form>
EOHTML;
        }
    }
    
    public function returnAllUserInfo()
    {
        $userInfo = $this->_getUserDetails();
        
        return $userInfo[0];
    }
}

?>