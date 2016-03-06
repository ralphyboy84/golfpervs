<?php

class notifications
{
    private $_notifid;
    private $_username;
    private $_to;
    private $_from;
    private $_subject;
    private $_notification;
    private $_type;
    private $_typeid;
    private $_dbObj;
    private $_emailObj;
    private $_dateObj;
    
    public function setNotifID($val)
    {
        $this->_notifid = $val;
    }
    
    public function getNotifID()
    {
        return $this->_notifid;   
    }
    
    public function setUserName($val)
    {
        $this->_username = $val;
    }
    
    public function getUserName()
    {
        return $this->_username;   
    }
    
    public function setTo($val)
    {
        $this->_to = $val;   
    }
    
    public function getTo()
    {
        return $this->_to;   
    }
    
    public function setFrom($val)
    {
        $this->_from = $val;   
    }
    
    public function getFrom()
    {
        return $this->_from;   
    }
    
    public function setSubject($val)
    {
        $this->_subject = $val;   
    }
    
    public function getSubject()
    {
        return $this->_subject;   
    }
    
    public function setNotification($val)
    {
        $this->_notification = $val;   
    }
    
    public function getNotification()
    {
        return $this->_notification;   
    }
    
    public function setType($val)
    {
        $this->_type = $val;   
    }
    
    public function getType()
    {
        return $this->_type;   
    }
    
    public function setTypeID($val)
    {
        $this->_typeid = $val;   
    }
    
    public function getTypeID()
    {
        return $this->_typeid;   
    }
    
    public function setDbObj($val)
    {
        $this->_dbObj = $val;   
    }
    
    public function getDbObj()
    {
        return $this->_dbObj;   
    }
    
    public function setEmailObj($val)
    {
        $this->_emailObj = $val;   
    }
    
    public function getEmailObj()
    {
        return $this->_emailObj;   
    }
    
    public function setDateObj($val)
    {
        $this->_dateObj = $val;   
    }
    
    public function getDateObj()
    {
        return $this->_dateObj;   
    }
    
    public function generateNotification()
    {
        $args = array (
            'username' => $this->getTo(),
            'sentby' => $this->getFrom(),
            'subject' => $this->getSubject(),
            'notification' => $this->getNotification(),
            'type' => $this->getType()
        );
        
        $dbobj = $this->getDbObj();
        $dbobj->insertNotification($args);
    }
    
    public function getAllUnconfirmedNotifications()
    {
        $args = array (
            'username' => $this->getUserName()    
        );
        
        $dbobj = $this->getDbObj();
        $notifs = $dbobj->getUnconfirmedNotifsDB($args);
        
        return $notifs['res'];
    }
    
    public function getLast5Notifications()
    {
        $args = array (
            'username' => $this->getUserName()    
        );
        
        $dbobj = $this->getDbObj();
        $notifs = $dbobj->getLast5NotifsDB($args);
        
        return $notifs['res'];
    }
    
    public function getAllNotifications()
    {
        $args = array (
            'username' => $this->getUserName()    
        );
        
        $dbobj = $this->getDbObj();
        $notifs = $dbobj->getAllNotifsDB($args);
        
        return $notifs['res'];
    }
    
    public function getAllNotificationsTable()
    {
        $info = $this->getAllNotifications();
        $dateObj = $this->getDateObj();
        
        if ($info) {
            foreach ($info as $notif) {
                $date = explode (" ", $notif['dateentered']);
                $dbdate = $date[0];
                $date = $dateObj->formatDateFromDatabaseOther($date[0]);
                
                $row[] = "<tr class='notifrow' data-notifid='".$notif['id']."' data-type='".$notif['type']."' data-typeid='".$notif['typeid']."'><td><span class='hiddendatecol'>$dbdate</span>$date</td><td>".$notif['sentby']."</td><td>".$notif['subject']."</td></tr>";   
            }
            
            return "<table id='fullnotiftable' class='table table-bordered table-striped dataTable'>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Sent By</th>
                    <th>Notification</th>
                </tr>
            </thead>
            <tbody>
            ".implode($row)."
            </tbody>
            </table>";
        }
    }
    
    public function getSingleNotificationInfo()
    {
        $args = array (
            'id' => $this->getNotifID()    
        );
        
        $dbobj = $this->getDbObj();
        return $dbobj->getNotifFromDB($args);   
    }
    
    public function getSingleNotification()
    {
        $notifs = $this->getSingleNotificationInfo();   
        
        if ($notifs['res']) {
            $notif = $notifs['res'][0];
            
            switch ($notif['type']) {
                case "friend":
                    $display = $this->_getFriendRequestDisplay($notif);
                    break;
                case "friendaccept":
                    $display = $this->_getFriendRequestAccepted($notif);
                    break;
                case "friendconfirm":
                    $display = $this->_getFriendRequestConfirmed($notif);
                    break;
            }
        }
        
        return $display;
    }
    
    private function _getFriendRequestAccepted($args)
    {
        $info['label'] = "Friend Request Accepted";
        $info['content'] = $args['notification'];
        
        return $this->_getTemplate($info);
    }
    
    private function _getFriendRequestConfirmed($args)
    {
        $info['label'] = "Friend Request Confirmed";
        $info['content'] = $args['notification'];
        
        return $this->_getTemplate($info);
    }
    
    private function _getFriendRequestDisplay($args)
    {
        $info['label'] = "New Friend Request";
        $info['content'] = $args['notification']."<br /><br /><button id='acceptfriend' data-notifid='".$this->getNotifID()."' type='button' class='btn btn-primary'>Accept</button>";
        
        return $this->_getTemplate($info);
    }
    
    private function _getTemplate($args)
    {
        $label = $args['label'];
        $content = $args['content'];
        
        return<<<EOTHML
<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-users"></i> $label</h3>
        </div>
        <div class="box-body">
            $content
      </div><!-- /.box -->
    </div>
  </div>
</div>
EOTHML;
    }
}

?>