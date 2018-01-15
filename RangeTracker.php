<?php
class Range{   #begin class range
        public $low;
        public $high;
        function __construct($lowx,$lowy,$highx,$highy){
                $this->low=array($lowx,$lowy);
                $this->high=array($highx,$highy);
        }
} #end class range



class RangeTracker extends SplDoublyLinkedList #begin class rangetracker
{
        private $list;

       public function __construct()
        {
                $this->list = new SplDoublyLinkedList;

        }
	public function addRange($range) #begin function addRange
	{
	       	$x1 = $range->low[0];
               	$x2 = $range->high[0];
             	$y1 = $range->low[1];
               	$y2 = $range->high[1];

		print "\nHERE in addRange:x1,y1,x2,y1: $x1,$y1,$x2,$y2 \n";
		$x=$range->low[0];
		print "\nthisInRange:".$this->inRange($range)."\n";
		if($this->inRange($range)==true)
		{
			return 0;
		}
        	if($this->list->isEmpty())
        	{
                	$this->list->push($range);
        	}
        	else if($x <= $this->list->offsetGet(0)->low[0])
        	{
                	$this->list->unshift($range);
        	}
        	else if($x >= $this->list->top()->low[0])  #top is the last entry
        	{
                	$this->list->push($range);
        	}
        	else{
                	$i=0;
                	while($x > $this->list->offsetGet($i)->high[0])
                	{
                        	$i++;
                	}

                	$this->list->add($i,$range);
        	}
        	return 1;
	
	} #end function addRange
      	
	public function isEmpty()
	{
		if($this->list->isEmpty())
			return true;
		else
			return false;
	}

	public function foundRange($range) #begin function foundRange. This function matches an exact range.
	{
	       	$x1 = $range->low[0];
                $x2 = $range->high[0];
                $y1 = $range->low[1];
                $y2 = $range->high[1];
                $i=0;
                if(!($this->list->isEmpty()))
                {
                        for($i=0; $i<$this->list->count(); $i++)
                        {
                                if($x1==$this->list->offsetGet($i)->low[0] && $x2==$this->list->offsetGet($i)->high[0]
                                        && $y1==$this->list->offsetGet($i)->low[1] && $y2==$this->list->offsetGet($i)->high[1])
                                        return true;				
			}
		}
		return false;


	} #end function foundRange

	public function inRange($range) #begin function inRange. The range is found even if it just overlapping
	{
		$x1 = $range->low[0];
		$x2 = $range->high[0];
		$y1 = $range->low[1];
		$y2 = $range->high[1];

		$i=0;

		if(!($this->list->isEmpty()))
		{
                       	for($i=0; $i<$this->list->count(); $i++)
                        {
				if($x1==$this->list->offsetGet($i)->low[0] && $x2==$this->list->offsetGet($i)->high[0] 
					&& $y1==$this->list->offsetGet($i)->low[1] && $y2==$this->list->offsetGet($i)->high[1])
					return true;

				else if($x1 == $x2)
				{
					if($x1 >= $this->list->offsetGet($i)->low[0] && $x1 <= $this->list->offsetGet($i)->high[0])
					{
						if($this->list->offsetGet($i)->low[0] == $this->list->offsetGet($i)->high[0])
						{
							if(($y1 >= $this->list->offsetGet($i)->low[1] && $y1 <= $this->list->offsetGet($i)->high[1]) ||
								($y2 >= $this->list->offsetGet($i)->low[1] && $y2 <= $this->list->offsetGet($i)->high[1]))
							{
								return true;
							}
						}
						else
						{
							if($this->list->offsetGet($i)->high[0] >= $x1 && $this->list->offsetGet($i)->low[0] <= $x1)
							{
								if($y1 <= $this->list->offsetGet($i)->high[1] && $y2 >= $this->list->offsetGet($i)->high[1])
								{
									return true;
								}
							}
						}	
					}

				}
				else if($y1==$y2)
				{
                                        if($y1 >= $this->list->offsetGet($i)->low[1] && $y1 <= $this->list->offsetGet($i)->high[1])
                                        {
                                                if($this->list->offsetGet($i)->low[1] == $this->list->offsetGet($i)->high[1])
                                                {
                                                        if(($x1 >= $this->list->offsetGet($i)->low[0] && $y1 <= $this->list->offsetGet($i)->high[0]) ||
                                                                ($x2 >= $this->list->offsetGet($i)->low[0] && $y2 <= $this->list->offsetGet($i)->high[0]))
                                                        {
                                                                return true;
                                                        }
                                                }
                                                else
                                                {
                                                        if($this->list->offsetGet($i)->high[1] >= $y1 && $this->list->offsetGet($i)->low[1] <= $y1)
                                                        {
                                                                if($x1 <= $this->list->offsetGet($i)->high[0] && $x2 >= $this->list->offsetGet($i)->high[0])
                                                                {
                                                                        return true;
                                                                }
                                                        }
                                                }
                                        }
                                }

			}			
		}

        	return false;
	} #end function inRange

	public function pointRange($x,$y)
	{
                if(!($this->list->isEmpty()))
                {
                        for($i=0; $i<$this->list->count(); $i++)
                        {
                                if($x>=$this->list->offsetGet($i)->low[0] && $x<=$this->list->offsetGet($i)->high[0]
                                        && $y>=$this->list->offsetGet($i)->low[1] && $y<=$this->list->offsetGet($i)->high[1])
                                        return true;
                        }
                }
                return false;

		
	}
	public function deleteRange($range) #begin function deleteRange
	{
                $x1 = $range->low[0];
                $x2 = $range->high[0];
                $y1 = $range->low[1];
                $y2 = $range->high[1];
		$i=0;
                while($i < $this->list->count() && $x1 != $this->list->offsetGet($i)->low[0])
                {
                        $i++;
                }
                while($i < $this->list->count() && $x1 == $this->list->offsetGet($i)->low[0])
                {
                        if($x2 == $this->list->offsetGet($i)->high[0] && $y1 == $this->list->offsetGet($i)->low[1] && $y2 == $this->list->offsetGet($i)->high[1])
                        {
                           	$this->list->offsetUnset($i);
                        }
			$i++;
                }

	} #end function deleteRange

	public function getRanges()
	{
		$ranges="";
		for($i=0;$i<$this->list->count();$i++)
		{
                        $x1 = $this->list->offsetGet($i)->low[0];
                        $x2 = $this->list->offsetGet($i)->high[0];
                        $y1 = $this->list->offsetGet($i)->low[1];
                        $y2 = $this->list->offsetGet($i)->high[1];
   
			$ranges=$ranges."$x1,$y1,$x2,$y2:";
		}
		return $ranges;
	}

	public function displayRanges()
	{
		print "\n\nRanges:\n\t";
		
		for($i=0;$i<$this->list->count();$i++)
		{
			$x1 = $this->list->offsetGet($i)->low[0];
                	$x2 = $this->list->offsetGet($i)->high[0];
                	$y1 = $this->list->offsetGet($i)->low[1];
                	$y2 = $this->list->offsetGet($i)->high[1];
			print("$x1,$y1,$x2,$y2\n\t");
		}
	}
} #end class RangeTracker

?>
