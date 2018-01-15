<?php
require_once("RangeTracker.php");
class Ship{  #begin class ship
	private $range; #will calculate the range based on ship number and positioning
	private $positioning;
	private $shipnumber;
	private $x1,$x2,$y1,$y2;
	public function __construct($x,$y,$shipnumber,$positioning)
	{
		$this->x1 = $x;
		$this->y1 = $y;

		if($positioning == "width")
		{
			$this->x2 = $x;
			$this->y2 = $this->y1 + $shipnumber - 1;
							
		}
		else if ($positioning == "height")
		{
			$this->y2 = $y;
			$this->x2 = $this->x1 + $shipnumber - 1;	
		}
		$this->range = new Range($this->x1,$this->y1,$this->x2,$this->y2);
	}
	public function getRange()
	{
		return $this->range;
	}
} #end class ship

$myship = new Ship(1,3,3,"width");
class Board{
        public $boardgame;
	public $ranges;
        public $ships3; #there will be two 1x3 ships
        public $ships4; #there will be one 1x4 ship
        public $ships5; #there will be one 1x5 ship
        public $ships6; #there will be one 1x6 ship3

       public function __construct() #begin construct
        {
		$boardgame=array(null,null);
		$this->ranges = null;
		$this->ships3 = 0;
		$this->ships4 = 0;
		$this->ships5 = 0;
		$this->ships6 = 0;
        } #end construct

	public function setRangeTracker() #begin setRangeTracker
	{
		$this->ranges = new RangeTracker();
                for($i=0;$i<=10;$i++) #column and row 0 arejust a placeholder
                {
                        for($j=0;$j<=10;$j++)
                        {
                                $this->boardgame[$i][$j]=0;
                        }
                }
  	
			
	} #end setRangeTracker

	public function addShip($x,$y,$position, $shipnumber) #begin addShip
	{
		switch($shipnumber)
		{
			case 3:
				if($this->ships3 > 1)
					return 0;
				else
				{
					$s3 = new Ship($x,$y,$shipnumber,$position);
					$range = $s3->getRange();
					
					$success = $this->ranges->addRange($range);
					if($success==1)
						$this->ships3++;
				}
				break;
			case 4:
                                if($this->ships4 > 0)
                                        return 0;
                                else
                                {
                                        $s3 = new Ship($x,$y,$shipnumber,$position);
                                        $success = $this->ranges->addRange($s3->getRange());
                                        if($success==1)
                                                $this->ships4++;
                                }

				break;
			case 5:
                                if($this->ships5 > 0)
				{
                                        return 0;
					
				}
                                else
                                {
                                        $s3 = new Ship($x,$y,$shipnumber,$position);
                                        $success = $this->ranges->addRange($s3->getRange());
                                        if($success==1)
                                                $this->ships5=1 ;
                                }

				break;
			case 6:
                                if($this->ships6 > 0)
                                        return 0;
                                else
                                {
                                        $s3 = new Ship($x,$y,$shipnumber,$position);
                                        $success = $this->ranges->addRange($s3->getRange());
                                        if($success==1)
                                                $this->ships6++;
                                }


				break;
				
		}
		$this->ranges->displayRanges();
		return 1;
	} #end function addShip

	public function deleteShip($range) #begin function deleteShip
	{
		if($this->ranges->foundRange($range))
		{
			$this->ranges->deleteRange($range);
		}
		else return 0;
			
	} #end function deleteShip

	public function hasShip($range) #begin function hasShip
	{
		if($this->ranges->foundRange($range))
		{
			return true;
		}
		else
			return false;
	} #end function hasShip

	public function makeMove($spot,$otherboard) #begin function makeMove
	{
	     $x=$spot[0];
	     $y=$spot[1];
	    if($otherboard->ranges->pointRange($x,$y))
	    {
		$this->boardgame[$x][$y]=1;
	    }
	    else
		$this->boardgame[$x][$y]=-1;
	    #$this->boardgame[$x][$y]= blah;
	}

	public function getBoard() #begin function getBoard
	{
	     $rangestring = $this->ranges->getRanges();
	     $boardstring = "";
	     for($i=0;$i<10;$i++)
	     {
		for($j=0;$j<10;$j++)
		{
			$boardstring = $boardstring.$this->boardgame[$i][$j].":";
		}
		$boardstring = $boardstring."-";
	     }
	     $board = array("ranges"=>$rangestring,"board"=>$boardstring);
	     return $board;	
	} #end function getBoard
}

class Player extends Board{ #begin class player
	public $name;
 	public function setName($usrname) #begin construct
 	{
		$this->name = $usrname;
	}
} #end class player

?>
