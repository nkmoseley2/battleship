<?php
require_once("RangeTracker.php");

######################################
#Class Ship
#           This class is used to define a basic object to represent a ship on the board
#arguments: x, the starting x cordinate the ship will be located at
#	    y, the starting y cordinate
#           shipnumber, a number coresponding to the size of the ship
#           positioning, whether the ship is to be width(lengthwize) or height(horizontal
#       
######################################
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

######################################
#Class board
#           This class is used to define a basic object to represent a boardgame. Each player will have an object of this
#           class.
#arguments: none 
#Constructor: There is a boardgame which is an array representing places the user has guessed a ship to be           
#             on the opponent's board. 0=no guess, 1=found ship space, 2=bad match
#             There is ranges, a member of the RangeClass to keep track of ships on the user's board and make
#             sure there's no overlap 
#             There are variables to only allow 2 ships of size three and one ship of size 4,5, and 6
######################################
class Board{
        public $boardgame;
	protected $ranges;
        protected $ships3; #there will be two 1x3 ships
        protected $ships4; #there will be one 1x4 ship
        protected $ships5; #there will be one 1x5 ship
        protected $ships6; #there will be one 1x6 ship3

       public function __construct() #begin construct
        {
		$boardgame=array(null,null);
		$this->ranges = null;
		$this->ships3 = 0;
		$this->ships4 = 0;
		$this->ships5 = 0;
		$this->ships6 = 0;
        } #end construct

######################################
#Function setRangeTracker
#           This function defines a new RangeTracker to be user to keep track of a user's shipss
#          
#arguments: none 
######################################

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

######################################
#Function addShip 
#           This function adds a Ship to the board 
#          
#arguments: x,y coordinates for the ship
#           position - width or height (legthwize or horizontal)
#           shipnumber - 3,4,5,6 depending on the length of the ship
######################################

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

######################################
#Function deleteShip
#           This function takes a member of the range class and finds and deletes the ship from the 
#           list of ranges
#arguments: range a member of the class Range from RangeTracker.php 
#          
######################################

	public function deleteShip($range) #begin function deleteShip
	{
		if($this->ranges->foundRange($range))
		{
			$this->ranges->deleteRange($range);
		}
		else return 0;
			
	} #end function deleteShip

######################################
#Function hasShip
#           This function tests if a ship exists 
#           
#arguments: range a member of the class Range from RangeTracker.php 
#          
######################################

	public function hasShip($range) #begin function hasShip
	{
		if($this->ranges->foundRange($range))
		{
			return true;
		}
		else
			return false;
	} #end function hasShip

######################################
#Function makeMove
#           This function makes a move on the opponents board 
#           
#arguments: spot - an array containing the x and y cordinates to make the move
#           otherboard - the opponents board, an instance of the Board (this) class
######################################

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

######################################
#Function getBoard
#           This function returns an array of the ranges and the values on the boardgame 
#           
#arguments: none
######################################

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

##################################################
#Function wonGame
#	This function determines if a game is won. 
#arguments: none
##############################################
	public function wonGame() #begin function wonGame
	{
		if($this->ranges->isEmpty())
			return true;
		else
			return false;
	} #end function wonGame
}

######################################
#Class Player
#           This class extends Board and defines a player of the boardgame 
#           
#arguments: usrname - the player's username
######################################

class Player extends Board{ #begin class player
	private $name;
 	public function setName($usrname) #begin construct
 	{
		$this->name = $usrname;
	}
	public function getName($usrname)
	{
		return $this->name;
	}
} #end class player

?>
