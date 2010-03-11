package obj
{
	import flash.events.Event;

	public class eventCoorde extends Event
	{
		public var xCoorde:Number= new Number();
		public var yCoorde:Number= new Number();
		public var label:String="";
		public function eventCoorde(type:String, x:String,y:String,label:String)
		{
			xCoorde=parseFloat(x);
			yCoorde=parseFloat(y);
			this.label=label;
			super(type, bubbles, cancelable);
		}
		
	}
}