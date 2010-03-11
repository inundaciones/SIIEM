package obj
{
	import flash.events.Event;

	public class eventCoorde extends Event
	{
		public var xCoorde:Number= new Number();
		public var yCoorde:Number= new Number();
		
		public function eventCoorde(type:String, bubbles:Boolean=false, cancelable:Boolean=false,x:String="0",y:String="0")
		{
			xCoorde=parseFloat(x);
			yCoorde=parseFloat(y);
			
			super(type, bubbles, cancelable);
		}
		
	}
}